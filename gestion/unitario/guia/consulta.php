<?php
   session_start();
   include("../../../clases/clases.php");

   include "../../../security/User.php";
   include "../../../Menu.php";

   $objUser = unserialize($_SESSION['currentUser']);
   //$objUser = new User();
//        echo($objUser->getStatus());
   if ($objUser->getStatus() != 1)
   {
       //$objUser->show();
       $operacion->redireccionar('No Puede entrar', 'index.php');
       return;
   }


//$producto = new producto();
//$tipo_producto = new tipo_producto();
//
//$nombres = 'producto';
//
//$vacio = true;
//
//$res2 = $producto->consultar();
//query a guia
   $query2 = "select g.idguia , g.numero_guia , g.causal_devolucion_idcausal_devolucion,
            cd.nombre_causal_devolucion, g.tercero_iddestinatario,
            c1.idciudad as  ciudad_idorigen, c1.nombre_ciudad as ciudad_nombreorigen,
            c2.idciudad as  ciudad_iddestino, c2.nombre_ciudad as ciudad_nombredestino,
            p.idproducto, p.nombre_producto, p.tipo_producto_idtipo_producto, tp.nombre_tipo_producto,
            t.idtercero , t.documento_tercero, t.nombres_tercero, t.apellidos_tercero, 
            t.direccion_tercero,
            d.documento_destinatario, d.nombres_destinatario
            from guia g inner join  tercero t on g.tercero_idremitente = t.idtercero  
            inner join ciudad c1 on c1.idciudad = g.ciudad_idorigen inner join ciudad c2 on c2.idciudad = g.ciudad_iddestino
            inner join producto p on p.idproducto = g.producto_idproducto
            inner join tipo_producto tp on tp.idtipo_producto = p.tipo_producto_idtipo_producto
            inner join causal_devolucion cd on cd.idcausal_devolucion = g.causal_devolucion_idcausal_devolucion
            left join destinatario d on d.iddestinatario = g.tercero_iddestinatario
            ";

   $results2 = mysql_query($query2) or die(mysql_error());

   $dataSetini = "[";
   $dataSet = "";

   while ($fila = mysql_fetch_assoc($results2))
   {
//                $tercero_idtercero = $datosAsig["tercero_idtercero"];
       //capturo los datos del tercero que envia
//    $idtercero = $fila["idtercero"];
       $estadoGuia = $fila["causal_devolucion_idcausal_devolucion"];
       $dniDestinatario = $fila["documento_destinatario"];
       $nomDestinatario = $fila["nombres_destinatario"];
       $estadoGuiaCausal = $fila["nombre_causal_devolucion"];
       $iddestinatario = $fila["tercero_iddestinatario"];
       if ($iddestinatario == NULL)
       {
           $dniDestinatario = "Incompleto";
           $nomDestinatario = "Incompleto";
       }

       $idGuia = $fila["idguia"];

       $numeroGuia = $fila["numero_guia"];
       $documento_tercero = $fila["documento_tercero"];
       $nombres_tercero = $fila["nombres_tercero"];
       $apellidos_tercero = $fila["apellidos_tercero"];
//    $direccion_tercero = $fila["direccion_tercero"];
//    $idTipoPro = $fila["tipo_producto_idtipo_producto"];
       $nomtp = $fila["nombre_tipo_producto"];
       //capturo los del destino y de el origen del paquete
       $nomOrigen = $fila["ciudad_nombreorigen"];
       $nomDestino = $fila["ciudad_nombredestino"];
//    $idProducto = $fila["idproducto"];
       $nomProducto = $fila["nombre_producto"];

       if ($estadoGuia != 3)
       {
           $resaltar = "";
           if ($estadoGuia == 2)
           {
               $resaltar = "color: red";
           }

           $linkeliminar = "<button style=\"width: 70px; " . $resaltar . " \"  type=\'button\' onclick=\'abrir(\"delete.php?idGuia=$idGuia\")\'>$estadoGuiaCausal</button>";
           $linkmodificar = "<a  = href=\'../../ordendeservicio/addosunitario.php?idGuiaFill=$numeroGuia\'><img src=\'../../imagenes/modificar.jpeg\' /></a>";
       }
       else
       {
           $linkeliminar = "Entregado";
           $linkmodificar = "Entregado";
       }

       $imprimir = "<button type=\'button\' onclick=\'abrir(\"printCorporativo.php?idGuia=$idGuia\")\'>Imprimir</button>";
       //$editar = "<button type=\'button\' onclick=\'abrir(\"../../ordendeservicio/addosunitario.php?idGuiaFill=$idGuia\")\'>Editar</button>";
//acumulo en el dataset
       $dataSet = $dataSet . "['$numeroGuia','$documento_tercero','$nombres_tercero','$nomtp','$nomOrigen','$nomDestino','$dniDestinatario','$nomDestinatario','$linkeliminar','$imprimir','$linkmodificar'],";
   }

   $dataSet = substr_replace($dataSet, "];", strlen($dataSet) - 1);
   $dataSet = $dataSetini . $dataSet;
