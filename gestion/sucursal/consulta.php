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
       die("No tienes permiso");
   }


   $sucursal = new sucursal();
   $operacion = new operacion();
   $ciudad = new ciudad();


   $nombres = 'sucursal';

   $vacio = true;

   $res2 = $sucursal->consultar();
   if (mysql_num_rows($res2) > 0)
   {
       $dataSetini = "[";
       $dataSet = "";
       while ($filas = mysql_fetch_assoc($res2))
       {
           $idsucursal = $filas["idsucursal"];
           $ciudad_idciudad = $filas["ciudad_idciudad"];
           $codigo_sucursal = $filas["codigo_sucursal"];
           $nombre_sucursal = $filas["nombre_sucursal"];
           $direccion_sucursal = $filas["direccion_sucursal"];
           $observaciones_sucursal = $filas["observaciones_sucursal"];
           $activa = $filas["Activa"];


           $cond = "idciudad=$ciudad_idciudad";
           $res = $ciudad->consultar($cond);
           $fila2 = mysql_fetch_assoc($res);
           $nombre_ciudad = $fila2["nombre_ciudad"];

           if ($activa == 1)
               $activa = "<font color=green>SI ACTIVA</font>";
           elseif ($activa == 0)
               $activa = "<font color=red>NO ACTIVA</font>";
           $linkeliminar = "<a href=\'delete.php?nombre=$nombres&documento=$codigo_sucursal\' onClick=\'return(confirmar())\'><img src=\'../../imagenes/borrar.jpeg\' /></a>";
           $linkmodificar = "<a href=\'edit.php?nombre=$nombres&documento=$codigo_sucursal\'><img src=\'../../imagenes/modificar.jpeg\' /></a>";
           $dataSet = $dataSet . "['$codigo_sucursal','$nombre_ciudad','$activa','$nombre_sucursal','$direccion_sucursal','$observaciones_sucursal','$linkeliminar','$linkmodificar'],";
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
        <title>Sucursales</title>
        <style type="text/css" title="currentStyle">
            @import "../../media/css/demo_page.css";
            @import "../../media/css/demo_table.css";
            @import "../../media/media/css/TableTools.css";
        </style>
        <script language="javascript" type="text/javascript">
            function confirmar()
            {
                return confirm('¿Seguro(a) de Activa/Desactivar?');
            }
        </script>
        <script type="text/javascript" language="javascript" src="../../js/jquery.js"></script>
        <script type="text/javascript" language="javascript" src="../../js/jquery.dataTables.js"></script>
        <script type="text/javascript" charset="utf-8" src="../../media/media/js/ZeroClipboard.js"></script>
        <script type="text/javascript" charset="utf-8" src="../../media/media/js/TableTools.js"></script>
        <script type="text/javascript" charset="utf-8">
            /* Data set - can contain whatever information you want */

            var aDataSet = <?= $dataSet ?>
            $(document).ready(function() {
                $('#dynamic').html('<table cellpadding="0" cellspacing="0"  border="0" class="display" id="example"></table>');
                $('#example').dataTable({
                    "aaData": aDataSet,
                    "aoColumns": [
                        {"sTitle": "Codigo"},
                        {"sTitle": "Ciudad Sucursal"},
                        {"sTitle": "Activa"},
                        {"sTitle": "Nombre "},
                        {"sTitle": "Direccion"},
                        {"sTitle": "Observaciones"},
                        {"sTitle": "Activar\n/Desactivar"},
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

    </head>
    <body id="dt_example">
        <?
           $objMenu = new Menu($objUser);
           $objMenu->generarMenu();
//		   	$operacion = new operacion();
//			 $operacion -> menu();
        ?>
        <div id="container">
            <? if (isset($_GET["mensaje"]))
               {
                   ?> 

                   <div class="mensaje"><?= $_GET["mensaje"] ?></div>  

                   <?
               }
            ?>
            <div class="full_width big">
                <p>&nbsp;</p>
                Sucursal
            </div>


            <p>&nbsp;</p>
            <table class="display"><tr><td>
                        <a href="add.php">Crear Sucursal</a>
                    </td></tr></table>
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
