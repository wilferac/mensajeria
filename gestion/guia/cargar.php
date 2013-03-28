<?
include ("../../param/param.php");
include ("../../clases/clases.php");


if (isset($_POST["cargar"]))
{
	 
 //print_r ($_POST);
	
	if ( isset($_GET["id"]) )
		$idos = $_GET["id"];
	elseif ( isset($_POST["idos"]) )
		$idos = $_POST["idos"];
	
	$rguia =  $ros = $rdpe = false;

	$nombreproducto = "Masivo"; // ojo ver bien la procedencia de este campo de producto
	$idciudadorigen = $_SESSION['datosinicio']['ciudad_idciudad'];			
	$iddepartamentoorigen = $_SESSION['datosinicio']['departamento_iddepartamento'];		
	$nombreciudadorigen = $_SESSION['datosinicio']['nombre_ciudad']; 	
	$idpaisdorigen = $_SESSION['datosinicio']['pais_ciudad'];
	
	$os = new orden_servicio();
	$cond = "idorden_servicio=$idos";
	$r = $os->consultar($cond);
	$fil = mysql_fetch_assoc($r);
	$idremitente = $fil["tercero_idcliente"];

	$uploaddir = "../../tmp/"; 
	$uploadfile = $uploaddir . basename($_FILES['fileguia']['name']); 
	$error = $_FILES['fileguia']['error']; 
	$subido = false; 

if($error==UPLOAD_ERR_OK) 
{ 
 	//print_r ($_FILES);
	$subido = copy($_FILES['fileguia']['tmp_name'], $uploadfile); 
	if($subido) 
	{ 
	echo "";  // codigo viejo, preferi no mejorar esta parte. 
	} 
	else 
	{ 
		echo "Se ha producido un error con el archivo: ".$error; 
		exit();
	}
	$handle = fopen($uploadfile, 'r');
	$contenido = fread($handle, filesize($uploadfile));
	fclose($handle);
	
	$lineas = explode("\n",$contenido);
	$camposguia = array ();
		$i = 0;
		foreach ($lineas as $linea) 
		{
			list($numeroguia,$algo,$ciudaddestino,$municipiodestino,$destinatario,$telefono,$zona,$direccion,$observacion) = explode (";",$linea); 
			//echo $ciudaddestino;
			$camposguia[$i]["numeroguia"] = $numeroguia;
			$camposguia[$i]["algo"] = $algo;
			$camposguia[$i]["ciudaddestino"] = $ciudaddestino;
			$camposguia[$i]["municipiodestino"] = $municipiodestino;
			$camposguia[$i]["destinatario"] = $destinatario;
			$camposguia[$i]["telefono"] = $telefono;
			$camposguia[$i]["zona"] = $zona;
			$camposguia[$i]["direccion"] = $direccion;
			$camposguia[$i]["observacion"] = $observacion;
			
			$i++;

		}
/*************************************************************************
Insercion en tablas correspondientes: Manejo de transaccionalidad
*************************************************************************/
	$guia = new guia();
	$producto = new producto();	
	$detalle_producto_especial = new detalle_producto_especial();
	$operaciones = new operacion();
		

	$conex = new conexion();
	$qtrans = "SET AUTOCOMMIT=0;";
	$sac = $conex -> ejecutar($qtrans); 
	$qtrans = "BEGIN;";
	$sac = $conex->ejecutar($qtrans);	
	
	
	
	$j = 0;
	$datosciudad = array ();
	$cantidadguias = count ($camposguia);
	while ($camposguia[$j])
	{	
		$idTipoProducto = $operaciones->calcularTipoProducto($idpaisdorigen,$iddepartamentoorigen,$idciudadorigen,$camposguia[$j]["ciudaddestino"]);		
		$datosciudad = $operaciones->obtenerDatosCiudad($camposguia[$j]["ciudaddestino"]);
		

		//echo $camposguia[$j]["ciudaddestino"]."--".$idTipoProducto."<br>";
		
		
		//este debe ser una consulta en BD
	/*	$producto->tipo_producto_idtipo_producto = $idTipoProducto;
		$producto->nombre_producto = $nombreproducto;
		$producto->porcentaje_seguro_producto = NULL;
		$rprod = $producto->agregar();*/	
		//este debe ser una consulta en BD
		
		$cond = "tipo_producto_idtipo_producto=$idTipoProducto and	nombre_producto='$nombreproducto'";
		$res = $producto->consultar($cond);
		$fila = mysql_fetch_assoc($res);
		$idproducto = $fila['idproducto'];
		
		
		//$idproducto = $producto->idproducto;
		
		$guia->numero_guia = $camposguia[$j]['numeroguia'];
		$guia->orden_servicio_idorden_servicio = $idos;			
		$guia->zona_idzona = 1;  //ojo cable updatear a la zona correpondiente
		$guia->causal_devolucion_idcausal_devolucion = 1; //1 para cuando no tiene causal de dev
		$guia->manifiesto_idmanifiesto = NULL;	
		$guia->producto_idproducto = $idproducto;
		$guia->ciudad_iddestino = $datosciudad["idciudad"];
		$guia->valor_declarado_guia = NULL;
		$guia->nombre_destinatario_guia = $camposguia[$j]["destinatario"];
		$guia->direccion_destinatario_guia = $camposguia[$j]["direccion"];
		$guia->telefono_destinatario_guia = $camposguia[$j]["telefono"];

		$guia->peso_guia = "";
    	$guia->ciudad_idorigen = $idciudadorigen;
    	$guia->tercero_idremitente = $idremitente;
    	$guia->tercero_iddestinatario = 1; // ojo deberia venir en el archivo de las guias
		$rguia = $guia->agregar();
		 
		$detalle_producto_especial->producto_idproducto = $idproducto;
		$detalle_producto_especial->orden_servicio_idorden_servicio = $idos;
		$rdpe = $detalle_producto_especial->agregar();
				 
		
	$j++;
	if ($j==$cantidadguias)
			break; 
	}
 		$stringset = "unidades=$cantidadguias";
		$ros = $os->modificar2($stringset, $idos);

}


if ($rguia===true && $ros===true && $rdpe===true)
{
	$qtrans = "COMMIT";
	$sac = $conex -> ejecutar($qtrans); 
	//echo "guias agregadas con exito<br>$contenido";

?>	

<script language="javascript" type="text/javascript">
var mensaje="Registro Exitoso";
window.location.href='../ordendeservicio/consulta.php?mensaje='+mensaje;
</script>
	
<?
}
else
{
	//echo "Error al agregar guias";
?>
<script language="javascript" type="text/javascript">
var mensaje="Registro NO Exitoso";
 window.location.href='../ordendeservicio/consulta.php?mensaje='+mensaje;
</script>
<?
}

return; 
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Carga de Guía</title>
		<link rel="stylesheet" type="text/css" media="screen" href="../../media/css/screen.css" />
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
				$("#idos").autocomplete("searchos.php", {
				minChars: 0, max:200, width: 155});
			});
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
		<div id="container">
