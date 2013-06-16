<?
include ("clases/clases.php");
//session_start();
session_destroy();

 $operacion = new operacion();
 $operacion->redireccionar("Volver a Acceso al Sistema","index.php");

?>
