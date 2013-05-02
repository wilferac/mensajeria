<? 
 include("../clases/clases.php");

if (isset($_POST['registrar']))
{
//print_r($_POST);
$categorias=new categorias();

$categorias->descripcion=$_POST["descripcion"];


$registro=false;
if ($categorias->agregar())
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
var mensaje="Registro NO Exitoso";
window.location.href='productos.php?mensaje='+mensaje;
</script>
<?
}

return;
}

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
				<p class="navegacion"><a href="../redireccionador.php">Página principal</a>&gt;<a href="../gestiondelsistema.php">Gestión del sistema</a>&gt;<a href="categorias.php">Categorias</a></p>Agregar Categorias 
                </div>
             <p>&nbsp;</p>

        <div id="dynamic">
      
        <form class="formulario" id="formulario" method="post" action="">
	<fieldset>

       	<p>
			<label for="descripcion">Descripción (Obligatorio)</label>
			<input id="descripcion" name="descripcion"  class="required" maxlength="10"/>
       </p>   
       	<p>
			<input class="submit" type="submit"  id="registrar" name="registrar" value="Registrar"/>
		</p>
      </fieldset>
      </form>
        </div>
			<div class="spacer"></div>
		</div>
	</body>
</html>