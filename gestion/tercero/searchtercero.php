<? 
	include("../../clases/clases.php");
	$q = strtolower($_GET["q"]);
	$items=array();
	$tipo_tercero = 1;  // $tipo_tercero 1 - Clientes
	$operaciones = new operacion();
	
	$qry="SELECT tercero_tipo.tipo_tercero_idtipo_tercero,tercero.idtercero,tercero.nombres_tercero, tercero.apellidos_tercero,documento_tercero FROM tercero_tipo, tercero WHERE tercero_tipo.tipo_tercero_idtipo_tercero=$tipo_tercero AND tercero_tipo.tercero_idtercero=tercero.idtercero";

	$res2=$operaciones->consultar($qry);
   if ( mysql_num_rows($res2)>0 )
     while ($filas=mysql_fetch_assoc($res2))
	 $items[$filas["idtercero"]." - ".$filas["nombres_tercero"]." ".$filas["apellidos_tercero"]." ".$filas["documento_tercero"]] = $filas["nombres_tercero"]." ".$filas["apellidos_tercero"]." ".$filas["documento_tercero"];

  foreach ($items as $key=>$value) {
  if ( empty($q) )
  		echo "$key|$value\n";
	elseif (strpos(strtolower($value), $q) !== false) {
		echo "$key|$value\n";
	}
}
?>
