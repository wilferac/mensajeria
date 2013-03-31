<?
   include ("../../clases/clases.php");
   include ("../../param/param.php");

   include "../../security/User.php";
   include "../../Menu.php";

   $objUser = unserialize($_SESSION['currentUser']);
   //$objUser = new User();
//        echo($objUser->getStatus());
   if ($objUser->getStatus() != 1)
   {
       //$objUser->show();
       $operacion->redireccionar('No Puede entrar', 'index.php');
       return;
   }
   //++ esta parte queda pendiente.
//   else
//   {
//       //verifico si tiene los permisos para ver el recurso.
//       if($objUser->checkRol("Usuario") or $objUser->checkRol("Cliente")or $objUser->checkRol("PuntoVenta"))
//       {
//           
//       }
//   }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Orden de Servicios</title>
        <style type="text/css" title="currentStyle">
            @import "../../media/css/demo_page.css";
            @import "../../media/css/demo_table.css";
            @import "../../media/css/jquery.css";
        </style>
        <script type="text/javascript" src="../../js/elementosVisiblesInvisibles.js"></script>
        <script type="text/javascript" language="javascript" src="../../js/jquery.js"></script>

        <script type="text/javascript" language="javascript" src="../../js/jquery.dataTables.js"></script>
        <script type="text/javascript" language="javascript" src="../../js/jquery.validate.js"></script> 

        <script type="text/javascript" src="../../js/jquery_003.js"></script>




        <script type="text/javascript">

            function ocultarTodo()
            {
            document.getElementById('tituloclientes').style.visibility = 'hidden';
            document.getElementById('labcccliente').style.visibility = 'hidden';


            document.getElementById('cccliente').style.visibility = 'hidden';


            document.getElementById('labnombrescliente').style.visibility = 'hidden';
            document.getElementById('nombrescliente').style.visibility = 'hidden';


            document.getElementById('labapellidoscliente').style.visibility = 'hidden';
            document.getElementById('apellidoscliente').style.visibility = 'hidden';


            document.getElementById('labdireccioncliente').style.visibility = 'hidden';
            document.getElementById('direccioncliente').style.visibility = 'hidden';


            document.getElementById('tituloclientes').style.visibility = 'hidden';
            document.getElementById('labcccliente').style.visibility = 'hidden';

            document.getElementById('cccliente').style.visibility = 'hidden';


            document.getElementById('labnombrescliente').style.visibility = 'hidden';
            document.getElementById('nombrescliente').style.visibility = 'hidden';


            document.getElementById('labapellidoscliente').style.visibility = 'hidden';
            document.getElementById('apellidoscliente').style.visibility = 'hidden';



            document.getElementById('labdireccioncliente').style.visibility = 'hidden';
            document.getElementById('direccioncliente').style.visibility = 'hidden';


            document.getElementById('titulodestinatarios').style.visibility = 'hidden';
            document.getElementById('labdatoArecordar').style.visibility = 'hidden';
            document.getElementById('datoArecordar').style.visibility = 'hidden';




            document.getElementById('labccdestinatario').style.visibility = 'hidden';
            document.getElementById('ccdestinatario').style.visibility = 'hidden';



            document.getElementById('labnombresdestinatario').style.visibility = 'hidden';
            document.getElementById('nombresdestinatario').style.visibility = 'hidden';



            document.getElementById('labapellidosdestinatario').style.visibility = 'hidden';
            document.getElementById('apellidosdestinatario').style.visibility = 'hidden';



            document.getElementById('labdirecciondestinatario').style.visibility = 'hidden';
            document.getElementById('direcciondestinatario').style.visibility = 'hidden';


            document.getElementById('labcelulardestinatario').style.visibility = 'hidden';
            document.getElementById('celulardestinatario').style.visibility = 'hidden';


            document.getElementById('labtelefono1destinatario').style.visibility = 'hidden';
            document.getElementById('telefono1destinatario').style.visibility = 'hidden';


            document.getElementById('capadatosguia').style.visibility = 'hidden';

            document.getElementById('capaPeso').style.visibility = 'hidden';


            document.getElementById('labExtraDestinatario').style.visibility = 'hidden';
            document.getElementById('extraDestinatario').style.visibility = 'hidden';
            document.getElementById('labExtraRemitente').style.visibility = 'hidden';
            document.getElementById('extraRemitente').style.visibility = 'hidden';
            document.getElementById('savetemp').style.visibility = 'hidden';
            }
            /*$().ready(function() 
            {
            $("#cccliente").autocomplete("../tercero/searchtercero.php", {
            minChars: 0, max:200, width: 350});
            });

            $().ready(function() 
            {
            $("#idciudad").autocomplete("../ciudad/searchciudad.php", {minChars: 0, max:50, width: 350});
            });

            */

            /*$().ready(function() 
            {
            $("#idproducto").autocomplete("../producto/searchproducto.php", {
            minChars: 0, max:50, width: 350});
            });*/
        </script>

        <style type="text/css">
            #formulario { width: 800px; }
            #formulario label { width: 250px; }
            #formulario label.error, #formulario input.submit { margin-left: 253px; }
        </style>

        <script type="text/javascript">
            $.validator.setDefaults({
            submitHandler: function() { //formulario.submit();
            }
            });


        </script>
        <script type="text/javascript" language="javascript" src="../../js/funciones.js"></script>

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
            parent.frames[0].document.getElementById("s2").style.visibility = "visible";

            parent.frames[0].document.getElementById("a3").innerHTML = "Ver Ordenes de Servicio";
            parent.frames[0].document.getElementById("a3").href = "gestion/ordendeservicio/consulta.php";

        </script>    

        <script language="javascript">
            // wtf?
            function validar()
            {
            return true;
            }
        </script>
        <script language="javascript">
            $(document).ready(function() {
            $("#numguia").blur(function() {

            //debo ocultar todo
            ocultarTodo();

            var val = $(this).attr("value");

            if (val != "")
            {

            $('#info').html('<img src="../../imagenes/loader.gif" alt="" height="17" />').fadeOut(1000);
            var dataString = 'numguia=' + val;
            alert(dataString);
            $.ajax({
            type: "POST",
            url: "prevalidacion.php",
            data: dataString,
            success: function(data) {
            $('#info').fadeIn(1000).html(data);
            }});


            } // end if (val)
            });   //BLUR    
            /////////////////////////////////////////////////////////////////////////////////

            $("#cccliente").blur(function() {

            var val = $(this).attr("value");

            if (val != "")
            {

            $('#info2').html('<img src="../../imagenes/loader2.gif" alt="" height="17" />').fadeOut(1000);
            var dataString = 'cccliente=' + val;
            //alert (dataString);
            $.ajax({
            type: "POST",
            url: "prevalidacion2.php?cccliente=" + val,
            data: dataString,
            success: function(data) {
            $('#info2').fadeIn(1000).html(data);
            }});

            } // end if (val)
            });   //BLUR	

            /////////////////////////////////////////////////////////////////////////////////
            $("#ciudaddestino").change(function() {

            var val = $(this).attr("value");

            var x = document.getElementById("ciudaddestino").selectedIndex;
            var y = document.getElementById("ciudaddestino").options;
            var innerHtmlSelec = y[x].text;

            var pos = innerHtmlSelec.indexOf("-");
            var ciudad = innerHtmlSelec.substring(pos, -1);

            var departamento = innerHtmlSelec.substring(pos + 1);

            var idciudadorigen2 = document.getElementById("idciudadorigen2").value;

            // alert (ciudad+departamento);
            if (val != "")
            {
            // alert (idciudadorigen2);
            $('#info4').html('<img src="../../imagenes/loader2.gif" alt="" height="17" />').fadeOut(1000);
            var dataString = 'idciudad=' + val + '&ciudaddestino=' + ciudad + '&departamentodestino=' + departamento + '&idciudadorigen2=' + idciudadorigen2;
            //alert (dataString);
            $.ajax({
            type: "POST",
            url: "obtenerTipoProducto.php?ciudaddestino=" + val,
            data: dataString,
            success: function(data) {
            $('#info4').fadeIn(1000).html(data);
            }});

            } // end if (val)
            });   //change



            $("#idciudadorigen2").change(function() {
            var valori = $(this).attr("value");
            var val = document.getElementById("ciudaddestino").value;
            var x = document.getElementById("ciudaddestino").selectedIndex;
            var y = document.getElementById("ciudaddestino").options;
            var innerHtmlSelec = y[x].text;

            var pos = innerHtmlSelec.indexOf("-");
            var ciudad = innerHtmlSelec.substring(pos, -1);

            var departamento = innerHtmlSelec.substring(pos + 1);

            var idciudadorigen2 = document.getElementById("idciudadorigen2").value;

            // alert (ciudad+departamento);
            if (valori != "" && val != "")
            {
            // alert (idciudadorigen2);
            $('#info4').html('<img src="../../imagenes/loader2.gif" alt="" height="17" />').fadeOut(1000);
            var dataString = 'idciudad=' + val + '&ciudaddestino=' + ciudad + '&departamentodestino=' + departamento + '&idciudadorigen2=' + idciudadorigen2;
            //alert (dataString);
            $.ajax({
            type: "POST",
            url: "obtenerTipoProducto.php?ciudaddestino=" + val,
            data: dataString,
            success: function(data) {
            $('#info4').fadeIn(1000).html(data);
            }});

            } // end if (val)
            });   //change


            /////////////////////////////////////////////////////////////////////////////////
            $("#datoArecordar").blur(function() {

            var val = $(this).attr("value");

            if (val != "")
            {

            $('#info3').html('<img src="../../imagenes/loader2.gif" alt="" height="17" />').fadeOut(1000);
            var dataString = 'datoArecordar=' + val;
            //alert (dataString);
            $.ajax({
            type: "POST",
            url: "prevalidacion3.php?datoArecordar=" + val,
            data: dataString,
            success: function(data) {
            $('#info3').fadeIn(1000).html(data);
            }});

            } // end if (val)
            });   //BLUR	   	   





            // evento jquery para guardar temporalmente la guia
            /////////////////////////////////////////////////////////////////////////////////
            $("#savetemp").click(function() {

            alert('entro a temp');
            //return;

            if (validar())
            {
            //'valordeclarado='+valordeclarado+'&valorempaque='+valorempaque+'&idtipoproducto='+idtipoproducto
            var amsp = '&';
            var igual = '=';
            var cadenaValores = "";
            var selectValues = "";

            var todosLosInputs = document.getElementsByTagName('input');
            var todosLosSelect = document.getElementsByTagName('select');

            for (var i = 0; i < todosLosInputs.length; i++) {
            cadenaValores = cadenaValores + todosLosInputs[i].name + igual + todosLosInputs[i].value + amsp;

            }

            for (var i = 0; i < todosLosSelect.length; i++) {
            if (i == todosLosSelect.length - 1)
            amsp = "";
            cadenaValores = cadenaValores + todosLosSelect[i].name + igual + todosLosSelect[i].value + amsp;

            }
            //alert( cadenaValores);
            $('#informacion').html('<img src="../../imagenes/loader2.gif" alt="" height="17" />').fadeOut(1000);
            var dataString = cadenaValores;
            //alert (dataString);
            $.ajax({
            type: "POST",
            url: "registrarOsUnitarioTemp.php",
            data: dataString,
            success: function(data) {
            //alert (data);

            // cadena = "<font face='$font' size='$size' color='$colorEx'>Registro Exitoso</font>";

            if (data == 1)
            {
            document.getElementById('registrar').style.visibility = 'hidden';
            $('#informacion').fadeIn(1000).html("<font face='$font' size='$size' color='$colorEx'>Registro Exitoso</font>");
            setInterval(function() {
            redirigir()
            }, 3000);

            }
            else if (data == 0)
            {
            document.getElementById('registrar').style.visibility = 'hidden';
            $('#informacion').fadeIn(1000).html("<font face='$font' size='$size' color='$colorNoEx'>Registro NO Exitoso</font>");
            }
            // alert(data);
            }});
            }



            });   //CLICK






            /////////////////////////////////////////////////////////////////////////////////
            $("#botoncalcular").click(function() {

            var valordeclarado = document.getElementById('valordeclarado').value;
            //var valorempaque = document.getElementById('valorempaque').value;
            var valorempaque = 0;
            var idtipoproducto = document.getElementById('idtipoproducto').value;
            var nombreproducto = document.getElementById('nombreproducto').value;

            var idcliente = document.getElementById('idcliente').value;
            var idsucursal = <?= /* $_SESSION['datosinicio']['sucursal_idsucursal'] */ 1 ?> //document.getElementById('sucursal_idsucursal').value;
            alert(":O");
            document.getElementById('registrar').style.visibility = "visible";
            /*                    if (valorempaque != "")
            alert("empaque");
            if (valordeclarado != "")
            alert("decla");
            if (idtipoproducto != "")
            {
            //$('#info4').html('<img src="../../imagenes/loader2.gif" alt="" height="17" />').fadeOut(1000);
            var dataString = 'valordeclarado=' + valordeclarado + '&valorempaque=' + valorempaque + '&idtipoproducto=' + idtipoproducto + '&nombreproducto=' + nombreproducto + '&idcliente=' + idcliente + '&idsucursal=' + idsucursal;
            alert (dataString);
            $.ajax({
            type: "POST",
            url: "calcularCostoEnvio.php",
            data: dataString,
            success: function(data) {
            $('#info4').fadeIn(1000).html(data);
            }});
            document.getElementById('registrar').style.visibility = "visible";
            }*/
            });   //CLICK

            /////////////////////////////////////////////////////////////////////////////////
            $("#registrar").click(function() {

            if (validar())
            {
            //'valordeclarado='+valordeclarado+'&valorempaque='+valorempaque+'&idtipoproducto='+idtipoproducto
            var amsp = '&';
            var igual = '=';
            var cadenaValores = "";
            var selectValues = "";

            var todosLosInputs = document.getElementsByTagName('input');
            var todosLosSelect = document.getElementsByTagName('select');

            for (var i = 0; i < todosLosInputs.length; i++) {
            cadenaValores = cadenaValores + todosLosInputs[i].name + igual + todosLosInputs[i].value + amsp;

            }

            for (var i = 0; i < todosLosSelect.length; i++) {
            if (i == todosLosSelect.length - 1)
            amsp = "";
            cadenaValores = cadenaValores + todosLosSelect[i].name + igual + todosLosSelect[i].value + amsp;

            }
            //alert( cadenaValores);
            $('#informacion').html('<img src="../../imagenes/loader2.gif" alt="" height="17" />').fadeOut(1000);
            var dataString = cadenaValores;
            //alert (dataString);
            $.ajax({
            type: "POST",
            url: "registrarosunitario.php",
            data: dataString,
            success: function(data) {
            //alert (data);

            // cadena = "<font face='$font' size='$size' color='$colorEx'>Registro Exitoso</font>";

            if (data == 1)
            {
            document.getElementById('registrar').style.visibility = 'hidden';
            $('#informacion').fadeIn(1000).html("<font face='$font' size='$size' color='$colorEx'>Registro Exitoso</font>");
            setInterval(function() {
            redirigir()
            }, 3000);

            }
            else if (data == 0)
            {
            document.getElementById('registrar').style.visibility = 'hidden';
            $('#informacion').fadeIn(1000).html("<font face='$font' size='$size' color='$colorNoEx'>Registro NO Exitoso</font>");
            }
            // alert(data);
            }});
            }

            });   //CLICK		

            });
            // funcion para redirigir al usuario
            function redirigir()
            {
            window.location.replace("http://localhost/~inovate/Mensajeria/gestion/ordendeservicio/addosunitario.php");
            }
        </script>

        <style>
            .nodisponible { color: red; }
            .disponible { color: green;}
            .titulo {
                font-size: 1.3em;
                font-weight: bold;
                line-height: 1.6em;
                color: #4E6CA3;
            }

            .Estilo1 {color: #0000FF}
        </style>

    </head>
    <body style="font-family:Verdana; font-size:13px;" id="dt_example">
        <?
//   $operacion = new operacion();
//   $operacion->menu();
           $objMenu = new Menu($objUser);
           $objMenu->generarMenu();
        ?>
        <div class="titulo">
            <br>
            Ordenes		
        </div>
    <br>

    <div align="center"> <?
//        $nombreciudadorigen = $_SESSION['datosinicio']['nombre_ciudad'];
           $nombreciudadorigen = "lol";
           $peso = $_SESSION['param']['peso'];
           $valordeclarado = $_SESSION['param']['valordeclarado'];
           $estiloinput = 'double'; //inset  double
           //print_r ($_SESSION);
        ?>
        <form class="formulario" id="formulario" name="formulario" method="post" onkeypress="javascript:if (event.keyCode == 13) {
                    return false;
                }">
            <fieldset>
                <div id="datoNumGuia">
                    <label for="numguia" id="labnumguia"  style="visibility:visible" ><span class="Estilo1">Número de Guía:</span>&nbsp;&nbsp;
                        <input id="numguia" name="numguia" class="required" size="30" maxlength="10" tabindex="1"  style="border:<?= $estiloinput ?>" title="Ingresa el numero de  Guia" required/>
                       <!-- <img src="../../imagenes/mouse.gif" alt="Click aqui para ejecutar" width="26" height="17" title="Click aqui para ejecutar"> -->
                        <br>
                        <br>
                        <br>
                    </label>
                    <div id="info"></div>
                </div>


                <div id="datosRemitente" style="border-left: 10px; float:left;">

                    <table border=0 >
                        <tr>
                            <td wight="200">
                                <span id="tituloclientes" style="visibility:hidden"><b>Datos Cliente:</b></span>         
                            </td>
                            <td wight="200">

                            </td>
                        </tr>
                        <tr for="cccliente" id="labcccliente" style="visibility:hidden">
                            <td>
                                <span class="Estilo1">C.C Cliente:</span>
                            </td>
                            <td>
                                <input id="cccliente" name="cccliente" class="required" size="30"  tabindex="2"  maxlength="10" style="visibility:hidden;border:<?= $estiloinput ?>" required/>
                                <input id="idcliente" name="idcliente" type="hidden" value=""/>

                                <div id="info2" style="display:inline"></div>
                            </td>
                        </tr>
                        <tr for="nombrescliente" id="labnombrescliente" style="visibility:hidden">
                            <td>

                                Nombre Cliente:

                            </td>
                            <td>

                                <input id="nombrescliente" name="nombrescliente" class="required" size="30" maxlength="20" style="visibility:hidden" required/>

                            </td>
                        </tr>
                        <tr for="apellidoscliente" id="labapellidoscliente" style="visibility:hidden">
                            <td>

                                Apellidos Client


                            </td>
                            <td>
                                <input id="apellidoscliente" name="apellidoscliente" class="required" size="30" maxlength="20" style="visibility:hidden" required/>

                            </td>
                        </tr>
                        <tr for="direccioncliente" id="labdireccioncliente" style="visibility:hidden">
                            <td >
                                Direccion Cliente:
                            </td>
                            <td>
                                <input id="direccioncliente" name="direccioncliente" class="required" size="60" maxlength="80" style="visibility:hidden" required/>

                            </td>
                        </tr>
                        <tr for="extraRemitente" id="labExtraRemitente" style="visibility:hidden">
                            <td >
                                Info. Extra:
                            </td>
                            <td>
                                <input id="extraRemitente" name="extraRemitente" class="required" size="60" maxlength="80" />
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="capadatosguia" style="visibility:hidden; border-left: 10px; float:left;">
                    <table border="0">
                        <tr>
                            <td>
                                <b>Datos Guia:</b>

                            </td>
                            <td>

                            </td>
                        </tr>

                        <tr>
                            <td>
                                Ciudad Origen:
                            </td>
                            <td>
                                <select id="idciudadorigen2" name="idciudadorigen2" style="display:inline">
                                    <option value="">Seleccione uno...</option>
                                    <?
