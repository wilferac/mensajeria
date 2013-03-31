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
				
			
      	$usuario = $_SESSION['datosinicio']['usuario_tercero'];
		$documento = $_GET["documento"];
		$id = $_GET["id"];
		$estado = $_GET["estado"];
         //cabecera($usuario,"Eliminacion Tercero","../../");  
       
         ?>
          
          <br><br>
	  <table width="100%" border="0">
          <tr>
          <td width="10%" valign="top">
          <?php 
          
	  // menu_gestion("../../","menu_terceros.php");
	  
           ?>
          
          </td>  
		  
		 <td width="80%" align="center">
		 <?php
		 $consulta=  mysql_query("select documento_tercero 
		                          from tercero 
				          where documento_tercero='$documento'");	
		 
		$tercero = new tercero();
		$cond = "documento_tercero='$documento'";
		$consulta = $tercero ->consultar($cond);
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
		  	$conex = new conexion();
		  	$qtrans = "SET AUTOCOMMIT=0;";
			$sac = $conex -> ejecutar($qtrans); 
			$qtrans = "BEGIN;";
			$sac = $conex -> ejecutar($qtrans);
			
			if (strtolower($estado) == 'activo')
				$estado = 'Inactivo';
			else
				$estado = 'Activo';
			
		 // $consulta2 = mysql_query("delete from tercero_tipo WHERE tercero_idtercero=$id");
		 	$consulta =  mysql_query("UPDATE  tercero SET  estado =  '".$estado."' WHERE  tercero.idtercero = $id");
		  echo mysql_error();
			if ( mysql_affected_rows()>0 )
			{
			$qtrans = "COMMIT";
			$sac = $conex -> ejecutar($qtrans);
				
			?>

		<script language="javascript" type="text/javascript">
		var mensaje="Activacion/Desactivacion Exitosa";
		window.location.href='consulta.php?mensaje='+mensaje;
		</script>
		<?
			}
			else
		{?>
			<script language="javascript" type="text/javascript">
		var mensaje="Activacion/Desactivacion NO Exitosa";
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
