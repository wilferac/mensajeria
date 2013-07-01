<?

/* * ***************************************************************************
  Rutina para cargar los parametros especificados en param/param.properties
  a variables de sesion.
 * *************************************************************************** */

//$archivo="../../param/param.properties";
//$archivo = "http://www.leuss.com.ve/tests/mensajeria/param/param.properties"; 
//$archivo = "http://localhost/mensajeria/param/param.properties";

/**
 * local
 */
$archivo = "/var/www/Mensajeria/param/param.properties";
/**
 * servidor
 */
//$archivo = "/home/innovat1/public_html/Mensajeria/param/param.properties";


//if(!file_exists($archivo))
//{echo "Config. de parametros no existe.";exit;}
//cargas todas las variables en un array
$archivo = file_get_contents($archivo);
$archivo = explode("\n", $archivo, -1);

//print_r($archivo);
// obtiene los parametros especificados en el archivo properties
foreach ($archivo as $linea)
{
    // elimina los comentarios del archivo de parametros
    $linealimpia = explode(";", $linea, -1);
    $div = explode("=", $linealimpia[0]);
    $nombreparam = $div[0];
    $valorparam = $div[1];
    // registra en sesion los datos de los parametros
    $_SESSION['param'][$nombreparam] = $valorparam;
}


//print_r ($_SESSION['param']);
?>
