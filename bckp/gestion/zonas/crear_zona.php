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

  
  <!--VERIFICACI�N DEL CAMPO NOMBRE
  if(formulario.NOMBRE.value=="")
  {
  	alert("Se encuentra vacio el campo \"NOMBRE\"");
	formulario.NOMBRE.focus();
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
          
          cabecera($usuario,"Creacion Zonas","../../");  
       
      ?>
    
    <table border="0" width="100%">
    
    <tr>    
    
    <td width="10%" valign="top">
        <br><br>
        <?php 
         menu_gestion("../../","menu_zonas.php"); 
       ?>
    </td> 
    
    <td width="80%" valing="top" aling="center">
    
  
  
  
	<br>  	
	  <table border="0" width="90%">
	  <tr aling="center">
	                   
                    
          <td>	 
	  
	  <FORM name="formulario" onSubmit="return validar(this)" action="validacion_zona.php" method="post" >
	  <TABLE width="100%" border="1" cellpadding="4" cellspacing="4" bgcolor="#FFFFFF" >
		<TR>
			<TD colspan="1"><font size="1" face="Verdana"><strong>CIUDAD</strong></font><br>
	    		<select name="CIUDAD" size="1">
                        <?php
		  
		        $consulta= mysql_query("select idciudad, nombre_ciudad 
                                                from ciudad
                                                order by 2");
                        while ($row = mysql_fetch_array($consulta, MYSQL_BOTH)) {
                        ?>
              
                        <option value=<?php echo $row[0] ?>><?php echo $row[1] ?></option>
                        <?php 
                      
                        }
                        ?>
                          </select>
			</TD>
                        
                        <TD><font size="1" face="Verdana"><strong>NOMBRE</strong></font><br>
	       		<input type="text" size="30" maxlength="30" name="NOMBRE">
			</TD>
	        
	    </TR>
		
		<TR>
			<TD><font size="1" face="Verdana"><strong>DESDE CALLE NUMERO</strong></font><br>
	       		<input type="text" size="20" maxlength="20" name="DCALLEN">
			</TD>
			<TD><font size="1" face="Verdana"><strong>HASTA CALLE NUMERO</strong></font><br>
	       		<input type="text" size="20" maxlength="20" name="HCALLEN">
			</TD>
			
		</TR>
                
                <TR>
			<TD><font size="1" face="Verdana"><strong>DESDE CALLE LETRA</strong></font><br>
	       		<input type="text" size="20" maxlength="20" name="DCALLEL">
			</TD>
			<TD><font size="1" face="Verdana"><strong>HASTA CALLE LETRA</strong></font><br>
	       		<input type="text" size="20" maxlength="20" name="HCALLEL">
			</TD>
			
		</TR>
                
                <TR>
			<TD><font size="1" face="Verdana"><strong>DESDE CALLE ANDEN</strong></font><br>
	       		<input type="text" size="20" maxlength="20" name="DCALLEA">
			</TD>
			<TD><font size="1" face="Verdana"><strong>HASTA CALLE ANDEN</strong></font><br>
	       		<input type="text" size="20" maxlength="20" name="HCALLEA">
			</TD>
			
		</TR>
                
                <TR>
			<TD><font size="1" face="Verdana"><strong>DESDE CARRERA NUMERO</strong></font><br>
	       		<input type="text" size="20" maxlength="20" name="DCARRERAN">
			</TD>
			<TD><font size="1" face="Verdana"><strong>HASTA CARRERA NUMERO</strong></font><br>
	       		<input type="text" size="20" maxlength="20" name="HCARRERAN">
			</TD>
			
		</TR>
                
                <TR>
			<TD><font size="1" face="Verdana"><strong>DESDE CARRERA LETRA</strong></font><br>
	       		<input type="text" size="20" maxlength="20" name="DCARRERAL">
			</TD>
			<TD><font size="1" face="Verdana"><strong>HASTA CARRERA LETRA</strong></font><br>
	       		<input type="text" size="20" maxlength="20" name="HCARRERAL">
			</TD>
			
		</TR>
                
                <TR>
			<TD><font size="1" face="Verdana"><strong>DESDE CARRERA ANDEN</strong></font><br>
	       		<input type="text" size="20" maxlength="20" name="DCARRERAA">
			</TD>
			<TD><font size="1" face="Verdana"><strong>HASTA CARRERA ANDEN</strong></font><br>
	       		<input type="text" size="20" maxlength="20" name="HCARRERAA">
			</TD>
			
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
        
        
    </td>
    
    <td width="10%" valign="top"></td> 
    
    </tr>     
  </table>
    
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