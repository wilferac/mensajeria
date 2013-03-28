<? 
include ("../../clases/clases.php");
include ("../../conexion/conexionftp.php");
//include ("../../param/param.php");
include ("../../autenticar.php");


//var_dump ($_SESSION);
/*$valido = verificarToken('busqueda', $_POST['token'], $_SESSION['param']['sessiontime']);
if(!$valido)
	{ 
	echo "El ticket recibido no es válido";
	session_destroy();
  	exit();
	}
*/
	
	$idguia = $_POST["idguia"];
	$token = $_POST['token'];	
	//$idguia = '1212';
 	$vacio=true;
	$archivojpg = "";
	$ext1 = '.jpg';
	$ext2 = '.jpeg';

	
 	$ftphost = $_SESSION['param']['ftphost'];
	$ftpusuario = $_SESSION['param']['ftpusuario'];
	$ftpclave = $_SESSION['param']['ftpclave'];
	$ftpdirectorio = $_SESSION['param']['ftpdirectorio'];
	
	$dirtmp = $_SESSION['param']['dirtmp'];

	$anchoimagenguia = $_SESSION['param']['anchoimagenguia'];
	$altoimagenguia = $_SESSION['param']['altoimagenguia'];
	
		if (empty($ftpdirectorio))	
			$separador = '';
		else
			$separador = '/';

	$conexionftp = new conexionftp();
	$idconftp = $conexionftp->conexion($ftphost,$ftpusuario,$ftpclave,$ftpdirectorio);
	$contenido = $conexionftp->listar();
	
		if ( in_array($idguia.$ext1,$contenido) ) 
			$archivojpg = $idguia.$ext1;
		//elseif ( in_array($idguia.$ext2,$contenido) )
			//$archivojpg = $idguia.$ext2;
	//echo $archivojpg;


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


<script language="javascript">
  parent.frames[0].document.getElementById("a1").innerHTML = "";
 parent.frames[0].document.getElementById("a2").innerHTML = "";
 parent.frames[0].document.getElementById("a3").innerHTML = "";
 
 parent.frames[0].document.getElementById("s1").style.visibility = "hidden";
 parent.frames[0].document.getElementById("s2").style.visibility = "hidden";
 
 
 parent.frames[0].document.getElementById("a1").innerHTML = "Principal";
 parent.frames[0].document.getElementById("a1").href = "redireccionador.php";
 parent.frames[0].document.getElementById("s1").style.visibility = "visible";
 
 parent.frames[0].document.getElementById("a2").innerHTML = "Procesos";
 parent.frames[0].document.getElementById("a2").href = "procesos.php";
 parent.frames[0].document.getElementById("s2").style.visibility = "visible";
 
 parent.frames[0].document.getElementById("a3").innerHTML = "Consultar guia";
 parent.frames[0].document.getElementById("a3").href = "gestion/guia/buscar.php";
 
</script> 
	
	</head>
	<body id="dt_example">
                   <? 
		   	$operacion = new operacion();
			 $operacion -> menu();
			?>
		<div id="container">
              <div class="full_width big">
      	<p>&nbsp;</p>
        	Gu&iacute;a <?=$idguia?></div>
            <p>&nbsp;</p>
      <?      	if ( $archivojpg == "" )
		{
			echo "Guia $idguia no encontrada";	
			exit();	
		}
		
		$archivojpg = $ftpdirectorio.$separador.$archivojpg;

/******************************************************************************** 
		Creacion de archivos locales en el servidor apache para acceso recurrente 
	  	de clientes
******************************************************************************/
	$localfile = $dirtmp.'/temp'.$token.'.jpg';
	$handle = fopen($localfile, 'w');
	
	//$localfile = substr_replace($localfile2,$ext1,strlen($localfile2)-4,strlen($localfile2));
	
	if ( $conexionftp->getftp($handle,$archivojpg) )
	{
		fclose($handle);		
		//rename($localfile2, $localfile);
				
		echo "<div align='center'> 
			  <a href='$localfile' target='_BLANK' border='0'>
			  <img  src='$localfile' height='$altoimagenguia' width='$anchoimagenguia'>
			</a>
			</div>";
	}
	else
		echo "Error en descarga de imagen desde el ftp";


	//var_dump ($contenido);
 
	$conexionftp->cerrar();
	//session_destroy();
    ?>
            
		</div>
	</body>
</html>
