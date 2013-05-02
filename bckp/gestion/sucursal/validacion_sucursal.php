<?php
session_start();
require("../../conexion.php");
?>	
<html>
<head>
<title>Sistema de Mensajeria.</title>
</head>
<body>
	  		  
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
			$consulta= mysql_query("insert into sucursal
			                        values(0,'$CIUDAD','$CODIGO','$NOMBRE','$DIRECCION','$TELEFONO','$OBSERVACIONES')");
                                
								
		 ?>
		
		<font face="verdana" size="+1">Creado Sucursal Satisfactoriamente</font>
	     
		 <?php 
		   }
           ?>
		
       </td>
		
		<td></td>
		
		</table>
		
		<br>
    
</BODY>
</HTML>