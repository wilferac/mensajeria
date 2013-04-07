<?
   include ("../../clases/clases.php");
   include ("../../param/param.php");

   include '../../security/User.php';
   include ('../../Menu.php');

   $objUser = unserialize($_SESSION['currentUser']);

   if ($objUser->getStatus() != 1)
   {
       $operacion->redireccionar('No Puede entrar', 'index.php');
       return;
   }

   if (isset($_POST['registrar']))
   {
       $conex = new conexion();
       $qtrans = "SET AUTOCOMMIT=0;";
       $sac = $conex->ejecutar($qtrans);
       $qtrans = "BEGIN;";
       $sac = $conex->ejecutar($qtrans);


       $orden_servicios = new orden_servicio();
       //$facturas = new factura();
       $movimientos = new movimiento();
       $operacion = new operacion ();
       $terceros = new tercero();
       $sucursales = new sucursal();

       $idusuario = $_SESSION['datosinicio']['idtercero']; // ojo SOLUCIONADO cable. Debe venir de una variable de sesion cuando ingrese al sistema
       $idsucursal = $_SESSION['datosinicio']['sucursal_idsucursal'];
       $nombresucursal = $_SESSION['datosinicio']["nombre_sucursal"];
       $idsucursal = $_SESSION['datosinicio']["sucursal_idsucursal"];
       $idciudad = $_SESSION['datosinicio']["ciudad_idciudad"];

       //$facturas->agregar();
       // no agrego un id de factura, ya que las ordenes pueden ser acumuladas bajo una misma factura.
       $idfactura = "NULL";

       $horaactual = $operacion->obtenerFechayHora("hora");
       $fechaactual = $operacion->obtenerFechayHora("fecha", 'd/m/Y');

       //-- no se esta usando -_-'
       //--$numrecogida = $_POST["idrecogida"]; // ojo requiere validacion previa sobre existencia de tal numero	
       $numordenservicio = $_POST["numordenservicio"];
       $idcliente = $_POST["idcliente"];
       //$idciudad = $_POST["idciudad"];
       //$idproducto = $_POST["idproducto"];
       $fechain = $_POST["fechain"];
       $fechaAsignacion = $_POST["fechaAsignacion"];
       $fechaEntrega = $_POST["fechaEntrega"];
       
       $areaOrden = $_POST["areaOrden"];
       
       $observaciones = $_POST["observaciones"];
       $plazo = $_POST["plazo"];

       $orden_servicios->factura_idfactura = $idfactura;
       $orden_servicios->tercero_idcliente = substr($idcliente, 0, strpos($idcliente, " -"));
       $orden_servicios->numero_orden_servicio = $numordenservicio;
       $orden_servicios->fechaentrada = $fechain;
       $orden_servicios->observacion_orden_servicio = $observaciones;
       $orden_servicios->unidades = NULL;
       $orden_servicios->area_orden_servicio = $areaOrden;
       $orden_servicios->plazo_entrega_orden = $fechaEntrega;
       $orden_servicios->plazo_asignacion_orden = $fechaAsignacion;

       $resos = $orden_servicios->agregar();
       
//       $query ="INSERT INTO orden_servicio (plazo_entrega_orden,plazo_asignacion_orden,factura_idfactura,tercero_idcliente,numero_orden_servicio,fechaentrada,observacion_orden_servicio,unidades,area_orden_servicio,plazo_entrega_orden,plazo_asignacion_orden)
//                                     values('$fechaEntrega','$fechaAsignacion',$idfactura,$orden_servicios->tercero_idcliente,'$numordenservicio','$fechain',$observaciones,0,$areaOrden)";

//       $movimientos->estado_idestado = 5; // alistado
//       $movimientos->tercero_idusuario = $idusuario;
//       $movimientos->tercero_idaliado = 1;
//       $movimientos->fecha_movimiento = $fechaactual;
//       $movimientos->hora_movimiento = $horaactual;
//       $movimientos->orden_servicio_idorden_servicio = $orden_servicios->idorden_servicio;
//       $movimientos->sucursal_idsucursal = $idsucursal;
//       $movimientos->recogida_idrecogida = NULL;   // ojo Debe ser el id de recogida de de la BD. Preguntar de donde viene este id| 
//       $movimientos->manifiesto_idmanifiesto = NULL;
//       $movimientos->guia_idguia = NULL;
//       $movimientos->asignacion_guias_idasignacion_guias = NULL;
//       $movimientos->tarifa_idtarifa = NULL;
//
//       $resmov = $movimientos->agregar();



       if ($resos === true)
       {
           $qtrans = "COMMIT";
           $sac = $conex->ejecutar($qtrans);
           ?>	

           <script language="javascript" type="text/javascript">
               var mensaje = "Registro Exitoso";
               alert(mensaje);
               window.location.href = 'consulta.php';
           </script>

           <?
       }
       else
       {
           $qtrans = "ROLLBACK";
           $sac = $conex->ejecutar($qtrans);
           ?>
           <script language="javascript" type="text/javascript">
               var mensaje = "Registro NO Exitoso";
               alert(mensaje);
               //window.location.href = 'consulta.php?mensaje=' + mensaje;
           </script>
           <?
       }

       return;
   }

   $plazodiasentregaos = $_SESSION['param']['plazodiasentregaos'];
   $operacion = new operacion();
   $fechaactual = $operacion->obtenerFechayHora('fecha', 'd/m/Y');

   $os = new orden_servicio();
   $numordenservicio = $os->generarNumeroOS();
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
            @import "../../media/css/jquery-ui.css";
        </style>
        <script type="text/javascript" language="javascript" src="../../js/jquery.js"></script>
        <!--<script type="text/javascript" language="javascript" src="../../../js/jquery-1.9.1.js"></script>-->
        <script type="text/javascript" language="javascript" src="../../js/jquery-ui.js"></script>
        <script type="text/javascript" language="javascript" src="../../js/jquery.dataTables.js"></script>
        <script type="text/javascript" language="javascript" src="../../js/jquery.validate.js"></script> 
        <script type="text/javascript" src="../../js/jquery_003.js"></script>

        <script type="text/javascript">
            $().ready(function()
            {
                $("#idcliente").autocomplete("../tercero/searchtercero.php", {
                    minChars: 0, max: 200, width: 350});
            });

            $().ready(function()
            {
                $("#idciudad").autocomplete("../ciudad/searchciudad.php", {
                    minChars: 0, max: 50, width: 350});
            });

            $().ready(function()
            {
                $("#idproducto").autocomplete("../producto/searchproducto.php", {
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
                // validate the comment form when it is submitted
                $("#formulario").validate();

                // validate signup form on keyup and submit
            });
        </script>
        <script type="text/javascript" language="javascript" src="../../js/funciones.js">
            //DiferenciaFechas( document.getElementById('fechaactual').value,document.getElementById('fechain').value );
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
            parent.frames[0].document.getElementById("s2").style.visibility = "visible";

            parent.frames[0].document.getElementById("a3").innerHTML = "Ver Ordenes de Servicio";
            parent.frames[0].document.getElementById("a3").href = "gestion/ordendeservicio/consulta.php";

        </script>    

    </head>
    <body id="dt_example">


        <?
           //generar menu
           $objMenu = new Menu($objUser);
           $objMenu->generarMenu();
//	 $operacion -> menu();
        ?>
        <div id="container">
            <div class="full_width big">
                <p>&nbsp;</p>
                Orden de Servicio
            </div>
            <p>&nbsp;</p>

            <div id="dynamic"><fieldset>
                    <form class="formulario" id="formulario" name='formulario' method="post" action="">

                        <table align="left" border=0 width="100%">
                            <tr>
                                <td>
                                    <label for="idrecogida">Número de Recogida</label>
                                </td>
                                <td>	
                                    <input id="idrecogida" name="idrecogida"  maxlength="10"/>
                                </td>
                            </tr> 
                            <tr>
                                <td>
                                    <label for="idcliente">Nombre del Cliente (Obligatorio)</label>
                                </td>
                                <td>	
                                    <input id="idcliente" name="idcliente" class="required" size=40 maxlength="60" title="Selecciona un cliente" required/></p>
                                </td>
                            </tr>   
                            <tr>
                                <td>
                                    <label for="areaOrden">Area de la Empresa</label>
                                </td>
                                <td>	
                                    <input id="areaOrden" name="areaOrden" size=40 maxlength="60" title="información del area de la empresa que envia" /></p>
                                </td>
                            </tr> 
                            <tr>
                                <td>
                                    <label for="numordenservicio">Número de Orden de Servicio</label>
                                </td>
                                <td>	
                                    <input id="numordenservicio" value="<?= $numordenservicio ?>" name="numordenservicio" maxlength="10" readonly/>
                                </td>
                            </tr>     	

<!--	<tr>
        <td>
    <label for="idciudad">Ciudad Origen (Obligatorio)</label>
        </td>
        <td>	
                <input id="idciudad" name="idciudad"  class="required" maxlength="15"/>
        </td>
</tr>       	
<tr>
        <td>
         <label for="idproducto">Producto (Obligatorio)</label>
        </td>
        <td>	
<select name="idproducto" id="idproducto"> 
        <option value="Masivo">Masivo</option>	
</select>
<input id="idproducto" name="idproducto"  class="required" maxlength="15"/> 
        </td>
</tr>   -->    	
                            <tr>
                                <td><label for="fechain">Fecha Entrada (Obligatorio)</label>
                                </td>
                                <td>
                                    <input id="fechain" name="fechain" type="date" value=""   required/>


                                    <input type="hidden" id="fechaactual" name="fechaactual" value="<?= $fechaactual ?>" />
                                </td>
                            </tr>   
                            <tr>
                                <td><label for="fechaAsignacion">Limite Asiganación (Obligatorio)</label>
                                </td>
                                <td>
                                    <input id="fechaAsignacion" name="fechaAsignacion" type="date" value=""   required/>


                                </td>
                            </tr> 
                            <tr>
                                <td><label for="fechaEntrega">Limite Entrega (Obligatorio)</label>
                                </td>
                                <td>
                                    <input id="fechaEntrega" name="fechaEntrega" type="date" value=""   required/>


                                </td>
                            </tr>        	
                            <tr>
                                <td><label for="observaciones">Observaciones </label>		</td>
                                <td>
                                    <textarea id="observaciones" name="observaciones" onClick ="return DiferenciaFechas(document.getElementById('fechaactual').value, document.getElementById('fechain').value, document.getElementById('plazo'), '+', document.getElementById('plazodiasentregaos').value)"></textarea>
                                </td>
                            </tr>       	
                            <tr>
                                <td colspan='2'><input class="submit" type="submit"  id="registrar" name="registrar" value="Registrar" onClick="return validar();"/>
                                </td>
                                </fieldset>
                        </table>

                    </form>

            </div>
        </div>

        <script language="javascript">
            var f = new Date();
            var mes = f.getMonth() + 1;
            var dia = f.getDate();
            var hoy;
            if ((f.getDate()) < 10)
                dia = "0" + f.getDate();


            if ((f.getMonth() + 1) < 10)
                mes = "0" + (f.getMonth() + 1);

            hoy =  f.getFullYear()+"/"+mes + "/" + dia;
            document.getElementById("fechain").value = hoy;

            $('input[type=date]').datepicker({
                dateFormat: 'yy/mm/dd'
            });

        </script>
    </body>
</html>
