<?php
session_start();
include("../../../clases/clases.php");

include "../../../security/User.php";


$objUser = unserialize($_SESSION['currentUser']);
//$objUser = new User();
//        echo($objUser->getStatus());
if ($objUser->getStatus() != 1)
{
    //$objUser->show();
    $operacion->redireccionar('No Puede entrar', 'index.php');
    return;
}


$fecha1 = $_REQUEST['fecha1'];
$fecha2 = $_REQUEST['fecha2'];

if(empty($fecha1))
{
    $fecha1 = date("Y/m/d");
}

if(empty($fecha2))
{
    $fecha2 = date("Y/m/d");
}

$fecha1c = strtotime($fecha1);
$fecha2c = strtotime($fecha2);
if ($fecha1c > $fecha2c)
{
    die("Rango Invalido");
}
//$producto = new producto();
//$tipo_producto = new tipo_producto();
//
//$nombres = 'producto';
//
//$vacio = true;
//
//$res2 = $producto->consultar();
//query a guia
$query2 = "SELECT os.`numero_orden_servicio` as numOrdenServ , CONCAT(term.nombres_tercero,' ',term.apellidos_tercero) AS nomMensajero,
g.idguia , g.numero_guia ,gm.`idEstadoGuia` ,g.causal_devolucion_idcausal_devolucion, g.direccion_destinatario_guia,
            cd.nombre_causal_devolucion, g.tercero_iddestinatario,
            c1.idciudad AS  ciudad_idorigen, c1.nombre_ciudad AS ciudad_nombreorigen,
            c2.idciudad AS  ciudad_iddestino, c2.nombre_ciudad AS ciudad_nombredestino,
            p.idproducto, p.nombre_producto, p.tipo_producto_idtipo_producto, tp.nombre_tipo_producto,
            t.idtercero , t.documento_tercero, t.nombres_tercero, t.apellidos_tercero, 
            t.direccion_tercero, DATE(g.fecha) AS fechaGuia, TIME(g.fecha) AS horaGuia,DATE(m.`fechaCreacion`) AS maniFecha ,gm.`manId`,
            d.documento_destinatario, g.nombre_destinatario_guia, gm.`fechaManual` , DATE(gm.`fechaDescarga`), c.nombrecausales
            FROM guia g INNER JOIN  tercero t ON g.tercero_idremitente = t.idtercero  
            INNER JOIN ciudad c1 ON c1.idciudad = g.ciudad_idorigen INNER JOIN ciudad c2 ON c2.idciudad = g.ciudad_iddestino
            INNER JOIN producto p ON p.idproducto = g.producto_idproducto
            INNER JOIN tipo_producto tp ON tp.idtipo_producto = p.tipo_producto_idtipo_producto
            INNER JOIN estadoGuia cd ON cd.idcausal_devolucion = g.causal_devolucion_idcausal_devolucion
            LEFT JOIN destinatario d ON d.iddestinatario = g.tercero_iddestinatario
            LEFT JOIN guia_manifiesto gm ON gm.`guiId` = g.`numero_guia`
            LEFT JOIN guia_manifiesto gm2 ON (gm2.`guiId` = gm.`guiId` AND gm.`gmId` < gm2.`gmId`)
            LEFT JOIN causales c ON c.idcausales = gm.`idCausal`
            LEFT JOIN manifiesto m ON m.`idmanifiesto` = gm.`manId`
            LEFT JOIN tercero_manifiesto tm ON tm.`idmanifiesto` = m.`idmanifiesto` AND tm.`tipo` = 2
            LEFT JOIN tercero term ON term.idtercero = tm.`idtercero`
            INNER JOIN orden_servicio os ON os.`idorden_servicio` = g.`orden_servicio_idorden_servicio`
            WHERE gm2.`gmId`  IS NULL AND date(g.`fecha`) BETWEEN '$fecha1' AND '$fecha2' AND g.estado = 1";

//echo($query2);
//si es usuario y no es admin
if ($objUser->checkRol("Cliente") && !$objUser->checkRol("Admin"))
{
    $id = $objUser->getId();
    $query2 = $query2 . " and  g.owner = $id ";
}


$results2 = mysql_query($query2) or die(mysql_error());

$dataSetini = "[";
$dataSet = "";