//echo $dataSet;
   $vacio = false;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Guias</title>
        <style type="text/css" title="currentStyle">
            @import "../../../media/css/demo_page.css";
            @import "../../../media/css/demo_table.css";
            @import "../../../media/media/css/TableTools.css";
        </style>
        <script language="javascript" type="text/javascript">
            function confirmar()
            {
                return confirm('¿Seguro(a) de Eliminar?');
            }
            function abrir(link)
            {
                window.open(link, "mywindow", "menubar=0,resizable=0,width=500,height=500");
                return;
            }
        </script>
        <script type="text/javascript" language="javascript" src="../../../js/jquery.js"></script>
        <script type="text/javascript" language="javascript" src="../../../js/jquery.dataTables.js"></script>
        <script type="text/javascript" charset="utf-8" src="../../../media/media/js/ZeroClipboard.js"></script>
        <script type="text/javascript" charset="utf-8" src="../../../media/media/js/TableTools.js"></script>
        <script type="text/javascript" charset="utf-8">
            /* Data set - can contain whatever information you want */

            var aDataSet = <?= $dataSet ?>;

            $(document).ready(function() {
                /*
                 * Support functions to provide a little bit of 'user friendlyness' to the textboxes in
                 * the footer
                 */
                $.fn.dataTableExt.afnFiltering.push(function(oSettings, aData, iDataIndex) {
                    var checked = $('#check').is(':checked');
                    //alert("entro");
                    if (checked && aData[6] == "Incompleto") {
//alert("entro");
                        return true;
                    }
                    else if (!checked) {
                        return true;
                    }
                    return false;
                });


                $('#dynamic').html('<table cellpadding="0" cellspacing="0"  border="0" class="display" id="example"></table>');
                var oTable = $('#example').dataTable({
                    "oLanguage": {
                        "sSearch": "Busqueda:"
                    },
                    "aaData": aDataSet,
                    "aoColumns": [
                        {"sTitle": "N. Guia"},
                        {"sTitle": "Remite C.C."},
                        {"sTitle": "Remite Nombre"},
                        {"sTitle": "Tipo"},
                        {"sTitle": "Origen"},
                        {"sTitle": "Destino"},
                        {"sTitle": "Destinatario C.C."},
                        {"sTitle": "Destinatario"},
                        {"sTitle": "Estado"},
                        {"sTitle": "Imprimir"},
                        {"sTitle": "Editar"}
                    ],
                    "sDom": 'T<"clear">lfrtip', "oTableTools": {"aButtons": ["copy", "xls", {"sExtends": "pdf",
                                "sPdfOrientation": "landscape", "sPdfMessage": "Reporte"}, "print"]}
                });

                $('#check').on("click", function(e) {
                    oTable.fnDraw();
                });

            });
        </script>

        <script language="javascript">

            parent.frames[0].document.getElementById("a1").innerHTML = "";
            parent.frames[0].document.getElementById("a2").innerHTML = "";
            parent.frames[0].document.getElementById("a3").innerHTML = "";

            parent.frames[0].document.getElementById("s1").style.visibility = "hidden";
            parent.frames[0].document.getElementById("s2").style.visibility = "hidden";

            parent.frames[0].document.getElementById("a1").innerHTML = "Principal";
            parent.frames[0].document.getElementById("a1").href = "redireccionador.php";
            parent.frames[0].document.getElementById("s1").style.visibility = "visible";

            parent.frames[0].document.getElementById("a2").innerHTML = "Gestión";
            parent.frames[0].document.getElementById("a2").href = "gestion.php";
        </script>        

    </head>
    <body id="dt_example">
<?
   $objMenu = new Menu($objUser);
   $objMenu->generarMenu();
//   $operacion = new operacion();
//   $operacion->menu();
?>
        <div id="container">
        <?
           if (isset($_GET["mensaje"]))
           {
               ?> 

                   <div class="mensaje"><?= $_GET["mensaje"] ?></div>  

                   <?
               }
            ?>
            <div class="full_width big">
                <p>&nbsp;</p>
                Guias
            </div>
            <p>&nbsp;</p>
            <table class="display"><tr><td>
                        <a href="../../ordendeservicio/addosunitario.php">Crear Guia</a>
                    </td></tr></table> 
<?
   if ($vacio)
   {
       ?>
                   <div align="center" style="color:#FF0000">No hay datos para mostrar</div>
                   <?
               }
            ?>
            No Terminados: <input id="check" type="checkbox" name="option3" value="incompleto" /> 
            <div id="dynamic"></div>
            <div class="spacer"></div>
        </div>
    </body>
</html>





