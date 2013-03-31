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
      
      cabecera($usuario,"Creacion Productos","../../");  
       
      ?>
    <br>
    
          <table width="100%">
          <tr>
          		  
		  
          <td width="10%" valign="top">
          <?php 
          
	   menu_gestion("../../","menu_productos.php");
	  
           ?>
          
          </td>
                       
              
           <td width="80%" align="center">
		   
		  <?php  								
			$consulta= mysql_query("insert into PRODUCTO
			                        values(0,'$TPRODUCTO','$NOMBRE','$PORCENTAJE')");
                                
								
		 ?>
                <br>
		<font face="verdana" size="+1">Creado Producto Satisfactoriamente</font>
	     
		
		
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