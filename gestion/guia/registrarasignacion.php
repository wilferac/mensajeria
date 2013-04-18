<?php

include ("../../clases/clases.php");
include ("../../param/param.php");

if ($_REQUEST) {

    $idtercero = $_REQUEST['documentotercero'];
    $asignardesde = $_REQUEST['asignardesde'];
    $asignarcantidad = $_REQUEST['asignarcantidad'];

    $asignarhasta = $asignardesde + $asignarcantidad;

    $idtercero = substr($idtercero, 0, strpos($idtercero, " -"));
    if ($idtercero != "") {
        /* $tercero = new tercero();
          $cond = "documento_tercero = $documentotercero";
          //$cond = "idtercero = $documentotercero";
          $restercero = $tercero->consultar($cond);
          if (mysql_num_rows($restercero)>0)
          {
          $datos = mysql_fetch_assoc($restercero);
          $idtercero = $datos['idtercero']; */
        $query = "SELECT asignacion_guias.inicial_asignacion AS desde, asignacion_guias.inicial_asignacion+asignacion_guias.cantidad_asignacion as hasta FROM asignacion_guias";

        $results = mysql_query($query);
        if (mysql_num_rows($results) > 0) {
            $encAsigDesde = false;
            $encAsigHasta = false;
            while (($datosAsig = mysql_fetch_assoc($results)) && (!$encAsigDesde && !$encAsigHasta)) {
                $desde = $datosAsig["desde"];
                $hasta = $datosAsig["hasta"];



                if ($asignardesde >= $desde && $asignardesde <= $hasta)
                    $encAsigDesde = true;
                if ($asignarhasta >= $desde && $asignarhasta <= $hasta)
                    $encAsigHasta = true;
            } //fin while

            if (!$encAsigDesde && !$encAsigHasta) {

                $asignacion_guia = new asignacion_guias();

                $asignacion_guia->sucursal_idsucursal = 1;
                $asignacion_guia->tercero_idtercero = $idtercero;
                $asignacion_guia->fecha_asignacion = date("d/m/Y");
                $asignacion_guia->hora_asignacion = date("g:i a");
                $asignacion_guia->inicial_asignacion = $asignardesde;
                $asignacion_guia->cantidad_asignacion = $asignarcantidad;
                $asignacion_guia->saldo_asignnacion = $asignarcantidad;
                $asignacion_guia->estado_asignacion = NULL;
                $asignacion_guia->observaciones_asignacion = 'ninguna';

                $resasignacion = $asignacion_guia->agregar();

                if ($resasignacion) {
                    echo "Registro exitoso";
                    echo "<script language='javascript'>
  document.getElementById('documentotercero').focus();
</script>";
                }
            } else {
                echo "Rango ya asignado";
            }
        }  // if mysql_num_rows($results) 
        else {
            echo '<div id=\'nodisponible\' class =\'nodisponible\' style=\'display:inline\'>El registro de Asignaciones está vacío</div>';
        }
    } else {
        echo '<div id=\'nodisponible\' class =\'nodisponible\' style=\'display:inline\'>Tercero no existe</div>';
    }
    //} // if idtercero != ""
} // if REQUEST
?>

