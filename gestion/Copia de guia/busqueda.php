<? 
include ("../usuarioftp/usuarioftp.php");
include ("../../conexion/conexionftp.php");
include ("../../param/param.php");


 	$usuarioftp = new usuarioftp();
 	$usuario="root"; //ojo deben venir desde un formulario de acceso
	$clave="root";  //ojo
	$host=$_SESSION['param']['ftpserverhost'];
	$directorio='';

	$cond="usuario='$usuario' and clave=MD5('$clave')"; 
	$res=$usuarioftp->consultar($cond);
	
	if (mysql_num_rows($res)>0)
	{
	    $filas=mysql_fetch_assoc($res);
	 	$carpeta=$filas["carpeta"];
	}
	else
		exit("Usuario no encontrado");


 	$vacio=true;

	$conexionftp = new conexionftp();
	$idconftp = $conexionftp->conexion($host,$usuario,$clave,$directorio);
	$contenido = $conexionftp->listar();
	
//foreach($contenido as $linealimpia)
	//{
		
//	}
//stripos($contenido) 
	var_dump ($contenido);
 	$dataSet="";
   /* 
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
*/  



	$vacio=false;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Busqueda de Guia</title>
		<style type="text/css" title="currentStyle">
			@import "css/demo_page.css";
			@import "css/demo_table.css";
		</style>
        
		<script type="text/javascript" language="javascript" src="js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>
		<script type="text/javascript" charset="utf-8">

		
		var aDataSet = [['13009','Abrir'],['45678','Abrir']];
			
			$(document).ready(function() {
				$('#dynamic').html( '<table cellpadding="0" cellspacing="0"  border="0" class="display" id="example"></table>' );
				$('#example').dataTable( {
					"aaData": aDataSet,
					"aoColumns": [
						{"sTitle": "Numero guia" },
						{"sTitle": "Ver" }	
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
