<?php

include ("../../clases/clases.php");
include ("../../param/param.php");

include '../../security/User.php';

$objUser = unserialize($_SESSION['currentUser']);

if ($objUser->getStatus() != 1)
{
    $operacion->redireccionar('No Puede entrar', 'index.php');
    return;
}


if ($_REQUEST)
{
    //el cliente al que le voy a asignar el rango
    //$userId=$objUser->getId();
    $sucurId = $objUser->getIdSucursal();
    
    $idtercero = $_REQUEST['documentotercero'];
    $asignardesde = $_REQUEST['asignardesde'];
    $asignarcantidad = $_REQUEST['asignarcantidad'];
    
    $tipo = $_REQUEST['tipo'];
    
    //se calcula el final del rango
    $asignarhasta = $asignardesde + $asignarcantidad;

    $idtercero = substr($idtercero, 0, strpos($idtercero, " -"));
    if ($idtercero != "")
    {
        /* $tercero = new tercero();
          $cond = "documento_tercero = $documentotercero";
          //$cond = "idtercero = $documentotercero";
          $restercero = $tercero->consultar($cond);
          if (mysql_num_rows($restercero)>0)
          {
          $datos = mysql_fetch_assoc($restercero);
          $idtercero = $datos['idtercero']; */
        $query = "SELECT ag.asigTipo, ag.inicial_asignacion AS desde, ag.inicial_asignacion+ag.cantidad_asignacion AS hasta FROM asignacion_guias ag 
        WHERE ag.asigTipo = $tipo AND ag.inicial_asignacion <= $asignarhasta
        ORDER BY  ag.inicial_asignacion DESC";
        $desde = 0;
        $hasta = 0;

        $colicion = false;

        if(!$results = mysql_query($query))
        {
            die(mysql_error());
        }
//        if (mysql_num_rows($results) > 0)
//        {

            while (($datosAsig = mysql_fetch_assoc($results)))
            {
                $desde = $datosAsig["desde"];
                $hasta = $datosAsig["hasta"];

                //ya no necesito verificar mas ya que no se encuentra en el rango
                if ($asignardesde > $hasta)
                {
                    break;
                }


                //verifico colicion de la  base de datos con los nuevos
                if (($desde >= $asignardesde && $desde <= $asignarhasta) || ($hasta >= $asignardesde && $hasta <= $asignarhasta))
                {
                    $colicion = true;
                    break;
                }
                //verifico colicion de los nuevos con la BD
                if (($asignardesde >= $desde && $asignardesde <= $hasta ) || ($asignarhasta >= $desde && $asignarhasta <= $hasta ))
                {
                    $colicion = true;
                    break;
                }
            } //fin while

            if (!$colicion)
            {
                //aca inserto el nuevo rango de asignaciones

                $asignacion_guia = new asignacion_guias();

                $asignacion_guia->sucursal_idsucursal = $sucurId;
                $asignacion_guia->tercero_idtercero = $idtercero;
                $asignacion_guia->fecha_asignacion = date("d/m/Y");
                $asignacion_guia->hora_asignacion = date("g:i a");
                $asignacion_guia->inicial_asignacion = $asignardesde;
                $asignacion_guia->cantidad_asignacion = $asignarcantidad;
                $asignacion_guia->saldo_asignnacion = $asignarcantidad;
                $asignacion_guia->estado_asignacion = NULL;
                $asignacion_guia->observaciones_asignacion = 'ninguna';
                $asignacion_guia->tipo =$tipo;

                $resasignacion = $asignacion_guia->agregar();

                if ($resasignacion)
                {
                    echo "Registro exitoso";
                    echo "<script language='javascript'>
  document.getElementById('documentotercero').focus();
</script>";
                }
            } else
            {
                echo "Rango ya asignado";
            }
//        }  // if mysql_num_rows($results) 
//        else
//        {
//            echo '<div id=\'nodisponible\' class =\'nodisponible\' style=\'display:inline\'>El registro de Asignaciones está vacío</div>';
//        }
    } else
    {
        echo '<div id=\'nodisponible\' class =\'nodisponible\' style=\'display:inline\'>Tercero no existe</div>';
    }
    //} // if idtercero != ""
} // if REQUEST
?>

