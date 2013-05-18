<?php
//session_start();
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
<!--VERIFICACION DEL CAMPO DOCUMENTO
	if(formulario.documento.value=="")
	{
		alert("Se encuentra vacio el campo \"DOCUMENTO\"");
		formulario.documento.focus();
		return(false);
	}

  var checkOK = "0123456789"; 
  var checkStr = formulario.documento.value; 
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
    formulario.documento.focus(); 
    return (false); 
  }
}
//--> FIN DEL SCRIPT DE VERIFICACION
</script>
</head>
<body>
    
    
    <?php	
      
      cabecera($_SESSION['datosinicio']['usuario_tercero'],"Modificar Tercero","../../");  
       
      ?>
    
    <br><br>
    <table border="0" width="100%">
     <tr>
  
     <td width="10%" valign="top">
     <?php 
        menu_gestion("../../","menu_terceros.php"); 
      ?>
     </td>  
  
     <td align="center" width="80%">		
     <?php 
	 $consulta = mysql_query("select documento_tercero from tercero");
    	 $num=  mysql_num_rows($consulta);
         if ($num>0)
	  {
	   ?>

	    <form name="formulario" onSubmit="return validar(this)" action="modificar_tercero2.php" method="post">
		<table align="center" border="1" cellpadding="4" cellspacing="4" bgcolor="#FFFFFF">
		<tr>
		     <td>
			  <font size="2" face="verdana"><strong>Documento:</strong></font>
			  <input type="text" size=10 maxlength="10" name="DOCUMENTO">
			  <input name="enviar" type="submit" value="Modificar">
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
	        <TD align="center"><font size="+1" face="Verdana">No existen Teceros para Modificar</font></TD> 
	        </TR>
                 </TABLE>
		<?php
		}
	        ?>
	 
          </td>
	 
	 <td width="10%"></td>
	 
	 </table>
    <br>
    <?php 
    finalizar("../../");
   ?>
</body>
</html>
