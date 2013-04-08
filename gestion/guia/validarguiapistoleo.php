<?
	#Permite validar que la guia ingresada:
	 
	# Se encuentre registrada 
	# Sin Manifiesto


include ("../../param/param.php");
include ("../../clases/clases.php");

    $numguia = $_REQUEST["numguia"];
	
	$guia = new  guia ();
	$cond="numero_guia = $numguia";	
	$res = $guia->consultar($cond);
		if (mysql_num_rows($res)>0) 
		{
		  $data = mysql_fetch_assoc($res);
		  $manifiesto_idmanifiesto = $data['manifiesto_idmanifiesto'];
			
				if ($manifiesto_idmanifiesto != 0)
					echo "1 $manifiesto_idmanifiesto";//"El numero de guia $numguia Ya tiene Manifiesto";
				else
					echo "2";	
		}
		else
		{
			echo "3"; // "El número de guia $numguia no existe. Presione Borrar";
		}
	


?>