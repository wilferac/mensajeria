<?
//session_start();

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
            @import "../../../media/css/jquery-ui.css";
        </style>
        <script type="text/javascript" language="javascript" src="../../../js/jquery-1.9.1.js"></script>
        <script type="text/javascript" language="javascript" src="../../../js/jquery-ui.js"></script>
        <style type="text/css">
            #formulario { width: 800px; }
            #formulario label { width: 250px; }
            #formulario label.error, #formulario input.submit { margin-left: 253px; }
        </style>

        <script type="text/javascript">
            var nguias=0;

            function quitar(num)
            {
                //alert(nguias);
                $('#response2').load('descargarGuia.php?option=3&numGuia='+num);
                nguias--;
            }

            function swithCase(num)
            { 
                nguias=0;
                $('#response2').html('');
                $('#response').load('descargarGuia.php?option=0&caso='+num); 
            }
            
            function guardar(tipo)
            {
                //alert(nguias);
                if(nguias<=0)
                {
                    alert("Agrega guias antes de guardar N:"+nguias);
                    return;
                }
                if (confirm('¿Estas seguro que deseas descargar estas guias?')){
                    var fechaManual = document.getElementById('fechaManual').value;
                    if(fechaManual == "")
                        {
                            alert("Deberias seleccionar una fecha!");
                            return;
                        }
                    
                    var razon=-1;
                    
                    if(tipo==2)
                    {
                        razon = document.getElementById('selRazon').value;
                    
                        if(razon==-1)
                        {
                            alert("Selecciona una causal de devolución");
                            return;
                        }
                    }
                    
                    var dataString = 'option=2'+'&tipo='+tipo+'&fechaManual='+fechaManual+'&razon='+razon;

                    $.ajax({
                        type: "POST",
                        url: "descargarGuia.php",
                        data: dataString,
                        success: function(data) {
                            if(data==1)
                            {
                                alert("Guias Descargadas!");
                                location.reload(); 
                            }
                            else
                            {
                                alert("No fue posible descargar las guias =(\n"+data);
                            }
                            
                        }});
                    return;      

                
                    
                //document.tuformulario.submit()
            } 
                
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
        <h2>Selecciona</h2>
        <input id="pistoleo" name="pistoleo" type="radio" value="pistoleo"  onClick="swithCase(1)" /> Entrega
        <br />
        <input id="pistoleo" name="pistoleo" type="radio" value="bloque"  onClick="swithCase(2)" /> Devolucion


        <div id="response">

        </div>
    </div>
    <!--aca muestro las guias acumuladas-->
    <div id="response2" style="padding-top: 30px; padding-left: 30px; float: left; text-align: left;">
    </div>

</div>
</body>
</html>
