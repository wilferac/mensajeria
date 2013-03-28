<?
//session_start();
   include ("../../../param/param.php");
   include ("../../../clases/clases.php");

   include '../../../security/User.php';
   include ('../../../Menu.php');

   $objUser = unserialize($_SESSION['currentUser']);

   if ($objUser->getStatus() != 1)
   {
       $operacion->redireccionar('No Puede entrar', 'index.php');
       return;
   }



   /*    * **************************************************************************** */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Manifiesto</title>
        <style type="text/css" title="currentStyle">
            @import "../../../media/css/demo_page.css";
            @import "../../../media/css/demo_table.css";
            @import "../../../media/css/jquery.css";
        </style>
        <script type="text/javascript" language="javascript" src="../../../js/jquery-1.9.1.js"></script>
        <style type="text/css">
            #formulario { width: 800px; }
            #formulario label { width: 250px; }
            #formulario label.error, #formulario input.submit { margin-left: 253px; }
        </style>

        <script type="text/javascript">
            $(document).ready(function() {
             alert("lol2");
                $('.btnMensajeria').click(function(event) {
                alert($(this).attr('href'));
                event.preventDefault(); //cancela el comportamiento por defecto
                $('#response').load($(this).attr('href')); //carga por ajax a la capa "notice_viewer"
                });
            });

        </script>



    </head>
    <body id="dt_example">

        <?
           $objMenu = new Menu($objUser);
           $objMenu->generarMenu();
        ?>
    <br  style=" clear: all">
    <br  style=" clear: all">
    <div id="container" align=center>
        <div id="info">
        </div>

        <table style=" padding-top: 30px;">
            <tr>
                <td style=" padding-right: 30px;"><button class="btnMensajeria" style=" width: 90px;" href="addManiCiudad.php">Ciudad</button> </td>
                <td style=" padding-left:  30px;"><button class="btnMensajeria" style=" width: 90px;" href="addManiMensajero.php">Mensajero</button> </td>
            </tr>
        </table>

        <div id="response" style="padding-top: 50px;">
        </div>
        <div id="response2" style="padding-top: 50px;">
        </div>

    </div>
</body>
</html>
