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



  /*   * **************************************************************************** */
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
                $('.btnMensajeria').click(function(event) {
                    event.preventDefault();
                    $('#response').load($(this).attr('href'));
                    $('#response2').html("");
                    $('#response3').html("");
                });
            });
            
            
            function quitar(num)
            {
//                var dataString = 'option=' + 3+ '&numGuia='+num;
 
                $('#response3').load('addManiMensajero.php?option=3&numGuia='+num);
            }
            //                
            //    $('.btnMensajeria').keypress(function(event) {
            //                    event.preventDefault();
            //                    event.keyCode.toString();
            //                });
            //            });
            
                        
            //            $("#registrar").click(function() {
            //                cadenaValores = cadenaValores + todosLosSelect[i].name + igual + todosLosSelect[i].value + amsp;
            //
            //                //alert( cadenaValores);
            //                //            $('#informacion').html('<img src="../../imagenes/loader2.gif" alt="" height="17" />').fadeOut(1000);
            //                //            var dataString = cadenaValores;
            //                //alert (dataString);
            //                $.ajax({
            //                    type: "POST",
            //                    url: "registrarosunitario.php",
            //                    data: dataString,
            //                    success: function(data) {
            //            
            //                    }}
            //                        )});

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
                <td style=" padding-left:  30px;"><button class="btnMensajeria" style=" width: 90px;" href="addManiMensajero.php?option=0">Mensajero</button> </td>
            </tr>
        </table>
        <div id="mainResponse" style="padding-top: 30px; float: left; text-align: left;">
            <div id="response">
            </div>
            <div id="response2" style="padding-top: 30px;">
            </div>
        </div>
        <!--aca muestro las guias acumuladas-->
        <div id="response3" style="padding-top: 30px; padding-left: 30px; float: left; text-align: left;">
        </div>

    </div>
</body>
</html>
