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
		  
		  $consulta= mysql_query("select documento_tercero 
                                          from tercero 
                                          where documento_tercero='$DOCUMENTO'");
                  $num= mysql_num_rows($consulta);
                    
		  
		  if ($num>0)
		   {
		   ?>
                    <br>
                    <TABLE border="0">
                    <TR>
		     <TD align="center"><font size="+1" face="Verdana">El Documento ya existe en el sistema</font></TD> 
	            </TR>
                    </TABLE>
                    <br>
		   <?php	
		   }
		  else
		  {  								
		     $consulta= mysql_query("select max(idtercero)+1 as id
                                             from tercero");
                     $row = mysql_fetch_array($consulta, MYSQL_BOTH);
                                                             
                     $id=$row["id"];
                     
                                          
                    $consulta= mysql_query("insert into TERCERO
			                    values('$id','$SUCURSAL','$TIPO_DOCUMENTO','$DOCUMENTO','$NOMBRES','$APELLIDOS','$DIRECCION','$TELEFONO','$TELEFONO2',
                                                       '$CELULAR','$CORREO','$USUARIO','$CLAVE',null,null,'$OBSERVACIONES')");
                        
                     $consulta= mysql_query("insert into TERCERO_TIPO
			                        values(0,$id,6)");
                        
                                
								
		 ?>
                <br>
		<font face="verdana" size="+1">Creado Vendedor Satisfactoriamente</font>
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
     
    }
   else//else de la sesion
   {
       
   denegada("SESION NO INICIADA","../../"); 
          
   }
    
   ?>
    
</BODY>
</HTML>