<?
//session_start();
   include ("../../param/param.php");
   include ("../../clases/clases.php");

   include '../../security/User.php';
   include ('../../Menu.php');

   $objUser = unserialize($_SESSION['currentUser']);

   if ($objUser->getStatus() != 1)
   {
       $operacion->redireccionar('No Puede entrar', 'index.php');
       return;
   }





   if (isset($_POST["destino"]))
   {

       $mensajeroOciudad = $_POST["destino"]; // aqui determinar si es mensalero registrar segun esa opcion
       if ($mensajeroOciudad == 'ciudad')
       {

           $flagbloque = false;
           if ($_POST["pistoleo"] == "bloque")
           {
               $flagbloque = true;
               $desde = $_POST['desde'];
               $hasta = $_POST['hasta'];
               $estadomovimiento = 3; //Guia En Reparto	
           }
           elseif ($_POST["pistoleo"] == "pistoleo")
           {
               $estadomovimiento = 3; //Guia En Reparto
           }
           else
               $estadomovimiento = 1; //Creacion

           $idusuario = $_SESSION['datosinicio']['idtercero'];

           $conex = new conexion();
           $qtrans = "SET AUTOCOMMIT=0;";
           $sac = $conex->ejecutar($qtrans);
           $qtrans = "BEGIN;";
           $sac = $conex->ejecutar($qtrans);


           $manifiestos = new manifiesto();
           $movimientos = new movimiento();
           $guias = new guia();
           $operaciones = new operacion();

           $fecha = $operaciones->obtenerFechayHora("fecha");
           $hora = $operaciones->obtenerFechayHora("hora");
           $datosmensajero = $_POST['mensajero'];

           $aliadossucursales = $_POST['aliadossucursales'];

           $aliadossucursales2 = substr($aliadossucursales, 0, strpos($aliadossucursales, "-"));
           $idclientesucursal = substr($aliadossucursales, strpos($aliadossucursales, "-") + 1);


           if ($aliadossucursales2 == 'aliado')
           {
               $idtercero = $idclientesucursal;
               $idsucursal = 1; // asignar un numero para saber que no tiene sucursal asignada (deberia ser null)	
           }
           elseif ($aliadossucursales2 == 'sucursal')
           {
               $idtercero = 1;
               $idsucursal = $idclientesucursal;
           }


//*************** Data para tabla manifiesto*********

           $manifiestos->generarNumeroManifiesto();
           $plazo = $_POST["plazo"];

           if (!empty($datosmensajero))
           {
               $cedmensajero = substr($datosmensajero, 0, strrpos($datosmensajero, "-"));
               $manifiestos->tercero_idmensajero_recibe = $cedmensajero;
           }
           else
               $manifiestos->tercero_idmensajero_recibe = null;

           $manifiestos->tercero_idmensajero_entrega = null;


           $manifiestos->sucursal_idsucursal = $idsucursal;
           $manifiestos->tercero_idaliado = $idtercero;
           $manifiestos->plazo_entrega_manifiesto = $plazo;
           $manifiestos->zonamensajero = 1;
           $manifiestos->tarifadestajo = '';


           $rmani = $manifiestos->agregar();
           $num_manifiesto = $manifiestos->num_manifiesto;

//***************Data para movimientos**************

           $movimientos->estado_idestado = $estadomovimiento;
           $movimientos->tercero_idusuario = $idusuario;
           $movimientos->tercero_idaliado = $idtercero;

           $movimientos->fecha_movimiento = $fecha;
           $movimientos->hora_movimiento = $hora;
           $movimientos->recogida_idrecogida == null;
           $movimientos->orden_servicio_idorden_servicio = null;
           $movimientos->manifiesto_idmanifiesto = $manifiestos->idmanifiesto;
           $movimientos->sucursal_idsucursal = $idsucursal;
           $movimientos->asignacion_guias_idasignacion_guias = null; //ojo se debe updatear despues para asociarlo
           $movimientos->tarifa_idtarifa = null; //ojo se debe updatear despues para asociarlo

           $observaciones = $_POST['observaciones'];
           $idciudad = $_POST['ciudad'];


//**************************************************	

           $guiasarray = array();
           $mensguiasYaManif = '';
           $mensguiasnoenc = '';
           $flagmodiguia = false;

           if (!$flagbloque)
           {
               foreach ($_POST as $clave => $valor)
               {
                   $clave = substr($clave, 0, 4);
                   if ($clave == 'guia')
                   {
                       $cond = "numero_guia = $valor";
                       $res = $guias->consultar($cond);
                       if (mysql_num_rows($res) > 0)  // existe la guia 
                       {


                           $cond = "numero_guia = $valor and manifiesto_idmanifiesto = 0"; // existe la guia 
                           $res = $guias->consultar($cond);
                           if (mysql_num_rows($res) > 0)
                           {
                               $stringset = "manifiesto_idmanifiesto=" . $manifiestos->idmanifiesto;

                               $rmodiguia = $guias->modificar2($stringset, $valor);  //update a manifiesto
                               //	if ($rmodiguia === false)
                               $flagmodiguia = true;

                               //Para obtener el idguia de guia y registrarlo en Movimiento 		
                               $sent = "Select idguia FROM guia WHERE numero_guia = $valor";
                               $r = $operaciones->consultar($sent);
                               if (mysql_num_rows($r) > 0)
                               {
                                   $dat = mysql_fetch_assoc($r);

                                   $idguia = $dat["idguia"];

                                   $movimientos->guia_idguia = $idguia;
                                   $rmov = $movimientos->agregar();
                               }
                               //_____________________________________________________________			
                           }
                           else
                               $mensguiasYaManif.= $valor . " \\n ";
                       }
                       else
                       {
                           $mensguiasnoenc.= $valor . " \\n ";
                       }
                   } //if ( $clave == 'guia'  )
               }
           } //if (!$flagbloque)
           else
           {
               for ($k = $desde; $k <= $hasta; $k++)
               {

                   $cond = "numero_guia=$k";
                   $res = $guias->consultar($cond);
                   if (mysql_num_rows($res) > 0)
                   {
                       $stringset = "manifiesto_idmanifiesto=" . $manifiestos->idmanifiesto;
                       $rmodiguia = $guias->modificar2($stringset, $k);  //update a manifiesto
                       //if ($rmodiguia===false)
                       $flagmodiguia = true;
                       $rmov = $movimientos->agregar();
                       echo "k " . $k;
                   }
                   else
                       $mensguiasnoenc.= $valor . " \n ";
               }
           }

           if ($mensguiasYaManif != '' || $mensguiasnoenc != '')
           {
               $texto = "";
               if ($mensguiasYaManif != '')
                   $texto = "Guia(s) ya tiene(n) Manifiesto:\\n$mensguiasYaManif\\n\\n";
               if ($mensguiasnoenc != '')
                   $texto.= "No se encuentra(n) la(s) siguiente(s) guia(s):\\n$mensguiasnoenc";
               ?>
               <script language="javascript" type="text/javascript">
                   alert("<?= $texto ?>");
               </script>	
               <?
           }

           echo "flagmodiguia" . $flagmodiguia;
           if ($rmani === true && $rmov === true && $flagmodiguia === true)
           {
               $qtrans = "COMMIT";
               $sac = $conex->ejecutar($qtrans);
               ?>	
               <script language="javascript" type="text/javascript">
                   var mensaje = "Registro Exitoso. <br>Número Manifiesto <?= $num_manifiesto ?>";
                   // window.location.href='consulta.php?mensaje='+mensaje;
               </script>

               <?
           }
           else
               
               ?>
               <script language="javascript" type="text/javascript">
                   var mensaje = "Registro NO Exitoso";
                   //window.location.href='consulta.php?mensaje='+mensaje;
               </script>
           <?
       } //if ( $mensajeroOciudad == 'ciudad')
       elseif ($mensajeroOciudad == 'mensajero')
       {
           $arrayGuias = array();
           foreach ($_POST as $clave => $valor)
           {
               $clave = substr($clave, 0, 4);
               if ($clave == 'guia')
               {
                   $arrayGuias[] = $valor;
               }
           }

           $operacion = new operacion();
           $datosmensajero = $_POST['mensajero'];
           $iddestajo = $_POST['iddestajo'];
           //$plazo = ( isset( $_POST["plazo"] ) ) ? $_POST["plazo"] : '0';

           $observaciones = $_POST['observaciones'];
           $idciudad = $_POST['ciudad'];
           $zonamensajero = $_POST['zona'];
           $tarifadestajo = $_POST['tarifa'];
           $tiposmensajeros = $_POST['tiposmensajeros'];

           $plazo = "";
           if ($tiposmensajeros == 'destajo')
               $plazo = $_POST["plazo"];


           $operacion->registrarManifiestoMensajero($arrayGuias, $datosmensajero, $iddestajo, $tiposmensajeros, $plazo, $observaciones, $idciudad, $zonamensajero, $tarifadestajo);
       }
   }
   // OJO realizar este procedimiento cuando se inicie sesion y cargar el idtercero de usuario y tambien se podria cargar la idsucursal 

   $idusuario = $_SESSION['datosinicio']['idtercero'];
   $nombre_ciudad = $_SESSION['datosinicio']['nombre_ciudad'];
   $idsucursal = $_SESSION['datosinicio']['sucursal_idsucursal'];
   $nombresucursal = $_SESSION['datosinicio']["nombre_sucursal"];
   $idsucursal = $_SESSION['datosinicio']["sucursal_idsucursal"];
   $idciudad = $_SESSION['datosinicio']["ciudad_idciudad"];

   /*    * **************************************************************************** */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Manifiesto</title>
        <style type="text/css" title="currentStyle">
            @import "../../media/css/demo_page.css";
            @import "../../media/css/demo_table.css";
            @import "../../media/css/jquery.css";
        </style>
        <script type="text/javascript" language="javascript" src="../../js/jquery.js"></script>
        <script type="text/javascript" language="javascript" src="../../js/jquery.dataTables.js"></script>
        <script type="text/javascript" language="javascript" src="../../js/jquery.validate.js"></script> 
        <script type="text/javascript" src="../../js/jquery_003.js"></script>

        <script type="text/javascript">
            $().ready(function()
            {
                $("#ciudad").autocomplete("../ciudad/searchciudad.php", {
                    minChars: 0, max: 200, width: 350});
            });

            $().ready(function()
            {
                $("#mensajero").autocomplete("../tercero/searchmensajero.php?tt=5", {
                    minChars: 0, max: 50, width: 350});
            });

            $().ready(function()
            {
                $("#iddestajo").autocomplete("../tercero/searchmensajero.php?tt=8", {
                    minChars: 0, max: 50, width: 350});
            });

            $().ready(function()
            {
                $("#zona").autocomplete("../zonas/searchzona.php", {
                    minChars: 0, max: 50, width: 350});
            });

        </script>

        <style type="text/css">
            #formulario { width: 800px; }
            #formulario label { width: 250px; }
            #formulario label.error, #formulario input.submit { margin-left: 253px; }
        </style>

        <script type="text/javascript">
            $.validator.setDefaults({
                submitHandler: function() {
                    formulario.submit();
                }
            });

            $().ready(function() {
                $("#formulario").validate();

            });
        </script>

        <!******************************************************
        Establece Visible a Input Ciudad o Mensajero
        ******************************************************>

        <script type="text/javascript">
            function seleciumens(nombre)
            {
                var i = 0;


                if (nombre == "ciudad")
                {

                    document.getElementById("ciudad").style.visibility = "visible";
                    document.getElementById("mensajero").style.visibility = "hidden";
                    document.getElementById("tipomensajero").style.visibility = "hidden";
                    document.getElementById("labzona").style.visibility = "hidden";
                    document.getElementById("tagpistoleo").style.visibility = "visible";
                    document.getElementById("sucursalesalidos").style.visibility = "visible";

                    document.getElementById("labdestajo").style.visibility = "hidden";
                    document.getElementById("labtarifa").style.visibility = "hidden";
                    document.getElementById("tagplazo").style.visibility = "hidden";


                    for (i = 0; i < document.formulario.pistoleo.length; i++)
                    {
                        document.formulario.pistoleo[i].style.visibility = "visible";
                    }

                    document.getElementById("tagbloque").style.visibility = "visible";
                    document.getElementById("tagdesde").style.visibility = "visible";
                    document.getElementById("taghasta").style.visibility = "visible";
                    document.getElementById("desde").style.visibility = "visible";
                    document.getElementById("hasta").style.visibility = "visible";

                    document.getElementById("tagplazo").style.visibility = "hidden";
                    document.getElementById("plazo").style.visibility = "hidden";
                    if (document.formulario.pistoleo[0].checked == true)
                    {
                        document.getElementById("tagdesde").style.visibility = "hidden";
                        document.getElementById("taghasta").style.visibility = "hidden";
                        document.getElementById("desde").style.visibility = "hidden";
                        document.getElementById("hasta").style.visibility = "hidden";
                    }

                }
                else if (nombre == "mensajero")
                {

                    document.getElementById("mensajero").style.visibility = "visible";
                    document.getElementById("tipomensajero").style.visibility = "visible";
                    document.getElementById("labzona").style.visibility = "visible";
                    document.getElementById("labdestajo").style.visibility = "hidden";
                    document.getElementById("labtarifa").style.visibility = "hidden";

                    document.getElementById("ciudad").style.visibility = "hidden";

                    document.getElementById("tagpistoleo").style.visibility = "hidden";
                    document.getElementById("pistoleo").style.visibility = "hidden";
                    document.getElementById("sucursalesalidos").style.visibility = "hidden";
                    document.getElementById("mensajero").focus();

                    for (i = 0; i < document.formulario.pistoleo.length; i++)
                    {
                        document.formulario.pistoleo[i].style.visibility = "hidden";
                    }

                    document.getElementById("tagbloque").style.visibility = "hidden";
                    document.getElementById("tagdesde").style.visibility = "hidden";
                    document.getElementById("taghasta").style.visibility = "hidden";
                    document.getElementById("desde").style.visibility = "hidden";
                    document.getElementById("hasta").style.visibility = "hidden";


                    //document.getElementById("tagplazo").style.visibility = "visible";
                    //document.getElementById("plazo").style.visibility = "visible";
                }
                if (nombre == "bloque")
                {
                    document.getElementById("tagdesde").style.visibility = "visible";
                    document.getElementById("taghasta").style.visibility = "visible";
                    document.getElementById("desde").style.visibility = "visible";
                    document.getElementById("hasta").style.visibility = "visible";
                    document.getElementById("desde").focus();
                }
                else if (nombre == "pistoleo")
                {
                    document.getElementById("tagdesde").style.visibility = "hidden";
                    document.getElementById("taghasta").style.visibility = "hidden";
                    document.getElementById("desde").style.visibility = "hidden";
                    document.getElementById("hasta").style.visibility = "hidden";
                    document.getElementById("idguia").focus();
                }
            }
        </script>

        <script language="javascript">

            function cambiar()
            {
                var tiposmensajeros = document.getElementById('tiposmensajeros').value;
                if (tiposmensajeros == 'destajo')
                {
                    document.getElementById("zona").value = '';
                    document.getElementById("labdestajo").style.visibility = "visible";
                    document.getElementById("labzona").style.visibility = "visible";
                    document.getElementById("labtarifa").style.visibility = "visible";
                    document.getElementById("tagplazo").style.visibility = "visible";
                    document.getElementById("plazo").style.visibility = "visible";
                    document.getElementById("mensajero").style.visibility = "hidden";
                    document.getElementById("iddestajo").focus();
                }
                else if (tiposmensajeros == 'propio')
                {
                    document.getElementById("labdestajo").style.visibility = "hidden";
                    document.getElementById("labzona").style.visibility = "visible";
                    document.getElementById("labtarifa").style.visibility = "hidden";
                    document.getElementById("tagplazo").style.visibility = "hidden";
                    document.getElementById("plazo").style.visibility = "hidden";

                    document.getElementById("mensajero").style.visibility = "visible";
                    document.getElementById("mensajero").focus();
                }

            }
        </script>
        <!*************************************************>

        <script language="javascript">
            function AceptarOtroDepartamento()
            {
                $('#info').fadeIn(1000).html("");
                guiaadd2();
            }



            function liberar()
            {

                var guia = document.getElementById('idguia').value;

                var dataString = 'guia=' + guia;

                $.ajax({
                    type: "POST",
                    url: "liberarguia.php",
                    data: dataString,
                    success: function(data) {


                    }});
            }

        </script>

        <!*************************************************>

        <script type="text/javascript">

            var activeElement;
            var activo;
            function blurFunc() {

                activeElement = null; /* Cuando el elemento deja de estar activo el elemento activo pasa a ser nulo (null) */

            }

            function focusFunc(evento) {

                if (!evento) { // Para IE

                    evento = window.event;
                    activeElement = evento.srcElement; /* Cuando un elemento se activa (focus) lo indicamos */

                } else { // Para otros navegadores

                    activeElement = evento.target;

                }
        //alert(activeElement.name); // Lo utilizaremos para hacer la prueba
                activo = activeElement.name;
            }

            function init() {

        //for (var i = 0; i < document.forms.length; i++) {

        //for(var j = 0; j < document.WebTaller.elements.length; j++) {

                document.formulario.ciudad.onfocus = focusFunc;
                document.formulario.ciudad.onblur = blurFunc;

                document.formulario.idguia.onfocus = focusFunc;
                document.formulario.idguia.onblur = blurFunc;

                document.formulario.mensajero.onfocus = focusFunc;
                document.formulario.mensajero.onblur = blurFunc;

                document.formulario.zona.onfocus = focusFunc;
                document.formulario.zona.onblur = blurFunc;

                document.formulario.iddestajo.onfocus = focusFunc;
                document.formulario.iddestajo.onblur = blurFunc;

        //}

        //}

            }

            window.onload = init;


            document.onkeypress = function(e) {
                var esIE = (document.all);
                tecla = (esIE) ? event.keyCode : e.which;
                var key = window.Event ? e.which : e.keyCode;

                if (tecla == 13)
                {
                    //alert (activo);
                    if (activo == 'idguia')
                        guiaadd();
                    else if (activo == 'ciudad') //ejecutar query para obtener sucursales y aliados
                    {
                        var ciudadstr = document.getElementById("ciudad").value;

                        ciudadstr = ciudadstr.replace(/^\s+/, '').replace(/\s+$/, '');


                        var dataString = 'ciudadstr=' + ciudadstr;

                        $.ajax({
                            type: "POST",
                            url: "versucursalesyaliados.php",
                            data: dataString,
                            success: function(data) {

                                $('#sucursalesalidos').fadeIn(1000).html(data);
                            }});

                    }
                    else if (activo == 'mensajero')
                    {
                        var mensajero = document.getElementById('mensajero').value;
                        mensajero = mensajero.replace(/^\s+/, '').replace(/\s+$/, '');
                        var idmensajero = mensajero.substring(0, mensajero.indexOf('-'));

                        var dataString = 'idmensajero=' + idmensajero + '&tipo=propio';

                        $.ajax({
                            type: "POST",
                            url: "buscarzonamensajero.php",
                            data: dataString,
                            success: function(data) {
                                document.getElementById('zona').value = data;
                                document.getElementById('zona').focus();
                            }});

                    }
                    else if (activo == 'iddestajo')
                    {
                        var destajo = document.getElementById('iddestajo').value;
                        destajo = destajo.replace(/^\s+/, '').replace(/\s+$/, '');
                        var iddestajo = destajo.substring(0, destajo.indexOf('-'));

                        var dataString = 'idmensajero=' + iddestajo + '&tipo=destajo';

                        $.ajax({
                            type: "POST",
                            url: "buscarzonamensajero.php",
                            data: dataString,
                            success: function(data) {

                                if (data == 'Sin Zona')
                                {
                                    document.getElementById('zona').value = data;
                                    document.getElementById('zona').focus();
                                }
                                else
                                {
                                    data = data.replace(/^\s+/, '').replace(/\s+$/, '');
                                    var tarifa = data.substring(0, data.indexOf('&'));
                                    var zona = data.substring(data.indexOf('&') + 1);

                                    document.getElementById('zona').value = zona;
                                    document.getElementById('zona').focus();
                                    document.getElementById('tarifa').value = tarifa;
                                }
                            }});


                    }
                }
                if (tecla == 9) // tab
                {
                    if (activo == 'idguia')
                        guiaadd();
                    else if (activo == 'ciudad') //ejecutar query para obtener sucursales y aliados
                    {
                        var ciudadstr = document.getElementById("ciudad").value;

                        ciudadstr = ciudadstr.replace(/^\s+/, '').replace(/\s+$/, '');


                        var dataString = 'ciudadstr=' + ciudadstr;

                        $.ajax({
                            type: "POST",
                            url: "versucursalesyaliados.php",
                            data: dataString,
                            success: function(data) {

                                $('#sucursalesalidos').fadeIn(1000).html(data);
                            }});

                    }
                    else if (activo == 'mensajero')
                    {
                        var mensajero = document.getElementById('mensajero').value;
                        mensajero = mensajero.replace(/^\s+/, '').replace(/\s+$/, '');
                        var idmensajero = mensajero.substring(0, mensajero.indexOf('-'));

                        var dataString = 'idmensajero=' + idmensajero;

                        $.ajax({
                            type: "POST",
                            url: "buscarzonamensajero.php",
                            data: dataString,
                            success: function(data) {
                                document.getElementById('zona').value = data;
                                document.getElementById('zona').focus();
                            }});

                    }

                }

                return (tecla);
            };



            var num = 0;
            function guiaadd() {

                var fs = document.getElementById("fiel");
                var inps = fs.getElementsByTagName("INPUT");

                var idguia = document.getElementById("idguia").value;
                var idciudad = document.getElementById('ciudad').value;


                var sAux = "";
                var frm = document.getElementById("fiel");



                for (i = 0; i < inps.length; i++)
                {
                    sAux = inps[i].value
                    if (sAux == document.getElementById("idguia").value || document.getElementById("idguia").value == "")
                    {
                        $('#info').fadeIn(1000).html("");
                        document.getElementById("idguia").value = "";
                        return false;
                    }
                }


                var dataString = 'numguia=' + idguia + '&idciudad=' + idciudad;

                $.ajax({
                    type: "POST",
                    url: "validarguia.php",
                    data: dataString,
                    success: function(data) {

                        limpio = data.replace(/^\s+/, '').replace(/\s+$/, '');
                        data = limpio.substring(0, 1);
                        //alert (limpio);

                        if (data == "1")
                        {
                            mani = limpio.substring(1);
                            $('#info').fadeIn(1000).html("El número de guía " + idguia + " <u>Ya tiene Manifiesto:</u> " + mani + ".  ¿Liberar guía?<input type='button' name='bliberar' id='bliberar' value='Si' onClick='liberar(); return false;' >");
                        }
                        if (data == "2")
                            $('#info').fadeIn(1000).html("El numero de guía <font color=green><b>" + idguia + "</b></font> tiene <u>destino a otro departamento</u>. ¿Enviar a la Ciudad - Departamento destino seleccionado? <input type='button' name='otrodepartamento' id='otrodepartamento' value='Si' onClick='AceptarOtroDepartamento(); return false;' >");
                        if (data == "3")
                            $('#info').fadeIn(1000).html("El número de guía " + idguia + " no existe.");

                        if (data != "1" && data != "2" && data != "3")
                        {
                            $('#info').fadeIn(1000).html("");
                            guiaadd2();
                        }

                    }});


            }

            function guiaadd2()
            {
                document.getElementById('titulodetalles').style.visibility = "visible";

                num++;
                fi = document.getElementById('fiel');
                contenedor = document.createElement('div');
                contenedor.id = 'div' + num;
                fi.appendChild(contenedor);

                objSelect = document.getElementById("idguia");
                indice = objSelect.selectedIndex;
                ele = document.createElement('input');
                ele.type = 'text';
                ele.name = 'guia' + num;
                ele.width = 20;
                ele.value = document.getElementById("idguia").value;
                ele.size = 20;
                ele.readOnly = true;
                contenedor.appendChild(ele);

                ele = document.createElement('input');
                ele.type = 'button';
                ele.value = 'Borrar';
                ele.name = 'div' + num;
                ele.onclick = function() {
                    borraradd(this.name)
                }
                contenedor.appendChild(ele);

                document.getElementById("idguia").value = "";
                document.getElementById("idguia").focus();

            }
            function borraradd(obj) {
                fi2 = document.getElementById('fiel');
                fi2.removeChild(document.getElementById(obj));
                if (fi2.getElementsByTagName("INPUT").length == 0)
                    document.getElementById('titulodetalles').style.visibility = "hidden";

            }



            function enviar()
            {
                var fs = document.getElementById("fiel");

                var inps = fs.getElementsByTagName("INPUT");

                var Selmensajero = document.getElementById("destino2").checked;
                var Selciudad = document.getElementById("ciudad").checked;
                var Selpistoleo = document.getElementById("pistoleo").checked;

                var ciudad = document.getElementById("ciudad").value;
                var mensajero = document.getElementById("mensajero").value;
                var plazo = document.getElementById("plazo").value;
                var aliadossucursales = document.getElementsByName("aliadossucursales");
                var msg = "";

                var seleccionado = false;
                var i;

                //var tiposmensajeros = document.getElementById("tiposmensajeros").value;

                if (Selmensajero == false)
                {
                    for (i = 0; i < aliadossucursales.length; i++)
                    {
                        if (aliadossucursales[i].checked)
                        {
                            seleccionado = true;
                            break;
                        }
                    }

                    if (seleccionado == false)
                        msg = msg + "Debe seleccionar un Aliado o Sucursal\n";

                }

                /* if ( ciudad =="" && mensajero =="" )
                 msg = msg+"Debe seleccionar una ciudad o un mensajero\n";
                 else if(mensajero != "")
                 */
                if (plazo == "")
                    msg = msg + "Debe Ingresar un plazo de entrega\n";

                if (inps.length == 0 && Selmensajero == true)
                {
                    msg = msg + "Debe agregar al menos una guia\n";
                    //return false;
                }

                if (msg == "")
                    document.formulario.submit();
                else
                    alert("Error:\n" + msg);
            }

        </script>

        <!******************************************************-->
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

            parent.frames[0].document.getElementById("a3").innerHTML = "Ver Manifiestos";
            parent.frames[0].document.getElementById("a3").href = "gestion/manifiesto/consulta.php";

        </script>   
    </head>
    <body id="dt_example">

