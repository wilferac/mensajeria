<?
  session_start();
  include("../../clases/clases.php");

//la primera consulta
  $idmanifiesto = $_GET["id"];

  $cons = "SELECT m.estado, s.nombre_sucursal ,m.idmanifiesto ,m.sucursal_idsucursal, m.plazo_entrega_manifiesto, GROUP_CONCAT(t.apellidos_tercero SEPARATOR ', ') AS apellidos,  GROUP_CONCAT(t.nombres_tercero SEPARATOR ',') AS tercero, GROUP_CONCAT(tm.tipo SEPARATOR ',')  AS tipo
       FROM manifiesto m INNER JOIN tercero_manifiesto tm ON tm.idmanifiesto = m.idmanifiesto 
       INNER JOIN tercero t ON t.idtercero= tm.idtercero 
       LEFT JOIN sucursal s ON s.idsucursal = m.sucursal_idsucursal       
       where m.idmanifiesto=$idmanifiesto GROUP BY m.idmanifiesto ";

  $res2 = mysql_query($cons);

  if (mysql_num_rows($res2) > 0)
  {
      $dataSetini = "[";
      $dataSet = "";
      while ($filas = mysql_fetch_assoc($res2))
      {


          $apellidos = $filas['apellidos'];
          $numero_manifiesto = $filas['idmanifiesto'];
          $nombreTerceros = $filas['tercero'];
          $tiposTerceros = $filas['tipo'];

          $cont = 0;
          $nombre = new ArrayObject();
          $apellido = new ArrayObject();
          $tipo = "";

          $idmanifiesto = $filas['idmanifiesto'];

          do
          {
              $tipo = strtok($tiposTerceros, ',');
              $nombre[$tipo] = strtok($nombreTerceros, ',');
              $apellido[$tipo] = strtok($apellidos, ',');

              $pos = stripos($nombreTerceros, ',');
              $nombreTerceros = substr($nombreTerceros, $pos + 1);
              $pos = stripos($tiposTerceros, ',');
              $tiposTerceros = substr($tiposTerceros, $pos + 1);
              $pos = stripos($apellidos, ',');
              $apellidos = substr($apellidos, $pos + 1);



              $cont++;
          } while (strlen($tiposTerceros) > 0 && $cont < 4);

          $plazo_entrega_manifiesto = $filas['plazo_entrega_manifiesto'];
          $nombre_sucursal = $filas['nombre_sucursal'];
//           
          $estado = $filas['estado'];


          $estado = $estado == 0 ? '<font color="green">Cerrado</font>' : '<font color="red">Abierto</font>';
          $dataSet = $dataSet . "['$numero_manifiesto','$estado','$nombre[1] $apellido[1]','$nombre[3] $apellido[3]','$nombre[2] $apellido[2]','$nombre_sucursal','$nombre[4] $apellido[4]','$plazo_entrega_manifiesto'],";
      }
      $dataSet = substr_replace($dataSet, "];", strlen($dataSet) - 1);
      $dataSet = $dataSetini . $dataSet;
      $vacio = false;
  }
//la segunda consulta

  $arrayGuias = new ArrayObject();
  $cons = "SELECT idEstadoGuia,guiId FROM guia_manifiesto gm WHERE  gm.manId = $idmanifiesto";

  $results2 = mysql_query($cons) or die(mysql_error());

  $totalguias = 0;
  //$creacion = $enreparto = $entregado = $devuelta =  = 0;
  $devuelto = $entregado = $enMani = $enCiudad = 0;
  while ($fila = mysql_fetch_assoc($results2))
  {
      $totalguias++;
      //guardo los id de las guias relacionadas con el manifiesto
      $arrayGuias[$fila['guiId']] = $fila['guiId'];
      $estado = $fila['idEstadoGuia'];
      if ($estado == 2)
      {
          $devuelto++;
          continue;
      }
      if ($estado == 3)
      {
          $entregado++;
          continue;
      }
      if ($estado == 4)
      {
          $enMani++;
          continue;
      }
      if ($estado == 5)
      {
          $enCiudad++;
          continue;
      }
  }

  $dataSet3 = $dataSet3 . "['$devuelto','$entregado','$enMani','$enCiudad',$totalguias],";

  $dataSet3 = substr_replace($dataSet3, "];", strlen($dataSet3) - 1);
  $dataSet3 = $dataSetini . $dataSet3;

  //creo una tabla temporal :O
  $queryTable = "CREATE TEMPORARY TABLE guia_temp
