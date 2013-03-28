<? 
 include("../../clases/clases.php");
$q = strtolower($_GET["q"]);
//if (!$q) return;
$items=array();
 $orden_servicio=new orden_servicio();
 //$qry="SELECT orden_servicio.idorden_servicio AS ID";
$qry="";
$res2=$orden_servicio->consultar();
   if ( mysql_num_rows($res2)>0 )
   {
     while ($filas=mysql_fetch_assoc($res2))
	 $items[$filas["idorden_servicio"]] = $filas["idorden_servicio"];
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
