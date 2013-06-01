<?php
session_start();
include "../../../security/User.php";

$objUser = unserialize($_SESSION['currentUser']);
if (!$objUser->checkRol('Admin'))
{
  exit("Solo admin puede anular guias");
}
$idGuia = $_REQUEST['idGuia'];

$query = "UPDATE guia SET estado = 0 WHERE numero_guia = '$idGuia' AND estado = 1";
if ($res = mysql_query($query))
{
  echo("Guia Numero $idGuia anulada.");
}
else
{
  die("No se pudo Anular la guia.");
}
?>
