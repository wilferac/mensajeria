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
          
	   menu_gestion("../../","menu_resolucion.php");
	  
           ?>
          
          </td>
              
              
              
           <td width="80%" align="center">
		   <?php
		  
		  $consulta= mysql_query("select nombre_resolfact 
                                          from resol_fact 
                                          where nombre_resolfact='$NOMBRE_RES'");
                  $num= mysql_num_rows($consulta);
                    
		  
		  if ($num>0)
		   {
		   ?>
		    <TABLE border="0">
                    <TR>
		     <TD align="center"><font size="+1" face="Verdana">Ya existe una Resolucion con este nombre</font></TD> 
	            </TR>
                    </TABLE>
		   <?php	
		   }
		  else
		  {  								
			$consulta= mysql_query("insert into resol_fact
			                        values(0,'$NOMBRE_RES','$NUMERO_RES','$FECHA_DESDE_RES','$FECHA_HASTA_RES','$CONSECUTIVO_RES','$SUCURSAL_RES','$PREFIJO_RES')");
                               
								
		 ?>
                <br>
		<font face="verdana" size="+1">Creado Resolucion Satisfactoriamente</font>
	     
		 <?php 
		   }
           ?>
		
       </td>
		
        <td width="10%" valign="top"></td>
              
		
	</table>
	
    
</BODY>
</HTML>