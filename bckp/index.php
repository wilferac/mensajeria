<?php
require("conexion.php");
session_start();
session_destroy();
?>

<HTML>
<head>
<title>Sistema de Mensajeria.</title>
</head>
<BODY>


    <br>
	 <table width="100%" border="0">
	 <tr>
	 <td width="25%" valign="top">
      
     </td> 
	 <td width="50%">
	 	<FORM name="formulario" onSubmit="return validar(this)"  action="menu_inicio.php" method="post" >
		 <FIELDSET>
	      <LEGEND accesskey="4"><font size="2" face="Verdana">
		   <strong>Ingreso Sistema A1</strong></font></LEGEND><br>
		   <table align="center">
		   <tr>
		     <td>
			  <font size="2" face="verdana"><strong>Usuario:</strong></font>
			  <input type="text" size=10 maxlength="10" name="usuario">
			  
		     </td>
                     
		    </tr>
                    <tr>
                        <td>
                          <font size="2" face="verdana"><strong>Clave:</strong></font>
			  <input type="password" size=10 maxlength="10" name="password">
			 </td>
                    </tr>
                    <TR>
                        <td>
                            <input name="enviar" type="submit" value="Ingreso">
                        </td>
                    </tr>
	       </table>
		   <br>
	   </FIELDSET>	
	   </form>
	  </td>
	  
	 <td width="25%">
	 </td>
     </tr>
     </table>

</BODY>	 
</HTML>