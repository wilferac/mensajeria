<? 
 //include("../clases/clases.php");
include ("guis.php");
 $sitios=new sitios();

 
 $nombres='sitios';
 
 $vacio=true;
 	$dataSet="";
   $res2=$sitios->consultar();
   if ( mysql_num_rows($res2)>0 )
   {
   $dataSetini="[";
   $dataSet="";
     while ($filas=mysql_fetch_assoc($res2))
	 { 
	 $id=$filas["id"];
	 $descripcion=ucfirst( $filas["nombre"] );
	 
	$linkeliminar="<a href=\'delete.php?nombre=$nombres&id=$id\' onClick=\'return(confirmar())\'><img src=\'../imagenes/borrar.jpeg\' /></a>";
	 $linkmodificar="<a href=\'edit.php?nombre=$nombres&id=$id\'><img src=\'../imagenes/modificar.jpeg\' /></a>";
	 
	 $dataSet=$dataSet."['$id','$descripcion','$linkeliminar','$linkmodificar'],";
    }
	$dataSet=substr_replace($dataSet,"];",strlen($dataSet)-1);
	$dataSet=$dataSetini.$dataSet;
  //echo $dataSet;
  $vacio=false;
  }
  
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Sitios</title>
		<style type="text/css" title="currentStyle">
			@import "../media/css/demo_page.css";
			@import "../media/css/demo_table.css";
		</style>
        <script language="javascript" type="text/javascript">
		function confirmar()
		{
		return confirm('¿Seguro(a) de ELIMINAR?');
		}
		</script>
		<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8">
			/* Data set - can contain whatever information you want */
		
			var aDataSet = <?=$dataSet?>
			
			$(document).ready(function() {
				$('#dynamic').html( '<table cellpadding="0" cellspacing="0"  border="0" class="display" id="example"></table>' );
				$('#example').dataTable( {
					"aaData": aDataSet,
					"aoColumns": [
						{ "sTitle": "id" },
						{"sTitle": "Nombre" },
						{"sTitle": "Eliminar" },	
						{"sTitle": "Modificar" }	
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
				<p class="navegacion"><a href="../redireccionador.php">Página principal</a>&gt;<a href="../gestiondelsistema.php">Gestión del sistema</a></p>Sitios
			</div>
             <p>&nbsp;</p>
             <table class="display"><tr><td>
             <a href="add.php">Agregar Datos</a>
             </td></tr></table>
               <? if ($vacio)
                {  
                ?>
				<div align="center" style="color:#FF0000">No hay datos para mostrar</div>
				<?
                }	
        		?>
        <div id="dynamic"></div>
			<div class="spacer"></div>
		</div>
	</body>
</html>
