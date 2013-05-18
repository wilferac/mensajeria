<?
//session_start();
   include("clases/clases.php");
   include 'security/User.php';
   include ('Menu.php');

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
       $operacion->redireccionar('No Puede entrar', 'index.php');
       return;
   }
//        if(!$objUser->checkRol("Admin"))
//        {
//            echo("no eres admin");
//            return;
//        }
// $operacion = new operacion();
//if (!isset($_SESSION["ingreso"]))
// {
//
// $operacion->redireccionar("No Puede entrar","index.php");
// exit();
//}
?>
<html><head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <title>::Sistema de Mensajeria::</title>

        <link href="css/default.css" rel="stylesheet" type="text/css">

        <style type="text/css" title="currentStyle">
            @import "media/css/demo_page.css";	
        </style>

        <script language="javascript">
            parent.frames[0].document.getElementById("a1").innerHTML = "";
            parent.frames[0].document.getElementById("a2").innerHTML = "";
            parent.frames[0].document.getElementById("a3").innerHTML = "";

            parent.frames[0].document.getElementById("s1").style.visibility = "hidden";
            parent.frames[0].document.getElementById("s2").style.visibility = "hidden";

        </script>

    </head>
    <body id="dt_example">
<?php
   $objMenu = new Menu($objUser);
   $objMenu->generarMenu();
   //$operacion -> menu();
?>
        <div class="marco" style="padding-top: 20px; padding-bottom: 20px;">

            <p align="center">
                <span class="class_login" style="font-size:20px;">BIENVENIDO AL SISTEMA DE MENSAJERIA DE A1</span>         </p>

        </div>
    </body></html>

