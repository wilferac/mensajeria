<?php
session_start();
require("../../conexion.php");
require("../../libreria/libreria.php");
?>	
<html>
<head>
<title>Sistema de Mensajeria.</title>
</head>
<body>
	  		  
          <table width="100%">
          <tr>
          		  
		  
          <td width="10%" valign="top">
          <?php 
          
	   menu_gestion("../../","menu_operacion.php");
	  
           ?>
          
          </td>
              
              
              
           <td width="80%" align="center">
		   
		  <?php  								
			$consulta= mysql_query("insert into OPERACION
			                        values(0,'$NOMBRE','$RUTA','$OBSERVACIONES')");
                                
								
		 ?>
                <br>
		<font face="verdana" size="+1">Creada Operacion Satisfactoriamente</font>
	     
		
		
       </td>
		
        <td width="10%" valign="top"></td>
              
		
	</table>
	
    
</BODY>
</HTML>