<?php
require("../../clases/clases.php");
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

</script>

<script language="javascript">
  parent.frames[0].document.getElementById("a1").innerHTML = "";
 parent.frames[0].document.getElementById("a2").innerHTML = "";
 parent.frames[0].document.getElementById("a3").innerHTML = "";
 
 parent.frames[0].document.getElementById("s1").style.visibility = "hidden";
 parent.frames[0].document.getElementById("s2").style.visibility = "hidden";
 
 
 parent.frames[0].document.getElementById("a1").innerHTML = "Principal";
 parent.frames[0].document.getElementById("a1").href = "redireccionador.php";
 parent.frames[0].document.getElementById("s1").style.visibility = "visible";
 
 parent.frames[0].document.getElementById("a2").innerHTML = "Gestion";
 parent.frames[0].document.getElementById("a2").href = "gestion.php";
 parent.frames[0].document.getElementById("s2").style.visibility = "visible";
 
 parent.frames[0].document.getElementById("a3").innerHTML = "Ver Sucursales";
 parent.frames[0].document.getElementById("a3").href = "gestion/sucursal/consulta.php";
 
</script>
</head>
<body>
     <?php	
		
      $usuario = $_SESSION['datosinicio']['usuario_tercero'];
	  $CODIGO = $_GET["documento"];
      ?> 		

       
    <table border="0">
    
    <tr>    
    
    <td width="10%" valign="top">
        <br><br>
    </td> 
    
    <?php
		  
          $consulta= mysql_query("select * from sucursal where codigo_sucursal='$CODIGO'");
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
	  
	  <FORM name="formulario" onSubmit="return validar(this)" action="validacion_sucursal3.php" method="post" >
		<input type=hidden name=idsucursal value= "<?=$row["idsucursal"] ?>" > 
	  <TABLE width="100%" border="1" cellpadding="4" cellspacing="4" bgcolor="#FFFFFF" >
		<TR>
			<TD colspan="2"><font size="1" face="Verdana"><strong>CODIGO</strong></font><br>
	    		<input disabled type="text" size="10" maxlength="10" value="<?php echo $row["codigo_sucursal"] ?>">
			<input type="hidden" size="10" maxlength="10" value="<?php echo $row["codigo_sucursal"] ?>" name="CODIGO">
                        </TD>
	        
	    </TR>
		
		<TR>
			<TD><font size="1" face="Verdana"><strong>NOMBRE</strong></font><br>
	       		<input type="text" size="30" maxlength="30" value="<?php echo $row["nombre_sucursal"] ?>" name="NOMBRE">
			</TD>
			<TD><font size="1" face="Verdana"><strong>DIRECCION</strong></font><br>
	       		<input type="text" size="30" maxlength="30" value="<?php echo $row["direccion_sucursal"] ?>" name="DIRECCION">
			</TD>
			
		</TR>
                
                <TR>
			<TD><font size="1" face="Verdana"><strong>CIUDAD</strong></font><br>
                            <select name="CIUDAD" size="1">
                   
                        <?php
                   
		  
		  $consulta3= mysql_query("select nombre_ciudad from ciudad where idciudad='$row[1]'");
                  $row3 = mysql_fetch_array($consulta3, MYSQL_BOTH);
                   ?>                  
		   <option value=<?php echo $row["ciudad_idciudad"] ?>><?php echo $row3["nombre_ciudad"] ?></option>
                   
                        <?php
		  $consulta2= mysql_query("select idciudad, nombre_ciudad 
                                          from ciudad order by nombre_ciudad");
                  while ($row2 = mysql_fetch_array($consulta2, MYSQL_BOTH)) {
                   ?>
              
                   <option value=<?php echo $row2["idciudad"] ?>><?php echo $row2["nombre_ciudad"] ?></option>
                      <?php 
                      
                      }
                  
                    ?>
                   
                          </select>
                            
                        
			</TD>
			<TD><font size="1" face="Verdana"><strong>TELEFONO</strong></font><br>
	       		<input type="text" size="40" maxlength="40" value="<?php echo $row["telefono_sucursal"] ?>" name="TELEFONO">
			</TD>
			
		</TR>
			
		
		
		<TR>
	   		<TD valign="top">
				<font size="1" face="Verdana"><strong>OBSERVACIONES</strong></font><font size="1" face="Verdana">(*)</font></TD>
	   		<TD COLSPAN="1" align="left">
				<textarea name="OBSERVACIONES" cols="50" rows="6"><?php echo $row["observaciones_sucursal"] ?></textarea></TD>
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
