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
          
        <?php	
      
         cabecera($usuario,"Eliminacion Tercero","../../");  
       
         ?>
          
          <br><br>
	  <table width="100%" border="0">
          <tr>
          <td width="10%" valign="top">
          <?php 
          
	   menu_gestion("../../","menu_terceros.php");
	  
           ?>
          
          </td>  
		  
		 <td width="80%" align="center">
		 <?php
		 $consulta=  mysql_query("select documento_tercero 
		                          from tercero 
				          where documento_tercero='$documento'");	
		 
		 $num= mysql_num_rows($consulta);
	 	 
		 if ($num<1)
		  {
		  ?>
	      <TABLE align="center" border="0">
               <TR>
		 <TD align="center"><font size="+1" face="Verdana">El Tercero No Existe</font></TD> 
	       </TR>
              </TABLE>
	      <?php
		  }
		 else
		  {
		  
		  $consulta=  mysql_query("delete 
		                          from tercero 
				          where documento_tercero='$documento'");
		
		 ?>
		   <TABLE align="center" border="0">
                   <TR>
		     <TD><font size="+1" face="Verdana">Borrado Satisfactoriamente</font></TD> 
	           </TR>
                   </TABLE>
	  
		  <?php
                  }
                  ?>
		</td>
		
		<td width="10%"></td>
          </tr>
           </table>
          <br>

          <?php
             finalizar("../../");
          ?>
</body>
</html>