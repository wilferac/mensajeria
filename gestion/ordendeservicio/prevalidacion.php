<?php

session_start();
include ("../../conexion/conexion.php");


include "../../security/User.php";

$objUser = unserialize($_SESSION['currentUser']);


if ($_REQUEST)
{

    $numguia = $_REQUEST['numguia'];
    $encGuia = false;
    $encAsig = false;



    if ($numguia != "")
    {
        $stringsContado = new ArrayObject();
        $stringsContado[0] = "BOCO";
        $stringsContado[1] = "BACO";
        $stringsContado[2] = "CACO";

        $entroFor = false;
        //intentando llenar una guia contado unitario
        foreach ($stringsContado as $find)
        {
            $pos = strpos($numguia, $find);
            //echo($pos . ' la pos <br>');
            if ($pos !== false)
            {
                //elimino el que encontre para comparar
                $numguiaCorta = trim($numguia, $find);
                //verifico la guia contado
                $query = "SELECT * FROM asignacion_guias ag
INNER JOIN sucursal s ON s.`idsucursal` = ag.`sucursal_idsucursal`
WHERE $numguiaCorta >= inicial_asignacion AND $numguiaCorta <= inicial_asignacion+(cantidad_asignacion -1) AND asigTipo = 2
AND s.`facturacion` = '$find' and ag.estado_asignacion = 1";
                $results = mysql_query($query) or die('error al consultar contado ' . mysql_error());
                $datosAsig = mysql_fetch_assoc($results);
                if (mysql_num_rows($results) > 0)
                {
                    $idAsig = $datosAsig['idasignacion_guias'];
                    echo "<script>document.getElementById('idAsignacion').value = $idAsig;</script>";
                    $encAsig = true;
                }
                else
                    $encAsig = false;
                //marco la entrada al for
                $entroFor = true;


                break;
            }
        }

        //verifico si es una guia de masivo
        if (!strstr($numguia, 'MM') && !strstr($numguia, 'CC'))
        {
            //verifico si es una guia credito
            if (!$entroFor)
            {
                $query = "SELECT * FROM asignacion_guias WHERE $numguia >= inicial_asignacion AND $numguia <= inicial_asignacion+(cantidad_asignacion-1) and asigTipo = 1 and estado_asignacion = 1";

                try
                {
                    $results = mysql_query($query) or die('error al consultar credito ' . mysql_error());

                    $datosAsig = mysql_fetch_assoc($results);
                    if (mysql_num_rows($results) > 0)
                    {
                        $idAsig = $datosAsig['idasignacion_guias'];
                        echo "<script>document.getElementById('idAsignacion').value = $idAsig;</script>";
                        $encAsig = true;
                    } else
                    {
                        $encAsig = false;
                    }
                } catch (Exception $e)
                {
                    //no hago nada
                }
            }



            if (!$encAsig)
            {
                echo "<script>alert('Antes de digitar la guia esta debe estar asignada a un cliente');</script>";
                die("Error (005)");
            }
        }
        else
        {
            $encAsig=true;
            echo("esto es una guia de masivo<br>");
        }








        //query para que pasen los que estan a medio llenar
        $query = "SELECT * FROM guia WHERE numero_guia = '$numguia' and tercero_iddestinatario IS NOT NULL";

        //$query = "SELECT * FROM guia WHERE numero_guia = $numguia";
        $results = mysql_query($query) or die('error2' . $query);

        if (mysql_num_rows($results) > 0)
            $encGuia = true;
        else
            $encGuia = false;


        if ($encAsig && $encGuia)
        {

            if ($objUser->checkRol('Admin'))
            {
                echo("<br>Editando una Guia ya creada<br>");
                fillEditData($numguia);



                // echo("aca toca rellenar datos");
                return;
            } else
            {
                echo "<script>
				ElementosClientesInvisibles();
			    ElementosDatosABuscarDestinatarioInvisibles();
				ElementosDestinatariosInvisibles();
				</script>
			  <div id='nodisponible' class ='nodisponible' style='display:inline'>Guia ya Asignada</div>
			  <input type='hidden' id='encGuia' name='encGuia' value='$encGuia'></input>
			  <input type='hidden' id='encAsig' name='encAsig' value='$encAsig'></input>";
                return;
            }
        }

        //esta parte es para validar la asignacion de las guias.
        else if ($encAsig)
        {
            $tercero_idtercero = $datosAsig["tercero_idtercero"];

            $query = "SELECT * FROM tercero WHERE idtercero = $tercero_idtercero";
            $results = mysql_query($query);

            $datosCliente = mysql_fetch_assoc($results);

            $idtercero = $datosCliente["idtercero"];
            $documento_tercero = $datosCliente["documento_tercero"];
            $nombres_tercero = $datosCliente["nombres_tercero"];
            $apellidos_tercero = $datosCliente["apellidos_tercero"];
            $direccion_tercero = $datosCliente["direccion_tercero"];


            echo "
			
				<script>
				ElementosClientesInvisibles();
ElementosClientesVisibles(true,'$idtercero','$documento_tercero','$nombres_tercero','$apellidos_tercero','$direccion_tercero');
				ElementosDestinatariosInvisibles();
				</script>			
			
				<script>
				document.getElementById('titulodestinatarios').style.visibility='visible';
				ElementosDatosABuscarDestinatarioVisibles();
				ElementosDestinatariosInvisibles();
                                document.getElementById('cccliente').focus();
				</script>
				<input type='hidden' id='encGuia' name='encGuia' value='$encGuia'></input>
			  <input type='hidden' id='encAsig' name='encAsig' value='$encAsig'></input>";
            // return;
        }
        if ($encAsig && !$encGuia)
        {
            //verifico si la guia fue salvada temporalmente :O
            $query2 = "select g.idguia , g.numero_guia ,
            c1.idciudad as  ciudad_idorigen, c1.nombre_ciudad as ciudad_nombreorigen,
            c2.idciudad as  ciudad_iddestino, c2.nombre_ciudad as ciudad_nombredestino,
            p.idproducto, p.nombre_producto, p.tipo_producto_idtipo_producto, tp.nombre_tipo_producto,
            t.idtercero , t.documento_tercero, t.nombres_tercero, t.apellidos_tercero, 
            t.direccion_tercero, g.remitenteInfo
            from guia g inner join  tercero t on g.tercero_idremitente = t.idtercero  
            inner join ciudad c1 on c1.idciudad = g.ciudad_idorigen inner join ciudad c2 on c2.idciudad = g.ciudad_iddestino
            inner join producto p on p.idproducto = g.producto_idproducto
            inner join tipo_producto tp on tp.idtipo_producto = tipo_producto_idtipo_producto
            where g.numero_guia = '$numguia'  and g.tercero_iddestinatario IS NULL";

            $results2 = mysql_query($query2) or die('error2' . $query2);

            if ($fila = mysql_fetch_assoc($results2))
            {
//                $tercero_idtercero = $datosAsig["tercero_idtercero"];
                //capturo los datos del tercero que envia
                $idtercero = $fila["idtercero"];
                $documento_tercero = $fila["documento_tercero"];
                $nombres_tercero = $fila["nombres_tercero"];
                $apellidos_tercero = $fila["apellidos_tercero"];
                $direccion_tercero = $fila["direccion_tercero"];
                $idTipoPro = $fila["tipo_producto_idtipo_producto"];
                $nomtp = $fila["nombre_tipo_producto"];
                //capturo los del destino y de el origen del paquete
                $idOrigen = $fila["ciudad_idorigen"];
                $idDestino = $fila["ciudad_iddestino"];
                $idProducto = $fila["idproducto"];
                $nomProducto = $fila["nombre_producto"];

                $remitenteInfo = $fila["remitenteInfo"];
                //lo igualo al value en el formulario ya que en la Bd esta con mayuscula inicial
                $nomProducto = strtolower($nomProducto);
                //$idTipoProducto = $operaciones->calcularTipoProducto($idpaisdorigen, $iddepartamentoorigen, $idciudadorigen, $nombreciudaddestino, $idciudaddestino);


                echo "<script>
//                    alert('entro aca');
                    ElementosClientesInvisibles();
                    ElementosClientesVisibles(true,'$idtercero','$documento_tercero','$nombres_tercero','$apellidos_tercero','$direccion_tercero');
                   // document.getElementById('tipoproducto').value = '$nomProducto';
                   // document.getElementById('idtipoproducto').value = '$idProducto';
                   // document.getElementById('idciudadorigen2').value= '$idOrigen';    
                   // document.getElementById('ciudaddestino').value= '$idDestino';
                    //    document.getElementById('nombreproducto').value= '$idProducto';

                document.getElementById('tipoproducto').value = '$nomtp';
		document.getElementById('idtipoproducto').value = '$idTipoPro';
                    document.getElementById('extraRemitente').value = '$remitenteInfo';
$(document).ready
$(function() {
   $('#idciudadorigen2').val('$idOrigen');
       $('#nombreproducto').val('$nomProducto');
           $('#ciudaddestino').val('$idDestino');
});
                        

                    document.getElementById('capadatosguia').style.visibility='visible';
                    document.getElementById('savetemp').style.visibility='hidden';
                    
                    
                    
				</script>			
			
				<script>
				document.getElementById('titulodestinatarios').style.visibility='visible';
                                ElementosDatosABuscarDestinatarioVisibles();
// ELEMENTOS DEL DESTINATARIO VISIBLES
document.getElementById('datoArecordar').focus();
				</script>
				";
                return;
            }
            return;

//            if (mysql_num_rows($results) > 0)
//                $encGuia = true;
//            else
//                $encGuia = false;
//
//
//            echo "<script>
//					ElementosDatosABuscarDestinatarioInvisibles();
//					ElementosClientesInvisibles();
//					ElementosClientesAbuscarVisibles();
//					ElementosDestinatariosInvisibles();
//			  		document.getElementById('cccliente').focus();
//			   </script>
//			   <input type='hidden' id='encGuia' name='encGuia' value='$encGuia'></input>
//			  <input type='hidden' id='encAsig' name='encAsig' value='$encAsig'></input>";
        }
    } // if numguia != ""
} // if REQUEST

