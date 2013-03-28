<?php

/*
 * este archivo va a encargarse del manejo de la parte de unitario.
 * 
 */
?>
<? 
 include("../../clases/clases.php");

	$producto = new producto();
 	$tipo_producto = new tipo_producto(); 
 
 $nombres='producto';
 
 $vacio=true;

   $res2=$producto->consultar();
   if ( mysql_num_rows($res2)>0 )
   {
   $dataSetini="[";
   $dataSet="";


     while ($filas=mysql_fetch_assoc($res2))
	 { 
	 $idproducto = $filas["idproducto"];
	 $codigo = $filas["codigo"];
	 $tipo_producto_idtipo_producto = $filas["tipo_producto_idtipo_producto"];
	 $nombre_producto = $filas["nombre_producto"];
	 $porcentaje_seguro_producto = $filas["porcentaje_seguro_producto"];


	
		$cond = "idtipo_producto=$tipo_producto_idtipo_producto";
		$res = $tipo_producto -> consultar($cond);
		$fila2 = mysql_fetch_assoc($res);	
		$nombre_tipo_producto = $fila2["nombre_tipo_producto"];
		
	$linkeliminar= "<a href=\'delete.php?nombre=$nombres&documento=$idproducto\' onClick=\'return(confirmar())\'><img src=\'../../imagenes/borrar.jpeg\' /></a>";
	 $linkmodificar= "<a href=\'edit.php?nombre=$nombres&documento=$idproducto\'><img src=\'../../imagenes/modificar.jpeg\' /></a>";
$dataSet=$dataSet."['$codigo','$nombre_producto','$nombre_tipo_producto','$porcentaje_seguro_producto','$linkeliminar'],";
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
		<title>Productos</title>
		<style type="text/css" title="currentStyle">
			@import "../../media/css/demo_page.css";
			@import "../../media/css/demo_table.css";
			@import "../../media/media/css/TableTools.css";
		</style>
        <script language="javascript" type="text/javascript">
		function confirmar()
		{
		return confirm('¿Seguro(a) de Eliminar?');
		}
		</script>
		<script type="text/javascript" language="javascript" src="../../js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../../js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8" src="../../media/media/js/ZeroClipboard.js"></script>
		<script type="text/javascript" charset="utf-8" src="../../media/media/js/TableTools.js"></script>
		<script type="text/javascript" charset="utf-8">
			/* Data set - can contain whatever information you want */
	
			var aDataSet = <?=$dataSet?>
			$(document).ready(function() {
				$('#dynamic').html( '<table cellpadding="0" cellspacing="0"  border="0" class="display" id="example"></table>' );
				$('#example').dataTable( {
					"aaData": aDataSet,
					"aoColumns": [
						{"sTitle": "Codigo" },
						{"sTitle": "Nombre Producto" },
						{"sTitle": "Tipo producto" },
						{"sTitle": "Porcentaje seguro" },		
						{"sTitle": "Eliminar" }],
			"sDom": 'T<"clear">lfrtip',	"oTableTools": {"aButtons": ["copy","xls",{"sExtends": "pdf",
			"sPdfOrientation": "landscape","sPdfMessage": "Reporte"},"print"]}
				} );	
			} );
		</script>
        
  <script language="javascript">
  
 parent.frames[0].document.getElementById("a1").innerHTML = "";
 parent.frames[0].document.getElementById("a2").innerHTML = "";
 parent.frames[0].document.getElementById("a3").innerHTML = "";
 
 parent.frames[0].document.getElementById("s1").style.visibility = "hidden";
 parent.frames[0].document.getElementById("s2").style.visibility = "hidden";
  
 parent.frames[0].document.getElementById("a1").innerHTML = "Principal";
 parent.frames[0].document.getElementById("a1").href = "redireccionador.php";
 parent.frames[0].document.getElementById("s1").style.visibility = "visible";
 
 parent.frames[0].document.getElementById("a2").innerHTML = "Gestión";
  parent.frames[0].document.getElementById("a2").href = "gestion.php";
</script>        
        
	</head>
	<body id="dt_example">
               <? 
		   	$operacion = new operacion();
			 $operacion -> menu();
			?>
		<div id="container">
        <? if ( isset($_GET["mensaje"]) ) { 
		?> 
        
        <div class="mensaje"><?=$_GET["mensaje"]?></div>  
        
        <?
        }
        ?>
			<div class="full_width big">
			<p>&nbsp;</p>
            Productos
			</div>
             <p>&nbsp;</p>
            <table class="display"><tr><td>
             <a href="add.php">Crear Producto</a>
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

