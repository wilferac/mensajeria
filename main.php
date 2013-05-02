<?php
session_start();
include("clases/clases.php");
include 'security/User.php';

	$operacion = new operacion ();
	//codigo antiguo para verificar el logueo :(
//if ($_SESSION['ingreso'] != 'ingresoseguro')
//	$operacion->redireccionar('No Puede entrar','index.php');
        $objUser = unserialize($_SESSION['currentUser']);
        //$objUser = new User();
//        echo($objUser->getStatus());
        if ($objUser->getStatus() != 1)
        {
            //$objUser->show();
            $operacion->redireccionar('No Puede entrar','index.php');
            return;
        }
//	$operacion->redireccionar('No Puede entrar','index.php');
        
        
        
?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<title>::Sistema de Mensajeria::</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
</head>

<frameset id="padre" rows="88,*" cols="*" framespacing="0" frameborder="NO" border="0">
  <frame src="encabezado.php" name="topFrame" noresize="noresize" scrolling="NO">
  <frame src="administrador.php" name="mainFrame" scrolling="auto"> 
</frameset>
<noframes>
<body>

</body></noframes>

</html>
