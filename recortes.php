<?php
   //incluir seguridad
   include '../../security/User.php';
   include ('../../Menu.php');

   $objUser = unserialize($_SESSION['currentUser']);

   if ($objUser->getStatus() != 1)
   {
       $operacion->redireccionar('No Puede entrar', 'index.php');
       return;
   }
   
   
   
   //generar menu
   $objMenu = new Menu($objUser);
   $objMenu->generarMenu();
   
   
?>
