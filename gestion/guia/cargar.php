<?
  /**
   * cargar.php este archivo es el encargado de subir un archivo csv a la BD, afecta a la tabla guia y en menor medida a orde de servicio.
   * 
   */
  include ("../../param/param.php");
  include ("../../clases/clases.php");


  include '../../security/User.php';

  $objUser = unserialize($_SESSION['currentUser']);

  if ($objUser->getStatus() != 1)
  {
      echo('No Puede entrar');
      return;
  }

  function microtime_float()
  {
      list($useg, $seg) = explode(" ", microtime());
      return ((float) $useg + (float) $seg);
  }

  if (isset($_POST["cargar"]))
  {
      $tiempo_inicio = microtime_float();
      //print_r ($_POST);

      if (isset($_GET["id"]))
          $idos = $_GET["id"];
      elseif (isset($_POST["idos"]))
          $idos = $_POST["idos"];

      $rguia = $ros = $rdpe = false;

      $nombreproducto = "Masivo"; // ojo ver bien la procedencia de este campo de producto
//       $idciudadorigen = $_SESSION['datosinicio']['ciudad_idciudad'];
//       $iddepartamentoorigen = $_SESSION['datosinicio']['departamento_iddepartamento'];
//       $nombreciudadorigen = $_SESSION['datosinicio']['nombre_ciudad'];
//       $idpaisdorigen = $_SESSION['datosinicio']['pais_ciudad'];
//       $objUser = new User();

      $idciudadorigen = $objUser->getIdCiudad();
      $iddepartamentoorigen = $objUser->getIdDepartamento();
      $nombreciudadorigen = "XD";
      $idpaisdorigen = 57;

      $os = new orden_servicio();
      $cond = "idorden_servicio=$idos";
      $r = $os->consultar($cond);
      $fil = mysql_fetch_assoc($r);
      $idremitente = $fil["tercero_idcliente"];

      $uploaddir = "../../tmp/";
      $uploadfile = $uploaddir . basename($_FILES['fileguia']['name']);
      $error = $_FILES['fileguia']['error'];
      $subido = false;

      if ($error == UPLOAD_ERR_OK)
      {
          //print_r ($_FILES);
          $subido = copy($_FILES['fileguia']['tmp_name'], $uploadfile);
          if ($subido)
          {
              echo "";  // codigo viejo, preferi no mejorar esta parte. 
          }
          else
          {
              echo "Se ha producido un error con el archivo: " . $error;
              exit();
          }
          $handle = fopen($uploadfile, 'r');
          $contenido = fread($handle, filesize($uploadfile));
          fclose($handle);

          $lineas = explode("\n", $contenido);
          $camposguia = array();

          $tamanio = count($lineas) - 1;
          echo("<br>filas: $tamanio<br>");

          for ($i = 0; $i < $tamanio; $i++)
          {
//               echo("haciendo para $i con $tamanio <br>");
//               continue;
              list($ciudaddestino, $municipiodestino, $cedula, $nombre, $apellido, $direccion, $telefono, $celular, $observacion) = explode(";", $lineas[$i]);
              if ($ciudaddestino == "" || $municipiodestino == '' || $nombre == '' || $direccion == '')
              {
                  die("Error en el archivo en la linea " . ($i + 1));
              }

//echo $ciudaddestino;
              $ciudaddestino = eregi_replace("[\n|\r|\n\r]", ' ', $ciudaddestino);
              $camposguia[$i]["ciudaddestino"] = $ciudaddestino;
              //municipio es departamento... :S
              $municipiodestino = eregi_replace("[\n|\r|\n\r]", ' ', $municipiodestino);
              $camposguia[$i]["municipiodestino"] = $municipiodestino;
              $cedula = eregi_replace("[\n|\r|\n\r]", ' ', $cedula);
              $camposguia[$i]["cedula"] = $cedula;
              $nombre = eregi_replace("[\n|\r|\n\r]", ' ', $nombre);
              $camposguia[$i]["nombre"] = $nombre;
              $apellido = eregi_replace("[\n|\r|\n\r]", ' ', $apellido);
              $camposguia[$i]["apellido"] = $apellido;
              $direccion = eregi_replace("[\n|\r|\n\r]", ' ', $direccion);
              $camposguia[$i]["direccion"] = $direccion;
              $telefono = eregi_replace("[\n|\r|\n\r]", ' ', $telefono);
              $camposguia[$i]["telefono"] = $telefono;
              $celular = eregi_replace("[\n|\r|\n\r]", ' ', $celular);
              $camposguia[$i]["celular"] = $celular;
              $observacion = eregi_replace("[\n|\r|\n\r]", ' ', $observacion);
              $camposguia[$i]["observacion"] = $observacion;

              // echo($ciudaddestino."". $municipiodestino."". $cedula."". $nombre."". $apellido."". $direccion."". $telefono."". $celular."". $observacion);
//               $i++;
          }
//            return;
          /*           * ***********************************************************************
            Insercion en tablas correspondientes: Manejo de transaccionalidad
           * *********************************************************************** */
          $guia = new guia();
          $producto = new producto();
          //$detalle_producto_especial = new detalle_producto_especial();
          $operaciones = new operacion();


          $conex = new conexion();
          $qtrans = "SET AUTOCOMMIT=0;";
          $sac = $conex->ejecutar($qtrans);
          $qtrans = "BEGIN;";
          $sac = $conex->ejecutar($qtrans);



          $j = 0;
          $datosciudad = NULL;
          $idTipoProducto = NULL;

          $cantidadguias = count($camposguia);
          while ($camposguia[$j])
          {
              mysql_query("SET NAMES 'utf8'");
              $datosciudad = NULL;
              $idTipoProducto = NULL;
              //echo($camposguia[$j]["ciudaddestino"] . " ciudad $j<br />");

              $idTipoProducto = $operaciones->calcularTipoProducto($idpaisdorigen, $iddepartamentoorigen, $idciudadorigen, $camposguia[$j]["ciudaddestino"]);


              $datosciudad = datosCiudad($camposguia[$j]["ciudaddestino"], $camposguia[$j]["municipiodestino"]);


              if (!isset($idTipoProducto))
              {
                  print_r($camposguia[$j]);
                  die("<br>Error al obtener el id producto, fila: " . ' ' . ($j + 1));
              }
              if (!isset($datosciudad))
              {
                  print_r($camposguia[$j]);
                  die("<br>Error al obtener los datos de la Ciudad, fila: " . ' ' . ($j + 1));
              }

              $cond = "tipo_producto_idtipo_producto=$idTipoProducto and nombre_producto='$nombreproducto'";
              $res = $producto->consultar($cond);
              $fila = mysql_fetch_assoc($res);
              $idproducto = $fila['idproducto'];


              //$idproducto = $producto->idproducto;

              $guia->numero_guia = $camposguia[$j]['numeroguia'];
              $guia->orden_servicio_idorden_servicio = $idos;
              $guia->zona_idzona = 1;  //ojo cable updatear a la zona correpondiente
              $guia->causal_devolucion_idcausal_devolucion = 1; //1 para cuando no tiene causal de dev
              $guia->manifiesto_idmanifiesto = NULL;
              $guia->producto_idproducto = $idproducto;
              $guia->ciudad_iddestino = $datosciudad;
              $guia->valor_declarado_guia = NULL;
              $nom = $camposguia[$j]["nombre"];
              $guia->nombre_destinatario_guia = $nom . ' ' . $camposguia[$j]["apellido"];
              $guia->direccion_destinatario_guia = $camposguia[$j]["direccion"];
              $guia->telefono_destinatario_guia = $camposguia[$j]["telefono"];
              $guia->owner = $idremitente;
              $guia->peso_guia = 10;
              $guia->ciudad_idorigen = $idciudadorigen;
              $guia->tercero_idremitente = $idremitente;
              $ced = $camposguia[$j]["cedula"];

              $guia->extraDestinatario = $camposguia[$j]["observacion"];

              $queryDesti = "INSERT INTO destinatario (tipo_identificacion_destinatario,
                   documento_destinatario,nombres_destinatario,datos1, datos2)
VALUES
(1,'$ced','$nom','','') ON DUPLICATE KEY UPDATE iddestinatario=LAST_INSERT_ID(iddestinatario), tipo_identificacion_destinatario = 1";

              if (mysql_query($queryDesti))
              {
                  $idDesti = mysql_insert_id();
                  $guia->tercero_iddestinatario = $idDesti;
                  $rguia = $guia->agregar();
                  if ($rguia == false)
                  {
                      $qtrans = "ROLLBACK;";
                      $sac = $conex->ejecutar($qtrans);
                      die("error al crear las guias (Línea " . ($j + 1) . ")");
                  }
              }
              else
              {
                  $qtrans = "ROLLBACK;";
                  $sac = $conex->ejecutar($qtrans);
                  die(mysql_error() . "error al crear el destinatario (Línea " . ($j + 1) . ")");
              }

              $j++;
              if ($j == $cantidadguias)
                  break;
          }
          $stringset = " unidades=$cantidadguias+unidades ";
          $ros = $os->modificar2($stringset, $idos);
      }


      if ($rguia === true && $ros === true)
      {
          $qtrans = "COMMIT";
          $sac = $conex->ejecutar($qtrans);
          //echo "guias agregadas con exito<br>$contenido";
          $tiempo_fin = microtime_float();
          $tiempo = $tiempo_fin - $tiempo_inicio;

          echo "Tiempo empleado: " . ($tiempo);
          ?>	

          <script language="javascript" type="text/javascript">
              var mensaje = "Registro Exitoso";
              alert(mensaje);
              window.location.href = '../ordendeservicio/consulta.php';
          </script>

          <?
      }
      else
      {
          //echo "Error al agregar guias";
          ?>
          <script language="javascript" type="text/javascript">
              var mensaje = "Registro NO Exitoso";
              alert(mensaje);
              // window.location.href = '../ordendeservicio/consulta.php?mensaje=' + mensaje;
          </script>
          <?
      }

      return;
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Carga de Guía</title>
        <link rel="stylesheet" type="text/css" media="screen" href="../../media/css/screen.css" />
        <style type="text/css" title="currentStyle">
            @import "../../media/css/demo_page.css";
            @import "../../media/css/demo_table.css";
            @import "../../media/css/jquery.css";
        </style>
        <script type="text/javascript" language="javascript" src="../../js/jquery.js"></script>
        <script type="text/javascript" language="javascript" src="../../js/jquery.dataTables.js"></script>
        <script type="text/javascript" language="javascript" src="../../js/jquery.validate.js"></script> 
        <script type="text/javascript" src="../../js/jquery_003.js"></script>

        <script type="text/javascript">
            $().ready(function()
            {
                $("#idos").autocomplete("searchos.php", {
                    minChars: 0, max: 200, width: 155});
            });
        </script>
        <script language="javascript">
            parent.frames[0].document.getElementById("a1").innerHTML = "";
            parent.frames[0].document.getElementById("a2").innerHTML = "";
            parent.frames[0].document.getElementById("a3").innerHTML = "";

            parent.frames[0].document.getElementById("s1").style.visibility = "hidden";
            parent.frames[0].document.getElementById("s2").style.visibility = "hidden";


            parent.frames[0].document.getElementById("a1").innerHTML = "Principal";
            parent.frames[0].document.getElementById("a1").href = "redireccionador.php";
            parent.frames[0].document.getElementById("s1").style.visibility = "visible";

            parent.frames[0].document.getElementById("a2").innerHTML = "Procesos";
            parent.frames[0].document.getElementById("a2").href = "procesos.php";
            parent.frames[0].document.getElementById("s2").style.visibility = "visible";

            parent.frames[0].document.getElementById("a3").innerHTML = "Ver Ordenes de Servicio";
            parent.frames[0].document.getElementById("a3").href = "gestion/ordendeservicio/consulta.php";

        </script>        

    </head>
    <body id="dt_example">
        <div id="container">