//   $idciudadorigen = $_SESSION['datosinicio']['ciudad_idciudad'];
                                       $idciudadorigen = 1;

                                       $operaciones = new operacion();

                                       $qry = "SELECT ciudad.idciudad, ciudad.nombre_ciudad, ciudad.departamento_iddepartamento, departamento.iddepartamento, departamento.nombre_departamento FROM ciudad, departamento WHERE ciudad.departamento_iddepartamento = departamento.iddepartamento
                                                ORDER BY ciudad.nombre_ciudad";
                                       $res2 = $operaciones->consultar($qry);
                                       if (mysql_num_rows($res2) > 0)
                                       {
                                           $selected = "";
                                           //$salir = 20;
                                           while ($filas = mysql_fetch_assoc($res2)) //$filas['idciudad'] //$filas['nombre_ciudad']
                                           {
                                               if ($idciudadorigen == $filas['idciudad'])
                                                   $selected = 'selected';
                                               echo "<option $selected value='" . $filas['idciudad'] . "'>$filas[nombre_ciudad]-$filas[nombre_departamento]</option>";
                                               if ($selected != "")
                                                   $selected = "";
                                               //$salir--;
                                           }
                                       }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Ciudad Destino:
                            </td>
                            <td>
                                <select id="ciudaddestino" name="ciudaddestino"   style="display:inline;border:<?= $estiloinput ?>">
                                    <option value="">Seleccione uno...</option>
                                    <?
                                       $operaciones = new operacion();

                                       $qry = "SELECT ciudad.idciudad, ciudad.nombre_ciudad, ciudad.departamento_iddepartamento, departamento.iddepartamento, departamento.nombre_departamento FROM ciudad, departamento WHERE ciudad.departamento_iddepartamento = departamento.iddepartamento
                                            ORDER BY ciudad.nombre_ciudad";
                                       $res2 = $operaciones->consultar($qry);
                                       if (mysql_num_rows($res2) > 0)
                                           while ($filas = mysql_fetch_assoc($res2)) //$filas['idciudad'] //$filas['nombre_ciudad']
                                               echo "<option value='" . trim($filas['idciudad']) . "'>$filas[nombre_ciudad]-$filas[nombre_departamento]</option>";
                                    ?>
                                </select>
                                <div id="info4" style="display:inline"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>

                                Tipo Producto:
                            </td>
                            <td>
                                <input id="tipoproducto" name="tipoproducto"  class="required" readonly maxlength="15"/>
                                <input id="idtipoproducto" name="idtipoproducto" type="hidden"/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Producto: 
                            </td>
                            <td>
                                <select name="nombreproducto" id="nombreproducto">

                                    <?
                                       $operacion = new operacion();
                                       $sql = "select nombre_producto from producto group by nombre_producto";
                                       $res = $operacion->consultar($sql);


                                       while ($fila = mysql_fetch_assoc($res))
                                       {
                                           $nombre_producto = $fila["nombre_producto"];

                                           if (strtolower($nombre_producto) == 'unitario')
                                               $selected = 'selected';
                                           ?>
                                           <option <?= $selected ?> value="<?= strtolower($nombre_producto) ?>"><?= strtoupper($nombre_producto) ?></option>
                                           <?
                                           if ($selected != '')
                                           {
                                               $selected = '';
                                           }
                                           ?>
                                           <?
                                       }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>

                            </td>
                            <td>
                                <input  type="button" id="savetemp" name="savetemp" value="Llenar Despues" />
                            </td>
                        </tr>
                    </table>
                </div>



                <div id="datosDestinatario" style="visibility:hidden; border-left: 10px; float:left;">
                    <table border="0">
                        <tr id="titulodestinatarios"  style="visibility:hidden">
                            <td>
                                <b>Datos Destinatario:</b>

                            </td>
                            <td>

                            </td>
                        </tr>
                        <tr for="datoArecordar" id="labdatoArecordar" style="visibility:hidden">
                            <td>
                                C.C ó Teléfono:
                            </td>
                            <td>
                                <input id="datoArecordar" name="datoArecordar"  tabindex="3" class="required" size="30" maxlength="10" style="visibility:hidden;border:<?= $estiloinput ?>" />
                                <div id="info3" style="display:inline-block"></div> 
                                <div id="info2" style="display:inline-block"> </div>
                            </td>
                        </tr>
                        <tr for="ccdestinatario" id="labccdestinatario" style="visibility:hidden">
                            <td>

                                C.C destinatario:

                            </td>
                            <td>
                                <input id="ccdestinatario" name="ccdestinatario" class="required" size="30" maxlength="10" style="visibility:hidden" />
                                <img src="../../imagenes/copy.jpg"  alt="Copiar valor desde C.C ó Teléfono" width="32" height="35" border="0" title="Copiar valor desde C.C ó Teléfono" onClick="javascript: document.getElementById('ccdestinatario').value = document.getElementById('datoArecordar').value";/></label>

                            </td>
                        </tr>
                        <tr for="nombresdestinatario" id="labnombresdestinatario" style="visibility:hidden">
                            <td>

                                Nombre destinatario:


                            </td>
                            <td>
                                <input id="nombresdestinatario" name="nombresdestinatario" class="required" size="30" maxlength="20" style="visibility:hidden" />

                            </td>
                        </tr>
                        <tr for="apellidosdestinatario" id="labapellidosdestinatario" style="visibility:hidden">
                            <td>

                                Apellidos destinatario:


                            </td>
                            <td>
                                <input id="apellidosdestinatario" name="apellidosdestinatario" class="required" size="30" maxlength="20" style="visibility:hidden" />

                            </td>
                        </tr>
                        <tr for="direcciondestinatario" id="labdirecciondestinatario" style="visibility:hidden">
                            <td>

                                Direccion destinatario:


                            </td>
                            <td>
                                <input id="direcciondestinatario" name="direcciondestinatario"  class="required" size="60" maxlength="80" style="visibility:hidden" />

                            </td>
                        </tr>
                        <tr for="telefono1destinatario" id="labtelefono1destinatario" style="visibility:hidden">
                            <td>

                                Telefono destinatario:

                            </td>
                            <td>
                                <input id="telefono1destinatario" name="telefono1destinatario" class="required" size="30" maxlength="20" style="visibility:hidden" /><img src="../../imagenes/copy.jpg" alt="Copiar valor desde C.C ó Teléfono" width="30" height="32" border="0" title="Copiar valor desde C.C ó Teléfono"  onClick="javascript: document.getElementById('telefono1destinatario').value = document.getElementById('datoArecordar').value";/>            </label>

                            </td>
                        </tr>
                        <tr for="celulardestinatario" id="labcelulardestinatario" style="visibility:hidden">
                            <td>
                                Celular destinatario:


                            </td>
                            <td>
                                <input id="celulardestinatario" name="celulardestinatario" class="required" size="20" maxlength="30" style="visibility:hidden" />
                                (*) 
                            </td>
                        </tr>
                        <tr for="extraDestinatario" id="labExtraDestinatario" style="visibility:hidden">
                            <td >
                                Info. Extra:
                            </td>
                            <td>
                                <input id="extraDestinatario" name="extraDestinatario" class="required" size="60" maxlength="80" style="visibility:hidden" />
                            </td>
                        </tr>

                    </table>
                </div>
                <br />
                <div id="capaPeso" style="visibility:hidden; border-left: 10px; float:left;">
                    <table border="0">
                        <tr>
                            <td>
                                <b>Información:</b>

                            </td>
                            <td>

                            </td>
                        </tr>
                        <tr>
                            <td>
                                Referencia:
                            </td>
                            <td>
                                <input id="numReferencia" name="numReferencia" class="required" size="30" maxlength="20" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Contenido:
                            </td>
                            <td>
                                <input id="contenido" name="contenido"  class="required" maxlength="10" value=""/> 

                            </td>
                        </tr>
                        <tr>
                            <td>
                                Peso (gr):
                            </td>
                            <td>
                                <input id="peso" name="peso"  class="required" maxlength="3" size="5" value="50" type="number"  min="1" max="99999" /> 
                            </td>
                            <td>
                                Dimensiones (cm):
                            </td>
                            <td>
                                <input id="largo" name="largo"  class="required" maxlength="3" size="5" value="1" type="number"  min="1" max="99999" /> 

                            </td>
                            <td>
                                X

                            </td>
                            <td>
                                <input id="ancho" name="ancho"  class="required" maxlength="3" size="5" value="1" type="number"  min="1" max="99999"/> 

                            </td>
                            <td>
                                X

                            </td>
                            <td>
                                <input id="alto" name="alto"  class="required" maxlength="3" size="5" value="1" type="number"  min="1" max="99999"/> 
                            </td>
                        </tr>

                        <tr>
                            <td>
                                Valor declarado:
                            </td>
                            <td>
                                <input id="valordeclarado" name="valordeclarado"  class="required" maxlength="10" value="5000"/> 
                            </td>
                        </tr>
