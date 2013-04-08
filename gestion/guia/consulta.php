<? 
 include("../../clases/clases.php");

	//$idmanifiesto = $_GET["id"];
 	
	$manifiesto = new manifiesto();	
	$tercero = new tercero();
	$sucursal = new sucursal();	
	$estado = new estado();

	$guia = new guia();
 	$operacion = new operacion();

	//$cond = "idmanifiesto=$idmanifiesto";
	//$filas = $operacion->consultarmultiple($manifiesto,$cond);		

	$zona = new zona();
	$causal_devolucion = new causal_devolucion();
	$producto = new producto();
	$ciudad = new ciudad();
 	$tipo_producto = new tipo_producto();


	$dataSetini="[";
	$dataSet2="";
	//$cond = "manifiesto_idmanifiesto=$idmanifiesto";
	$res = $guia->consultar();
	
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
	
		$estado_idestado = $fila["estado_idestado"];
		$cond = "idestado=$estado_idestado";	
		$resp = $estado->consultar($cond);
		$fila = @mysql_fetch_assoc($resp);
		$nombreestado = $fila["nombre_estado"];

		
		
		$cond = "idzona=$zona_idzona";
		$fila = $operacion->consultarmultiple($zona,$cond);
		$nombrezona = $fila["nombre_zona"];	

		$cond = "idcausal_devolucion=$causal_devolucion_idcausal_devolucion";
		$fila = $operacion->consultarmultiple($causal_devolucion,$cond);
		$nombre_causal_devolucion = $fila["nombre_causal_devolucion"];
		
		if ( strtolower($nombre_causal_devolucion) == "devuelto" )	
			$nombre_causal_devolucion = "<font color=red>".$nombre_causal_devolucion."</font>";
		elseif (strtolower($nombre_causal_devolucion) == "entregado" )
			$nombre_causal_devolucion = "<font color=blue>".$nombre_causal_devolucion."</font>";

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

		

$dataSet2=$dataSet2."['$numero_guia','$nombreestado','$valor_declarado_guia','$nombre_destinatario_guia','$direccion_destinatario_guia',
'$telefono_destinatario_guia','$peso_guia','$nombrezona','$nombre_causal_devolucion','$nombre_producto','$nombre_tipo_producto ','$nombre_ciudad_origen','$nombre_ciudad_destino'],";		
	}
	$dataSet2=substr_replace($dataSet2,"];",strlen($dataSet2)-1);
	$dataSet2=$dataSetini.$dataSet2; 
	
	$vacio=false;
	$vacio2=false;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Guias</title>
		<style type="text/css" title="currentStyle">
			@import "../../media/css/demo_page.css";
			@import "../../media/css/demo_table.css";
			@import "media/css/TableTools.css";
		</style>
   <script type="text/javascript" charset="utf-8">
	
		</script>
		<script type="text/javascript" language="javascript" src="../../js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../../js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8" src="media/js/ZeroClipboard.js"></script>
		<script type="text/javascript" charset="utf-8" src="media/js/TableTools.js"></script>
		<script type="text/javascript" charset="utf-8">
			
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
						
					],
			"sDom": 'T<"clear">lfrtip',	"oTableTools": {"aButtons": ["copy","xls",{"sExtends": "pdf",
			"sPdfOrientation": "landscape","sPdfMessage": "Reporte"},"print"]}});
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

	
                <? if ($vacio2)
                {  
                ?>
				<div align="center" style="color:#FF0000">No hay guias</div>
				<?
                } else
				{
        		?>   
    	
<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
<hr>
  	 <div class="full_width big">
				Guias
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

