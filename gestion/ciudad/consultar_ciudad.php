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
    <br><br><br> 
    <table border="0" width="100%">
     <tr>
  
     <td width="10%" valign="top">
     <?php 
        menu_gestion("../../","menu_terceros.php"); 
      ?>
     </td>  
  
     <td align="center" width="80%">		
     

	    <form name="formulario" onSubmit="return validar(this)" action="modificar_ciudad.php" method="post">
		<table align="center" border="1" cellpadding="4" cellspacing="4" bgcolor="#FFFFFF">
		<tr>
		    <TD><font size="1" face="Verdana"><strong>CIUDAD</strong></font><br>
                            <select name="CIUDAD" size="1">
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
                        
			  <input name="enviar" type="submit" value="Consultar">
                        	 </td>
                    
		    </tr>
		</table></form>
		
	 
          </td>
	 
	 <td width="10%"></td>
	 
	 </table>
	 
	
</body>
</html>
