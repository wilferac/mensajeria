<?
	#Consulta los aliados asociados al la ciudad y los filtra por departamento
	/*
include ("../../param/param.php");
include ("../../clases/clases.php");

    $guia = $_REQUEST["guia"];
    
	$operacion = new  operacion ();
		//__________________________________________ ALIADOS __________________________________________________
	
	//update
	$sql = "UPDATE  guia SET `manifiesto_idmanifiesto =  '0' WHERE  `numero_guia = $guia";
    	$res = $operacion->consultar($sql);
		if (mysql_num_rows($res)>0) 
		{
		  $data2 = mysql_fetch_assoc($res);
		  $nombre_departamento = $data2['nombre_departamento'];	
		  $departamento_iddepartamento = $data2['departamento_iddepartamento'];	
		}

.....

$stringset = "manifiesto_idmanifiesto=".$manifiestos->idmanifiesto;	
												
						$rmodiguia = $guias->modificar2($stringset,$valor);	
						*/
?>