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
	  
     <?php	
       if (isset($_SESSION['usuario']))
      {
      
      cabecera($usuario,"Asiganar Operacion","../../");  
       
      ?>
    <br>
    
          <table width="100%">
          <tr>
          		  
		  
          <td width="10%" valign="top">
          <?php 
          
	   menu_gestion("../../","menu_movimiento_operacion.php");
	  
           ?>
          
          </td>
                          
           <td width="80%" align="center">
		   
		  <?php  								
			$consulta= mysql_query("insert into MOVIMIENTO_OPERACION
			                        values(0,'$OPERACION','$TERCERO')");
                                
								
		 ?>
                <br>
		<font face="verdana" size="+1">Asignada Operacion Satisfactoriamente</font>
	     
		
       </td>
		
        <td width="10%" valign="top"></td>
              
		
	</table>
    <br>
 <?php 
    
    finalizar("../../");
    
     }
   else//else de la sesion
   {
       
   denegada("SESION NO INICIADA","../../"); 
          
   }
   ?>	
    
</BODY>
</HTML>