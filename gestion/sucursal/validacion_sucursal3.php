<?php
require("../../clases/clases.php");
require("../../libreria/libreria.php");
?>	
<html>
<head>
<title>Sistema de Mensajeria.</title>
</head>
<body>
    <br><br>		  
          <table width="100%">
          <tr>
          <td width="10%" valign="top">
          </td>
           <td width="80%" align="center">
	     <?php

		$sucursal = new sucursal();
		$sucursal->idsucursal = $_POST["idsucursal"];
		$sucursal->ciudad_idciudad = $_POST["CIUDAD"];
		$sucursal->codigo_sucursal = $_POST["CODIGO"];	
		$sucursal->nombre_sucursal = $_POST["NOMBRE"];
		$sucursal->direccion_sucursal = $_POST["DIRECCION"];
		$sucursal->telefono_sucursal = $_POST["TELEFONO"];
		$sucursal->observaciones_sucursal = $_POST["OBSERVACIONES"];
		
		/*$consulta= mysql_query("update sucursal set
                                        ciudad_idciudad='$CIUDAD',
                                        nombre_sucursal='$NOMBRE',
                                        direccion_sucursal='$DIRECCION',
                                        telefono_sucursal='$TELEFONO',
                                        observaciones_sucursal='$OBSERVACIONES'
                                        where codigo_sucursal='$CODIGO'");
*/		
		$res = $sucursal->modificar();
		if ( mysql_affected_rows()>0 )
		{
		?>
		<script language="javascript" type="text/javascript">
		var mensaje="Actualizacion Exitosa";
		window.location.href='consulta.php?mensaje='+mensaje;
		</script>
	<?
		}
	else
	{
?>
	<script language="javascript" type="text/javascript">
		var mensaje="Actualizacion No Exitosa";
		window.location.href='consulta.php?mensaje='+mensaje;
		</script>
	
<?
	}
	      ?>
               <br>
       </td>
        <td width="10%" valign="top"></td>
	</table>
</BODY>
</HTML>
