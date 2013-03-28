<?
	#Consulta los aliados asociados al la ciudad y los filtra por departamento
include ("../../param/param.php");
include ("../../clases/clases.php");

    $ciudadstr = $_REQUEST["ciudadstr"];
    
   	$idciudad = substr($ciudadstr,0,strpos($ciudadstr," "));
	
	$operacion = new  operacion ();
		//__________________________________________ ALIADOS __________________________________________________
	
	$sql = "select * from ciudad, departamento
WHERE
ciudad.idciudad = $idciudad
AND departamento.iddepartamento = ciudad.departamento_iddepartamento";
    	$res = $operacion->consultar($sql);
		if (mysql_num_rows($res)>0) 
		{
		  $data2 = mysql_fetch_assoc($res);
		  $nombre_departamento = $data2['nombre_departamento'];	
		  $departamento_iddepartamento = $data2['departamento_iddepartamento'];	
		}

$sql = "Select * from ciudad, ciudad_tercero, tercero
Where
ciudad.idciudad = ciudad_tercero.idciudad
AND ciudad.departamento_iddepartamento = $departamento_iddepartamento
AND ciudad_tercero.idtercero = tercero.idtercero
AND lower( tercero.estado ) =  'activo'";
	
		$res = $operacion->consultar($sql);
		if (mysql_num_rows($res)>0) 
		{
		echo "<br><b>ALIADOS</b><br><hr size=6 color='#999999'>";
		  while ( $data = mysql_fetch_assoc($res) )
		  	{	
		  $nombres_tercero = $data['nombres_tercero'];
		  $direccion_tercero = $data['direccion_tercero'];
		  $telefono_tercero = $data['telefono_tercero'];
		  $departamentovecino = $data['departamentovecino'];
		  $departamento_iddepartamento = $data['departamento_iddepartamento'];
		  $idtercero = $data['idtercero'];
		  
		  echo "<input name='aliadossucursales' id='aliadossucursales' type='radio' value='aliado-$idtercero' ><b>".strtoupper($nombres_tercero)."</b> <u>$nombre_departamento</u> Tel: $telefono_tercero<br><hr>";
		  
			}
		
		}
		//__________________________________________SUCURSALES __________________________________________________
		$sql = "SELECT * FROM  sucursal Where ciudad_idciudad = $idciudad and Activa = 1";	
			$res = $operacion->consultar($sql);
		if (mysql_num_rows($res)>0) 
		{
		echo "<br><br><b>SUCURSALES</b><br><hr size=6  color='#999999'>";
		  while ( $data = mysql_fetch_assoc($res) )
		  	{		
		  $codigo_sucursal = $data['codigo_sucursal'];				  
		  $nombre_sucursal = $data['nombre_sucursal'];
		  $telefono_sucursal = $data['telefono_sucursal'];
		  $idsucursal = $data['idsucursal'];
 
		  echo "<input name='aliadossucursales' id='aliadossucursales' type='radio' value='sucursal-$idsucursal' checked><b>".strtoupper($nombre_sucursal)."</b> Código: <u>$codigo_sucursal</u> Tel: $telefono_sucursal<br><hr>";
		  
			}
		
		}
		

?>