function fillEditData($numguia)
{
    $query2 = "SELECT g.* , des.*,
            c1.idciudad AS  ciudad_idorigen, c1.nombre_ciudad AS ciudad_nombreorigen,
            c2.idciudad AS  ciudad_iddestino, c2.nombre_ciudad AS ciudad_nombredestino,
            p.idproducto, p.nombre_producto, p.tipo_producto_idtipo_producto, tp.nombre_tipo_producto,
            t.idtercero , t.documento_tercero, t.nombres_tercero, t.apellidos_tercero, 
            t.direccion_tercero, g.remitenteInfo
            FROM guia g INNER JOIN  tercero t ON g.tercero_idremitente = t.idtercero  
            INNER JOIN ciudad c1 ON c1.idciudad = g.ciudad_idorigen INNER JOIN ciudad c2 ON c2.idciudad = g.ciudad_iddestino
            INNER JOIN producto p ON p.idproducto = g.producto_idproducto
            INNER JOIN tipo_producto tp ON tp.idtipo_producto = tipo_producto_idtipo_producto
            INNER JOIN destinatario des ON des.iddestinatario = g.tercero_iddestinatario
            WHERE g.numero_guia = '$numguia' AND g.causal_devolucion_idcausal_devolucion <> 3 ";

    $results2 = mysql_query($query2) or die('error2' . $query2);

    if ($fila = mysql_fetch_assoc($results2))
    {
//                $tercero_idtercero = $datosAsig["tercero_idtercero"];
        //capturo los datos del tercero que envia
        $idtercero = $fila["idtercero"];
        $documento_tercero = $fila["documento_tercero"];
        $nombres_tercero = $fila["nombres_tercero"];
        $apellidos_tercero = $fila["apellidos_tercero"];
        $direccion_tercero = $fila["direccion_tercero"];
        $idTipoPro = $fila["tipo_producto_idtipo_producto"];
        $nomtp = $fila["nombre_tipo_producto"];
        //capturo los del destino y de el origen del paquete
        $idOrigen = $fila["ciudad_idorigen"];
        $idDestino = $fila["ciudad_iddestino"];
        $idProducto = $fila["idproducto"];
        $nomProducto = $fila["nombre_producto"];

        $remitenteInfo = $fila["remitenteInfo"];
        //lo igualo al value en el formulario ya que en la Bd esta con mayuscula inicial
        $nomProducto = strtolower($nomProducto);
        //$idTipoProducto = $operaciones->calcularTipoProducto($idpaisdorigen, $iddepartamentoorigen, $idciudadorigen, $nombreciudaddestino, $idciudaddestino);


        echo "<script>
//            alert('entro aca');
            ElementosClientesInvisibles();
            ElementosClientesVisibles(true,'$idtercero','$documento_tercero','$nombres_tercero','$apellidos_tercero','$direccion_tercero');
            document.getElementById('tipoproducto').value = '$nomtp';
            document.getElementById('idtipoproducto').value = '$idTipoPro';
            document.getElementById('extraRemitente').value = '$remitenteInfo';
                $(document).ready
                $(function() {
                    $('#idciudadorigen2').val('$idOrigen');
                    $('#nombreproducto').val('$nomProducto');
                    $('#ciudaddestino').val('$idDestino');
                });
                document.getElementById('capadatosguia').style.visibility='visible';
                document.getElementById('savetemp').style.visibility='hidden';
                </script>
                <script>
                    document.getElementById('titulodestinatarios').style.visibility='visible';
                    ElementosDatosABuscarDestinatarioVisibles();
                    // ELEMENTOS DEL DESTINATARIO VISIBLES
                    document.getElementById('datoArecordar').focus();
                 </script>";

        $encDestinatario = true;

        $iddestinatario = $fila["iddestinatario"];
        $documento_tercero = $fila["documento_destinatario"];
        $nombres_tercero = $fila["nombres_destinatario"];
        $apellidos_tercero = $fila["apellidos_destinatario"];
        $direccion_tercero = $fila["direccion_destinatario"];
        $telefono_destinatario = $fila["telefono_destinatario"];
        $celular_destinatario = $fila["celular_destinatario"];

        echo "<script>
ElementosDestinatariosVisibles(true,'$documento_tercero','$nombres_tercero','$apellidos_tercero','$direccion_tercero','$telefono_destinatario','$celular_destinatario');
//muestro la capa de datos de peso			
document.getElementById('capaPeso').style.visibility='visible';
//muestro la info extra
document.getElementById('labExtraDestinatario').style.visibility='visible';
                                        document.getElementById('extraDestinatario').style.visibility='visible';
			document.getElementById('direcciondestinatario').focus();
			//document.getElementById('direcciondestinatario').style.color='#00FA00';
			</script>
			 <input type='hidden' id='encDestinatario' name='encDestinatario' value='$encDestinatario'></input>
			 
			 <input type='hidden' id='iddestinatario' name='iddestinatario' value='$iddestinatario'></input>
			 <input type='hidden' id='nombres_terceroOrig' name='nombres_terceroOrig' value='$nombres_tercero'></input>
			 <input type='hidden' id='apellidos_terceroOrig' name='apellidos_terceroOrig' value='$apellidos_tercero'></input>
			 <input type='hidden' id='direccion_terceroOrig' name='direccion_terceroOrig' value='$direccion_tercero'></input>
			 <input type='hidden' id='telefono_destinatarioOrig' name='telefono_destinatarioOrig' value='$telefono_destinatario'></input>
			 <input type='hidden' id='celular_destinatarioOrig' name='celular_destinatarioOrig' value='$celular_destinatario'></input>
			 
			";






        return;
    } else
    {
        echo('Esta Guia ya fue entregada!');
    }
    return;
}

?>