<?
   //generar menu
   $objMenu = new Menu($objUser);
   $objMenu->generarMenu();
//		   	$operacion = new operacion();
//			 $operacion -> menu();
?>
        <div id="container">
            <div class="full_width big">
                <p>&nbsp;</p>
                Manifiesto
            </div>
            <p>Ciudad:   
<?= $nombre_ciudad ?> 
                <br>
                Sucursal:   <?= $nombresucursal ?></p>
            <div id="dynamic"><form class="formulario" id="formulario" name='formulario' method="post" action="" ><fieldset>
                        <table width="988" border=0 cellspacing=20>
                            <tr><td width="498">			
                                    <input checked type="radio" id="destino" value="ciudad"  name="destino" onClick="seleciumens('ciudad')"/>
                                    Ciudad destino 
                                    <input id="ciudad"  name="ciudad" value="" maxlength="100"  size="60" style="visibility:visible"/>
                                    <br>
                                    <div id="sucursalesalidos" style="font-size:11px"></div>
                                    <br>
                                    <label id="tagpistoleo" name="tagpistoleo" style="visibility:visible">Pistoleo guia por guia: </label>
                                    <input checked id="pistoleo" name="pistoleo" type="radio" value="pistoleo"  onClick="seleciumens('pistoleo')">
                                    <br>
                                    <label id="tagbloque" name="tagbloque" style="visibility:visible">Por bloque: </label>
                                    <input id="pistoleo" name="pistoleo" type="radio" value="bloque"  onClick="seleciumens('bloque')">
                                    <br>

                                    <label id="tagdesde" name="tagdesde" style="visibility:hidden">Desde: </label>			
                                    <input id="desde" name="desde"   type="text" maxlength="45" size="35" style="visibility:hidden"/><br>
                                    <label id="taghasta" name="taghasta" style="visibility:hidden">Hasta: </label>			
                                    <input id="hasta" name="hasta"   type="text" maxlength="45" size="35" style="visibility:hidden"/>
                                </td>

                                <td width="426" valign=top>
                                    <p>
                                        <input name="destino" type="radio" id="destino2" value="mensajero" onClick="seleciumens('mensajero')"/>
                                        Mensajero<br>
                                    </p>
                                    <p>
                                        <label id="tipomensajero" name="tipomensajero" style="visibility:hidden">Tipo: 
                                            <select name="tiposmensajeros" id="tiposmensajeros" onChange="cambiar();">
                                                <option value="propio" selected >Propio</option>
                                                <option value="destajo">Destajo</option>
                                            </select>
                                        </label>
                                    </p>

                                    <div id="labpropio" style="visibility:hidden">
                                        <label for="mensajero">Nombre: </label> 
                                        <input id="mensajero" name="mensajero" type="text"   value="" maxlength="45" size="45">
                                    </div>
                                    <br>
                                    <div id="labdestajo" style="visibility:hidden">Nombre: 
                                        <input name="iddestajo" type="text" value="" id="iddestajo" size="45"  maxlength="45">
                                    </div><br>

                                    <label id="labzona" style="visibility:hidden"> Zona:
                                        <input type="text" name="zona" id="zona" size="40">
                                    </label><br><br>


                                    <div id="labtarifa" style="visibility:hidden">
                                        <label>Tarifa: 
                                            <input name="tarifa" type="text" id="tarifa" size="10">
                                        </label>
                                        <label id="tagplazo" name="tagplazo" style="visibility:hidden">Plazo de entrega:
                                            <input size="10" id="plazo" name="plazo" type="text" value="1">
                                        </label>
                                        <p></p>
                                    </div></td>
                            </tr>
                            <tr><td align=center colspan="2">
                                    <label for="idguia"><br>
                                        <br>
                                        <br>
                                        Guia: </label>


                                    <input id="idguia" name="idguia" maxlength="30"  />


                                    <div id="info"></div>

                                    <div id="enviaraotrodepartamento">
                                        <label>        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr><td colspan="2" align=center>	
                                    <label for="observaciones">Observaciones: </label> <br>   	 <textarea id="observaciones" name="observaciones"></textarea></td>
                            </tr>
                            <tr><td colspan="2">
                                    <input id="idsucursal" name="idsucursal" type="hidden" value="<?= $idsucursal ?>" />
                                    <input class="submit" type="button"  id="registrar" name="registrar" value="Registrar" onClick="enviar()" /></td>
                            </tr>
                        </table>
                    </fieldset>

                    <div class="spacer"></div>
                    <div class="full_width big" >
                        <p id="titulodetalles" style="visibility:hidden">Guias</p>
                    </div>
                    <div align="center">
                        <fieldset id="fiel" name="fiel"  style="border:none"></fieldset>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
