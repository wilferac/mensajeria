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





   if (isset($_POST["causal"]))
   {

       $estadomovimiento = 1; //Creacion
       $flagmodiguia = true;
       $mensguiasnoenc = "";
       $idusuario = $_SESSION['datosinicio']['idtercero'];

//	print_r ($_POST);
       $guia = new guia();
       $conex = new conexion();

       foreach ($_POST as $clave => $valor)
       {
           $ifguia = substr($clave, 0, 4);
           if ($ifguia == "guia")
           {
               $idcausal = substr($clave, -1, 1);

               $cond = "numero_guia=$valor";
               $res = $guia->consultar($cond);
               if (mysql_num_rows($res) > 0)
               {
                   $strinset = "causal_devolucion_idcausal_devolucion=$idcausal";
                   $rmodiguia = $guia->modificar2($strinset, $valor);
                   if ($rmodiguia === false)
                       $flagmodiguia == false;
               }
               else
                   $mensguiasnoenc.= $valor;
           }
       }



       if ($flagmodiguia === true)
       {
           $qtrans = "COMMIT";
           $sac = $conex->ejecutar($qtrans);
           ?>	
           <script language="javascript" type="text/javascript">
               var mensaje = "Registro Exitoso";
               window.location.href = 'consulta.php?mensaje=' + mensaje;
           </script>

           <?
       }
       else
       {
           ?>
           <script language="javascript" type="text/javascript">
               var mensaje = "Registro NO Exitoso";
               window.location.href = 'consulta.php?mensaje=' + mensaje;
           </script>
           <?
       }
   }

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
        <title>Pistoleo</title>
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
                    document.getElementById("mensajero").style.visibility = "hidden"

                    document.getElementById("tagpistoleo").style.visibility = "visible";

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
                    document.getElementById("ciudad").style.visibility = "hidden";

                    document.getElementById("tagpistoleo").style.visibility = "hidden";
                    document.getElementById("pistoleo").style.visibility = "hidden";


                    for (i = 0; i < document.formulario.pistoleo.length; i++)
                    {
                        document.formulario.pistoleo[i].style.visibility = "hidden";
                    }

                    document.getElementById("tagbloque").style.visibility = "hidden";
                    document.getElementById("tagdesde").style.visibility = "hidden";
                    document.getElementById("taghasta").style.visibility = "hidden";
                    document.getElementById("desde").style.visibility = "hidden";
                    document.getElementById("hasta").style.visibility = "hidden";


                    document.getElementById("tagplazo").style.visibility = "visible";
                    document.getElementById("plazo").style.visibility = "visible";
                }
                if (nombre == "bloque")
                {
                    document.getElementById("tagdesde").style.visibility = "visible";
                    document.getElementById("taghasta").style.visibility = "visible";
                    document.getElementById("desde").style.visibility = "visible";
                    document.getElementById("hasta").style.visibility = "visible";
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
        <!*************************************************>

        <script type="text/javascript">

            document.onkeypress = function(e) {
                var esIE = (document.all);
                tecla = (esIE) ? event.keyCode : e.which;
                if (tecla == 13)
                {


                    guiaadd();
                }
                return (tecla);
            };



            var num = 0;
            function guiaadd() {

                var fs = document.getElementById("fiel");
                var inps = fs.getElementsByTagName("INPUT");
                var sAux = "";
                var frm = document.getElementById("fiel");


                for (i = 0; i < inps.length; i++)
                {
                    sAux = inps[i].value
                    if (sAux == document.getElementById("idguia").value || document.getElementById("idguia").value == "")
                    {
                        document.getElementById("idguia").value = "";
                        return false;
                    }
                }

                var idguia = document.getElementById("idguia").value;

                var dataString = 'numguia=' + idguia;

                $.ajax({
                    type: "POST",
                    url: "validarguiapistoleo.php",
                    data: dataString,
                    success: function(data) {

                        limpio = data.replace(/^\s+/, '').replace(/\s+$/, '');
                        data = limpio.substring(0, 1);
                        alert(data);

                        if (data == "1")
                        {
                            mani = limpio.substring(1);
                            //$('#info').fadeIn(1000).html("+mani+");
                        }
                        if (data == "2")
                            $('#info').fadeIn(1000).html("El número de guía " + idguia + " no tiene relacionado manifiesto .");
                        if (data == "3")
                            $('#info').fadeIn(1000).html("El número de guía " + idguia + " no existe.");

                        if (data != "2" && data != "3")
                        {
                            $('#info').fadeIn(1000).html("");
                            guiaadd2();
                        }

                    }});


            }






            function guiaadd2() {
                var marcadev = document.formulario.causal[1].checked;
                var marcaentre = document.formulario.causal[0].checked;
                var texto = "";
                if (marcadev == true)
                {
                    texto = "DEVOLUCION"; //DEVOLUCION
                    id = 2;  // id corresponde con la tabla causal_devolucion
                }
                else if (marcaentre == true)
                {
                    texto = "ENTREGA";  //"ENTREGA"
                    id = 3;  // id corresponde con la tabla causal_devolucion
                }
                //alert(texto);

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
                ele.name = 'guia' + num + '_' + id;
                ele.width = 20;
                ele.value = document.getElementById("idguia").value;
                ele.size = 20;
                ele.readOnly = true;
                contenedor.appendChild(ele);

                ele = document.createElement('input');
                ele.type = 'text';
                ele.name = 'devOentre' + num;
                ele.width = 20;
                ele.value = texto;
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

                var ciudad = document.getElementById("ciudad").value;
                var mensajero = document.getElementById("mensajero").value;

                var plazo = document.getElementById("plazo").value;

                var msg = "";


                if (ciudad == "" && mensajero == "")
                    msg = msg + "Debe seleccionar una ciudad o un mensajero\n";
                else if (mensajero != "")
                    if (plazo == "")
                        msg = msg + "Debe Ingresar un plazo de entrega\n";
                if (inps.length == 0)
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

    </head>
    <body id="dt_example">
        <?
           //generar menu
           $objMenu = new Menu($objUser);
           $objMenu->generarMenu();
//		   	$operacion = new operacion();
//			$operacion -> menu();
        ?>
        <?
           $causales = new causales();
           $res = $causales->consultar();

           $options = "";
           if (mysql_num_rows($res) > 0)
           {

               while ($fila = mysql_fetch_assoc($res))
               {
                   $idcausales = $fila["idcausales"];
                   $nombrecausales = $fila["nombrecausales"];
                   $descripcioncausales = $fila["descripcioncausales"];
                   $options.="<option value=$idcausales>$nombrecausales</option>";
               }
           }
        ?>

        <div id="container">
            <div class="full_width big">
                <p>&nbsp;</p>Pistoleo
            </div>
            <p>&nbsp;</p>
            <div>Ciudad:   <?= $nombre_ciudad ?> </div>
            <div>Sucursal:   <?= $nombresucursal ?> </div>
            <p>&nbsp;</p>
            <div id="dynamic">
                <form class="formulario" id="formulario" name='formulario' method="post" action="" >
                    <fieldset>
                        <table border=0 cellspacing=20 width=100%>
                            <tr>
                                <td><label id="tagentrega" name="tagentrega">Entrega<input type=radio name=causal id=causal value=entrega></label><br><br>
                                    <label id="tagdevolucion" name="tagdevolucion">Devolucion <input checked type=radio name=causal id=causal value=devolucion></label><br>
                                </td>
                            </tr>
                            <tr>
                                <td><label id="tagcausales" name="tagcausales">Causales
                                        <select  name="causales" id="causales">
                                            <? echo $options ?>
                                        </select>
                                    </label>
                                    <br><br>

                                </td>
                            </tr>

                            <tr><td align=center>
                                    <label for="idguia">Guia: </label><input id="idguia" name="idguia" maxlength="30"  />
                                    <div id="info"></div>

                                </td>
                            </tr>

                            <tr><td>
                                    <input id="idsucursal" name="idsucursal" type="hidden" value="<?= $idsucursal ?>" />
                                    <input class="submit" type="button"  id="registrar" name="registrar" value="Registrar" onClick="enviar()" />
                                </td>
                            </tr>
                        </table>
                    </fieldset>

                    <div class="spacer"></div>
                    <div class="full_width big" >
                        <p id="titulodetalles" style="visibility:hidden">Guias</p>
                    </div>
                    <div align="center">
                        <fieldset id="fiel" name="fiel"  style="border:none">
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
