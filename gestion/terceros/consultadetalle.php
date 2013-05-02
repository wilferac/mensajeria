<? 
 include("../../clases/clases.php");
	
	$idtercero = $_GET["id"];

	$tercero = new tercero();
 	$operacion = new operacion();
		
		$con = "idtercero = $idtercero";
   		$res2=$tercero->consultar($con);
	
		$filas=mysql_fetch_assoc($res2);
	    $idtercero = $filas["idtercero"];
	    $documento_tercero = $filas["documento_tercero"];
	 	$nombres_tercero = $filas["nombres_tercero"];
	 	
	
			 
		$dataSetini="[";
	$dataSet="";
	 $dataSet=$dataSet."['$documento_tercero','$nombres_tercero'],";
	$dataSet=substr_replace($dataSet,"];",strlen($dataSet)-1);
	$dataSet=$dataSetini.$dataSet; 


	$ciudad_tercero = new ciudad_tercero();
	$ciudad = new ciudad();
	$departamento = new departamento();

	$dataSetini="[";
	$dataSet2="";
	$cond = "idtercero=$idtercero";
	$res = $ciudad_tercero->consultar($cond);
	
	while ( $filas = mysql_fetch_assoc($res) )
	{
		$idciudad = $filas["idciudad"];
						
		$cond = "SELECT nombre_ciudad, departamento_iddepartamento FROM ciudad WHERE idciudad = $idciudad";
		$resp = $operacion->consultar($cond);
		$fila = mysql_fetch_assoc($resp);	
	
		if (mysql_num_rows($resp)>0 )
		{
			$nombreciudad = $fila["nombre_ciudad"];
			$departamento_iddepartamento = $fila["departamento_iddepartamento"];
			
			$cond = "iddepartamento=$departamento_iddepartamento";	
			
			$resp = $departamento->consultar($cond);
			$fila = mysql_fetch_assoc($resp);
			$nombredepartamento = $fila["nombre_departamento"];
		}
	
	$dataSet2=$dataSet2."['$nombreciudad','$nombredepartamento'],";		
	}
	$dataSet2=substr_replace($dataSet2,"];",strlen($dataSet2)-1);
	$dataSet2=$dataSetini.$dataSet2; 
	
	$vacio=false;
	$vacio2=false;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Detalle de Manifiesto</title>
		<style type="text/css" title="currentStyle">
			@import "../../media/css/demo_page.css";
			@import "../../media/css/demo_table.css";
		</style>
   <script type="text/javascript" charset="utf-8">
	
		</script>
		<script type="text/javascript" language="javascript" src="../../js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../../js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8">
			
			var aDataSet = <?=$dataSet?>
			
			$(document).ready(function() {
				$('#dynamic').html( '<table width=60% cellpadding="0" cellspacing="0"  border="0" class="display" id="example"></table>' );
				$('#example').dataTable( {
					"aaData": aDataSet,
					"aoColumns": [
						{"sTitle": "Documento Aliado" },	
						{"sTitle": "Nombre Aliado" }
					]
				} );	
			} );

				var aDataSet2 = <?=$dataSet2?>
			
			$(document).ready(function() {
				$('#dynamic2').html( '<table width=60% cellpadding="0" cellspacing="0"  border="0" class="display" id="example2"></table>' );
				$('#example2').dataTable( {
					"aaData": aDataSet2,
					"aoColumns": [
						{ "sTitle": "Ciudad" },
						{"sTitle": "Departamento" }
						
					]
				} );	
			} );
		</script>
	</head>
	<body id="dt_example">
		<div id="container">
        <? if ( isset($_GET["mensaje"]) ) { 
		?> 
        
        <div class="mensaje"><?=$_GET["mensaje"]?></div>  
        
        <?
        }
        ?>
			<div class="full_width big">
				Detalle Aliado
			</div>
               <? if ($vacio)
                {  
                ?>
				<div align="center" style="color:#FF0000">No hay datos para mostrar</div>
				<?
                }	
        		?>
        <div id="dynamic"></div>
			<div class="spacer"></div>
	
     <!------------------------------------------------------------------------------------------------------>
                <? if ($vacio2)
                {  
                ?>
				<div align="center" style="color:#FF0000">No se pidieron Adicionales</div>
				<?
                } else
				{
        		?>   
    	
<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
<hr>
  	 <div class="full_width big">
				Ciudades relacionadas
			</div>
         
        <div id="dynamic2"></div>
			<div class="spacer"></div>
            <?
            }
			?>
		</div>
		</div>
	</body>
</html>

