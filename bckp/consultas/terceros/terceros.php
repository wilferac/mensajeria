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
    
      cabecera($usuario,"Consultas","../../");  
       
      ?>
    
    <br> <br>
    <table border="0" width="100%">
     <tr>
  
     <td width="25%" valign="top">
     <?php 
        menu_gestion2("../"); 
      ?>
     </td>  
  
     <td align="center" width="50%">		
    
     <FORM  action="respuesta_tercero1.php" method="post" target="_blank" >
		 <FIELDSET>
	      <LEGEND accesskey="4"><font size="2" face="Verdana">
		   <strong>Consulta Terceros</strong></font></LEGEND><br>
		   <table border="0" align="center">
		   <tr>
		     <td>
			  
	        <input name="enviar" type="submit" value="Consultar">
		   </td>
		   </tr>
		   <tr><td height="0"><br></td></tr>
		  
		   
	       </table>	  
	   </FIELDSET>	  
	   </form>
                 
     </td>
	 
	 <td width="25%"></td>
	 
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
</body>
</html>
