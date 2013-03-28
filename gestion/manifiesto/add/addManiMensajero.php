<?php
   
   include '../../../security/User.php';
   include '../../../clases/Mensajero.php';
   include '../../../clases/DaoMensajero.php';
  // include '../../../conexion/conexion.php';

   $objUser = unserialize($_SESSION['currentUser']);

   if ($objUser->getStatus() != 1)
   {
       //$operacion->redireccionar('No Puede entrar', 'index.php');
       return;
   }
   $dao = new DaoMensajero();
   //$objMensajero = new Mensajero("", "", "");
   $arrayMensajeros=$dao->getAll();
   
   foreach ($arrayMensajeros as $objMen)
   {
       $objMen->show();
       echo("<br />");
   }
   
   
   echo("add mani mensajero :D");
   
   
   
   
   ?>
