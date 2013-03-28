<?
session_start();
include ("../../param/param.php");
include ("../../autenticar.php");

$formname = "busqueda";
?>
<html>
<head>
		<style type="text/css" title="currentStyle">
			@import "../../media/css/demo_page.css";
			@import "css/default.css";	
		</style>

<title>Buscar Guia</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
</head>

	<body id="dt_example">
		<div id="container">
        <div class="full_width big">
      	<p class="navegacion">
        	<a href="../../redireccionador.php">Principal</a>&gt;<a href="../../procesos.php">Procesos</a>
        </p>
        	Asignar gu&iacute;a</div>
		 <p>&nbsp;</p>
       
			<div class="spacer"><form name="<?=$formname?>" method="post" action="busqueda.php">

<table width="767" border="0" align="center" cellpadding="0" cellspacing="0"  >

  <tr>
    <td>&nbsp;</td>
    <td><table  class="table_Fondo" width="450" align="center" border="0" cellspacing="0" cellpadding="3" bgcolor="#FFFFFF">
      
      <tr>
        <td>&nbsp;</td>
        </tr>
      <tr>
        <td><br></td>
        </tr>
      <tr>
        <td align="center">&nbsp;</td>
      </tr>
      <tr>
        <td align="center">&nbsp;</td>
        </tr>
		
    </table>
      </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<input type="hidden" name="token" value="<?=generarToken($formname)?>" />
</form></div>
		</div>
	</body>

<br> 

<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
  <!--DWLayoutTable-->
  <tr>
        <td height="40" class="table_Fondo" align="center"><!--DWLayoutEmptyCell-->&nbsp;</td>
<tr>
    <td class="table_Fondo" align="center">
      <div align="center"></div></td>
  </tr>
</table>
<br> 
<h2 align="center">&nbsp;</h2>
<br> 

</body>
</html>
