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
          
	   menu_gestion("../../","menu_sucursal.php");
	  
           ?>
          
          </td>
                          
              
           <td width="80%" align="center">
	     <?php
		  
		 								
		$consulta= mysql_query("update sucursal set
                                        ciudad_idciudad='$CIUDAD',
                                        nombre_sucursal='$NOMBRE',
                                        direccion_sucursal='$DIRECCION',
                                        telefono_sucursal='$TELEFONO',
                                        observaciones_sucursal='$OBSERVACIONES'
                                        where codigo_sucursal='$CODIGO'");
                                         
                                        
                                
								
	      ?>
                
               <br>
		
               <font face="verdana" size="+1">Modificado sucursal Satisfactoriamente</font>
	     
		 
		
       </td>
		
        <td width="10%" valign="top"></td>
              
		
	</table>
	
    
</BODY>
</HTML>