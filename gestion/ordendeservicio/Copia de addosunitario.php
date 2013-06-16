<? 
include ("../../clases/clases.php");
include ("../../param/param.php");

if (isset($_POST['registrar']))
{
	print_r ($_POST);
	$encGuia = $_POST['encGuia'];
	$encAsig = $_POST['encAsig'];
	$encDestinatario = $_POST['encDestinatario'];
	$datoArecordar = $_POST['datoArecordar'];
	$resdesti = NULL;
	
	if ( ($encAsig && !$encGuia) || (!$encAsig && !$encGuia) )
	{	
		$iddestinatario = $_POST['iddestinatario'];
		$ccdestinatario = $_POST['ccdestinatario'];
		$nombresdestinatario = $_POST['nombresdestinatario'];
		$apellidosdestinatario = $_POST['apellidosdestinatario'];
		$direcciondestinatario = $_POST['direcciondestinatario'];
		$telefono1destinatario = $_POST['telefono1destinatario'];
		$celulardestinatario = $_POST['celulardestinatario'];
		
		$nombres_terceroOrig = $_POST['nombres_terceroOrig'];
		$apellidos_terceroOrig = $_POST['apellidos_terceroOrig'];	
		$direccion_terceroOrig = $_POST['direccion_terceroOrig'];
		$telefono_destinatarioOrig = $_POST['telefono_destinatarioOrig'];
		$celular_destinatarioOrig = $_POST['celular_destinatarioOrig'];	
		
		$peso = $_POST['peso'];
		$idcliente	=  $_POST['idcliente'];
		$idciudadorigen = $_SESSION['datosinicio']['codigo_ciudad'];
		$ciudaddestino = $_POST['ciudaddestino'];
	
		$conex = new conexion();
		$qtrans = "SET AUTOCOMMIT=0;";
		$sac = $conex -> ejecutar($qtrans); 
		$qtrans = "BEGIN;";
		$sac = $conex->ejecutar($qtrans);
		//Datos guia
		
		$nombreproducto = $_POST['nombreproducto'];
		$idtipoproducto = $_POST['idtipoproducto'];
		
		$operacion = new operacion();
		$sql = "select idproducto from producto where lower(nombre_producto) = lower('$nombreproducto') And tipo_producto_idtipo_producto=$idtipoproducto";
		$res = $operacion -> consultar($sql);
		$fila = mysql_fetch_assoc($res);
		$idproducto = $fila["idproducto"];
		
		$guia = new guia2 ();
			
		$guia->numero_guia = $_POST['numguia'];
		$guia->orden_servicio_idorden_servicio = 1; // ojo
		$guia->zona_idzona = 1; //ojo
		$guia->causal_devolucion_idcausal_devolucion = 1; //ojo
		$guia->manifiesto_idmanifiesto = 0; //ojo
		$guia->producto_idproducto = $idproducto;
		$guia->valor_declarado_guia = $_POST['valordeclarado'];
		
		if ($encDestinatario)
			{
			// Verificar si no han sido modificados datos de destinatario
			/*if ($nombresdestinatario != $nombres_terceroOrig)
			if ($apellidosdestinatario != $apellidos_terceroOrig)
			if ($direcciondestinatario != $direccion_terceroOrig)
			if ($telefono1destinatario != $telefono_destinatarioOrig)
			if ($celulardestinatario != $celular_destinatarioOrig)
			*/	
		
		
			$guia->nombre_destinatario_guia = $nombresdestinatario.' '.$apellidosdestinatario;
			$guia->direccion_destinatario_guia = $direcciondestinatario;
			$guia->telefono_destinatario_guia = $telefono1destinatario;
		}
		else 
		{
			
			$guia->nombre_destinatario_guia = $nombresdestinatario.' '.$apellidosdestinatario;
			$guia->direccion_destinatario_guia = $direcciondestinatario;
			$guia->telefono_destinatario_guia = $telefono1destinatario;
			
			$destinatario = new destinatario();	
			
			$destinatario->tipo_identificacion_destinatario=1;
			$destinatario->documento_destinatario = $ccdestinatario;
			$destinatario->nombres_destinatario = $nombresdestinatario;
			$destinatario->apellidos_destinatario = $apellidosdestinatario;
			$destinatario->direccion_destinatario = $direcciondestinatario;
			$destinatario->datos1 = $datoArecordar;
			if ($datoArecordar == $ccdestinatario)
				$destinatario->datos2 = $telefono1destinatario;
			else
				$destinatario->datos2 = $ccdestinatario;	
			$destinatario->telefono_destinatario = $telefono1destinatario;
			$destinatario->telefono2_destinatario = '';
			$destinatario->celular_destinatario = $celulardestinatario;
			$destinatario->email_destinatario = '';
			$destinatario->observaciones_destinatario = '';
					
			$resdesti = $destinatario -> agregar(); 
			$iddestinatario = $destinatario->iddestinatario;
		
		}
		
		$guia->celular_destinatario = $celulardestinatario;
		$guia->peso_guia = $peso;
		$guia->tercero_idremitente	=  $idcliente;
		$guia->ciudad_idorigen = $idciudadorigen; //
		$guia->tercero_iddestinatario = $iddestinatario;
		$guia->ciudad_iddestino = $ciudaddestino;
		
		 $resguia = $guia -> agregar();
		 
		 if ($encDestinatario)
		 	echo $resdesti."--".$resguia;
		else 
			echo $resguia;
	}


/*
	$conex = new conexion();
	$qtrans = "SET AUTOCOMMIT=0;";
	$sac = $conex -> ejecutar($qtrans); 
	$qtrans = "BEGIN;";
	$sac = $conex->ejecutar($qtrans);


	$orden_servicios = new orden_servicio();
	$facturas = new factura();
	$movimientos = new movimiento();
	$operacion = new operacion ();
	$terceros = new tercero();
	$sucursales = new sucursal();
	
	$idusuario = $_SESSION['datosinicio']['idtercero']; // ojo SOLUCIONADO cable. Debe venir de una variable de sesion cuando ingrese al sistema
	$idsucursal = $_SESSION['datosinicio']['sucursal_idsucursal'];
	$nombresucursal = $_SESSION['datosinicio']["nombre_sucursal"];
	$idsucursal = $_SESSION['datosinicio']["sucursal_idsucursal"];
	$idciudad = $_SESSION['datosinicio']["ciudad_idciudad"];

	$facturas->agregar(); 
	$idfactura = $facturas -> idfactura;

	$horaactual = $operacion->obtenerFechayHora("hora");
	$fechaactual = $operacion->obtenerFechayHora("fecha",'d/m/Y');

	
	$numrecogida = $_POST["idrecogida"]; // ojo requiere validacion previa sobre existencia de tal numero	
	$numordenservicio = $_POST["numordenservicio"];
	$idcliente = $_POST["idcliente"];
	//$idciudad = $_POST["idciudad"];
	//$idproducto = $_POST["idproducto"];
	$fechain = $_POST["fechain"];
	$observaciones = $_POST["observaciones"];
	$plazo = $_POST["plazo"];

	$orden_servicios->factura_idfactura = $idfactura;
	$orden_servicios->tercero_idcliente = substr($idcliente,0,strpos($idcliente," -"));	
    $orden_servicios->numero_orden_servicio = $numordenservicio;
	$orden_servicios->fechaentrada = $fechain;
    $orden_servicios->observacion_orden_servicio = $observaciones;
	$orden_servicios->unidades = NULL;
	$orden_servicios->area_orden_servicio = NULL;
	$orden_servicios->plazo_entrega_orden = $plazo;
    $orden_servicios->plazo_asignacion_orden = NULL;

	$resos = $orden_servicios->agregar();

	$movimientos->estado_idestado = 5; // alistado
	$movimientos->tercero_idusuario = $idusuario;
	$movimientos->fecha_movimiento = $fechaactual;
	$movimientos->hora_movimiento = $horaactual;	
	$movimientos->orden_servicio_idorden_servicio = $orden_servicios->idorden_servicio;
	$movimientos->sucursal_idsucursal = $idsucursal;
	$movimientos->recogida_idrecogida = NULL;  	// ojo Debe ser el id de recogida de de la BD. Preguntar de donde viene este id| 
	$movimientos->manifiesto_idmanifiesto = NULL;
	$movimientos->guia_idguia = NULL;
	$movimientos->asignacion_guias_idasignacion_guias = NULL;
	$movimientos->tarifa_idtarifa = NULL;

	$resmov = $movimientos->agregar();



if ($resos===true && $resmov===true)
{
	$qtrans = "COMMIT";
	$sac = $conex -> ejecutar($qtrans); 


?>	

<script language="javascript" type="text/javascript">
var mensaje="Registro Exitoso";
window.location.href='consulta.php?mensaje='+mensaje;
</script>
	
<?
}
else
{
		$qtrans = "ROLLBACK";
		$sac = $conex -> ejecutar($qtrans); 
?>
<script language="javascript" type="text/javascript">
var mensaje="Registro NO Exitoso";
window.location.href='consulta.php?mensaje='+mensaje;
</script>
<?
}


*/
return;
}

	/*$plazodiasentregaos = $_SESSION['param']['plazodiasentregaos'];
	$operacion = new operacion();
	$fechaactual = $operacion->obtenerFechayHora('fecha','d/m/Y');

	$os = new orden_servicio();	
	$numordenservicio = $os->generarNumeroOS();*/
		

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
		<script type="text/javascript" language="javascript" src="../../js/jquery.js"></script>
		
		<script type="text/javascript" language="javascript" src="../../js/jquery.dataTables.js"></script>
		<script type="text/javascript" language="javascript" src="../../js/jquery.validate.js"></script> 
		<script type="text/javascript" src="../../js/jquery_003.js"></script>
        <script type="text/javascript" src="../../js/elementosVisiblesInvisibles.js"></script>
        

        
		<script type="text/javascript">
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
		submitHandler: function() { formulario.submit();
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
$(document).ready(function() {    
	$("#numguia").blur(function(){

  var val = $(this).attr("value");

		if (val != "")
		{
	 
        $('#info').html('<img src="../../imagenes/loader.gif" alt="" height="17" />').fadeOut(1000);
         var dataString = 'numguia='+val;
          //alert (dataString);
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
	$("#cccliente").blur(function(){

  var val = $(this).attr("value");
 
		if (val != "")
		{
	 
        $('#info2').html('<img src="../../imagenes/loader2.gif" alt="" height="17" />').fadeOut(1000);
           var dataString = 'cccliente='+val;
          //alert (dataString);
		  $.ajax({
            type: "POST",
            url: "prevalidacion2.php?cccliente="+val,
            data: dataString,
            success: function(data) {
               $('#info2').fadeIn(1000).html(data); 
            }});
		
         } // end if (val)
    });   //BLUR	   

/////////////////////////////////////////////////////////////////////////////////
	$("#datoArecordar").blur(function(){

  var val = $(this).attr("value");
 
		if (val != "")
		{
	 
        $('#info3').html('<img src="../../imagenes/loader2.gif" alt="" height="17" />').fadeOut(1000);
           var dataString = 'datoArecordar='+val;
          //alert (dataString);
		  $.ajax({
            type: "POST",
            url: "prevalidacion3.php?datoArecordar="+val,
            data: dataString,
            success: function(data) {
               $('#info3').fadeIn(1000).html(data); 
            }});
		
         } // end if (val)
    });   //BLUR	   	   

  
/////////////////////////////////////////////////////////////////////////////////
	$("#ciudaddestino").blur(function(){

  var val = $(this).attr("value");
  
 	var x=document.getElementById("ciudaddestino").selectedIndex;
	var y=document.getElementById("ciudaddestino").options;
	var innerHtmlSelec = y[x].text;
	
	var pos = innerHtmlSelec.indexOf("-");
    var ciudad = innerHtmlSelec.substring(pos,-1);
	 
	var departamento = innerHtmlSelec.substring(pos+1);
	 
// alert (ciudad+departamento);
		if (val != "")
		{
	 
        $('#info4').html('<img src="../../imagenes/loader2.gif" alt="" height="17" />').fadeOut(1000);
           var dataString = 'idciudad='+val+'&ciudaddestino='+ciudad+'&departamentodestino='+departamento;
          //alert (dataString);
		  $.ajax({
            type: "POST",
            url: "obtenerTipoProducto.php?ciudaddestino="+val,
            data: dataString,
            success: function(data) {
               $('#info4').fadeIn(1000).html(data); 
            }});
		
         } // end if (val)
    });   //BLUR	
	
/////////////////////////////////////////////////////////////////////////////////
	$("#botoncalcular").click(function(){
	
 var valordeclarado = document.getElementById('valordeclarado').value;
 var valorempaque = document.getElementById('valorempaque').value;
 
 var idtipoproducto = document.getElementById('idtipoproducto').value;
 var nombreproducto = document.getElementById('nombreproducto').value;
 
 var idcliente = document.getElementById('idcliente').value;
 var idsucursal = <?=$_SESSION['datosinicio']['sucursal_idsucursal']?> //document.getElementById('sucursal_idsucursal').value;
 

 	if (valorempaque!="" )
	 if (valordeclarado!="")
	{
       //$('#info4').html('<img src="../../imagenes/loader2.gif" alt="" height="17" />').fadeOut(1000);
           var dataString = 'valordeclarado='+valordeclarado+'&valorempaque='+valorempaque+'&idtipoproducto='+idtipoproducto+'&nombreproducto='+nombreproducto+'&idcliente='+idcliente+'&idsucursal='+idsucursal;
          //alert (dataString);
		  $.ajax({
            type: "POST",
            url: "calcularCostoEnvio.php",
            data: dataString,
            success: function(data) {
               $('#info4').fadeIn(1000).html(data); 
            }});
	}
 });   //CLICK
		
	   	   
});  
  
</script>

<style>
.nodisponible { color: red; }
.disponible { color: green;}
</style>

</head>
	<body>
		<div id="container">
			<p>&nbsp;</p>
            	Orden
         </div>
             <p>&nbsp;</p>

        <div align="center"> <?
	   	$nombreciudadorigen = $_SESSION['datosinicio']['nombre_ciudad'];
		
		$peso = $_SESSION['param']['peso'];
		$valordeclarado = $_SESSION['param']['valordeclarado'];
		
		//print_r ($_SESSION);
		?>
        <form class="formulario" id="formulario" name="formulario" method="post" action="">
<fieldset>
<table width="100%" border=0 align="center">
<tr>
	<td align="center"> 
    <label for="numguia" id="labnumguia"  style="visibility:visible" >Número de Guía:&nbsp;&nbsp;
       	  <input id="numguia" name="numguia" class="required" size="30" maxlength="10" /><img src="../../imagenes/mouse.gif" alt="Click aqui para ejecutar" width="26" height="17" title="Click aqui para ejecutar">
       	  <br>
       	  <br>
       	  <br>
	</label>
       	  <div id="info"></div>     </td>
</tr>
	<tr>
	<td>
    
        <p><span id="tituloclientes" style="visibility:hidden"><b>Datos Cliente</b></span>          </p>
            <label for="cccliente" id="labcccliente" style="visibility:hidden">
         <p>C.C Cliente:&nbsp;&nbsp;
          <input id="cccliente" name="cccliente" class="required" size="30" maxlength="10" style="visibility:hidden" />
          <input id="idcliente" name="idcliente" type="hidden" value=""/>
          <img src="../../imagenes/mouse.gif" alt="Click aqui para ejecutar" width="26" height="17" title="Click aqui para ejecutar">         </p>
        <div id="info2" style="display:inline"></div>
        </label>
         
		  <p>
            <label for="nombrescliente" id="labnombrescliente" style="visibility:hidden">Nombre Cliente:&nbsp;&nbsp;
            <input id="nombrescliente" name="nombrescliente" class="required" size="30" maxlength="20" style="visibility:hidden" />
            </label>
          </p>
		  <p>
            <label for="apellidoscliente" id="labapellidoscliente" style="visibility:hidden">Apellidos Cliente:&nbsp;&nbsp;
          <input id="apellidoscliente" name="apellidoscliente" class="required" size="30" maxlength="20" style="visibility:hidden" />
            </label>
	    </p>
		  <p>
            <label for="apellidoscliente" id="labdireccioncliente" style="visibility:hidden">Direccion Cliente:&nbsp;&nbsp;
          <input id="direccioncliente" name="direccioncliente" class="required" size="60" maxlength="80" style="visibility:hidden" />
            </label>
	    </p>            
	    <p>&nbsp;</p>         </td>
	</tr>     	
<tr>
			<td >
            
        <span id="titulodestinatarios"  style="visibility:hidden"><b>Datos Destinatario</b></span>
        <br><br>
            <label for="datoArecordar" id="labdatoArecordar" style="visibility:hidden">C.C ó Teléfono:&nbsp;&nbsp;
            <input id="datoArecordar" name="datoArecordar" class="required" size="30" maxlength="10" style="visibility:hidden" />
            <img src="../../imagenes/mouse.gif" alt="Click aqui para ejecutar" width="26" height="17" style="display:inline" title="Click aqui para ejecutar">
            <div id="info3" style="display:inline-block"></div> 
            <div id="info2" style="display:inline-block"> </div>
          </label>
          
                   
          <p>
            <label for="ccdestinatario" id="labccdestinatario" style="visibility:hidden">C.C destinatario:&nbsp;&nbsp;
            <input id="ccdestinatario" name="ccdestinatario" class="required" size="30" maxlength="10" style="visibility:hidden" />
            <img src="../../imagenes/copy.jpg"  alt="Copiar valor desde C.C ó Teléfono" width="32" height="35" border="0" title="Copiar valor desde C.C ó Teléfono" onClick="javascript: document.getElementById('ccdestinatario').value = document.getElementById('datoArecordar').value";/></label>
          </p>
		  <p>
            <label for="nombresdestinatario" id="labnombresdestinatario" style="visibility:hidden">Nombre destinatario:&nbsp;&nbsp;
            <input id="nombresdestinatario" name="nombresdestinatario" class="required" size="30" maxlength="20" style="visibility:hidden" />
            </label>
          </p>
		  <p>
            <label for="apellidosdestinatario" id="labapellidosdestinatario" style="visibility:hidden">Apellidos destinatario:&nbsp;&nbsp;
          <input id="apellidosdestinatario" name="apellidosdestinatario" class="required" size="30" maxlength="20" style="visibility:hidden" />
            </label>
		    </p>
		  <p>
            <label for="direcciondestinatario" id="labdirecciondestinatario" style="visibility:hidden">Direccion destinatario:&nbsp;&nbsp;
          <input id="direcciondestinatario" name="direcciondestinatario" class="required" size="60" maxlength="80" style="visibility:hidden" />
            </label>
		    </p>
		  <p>
		    <label for="telefono1destinatario" id="labtelefono1destinatario" style="visibility:hidden">Telefono destinatario:&nbsp;&nbsp;
            <input id="telefono1destinatario" name="telefono1destinatario" class="required" size="30" maxlength="20" style="visibility:hidden" /><img src="../../imagenes/copy.jpg" alt="Copiar valor desde C.C ó Teléfono" width="30" height="32" border="0" title="Copiar valor desde C.C ó Teléfono"  onClick="javascript: document.getElementById('telefono1destinatario').value = document.getElementById('datoArecordar').value";/>            </label>
		    </p>
		  <p> <label for="celulardestinatario" id="labcelulardestinatario" style="visibility:hidden">Celular destinatario:&nbsp;&nbsp; 
              <input id="celulardestinatario" name="celulardestinatario" class="required" size="20" maxlength="30" style="visibility:hidden" />
           (*) </label>
		  </p>
			
            <p>&nbsp;</p>          </td>
	</tr>		
    <tr>
		<td colspan="2">

        <div id="capadatosguia" style="visibility:hidden">
        <span id="titulodestinatarios"><b>Datos Guia</b></span>
        	<label for="idciudad"><br>
        	<br>
            Ciudad Origen</label>
			<input id="idciudad" name="idciudad"  class="required" maxlength="15" value="<?=$nombreciudadorigen?>"/>	
              <label for="labciudaddestino"><br>
            <br>
            Ciudad Destino</label>
		<!-- <input id="ciudaddestino" name="ciudaddestino"  class="required" maxlength="15"/> -->
        <select id="ciudaddestino" name="ciudaddestino" style="display:inline">
    	<option value="">Seleccione uno...</option>
			<? $operaciones = new operacion();
	
			$qry="SELECT ciudad.idciudad, ciudad.nombre_ciudad, ciudad.departamento_iddepartamento, departamento.iddepartamento, departamento.nombre_departamento FROM ciudad, departamento WHERE ciudad.departamento_iddepartamento = departamento.iddepartamento
ORDER BY ciudad.nombre_ciudad";
			$res2=$operaciones->consultar($qry);
   		if ( mysql_num_rows($res2)>0 )
     	while ($filas=mysql_fetch_assoc($res2)) //$filas['idciudad'] //$filas['nombre_ciudad']
	      echo "<option value='".trim($filas['idciudad'])."'>$filas[nombre_ciudad]-$filas[nombre_departamento]</option>";
		  ?>
	</select>
        
        
   		<?	
          	/*   <!-- SELECT DE CIUDADES --> <script language="javascript" src="../../js/jsautocomplete/jquery-1.8.0.js" type="text/javascript"></script>
                <script src="../../js/jsautocomplete/jquery-1.8.0.js"></script>
    	   <script src="../../js/jsautocomplete/jquery.ui.core.js"></script>
			<script src="../../js/jsautocomplete/jquery.ui.widget.js"></script>
			<script src="../../js/jsautocomplete/jquery.ui.button.js"></script>
			<script src="../../js/jsautocomplete/jquery.ui.position.js"></script>
			<script src="../../js/jsautocomplete/jquery.ui.autocomplete.js"></script>

			<link rel="stylesheet" href="../../css/cssautocomplete/jquery.ui.all.css">
		     <script src="../../js/jsautocomplete/selectautocomplete.js"></script>
			 <div class="ui-widget">
			<select id="ciudaddestino" name="ciudaddestino" style="display:inline">
    	<option value="">Seleccione uno...</option>
        <option value="cali">alaal</option>
        <option value="medellin">aqui</option>
			$operaciones = new operacion();
	
			$qry="SELECT ciudad.idciudad, ciudad.nombre_ciudad, ciudad.departamento_iddepartamento, departamento.iddepartamento, departamento.nombre_departamento FROM ciudad, departamento WHERE ciudad.departamento_iddepartamento = departamento.iddepartamento
ORDER BY ciudad.nombre_ciudad";
			$res2=$operaciones->consultar($qry);
   		if ( mysql_num_rows($res2)>0 )
     	while ($filas=mysql_fetch_assoc($res2)) //$filas['idciudad'] //$filas['nombre_ciudad']
	      echo "<option value='".trim($filas['nombre_ciudad'])."'>$filas[nombre_ciudad] - $filas[nombre_departamento]</option>";
	</select>  </div>	*/?>	
	
   <div id="info4" style="display:inline"></div>
     <img src="../../imagenes/mouse.gif" alt="Click aqui para ejecutar" title="Click aqui para ejecutar" width="26" height="17" style="display:inline-block">
    
             <label for="labtipoproducto"><br><br>
            Tipo Producto&nbsp;</label>
			<input id="tipoproducto" name="tipoproducto"  class="required" readonly maxlength="15"/>
            <input id="idtipoproducto" name="idtipoproducto" type="hidden"/>
            <br><br>
            <label for="labnombreproducto">Producto</label>
            <select name="nombreproducto" id="nombreproducto">
              
              <?
              $operacion = new operacion();
			  $sql = "select nombre_producto from producto group by nombre_producto";
			  $res = $operacion->consultar ($sql);
			  
			  
			  while ($fila = mysql_fetch_assoc($res)) 
			   {
			   $nombre_producto = $fila["nombre_producto"];
			  
			  ?>
              <option value="<?=strtolower($nombre_producto)?>"><?=strtoupper($nombre_producto)?></option>
            <?
            	}
			?>
            </select>
            <br>
            <br>
           <!-- <input id="idproducto" name="idproducto"  class="required" maxlength="15"/> -->
            
            	
		    
		    <label for="labpeso"><br><br>
            Peso&nbsp;</label>
			<input id="peso" name="peso" class="required" maxlength="3" size="5" value="<?=$peso?>"/> 
            <br>
            <label for="labvalordeclarado"><br>
            Valor declarado&nbsp;</label>
			<input id="valordeclarado" name="valordeclarado"  class="required" maxlength="10" value="<?=$valordeclarado?>"/> 
            <br>
            <label for="labvalorempaque"><br>
            Valor empaque&nbsp;</label>
			<input id="valorempaque" name="valorempaque"  class="required" maxlength="10"/> 
            <br><br>
            <input type="button"  id="botoncalcular" name="botoncalcular" value="Calcular Monto" />
            <br>
            <label for="labvalortotal"><br>
            Valor Total&nbsp;</label>
			<input id="valortotal" name="valortotal"  class="required" maxlength="10" style="background-color:#009900; text-align:center; font-weight:bold"/> 
            <input id="valortotal2" name="valortotal2"  type="hidden" /> 
            <br>
            <pre>
            Valor Total = Valor de Envio +( Valor Declarado * Porcentaje Seguro)+ Valor Empaque
            </pre>
	        <p>&nbsp;</p>         
          </div>          </td>
	</tr>       	
	        	
	<tr>
		<td colspan='2'>
        <input class="submit" type="submit"  id="registrar" name="registrar" value="Registrar" onClick="return validar();"/>									         </td>
    </tr>    
</table>
</fieldset>
      </form>
  </div>

</body>
</html>
