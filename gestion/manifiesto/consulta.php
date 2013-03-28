<?
//session_start();
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
?>

<?
   $nombres = "manifiesto";
   $vacio = true;

   $manifiesto = new manifiesto();
   $tercero = new tercero();
   $sucursal = new sucursal();
   $operacion = new operacion();

   //$cons = "SELECT * FROM guia, manifiesto WHERE guia.manifiesto_idmanifiesto=manifiesto.idmanifiesto ORDER BY guia.numero_guia";
   $cons = "SELECT idmanifiesto,num_manifiesto,tercero_idmensajero_recibe,tercero_idmensajero_entrega,manifiesto.sucursal_idsucursal,plazo_entrega_manifiesto, tercero_idaliado, t1.nombres_tercero 
       from manifiesto inner join tercero t1 on t1.idtercero = idmanifiesto 
       inner join tercero t2 on t2.idtercero= idmanifiesto";

//FROM guia, manifiesto, tercero 
//WHERE guia.manifiesto_idmanifiesto=manifiesto.idmanifiesto
//AND tercero.idtercero = manifiesto.tercero_idaliado
// group by manifiesto.idmanifiesto ORDER BY guia.numero_guia";
   $res2 = $operacion->consultar($cons);
   if (mysql_num_rows($res2) > 0)
   {
       $dataSetini = "[";
       $dataSet = "";
       while ($filas = mysql_fetch_assoc($res2))
       {

           $idmanifiesto = $filas["idmanifiesto"];
           //$factura_idfactura = ucfirst( $filas["factura_idfactura"] );
           $tercero_idmensajero_recibe = $filas["tercero_idmensajero_recibe"];
           $numero_manifiesto = $filas["num_manifiesto"];
           $tercero_idmensajero_entrega = $filas["tercero_idmensajero_entrega"];
           //$area_manifiesto = ucfirst( $filas["area_manifiesto"] );	
           $sucursal_idsucursal = $filas["sucursal_idsucursal"];
           $tercero_idaliado = $filas["tercero_idaliado"];
           $nombres_terceroali = $filas["nombres_tercero"];
           if ($tercero_idaliado == 1)
           {
               $nombres_terceroali = '-';
           }
           $plazo_entrega_manifiesto = $filas["plazo_entrega_manifiesto"];
           //$plazo_asignacion_orden = ucfirst( $filas["plazo_asignacion_orden"] );

           $cond = "idtercero = $tercero_idmensajero_recibe";
           $res = $tercero->consultar($cond);
           $fila2 = mysql_fetch_assoc($res);
           $nombres_tercero = $fila2["nombres_tercero"];
           $apellidos_tercero = $fila2["apellidos_tercero"];
           $documento_tercero = $fila2["documento_tercero"];

           $cond = "idtercero = $tercero_idmensajero_entrega";
           $res = $tercero->consultar($cond);
           $fila2 = mysql_fetch_assoc($res);
           $nombres_tercero2 = $fila2["nombres_tercero"];
           $apellidos_tercero2 = $fila2["apellidos_tercero"];
           $documento_tercero2 = $fila2["documento_tercero"];

           $cond = "idsucursal=$sucursal_idsucursal";
           $res = $sucursal->consultar($cond);
           $fila2 = mysql_fetch_assoc($res);
           $nombre_sucursal = $fila2["nombre_sucursal"];

           $linkcargar = "<a href=\'../manifiesto/actualizaredo.php?nombre=$nombres&id=$idmanifiesto\'><img src=\'../../imagenes/cargar.jpg\' /></a>";
           // hacer manifiesto count en guias con igual idmanifiesto	 
           $wrapini = $wrapfin = "";
           $wrapini = "<a target=\'_blank\' title=\'Ver detalle manifiesto: $idmanifiesto \' href=\'consultadetalle.php?nombre=$nombres&id=$idmanifiesto\' onClick=\'return(wo(this))\'>";
           $wrapfin = "</a>";

           $dataSet = $dataSet . "['$wrapini$numero_manifiesto$wrapfin','$wrapini$nombres_tercero $apellidos_tercero$wrapfin','$wrapini$nombres_tercero2 $apellidos_tercero2$wrapfin','$wrapini$nombre_sucursal$wrapfin','$wrapini$nombres_terceroali$wrapfin','$wrapini$plazo_entrega_manifiesto$wrapfin','$linkcargar'],";
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
                        {"sTitle": "Mensajero Recibe"},
                        {"sTitle": "Mensajero Entrega"},
                        {"sTitle": "Nombre Sucursal"},
                        {"sTitle": "Nombre Aliado"},
                        {"sTitle": "Plazo Entrega"},
                        {"sTitle": "Llegada a Destino"},
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
                        <a href="add.php">Crear Manifiesto</a>
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
