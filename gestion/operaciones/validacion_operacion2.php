<?php
session_start();
require("../../conexion.php");
require("../../libreria/libreria.php");
?>

<html>
<head>
<title>Sistema de Mensajeria.</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
          <br>
	  <table width="100%">
          <tr>
          <td width="10%" valign="top">
          <?php 
          
	   menu_gestion("../../","menu_operacion.php");
	  
           ?>
          
          </td>  
		  
		 <td width="80%" align="center">
		 <?php
		 $consulta=  mysql_query("select nombre_operacion 
		                          from operacion 
				          where idoperacion='$OPERACION'");	
		 
		 $num= mysql_num_rows($consulta);
	 	 
		 if ($num<1)
		  {
		  ?>
	      <TABLE align="center" border="0">
               <TR>
		 <TD align="center"><font size="+1" face="Verdana">La Operacion No Existe</font></TD> 
	       </TR>
              </TABLE>
	      <?php
		  }
		 else
		  {
		  
		  $consulta=  mysql_query("delete 
		                          from operacion 
				          where idoperacion='$OPERACION'");
		
		 ?>
		   <TABLE align="center" border="0">
           <TR>
		     <TD><font size="+1" face="Verdana">Borrado Satisfactoriamente</font></TD> 
	       </TR>
          </TABLE>
	  
		
		</td>
		
		<td width="10%"></td>
		
		</table>
		

          <?php
                  }
                  ?>
</body>
</html>