<!--                        <tr>
                            <td>
                                Valor empaque:
                            </td>
                            <td>
                                <input id="valorempaque" name="valorempaque" value="0"  class="required" maxlength="10"/> 

                            </td>
                        </tr>-->
                        <tr>
                            <td>


                            </td>
                            <td>
                                <input type="button"   id="botoncalcular" name="botoncalcular" value="Calcular Monto" />

                            </td>
                        </tr>

                    </table>
                </div>

                <table width="100%" border=0 align="center">
                    <tr>
                        <td>
                            <label for="labvalortotal"><br>
                                Valor Total:&nbsp;</label>
                            <input id="valortotal" name="valortotal"  class="required" maxlength="10" style="background-color:#009900; text-align:center; font-weight:bold"/> 
                            <input id="valortotal2" name="valortotal2"  type="hidden" /> 
                            <br>
                            <pre>
            Valor Total = Valor de Envio +( Valor Declarado * Porcentaje Seguro)+ Valor Empaque
                            </pre>
                            <p>&nbsp;</p> 
                        </td>
                    </tr>

                    <tr>
                        <td colspan='2' align="center"><div id="informacion"></div></td>
                    </tr>        	
                    <tr>
                        <td colspan='2' align="center"><br>
                            <input type="submit"  id="registrar" name="registrar" value="Registrar" style="visibility:hidden" onClick="return false;"/>									         </td>
                    </tr>    
                </table>
            </fieldset>
        </form>
    </div>

