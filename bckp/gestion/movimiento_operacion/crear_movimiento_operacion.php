<?php
session_start();
require("../../conexion.php");
require("../../libreria/libreria.php");
?>
<html>
<head>
<title>Sistema de Mensajeria.</title>

</head>
<body>

    <?php	
       if (isset($_SESSION['usuario']))
      {
      
      cabecera($usuario,"Asignar Operacion","../../");  
       
      ?>
    <br>
    
    <table border="0" width="100%">
    
    <tr>    
    
    <td width="10%" valign="top">
        <br><br>
        <?php 
         menu_gestion("../../","menu_movimiento_operacion.php"); 
       ?>
    </td> 
    
    <td width="80%" valing="top" ALIGN="CENTER" >
     
	  	
	  <table border="0" width="60%" >
	  <tr align="center">
	                            
          <td align="center">	 
	  
	  <FORM name="formulario" onSubmit="return validar(this)" action="validacion_movimiento_operacion.php" method="post" >
	  <TABLE width="100%" border="1" cellpadding="4" cellspacing="4" bgcolor="#FFFFFF" >
		
		
		<TR>
                 <TD><font size="1" face="Verdana"><strong>USUARIOS</strong></font><br>
                            <select name="TERCERO" size="1">
                   <?php
		  
		  $consulta= mysql_query("select idtercero, usuario_tercero 
                                          from tercero");
                  while ($row = mysql_fetch_array($consulta, MYSQL_BOTH)) {
                   ?>
              
                   <option value=<?php echo $row["idtercero"] ?>><?php echo $row["usuario_tercero"] ?></option>
                      <?php 
                      
                      }
                    ?>
                          </select>
                            
                        
		</TD>   
                    
                    
                </TR>
                                   
               <TR>
			<TD><font size="1" face="Verdana"><strong>OPERACIONES</strong></font><br>
                            <select name="OPERACION" size="1">
                   <?php
		  
		  $consulta= mysql_query("select idoperacion, nombre_operacion 
                                          from operacion");
                  while ($row = mysql_fetch_array($consulta, MYSQL_BOTH)) {
                   ?>
              
                   <option value=<?php echo $row["idoperacion"] ?>><?php echo $row["nombre_operacion"] ?></option>
                      <?php 
                      
                      }
                    ?>
                          </select>
                            
                        
		</TD>   
			
			
		</TR>
                
                
		<TR>
			<TD COLSPAN="2" align="right">
				<input name="INSERTAR" type="submit" value="Crear">
				<input name="BORRAR" type="reset" VALUE="Limpiar"></TD>
    	</TR>
  
  </TABLE>
  </FORM>
   
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
   else//else de la sesion
   {
       
   denegada("SESION NO INICIADA","../../"); 
          
   }
   ?> 


</body>
</html>