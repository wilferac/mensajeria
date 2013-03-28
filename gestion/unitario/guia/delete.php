<?php
/*
 * esta clase se encarga de administrar el estado de una guia.
 * el estado de una guia cambia en relacion a si fue entregado existosamente o no.
 * 
 */

session_start();
require("../../../conexion/conexion.php");
$idGuia = -1;

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    echo("WTF?");
    
    $estadoGuia=$_REQUEST["selEstadoGuia"];
    $idGuia=$_REQUEST["idGuia"];
    
    $consulta3 = "update guia set causal_devolucion_idcausal_devolucion = $estadoGuia where idguia = $idGuia";

    $results3 = mysql_query($consulta3) or die(mysql_error());
    
    echo('<script>opener.location.reload(true);
   self.close();</script>');
    return;
} else
{
    $idGuia = $_REQUEST["idGuia"];
}

$consulta = "select numero_guia, causal_devolucion_idcausal_devolucion from guia where idguia = $idGuia and causal_devolucion_idcausal_devolucion <> 3";

$results = mysql_query($consulta) or die(mysql_error());


if ($fila = mysql_fetch_assoc($results))
{
    $estadoGuia = $fila["causal_devolucion_idcausal_devolucion"];
    $numeroGuia = $fila["numero_guia"];
}

//echo($estadoGuia." ".$numeroGuia);

$consulta2 = "select * from  causal_devolucion";

$results2 = mysql_query($consulta2) or die(mysql_error());

echo('<h2>Cambiando Estado de la Guia</h2>');

echo('Numero de Guia: ' . $numeroGuia . "<br />");

echo('<form action="delete.php" method="post">');

echo('<select id="selEstadoGuia" name="selEstadoGuia">');

while ($fila = mysql_fetch_assoc($results2))
{
    $id = $fila["idcausal_devolucion"];
    $nombre = $fila["nombre_causal_devolucion"];

    echo("<option value='$id'>$nombre</option>");
}

echo('</select><br /><br />');

echo("<input type='hidden' name='idGuia' id='idGuia' value='$idGuia'>");


echo('<input type="submit" value="Enviar">');

echo('</form>');

?>

<script language="javascript" type="text/javascript">
    function cambiarEstadoGuia()
    {
        idEstado = document.getElementById("selEstadoGuia").value;
        idGuia = <?= $idGuia?>;
        
        
        alert("cambiando");
    }
</script>