<?
  $labelidos = "";
  $campoidos = "";
  $datos = false;

  if (isset($_GET["id"]) === false)
  {
      $labelidos = "<label for='idos'>Ingrese el Numero de Orden de Servicio: </label>";
      $campoidos = "<input type='text' id='idos' name='idos' size='20'>";
  }
  else
  {

      $id = $_GET["id"];
      $cons = "idorden_servicio = $id";
      $orden_servicio = new orden_servicio();
      $res = $orden_servicio->consultar($cons);
      if (mysql_num_rows($res) > 0)
      {
          $fila = mysql_fetch_array($res);
          $idtercero = $fila["tercero_idcliente"];

          $tercero = new tercero();
          $cons = "idtercero = $idtercero";
          $res = $tercero->consultar($cons);
          if (mysql_num_rows($res) > 0)
          {
              $fila = mysql_fetch_array($res);
              $nombres_tercero = $fila["nombres_tercero"];
              $apellidos_tercero = $fila["apellidos_tercero"];
              $documento_tercero = $fila["documento_tercero"];
              $datos = true;
          }
      }
  }
?>	
            <div class="full_width big">
                <p class="navegacion"><a href="../redireccionador.php"></a><a href="../gestiondelsistema.php"></a></p>Carga de Guía
            </div>

            <form name='cargaguia' method='POST' action="" enctype="multipart/form-data" >
                <table borde='0' align='center'>
                    <tr>
                        <td>
