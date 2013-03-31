<?php
//session_start();
require("../../clases/clases.php");
require("../../libreria/libreria.php");
?>

<html>
<head>
<title>Sistema de Mensajeria.</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
          
        <?php

		$documento = $_GET["documento"];
         //cabecera($usuario,"Eliminacion Tercero","../../");  
       
         ?>
          
          <br>
<br>
	  <table width="100%" border="0">
          <tr>
          <td width="10%" valign="top">
          <?php 
          
	  // menu_gestion("../../","menu_terceros.php");
	  
           ?>
          
          </td>  
		  
		 <td width="80%" align="center">
		 <?php
		// $consulta=  mysql_query("select * from producto where idproducto='$documento'");	
		 
		$tercero = new producto();
		$cond = "idproducto='$documento'";
		$consulta = $tercero ->consultar($cond);
		 $num= mysql_num_rows($consulta);
		
		 if ($num<1)
		  {
		  ?>
	      <TABLE align="center" border="0">
               <TR>
		 <TD align="center"><font size="+1" face="Verdana">El Producto No Existe</font></TD> 
	       </TR>
              </TABLE>
	      <?php
		  }
		 else
		  {
		  	$conex = new conexion();
		  			$qtrans = "SET AUTOCOMMIT=0;";
			$sac = $conex -> ejecutar($qtrans); 
			$qtrans = "BEGIN;";
			$sac = $conex -> ejecutar($qtrans);
		  
		  $consulta=  mysql_query("delete 
		                          from producto 
				          where idproducto='$documento'");
			if ( mysql_affected_rows()>0 )
			{
			$qtrans = "COMMIT";
			$sac = $conex -> ejecutar($qtrans);
			?>

		<script language="javascript" type="text/javascript">
		var mensaje="Eliminacion Exitosa";
		window.location.href='consulta.php?mensaje='+mensaje;
		</script>
		<?
			}
			else
		{?>
			<script language="javascript" type="text/javascript">
		var mensaje="Eliminacion NO Exitosa";
		window.location.href='consulta.php?mensaje='+mensaje;
		</script>
		<?}
		
		 ?>
	  
		  <?php
                  }
                  ?>
		</td>
		
		<td width="10%"></td>
          </tr>
           </table>
          <br>

          <?php
             //finalizar("../../");
          ?>
</body>
</html>
