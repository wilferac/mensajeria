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
//corrijo lo de las enies
  header("Content-Type: text/html;charset=utf-8");

  $idMani = $_REQUEST['idMani'];

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
            var nguias=0;
            $(document).ready(function() {
                
                
                $('#txtGuia').keypress(function(event) {
                    if(event.keyCode.toString()== '13')
                    {
                        var guiaNum = document.getElementById('txtGuia').value;
                        if(guiaNum == '')
                        {
                            return;
                        }
                        document.getElementById('txtGuia').value='';
                        event.preventDefault();
                        //alert('se '+guiaNum);
                        
                        $('#response2').load('descargarGuia.php?option=1&numGuia='+guiaNum+'&idMani='+<?php echo($idMani); ?>);
                    }
                });
                
                
                nguias=0;
                $('#response2').load('descargarGuia.php?option=0&idMani='+<?php echo($idMani); ?>);
            });
            
            function quitar(num)
            {
                //                var dataString = 'option=' + 3+ '&numGuia='+num;
                nguias--;
                $('#response2').load('descargarGuia.php?option=1&numGuia='+num+'&idMani='+<?php echo($idMani); ?>);
                //$('#response4').html('');
            }
            
            
        </script>



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


    <div id="mainResponse" style="padding-top: 30px; float: left; text-align: left;">
        <div id="response">

            <h1>Descargando Guias de Manifiesto</h1>

            <ul>
                <li>Numero Manifiesto: <?php echo($idMani) ?></li>
                <li></li>
            </ul>

            <h3>Guia N. </h3>
            <input name="guia" type="text" id="txtGuia" size="10" require/>
        </div>
    </div>
    <!--aca muestro las guias acumuladas-->
    <div id="response2" style="padding-top: 30px; padding-left: 30px; float: left; text-align: left;">
    </div>

</div>
</body>
</html>
