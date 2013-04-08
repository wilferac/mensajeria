<?php
session_start();
require("../../conexion.php");
require("../../libreria/libreria.php");
?>
<html>
<head>
<title>Sistema de Mensajeria</title>

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
  
  <!--VERIFICACION DEL CAMPO COMISION
  var checkOK = "0123456789"; 
  var checkStr = formulario.COMISION.value; 
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
    alert("Escriba solo digitos en el campo \"COMISION\" ");
    formulario.COMISION.focus(); 
    return (false); 
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
     
      cabecera($usuario,"Modificar Tercero","../../");  
       
      ?>
    <br> 
    
    <table border="0" width="100%">
    <tr>
       
    <td width="10%" valign="top">
        <br>
        <?php 
         menu_gestion("../../","menu_terceros.php"); 
       ?>
    </td> 
    
    <?php
		  
          $consulta= mysql_query("select sucursal_idsucursal,documento_tercero,nombres_tercero,apellidos_tercero,direccion_tercero,
                                  telefono_tercero,telefono2_tercero,celular_tercero,email_tercero,usuario_tercero,clave_tercero,observaciones_tercero,
                                  tercero_idvendedor,comision_tercero                  
                                  from tercero 
                                  where documento_tercero='$DOCUMENTO'");
          $num= mysql_num_rows($consulta);
          		  
		  if ($num<>1)
		   {
		   ?>
		    
		   <TABLE align="center" border="0">
                   <TR>
	             <TD align="center"><font size="+1" face="Verdana">El Documento No Existe en el Sistema</font></TD> 
	           </TR>
                   </TABLE>
	                              
		   <?php	
		   }
		  else
		  {  
                   $row = mysql_fetch_array($consulta, MYSQL_BOTH);
                   
                                      
                   ?>     
            
          <td width="80%" valing="top" >
                         
	  <table border="0" width="100%">
	  <tr>
	                   
                    
          <td>	 
	  
	  <FORM name="formulario" onSubmit="return validar(this)" action="validacion_tercero3.php" method="post" >
	  <TABLE width="100%" border="1" cellpadding="4" cellspacing="4" bgcolor="#FFFFFF" >
		<TR>
			<TD colspan="2"><font size="1" face="Verdana"><strong>DOCUMENTO</strong></font><br>
	    		<input disabled type="text" size="10" maxlength="10" value="<?php echo $row["documento_tercero"] ?>">
			<input type="hidden" size="10" maxlength="10" value="<?php echo $row["documento_tercero"] ?>" name="DOCUMENTO">
                        </TD>
	        
	    </TR>
		
		<TR>
			<TD><font size="1" face="Verdana"><strong>APELLIDOS</strong></font><br>
	       		<input type="text" size="30" maxlength="30" value="<?php echo $row["apellidos_tercero"] ?>" name="APELLIDOS">
			</TD>
			<TD><font size="1" face="Verdana"><strong>NOMBRES</strong></font><br>
	       		<input type="text" size="30" maxlength="30" value="<?php echo $row["nombres_tercero"] ?>" name="NOMBRES">
			</TD>
			
		</TR>
                
                <TR>
			<TD><font size="1" face="Verdana"><strong>SUCURSAL</strong></font><br>
                            <select name="SUCURSAL" size="1">
                   <?php
		  
		  $consulta2= mysql_query("select idsucursal, nombre_sucursal 
                                           from sucursal");
                  while ($row2 = mysql_fetch_array($consulta2, MYSQL_BOTH)) {
                   ?>
              
                   <option value=<?php echo $row2["idsucursal"] ?>><?php echo $row2["nombre_sucursal"] ?></option>
                      <?php 
                      
                      }
                  
                    ?>
                   
                          </select>
                            
                        
			</TD>
			<TD><font size="1" face="Verdana"><strong>DIRECCION</strong></font><br>
	       		<input type="text" size="40" maxlength="40" value="<?php echo $row[4] ?>" name="DIRECCION">
			</TD>
			
		</TR>
			
		<TR>
		    <TD><font size="1" face="Verdana"><strong>TELOFONO 1</strong></font><font size="1" face="Verdana">(*)</font><br>
	        	<input type="text" size="12" maxlength="7" value="<?php echo $row[5] ?>" name="TELEFONO">
			</TD>
	            <TD colspan="1"><font size="1" face="Verdana"><strong>TELOFONO 2</strong></font><font size="1" face="Verdana">(*)</font><br>
	        	<input type="text" size="12" maxlength="11" value="<?php echo $row[6] ?>" name="TELEFONO2">
			</TD>
			
                 </TR>
                 
                 <TR>
                        <TD><font size="1" face="Verdana"><strong>CELULAR</strong></font><font size="1" face="Verdana">(*)</font><br>
	        	<input type="text" size="15" maxlength="11" value="<?php echo $row["celular_tercero"] ?>" name="CELULAR">
			</TD>
			<TD colspan="1"><font size="1" face="Verdana"><strong>CORREO ELECTRONICO</strong></font><font size="1" face="Verdana">(*)</font><br>
	        	<input type="text" size="30" maxlength="30" value="<?php echo $row["email_tercero"] ?>" name="CORREO">
			</TD>
	         </TR>
                 <TR>
                        <TD><font size="1" face="Verdana"><strong>USUARIO</strong></font><font size="1" face="Verdana">(*)</font><br>
	        	<input type="text" size="15" maxlength="11" value="<?php echo $row["usuario_tercero"] ?>" name="USUARIO">
			</TD>
			<TD colspan="1"><font size="1" face="Verdana"><strong>CLAVE</strong></font><font size="1" face="Verdana">(*)</font><br>
	        	<input type="password" size="30" maxlength="30" value="<?php echo $row["clave_tercero"] ?>" name="CLAVE">
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
                  
                  while ($row2 = mysql_fetch_array($consulta, MYSQL_BOTH)) {
                   ?>
              
                   <option value=<?php echo $row2["0"] ?>><?php echo $row2["1"]." ".$row2["2"] ?></option>
                      <?php 
                      
                      }
                    ?>
                          </select>
                            
                        </TD>
			<TD colspan="1"><font size="1" face="Verdana"><strong>COMISION</strong></font><font size="1" face="Verdana">(*)</font><br>
	        	<input type="text" size="5" maxlength="2" value="<?php echo $row["comision_tercero"] ?>" name="COMISION">
			</TD>
	         </TR>
		
		
		<TR>
	   		<TD valign="top">
				<font size="1" face="Verdana"><strong>OBSERVACIONES</strong></font><font size="1" face="Verdana">(*)</font></TD>
	   		<TD COLSPAN="1" align="left">
				<textarea name="OBSERVACIONES" cols="50" rows="6"><?php echo $row["observaciones_tercero"] ?></textarea></TD>
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
                 
    <td width="10%" valign="top"></td> 
    
    </tr>     
  </table>

     <br>
    <?php 
    
      }
      
    finalizar("../../");
    
     }
   else//else de la sesion
   {
       
   denegada("SESION NO INICIADA","../../"); 
          
   }
   ?>

</body>
</html>