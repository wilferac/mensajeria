<?php
require("../../clases/clases.php");
?>	
<html>
<head>
<title>Sistema de Mensajeria.</title>
</head>
<body>
	  	<? $CODIGO = $_POST["CODIGO"];	 ?> 
          <table width="100%">
          <tr>
          		  
		  <td align="center">
		   <?php
		  
		  $consulta= mysql_query("select codigo_sucursal 
                                          from sucursal 
                                          where codigo_sucursal='$CODIGO'");
                  $num= mysql_num_rows($consulta);
                    
		  
		  if ($num>0)
		   {
		   ?>
		    <TABLE border="0">
                    <TR>
		     <TD align="center"><font size="+1" face="Verdana">El CODIGO ya existe en el sistema</font></TD> 
	            </TR>
                    </TABLE>
		   <?php	
		   }
		  else
		  {  
			$fila = mysql_fetch_assoc($consulta);
		
			$sucursal = new sucursal();
			//$sucursal -> idsucursal = $_POST["idsucursal"];
			$sucursal -> ciudad_idciudad = $_POST["CIUDAD"];
			$sucursal -> codigo_sucursal = $_POST["CODIGO"];
			$sucursal -> nombre_sucursal = $_POST["NOMBRE"];
			$sucursal -> direccion_sucursal = $_POST["DIRECCION"];	
			$sucursal -> telefono_sucursal = $_POST["TELEFONO"];	
			$sucursal -> observaciones_sucursal = $_POST["OBSERVACIONES"];	
			//$sucursal -> Activa = $fila["Activa"];
			
			$res = $sucursal -> agregar();							
				if ($res === true)
			{					
		 ?>			

		<script language="javascript" type="text/javascript">
		var mensaje="Registro Exitoso";
		window.location.href='consulta.php?mensaje='+mensaje;
		</script>
		<?
			}
			else
		{?>
			<script language="javascript" type="text/javascript">
		var mensaje="Registro NO Exitoso";
		window.location.href='consulta.php?mensaje='+mensaje;
		</script>
		 <?php 
		   }
 
		   }
           ?>
		
       </td>
		
		<td></td>
		
		</table>
		
		<br>
    
</BODY>
</HTML>
