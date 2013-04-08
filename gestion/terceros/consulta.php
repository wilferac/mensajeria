<?
   include("../../clases/clases.php");

   include '../../security/User.php';
   include ('../../Menu.php');

   $objUser = unserialize($_SESSION['currentUser']);

   if ($objUser->getStatus() != 1)
   {
       $operacion->redireccionar('No Puede entrar', 'index.php');
       return;
   }
   if (!$objUser->checkRol("Usuario"))
   {
       die();
   }




   $tercero = new tercero();
   $sucursal = new sucursal();
   $tipo_identificacion = new tipo_identificacion();
   $operacion = new operacion();

   $noaplica = "N/A";
   $nombres = 'tercero';

   $vacio = true;

   $res2 = $tercero->consultar();
   if (mysql_num_rows($res2) > 0)
   {
       $dataSetini = "[";
       $dataSet = "";
       while ($filas = mysql_fetch_assoc($res2))
       {
           $idtercero = $filas["idtercero"];
           $sucursal_idsucursal = $filas["sucursal_idsucursal"];
           $tipo_identificacion_tercero = $filas["tipo_identificacion_tercero"];
           $documento_tercero = $filas["documento_tercero"];
           $nombres_tercero = $filas["nombres_tercero"];
           $apellidos_tercero = $filas["apellidos_tercero"];
           $direccion_tercero = $filas["direccion_tercero"];
           $telefono_tercero = $filas["telefono_tercero"];
           $telefono2_tercero = $filas["telefono2_tercero"];
           $celular_tercero = $filas["celular_tercero"];
           $email_tercero = $filas["email_tercero"];
           $usuario_tercero = $filas["usuario_tercero"];
           $tercero_idvendedor = $filas["tercero_idvendedor"];
           $comision_tercero = $filas["comision_tercero"];
           $observaciones_tercero = $filas["observaciones_tercero"];
           $estado = $filas ["estado"];





           if (isset($sucursal_idsucursal))
           {
               $cond = "idsucursal=$sucursal_idsucursal";
               $res = $sucursal->consultar($cond);
               $fila2 = mysql_fetch_assoc($res);
               $nombre_sucursal = $fila2["nombre_sucursal"];
           }



           $cond = "idtipo_identificacion=$tipo_identificacion_tercero";
           $res = $tipo_identificacion->consultar($cond);
           $fila2 = mysql_fetch_assoc($res);
           $nombre_tipo_identificacion = $fila2["nombre_tipo_identificacion"];


           $cond = "select  tipo_tercero.nombre_tipo_tercero from tipo_tercero,tercero_tipo, tercero
where tercero_tipo.tercero_idtercero = $idtercero AND tercero.idtercero =tercero_tipo.tercero_idtercero AND tercero_tipo.tipo_tercero_idtipo_tercero =  tipo_tercero.idtipo_tercero";
           $res = $operacion->consultar($cond);
           $fila2 = mysql_fetch_assoc($res);
           $nombre_tipo_tercero = $fila2["nombre_tipo_tercero"];

           $wrapini = $wrapfin = "";
           if (strtolower($nombre_tipo_tercero) == 'aliado')
           {
               $wrapini = "<a target=\'_blank\' title=\'Ver detalle Aliado: $documento_tercero \' href=\'consultadetalle.php?nombre=$nombres&id=$idtercero\' onClick=\'return wo(this);\'>";
               $wrapfin = "</a>";
           }


           $nombres_vendedor = "N/A";
           $apellidos_vendedor = "";
           if (!empty($tercero_idvendedor))
               if ($tercero_idvendedor != 1)
               {
                   $cond = "idtercero=$tercero_idvendedor";
                   $res = $tercero->consultar($cond);
                   $fila2 = mysql_fetch_assoc($res);
                   $nombres_vendedor = $fila2["nombres_tercero"];
                   $apellidos_vendedor = $fila2["apellidos_tercero"];
               }

           $vendedor = $nombres_vendedor . " " . $apellidos_vendedor;

           if ($nombres_tercero != 'nobody')
           {
               $linkeliminar = "<a href=\'delete.php?nombre=$nombres&documento=$documento_tercero&id=$idtercero&estado=$estado\' onClick=\'return(confirmar())\'><img src=\'../../imagenes/borrar.jpeg\' /></a>";
               $linkmodificar = "<a href=\'edit.php?nombre=$nombres&documento=$documento_tercero&id=$idtercero\'><img src=\'../../imagenes/modificar.jpeg\' /></a>";

               if (strtolower($estado) == 'activo')
                   $estado = "<font color=green><b>$estado</b></font>";
               else
                   $estado = "<font color=red>$estado</font>";

               $dataSet = $dataSet . "['$wrapini$nombres_tercero$wrapfin','$wrapini$apellidos_tercero$wrapfin','$wrapini$documento_tercero$wrapfin','$wrapini$nombre_tipo_tercero$wrapfin','$wrapini$usuario_tercero$wrapfin','$wrapini$comision_tercero$wrapfin','$wrapini$vendedor$wrapfin','$wrapini$direccion_tercero$wrapfin','$wrapini$telefono_tercero$wrapfin','$wrapini$telefono2_tercero$wrapfin','$wrapini$celular_tercero$wrapfin','$wrapini$email_tercero$wrapfin','$wrapini$observaciones_tercero$wrapfin','$wrapini$estado$wrapfin','$linkeliminar','$linkmodificar'],
";
           }
       }
       $dataSet = substr_replace($dataSet, "];", strlen($dataSet) - 1);
       $dataSet = $dataSetini . $dataSet;
       //echo $dataSet;
       $vacio = false;
   }
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Terceros</title>
        <style type="text/css" title="currentStyle">
            @import "../../media/css/demo_page.css";
            @import "../../media/css/demo_table.css";
            @import "../../media/media/css/TableTools.css";

        </style>


        <script language="javascript" type="text/javascript">
            function confirmar()
            {
                return confirm('¿Seguro(a) de Activar/Desactivar?');
            }
        </script>
        <script type="text/javascript" language="javascript" src="../../js/jquery.js"></script>
        <script type="text/javascript" language="javascript" src="../../js/jquery.dataTables.js"></script>
        <script type="text/javascript" language="javascript" src="../../js/funciones.js"></script>	
        <script type="text/javascript" charset="utf-8" src="../../media/media/js/ZeroClipboard.js"></script> 
        <script type="text/javascript" charset="utf-8" src="../../media/media/js/TableTools.js"></script> 
        <script type="text/javascript" charset="utf-8">
            /* Data set - can contain whatever information you want */

            var aDataSet = <?= $dataSet ?>
