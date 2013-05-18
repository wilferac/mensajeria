<?php
session_start(); 
require("conexion.php");
?>
<HTML>
<head>
<title>Sistema Mensajeria.</title>
</head>
	<BODY>
	  
    <br>
    <br>
	 <table width="100%">
	 <tr>
	 
	 <td width="25%" valign="top">
             
         <?php 
         
        if (!isset($_SESSION['usuario']))
         { 
         ?>
             <td width="50%">
   		<font face="verdana" size="1">
		<table width='60%' align="center" border='0' cellpadding='0' cellspacing='4'>
        	 
                 <tr bgcolor="#FF9933"><td align="center"><a href="gestion/sucursal/menu_sucursal.php" style="text-transform: none; text-decoration:none; color: #293172"><font face="Verdana" size="4"><b>Sucursales</b></font></a></td></tr>
                 <tr bgcolor="#FF9933"><td align="center"><a href="gestion/terceros/menu_terceros.php" style="text-transform: none; text-decoration:none; color: #293172"><font face="Verdana" size="4"><b>Terceros</b></font></a></td></tr>
  	         <tr bgcolor="#FF9933"><td align="center"><a href="gestion/zonas/menu_zonas.php" style="text-transform: none; text-decoration:none; color: #293172"><font face="Verdana" size="4"><b>Zonas</b></font></a></td></tr>
		 <tr bgcolor="#FF9933"><td align="center"><a href="gestion/productos/menu_productos.php" style="text-transform: none; text-decoration:none; color: #293172"><font face="Verdana" size="4"><b>Productos</b></font></a></td></tr> 
                 <tr bgcolor="#FF9933"><td align="center"><a href="gestion/operaciones/menu_operacion.php" style="text-transform: none; text-decoration:none; color: #293172"><font face="Verdana" size="4"><b>Operaciones</b></font></a></td></tr> 
                 <tr bgcolor="#FF9933"><td align="center"><a href="gestion/movimiento_operacion/menu_movimiento_operacion.php" style="text-transform: none; text-decoration:none; color: #293172"><font face="Verdana" size="4"><b>Asignar Operaciones</b></font></a></td></tr> 
			  
		</table>
	   </td>
          
          <?php  
         }
         
         else
         
         {   
         
          if (!isset($_SESSION['usuario'])){
               $_SESSION['usuario']=$usuario; 
          }  
         
         if (!isset($_SESSION['password'])){
               $_SESSION['password']=$password; 
          }  
         
         $consulta=  mysql_query("select usuario_tercero,clave_tercero 
                                  from tercero 
                                  where usuario_tercero='$usuario' and clave_tercero='$password'");
         $num=  mysql_num_rows($consulta);
     
          if ($num=="false")
           {
             ?>
	       <TD width="50%" align="center"><font size="+1" face="Verdana">Usuario y/o Clave Invalida</font></TD> 
	      <?php	
           }
           else
           {
           ?>    
                      
      
         </td>  	 
	 <td width="50%">
   		<font face="verdana" size="1">
		<table width='60%' align="center" border='0' cellpadding='0' cellspacing='4'>
        	 <tr bgcolor="#FF9933"><td align="center"><a href="gestion/terceros/menu_terceros.php" style="text-transform: none; text-decoration:none; color: #293172"><font face="Verdana" size="4"><b>Terceros</b></font></a></td></tr>
  	         <tr bgcolor="#FF9933"><td align="center"><a href="gestion/zonas/menu_zonas.php" style="text-transform: none; text-decoration:none; color: #293172"><font face="Verdana" size="4"><b>Zonas</b></font></a></td></tr>
		 <tr bgcolor="#FF9933"><td align="center"><a href="gestion/productos/menu_productos.php" style="text-transform: none; text-decoration:none; color: #293172"><font face="Verdana" size="4"><b>Productos</b></font></a></td></tr> 
			  
		</TABLE></font>
		</td>
   		<?php
             }
         }
             ?>     
		   <td width="25%"></td>
        
		</tr>
      </table>
  
  

</BODY>	 