<?= $labelidos . $campoidos . "<br>" ?>
                        </td>
                    </tr>
<?
  if ($datos == true)
  {
      echo "<tr>
		<td>
			Datos cliente: " . strtoupper($nombres_tercero) . " " . strtoupper($apellidos_tercero) . " " . $documento_tercero . "
		</td>
	</tr>";
  }
?>

                    <tr>
                        <td>
                            <label for='fileguia'>Seleccione el archivo: </label>
                            <input type='file' id='fileguia' name='fileguia' ><br>
                        </td>
                    </tr>
                    <tr>
                        <td align='center'>
                            <input type=submit value='Cargar' id='cargar' name='cargar' onClick="javascript: if (document.cargaguia.fileguia.value == '')
                                return false;">
                        </td>
                    </tr>

            </form>
        </div>
    </body>
</html>


<?php

  function datosCiudad($nombreciudaddestino, $nomDep)
  {
      $SQL = "SELECT c.idciudad, c.nombre_ciudad, c.departamento_iddepartamento,  c.pais_ciudad , d.`nombre_departamento`
FROM ciudad c
INNER JOIN departamento  d ON d.`iddepartamento` = c.`departamento_iddepartamento`
WHERE LOWER( TRIM(c.nombre_ciudad)) = LOWER( TRIM('$nombreciudaddestino' ))
AND LOWER(TRIM(d.nombre_departamento))  =  LOWER( TRIM('$nomDep'))
               ";
      // echo("<br>" . $SQL . "<br>");
      $res = mysql_query($SQL);
      if ($res)
      {
          if ($fila = mysql_fetch_assoc($res))
          {
              $rta = $fila['idciudad'];

              return $rta;
          }
          else
          {
              echo("<br><br>");
              echo($SQL);
              echo("<br><br>");
              echo("<br> No se encontraron Registros Ciudad-Departamento<br>");
              return NULL;
          }
      }
      else
          echo("<br>No se pudo consultar " . mysql_error());
      return NULL;
  }
?>
