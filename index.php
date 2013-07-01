<?php
/**
 * index de la aplicacion, muestra el login para que el usuario se loguee.
 */
session_start();
require_once "param/param.php";
include ("autenticar.php");

$titulopagina = $_SESSION['param']['titulopagina'];
?>
<html>
    <head>
        <script language="javascript" type="text/javascript" src="js/funciones.js"></script>
        <title><?= $titulopagina ?></title>
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
        <style type="text/css" title="currentStyle">
            @import "css/default.css";	
        </style>
    </head>

    <body>
        <?
        //session_destroy();
        ?>
        <br> 

        <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
            <tr>
                <td class="table_Fondo" align="center">
                    <div align="center"><img src="imagenes/logo.gif" width="311" height="120" align="middle" class="titulosayuda"/> </div></td>
            </tr>
        </table>
        <br> 
        <h2 align="center">Sistema de Mensajeria</h2>
        <br> 
        <form name="forma" method="post" action="acceso.php" onSubmit="return validar(this);">
            <!--NOMBRE DE LA PAGINA QUE HACE LA LLAMADA-->
            <table width="300" border="0" align="center" cellpadding="0" cellspacing="0"  >
                <tr>
                    <td> Usuario: </td>
                    <td><input name="login" type="text" id="login"  size="25" maxlength="25" onLoad="focus()"></td>
                </tr>
                <tr>
                    <td>Contrase&ntilde;a:</td>
                    <td><input name="password" type="password" id="password"  size="25" maxlength="20"></td>
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
        </form>
    </body>
</html>
