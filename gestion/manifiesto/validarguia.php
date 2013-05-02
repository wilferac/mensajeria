<?
	#Permite validar que la guia ingresada:
	 
	# Se encuentre registrada 
	# Sin Manifiesto
	# Y que pertenezca a la ciudad destino seleccionada

include ("../../param/param.php");
include ("../../clases/clases.php");

    $numguia = $_REQUEST["numguia"];
    $idciudad = $_REQUEST["idciudad"];
   	
	$idciudad = substr($idciudad,0,strpos($idciudad," "));
	
	$guia = new  guia ();
	$cond="numero_guia = $numguia";	
	$res = $guia->consultar($cond);
		if (mysql_num_rows($res)>0) 
		{
		  $data = mysql_fetch_assoc($res);
		  $manifiesto_idmanifiesto = $data['manifiesto_idmanifiesto'];
		  $ciudad_iddestino = $data['ciudad_iddestino'];
			
				if ($manifiesto_idmanifiesto != 0)
				{
					echo "1 $manifiesto_idmanifiesto";//"El numero de guia $numguia Ya tiene Manifiesto";
				}
				elseif ($ciudad_iddestino != $idciudad)
				{  
				
				$operacion = new operacion();
				
				$qry = "SELECT  departamento_iddepartamento FROM  ciudad WHERE  idciudad = $idciudad";
				$qry2 = "SELECT  departamento_iddepartamento FROM  ciudad WHERE  idciudad = $ciudad_iddestino";
				
				$res = $operacion->consultar($qry);
				$res2 = $operacion->consultar($qry2);
				
				$data = mysql_fetch_assoc($res);
				$data2 = mysql_fetch_assoc($res2);
				
				$departamento_iddepartamento = $data['departamento_iddepartamento'];
				$departamento_iddepartamento2 = $data2['departamento_iddepartamento'];
				
							
				if ($departamento_iddepartamento != $departamento_iddepartamento2)
							echo "2";  //"El numero de guia $numguia está asignado a otra ciudad destino";
				}
		
		}
		else
		{
			echo "3"; // "El número de guia $numguia no existe. Presione Borrar";
		}
	


?>