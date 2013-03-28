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
        cabecera($_SESSION['usuario'],"Menu Zonas","../../");  
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
        	  <tr bgcolor="#FF9933"><td align="center"><a href="crear_zona.php" style="text-transform: none; text-decoration:none; color: #293172"><font face="Verdana" size="2"><b>Crear Zona</b></font></a></td></Tr>
  	          <tr bgcolor="#FF9933"><td align="center"><a href="eliminar_zona.php" style="text-transform: none; text-decoration:none; color: #293172"><font face="Verdana" size="2"><b>Eliminar Zona</b></font></a></td></Tr>
		  <tr bgcolor="#FF9933"><td align="center"><a href="modificar_zona.php" style="text-transform: none; text-decoration:none; color: #293172"><font face="Verdana" size="2"><b>Modificar Zona</b></font></a></td></tr>
			  
		</TABLE></font>
		</td>
   		  
		   <td width="25%"></td>
        
		</tr>
      </table>
  <br>
  
<?php 
    finalizar("../../");
 ?>

</BODY>	 
