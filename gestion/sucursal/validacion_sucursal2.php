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
      	$usuario = $_SESSION['datosinicio']['usuario_tercero'];
		$codigo = $_GET["documento"];
         ?>
          <br>
	  <table width="100%">
          <tr>
          <td width="10%" valign="top">
          <?php 
          
	  // menu_gestion("../../","menu_sucursal.php");
	  
           ?>
          
          </td>  
		  
		 <td width="80%" align="center">
		 <?php
		 $consulta=  mysql_query("select codigo_sucursal 
		                          from sucursal 
				          where codigo_sucursal='$codigo'");	
		 
		 $num= mysql_num_rows($consulta);
	 	 
		 if ($num<1)
		  {
		  ?>
	      <TABLE align="center" border="0">
               <TR>
		 <TD align="center"><font size="+1" face="Verdana">La sucursal No Existe</font></TD> 
	       </TR>
              </TABLE>
	      <?php
		  }
		 else
		  {
		  
		  $consulta=  mysql_query("delete 
		                          from sucursal 
				          where codigo_sucursal='$codigo'");
	if ( mysql_affected_rows()>0 )
			{
			?>

		<script language="javascript" type="text/javascript">
		var mensaje="Registro Exitoso";
		window.location.href='consulta.php?mensaje='+mensaje;
		</script>
		<?
			}
			else
		{?>
			<script language="javascript" type="text/javascript">
		var mensaje="Registro NO Exitoso";
		window.location.href='consulta.php?mensaje='+mensaje;
		</script>
		<?}
		
		 ?>
	  
		  <?php
                  }
                  ?>
		
		</td>
		
		<td width="10%"></td>
		
		</table>
		

          <?php
                  }
                  ?>
</body>
</html>
