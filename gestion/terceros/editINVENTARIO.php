<? 
 include("../clases/clases.php");

if (isset($_POST['actualizar']))
{
//print_r($_POST);

$descripcion=$_POST["categorias_descripcion"];
$categorias_id=$_POST["categorias_id"];

$categorias=new categorias();
$categorias->id=$categorias_id;
$categorias->descripcion=$descripcion;
if ( $categorias->modificar() )
{
  ?>
	<script language="javascript" type="text/javascript">
	var mensaje="Registro Exitoso";
	window.location.href='categorias.php?mensaje='+mensaje;
	</script>
  <?
}
else
{
?>
	<script language="javascript" type="text/javascript">
	var mensaje="Registro No Exitoso";
	window.location.href='categorias.php?mensaje='+mensaje;
	</script>
<?
}  
return;
}

$id=$_GET["id"];
$categorias=new categorias();

$cond="id=$id";
$res=$categorias->consultar($cond);
$filas_categorias=mysql_fetch_assoc($res);

$categorias_id=$filas_categorias["id"];
$categorias_descripcion=$filas_categorias["descripcion"];



?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Tablas</title>
		<style type="text/css" title="currentStyle">
			@import "../media/css/demo_page.css";
			@import "../media/css/demo_table.css";
		</style>
        <link rel="stylesheet" type="text/css" media="screen" href="../estilos/screen.css" />
        <style type="text/css">
			#formulario { width: 500px; }
			#formulario label { width: 250px; }
			#formulario label.error, #formulario input.submit { margin-left: 253px; }
		</style>

		<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
        <script type="text/javascript" language="javascript" src="../js/jquery.validate.js"></script>

		<script type="text/javascript">
		$.validator.setDefaults({
		submitHandler: function() { formulario.submit();
		 }
		});

		$().ready(function() {
		// validate the comment form when it is submitted
		$("#formulario").validate();

		// validate signup form on keyup and submit
		});
		</script>

	</head>
	<body id="dt_example">
		<div id="container">
			<div class="full_width big">
				<p class="navegacion"><a href="../redireccionador.php">Página principal</a>&gt;<a href="../gestiondelsistema.php">Gestión del sistema</a>&gt;<a href="categorias.php">Categorias</a></p>Modificar Categorias
			</div>
             <p>&nbsp;</p>

        <div id="dynamic">
      
        <form class="formulario" id="formulario" method="post" action="">
        <input type="hidden" name="categorias_id" id="categorias_id" value="<?=$id?>">
	<fieldset>

       	<p>
			<label for="categorias_descripcion">Descripción (Obligatorio)</label>
			<input id="categorias_descripcion" name="categorias_descripcion" value="<?=$categorias_descripcion?>"  class="required" maxlength="10"/>
       </p>   
       
        <p>
			<input class="submit" type="submit"  id="actualizar" name="actualizar" value="Actualizar" >
		</p>
      </fieldset>
      </form>
        </div>
			<div class="spacer"></div>
		</div>
	</body>
</html>