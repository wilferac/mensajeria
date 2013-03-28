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
    <br><br><br> 
    <table border="0" width="100%">
     <tr>
  
     <td width="25%" valign="top">
     <?php 
        menu_gestion("../../","menu_operacion.php"); 
      ?>
     </td>  
  
     <td align="center" width="50%">		
     <?php 
	 $consulta = mysql_query("select idoperacion,nombre_operacion 
                                  from operacion");
    	 
         $num=  mysql_num_rows($consulta);
    	 
                  
         if ($num>0)
	  {
	   ?>

	    <form name="formulario" onSubmit="return validar(this)" action="validacion_operacion2.php" method="post">
		<table align="center" border="1" cellpadding="4" cellspacing="4" bgcolor="#FFFFFF">
		<tr>
		     <TD><font size="1" face="Verdana"><strong>OPERACIONES</strong></font><br>
                            <select name="OPERACION" size="1">
                   <?php
		  
		  
                  while ($row = mysql_fetch_array($consulta, MYSQL_BOTH)) {
                   ?>
              
                   <option value=<?php echo $row["idoperacion"] ?>><?php echo $row["nombre_operacion"] ?></option>
                      <?php 
                      
                      }
                    ?>
                          </select>
                            
                        
			</TD>	  
                </tr>        
                          
                <tr>
                         <td> 
                         <input name="enviar" type="submit" value="Eliminar">
			 </td>
		    </tr>
		</table></form>
		<?php
		}
		else
		{
		?>
		<TABLE align="center" border="0">
          <TR>
		    <TD align="center"><font size="+1" face="Verdana">No existen Operaciones para Eliminar</font></TD> 
	      </TR>
         </TABLE>
		<?php
		}
	 ?>
	 </td>
	 
	 <td width="25%"></td>
	 
	 </table>
	 
	
</body>
</html>