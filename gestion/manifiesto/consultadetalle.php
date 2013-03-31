<? 
 include("../../clases/clases.php");

	$idmanifiesto = $_GET["id"];
 	
	$manifiesto = new manifiesto();	
	$tercero = new tercero();
	$sucursal = new sucursal();	
	$estado = new estado();

	$guia = new guia();
 	$operacion = new operacion();

	$cond = "idmanifiesto=$idmanifiesto";
	$filas = $operacion->consultarmultiple($manifiesto,$cond);		
	
	$idmanifiesto=$filas["idmanifiesto"];
	//$factura_idfactura = ucfirst( $filas["factura_idfactura"] );
	$tercero_idmensajero_recibe =  $filas["tercero_idmensajero_recibe"];
	$numero_manifiesto = $filas["num_manifiesto"] ;
	$tercero_idmensajero_entrega = $filas["tercero_idmensajero_entrega"];
	//$area_manifiesto = ucfirst( $filas["area_manifiesto"] );	
	$sucursal_idsucursal = $filas["sucursal_idsucursal"];
	$plazo_entrega_manifiesto = $filas["plazo_entrega_manifiesto"];
	//$plazo_asignacion_orden = ucfirst( $filas["plazo_asignacion_orden"] );
	
		$cond = "idtercero = $tercero_idmensajero_recibe";
		$res = $tercero -> consultar($cond);
		$fila2 = mysql_fetch_assoc($res);
		$nombres_tercero = $fila2["nombres_tercero"];
	 	$apellidos_tercero = $fila2["apellidos_tercero"];
		$documento_tercero = $fila2["documento_tercero"];

		$cond = "idtercero = $tercero_idmensajero_entrega";
		$res = $tercero -> consultar($cond);
		$fila2 = mysql_fetch_assoc($res);
		$nombres_tercero2 = $fila2["nombres_tercero"];
	 	$apellidos_tercero2 = $fila2["apellidos_tercero"];
		$documento_tercero2 = $fila2["documento_tercero"];
		
		$cond = "idsucursal=$sucursal_idsucursal";
		$res = $sucursal -> consultar($cond);
		$fila2 = mysql_fetch_assoc($res);	
		$nombre_sucursal = $fila2["nombre_sucursal"];
	
	$linkcargar=""; //<a href=\'../guia/cargar.php?nombre=$nombres&id=$idmanifiesto\'><img src=\'../../imagenes/cargar.jpg\' /></a>";
		// hacer manifiesto count en guias con igual idmanifiesto	 
	 		$wrapini = $wrapfin = "";
		$dataSetini="[";
	$dataSet="";
	 $dataSet=$dataSet."['$wrapini$numero_manifiesto$wrapfin','$wrapini$nombres_tercero $apellidos_tercero$wrapfin','$wrapini$nombres_tercero2 $apellidos_tercero2$wrapfin','$wrapini$nombre_sucursal$wrapfin','$wrapini$plazo_entrega_manifiesto$wrapfin'],";
	$dataSet=substr_replace($dataSet,"];",strlen($dataSet)-1);
	$dataSet=$dataSetini.$dataSet; 


	$zona = new zona();
	$causal_devolucion = new causal_devolucion();
	$producto = new producto();
	$ciudad = new ciudad();
 	$tipo_producto = new tipo_producto();


	$dataSetini="[";
	$dataSet2="";
	$dataSet3="";
	$cond = "manifiesto_idmanifiesto=$idmanifiesto";
	$res = $guia->consultar($cond);
			$creacion = $enreparto = $entregado = $devuelta = $totalguias = 0;

	while ( $filas = mysql_fetch_assoc($res) )
	{
		$id_guia = $filas["idguia"];		
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

		

		
		$cond = "SELECT estado_idestado,fecha_movimiento,hora_movimiento FROM movimiento WHERE movimiento.guia_idguia=$id_guia ORDER BY FECHA_MOVIMIENTO, hora_movimiento DESC limit 1";
		$resp = $operacion->consultar($cond);
		$fila = mysql_fetch_assoc($resp);	
	
		if (mysql_num_rows($resp)>0 )
		{
			$estado_idestado = $fila["estado_idestado"];
			$cond = "idestado=$estado_idestado";	
			$resp = $estado->consultar($cond);
			$fila = mysql_fetch_assoc($resp);
			$nombreestado = $fila["nombre_estado"];
		}
		else
			$nombreestado ="";		
		
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

		
		if ( strtolower($nombreestado) == 'creacion' )
		{
		$creacion++;
		}
		elseif( strtolower($nombreestado) == 'en reparto' )
		{
		
		$enreparto++;
		}
		 elseif( strtolower($nombreestado) == 'entregado' )
		{
		
		$entregado++;
		}	

		elseif( strtolower($nombreestado) == 'devuelta' )
		{
		
		$devuelta++;
		}	
		
		$totalguias++;
	
	

	$dataSet2=$dataSet2."['$numero_guia','$nombreestado','$valor_declarado_guia','$nombre_destinatario_guia','$direccion_destinatario_guia',
'$telefono_destinatario_guia','$peso_guia','$nombrezona','$nombre_causal_devolucion','$nombre_producto','$nombre_tipo_producto ','$nombre_ciudad_origen','$nombre_ciudad_destino'],";		

	
	}
	$dataSet2=substr_replace($dataSet2,"];",strlen($dataSet2)-1);
	$dataSet2=$dataSetini.$dataSet2; 
	
$dataSet3=$dataSet3."['$creacion','$enreparto','$entregado','$devuelta',$totalguias],";		

		$dataSet3=substr_replace($dataSet3,"];",strlen($dataSet3)-1);
	$dataSet3=$dataSetini.$dataSet3; 
	
	$vacio=false;
	$vacio2=false;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Detalle de Manifiesto</title>
		<style type="text/css" title="currentStyle">
			@import "../../media/css/demo_page.css";
			@import "../../media/css/demo_table.css";
		</style>
   <script type="text/javascript" charset="utf-8">
	
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
						{"sTitle": "Num. Manifiesto" },	
						{"sTitle": "Mensajero Recibe" },
						{"sTitle": "Mensajero Entrega" },
						{"sTitle": "Nombre Sucursal" },				
						{"sTitle": "Plazo Entrega" },
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
						{ "sTitle": "Creadas" },
						{ "sTitle": "En Reparto" },
						{"sTitle": "Devueltas" },
						{"sTitle": "Entregas" },
						{"sTitle": "TOTAL" }
						
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
						{"sTitle": "Estado" },
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
				Manifiesto
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
     
     			<div class="full_width big">
				Estados
			</div>
               <? if ($vacio)
                {  
                ?>
				<div align="center" style="color:#FF0000">No hay datos para mostrar</div>
				<?
                }	
        		?>
        <div id="dynamic3"></div>
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
    	
<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
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

