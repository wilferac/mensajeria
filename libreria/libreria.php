<?php 
 
function menu_gestion($path,$anterior)
{ 
	
?> 
<a href="<?php echo $anterior;?>"  
onMouseOver="MM_swapImage('menu','','../images/menu_gestion2.gif',1)" onMouseOut="MM_swapImgRestore()">  
<img src="<?php echo $path;?>images/menu_gestion.gif"  
name="menu" border="0" id="menu"> 
</a> 
<?php 
} 


function finalizar($path)
{ 

?> 
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"> 
<tr>  
    <td colspan="2" height="1" bgcolor="#000000"> 
    <img src="<?php echo $path;?>images/linenegro.gif" width="1" height="1"></td> 
</tr> 
<tr>  
	<td align="center" colspan="2" height="30" bgcolor="#F2F2F2">&nbsp; 
	</td> 
</tr> 
<tr>  
	<td height="1" bgcolor="#000000"> 
	<img src="<?php echo $path;?>images/linenegro.gif" width="1" height="1"></td> 
</tr> 
<tr>  
	<td>  
   	<div align="right"> 
	<a href="<?php echo $path;?>ingreso.php" onMouseOver="MM_swapImage('Image3','','<?php echo $path;?>images/salir3.gif',1)" 	onMouseOut="MM_swapImgRestore()">  
       <img src="<?php echo $path;?>images/salir.gif" name="Image3" width="268" height="40" border="0" 		id="Image3"></a></div> 
	</td> 
</tr> 
</table> 

<?php 
} 


function cabecera($nombre,$titulo_ppal,$path)
{ 
?>
<head> 
<title></title> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"> 
</head> 
<body> 
<table width="100%" border="0" cellpadding="0" cellspacing="0"> 
<tr> 
    <td width="30%" valign="middle">	 
	<font size="2" face="Verdana"><strong> 
	<?php echo $nombre;?><br>Sesion Iniciada</strong></font> 
	</td> 
    <td width="40%" valign="top" align="center"> 
	<img width="10%" src="<?php echo $path;?>images/logo.gif" border="0"> 
	</td>	 
	<td width="30%" align="right"> </td> 
</tr> 
<tr>  
    <td colspan="3" height="1" bgcolor="#000000"> 
    <img src="<?php echo $path;?>images/linenegro.gif" width="1" height="1"></td> 
</tr> 
<tr>  
    <td align="center" colspan="3" height="1" bgcolor="#F2F2F2"><font face="Verdana" size="4"> 
    <b><?php echo $titulo_ppal;?></b></font> 
</td> 
</tr> 
<tr>  
    <td colspan="3" height="1" bgcolor="#000000"> 
    <img src="<?php echo $path;?>images/linenegro.gif" width="1" height="1"></td> 
</tr> 
</table> 
<?php 
} 


function denegada($Cadena,$path){ 
?> 
<html> 

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"> 
  <tr>  
    <td align="center"><img src="<?php echo $path;?>images/stop.gif" ></td> 
  </tr>
  
  <tr>  
    <td align="center">&nbsp;</td> 
  </tr>
   <tr>  
    <td align="center">&nbsp;</td> 
  </tr>
  <tr>  
    <td align="center">&nbsp;</td> 
  </tr>  
     
  <tr>  
      <td width="50%" height="24" align="center"><font size="5" face="Arial, Helvetica, sans-serif"> 
	       <strong>ACCESO DENEGADO! </strong></font><strong>
		   <br>
		   <br> 
           <?php echo $Cadena;?><br><br> 
           <a href="<?php echo $path;?>ingreso.php">Click aqui para regresar</a></strong></font>
		   </td> 
          
  </tr> 
   
</table> 
</html> 
<?php 
} 
?>
