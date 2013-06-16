<?
include ("../../../param/param.php");
include ("../../../clases/clases.php");
include '../../../security/User.php';

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

$fecha1 = $_REQUEST['fecha1'];
$fecha2 = $_REQUEST['fecha2'];
if (empty($fecha1))
{
    $fecha1 = date("Y/m/d");
}
if (empty($fecha2))
{
    $fecha2 = date("Y/m/d");
}
$fecha1c = strtotime($fecha1);
$fecha2c = strtotime($fecha2);
if ($fecha1c > $fecha2c)
{
    die("Rango Invalido");
}

$nombres = "orden_servicio";
$vacio = true;

$orden_servicio = new orden_servicio();
$tercero = new tercero();
$not = " fechaentrada is not null order by numero_orden_servicio";

$SQL = "SELECT * FROM orden_servicio os WHERE os.fechaentrada IS NOT NULL AND os.`fechaentrada` BETWEEN '$fecha1' AND '$fecha2'
ORDER BY numero_orden_servicio";

$res2 = mysql_query($SQL);

if (mysql_num_rows($res2) > 0)
{
    $dataSetini = "[";
    $dataSet = "";
    while ($filas = mysql_fetch_assoc($res2))
    {
        $id = $filas["idorden_servicio"];
        $sqlCampos = "SELECT eg.`idcausal_devolucion`, g.`numero_guia`  FROM guia g INNER JOIN estadoGuia eg ON eg.`idcausal_devolucion` = g.`causal_devolucion_idcausal_devolucion`
        WHERE g.`orden_servicio_idorden_servicio` =$id";       
        $results2 = mysql_query($sqlCampos) or die(mysql_error());

        //$totalguias = 0;
        //$creacion = $enreparto = $entregado = $devuelta =  = 0;
        $devuelto = $entregado = $enMani = $enCiudad = $casillero =0;
        while ($fila = mysql_fetch_assoc($results2))
        {
            //$totalguias++;
            //guardo los id de las guias relacionadas con el manifiesto
            //$arrayGuias[$fila['guiId']] = $fila['guiId'];
            $estado = $fila['idcausal_devolucion'];
            if ($estado == 1)
            {
                $casillero++;
                continue;
            }
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

        $linkcargar = "<a href=\'../../guia/cargar.php?nombre=$nombres&id=$id\'><img src=\'../../../imagenes/cargar.jpg\' /></a>";
        $linkimprimirguias = "<a href=\'../../print/printOrdenServicio.php?id=$id\' target=\'_blank\'>Imprimir</a>";

        if ($unidades > 0)
        {
            $wrapini = "<a target=\'_blank\' title=\'Ver detalle num. orden: $numero_orden_servicio \' href=\'../consultadetalle.php?numero=$numero_orden_servicio&id=$id\' onClick=\'return(wo(this))\'>";
            $wrapfin = "</a>";
        }
        else
            $wrapini = $wrapfin = "";

        $dataSet = $dataSet . "['$wrapini$numero_orden_servicio$wrapfin','$documento_tercero','$nombres_tercero','$apellidos_tercero','$fechaentrada','$observacion_orden_servicio','$plazo_entrega_orden','$devuelto','$entregado','$enMani','$enCiudad',$casillero,'$unidades','$linkcargar','$linkimprimirguias'],";
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
            @import "../../../media/css/demo_page.css";
            @import "../../../media/css/demo_table.css";
            @import "../../../media/media/css/TableTools.css";
        </style>
        <script type="text/javascript" language="javascript" src="../../../js/jquery.js"></script>
        <script type="text/javascript" language="javascript" src="../../../js/jquery.dataTables.js"></script>
        <script type="text/javascript" charset="utf-8" src="../../../media/media/js/ZeroClipboard.js"></script>
        <script type="text/javascript" charset="utf-8" src="../../../media/media/js/TableTools.js"></script>
        <script type="text/javascript" charset="utf-8">
            /* Data set - can contain whatever information you want */

            var aDataSet = <?= $dataSet ?>

            $(document).ready(function() {
                $('#dynamic').html('<table cellpadding="0" cellspacing="0"  border="0" class="display" id="example"></table>');
                $('#example').dataTable({
                    "aaData": aDataSet,
                    "aoColumns": [
                        {"sTitle": "Num. O.S"},
                        {"sTitle": "Documento Tercero"},
                        {"sTitle": "Nombre Cliente"},
                        {"sTitle": "Apellido Cliente"},
                        {"sTitle": "Fecha Entrada"},
                        {"sTitle": "Observacion"},
                        {"sTitle": "Plazo Entrega"},
                        {"sTitle": "Devueltas" },
                        {"sTitle": "Entregas" },
                        {"sTitle": "En Reparto" },
                        {"sTitle": "Ciudad Destino" },
                        {"sTitle": "Casillero" },
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
                <p>&nbsp;</p>
                Ordenes de Servicio
            </div>
            <p>&nbsp;</p>
            <table class="display"><tr><td>
                        <a href="../add.php">Crear Orden</a>
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
