<?php
//session_start();
require("../../clases/clases.php");
require("../../libreria/libreria.php");
?>	
<html>
<head>
<title>Sistema de Mensajeria.</title>
</head>
<body>
    	  <?php	
      		$usuario = $_SESSION['datosinicio']['usuario_tercero'];
         cabecera($usuario,"Modificacion Tercero","../../");  
       
         ?>	  
        <br><br>  
    <table width="100%">
          <tr>
          <td width="10%" valign="top">
          <?php 
	   menu_gestion("../../","menu_terceros.php");
	  
           ?>
          
          </td>
           <td width="80%" align="center">
	     <?php
		  
		$tercero = new tercero();
		
			$tercero->idtercero = $_POST["idtercero"];
			$DOCUMENTO = $tercero->documento_tercero = $_POST["DOCUMENTO"];
			$SUCURSAL = $tercero->sucursal_idsucursal = $_POST["SUCURSAL"];
			$TIPO_DOCUMENTO = $tercero->tipo_identificacion_tercero = $_POST["TIPO_DOCUMENTO"];
			$NOMBRES = $tercero->nombres_tercero = $_POST["NOMBRES"];
			$APELLIDOS = $tercero->apellidos_tercero = $_POST["APELLIDOS"];
			$DIRECCION = $tercero->direccion_tercero = $_POST["DIRECCION"];
			$TELEFONO = $tercero->telefono_tercero = $_POST["TELEFONO"];
			$TELEFONO2 = $tercero->telefono2_tercero = $_POST["TELEFONO2"];
			$CELULAR = $tercero->celular_tercero = $_POST["CELULAR"];
			$CORREO = $tercero->email_tercero = $_POST["CORREO"];
			$USUARIO = $tercero->usuario_tercero = $_POST["USUARIO"];
			$CLAVE = $tercero->clave_tercero = md5($_POST["CLAVE"]);
			$OBSERVACIONES = $tercero->observaciones_tercero = $_POST["OBSERVACIONES"];
			$VENDEDOR = $tercero->tercero_idvendedor= $_POST["VENDEDOR"];
			$COMISION = $tercero->comision_tercero = $_POST["COMISION"];			
			$res = $tercero -> modificar();			 	

	      ?>
                             		
               <font face="verdana" size="+1">Modificado Tercero Satisfactoriamente</font>
	     
		
       </td>
		
        <td width="10%" valign="top"></td>
              
		
	</table>
    <br>
     <?php
           //  finalizar("../../");
          ?>
</BODY>
</HTML>
