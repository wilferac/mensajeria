<?php
session_start();
include("../../../clases/clases.php");
include "../../../security/User.php";
include "../../../Menu.php";

$objUser = unserialize($_SESSION['currentUser']);
if ($objUser->getStatus() != 1)
{
  $operacion->redireccionar('No Puede entrar', 'index.php');
  return;
}
if (!$objUser->checkRol('Admin'))
{
  exit("Solo admin puede anular guias");
}
?>

<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Guias</title>
    <style type="text/css" title="currentStyle">
      @import "../../../media/css/demo_page.css";
      @import "../../../media/css/demo_table.css";
      @import "../../../media/css/jquery.css";
      @import "../../../media/css/jquery-ui.css";
    </style>
    <script type="text/javascript" language="javascript" src="../../../js/jquery-1.9.1.js"></script>
    <script type="text/javascript" language="javascript" src="../../../js/jquery-ui.js"></script>
    <script language="javascript" type="text/javascript">

      function buscarGuias(request)
      {
        //alert("no");
        var idGuia = document.getElementById('idGuia').value;

        var dataString = 'idGuia=' + idGuia + '&anulate=' + true;
        $('#response').html("Loading...");
        $.ajax({
          type: "POST",
          url: request,
          data: dataString,
          success: function(data) {
            //alert(data);
            $('#response').html(data);
          }});
      }
      function anularGuia(idGuia)
      {
        if (confirm("De verdad desea anula la guia " + idGuia))
        {


          var dataString = 'idGuia=' + idGuia;
          $('#response').html("Loading...");
          $.ajax({
            type: "POST",
            url: 'anular.php',
            data: dataString,
            success: function(data) {
              //alert(data);
              $('#response').html(data);
            }});
        }
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
      <h2>Anulación de guias</h2>
      <input id="idGuia"type="text"></input>
      <button class="btnConsultarGuia" style=" width: 90px;" onclick="buscarGuias('../../unitario/guia/consultaDetallada.php')">Anular</button>
      <div id="response"></div>
    </div>
  </body>
</html>