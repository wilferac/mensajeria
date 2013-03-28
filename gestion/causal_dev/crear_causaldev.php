<?php
session_start();
require("../../conexion.php");
?>
<html>
<head>
<title>Sistema de Mensaria.</title>
<script type="text/javascript" src="tabber.js"></script>
<link rel="stylesheet" href="example.css" TYPE="text/css" MEDIA="screen">
<link rel="stylesheet" href="example-print.css" TYPE="text/css" MEDIA="print">

<script type="text/javascript">

/* Optional: Temporarily hide the "tabber" class so it does not "flash"
   on the page as plain HTML. After tabber runs, the class is changed
   to "tabberlive" and it will appear. */

document.write('<style type="text/css">.tabber{display:none;}<\/style>');
</script>

<script language="JavaScript">
<!--FUNCI�N DE VERIFICACI�N DE LOS CAMPOS DEL FORMULARIO

function validar(formulario)
{
<!--VERIFICACI�N DEL CAMPO CODIGO
	if(formulario.CODIGO.value=="")
	{
		alert("Se encuentra vacio el campo \"CODIGO\"");
		formulario.CODIGO.focus();
		return(false);
	}
  var checkOK = "0123456789"; 
  var checkStr = formulario.CODIGO.value; 
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
    alert("Escriba solo digitos en el campo \"CODIGO\" ");
    formulario.CODIGO.focus(); 
    return (false); 
  }
  
  <!--VERIFICACI�N DEL CAMPO APELLIDO
  if(formulario.NOMBRE.value=="")
  {
  	alert("Se encuentra vacio el campo \"NOMBRE\"");
	formulario.NOMBRE.focus();
	return(false);
  }
  
  <!--VERIFICACION DEL CAMPO NOMBRE
  if(formulario.DIRECCION.value=="")
  {
  	alert("Se encuentra vacio el campo \"DIRECCION\"");
	formulario.DIRECCION.focus();
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
  if(formulario.OBSERVACIONES.value.length > 500)
  {
  	alert("Digite menos de 500 caracteres en el campo \"OBSERVACIONES\"");
	formulario.OBSERVACIONES.focus();
	return(false);
  }
}

//--> FIN DEL SCRIPT DE VERIFICACION
</script>
</head>
<body>
<div class="tabber">
     
	 <div class="tabbertab">
  <h2>CREAR CAUSAL DEVOLUCION</h2>
	<br>  	
	        <table border="0" width="70%">
	     	<tr>
		 	 
	
	<td>	 
	  
	  <FORM name="formulario" onSubmit="return validar(this)" action="validacion_causaldev.php" method="post" >
	  <TABLE width="100%" border="1" cellpadding="4" cellspacing="4" bgcolor="e9e7ed" >
		<TR>
			<TD><font size="1" face="Verdana"><strong>CODIGO</strong></font><br>
	    		<input type="text" size="10" maxlength="10" name="CODIGO">
			</TD>
                        
                        <TD><font size="1" face="Verdana"><strong>NOMBRE</strong></font><br>
	       		<input type="text" size="30" maxlength="30" name="NOMBRE">
			</TD>
	        
	    </TR>
		
		
                
		
		<TR>
	   		<TD valign="top">
				<font size="1" face="Verdana"><strong>OBSERVACIONES</strong></font><font size="1" face="Verdana">(*)</font></TD>
	   		<TD COLSPAN="4" align="left">
				<textarea name="OBSERVACIONES" cols="70" rows="6"></textarea></TD>
     	</TR>
		<TR>
			<TD COLSPAN="4" align="right">
				<input name="INSERTAR" type="submit" value="Crear">
				<input name="BORRAR" type="reset" VALUE="Limpiar"></TD>
    	</TR>
  
  </TABLE>
  </FORM>
   <font face="Verdana" size="1"><center>(*) Campo Opcional</center></font>
   </TD>
   
   
   
   </tr>
   
   </table>
    </div>
                
</body>
</html>