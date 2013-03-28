<?php
session_start(); 
require("conexion.php");
require("libreria/libreria.php");
?>
<HTML>
<head>
<title>Sistema Mensajeria.</title>
</head>

<BODY> 

 
   <?php 
   
   if (isset($usuario))
      {
   
   
   if (!isset($_SESSION['usuario'])){
               $_SESSION['usuario']=$usuario; 
          }

         
         if (!isset($_SESSION['password'])){
               $_SESSION['password']=$password; 
          }   
   
        
          $consulta=  mysql_query("select usuario_tercero,clave_tercero 
                                  from tercero 
                                  where usuario_tercero='$usuario' and clave_tercero='$password'");
         $num=  mysql_num_rows($consulta);
     
          if ($num=="false")
           {
             ?>
             <br><br><br>
             <TABLE border="0" width="100%">
             <TR>
		<TD align="center"><font size="+1" face="Verdana">Usuario y/o Clave Invalida</font></TD> 
             </TR>
             <tr>
                 <td align="center"><font size="1" face="Verdana"><a href="http://localhost/mensajeria/ingreso.php">Click aqui para regresar</a></font></td>
             </tr>
             </TABLE>
	      <?php	
           }
           else
           {
            
          
        cabecera($usuario,"Menu Principal","");        
        
        $consulta= mysql_query("select idtercero
                                 from tercero 
                                 where usuario_tercero='$usuario'");
         $row = mysql_fetch_array($consulta, MYSQL_BOTH);
   
         $id_tercero= $row["idtercero"];
       
                 
        $consulta2= mysql_query("select nombre_operacion,ruta_operacion  
		                 from operacion  
        	                 where operacion.idoperacion in  
                                 (select operacion_idoperacion  
                                  from movimiento_operacion 
                                  where tercero_idtercero='$id_tercero')");
        $num2= mysql_num_rows($consulta2);
        
       
        
        if ($num2>0)
           { 
	   echo "<br><br><br>"; 
	   echo "<table width='100%' border='0'>"; 
	   echo "<tr>"; 
	   echo "<td width='10%'>&nbsp;</td>"; 
	   echo "<td width='80%'>"; 
	   echo "<table width='100%' border='0' cellpadding='0' cellspacing='4'>"; 
	   $n = 1; 
	   while ($row = mysql_fetch_array($consulta2, MYSQL_BOTH))
           {
  
                $nom = $row["nombre_operacion"]; 
	        $ubi=  $row["ruta_operacion"];
			if (bcmod($n,2)==1){ 
	        ?> 
				<tr bgcolor="#FF9933"><td align="center" width="50%"> 
                <a href=<?php echo $ubi?> style="text-transform: none; text-decoration:none; color: #293172"><font face="Verdana, Arial, Helvetica, sans-serif" size="4"><b><?php echo $nom?></b></font></a>  
				</td> 
			<?php 
			} 
			else{ 
			?> 
				<td align="center"  width="50%"> 
                <a href=<?php echo $ubi?> style="text-transform: none; text-decoration:none; color: #293172"><font face="Verdana, Arial, Helvetica, sans-serif" size="4"><b><?php echo $nom?></b></font></a>  
				</td></tr> 
			<?php 
			} 
		$n = $n + 1; 
	   }//fin del while 
	   echo "</table>"; 
	   echo "<td width='10%'>&nbsp;</td>"; 
	   echo "</td></tr>"; 
	   echo "</table>";
           echo "<br><br>"; 
           
	}/*fin del if OCIFetch($st) */ 
	else
        { 
          ?>
             <br><br><br>
             <TABLE border="0" width="100%">
             <TR>
		<TD align="center"><font size="+1" face="Verdana">Usuario no tiene Operaciones Asignadas</font></TD> 
	      </TR>
             </TABLE>
	  <?php
	}	  
  	finalizar("");
       
    } 
    
    }
   else//else de la sesion
   {
       
   denegada("SESION NO INICIADA",""); 
          
   }
        ?> 
      
   </BODY>	