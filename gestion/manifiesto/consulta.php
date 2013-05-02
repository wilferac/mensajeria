<?
  session_start();
  include ("../../param/param.php");
  include ("../../clases/clases.php");

  include '../../security/User.php';
  include ('../../Menu.php');

  $objUser = unserialize($_SESSION['currentUser']);

  if ($objUser->getStatus() != 1)
  {
      $operacion->redireccionar('No Puede entrar', 'index.php');
      return;
  }
  if (!$objUser->checkRol("Usuario"))
  {
      die("No tienes permiso");
  }





//include ("../../autenticar.php");
//$titulopagina = $_SESSION['param']['titulopagina'];
//$formname = "busqueda";
?>

<?
  $nombres = "manifiesto";
  $vacio = true;

  $manifiesto = new manifiesto();
  $tercero = new tercero();
  $sucursal = new sucursal();
  $operacion = new operacion();
  $sucur = $objUser->getIdSucursal();
  //$cons = "SELECT * FROM guia, manifiesto WHERE guia.manifiesto_idmanifiesto=manifiesto.idmanifiesto ORDER BY guia.numero_guia";
  $cons = "SELECT m.estado, s.nombre_sucursal ,m.idmanifiesto ,m.sucursal_idsucursal, m.plazo_entrega_manifiesto, GROUP_CONCAT(t.apellidos_tercero SEPARATOR ', ') AS apellidos,  GROUP_CONCAT(t.nombres_tercero SEPARATOR ',') AS tercero, GROUP_CONCAT(tm.tipo SEPARATOR ',')  AS tipo
  , date(m.fechaCreacion) as fechaMani
       FROM manifiesto m INNER JOIN tercero_manifiesto tm ON tm.idmanifiesto = m.idmanifiesto 
       INNER JOIN tercero t ON t.idtercero= tm.idtercero 
       LEFT JOIN sucursal s ON s.idsucursal = m.sucursal_idsucursal       
       


       
       GROUP BY m.idmanifiesto
       HAVING  (m.sucursal_idsucursal = $sucur OR 
EXISTS (
SELECT * FROM tercero_manifiesto tm INNER JOIN tercero t ON t.`idtercero` = tm.`idtercero` WHERE tm.`tipo` = 1 AND t.`sucursal_idsucursal` = $sucur AND tm.`idmanifiesto` =  m.`idmanifiesto`      )) ";

//FROM guia, manifiesto, tercero 
//WHERE guia.manifiesto_idmanifiesto=manifiesto.idmanifiesto
//AND tercero.idtercero = manifiesto.tercero_idaliado
// group by manifiesto.idmanifiesto ORDER BY guia.numero_guia";
  $res2 = mysql_query($cons);

  //$res2 = $operacion->consultar($cons);
  if (mysql_num_rows($res2) > 0)
  {
      $dataSetini = "[";
      $dataSet = "";
      while ($filas = mysql_fetch_assoc($res2))
      {

	$fechaMani = $filas['fechaMani'];
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
              // echo($nombreTerceros."<br />");
              // echo($tiposTerceros."<br />");
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
//           $nombres_tercero2=strtok($nombres_tercero, ',');
//           
          $estado = $filas['estado'];
          if (isset($nombre_sucursal) && $estado == 1)
          {
              $linkcargar = "<a href=del/main.php?idMani=$idmanifiesto><img src=\'../../imagenes/cargar.jpg\' /></a>";
          }
          else
          {
              $linkcargar = "";
          }
          $imprimir = "<a target=\'_blank\' title=\'imprimir manifiesto: $idmanifiesto \' href=\'../print/manifiesto/gui.php?id=$idmanifiesto\' >Imprimir</a>";

          //cambio el dato para mostrarlo :o
          $estado = $estado == 0 ? '<font color="green">Cerrado</font>' : '<font color="red">Abierto</font>';
          // hacer manifiesto count en guias con igual idmanifiesto	 
          $wrapini = $wrapfin = "";
          $wrapini = "<a target=\'_blank\' title=\'Ver detalle manifiesto: $idmanifiesto \' href=\'consultadetalle.php?nombre=$nombres&id=$idmanifiesto\' onClick=\'return(wo(this))\'>";
          $wrapfin = "</a>";

          $dataSet = $dataSet . "['$wrapini$numero_manifiesto$wrapfin','$fechaMani','$estado','$nombre[1] $apellido[1]','$nombre[3] $apellido[3]','$nombre[2] $apellido[2]','$nombre_sucursal','$nombre[4] $apellido[4]','$plazo_entrega_manifiesto','$linkcargar','$imprimir'],";
      }
      $dataSet = substr_replace($dataSet, "];", strlen($dataSet) - 1);
      $dataSet = $dataSetini . $dataSet;
      $vacio = false;
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Manifiestos</title>
        <style type="text/css" title="currentStyle">
            @import "../../media/css/demo_page.css";
            @import "../../media/css/demo_table.css";
            @import "../../media/media/css/TableTools.css";
        </style>
        <script type="text/javascript" language="javascript" src="../../js/jquery.js"></script>
        <script type="text/javascript" language="javascript" src="../../js/jquery.dataTables.js"></script>
        <script type="text/javascript" language="javascript" src="../../js/funciones.js"></script>	
        <script type="text/javascript" charset="utf-8" src="../../media/media/js/ZeroClipboard.js"></script>
        <script type="text/javascript" charset="utf-8" src="../../media/media/js/TableTools.js"></script>	
        <script type="text/javascript" charset="utf-8">
            /* Data set - can contain whatever information you want */

            var aDataSet = <?= $dataSet ?>

            $(document).ready(function() {
                $('#dynamic').html('<table cellpadding="0" cellspacing="0"  border="0" class="display" id="example"></table>');
                $('#example').dataTable({
                    "aaData": aDataSet,
                    "aoColumns": [
                        {"sTitle": "Num. Manifiesto"},
                        {"sTitle": "Fecha"},
                        {"sTitle": "Estado"},
                        {"sTitle": "Creado Por"},
                        {"sTitle": "Mensajero Recibe"},
                        {"sTitle": "Mensajero Entrega"},
                        {"sTitle": "Nombre Sucursal"},
                        {"sTitle": "Nombre Aliado"},
                        {"sTitle": "Plazo Entrega"},
                        {"sTitle": "Llegada a Destino"},
                        {"sTitle": "Imprimir"},
                    ],
                    "sDom": 'T<"clear">lfrtip', "oTableTools": {"aButtons": ["copy", "xls", {"sExtends": "pdf",
                                "sPdfOrientation": "landscape", "sPdfMessage": "Reporte"}, "print"]}});
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
        </script>         

    </head>
    <body id="dt_example">
        <?
          //generar menu
          $objMenu = new Menu($objUser);
          $objMenu->generarMenu();
//           $operacion = new operacion();
//           $operacion->menu();
        ?>
        <div id="container">
            <?
              if (isset($_GET["mensaje"]))
              {
                  ?> 

                  <div class="mensaje"><?= $_GET["mensaje"] ?></div>  

                  <?
              }
            ?>
            <p>&nbsp;</p>
            <table class="display"><tr><td>
                        <a href="add/main.php">Crear Manifiesto</a>
                    </td></tr></table>
            <br>
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
        </div>
    </body>
</html>
