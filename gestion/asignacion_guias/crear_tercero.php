<?php
session_start();
require("../../conexion.php");
require("../../libreria/libreria.php");
?>
<html>
<head>
<title>Sistema de Cartera.</title>
<script type="text/javascript" src="tabber.js"></script>
<link rel="stylesheet" href="example.css" TYPE="text/css" MEDIA="screen">
<link rel="stylesheet" href="example-print.css" TYPE="text/css" MEDIA="print">

<script type="text/javascript">

/* Optional: Temporarily hide the "tabber" class so it does not "flash"
   on the page as plain HTML. After tabber runs, the class is changed
   to "tabberlive" and it will appear. */

document.write('<style type="text/css">.tabber{display:none;} </style>');
</script>

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

    
    <?php	
      if (isset($_SESSION['usuario']))
      {
          
          cabecera($usuario,"Creacion Tercero","../../");  
       
      ?>
    
    <br>
    <table border="0">
    
    <tr>    
    
    <td width="10%" valign="top">
        <br><br>
        <?php 
         menu_gestion("../../","menu_terceros.php"); 
       ?>
    </td> 
    
    <td width="80%" valing="top" >
    <div class="tabber">
  
  
  <div class="tabbertab">
  <h2>CREAR USUARIO</h2>
	<br>  	
	  <table border="0" width="100%">
	  <tr>
	                   
                    
          <td>	 
	  
	  <FORM name="formulario" onSubmit="return validar(this)" action="validacion_tercero.php" method="post" >
	  <TABLE width="100%" border="1" cellpadding="4" cellspacing="4" bgcolor="#FFFFFF" >
		<TR>
			
                  <TD><font size="1" face="Verdana"><strong>TIPO DE DOCUMENTO</strong></font><br>
                            <select name="TIPO_DOCUMENTO" size="1">
                   <?php
		  
		  $consulta= mysql_query("select idtipo_identificacion, nombre_tipo_identificacion 
                                          from tipo_identificacion");
                  while ($row = mysql_fetch_array($consulta, MYSQL_BOTH)) {
                   ?>
              
                   <option value=<?php echo $row["0"] ?>><?php echo $row["1"] ?></option>
                      <?php 
                      
                      }
                    ?>
                          </select>
                                                
			</TD>
                        <TD><font size="1" face="Verdana"><strong>DOCUMENTO</strong></font><br>
	    		<input type="text" size="15" maxlength="15" name="DOCUMENTO">
			</TD>
	        
	    </TR>
		
		<TR>
			<TD><font size="1" face="Verdana"><strong>APELLIDOS</strong></font><br>
	       		<input type="text" size="30" maxlength="30" name="APELLIDOS">
			</TD>
			<TD><font size="1" face="Verdana"><strong>NOMBRES</strong></font><br>
	       		<input type="text" size="30" maxlength="30" name="NOMBRES">
			</TD>
			
		</TR>
                
                <TR>
			<TD><font size="1" face="Verdana"><strong>SUCURSAL</strong></font><br>
                            <select name="SUCURSAL" size="1">
                   <?php
		  
		  $consulta= mysql_query("select idsucursal, nombre_sucursal 
                                          from sucursal");
                  while ($row = mysql_fetch_array($consulta, MYSQL_BOTH)) {
                   ?>
              
                   <option value=<?php echo $row["idsucursal"] ?>><?php echo $row["nombre_sucursal"] ?></option>
                      <?php 
                      
                      }
                    ?>
                          </select>
                            
                        
			</TD>
			<TD><font size="1" face="Verdana"><strong>DIRECCION</strong></font><br>
	       		<input type="text" size="40" maxlength="40" name="DIRECCION">
			</TD>
			
		</TR>
			
		<TR>
		    <TD><font size="1" face="Verdana"><strong>TELOFONO 1</strong></font><font size="1" face="Verdana">(*)</font><br>
	        	<input type="text" size="12" maxlength="7" name="TELEFONO">
			</TD>
	        <TD colspan="1"><font size="1" face="Verdana"><strong>TELOFONO 2</strong></font><font size="1" face="Verdana">(*)</font><br>
	        	<input type="text" size="12" maxlength="11" name="TELEFONO2">
			</TD>
			
                 </TR>
                 
                 <TR>
                        <TD><font size="1" face="Verdana"><strong>CELULAR</strong></font><font size="1" face="Verdana">(*)</font><br>
	        	<input type="text" size="15" maxlength="11" name="CELULAR">
			</TD>
			<TD colspan="1"><font size="1" face="Verdana"><strong>CORREO ELECTRONICO</strong></font><font size="1" face="Verdana">(*)</font><br>
	        	<input type="text" size="30" maxlength="30" name="CORREO">
			</TD>
	         </TR>
                 <TR>
                        <TD><font size="1" face="Verdana"><strong>USUARIO</strong></font><font size="1" face="Verdana">(*)</font><br>
	        	<input type="text" size="15" maxlength="11" name="USUARIO">
			</TD>
			<TD colspan="1"><font size="1" face="Verdana"><strong>CLAVE</strong></font><font size="1" face="Verdana">(*)</font><br>
	        	<input type="password" size="30" maxlength="30" name="CLAVE">
			</TD>
	         </TR>
                 <TR>
                      <TD><font size="1" face="Verdana"><strong>VENDEDOR</strong></font><br>
                            <select name="VENDEDOR" size="1">
                   <?php
		  
		  $consulta= mysql_query("select idtercero,nombres_tercero, apellidos_tercero 
                                          from tercero,tercero_tipo
                                          where tercero_tipo.tercero_idtercero=tercero.idtercero
                                          and tercero_tipo.tipo_tercero_idtipo_tercero=6");
                  while ($row = mysql_fetch_array($consulta, MYSQL_BOTH)) {
                   ?>
              
                   <option value=<?php echo $row["0"] ?>><?php echo $row["1"]." ".$row["2"] ?></option>
                      <?php 
                      
                      }
                    ?>
                          </select>
                            
                        </TD>
			<TD colspan="1"><font size="1" face="Verdana"><strong>COMISION</strong></font><font size="1" face="Verdana">(*)</font><br>
	        	<input type="password" size="10" maxlength="10" name="COMISION">
			</TD>
	         </TR>
		
		
		<TR>
	   		<TD valign="top">
				<font size="1" face="Verdana"><strong>OBSERVACIONES</strong></font><font size="1" face="Verdana">(*)</font></TD>
	   		<TD COLSPAN="1" align="left">
				<textarea name="OBSERVACIONES" cols="50" rows="6"></textarea></TD>
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
    </div>
        
         <div class="tabbertab">
	 <h2>CREAR CLIENTE</h2>   
        
        
        </div>
        
        <div class="tabbertab">
	 <h2>CREAR VENDEDOR</h2>   
        
        
        </div>
        
     </div>
        
    </td>
    
    <td width="10%" valign="top"></td> 
    
    </tr>     
  </table>
    <br>
  <?php 
    finalizar("../../");
      
      }
   else
   {
       
       denegada("SESION NO INICIADA","../../"); 
   
       
   }
    ?>
        
</body>
</html>