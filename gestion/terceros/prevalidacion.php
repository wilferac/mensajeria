<?php
session_start();
include ("../../conexion/conexion.php");
sleep(1);

if($_REQUEST) {
    $DOCUMENTO = $_REQUEST['DOCUMENTO'];
	$numero = $_REQUEST['numero'];
	//print_r ($_REQUEST);
	if ($DOCUMENTO!="")
	{
    $query = "select idtercero from tercero where documento_tercero='$DOCUMENTO'";
    $results = mysql_query( $query) or die('error');

    if(mysql_num_rows($results) > 0)
        {
		 echo '<div id="nodisponible" class ="nodisponible" style="display:inline">Tercero '.$DOCUMENTO.' ya existente</div>';
    	 echo "<script>document.getElementById('DOCUMENTO$numero').value=\"\"; document.getElementById('DOCUMENTO$numero').focus();</script>";
		}
	else
		{
        echo '<div id="disponible$numero"  class ="disponible"  style="display:inline">Disponible</div>';
		//echo "<script>document.getElementById('APELLIDOS$numero').focus();</script>";
		}
	}
}
?>