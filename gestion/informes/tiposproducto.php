<?
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
?>

<?
   $vacio = true;

   $operaciones = new operacion();

   $SQL = "SELECT COUNT( unidades ) AS mayor FROM  orden_servicio WHERE unidades >0";
   $res = $operaciones->consultar($SQL);
   $fila = mysql_fetch_assoc($res);
   $mayor = $fila["mayor"];

   $SQL = "SELECT COUNT( unidades ) AS igual FROM  orden_servicio WHERE unidades=0";
   $res = $operaciones->consultar($SQL);
   $fila = mysql_fetch_assoc($res);
   $igual = $fila["igual"];

   // se pueden optimizar
   $SQL = "SELECT COUNT( tipo_producto_idtipo_producto ) AS nacional FROM  producto WHERE tipo_producto_idtipo_producto=1";
   $res = $operaciones->consultar($SQL);
   $fila = mysql_fetch_assoc($res);
   $nacional = $fila["nacional"];

   $SQL = "SELECT COUNT( tipo_producto_idtipo_producto ) AS regional FROM  producto WHERE tipo_producto_idtipo_producto=2";
   $res = $operaciones->consultar($SQL);
   $fila = mysql_fetch_assoc($res);
   $regional = $fila["regional"];

   $SQL = "SELECT COUNT( tipo_producto_idtipo_producto ) AS especial FROM  producto WHERE tipo_producto_idtipo_producto=3";
   $res = $operaciones->consultar($SQL);
   $fila = mysql_fetch_assoc($res);
   $especial = $fila["especial"];

   $SQL = "SELECT COUNT( tipo_producto_idtipo_producto ) AS urbano FROM  producto WHERE tipo_producto_idtipo_producto=4";
   $res = $operaciones->consultar($SQL);
   $fila = mysql_fetch_assoc($res);
   $urbano = $fila["urbano"];

   $dataSetini = "[";
   $dataSet = "";

   //$linkcargar="<a href=\'../guia/cargar.php?nombre=$nombres&id=$id\'><img src=\'../../imagenes/cargar.jpg\' /></a>";

   $dataSet = $dataSet . "['$mayor','$igual','$nacional','$regional','$especial','$urbano'],";

   $dataSet = substr_replace($dataSet, "];", strlen($dataSet) - 1);
   $dataSet = $dataSetini . $dataSet;
   $vacio = false;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Ordenes de Servicio</title>
        <style type="text/css" title="currentStyle">
            @import "../../media/css/demo_page.css";
            @import "../../media/css/demo_table.css";
            @import "../../media/media/css/TableTools.css";
        </style>
        <script type="text/javascript" language="javascript" src="../../js/jquery.js"></script>
        <script type="text/javascript" language="javascript" src="../../js/jquery.dataTables.js"></script>
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
                        {"sTitle": "Con guias asig."},
                        {"sTitle": "Sin guias asig."},
                        {"sTitle": "T.P Nacional"},
                        {"sTitle": "T.P Regional"},
                        {"sTitle": "T.P Especial"},
                        {"sTitle": "T.P Local"}
                    ],
                    "sDom": 'T<"clear">t', "oTableTools": {"aButtons": ["copy", "xls", {"sExtends": "pdf",
                                "sPdfOrientation": "landscape", "sPdfMessage": "Reporte"}, "print"]}});
            });
        </script>
    </head>
    <body id="dt_example">
        <?
           //generar menu
           $objMenu = new Menu($objUser);
           $objMenu->generarMenu();
//		   	$operacion = new operacion();
//			 $operacion -> menu();
        ?>
        <p>&nbsp;</p><p>&nbsp;</p>
        <div id="container">
            <div class="full_width big">
                <div align=center>Informe: Tipos de Productos</div>
            </div>
            <p>&nbsp;</p>
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
