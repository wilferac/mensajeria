<?php
include ("../../clases/clases.php");
include ("../../param/param.php");

if ($_REQUEST) {

    $idtercero = $_REQUEST['documentotercero'];
    $idtercero = substr($idtercero, 0, strpos($idtercero, " -"));

    if ($idtercero != "") {
        // $query = "SELECT saldo_asignnacion,asignacion_guias.inicial_asignacion AS desde, asignacion_guias.inicial_asignacion+asignacion_guias.cantidad_asignacion as hasta, fecha_asignacion, hora_asignacion FROM tercero, asignacion_guias WHERE tercero.documento_tercero = $documentotercero AND asignacion_guias.tercero_idtercero = tercero.idtercero";

        $query = "SELECT ag.idasignacion_guias ,ag.asigTipo, ag.cantidad_asignacion,
IF(ag.asigTipo = 2, s.facturacion,NULL) AS prefijo,
ag.saldo_asignnacion,ag.inicial_asignacion AS desde, ag.inicial_asignacion+(ag.cantidad_asignacion-1) AS hasta, ag.fecha_asignacion 
FROM  asignacion_guias ag
INNER JOIN sucursal s ON s.idsucursal = ag.sucursal_idsucursal
WHERE ag.tercero_idtercero = $idtercero and estado_asignacion = 1";

        $results = mysql_query($query);
        if (mysql_num_rows($results) > 0) {
            ?>
            <style type="text/css">
                <!--
                .Estilo4 {color: #FFFFFF; font-weight: bold; font-family: Georgia, "Times New Roman", Times, serif; }
                -->
            </style>


            <script type="text/javascript">


                function desAsignar(e, id, quedan, total)
                {
                    e.preventDefault();
                    if (confirm("¿seguro que quiere desasignar estas guias?"))
                    {
                        if (quedan < total)
                        {
                           
                                //alert("desasignando usadas!");
                                //aca debo mostrar un resumen de las guias que ya se usaron
                                //tipo = 0 me muestra las que ya estan usadas en dicho rango
                                llamarDesasginar(e,id, 0);
                                return false;
                         

                        }
                        //alert("desasignando!");

                    }
                    else
                        return false;
                    //llamo para que desasigne
                    llamarDesasginar(e,id, 1);

                }

                function llamarDesasginar(e,id, tipo, confirmar)
                {
                    e.preventDefault();
                    confirmar = typeof(confirmar) != 'undefined' ? confirmar : false;
                    //le paso tipo 1 para que borre
                    if (!confirmar || confirm("¿seguro que quiere desasignar?"))
                    {
                        //return;
                        var dataString = 'idAsig=' + id + '&tipo=' + tipo;
                        //alert (dataString);
                        $.ajax({
                            type: "POST",
                            url: "cancelarAsignacion.php",
                            data: dataString,
                            success: function(data) {
                                $('#info').html(data);
                            }});
                    }


                }

            </script>


            <table width="400" border="1" cellpadding="0" cellspacing="0">
                <tr>
                    <td height="29" colspan="6">
                        <div align="center"><strong>Asignaciones</strong></div>     </td>
                </tr>
                <tr bgcolor="#0066FF">
                    <td width="72"><div align="center" class="Estilo4">Prefijo</div></td>
                    <td width="72"><div align="center" class="Estilo4">Desde</div></td>
                    <td width="90"><div align="center" class="Estilo4">Hasta</div></td>
                    <td width="113"><div align="center" class="Estilo4">Fecha y Hora</div></td>
                    <td width="115" height="29"><div align="center" class="Estilo4">Disponibles</div></td>
                    <td width="100" ><div align="center" class="Estilo4">Desasignar</div></td>
                </tr>
                <?
                while ($datosAsig = mysql_fetch_assoc($results)) {
                    $desde = $datosAsig["desde"];
                    $hasta = $datosAsig["hasta"];
                    $fecha = $datosAsig["fecha_asignacion"];
                    $prefijo = $datosAsig["prefijo"];
                    //$hora = $datosAsig["hora_asignacion"];
                    $saldo_asignnacion = $datosAsig["saldo_asignnacion"];
                    $id = $datosAsig["idasignacion_guias"];
                    $total = $datosAsig["cantidad_asignacion"];

                    //manejo los que llegan sin prefijo (credito)
                    $prefijo = !empty($prefijo) ? $prefijo : "-";

                    echo "<tr align='center'>
                        <td>$prefijo</td>
				   <td>$desde</td>
				  <td>$hasta</td>
				  <td>$fecha</td>
				  <td>$saldo_asignnacion</td>
                                  <td> <button id='desasigGuias' onclick='desAsignar(event,$id,$saldo_asignnacion,$total)'>Desasignar</button>  </td>
				  </tr>
				  ";
                } //fin while
                ?>

            </table>
            <?
        }  // if mysql_num_rows($results) 
        else {
            echo '<div id=\'nodisponible\' class =\'nodisponible\' style=\'display:inline\'>Sin Gu&iacute;as asignadas</div>';
        }
    } // if documentotercero != ""
} // if REQUEST
?>
