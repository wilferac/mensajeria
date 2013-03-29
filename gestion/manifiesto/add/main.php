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
                $('.btnMensajeria').click(function(event) {
                    event.preventDefault();
                    nguias=0;
                    $('#response').load($(this).attr('href'));
                    $('#response2').html("");
                    $('#response3').html("");
                    $('#response4').html('');
                });
            });
            
            function agregarRango()
            {
                var r1=document.getElementById('rango1').value;
                var r2=document.getElementById('rango2').value;
                
                
                var x = parseInt(r1);
                var y = parseInt(r2);
                if (isNaN(x))
                {
                    alert("Esta funcion no trabaja con numeros de guia Alfanumericos!");
                    return;
                }
                if (isNaN(y))
                {
                    alert("Esta funcion no trabaja con numeros de guia Alfanumericos!");
                    return;
                }
                if(x<y)
                {
                    if(confirm("Seguro que desea agregar este rango?\n El programa saltara los numero de guia que no se puedan agregar"))
                    $('#response3').load('addManiCiudad.php?option=6&r1='+r1+'&r2='+r2);
                }
                else
                {
                    alert("el rango no es correcto =(");
                    return;
                }
                
                
            }
            
            function selDestino(num)
            {
                alert(num);
                if(num==1)
                {
                    document.getElementById('selAli').value=-1;
                }
                else
                {
                    document.getElementById('selSucursal').value=-1;
                }
                
                var idAli=document.getElementById('selAli').value
                var idSucur=document.getElementById('selSucursal').value;
                if(idAli ==-1 && idSucur== -1)
                {
                    //reseteo
                    $('#response4').html('');
                    $('#response3').html('');
                    return;
                }
                else
                {
                    $('#response4').load('addManiCiudad.php?option=5&idAli='+idAli+'&idSucur='+idSucur);
                }
            }
            
            
            function quitar(num)
            {
                //                var dataString = 'option=' + 3+ '&numGuia='+num;
                nguias--;
                $('#response3').load('addManiMensajero.php?option=3&numGuia='+num);
                $('#response4').html('');
            }
            
            function guardar(tipo)
            {
                //alert(nguias);
                if(nguias<=0)
                {
                    alert("Agrega guias antes de guardar N:"+nguias);
                    return;
                }
                if (confirm('¿Estas seguro que deseas crear el manifiesto con estos datos?')){
                    var idMensajero = document.getElementById('selMensajeroEntrega').value;
                    var idZona = document.getElementById('selZonaCiudad').value;
                    var plazo = document.getElementById('plazo').value;
                    if(idMensajero==-1 || idZona==-1)
                    {
                        alert("Selecciona los datos del Mensajero y la Zona");
                        return;
                    }
                    var y = parseInt(plazo);
                    if (isNaN(y))
                    {
                        alert("llena los dias de plazo");
                        return;
                    }
                    
                    
                    //alert(idMensajero+" zona: "+idZona+"plazo "+plazo);
                    //alert(tipo);
                    
                    
                    //mensajero destajo
                    if(tipo==8)
                    {
                        // alert("destajador :O");
                        var tarifa = document.getElementById('tarifa').value;
                        var x = parseInt(tarifa);
                        if (isNaN(x))
                        {
                            alert("digita una tarifa para el mensajero");
                            return;
                        }
                        var dataString = 'idMensajero=' + idMensajero + '&idZona='+idZona+ '&plazo='+plazo+ '&tipo='+tipo+'&option=4'+'&tarifa='+tarifa;

                        $.ajax({
                            type: "POST",
                            url: "addManiMensajero.php",
                            data: dataString,
                            success: function(data) {
                                if(data==1)
                                {
                                    alert("Manifiesto creado correctamente");
                                    location.reload(); 
                                }
                            
                            }});
                        return;      
                    }
                    //mensajero propio
                    if(tipo==5)
                    {
                        //alert("propio");
                        var dataString = 'idMensajero=' + idMensajero + '&idZona='+idZona+ '&plazo='+plazo+ '&tipo='+tipo+'&option=4';

                        $.ajax({
                            type: "POST",
                            url: "addManiMensajero.php",
                            data: dataString,
                            success: function(data) {
                                if(data==1)
                                {
                                    alert("Manifiesto creado correctamente");
                                    location.reload(); 
                                }
                            }});
                        
                        
                        return;
                    }
                    
                    //document.tuformulario.submit()
                } 
                
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
            <!--un div estra para la info de los mensajeros-->
            <div id="response4" style="padding-top: 30px;">
            </div>
        </div>
        <!--aca muestro las guias acumuladas-->
        <div id="response3" style="padding-top: 30px; padding-left: 30px; float: left; text-align: left;">
        </div>

    </div>
</body>
</html>
