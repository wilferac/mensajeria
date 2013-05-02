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
		  
		  $consulta= mysql_query("select codigo_causal_devolucion 
                                          from causal_devolucion 
                                          where codigo_causal_devolucion='$CODIGO'");
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
			$consulta= mysql_query("insert into causal_devolucion
			                        values(0,'$CODIGO','$NOMBRE','$OBSERVACIONES')");
                                
								
		 ?>
		
		<font face="verdana" size="+1">Creado Causal de Devolucion Satisfactoriamente</font>
	     
		 <?php 
		   }
           ?>
		
       </td>
		
		<td></td>
		
		</table>
		
		<br>
    
</BODY>
</HTML>