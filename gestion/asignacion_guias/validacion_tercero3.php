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
      
         cabecera($usuario,"Modificacion Tercero","../../");  
       
         ?>	  
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
		  
		 								
		$consulta= mysql_query("update TERCERO set
                                        apellidos_tercero='$APELLIDOS',
                                        nombres_tercero='$NOMBRES',
                                        sucursal_idsucursal='$SUCURSAL',
                                        direccion_tercero='$DIRECCION',
                                        telefono_tercero='$TELEFONO',
                                        telefono2_tercero='$TELEFONO2',
                                        celular_tercero='$CELULAR',
                                        email_tercero='$CORREO',
                                        usuario_tercero='$USUARIO',
                                        clave_tercero='$CLAVE',
                                        observaciones_tercero='$OBSERVACIONES'
                                        where documento_tercero='$DOCUMENTO'");
              						
	      ?>
                             		
               <font face="verdana" size="+1">Modificado Tercero Satisfactoriamente</font>
	     
		
       </td>
		
        <td width="10%" valign="top"></td>
              
		
	</table>
    <br>
     <?php
             finalizar("../../");
          ?>
</BODY>
</HTML>