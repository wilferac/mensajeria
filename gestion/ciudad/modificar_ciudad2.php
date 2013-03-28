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
    <br><br>		  
          <table width="100%">
          <tr>
          		  
		  
          <td width="10%" valign="top">
          
          <?php 
          
	   menu_gestion("../../","menu_terceros.php");
	  
           ?>
          
          </td>
                          
              
           <td width="80%" align="center">
	     <?php
		 
		 
		$consulta= mysql_query("update ciudad set
                                        trayecto_especial_ciudad='$TRAYECTO'
                                        where idciudad='$IDCIUDAD'");
                                         
                 
                                
								
	      ?>
                
               <br>
		
               <font face="verdana" size="+1">Modificado Tercero Satisfactoriamente</font>
	     
		 
		
       </td>
		
        <td width="10%" valign="top"></td>
              
		
	</table>
	
    
</BODY>
</HTML>