</body>
</html>


<?php
   //aca consulto si se le paso el dato para que complete automaticamente la guia
   $idGuiaFill = $_REQUEST["idGuiaFill"];

   if ($idGuiaFill > 0)
   {
       echo("<script>
           alert('call to fill');
                ocultarTodo();
               $('#numguia').val('$idGuiaFill');
                var val =  $idGuiaFill;
                $('#info').html('<img src=\"../../imagenes/loader.gif\"  height=\"17\" />').fadeOut(1000);
                var dataString = 'numguia=' + val;
                alert (dataString);
                $.ajax({
                    type: 'POST',
                    url: 'prevalidacion.php',
                    data: dataString,
                    success: function(data) {
                    $('#info').fadeIn(1000).html(data);
                    }});
</script>");
       //require("prevalidacion.php");
   }


//   $objUser = new User();
   if ($objUser->checkRol("Cliente") && !$objUser->checkRol("Admin"))
   {
       $cc = $objUser->getNumDocu();
       $idCli = $objUser->getId();
       echo("<script>
          alert('llamado por un cliente');
          document.getElementById('labnumguia').style.visibility='hidden';
          document.getElementById('numguia').style.visibility='hidden';
          ElementosDatosABuscarDestinatarioInvisibles();
          ElementosClientesInvisibles();
          ElementosClientesAbuscarVisibles();
          ElementosDestinatariosInvisibles();
          document.getElementById('cccliente').focus();
          document.getElementById('cccliente').value = $cc;
          document.getElementById('idcliente').value = $idCli;
          document.getElementById('cccliente').readOnly = true;
</script>");
   }
?>
