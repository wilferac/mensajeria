<? 
 include("../clases/clases.php");
$q = strtolower($_GET["q"]);
//if (!$q) return;
$items=array();
 $productos=new productos();
 $i=0;
 $qry="SELECT productos.id AS PID,productos.descripcion,productos.unidaddemedida_id,unidaddemedidas.id, unidaddemedidas.nombre, unidaddemedidas.abreviacion FROM productos, unidaddemedidas where productos.unidaddemedida_id=unidaddemedidas.id ORDER BY productos.descripcion ASC";
$res2=$productos->consultar("","","",$qry);
$text=" almacenado en ";
   if ( mysql_num_rows($res2)>0 )
   {
     while ($filas=mysql_fetch_assoc($res2))
	 { 
	 $items[$filas["PID"]."- ".$filas["descripcion"].$text.strtoupper($filas["abreviacion"])]=ucfirst($filas["descripcion"]).$text.strtoupper($filas["abreviacion"]);
	 $i++;
	 }
  }
  //print_r($items);
  foreach ($items as $key=>$value) {
  if ( empty($q) )
  		echo "$key|$value\n";
	elseif (strpos(strtolower($value), $q) !== false) {
		echo "$key|$value\n";
	}
}
?>
