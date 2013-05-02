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
          
	   menu_gestion("../../","menu_causaldev.php");
	  
           ?>
          
          </td>
                          
              
           <td width="80%" align="center">
	     <?php
		  
		 								
		$consulta= mysql_query("update causal_devolucion set
                                        nombre_causal_devolucion='$NOMBRE',
                                        observaciones_causal_devolucion='$OBSERVACIONES'
                                        where codigo_causal_devolucion='$CODIGO'");
                                         
                                        
                                
								
	      ?>
                
               <br>
		
               <font face="verdana" size="+1">Modificado Sucursal de Devolucion Satisfactoriamente</font>
	     
		 
		
       </td>
		
        <td width="10%" valign="top"></td>
              
		
	</table>
	
    
</BODY>
</HTML>