(
      id varchar(20) unique not null
      ) ;";

  if (mysql_query($queryTable))
  {
      //inserto datos en la tabla temp para el posterior inner join
      $insert = "INSERT INTO guia_temp VALUES ";
      foreach ($arrayGuias as $id)
      {
          $insert = $insert . "('$id'),";
      }
      //quito la ultima coma
      $insert = substr($insert, 0, -1);
      if (mysql_query($insert))
      {
         // echo($insert);
      }
      else
      {
         // echo($insert);
          die("no se insertaron datos en temp: " . mysql_error());
      }
  }
  else
  {
      die("no se creo la tabla: " . mysql_error());
  }



  //consulta 3
  $query3 = "select g.idguia , g.numero_guia , g.causal_devolucion_idcausal_devolucion,
            cd.nombre_causal_devolucion, g.tercero_iddestinatario,
            c1.idciudad as  ciudad_idorigen, c1.nombre_ciudad as ciudad_nombreorigen,
            c2.idciudad as  ciudad_iddestino, c2.nombre_ciudad as ciudad_nombredestino,
            p.idproducto, p.nombre_producto, p.tipo_producto_idtipo_producto, tp.nombre_tipo_producto,
            t.idtercero , t.documento_tercero, t.nombres_tercero, t.apellidos_tercero, 
            t.direccion_tercero,
            d.documento_destinatario, d.nombres_destinatario
            from guia g 
            inner join guia_temp gtemp on gtemp.id = g.numero_guia
            inner join  tercero t on g.tercero_idremitente = t.idtercero  
            inner join ciudad c1 on c1.idciudad = g.ciudad_idorigen inner join ciudad c2 on c2.idciudad = g.ciudad_iddestino
            inner join producto p on p.idproducto = g.producto_idproducto
            inner join tipo_producto tp on tp.idtipo_producto = p.tipo_producto_idtipo_producto
            inner join estadoGuia cd on cd.idcausal_devolucion = g.causal_devolucion_idcausal_devolucion
            left join destinatario d on d.iddestinatario = g.tercero_iddestinatario
            ";

  $results3 = mysql_query($query3) or die(mysql_error());

  $dataSetini = "[";
  //$dataSet = "";

  while ($fila = mysql_fetch_assoc($results3))
  {
//                $tercero_idtercero = $datosAsig["tercero_idtercero"];
      //capturo los datos del tercero que envia
//    $idtercero = $fila["idtercero"];
      $estadoGuia = $fila["causal_devolucion_idcausal_devolucion"];
      $dniDestinatario = $fila["documento_destinatario"];
      $nomDestinatario = $fila["nombres_destinatario"];
      $estadoGuiaCausal = $fila["nombre_causal_devolucion"];
      $iddestinatario = $fila["tercero_iddestinatario"];
      if ($iddestinatario == NULL)
      {
          $dniDestinatario = "Incompleto";
          $nomDestinatario = "Incompleto";
      }

      $idGuia = $fila["idguia"];

      $numeroGuia = $fila["numero_guia"];
      $documento_tercero = $fila["documento_tercero"];
      $nombres_tercero = $fila["nombres_tercero"];
      $apellidos_tercero = $fila["apellidos_tercero"];
//    $direccion_tercero = $fila["direccion_tercero"];
//    $idTipoPro = $fila["tipo_producto_idtipo_producto"];
      $nomtp = $fila["nombre_tipo_producto"];
      //capturo los del destino y de el origen del paquete
      $nomOrigen = $fila["ciudad_nombreorigen"];
      $nomDestino = $fila["ciudad_nombredestino"];
//    $idProducto = $fila["idproducto"];
      $nomProducto = $fila["nombre_producto"];

      $dataSet2 = $dataSet2 . "['$numeroGuia','$documento_tercero','$nombres_tercero','$nomtp','$nomOrigen','$nomDestino','$dniDestinatario','$nomDestinatario','$linkeliminar'],";
  }
  
  $queryBorrar="drop table guia_temp";
  
  if(mysql_query($queryBorrar))
  {
      
  }
  else
  {
      die(mysql_error());
  }


  $dataSet2 = substr_replace($dataSet2, "];", strlen($dataSet2) - 1);
  $dataSet2 = $dataSetini . $dataSet2;



  $vacio = false;
  $vacio2 = false;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Detalle de Manifiesto</title>
        <style type="text/css" title="currentStyle">
            @import "../../media/css/demo_page.css";
            @import "../../media/css/demo_table.css";
        </style>
        <script type="text/javascript" charset="utf-8">
	
        </script>
        <script type="text/javascript" language="javascript" src="../../js/jquery.js"></script>
        <script type="text/javascript" language="javascript" src="../../js/jquery.dataTables.js"></script>
        <script type="text/javascript" charset="utf-8">
			
            var aDataSet = <?= $dataSet ?>
			
            $(document).ready(function() {
                $('#dynamic').html( '<table cellpadding="0" cellspacing="0"  border="0" class="display" id="example"></table>' );
                $('#example').dataTable( {
                    "aaData": aDataSet,
                    "aoColumns": [
                        {"sTitle": "Num. Manifiesto"},
                        {"sTitle": "Estado"},
                        {"sTitle": "Creado Por"},
                        {"sTitle": "Mensajero Recibe"},
                        {"sTitle": "Mensajero Entrega"},
                        {"sTitle": "Nombre Sucursal"},
                        {"sTitle": "Nombre Aliado"},
                        {"sTitle": "Plazo Entrega"},
                    ],
                    "sDom": 'T<"clear">t'
                } );	
            } );



            var aDataSet3 = <?= $dataSet3 ?>
			
            $(document).ready(function() {
                $('#dynamic3').html( '<table cellpadding="0" cellspacing="0"  border="0" class="display" id="example3"></table>' );
                $('#example3').dataTable( {
                    "aaData": aDataSet3,
                    "aoColumns": [
                        { "sTitle": "Devueltas" },
                        { "sTitle": "Entregas" },
                        {"sTitle": "En Reparto" },
                        {"sTitle": "Ciudad Destino" },
                        {"sTitle": "TOTAL" }
						
                    ],
                    "sDom": 'T<"clear">t'
                } );	
            } );








            var aDataSet2 = <?= $dataSet2 ?>
			
            $(document).ready(function() {
                $('#dynamic2').html( '<table cellpadding="0" cellspacing="0"  border="0" class="display" id="example2"></table>' );
                $('#example2').dataTable( {
                    "aaData": aDataSet2,
                    "aoColumns": [
                        {"sTitle": "N. Guia"},
                        {"sTitle": "Remite C.C."},
                        {"sTitle": "Remite Nombre"},
                        {"sTitle": "Tipo"},
                        {"sTitle": "Origen"},
                        {"sTitle": "Destino"},
                        {"sTitle": "Destinatario C.C."},
                        {"sTitle": "Destinatario"},
                        {"sTitle": "Estado"},
                    ]
                } );	
            } );
        </script>
    </head>
    <body id="dt_example">
        <div id="container">
            <?
              if (isset($_GET["mensaje"]))
              {
                  ?> 

                  <div class="mensaje"><?= $_GET["mensaje"] ?></div>  

                  <?
              }
            ?>
            <div class="full_width big">
                Manifiesto
            </div>
            <?
              if ($vacio)
              {
                  ?>
                  <div align="center" style="color:#FF0000">No hay datos para mostrar</div>
                  <?
              }
            ?>
            <div id="dynamic"></div>
            <div class="spacer"></div>


            <!------------------------------------------------------------------------------------------------------> 

            <div class="full_width big">
                Estados
            </div>
            <?
              if ($vacio)
              {
                  ?>
                  <div align="center" style="color:#FF0000">No hay datos para mostrar</div>
                  <?
              }
            ?>
            <div id="dynamic3"></div>
            <div class="spacer"></div>      


            <!------------------------------------------------------------------------------------------------------>
            <?
              if ($vacio2)
              {
                  ?>
                  <div align="center" style="color:#FF0000">No se pidieron Adicionales</div>
                  <?
              }
              else
              {
                  ?>   

                  <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
                  <hr>
                  <div class="full_width big">
                      Guias relacionadas
                  </div>

                  <div id="dynamic2"></div>
                  <div class="spacer"></div>
                  <?
              }
            ?>
        </div>
    </div>
</body>
</html>

