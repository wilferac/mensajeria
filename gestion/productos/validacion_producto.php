<?php

require("../../clases/clases.php");
require("../../libreria/libreria.php");
?>	
<html>
<head>
<title>Sistema de Mensajeria.</title>
</head>
<body>
	  		<?
			$CODIGO = $_POST["CODIGO"];
            $TPRODUCTO = $_POST["TPRODUCTO"];
			$NOMBRE = $_POST["NOMBRE"];
			$PORCENTAJE = $_POST["PORCENTAJE"];
			
			?>  
          <table width="100%">
          <tr>
          		  
		  
          <td width="10%" valign="top">
           </td>
 <?php
		  
		  $consulta= mysql_query("select * from PRODUCTO where codigo='$CODIGO'");
		  
          $num= @mysql_num_rows($consulta);  
		  
		  if ($num>0)
		   {
		   ?>
                    <br>
                    <TABLE border="0">
                    <TR>
		     <TD align="center"><font size="+1" face="Verdana">El Producto ya existe en el sistema</font></TD> 
	            </TR>
                    </TABLE>
                    <br>
		   <?php
		   	exit();	
		   }
		  else
		  {  
  		  	$producto = new producto ();
			
			$producto->codigo = $CODIGO;
			$producto->tipo_producto_idtipo_producto = $TPRODUCTO;
			$producto->nombre_producto = ucfirst($NOMBRE);
			$producto->porcentaje_seguro_producto = $PORCENTAJE;
						
			$res = $producto->agregar();
    		//$consulta= mysql_query("insert into PRODUCTO values(0,'$TPRODUCTO','$NOMBRE','$PORCENTAJE')");
                                
			}					
		 if ($res === true)
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
		 <?php 
		   }
         
      ?>	
		
       </td>
		
        <td width="10%" valign="top"></td>
              
		
	</table>
	
    
</BODY>
</HTML>