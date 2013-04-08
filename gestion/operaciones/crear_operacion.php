<?php
session_start();
require("../../conexion.php");
require("../../libreria/libreria.php");
?>
<html>
<head>
<title>Sistema de Mensajeria.</title>


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
         menu_gestion("../../","menu_operacion.php"); 
       ?>
    </td> 
    
    <td width="80%" valing="top" ALIGN="CENTER" >
    
  
  
  
	<br>  	
	  <table border="0" width="80%" >
	  <tr ALING="CENTER">
	                   
                    
          <td>	 
	  
	  <FORM name="formulario" onSubmit="return validar(this)" action="validacion_operacion.php" method="post" >
	  <TABLE width="100%" border="1" cellpadding="4" cellspacing="4" bgcolor="#FFFFFF" >
		
		
		<TR>
                 <TD colspan="2"><font size="1" face="Verdana"><strong>NOMBRE</strong></font><br>
	       		<input type="text" size="30" maxlength="30" name="NOMBRE">
		 </TD>   
                    
                    
                </TR>
                                   
               <TR>
			<TD colspan="2"><font size="1" face="Verdana"><strong>RUTA</strong></font><br>
	       		<input type="text" size="50" maxlength="50" name="RUTA">
			</TD>
			
			
		</TR>
                <TR>
	   		<TD valign="top">
				<font size="1" face="Verdana"><strong>OBSERVACIONES</strong></font><font size="1" face="Verdana">(*)</font></TD>
	   		<TD align="left">
				<textarea name="OBSERVACIONES" cols="60" rows="6"></textarea></TD>
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