<?
include ("../../clases/clases.php");

include '../../security/User.php';
include ('../../Menu.php');

$objUser = unserialize($_SESSION['currentUser']);

if ($objUser->getStatus() != 1) {
    $operacion->redireccionar('No Puede entrar', 'index.php');
    return;
}
?>
<html>
    <title>Asignar Guia</title>
    <style type="text/css">
        <!--
        .Estilo1 {
            font-size: 100%
        }
        -->
    </style>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style type="text/css" title="currentStyle">
            @import "../../media/css/demo_page.css";
            @import "../../media/css/jquery.css";
        </style>

        <script type="text/javascript" language="javascript"
        src="../../js/jquery.js"></script>
        <script type="text/javascript" src="../../js/jquery_003.js"></script>

        <script language="JavaScript" type="text/JavaScript">
            <!--
            function MM_reloadPage(init) {  //reloads the window if Nav4 resized
            if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
            document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
            else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
            }
            MM_reloadPage(true);
            //-->
        </script>

        <script language="javascript">
            $(document).ready(function() {
            $("#documentotercero").blur(function() {

            var val = $(this).attr("value");

            if (val != "")
            {

            $('#info').html('<img src="../../imagenes/loader.gif" alt="" height="17" />').fadeOut(1000);
            var dataString = 'documentotercero=' + val;
            //alert (dataString);
            $.ajax({
            type: "POST",
            url: "consultarasignacion.php",
            data: dataString,
            success: function(data) {
            $('#info').fadeIn(1000).html(data);
            }});
            } // end if (val)
            });   //BLUR    




            $("#botonasignar").click(function() {

            var documentotercero = document.getElementById('documentotercero').value;
            var asignardesde = document.getElementById('asignardesde').value;
            var asignarcantidad = document.getElementById('asignarcantidad').value;


            if (documentotercero != "")
            if (asignardesde != 0 || asignarcantidad != 0)
            {
            $('#info2').html('<img src="../../imagenes/loader.gif" alt="" height="17" />').fadeOut(1000);
            var dataString = 'documentotercero=' + documentotercero + '&asignardesde=' + asignardesde + '&asignarcantidad=' + asignarcantidad;
            //alert (dataString);
            $.ajax({
            type: "POST",
            url: "registrarasignacion.php",
            data: dataString,
            success: function(data) {
            $('#info2').fadeIn(1000).html(data);
            }});
            } // end if (asignardesde != 0 || asignarcantidad != 0)
            });   //click   






            $("#documentotercero").keypress(function(event) {

            if (event.which == 13)
            {
            var val = $(this).attr("value");

            if (val != "")
            {

            $('#info').html('<img src="../../imagenes/loader.gif" alt="" height="17" />').fadeOut(1000);
            var dataString = 'documentotercero=' + val;
            //alert (dataString);
            $.ajax({
            type: "POST",
            url: "consultarasignacion.php",
            data: dataString,
            success: function(data) {
            $('#info').fadeIn(1000).html(data);
            }});
            } // end if (val)
            }	//if (event.which == 13)   
            });   //keypress 		




            });
        </script>

        <script type="text/javascript">
            $().ready(function()
            {
            $("#documentotercero").autocomplete("../tercero/searchtercero.php", {
            minChars: 0, max: 200, width: 350});
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

            parent.frames[0].document.getElementById("a2").innerHTML = "Procesos";
            parent.frames[0].document.getElementById("a2").href = "procesos.php";
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
            <div class="full_width big">
                <p>&nbsp;</p>
                Asignar gu&iacute;a
            </div>
            <p>&nbsp;</p>

            <div class="spacer">
                <form name="asignarguia" method="post" action=".php">

                    <table width="767" border="0" align="center" cellpadding="0"
                           cellspacing="0">

                        <tr>
                            <td>&nbsp;</td>
                            <td><table width="503" align="center" border="0" cellspacing="0"
                                       cellpadding="3" bgcolor="#FFFFFF">

                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td width="221"><div>Documento de Tercero:</div></td>
                                        <td width="263"><input name="documentotercero" type="text"
                                                               id="documentotercero" size="30" maxlength="60"> <input
                                                               name="buscar" type="button" value="Buscar"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="center">
                                            <div id="info"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="center">&nbsp;</td>
                                        <td width="1" colspan="2" align="center">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="center"><p class="Estilo1">Asignación de
                                                guías</p>
                                            <p>&nbsp;</p></td>
                                    </tr>
                                    <tr>
                                        <td align="center"><strong>Desde</strong>:<br> <input
                                                type="text" name="asignardesde" id="asignardesde" value="0">
                                        </td>
                                        <td align="center"><strong>Cantidad</strong>: <br> <input
                                                type="text" name="asignarcantidad" id="asignarcantidad"
                                                value="0"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="center"><input type="button"
                                                                              name="botonasignar" id="botonasignar" value="Asignar">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="center">
                                            <p>

                                            <div id="info2"></div>
                                            </p>
                                        </td>
                                    </tr>

                                </table>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </body>

</html>
