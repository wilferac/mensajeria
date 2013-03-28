<? 
	include("../../clases/clases.php");
	$q = strtolower($_GET["q"]);
	$items=array();
	$operaciones = new operacion();
	
	$qry="SELECT producto.idproducto, producto.nombre_producto FROM producto";

	$res2=$operaciones->consultar($qry);
   if ( mysql_num_rows($res2)>0 )
     while ($filas=mysql_fetch_assoc($res2))
	 $items[$filas["idproducto"]." ".$filas["nombre_producto"]] = $filas["idproducto"]." ".$filas["nombre_producto"];

  foreach ($items as $key=>$value) {
  if ( empty($q) )
  		echo "$key|$value\n";
	elseif (strpos(strtolower($value), $q) !== false) {
		echo "$key|$value\n";
	}
}
?>
