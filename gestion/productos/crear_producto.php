<?php

require("../../clases/clases.php");
require("../../libreria/libreria.php");
?>
<html>
<head>
<title>Sistema de Mensajeria</title>


<script language="JavaScript">
<!--FUNCI�N DE VERIFICACION DE LOS CAMPOS DEL FORMULARIO

function validar(formulario)
{

  
  <!--VERIFICACI�N DEL CAMPO NOMBRE
  if(formulario.NOMBRE.value=="")
  {
  	alert("Se encuentra vacio el campo \"NOMBRE\"");
	formulario.NOMBRE.focus();
	return(false);
  }
  
  
}

//--> FIN DEL SCRIPT DE VERIFICACION
</script>
</head>
<body>

    <table border="0">
    
    <tr>    
    
    <td width="10%" valign="top">
        <br><br>
        <?php 
        //menu_gestion("../../","menu_productos.php"); 
       ?>
    </td> 
    
    <td width="80%" valing="top" ALIGN="CENTER" >
    
  
  
  
	<br>  	
	  <table border="0" width="80%" >
	  <tr ALING="CENTER">
          <td>	 
	  <FORM name="formulario" onSubmit="return validar(this)" action="validacion_producto.php" method="post" >
	  <TABLE width="100%" border="1" cellpadding="4" cellspacing="4" bgcolor="#FFFFFF" >
		<TR>
			<TD colspan="1"><font size="1" face="Verdana"><strong>TIPO PRODUCTO</strong></font><br>
	    		<select name="TPRODUCTO" size="1">
                        <?php
		  
		        $consulta= mysql_query("select idtipo_producto, nombre_tipo_producto 
                                                from tipo_producto");
                        while ($row = mysql_fetch_array($consulta, MYSQL_BOTH)) {
                        ?>
              
                        <option value=<?php echo $row[0] ?>><?php echo $row[1] ?></option>
                        <?php 
                      
                        }
                        ?>
                          </select>
			</TD>
	    </TR>
		
		<TR>
                 <TD><font size="1" face="Verdana"><strong>NOMBRE</strong></font><br>
	       		<input type="text" size="30" maxlength="30" name="NOMBRE">
		 </TD>   
                </TR>
               <TR>
			<TD><font size="1" face="Verdana"><strong>PORCENTAJE</strong></font><br>
	       		<input type="text" size="10" maxlength="10" name="PORCENTAJE">
			</TD>
		</TR>
		<TR>
			<TD COLSPAN="2" align="right">
				<input name="INSERTAR" type="submit" value="Crear">
				<input name="BORRAR" type="reset" VALUE="Limpiar"></TD>
    	</TR>
  </TABLE>
  </FORM>
   <font face="Verdana" size="1"><center>(*) Campo Opcional</center></font>
   </TD>
     
   
   </tr>
   
   </table>
        
        
    </td>
    
    <td width="10%" valign="top"></td> 
    
    </tr>     
  </table>
</body>
</html>
