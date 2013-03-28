<?php
session_start();
require("../../conexion.php");
require("../../libreria/libreria.php");
?>
<html>
<head>
<title>Sistema de Cartera.</title>

<script language="JavaScript">
<!--FUNCI�N DE VERIFICACION DE LOS CAMPOS DEL FORMULARIO

function validar(formulario)
{
<!--VERIFICACI�N DEL CAMPO DOCUMENTO
	if(formulario.DOCUMENTO.value=="")
	{
		alert("Se encuentra vacio el campo \"DOCUMENTO\"");
		formulario.DOCUMENTO.focus();
		return(false);
	}
  var checkOK = "0123456789"; 
  var checkStr = formulario.DOCUMENTO.value; 
  var allValid = true; 
  var decPoints = 0; 
  var allNum = ""; 
  for (i = 0; i < checkStr.length; i++) 
  { 
    ch = checkStr.charAt(i); 
    for (j = 0; j < checkOK.length; j++) 
      if (ch == checkOK.charAt(j))
        break; 
    if (j == checkOK.length) 
	{ 
      allValid = false; 
      break; 
    } 
    allNum += ch; 
  } 
  if (!allValid) 
  { 
    alert("Escriba solo digitos en el campo \"DOCUMENTO\" ");
    formulario.DOCUMENTO.focus(); 
    return (false); 
  }
  
  <!--VERIFICACI�N DEL CAMPO APELLIDO
  if(formulario.APELLIDOS.value=="")
  {
  	alert("Se encuentra vacio el campo \"APELLIDOS\"");
	formulario.APELLIDOS.focus();
	return(false);
  }
  
  <!--VERIFICACION DEL CAMPO NOMBRE
  if(formulario.NOMBRES.value=="")
  {
  	alert("Se encuentra vacio el campo \"NOMBRES\"");
	formulario.NOMBRES.focus();
	return(false);
  }
  
  <!--VERIFICACION DEL CAMPO DIRECCION
  if(formulario.DIRECCION.value=="")
  {
  	alert("Se encuentra vacio el campo \"DIRECCION\"");
	formulario.DIRECCION.focus();
	return(false);
  }
  
   	
  <!--VERIFICACI�N DEL CAMPO TELEFONO
  var checkOK = "0123456789"; 
  var checkStr = formulario.TELEFONO.value; 
  var allValid = true; 
  var decPoints = 0; 
  var allNum = ""; 
  for (i = 0; i < checkStr.length; i++) 
  { 
    ch = checkStr.charAt(i); 
    for (j = 0; j < checkOK.length; j++) 
      if (ch == checkOK.charAt(j))
        break; 
    if (j == checkOK.length) 
	{ 
      allValid = false; 
      break; 
    } 
    allNum += ch; 
  } 
  if (!allValid) 
  { 
    alert("Escriba solo digitos en el campo \"TELEFONO 1\" ");
    formulario.TELEFONO.focus(); 
    return (false); 
  }
  
  <!--VERIFICACI�N DEL CAMPO TELEFONO 2
  var checkOK = "0123456789"; 
  var checkStr = formulario.TELEFONO2.value; 
  var allValid = true; 
  var decPoints = 0; 
  var allNum = ""; 
  for (i = 0; i < checkStr.length; i++) 
  { 
    ch = checkStr.charAt(i); 
    for (j = 0; j < checkOK.length; j++) 
      if (ch == checkOK.charAt(j))
        break; 
    if (j == checkOK.length) 
	{ 
      allValid = false; 
      break; 
    } 
    allNum += ch; 
  } 
  if (!allValid) 
  { 
    alert("Escriba solo digitos en el campo \"TELEFONO 2\" ");
    formulario.TELEFONO2.focus(); 
    return (false); 
  }
  
  <!--VERIFICACION DEL CAMPO CELULAR
  var checkOK = "0123456789"; 
  var checkStr = formulario.CELULAR.value; 
  var allValid = true; 
  var decPoints = 0; 
  var allNum = ""; 
  for (i = 0; i < checkStr.length; i++) 
  { 
    ch = checkStr.charAt(i); 
    for (j = 0; j < checkOK.length; j++) 
      if (ch == checkOK.charAt(j))
        break; 
    if (j == checkOK.length) 
	{ 
      allValid = false; 
      break; 
    } 
    allNum += ch; 
  } 
  if (!allValid) 
  { 
    alert("Escriba solo digitos en el campo \"CELULAR\" ");
    formulario.CELULAR.focus(); 
    return (false); 
  }
  
   <!--VERIFICACI�N DEL CAMPO CORREO ELECTRONICO
  var checkOK = "@"; 
  var checkStr = formulario.CORREO.value; 
  var allValid = false; 
  var decPoints = 0; 
  var allNum = ""; 
  for (i = 0; i < checkStr.length; i++) 
  { 
    ch = checkStr.charAt(i); 
    for (j = 0; j < checkOK.length; j++) 
      if (ch == checkOK.charAt(j))
        {
		allValid = true; 
		break;
		} 
   } 
  
  if(formulario.CORREO.value!="")
  {
  if (allValid==false) 
  { 
    alert("El correo esta erroneamente en el campo \"CORREO ELECTRONICO\" ");
    formulario.CORREO.focus(); 
    return (false); 
  }
  }
  

<!--VERIFICACI�N DEL CAMPO OBSERVACIONES
  if(formulario.OBSERVACIONES.value.length > 300)
  {
  	alert("Digite menos de 300 caracteres en el campo \"OBSERVACIONES\"");
	formulario.OBSERVACIONES.focus();
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
         menu_gestion("../../","menu_causaldev.php"); 
       ?>
    </td> 
    
    <?php
		  
          $consulta= mysql_query("select * from causal_devolucion where codigo_causal_devolucion='$CODIGO'");
          $num= mysql_num_rows($consulta);
		  if ($num<>1)
		   {
		   ?>
		    
                    
                         <TD width="80%" align="center"><font size="+1" face="Verdana">El Codigo no existe en el sistema</font></TD> 
	            
                    
		   <?php	
		   }
		  else
		  {  
                   $row = mysql_fetch_array($consulta, MYSQL_BOTH);
                                                           
                   
                   ?>     
    
        
    <td width="80%" valing="top" >
          
             <br><br>    
	  <table border="0" width="100%">
	  <tr>
	                   
                    
          <td>	 
	  
	  <FORM name="formulario" onSubmit="return validar(this)" action="validacion_causaldev3.php" method="post" >
	  <TABLE width="100%" border="1" cellpadding="4" cellspacing="4" bgcolor="#FFFFFF" >
		<TR>
			<TD colspan="2"><font size="1" face="Verdana"><strong>CODIGO</strong></font><br>
	    		<input disabled type="text" size="10" maxlength="10" value="<?php echo $row["codigo_causal_devolucion"] ?>">
			<input type="hidden" size="10" maxlength="10" value="<?php echo $row["codigo_causal_devolucion"] ?>" name="CODIGO">
                        </TD>
	        
	    </TR>
		
		<TR>
			<TD><font size="1" face="Verdana"><strong>NOMBRE</strong></font><br>
	       		<input type="text" size="30" maxlength="30" value="<?php echo $row[2] ?>" name="NOMBRE">
			</TD>
			
		<TR>
	   		<TD valign="top">
				<font size="1" face="Verdana"><strong>OBSERVACIONES</strong></font><font size="1" face="Verdana">(*)</font></TD>
	   		<TD COLSPAN="1" align="left">
				<textarea name="OBSERVACIONES" cols="50" rows="6"><?php echo $row[3] ?></textarea></TD>
     	</TR>
		<TR>
			<TD COLSPAN="2" align="right">
				<input name="INSERTAR" type="submit" value="Modificar">
			</TD>
    	</TR>
  
  </TABLE>
  </FORM>
   <font face="Verdana" size="1"><center>(*) Campo Opcional</center></font>
   </TD>
     
   
   </tr>
   
   </table>
    
        
    </td>
    <?php
      }
   
     ?>             
    <td width="10%" valign="top"></td> 
    
    </tr>     
  </table>



</body>
</html>