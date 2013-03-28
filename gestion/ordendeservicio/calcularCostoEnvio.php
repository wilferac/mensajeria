<?
  
function  obtenerValorEnvio($nombreproducto,$idtipoproducto,$idcliente,$idsucursal)
{
  	$operacion = new operacion ();
	
	$sql = "SELECT idproducto FROM producto WHERE tipo_producto_idtipo_producto = $idtipoproducto";
	
 	$res = $operacion -> consultar($sql);
	$fila = mysql_fetch_assoc($res);
	$id_producto = $fila['idproducto'];
	
	$sql = "select valor_envio_tarifa from tarifa where producto_idproducto = '$id_producto' AND tercero_idcliente = '$idcliente'    AND	sucursal_idsucursal = '$idsucursal'";
	$res = $operacion -> consultar($sql);
	$fila = mysql_fetch_assoc($res);
	
	
	$valorenviotarifa = $fila['valor_envio_tarifa'];
	return $valorenviotarifa;
}
///////////////////////////////////////////////
function obtenerPorcSeguro ($nombreproducto,$idtipoproducto)
{
		$operacion = new operacion ();
	
	$sql = "SELECT porcentaje_seguro_producto FROM producto WHERE tipo_producto_idtipo_producto = 5
	AND LOWER( nombre_producto) = LOWER('$nombreproducto')";
	
 	$res = $operacion -> consultar($sql);
	$fila = mysql_fetch_assoc($res);
	$porcentaje_seguro_producto =  $fila['porcentaje_seguro_producto']/100;
  //echo $porcentaje_seguro_producto;
 return $porcentaje_seguro_producto;
}

	include ("../../clases/clases.php");
	

	$valordeclarado = $_REQUEST["valordeclarado"];
	$valorempaque = $_REQUEST["valorempaque"];
	
	$nombreproducto = $_REQUEST["nombreproducto"];
	$idtipoproducto = $_REQUEST["idtipoproducto"];
	
	$idcliente = $_REQUEST["idcliente"];
	$idsucursal = $_REQUEST["idsucursal"];
	
	$valortotal = 0;
 	//echo "$valordeclarado $valorempaque $nombreproducto  $idtipoproducto  $idcliente  $idsucursal";
 	$valortotal = obtenerValorEnvio($nombreproducto,$idtipoproducto,$idcliente,$idsucursal)+($valordeclarado*obtenerPorcSeguro($nombreproducto,$idtipoproducto))+$valorempaque;
	
	echo "<script>
			document.getElementById('valortotal').value = '$ '+'$valortotal';
			document.getElementById('valortotal2').value = '$valortotal';
		</script>";
	
?>