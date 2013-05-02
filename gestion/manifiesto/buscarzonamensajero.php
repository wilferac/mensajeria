<?
	#Busca el id, nombre y descripcion de una zona segune el id de mensajero dado
include ("../../param/param.php");
include ("../../clases/clases.php");

    $idmensajero = $_REQUEST["idmensajero"];
	$tipo = $_REQUEST["tipo"];
	
	$flagSinZona = false;
    $operacion = new  operacion ();
	

	$sql = "select zona.idzona,	zona.ciudad_idciudad, zona.nombre_zona, zona.descripcion, zona.tarifa from tercero, zona WHERE tercero.idtercero = $idmensajero AND tercero.idzona = zona.idzona";
    	$res = $operacion->consultar($sql);
		if (mysql_num_rows($res)>0) 
		{
		  $data = mysql_fetch_assoc($res);
		  
		  $idzona = $data['idzona'];	
		  $ciudad_idciudad = $data['ciudad_idciudad'];	
		  $nombre_zona = $data['nombre_zona'];
		  $descripcion = $data['descripcion'];
		  $tarifa = $data['tarifa'];
		}
		else
			$flagSinZona = true;
	if ($tipo == 'propio')
	{	
		if (!$flagSinZona)
		{
			$valorZona = $idzona.'-'.$nombre_zona.':   '.$descripcion; 
			echo "$valorZona";
		}
		else
			echo "Sin Zona";
	}
	elseif ($tipo == 'destajo')
	{
				if (!$flagSinZona)
		{
		 $valorZona = $tarifa.'&'.$idzona.'-'.$nombre_zona.':   '.$descripcion; 
		echo "$valorZona";
		}
		else
			echo "Sin Zona";
			
	}


?>
