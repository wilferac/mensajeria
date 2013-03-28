<? 
 include("../../clases/clases.php");

	$idos = $_GET["id"];
 	
	$os = new orden_servicio();	
	$guia = new guia();
	$tercero = new tercero();
 	$operacion = new operacion();

	$cond = "idorden_servicio=$idos";
	$fila = $operacion->consultarmultiple($os,$cond);		

	$idorden_servicio = $fila["idorden_servicio"];
	$factura_idfactura = $fila["factura_idfactura"];
	$tercero_idcliente = $fila["tercero_idcliente"]; 
	$numero_orden_servicio = $fila["numero_orden_servicio"];
	$fechaentrada = $fila["fechaentrada"];	
	$observacion_orden_servicio = ucfirst ($fila["observacion_orden_servicio"]);
	$unidades = $fila["unidades"];
	$area_orden_servicio = $fila["area_orden_servicio"];
	$plazo_entrega_orden = $fila["plazo_entrega_orden"];
	$plazo_asignacion_orden = $fila["plazo_asignacion_orden"];

	$cond ="idtercero=$tercero_idcliente";
	$filas = $operacion->consultarmultiple($tercero,$cond);	 
	$nombres_tercero = $filas["nombres_tercero"]; 		
	$apellidos_tercero = $filas["apellidos_tercero"];
	$documento_tercero = $filas["documento_tercero"];

	$dataSetini="[";
	$dataSet="";
    $dataSet=$dataSet."['$documento_tercero','$nombres_tercero','$apellidos_tercero','$fechaentrada','$numero_orden_servicio','$observacion_orden_servicio','$plazo_entrega_orden','$unidades'],";
	$dataSet=substr_replace($dataSet,"];",strlen($dataSet)-1);
	$dataSet=$dataSetini.$dataSet; 


	$zona = new zona();
	$causal_devolucion = new causal_devolucion();
	$producto = new producto();
	$ciudad = new ciudad();
 	$tipo_producto = new tipo_producto();


	$dataSetini="[";
	$dataSet2="";
	$cond = "orden_servicio_idorden_servicio=$idorden_servicio and tercero_idremitente=$tercero_idcliente";
	$res = $guia->consultar($cond);
	
	$internacionales = 0;
	$nacionales = 0;
	$regionales = 0;
	$urbanos = 0;
	$trayectoEspecial = 0;
	
	while ( $filas = mysql_fetch_assoc($res) )
	{
		$numero_guia = $filas["numero_guia"];
		$zona_idzona = $filas["zona_idzona"]; //
		$causal_devolucion_idcausal_devolucion = $filas["causal_devolucion_idcausal_devolucion"]; //
		$manifiesto_idmanifiesto = $filas["manifiesto_idmanifiesto"]; //
		$producto_idproducto = $filas["producto_idproducto"]; //
		$ciudad_iddestino = $filas["ciudad_iddestino"];  //
		$valor_declarado_guia = $filas["valor_declarado_guia"];
		$nombre_destinatario_guia = $filas["nombre_destinatario_guia"];
		$direccion_destinatario_guia = $filas["direccion_destinatario_guia"];
		$telefono_destinatario_guia = $filas["telefono_destinatario_guia"];
		//$dato1_destinatario_guia = $filas["dato1_destinatario_guia"];
		//$dato2_destinatario_guia = $filas["dato2_destinatario_guia"];
		$peso_guia = $filas["peso_guia"];
		$ciudad_idorigen = $filas["ciudad_idorigen"]; //
		$tercero_idremitente = $filas["tercero_idremitente"]; //
		$tercero_iddestinatario = $filas["tercero_iddestinatario"]; //

		$cond = "idzona=$zona_idzona";
		$fila = $operacion->consultarmultiple($zona,$cond);
		$nombrezona = $fila["nombre_zona"];	
		

		$cond = "idcausal_devolucion=$causal_devolucion_idcausal_devolucion";
		$fila = $operacion->consultarmultiple($causal_devolucion,$cond);
		$nombre_causal_devolucion = $fila["nombre_causal_devolucion"];	

		$cond = "idproducto=$producto_idproducto";
		$fila = $operacion->consultarmultiple($producto,$cond);
		$nombre_producto = $fila["nombre_producto"];

		$tipo_producto_idtipo_producto = $fila["tipo_producto_idtipo_producto"];
		$cond = "idtipo_producto=$tipo_producto_idtipo_producto";
		$fila = $operacion->consultarmultiple($tipo_producto,$cond);
		$nombre_tipo_producto = $fila["nombre_tipo_producto"];	

		$cond = "idciudad=$ciudad_iddestino";
		$fila = $operacion->consultarmultiple($ciudad,$cond);
		$nombre_ciudad_destino = $fila["nombre_ciudad"];

		$cond = "idciudad=$ciudad_idorigen";
		$fila = $operacion->consultarmultiple($ciudad,$cond);
		$nombre_ciudad_origen = $fila["nombre_ciudad"];

		

$dataSet2=$dataSet2."['$numero_guia','$valor_declarado_guia','$nombre_destinatario_guia','$direccion_destinatario_guia',
'$telefono_destinatario_guia','$peso_guia','$nombrezona','$nombre_causal_devolucion','$nombre_producto','$nombre_tipo_producto ','$nombre_ciudad_origen','$nombre_ciudad_destino'],";	

	$nombre_tipo_producto = strtolower($nombre_tipo_producto);
	switch ($nombre_tipo_producto)
	{
		case "internacional": $internacionales++; break;
		case "nacional": $nacionales++; break;
		case "regional": $regionales++; break;
		case "urbano": $urbanos++; break;
		case "trayecto especial": $trayectoEspecial++; break;
	
	}	
	
	}
	$dataSet2=substr_replace($dataSet2,"];",strlen($dataSet2)-1);
	$dataSet2=$dataSetini.$dataSet2; 

  
	$dataSet3="";
	$dataSet3=$dataSet3."['$internacionales','$nacionales','$regionales','$urbanos','$trayectoEspecial'],";
	$dataSet3=substr_replace($dataSet3,"];",strlen($dataSet3)-1);
	$dataSet3=$dataSetini.$dataSet3; 

	
	
	
	
	$vacio=false;
	$vacio2=false;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Detalle de Orden de Servicio</title>
		<style type="text/css" title="currentStyle">
			@import "../../media/css/demo_page.css";
			@import "../../media/css/demo_table.css";
		</style>
   <script type="text/javascript" charset="utf-8">
			function wo(obj)
			{
				window.open(obj,"Detalle","location=0,toolbar=0,menubar=0,resizable=1,width=900,height=500");
				return false;
			}
		</script>
		<script type="text/javascript" language="javascript" src="../../js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../../js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8">
			
			var aDataSet = <?=$dataSet?>
			
			$(document).ready(function() {
				$('#dynamic').html( '<table cellpadding="0" cellspacing="0"  border="0" class="display" id="example"></table>' );
				$('#example').dataTable( {
					"aaData": aDataSet,
					"aoColumns": [
						{ "sTitle": "Documento cliente" },
						{"sTitle": "Nombre Cliente" },
						{"sTitle": "Apellido Cliente" },
						{"sTitle": "Fecha Entrada" },
						{"sTitle": "Numero orden" },
						{"sTitle": "Observacion" },
						{"sTitle": "Plazo Entrega" },
						{"sTitle": "Unidades" }
						
					],
			"sDom": 'T<"clear">t'
				} );	
			} );
			
					
					var aDataSet3 = <?=$dataSet3?>
			
			$(document).ready(function() {
				$('#dynamic3').html( '<table cellpadding="0" cellspacing="0"  border="0" class="display" id="example3"></table>' );
				$('#example3').dataTable( {
					"aaData": aDataSet3,
					"aoColumns": [
						{ "sTitle": "T.P Internacional" },
						{"sTitle": "T.P Nacional" },
						{"sTitle": "T.P Regional" },
						{"sTitle": "T.P Urbano" },
						{"sTitle": "Trayecto Especial" }
						
					],
			"sDom": 'T<"clear">t'
				} );	
			} );
			

				var aDataSet2 = <?=$dataSet2?>
			
			$(document).ready(function() {
				$('#dynamic2').html( '<table cellpadding="0" cellspacing="0"  border="0" class="display" id="example2"></table>' );
				$('#example2').dataTable( {
					"aaData": aDataSet2,
					"aoColumns": [
						{ "sTitle": "Numero" },
						{"sTitle": "Valor declarado" },
						{"sTitle": "Destinatario" },
						{"sTitle": "Direccion dest." },
						{"sTitle": "Telefono dest." },
						{"sTitle": "Peso guia" },
						{"sTitle": "Zona" },
						{"sTitle": "Causal Dev." },
						{"sTitle": "Producto" },
						{"sTitle": "Tipo Producto" },
						{"sTitle": "Ciudad Origen" },
						{"sTitle": "Ciudad Destino" }
						
					]
				} );	
			} );
		</script>
	</head>
	<body id="dt_example"> 
		<div id="container">
        <? if ( isset($_GET["mensaje"]) ) {  
		?> 
        
        <div class="mensaje"><?=$_GET["mensaje"]?></div>  
        
        <?
        }
        ?>
			<div class="full_width big">
				Orden de Servicio
			</div>
               <? if ($vacio)
                {  
                ?>
				<div align="center" style="color:#FF0000">No hay datos para mostrar</div>
				<?
                }	
        		?>
        <div id="dynamic"></div>
			<div class="spacer"></div>
	
     <!------------------------------------------------------------------------------------------------------>
                <? if ($vacio2)
                {  
                ?>
				<div align="center" style="color:#FF0000">No se pidieron Adicionales</div>
				<?
                } else
				{
        		?>   
 
<p>&nbsp;</p>
<hr>
  	 <div class="full_width big">
				Cantidad de Tipos de Producto
			</div>
         
        <div id="dynamic3"></div>
			<div class="spacer"></div>
    
		</div>
    	
<p>&nbsp;</p>
<hr>
  	 <div class="full_width big">
				Guias relacionadas
			</div>
         
        <div id="dynamic2"></div>
			<div class="spacer"></div>
            <?
            }
			?>
		</div>
		</div>
	</body>
</html>

