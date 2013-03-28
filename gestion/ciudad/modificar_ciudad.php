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

     

  <h2>GESTION CIUDAD</h2>
	<br>  	
	        <table border="0" width="70%">
	     	<tr>
		 	 
	
	<td>	 
	  
	  <FORM name="formulario" onSubmit="return validar(this)" action="modificar_ciudad2.php" method="post" >
	  <TABLE width="100%" border="1" cellpadding="4" cellspacing="4" bgcolor="e9e7ed" >
		<TR>
                    <?php
		  
		  $consulta2= mysql_query("select nombre_ciudad, trayecto_especial_ciudad, idciudad 
                                          from ciudad where idciudad='$CIUDAD'");
                  $row2 = mysql_fetch_array($consulta2, MYSQL_BOTH)
    ?>
              
            
                    <TD colspan="2"><font size="1" face="Verdana"><strong>CIUDAD</strong></font><br>
	    		<input disabled type="text" size="20" maxlength="30" value="<?php echo $row2["nombre_ciudad"] ?>">
			<input type="hidden" size="20" maxlength="30" value="<?php echo $row2["idciudad"] ?>" name="IDCIUDAD">
                        </TD>
		                    
                    <TD><font size="1" face="Verdana"><strong>AGREGAR ZONA ALEDANA</strong></font><br>
                            <select name="ZONA_ALEDANA" size="1">
                   <?php
		  
		  $consulta= mysql_query("select idciudad, nombre_ciudad 
                                          from ciudad order by nombre_ciudad");
                  while ($row = mysql_fetch_array($consulta, MYSQL_BOTH)) {
    ?>
              
              <option value=<?php echo $row["idciudad"] ?>><?php echo $row["nombre_ciudad"] ?></option>
                      <?php 
                      
                      }
                  		   ?>
                  
                            </select>
                        <input name="INSERTAR" type="button" value="Agregar">
                        
                    <TD align="center"><font size="1" face="Verdana"><strong>TRAYECTO ESPECIAL</strong></font><br>
	       		 <?php 
         if ($row2["trayecto_especial_ciudad"]==1)
	  {?>

<input type="radio" name="TRAYECTO" value="1" checked> Si
<input type="radio" name="TRAYECTO" value="0"> No<br>
	 <?php }else{
         	  ?>
                        <input type="radio" name="TRAYECTO" value="1"> Si
<input type="radio" name="TRAYECTO" value="0" checked> No<br>
                 <?php }                      ?>
                    
                    
                    </TD>
	        
	    </TR>
		<TR>
			<TD COLSPAN="4" align="center"><font size="1" face="Verdana"><strong>ZONAS ALEDANAS</strong></font><br>
			<table border="0" width="70%">
                            <tr>
                               
                                <TD><font size="1" face="Verdana"><strong>CIUDAD1</strong></font><br>    
                                    
                               
                            </tr>
                            <tr>
                               
                                <TD><font size="1" face="Verdana"><strong>CIUDAD2</strong></font><br>    
                                    
                               
                            </tr>
                        </table>
			
    	</TR>
				<TR>
			<TD COLSPAN="4" align="center">
				<input name="INSERTAR" type="submit" value="Guardar">
				<input name="BORRAR" type="reset" VALUE="Cancelar"></TD>
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