while ($fila = mysql_fetch_assoc($results2))
{
    $nomMensajero = $fila['nomMensajero'];
    $numOrdenServ = $fila['numOrdenServ'];

    $idMani = $fila['manId'];
    $fechaEstado = !empty($fila['fechaManual']) ? $fila['fechaManual'] : $fila['fechaSys'];
    $fechaEstado = !empty($fechaEstado) ? $fechaEstado : $fila['maniFecha'];

    $fecha = $fila["fechaGuia"];
    $hora = $fila["horaGuia"];
//                $tercero_idtercero = $datosAsig["tercero_idtercero"];
    //capturo los datos del tercero que envia
//    $idtercero = $fila["idtercero"];
    $estadoGuia = $fila["causal_devolucion_idcausal_devolucion"];
    $dniDestinatario = $fila["documento_destinatario"];
    $nomDestinatario = $fila["nombre_destinatario_guia"];
    $estadoGuiaCausal = $fila["nombre_causal_devolucion"];
    $causalDevolucion = $fila["nombrecausales"];
    $iddestinatario = $fila["tercero_iddestinatario"];
    if (empty($iddestinatario))
    {
        $dniDestinatario = "Incompleto";
        $nomDestinatario = "Incompleto";
    }
    //$tercero_iddestinatario= $fila["tercero_iddestinatario"];
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



    $destiDirec = $fila["direccion_destinatario_guia"];

    if ($estadoGuia != 3 && $objUser->checkRol('Admin'))
    {
        $resaltar = "";
        if ($estadoGuia == 2)
        {
            $resaltar = "color: red";
        }

        // $linkeliminar = "<button style=\"width: 70px; " . $resaltar . " \"  type=\'button\' onclick=\'abrir(\"delete.php?idGuia=$idGuia\")\'>$estadoGuiaCausal</button>";
        $linkmodificar = "<a  = href=\'../../ordendeservicio/addosunitario.php?idGuiaFill=$numeroGuia\'><img src=\'../../imagenes/modificar.jpeg\' /></a>";
    } else
    {
        // $linkeliminar = "Entregado";
        $linkmodificar = "";
    }

    $linkDetalle = "<a href=\'../../unitario/guia/consultaDetallada.php?idGuia=$numeroGuia\' target=\'_blank\' onClick=\'window.open(this.href, this.target, \'idth=500,height=500\'); return false;\'>$numeroGuia</a>";

    $imprimir = "<button type=\'button\' onclick=\'abrir(\"../../unitario/guia/printCorporativo.php?idGuia=$idGuia\")\'>Imprimir</button>";
    //$editar = "<button type=\'button\' onclick=\'abrir(\"../../ordendeservicio/addosunitario.php?idGuiaFill=$idGuia\")\'>Editar</button>";
//acumulo en el dataset
    $dataSet = $dataSet . "['$linkDetalle','$idMani','$fecha','$hora','$documento_tercero','$nombres_tercero','$nomtp','$nomOrigen','$nomDestino','$dniDestinatario','$nomDestinatario','$destiDirec','$estadoGuiaCausal','$causalDevolucion','$fechaEstado','$numOrdenServ','$nomMensajero','$imprimir','$linkmodificar'],";
}

$dataSet = substr_replace($dataSet, "];", strlen($dataSet) - 1);
$dataSet = $dataSetini . $dataSet;
//echo $dataSet;
$vacio = false;
?>



<script type="text/javascript" language="javascript" src="../../../js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../../../js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf-8" src="../../../media/media/js/ZeroClipboard.js"></script>
<script type="text/javascript" charset="utf-8" src="../../../media/media/js/TableTools.js"></script>
<script type="text/javascript" charset="utf-8">
    /* Data set - can contain whatever information you want */

    var aDataSet = <?= $dataSet ?>;

    $(document).ready(function() {
        /*
         * Support functions to provide a little bit of 'user friendlyness' to the textboxes in
         * the footer
         */
        $.fn.dataTableExt.afnFiltering.push(function(oSettings, aData, iDataIndex) {
            var checked = $('#check').is(':checked');
            //alert("entro");
            if (checked && aData[6] == "Incompleto") {
                //alert("entro");
                return true;
            }
            else if (!checked) {
                return true;
            }
            return false;
        });


        $('#dynamic').html('<table cellpadding="0" cellspacing="0"  border="0" class="display" id="example"></table>');
        var oTable = $('#example').dataTable({
            "oLanguage": {
                "sSearch": "Busqueda:"
            },
            "aaData": aDataSet,
            "aoColumns": [
                {"sTitle": "N. Guia"},
                {"sTitle": "Manifiesto"},
                {"sTitle": "Fecha"},
                {"sTitle": "Hora"},
                {"sTitle": "Remite C.C."},
                {"sTitle": "Remite Nombre"},
                {"sTitle": "Tipo"},
                {"sTitle": "Origen"},
                {"sTitle": "Destino"},
                {"sTitle": "Destinatario C.C."},
                {"sTitle": "Destinatario"},
                {"sTitle": "Dirección"},
                {"sTitle": "Estado"},
                {"sTitle": "Causal"},
                {"sTitle": "Fecha Estado"},
                {"sTitle": "Numero Orden"},
                {"sTitle": "Mensajero"},
                {"sTitle": "Imprimir"},
                {"sTitle": "Editar"}
            ],
            "sDom": 'T<"clear">lfrtip', "oTableTools": {"aButtons": ["copy", "xls", {"sExtends": "pdf",
                        "sPdfOrientation": "landscape", "sPdfMessage": "Reporte"}, "print"]}
        });

        $('#check').on("click", function(e) {
            oTable.fnDraw();
        });

    });
</script>


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
            <p>&nbsp;</p>
            Guias
        </div>
        <p>&nbsp;</p>
        <table class="display"><tr><td>
                    <a href="../../ordendeservicio/addosunitario.php">Crear Guia</a>
                </td></tr></table> 
        <?
        if ($vacio)
        {
            ?>
            <div align="center" style="color:#FF0000">No hay datos para mostrar</div>
            <?
        }
        ?>
        No Terminados: <input id="check" type="checkbox" name="option3" value="incompleto" /> 
        <div id="dynamic"></div>
        <div class="spacer"></div>
    </div>
</body>






