<?
session_start();
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

$nombres = "manifiesto";
$vacio = true;

$manifiesto = new manifiesto();
$tercero = new tercero();
$sucursal = new sucursal();
$operacion = new operacion();
$sucur = $objUser->getIdSucursal();
//$cons = "SELECT * FROM guia, manifiesto WHERE guia.manifiesto_idmanifiesto=manifiesto.idmanifiesto ORDER BY guia.numero_guia";
$cons = "SELECT m.estado, s.nombre_sucursal ,m.idmanifiesto, m.fechaCierre ,m.sucursal_idsucursal, m.plazo_entrega_manifiesto, GROUP_CONCAT(t.apellidos_tercero SEPARATOR ', ') AS apellidos,  GROUP_CONCAT(t.nombres_tercero SEPARATOR ',') AS tercero, GROUP_CONCAT(tm.tipo SEPARATOR ',')  AS tipo
  , date(m.fechaCreacion) as fechaMani
       FROM manifiesto m INNER JOIN tercero_manifiesto tm ON tm.idmanifiesto = m.idmanifiesto 
       INNER JOIN tercero t ON t.idtercero= tm.idtercero 
       LEFT JOIN sucursal s ON s.idsucursal = m.sucursal_idsucursal  
       WHERE date(m.`fechaCreacion`) BETWEEN '$fecha1' AND '$fecha2'
       GROUP BY m.idmanifiesto
       HAVING  (m.sucursal_idsucursal = $sucur OR 
           EXISTS ( 
           SELECT * FROM tercero_manifiesto tm INNER JOIN tercero t ON t.`idtercero` = tm.`idtercero` 
           WHERE tm.`tipo` = 1 AND t.`sucursal_idsucursal` = $sucur AND tm.`idmanifiesto` =  m.`idmanifiesto`))";
$res2 = mysql_query($cons);

//$res2 = $operacion->consultar($cons);
if (mysql_num_rows($res2) > 0)
{
    $dataSetini = "[";
    $dataSet = "";
    while ($filas = mysql_fetch_assoc($res2))
    {
        $numero_manifiesto = $filas['idmanifiesto'];

        $cons = "SELECT idEstadoGuia,guiId FROM guia_manifiesto gm WHERE  gm.manId = $numero_manifiesto";

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
        
        $fechaMani = $filas['fechaMani'];
        $apellidos = $filas['apellidos'];
        $fechaCierre = $filas['fechaCierre'];
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
            $linkcargar = "<a href=../del/main.php?idMani=$idmanifiesto><img src=\'../../../imagenes/cargar.jpg\' /></a>";
        } else
        {
            $linkcargar = "";
        }
        $imprimir = "<a target=\'_blank\' title=\'imprimir manifiesto: $idmanifiesto \' href=\'../../print/manifiesto/gui.php?id=$idmanifiesto\' >Imprimir</a>";

        //cambio el dato para mostrarlo :o
        $estado = $estado == 0 ? '<font color="green">Cerrado</font>' : '<font color="red">Abierto</font>';
        // hacer manifiesto count en guias con igual idmanifiesto	 
        $wrapini = $wrapfin = "";
        $wrapini = "<a target=\'_blank\' title=\'Ver detalle manifiesto: $idmanifiesto \' href=\'../consultadetalle.php?nombre=$nombres&id=$idmanifiesto\' onClick=\'return(wo(this))\'>";
        $wrapfin = "</a>";

        $dataSet = $dataSet . "['$wrapini$numero_manifiesto$wrapfin','$fechaMani','$estado','$fechaCierre','$nombre[1] $apellido[1]','$nombre[3] $apellido[3]','$nombre[2] $apellido[2]','$nombre_sucursal','$nombre[4] $apellido[4]','$plazo_entrega_manifiesto','$devuelto','$entregado','$enMani','$enCiudad',$totalguias,'$linkcargar','$imprimir'],";
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
            @import "../../../media/css/demo_page.css";
            @import "../../../media/css/demo_table.css";
            @import "../../../media/media/css/TableTools.css";
        </style>
        <script type="text/javascript" language="javascript" src="../../../js/jquery.js"></script>
        <script type="text/javascript" language="javascript" src="../../../js/jquery.dataTables.js"></script>
        <script type="text/javascript" language="javascript" src="../../../js/funciones.js"></script>	
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
                        {"sTitle": "Num. Manifiesto"},
                        {"sTitle": "Fecha"},
                        {"sTitle": "Estado"},
                        {"sTitle": "Fecha Cierre"},
                        {"sTitle": "Creado Por"},
                        {"sTitle": "Mensajero Recibe"},
                        {"sTitle": "Mensajero Entrega"},
                        {"sTitle": "Nombre Sucursal"},
                        {"sTitle": "Nombre Aliado"},
                        {"sTitle": "Plazo Entrega"},
                        {"sTitle": "Devueltas" },
                        {"sTitle": "Entregas" },
                        {"sTitle": "En Reparto" },
                        {"sTitle": "Ciudad Destino" },
                        {"sTitle": "Total" },
                        {"sTitle": "Llegada a Destino"},
                        {"sTitle": "Imprimir"},
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
