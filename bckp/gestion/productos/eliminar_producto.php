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
<!--VERIFICACION DEL CAMPO CODIGO
	if(formulario.codigo.value=="")
	{
		alert("Se encuentra vacio el campo \"CODIGO\"");
		formulario.codigo.focus();
		return(false);
	}

  var checkOK = "0123456789"; 
  var checkStr = formulario.codigo.value; 
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
    formulario.codigo.focus(); 
    return (false); 
  }
}
//--> FIN DEL SCRIPT DE VERIFICACION
</script>
</head>
<body>
   
    <?php	
       if (isset($_SESSION['usuario']))
      {
      
      cabecera($usuario,"Eliminacion Productos","../../");  
       
      ?>
    <br>
    
    
    <table border="0" width="100%">
     <tr>
  
     <td width="25%" valign="top">
     <?php 
        menu_gestion("../../","menu_productos.php"); 
      ?>
     </td>  
  
     <td align="center" width="50%">		
     <?php 
	 $consulta = mysql_query("select nombre_producto 
                                  from producto");
    	 $num=  mysql_num_rows($consulta);
    	 
         if ($num>0)
	  {
	   ?>

	    <form name="formulario" onSubmit="return validar(this)" action="validacion_producto2.php" method="post">
		<table align="center" border="1" cellpadding="4" cellspacing="4" bgcolor="#FFFFFF">
		<tr>
		     <td>
			  <font size="2" face="verdana"><strong>Codigo:</strong></font>
			  <input type="text" size=10 maxlength="10" name="codigo">
			  <input name="enviar" type="submit" value="Eliminar">
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
		    <TD align="center"><font size="+1" face="Verdana">No existen Productos para Eliminar</font></TD> 
	      </TR>
         </TABLE>
		<?php
		}
	 ?>
	 </td>
	 
	 <td width="25%"></td>
	 
	 </table>
	<br> 
<?php 
    
    finalizar("../../");
    
     }
   else//else de la sesion
   {
       
   denegada("SESION NO INICIADA","../../"); 
          
   }
   ?>

    
    
</body>
</html>
