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



//include ("../../autenticar.php");
//$titulopagina = $_SESSION['param']['titulopagina'];
//$formname = "busqueda";

   $nombres = "orden_servicio";
   $vacio = true;

   $orden_servicio = new orden_servicio();
   $tercero = new tercero();

   $res2 = $orden_servicio->consultar();
   if (mysql_num_rows($res2) > 0)
   {
       $dataSetini = "[";
       $dataSet = "";
       while ($filas = mysql_fetch_assoc($res2))
       {
           $id = $filas["idorden_servicio"];
           //$factura_idfactura = ucfirst( $filas["factura_idfactura"] );
           $tercero_idcliente = $filas["tercero_idcliente"];
           $numero_orden_servicio = $filas["numero_orden_servicio"];
           $fechaentrada = $filas["fechaentrada"];
           $observacion_orden_servicio = ucfirst($filas["observacion_orden_servicio"]);
           //$area_orden_servicio = ucfirst( $filas["area_orden_servicio"] );	
           $plazo_entrega_orden = $filas["plazo_entrega_orden"];
           $unidades = $filas["unidades"];
           //$plazo_asignacion_orden = ucfirst( $filas["plazo_asignacion_orden"] );

           $cond = "idtercero = $tercero_idcliente";
           $res = $tercero->consultar($cond);
           $fila2 = mysql_fetch_assoc($res);
           $nombres_tercero = $fila2["nombres_tercero"];
           $apellidos_tercero = $fila2["apellidos_tercero"];
           $documento_tercero = $fila2["documento_tercero"];

           $linkcargar = "<a href=\'../guia/cargar.php?nombre=$nombres&id=$id\'><img src=\'../../imagenes/cargar.jpg\' /></a>";
           $linkimprimirguias = "<a href=\'./impresiones/imprimirguia.php?id=$id\' target=\'_blank\'>Imprimir</a>";

           if ($unidades > 0)
           {
               $wrapini = "<a target=\'_blank\' title=\'Ver detalle num. orden: $numero_orden_servicio \' href=\'consultadetalle.php?nombre=$nombres&id=$id\' onClick=\'return(wo(this))\'>";
               $wrapfin = "</a>";
           }
           else
               $wrapini = $wrapfin = "";

           $dataSet = $dataSet . "['$wrapini$documento_tercero$wrapfin','$wrapini$nombres_tercero$wrapfin','$wrapini$apellidos_tercero$wrapfin','$wrapini$fechaentrada$wrapfin','$wrapini$numero_orden_servicio$wrapfin','$observacion_orden_servicio','$wrapini$plazo_entrega_orden$wrapfin','$wrapini$unidades$wrapfin','$linkcargar','$linkimprimirguias'],";
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
                        {"sTitle": "Documento Tercero"},
                        {"sTitle": "Nombre Cliente"},
                        {"sTitle": "Apellido Cliente"},
                        {"sTitle": "Fecha Entrada"},
                        {"sTitle": "Num. O.S"},
                        {"sTitle": "Observacion"},
                        {"sTitle": "Plazo Entrega"},
                        {"sTitle": "Unidades"},
                        {"sTitle": "Cargar guia"},
                        {"sTitle": "Imprimir guias"},
                    ],
                    "sDom": 'T<"clear">lfrtip', "oTableTools": {"aButtons": ["copy", "xls", {"sExtends": "pdf",
                                "sPdfOrientation": "landscape", "sPdfMessage": "Reporte"}, "print"]}});
            });

            function wo(obj)
            {
                window.open(obj, "Detalle", "scrollbars=1,location=0,toolbar=0,menubar=0,resizable=1,width=1300,height=700");
                return false;
            }
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
           $objMenu = new Menu($objUser);
           $objMenu->generarMenu();
//   $operacion = new operacion();
//   $operacion->menu();
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
            <div class="full_width big">
                <p>&nbsp;</p>
                Ordenes de Servicio
            </div>
            <p>&nbsp;</p>
            <table class="display"><tr><td>
                        <a href="add.php">Crear Orden</a>
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
