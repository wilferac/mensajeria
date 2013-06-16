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
?>

<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Guias</title>
        <style type="text/css" title="currentStyle">
            @import "../../../media/css/demo_page.css";
            @import "../../../media/css/demo_table.css";
            @import "../../../media/media/css/TableTools.css";
            @import "../../../media/css/jquery.css";
            @import "../../../media/css/jquery-ui.css";
        </style>

        <script type="text/javascript" language="javascript" src="../../../js/jquery-1.9.1.js"></script>
        <script type="text/javascript" language="javascript" src="../../../js/jquery-ui.js"></script>
        <script language="javascript" type="text/javascript">


            function buscarGuias()
            {
                //alert("no");
                var fecha1 = document.getElementById('fecha1').value;
                var fecha2 = document.getElementById('fecha2').value;

                var dataString = '?fecha1=' + fecha1 + '&fecha2=' + fecha2;
                abrir("printCorporativo.php"+dataString);
                return false;
            }
            function abrir(link)
            {
                window.open(link, "mywindow", "menubar=0,resizable=0,width=500,height=500");
                return;
            }
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
        ?>
        <br style="clear: both">
        <div id="container">
            <h2>Selecciona Una Fecha</h2>
            <label>Desde</label>
            <input size='15' id='fecha1' type='date' value=''required/>
            <label>Hasta</label>
            <input size='15' id='fecha2' type='date' value=''/>
            <p></p>
            <button class="btnConsultarGuia" style=" width: 90px;" onclick="buscarGuias()">Consultar</button>
            <div id="response"></div>
        </div>

    </body>
</html>

<script language="javascript">
    $('input[type=date]').datepicker({
        dateFormat: 'yy/mm/dd'
    });
</script>