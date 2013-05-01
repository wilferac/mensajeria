<?php

//echo("muhahaha");
//session_start();
//include ("../../clases/clases.php");
//echo("muhahaha2");
include '../../security/User.php';
//echo("muhahaha3");

$objUser = unserialize($_SESSION['currentUser']);

if ($objUser->getStatus() != 1)
{
    $operacion->redireccionar('No Puede entrar', 'index.php');
    return;
}
if (!$objUser->checkRol('Usuario'))
{
    die('Acceso denegado');
}

$id = $_REQUEST['idAsig'];
$tipo = $_REQUEST['tipo'];

if ($tipo == 0)
{
    BuscarUsadas($id);
} 
else if ($tipo == 1)
{
    borrar($id);
}

//echo($id);
//return;
//echo("muhahaha4");
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function borrar($id)
{
    $query = ("UPDATE asignacion_guias SET estado_asignacion = 0 WHERE idasignacion_guias = $id ");
    if (mysql_query($query))
    {
        echo("Las guias se desasginaron correctamente");
    } else
    {
        die("ocurrio un error al desasignar " . mysql_error());
    }
}

function BuscarUsadas($id)
{
    //echo("buscando usadas");
    $query = ("CALL findUsedGuias(1) ");
    if ($res = mysql_query($query)) {
        echo("Listado de Guias Usadas:<br>");
        echo("<ul>");
        while($fila=  mysql_fetch_assoc($res))
        {
            $num=$fila['numero_guia'];
            echo("<li>$num</li>");
        }
        echo("</ul>");
        echo("<button id='desasigGuias' onclick='llamarDesasginar(event,$id, 1, true)'>Desasignar</button>  </td>");
        //echo("Las guias se desasginaron correctamente");
    } else {
        die("ocurrio un error al consultar las guias " . mysql_error());
    }
}

?>
