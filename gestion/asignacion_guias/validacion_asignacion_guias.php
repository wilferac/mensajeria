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
      
      cabecera($usuario,"Creacion Tercero","../../");  
       
      ?>
    
    <br>
    <table width="100%">
          <tr>
          		  
		  
          <td width="10%" valign="top">
          <?php 
          
	   menu_gestion("../../","menu_terceros.php");
	  
           ?>
          
          </td>
              
              
              
           <td width="80%" align="center">
		   <?php
		  for($i=$VALOR_INICIAL;$i<($VALOR_INICIAL+$CANTIDAD);$i++){
                      
		  $consulta= mysql_query("select tercero_idtercero
                                          from asignacion_guias
                                          where inicial_asignacion <= $VALOR_INICIAL 
                          and documento_tercero='$DOCUMENTO'");
                  $num= mysql_num_rows($consulta);
                                      
                  if ($num>0)
		   {
		   ?>
                    <br>
                    <TABLE border="0">
                    <TR>
		     <TD align="center"><font size="+1" face="Verdana">Existen guias asignadas en el rango.</font></TD> 
	            </TR>
                    </TABLE>
                    <br>
		   <?php                        
                   return;
		   }
                                      }

		  
		  
		  else
		  {  								
			$consulta= mysql_query("insert into TERCERO
			                        values(0,'$SUCURSAL','$TIPO_DOCUMENTO','$DOCUMENTO','$NOMBRES','$APELLIDOS','$DIRECCION','$TELEFONO','$TELEFONO2',
                                                       '$CELULAR','$CORREO','$USUARIO','$CLAVE','$OBSERVACIONES','$VENDEDOR','$COMISION')");
                                
								
		 ?>
                <br>
		<font face="verdana" size="+1">Creado Tercero Satisfactoriamente</font>
                <br><br>
		 <?php 
		   }
           ?>
		
       </td>
		
        <td width="10%" valign="top"></td>
              
		
	</table>
    <br>
    <?php 
    finalizar("../../");
   ?>
    
</BODY>
</HTML>