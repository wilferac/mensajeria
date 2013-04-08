<?php
session_start();
require("../../conexion.php");
require("../../libreria/libreria.php");
?>
<HTML>
<head>
<title>Sistema Mensajeria.</title>
</head>
<BODY>
	  
    
     <?php	
       if (isset($_SESSION['usuario']))
      {
      
      cabecera($usuario,"Menu Operaciones","../../");  
       
      ?>
    <br>	 
    
    <table width="100%">
	 <tr>
	 
	 <td width="25%" valign="top">
         <?php 
          menu_gestion("../../","../../menu_inicio.php"); 
         ?>
         
         </td>  	 
	 
        <td width="50%">
   		<font face="verdana" size="1">
		<table width='60%' align="center" border='0' cellpadding='0' cellspacing='4'>
        	  <tr bgcolor="#FF9933"><td align="center"><a href="crear_operacion.php" style="text-transform: none; text-decoration:none; color: #293172"><font face="Verdana" size="2"><b>Crear Operacion</b></font></a></td></Tr>
  	          <tr bgcolor="#FF9933"><td align="center"><a href="eliminar_operacion.php" style="text-transform: none; text-decoration:none; color: #293172"><font face="Verdana" size="2"><b>Eliminar Operacion</b></font></a></td></Tr>
		  <tr bgcolor="#FF9933"><td align="center"><a href="modificar_operacion.php" style="text-transform: none; text-decoration:none; color: #293172"><font face="Verdana" size="2"><b>Modificar Operacion</b></font></a></td></tr>
			  
		</TABLE></font>
		</td>
   		  
		   <td width="25%"></td>
        
		</tr>
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
