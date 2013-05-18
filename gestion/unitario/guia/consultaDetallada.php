<?
session_start();
include "../../../param/param.php";


include ("../../../conexion/conexion.php");

include "../../../security/User.php";
include "../../../Menu.php";

$objUser = unserialize($_SESSION['currentUser']);
if ($objUser->getStatus() != 1)
{
    $operacion->redireccionar('No Puede entrar', 'index.php');
    return;
}

$idguia = $_REQUEST["idGuia"];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Detallado de Guia</title>
        <style type="text/css" title="currentStyle">
            @import "../../../media/css/demo_page.css";
            @import "../../../media/css/demo_table.css";
            @import "../../../media/media/css/TableTools.css";
        </style>

    </head>
    <body id="dt_example">
<?
$objMenu = new Menu($objUser);
$objMenu->generarMenu();
?>
        <div id="container">
            <div class="full_width big">
                <p>&nbsp;</p>
                Gu&iacute;a <?= $idguia ?></div>
            <p>&nbsp;</p>
<?
consultarTraza($idguia);

function consultarTraza($idguia)
{
    $query = "SELECT 
g.`numero_guia`,
g.`idguia`, DATE(g.`fecha`) AS guiaFecha, gm.fechaManual AS gmFechaManual, DATE(gm.`fechaDescarga`) AS gmFechaAuto, DATE(m.`fechaCreacion`) AS maniFecha,
m.`ciudadDestino`, m.`ciudadOrigen`, m.idmanifiesto, cau.nombrecausales,
eg1.idcausal_devolucion AS est1Id, eg1.nombre_causal_devolucion AS est1Nombre, 
eg2.idcausal_devolucion AS est2Id, eg2.nombre_causal_devolucion AS est2Nombre,
CONCAT(t.nombres_tercero,' ',t.apellidos_tercero) AS nomMensajero,  g.`nombre_destinatario_guia`,
g.`direccion_destinatario_guia`, g.`fecha` AS fechaGuia, CONCAT(t2.nombres_tercero,' ',t2.apellidos_tercero) AS nomRemitente
FROM guia g 
LEFT JOIN guia_manifiesto gm ON g.`numero_guia` = gm.guiId
LEFT JOIN manifiesto m ON m.`idmanifiesto` = gm.manId
INNER JOIN estadoGuia eg1 ON eg1.idcausal_devolucion = g.`causal_devolucion_idcausal_devolucion`
LEFT JOIN estadoGuia eg2 ON eg2.idcausal_devolucion = gm.idEstadoGuia
LEFT JOIN tercero_manifiesto tm ON tm.`idmanifiesto` = m.`idmanifiesto` AND tm.`tipo` = 2
LEFT JOIN tercero t ON t.idtercero = tm.`idtercero`
INNER JOIN tercero t2 ON t2.idtercero = g.`tercero_idremitente`
LEFT JOIN causales cau ON cau.idcausales = gm.`idCausal`
WHERE g.`numero_guia` = '$idguia'
ORDER BY g.idguia";

    //echo($query);
    echo("<br /><br />");
    if ($res = mysql_query($query))
    {
        $ultimaFecha = null;
        $cont = 0;
        while ($fila = mysql_fetch_assoc($res))
        {
            //aca muestro los datos :O
            if ($cont == 0)
            {
                $fechaInicial = $fila['guiaFecha'];
                $direc = $fila['direccion_destinatario_guia'];
                $remi = $fila['nomRemitente'];
                $desti = $fila['nombre_destinatario_guia'];
                echo("Fecha Entrada: " . $fechaInicial);
                echo("<br />");
                echo("<br />Remitente: " . $remi);
                echo("<br />Destinatario: " .$desti );
                echo("<br />Direccion: " . $direc);
                echo("<br />Estado Actual: " . $fila['est1Nombre']." ".$fila['nombrecausales']);
                echo("<br />Historial Guia");

                echo("<ul>");
            }
            $idmanifiesto = $fila['idmanifiesto'];
            if (!empty($idmanifiesto))
            {
                echo("<li>");
                echo("Agregado a Manifiesto: " . $fila['maniFecha']);
                $fechaEstado = !empty($fila['gmFechaManual']) ? $fila['gmFechaManual'] : $fila['gmFechaAuto'];
                echo("<br>Mensajero:" . $fila['nomMensajero']);
                echo("<br />Estado: (" . $fila['est2Nombre'] .") ".$fila['nombrecausales']. " - " . $fechaEstado);
                echo("</li>");
                $ultimaFecha = $fechaEstado;
                echo("<br />");
            }



            $cont++;
        }
        echo("<ul>");
        //$newdate = strtotime ( -strtotime ($ultimaFecha),strtotime ($fechaInicial)  ) ;
        $datetime1 = date_create($fechaInicial);
        $datetime2 = date_create($ultimaFecha);
        $interval = $datetime1->diff($datetime2);
        //$interval = date_diff($ultimaFecha, $fechaInicial);
        echo $interval->format('Tiempo De Gestion: %R%a dias');
        //$newdate = date ( 'Y-m-j' , $newdate );
        //echo("Tiempo Total: ".$newdate);
    } else
    {

        die("Error al consultar la guia " . mysql_error());
    }
}

$con->cerrar();
?>

        </div>
    </body>
</html>