// $dataSet=$dataSet."['$nombres_tercero','$apellidos_tercero','$usuario_tercero',$direccion_tercero','$telefono_tercero','$telefono2_tercero','$celular_tercero','$email_tercero','$observaciones_tercero','$linkeliminar','$linkmodificar'			
            $(document).ready(function() {
                $('#dynamic').html('<table cellpadding="0" cellspacing="0"  border="0" class="display" id="example"></table>');
                $('#example').dataTable({
                    "aaData": aDataSet,
                    "aoColumns": [
                        {"sTitle": "Nombres"},
                        {"sTitle": "Apellidos"},
                        {"sTitle": "Documento"},
                        {"sTitle": "Tipo Tercero"},
                        {"sTitle": "Usuario"},
                        {"sTitle": "Comision"},
                        {"sTitle": "Vendedor asignado"},
                        {"sTitle": "Direccion"},
                        {"sTitle": "Telefono"},
                        {"sTitle": "Telefono2"},
                        {"sTitle": "Celular"},
                        {"sTitle": "Email"},
                        {"sTitle": "Observaciones"},
                        {"sTitle": "Estado"},
                        {"sTitle": "Activar/Desact."},
                        {"sTitle": "Modificar"}
                    ],
                    "sDom": 'T<"clear">lfrtip', "oTableTools": {"aButtons": ["copy", "xls", {"sExtends": "pdf",
                                "sPdfOrientation": "landscape", "sPdfMessage": "Reporte"}, "print"]}
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
        <script src="../../SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
        <link href="../../SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css">           
    </head>

    <body id="dt_example">
        <?
           $objMenu = new Menu($objUser);
           $objMenu->generarMenu();
//           $operacion->menu();
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
                Tercero
            </div>
            <p>&nbsp;</p>
            <table class="display"><tr>
                    <td>
                        <a href="add.php">Crear Terceros</a></td>
                </tr>
                <tr>
                    <td>
                        <a href="addAliaPV.php">Crear Puntos de Venta o Aliados</a>
                    </td>
                </tr>
            </table>
            <p>&nbsp;</p>
            <?
               if ($vacio)
               {
                   ?>
                   <div align="center" style="color:#FF0000">No hay datos para mostrar</div>
                   <?
               }
            ?>
            <div id="dynamic"></div>
            <div class="spacer"></div>
        </div>
    </body>
</html>
