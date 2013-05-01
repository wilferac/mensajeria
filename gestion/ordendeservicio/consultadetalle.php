<?
   include("../../clases/clases.php");

   $idos = $_GET["id"];
   $numeroOS =  $_REQUEST['numero'];
   

   $os = new orden_servicio();
   $guia = new guia();
   $tercero = new tercero();
   $operacion = new operacion();

   $cond = "idorden_servicio=$idos";
   $fila = $operacion->consultarmultiple($os, $cond);

   $idorden_servicio = $fila["idorden_servicio"];
   $factura_idfactura = $fila["factura_idfactura"];
   $tercero_idcliente = $fila["tercero_idcliente"];
   $numero_orden_servicio = $fila["numero_orden_servicio"];
   $fechaentrada = $fila["fechaentrada"];
   $observacion_orden_servicio = ucfirst($fila["observacion_orden_servicio"]);
   $unidades = $fila["unidades"];
   $area_orden_servicio = $fila["area_orden_servicio"];
   $plazo_entrega_orden = $fila["plazo_entrega_orden"];
   $plazo_asignacion_orden = $fila["plazo_asignacion_orden"];

   $cond = "idtercero=$tercero_idcliente";
   $filas = $operacion->consultarmultiple($tercero, $cond);
   $nombres_tercero = $filas["nombres_tercero"];
   $apellidos_tercero = $filas["apellidos_tercero"];
   $documento_tercero = $filas["documento_tercero"];

   $dataSetini = "[";
   $dataSet = "";
   $dataSet = $dataSet . "['$documento_tercero','$nombres_tercero','$apellidos_tercero','$fechaentrada','$numero_orden_servicio','$observacion_orden_servicio','$plazo_entrega_orden','$unidades'],";
   $dataSet = substr_replace($dataSet, "];", strlen($dataSet) - 1);
   $dataSet = $dataSetini . $dataSet;


   $zona = new zona();
   //$causal_devolucion = new causal_devolucion();
   $producto = new producto();
   $ciudad = new ciudad();
   $tipo_producto = new tipo_producto();


   $dataSetini = "[";
   $dataSet2 = "";
   //$cond = "orden_servicio_idorden_servicio=$idorden_servicio";
   //++ debo hacerlo todo en una sola consulta para que sea mas rapido :D
   
   mysql_query("SET NAMES 'utf8'");

   $queryGuias = "SELECT g.`numero_guia`, g.`nombre_destinatario_guia`, g.`direccion_destinatario_guia`,
c.`nombre_ciudad` AS ciuOrigen,  d.`nombre_departamento` AS depOrigen,
c2.`nombre_ciudad` AS ciuDesti,  d2.`nombre_departamento` AS depDesti ,
eg.`nombre_causal_devolucion`, ca.`nombrecausales`,
m.`idmanifiesto`,
m.`fechaCreacion`,
t.`nombres_tercero` AS mensajero,
z.`nombre_zona`,
eg.`idcausal_devolucion` AS idEstado


FROM guia g
LEFT JOIN (SELECT gm.* FROM guia_manifiesto gm 
LEFT JOIN guia_manifiesto gm2
ON (gm.`guiId` = gm2.`guiId` AND gm.`gmId` < gm2.`gmId`)
WHERE gm2.`gmId` IS NULL  ) AS guia_mani  ON guia_mani.guiId = g.`numero_guia` 
LEFT JOIN manifiesto m ON m.`idmanifiesto` = guia_mani.`manId`
INNER JOIN ciudad c ON g.ciudad_idorigen = c.idciudad
INNER JOIN departamento d ON d.`iddepartamento` = c.`departamento_iddepartamento`
INNER JOIN ciudad c2 ON c2.`idciudad` = g.`ciudad_iddestino`
INNER JOIN departamento d2 ON d2.`iddepartamento` = c2.`departamento_iddepartamento`
INNER JOIN estadoGuia eg ON eg.`idcausal_devolucion` = g.`causal_devolucion_idcausal_devolucion`
LEFT JOIN causales ca ON  ca.`idcausales` = guia_mani.idCausal

LEFT JOIN tercero_manifiesto tm ON tm.`idmanifiesto` = m.`idmanifiesto` AND tm.`tipo` = 2
LEFT JOIN tercero t ON t.`idtercero` = tm.`idtercero`
LEFT JOIN zona z ON z.`idzona` = m.`zonamensajero`
WHERE g.`orden_servicio_idorden_servicio` = $idorden_servicio

       
";

   if (!$res = mysql_query($queryGuias))
   {
       die(mysql_error());
   }

   //$res = $guia->consultar($cond);

   $normal = 0;
   $devueltas = 0;
   $entregadas = 0;
   $enMani = 0;
   $enCiudad = 0;