<?
	$labelidos = "";
	$campoidos = "";
	$datos = false;

	if ( isset($_GET["id"]) === false )
	{	$labelidos = "<label for='idos'>Ingrese el Numero de Orden de Servicio: </label>";
		$campoidos = "<input type='text' id='idos' name='idos' size='20'>";
	}
	else 
	{
		
		$id = $_GET["id"];
		$cons = "idorden_servicio = $id";
		$orden_servicio = new orden_servicio();	
		$res = $orden_servicio->consultar($cons);
		if ( mysql_num_rows($res)>0 )
		{
			$fila = mysql_fetch_array($res);
			$idtercero = $fila["tercero_idcliente"]; 

			$tercero = new tercero();
			$cons = "idtercero = $idtercero";
			$res = $tercero->consultar($cons);
			  if ( mysql_num_rows($res)>0 )
			  {
				$fila = mysql_fetch_array($res);
				$nombres_tercero = $fila["nombres_tercero"];
				$apellidos_tercero = $fila["apellidos_tercero"];
				$documento_tercero = $fila["documento_tercero"];
				$datos = true;		
			  }
		}	
		
	}
?>	
			<div class="full_width big">
				<p class="navegacion"><a href="../redireccionador.php"></a><a href="../gestiondelsistema.php"></a></p>Carga de Guía
			</div>

		<form name='cargaguia' method='POST' action="" enctype="multipart/form-data" >
	<table borde='0' align='center'>
	<tr>
		<td>
			<?=$labelidos.$campoidos."<br>"?>
		</td>
	</tr>
<?
	if ( $datos == true )
	{
	echo "<tr>
		<td>
			Datos cliente: ".strtoupper($nombres_tercero)." ".strtoupper($apellidos_tercero)." ".$documento_tercero."
		</td>
	</tr>";
	}
?>

	<tr>
		<td>
		 <label for='fileguia'>Seleccione el archivo: </label>
<input type='file' id='fileguia' name='fileguia' ><br>
		</td>
	</tr>
	<tr>
		<td align='center'>
			<input type=submit value='Cargar' id='cargar' name='cargar' onClick="javascript: if (document.cargaguia.fileguia.value == '') return false;">
		</td>
	</tr>
	
		</form>
		</div>
	</body>
</html>

