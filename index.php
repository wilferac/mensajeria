<?
session_start();
include ("param/param.php");
include ("autenticar.php");


$titulopagina=$_SESSION['param']['titulopagina'];
?>
<html>
<head>
<script language="javascript" type="text/javascript" src="js/funciones.js"></script>
<title><?=$titulopagina?></title>
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

<body onLoad="setfocus()">
<?
  //session_destroy();
?>
<br> 

<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
  <!--DWLayoutTable-->
  <tr>
        <td height="40" class="table_Fondo" align="center"><!--DWLayoutEmptyCell-->&nbsp;</td>
<tr>
    <td class="table_Fondo" align="center">
      <div align="center"><img src="imagenes/logo.gif" width="311" height="120" align="middle" class="titulosayuda"/> </div></td>
  </tr>
</table>
<br> 
<h2 align="center"><font face="Verdana, Arial, Helvetica, sans-serif" color="#FF0000">Sistema de Mensajeria</font></h2>
<br> 
<form name="forma" method="post" action="acceso.php" onSubmit="return validar(this);">
<!--NOMBRE DE LA PAGINA QUE HACE LA LLAMADA-->

<table width="767" border="0" align="center" cellpadding="0" cellspacing="0"  >

  <tr>
    <td>&nbsp;</td>
    <td><table  class="table_Fondo" width="450" align="center" border="0" cellspacing="0" cellpadding="3" bgcolor="#FFFFFF">
      <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
      <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
      <tr>
        <td><div align="right" class="Estilo1"> Usuario: </div></td>
        <td><input name="login" type="text" id="login"  size="25" maxlength="25"></td>
      </tr>
      <tr>
        <td><div align="right" class="Estilo1"> Contrase&ntilde;a: </div></td>
        <td><input name="password" type="password" id="password"  size="25" maxlength="10"></td>
      </tr>
      <tr>
        <td colspan="2"><br></td>
        </tr>
      <tr>
        <td colspan="2" align="center"><input name="Submit" type="submit" value="Ingresar"></td>
        </tr>
      <tr>
        <td colspan="2" align="center">&nbsp;</td>
        </tr>
		
    </table>
      </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<input type="hidden" name="token" value="<?=generarToken('acceso')?>" />
</form>
</body>
</html>