//   $trayectoEspecial = 0;

   while ($filas = mysql_fetch_assoc($res))
   {
       $numero_guia = $filas["numero_guia"];
       $destinatario = $filas["nombre_destinatario_guia"];
       $direccion = $filas["direccion_destinatario_guia"];
        $direccion = eregi_replace("[\n|\r|\n\r]", ' ', $direccion);
       $ciuOrigen = $filas["ciuOrigen"];
       $depOrigen = $filas["depOrigen"];
       $ciuDesti = $filas["ciuDesti"];
       $depDesti = $filas["depDesti"];
       $estado = $filas["nombre_causal_devolucion"] . ' ' . $filas["nombrecausales"];
       $idManifiesto = $filas["idmanifiesto"];
       $fechaAsignacion = $filas["fechaCreacion"];
       $mensajero = $filas["mensajero"];
       $zona = $filas["nombre_zona"];
       $idEstado = $filas["idEstado"];





       $dataSet2 = $dataSet2 . "['$numero_guia','$destinatario','$direccion','$ciuOrigen',
'$depOrigen','$ciuDesti','$depDesti','$estado','$idManifiesto','$fechaAsignacion','$mensajero','$zona '],";

//       $nombre_tipo_producto = strtolower($nombre_tipo_producto);
       switch ($idEstado)
       {
           case 1:
               $normal++;
               break;
           case 2:
               $devueltas++;
               break;
           case 3:
               $entregadas++;
               break;
           case 4:
               $enMani++;
               break;
           case 5:
               $enCiudad++;
               break;
       }
   }
   $dataSet2 = substr_replace($dataSet2, "];", strlen($dataSet2) - 1);
   $dataSet2 = $dataSetini . $dataSet2;


   $dataSet3 = "";
   $dataSet3 = $dataSet3 . "['$normal','$devueltas','$entregadas','$enMani','$enCiudad'],";
   $dataSet3 = substr_replace($dataSet3, "];", strlen($dataSet3) - 1);
   $dataSet3 = $dataSetini . $dataSet3;





   $vacio = false;
   $vacio2 = false;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Detalle de Orden de Servicio</title>
        <style type="text/css" title="currentStyle">
            @import "../../media/css/demo_page.css";
            @import "../../media/css/demo_table.css";
            @import "../../media/media/css/TableTools.css";
        </style>
        <script type="text/javascript" charset="utf-8">
            function wo(obj)
            {
                window.open(obj, "Detalle", "location=0,toolbar=0,menubar=0,resizable=1,width=900,height=500");
                return false;
            }
        </script>
        <script type="text/javascript" language="javascript" src="../../js/jquery.js"></script>
        <script type="text/javascript" language="javascript" src="../../js/jquery.dataTables.js"></script>
        <script type="text/javascript" charset="utf-8" src="../../media/media/js/ZeroClipboard.js"></script>
        <script type="text/javascript" charset="utf-8" src="../../media/media/js/TableTools.js"></script>
        <script type="text/javascript" charset="utf-8">

            var aDataSet = <?= $dataSet ?>

            $(document).ready(function() {
                $('#dynamic').html('<table cellpadding="0" cellspacing="0"  border="0" class="display" id="example"></table>');
                $('#example').dataTable({
                    "sDom": '',
                    "aaData": aDataSet,
                    "aoColumns": [
                        {"sTitle": "Documento cliente"},
                        {"sTitle": "Nombre Cliente"},
                        {"sTitle": "Apellido Cliente"},
                        {"sTitle": "Fecha Entrada"},
                        {"sTitle": "Numero orden"},
                        {"sTitle": "Observacion"},
                        {"sTitle": "Plazo Entrega"},
                        {"sTitle": "Unidades"}

                    ]
                });
            });


            var aDataSet3 = <?= $dataSet3 ?>

            $(document).ready(function() {
                $('#dynamic3').html('<table cellpadding="0" cellspacing="0"  border="0" class="display" id="example3"></table>');
                $('#example3').dataTable({
                    "sDom": '',
                    "aaData": aDataSet3,
                    "aoColumns": [
                        {"sTitle": "Casillero"},
                        {"sTitle": "Devueltas"},
                        {"sTitle": "Entregadas"},
                        {"sTitle": "En Manifiesto"},
                        {"sTitle": "En Ciudad"}

                    ]
                });
            });


            var aDataSet2 = <?= $dataSet2 ?>

            $(document).ready(function() {
                $('#dynamic2').html('<table cellpadding="0" cellspacing="0"  border="0" class="display" id="example2"></table>');
                $('#example2').dataTable({
                "bSort": false,
                    "aaData": aDataSet2,
                    "aoColumns": [
                        {"sTitle": "Guia"},
                        {"sTitle": "Destinatario"},
                        {"sTitle": "Direccion dest."},
                        {"sTitle": "Ciudad Origen"},
                        {"sTitle": "Dpto. Origen"},
                        {"sTitle": "Ciudad Destino"},
                        {"sTitle": "Dpto. Destino"},
                        {"sTitle": "Estado"},
                        {"sTitle": "N. Manifiesto"},
                        //fecha ultimo manifiesto
                        {"sTitle": "Fecha"},
                        {"sTitle": "Mensajero"},
                        {"sTitle": "Zona"},
                    ],
                    "sDom": 'T<"clear">lfrtip', "oTableTools": {"aButtons": ["copy", "xls", {"sExtends": "pdf",
                                "sPdfOrientation": "landscape", "sPdfMessage": "Reporte"}, "print"]}});

            });
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
                Orden de Servicio N. <?=$numeroOS?>
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

                   <p>&nbsp;</p>
                   <hr>
                   <div class="full_width big">
                       Resumen de Guias
                   </div>

                   <div id="dynamic3"></div>
                   <div class="spacer"></div>

               </div>

               <p>&nbsp;</p>
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

