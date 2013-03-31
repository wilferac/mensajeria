<?php

include ("../../clases/clases.php");
include ("../../param/param.php");

if($_REQUEST) {

    $idtercero = $_REQUEST['documentotercero'];
	$idtercero = substr($idtercero,0,strpos($idtercero," -"));
	
	if ($idtercero!="")
	{
   // $query = "SELECT saldo_asignnacion,asignacion_guias.inicial_asignacion AS desde, asignacion_guias.inicial_asignacion+asignacion_guias.cantidad_asignacion as hasta, fecha_asignacion, hora_asignacion FROM tercero, asignacion_guias WHERE tercero.documento_tercero = $documentotercero AND asignacion_guias.tercero_idtercero = tercero.idtercero";
    
	 $query = "SELECT saldo_asignnacion,asignacion_guias.inicial_asignacion AS desde, asignacion_guias.inicial_asignacion+asignacion_guias.cantidad_asignacion as hasta, fecha_asignacion, hora_asignacion FROM  asignacion_guias WHERE asignacion_guias.tercero_idtercero = $idtercero";
	 
	$results = mysql_query($query);
		if ( mysql_num_rows($results) > 0 )
		{
	?>
<style type="text/css">
<!--
.Estilo4 {color: #FFFFFF; font-weight: bold; font-family: Georgia, "Times New Roman", Times, serif; }
-->
</style>
  
    <table width="400" border="1" cellpadding="0" cellspacing="0">
   <tr>
     <td height="29" colspan="4">
         <div align="center"><strong>Asignaciones</strong></div>     </td>
   </tr>
   <tr bgcolor="#0066FF">
     <td width="72"><div align="center" class="Estilo4">Desde</div></td>
     <td width="90"><div align="center" class="Estilo4">Hasta</div></td>
     <td width="113"><div align="center" class="Estilo4">Fecha y Hora</div></td>
     <td width="115" height="29"><div align="center" class="Estilo4">Disponibles</div></td>
   </tr>
    <?
	while ( $datosAsig = mysql_fetch_assoc($results) )
		{
			$desde = $datosAsig["desde"];
			$hasta = $datosAsig["hasta"];
			$fecha = $datosAsig["fecha_asignacion"];
			$hora = $datosAsig["hora_asignacion"];
			$saldo_asignnacion = $datosAsig["saldo_asignnacion"];
			
			echo "<tr align='center'>
				   <td>$desde</td>
				  <td>$hasta</td>
				  <td>$fecha $hora</td>
				  <td>$saldo_asignnacion</td>
				  </tr>
				  ";

		} //fin while
		?>
     
	</table>
	<?	
	  }  // if mysql_num_rows($results) 
	  else
	   {
	   		echo '<div id=\'nodisponible\' class =\'nodisponible\' style=\'display:inline\'>Sin Gu&iacute;as asignadas</div>';
	   }
	} // if documentotercero != ""
} // if REQUEST
?>
