<?
session_start();
//include ("../../clases/clases.php");
include "../../param/param.php";

include ("../../conexion/conexionftp.php");
//
include ("../../conexion/conexion.php");
//include ("../../clases/Guia.php");
//include ("../../clases/DaoGuia.php");

//include "../../security/User.php";
//include "../../Menu.php";

//$objUser = unserialize($_SESSION['currentUser']);
////$objUser = new User();
////        echo($objUser->getStatus());
//if ($objUser->getStatus() != 1)
//{
//    //$objUser->show();
//    $operacion->redireccionar('No Puede entrar', 'index.php');
//    return;
//}
//include ("../../param/param.php");
//include ("../../autenticar.php");
//include ("../../clases/Guia.php");
//include ("../../clases/DaoGuia.php");
//var_dump ($_SESSION);
/* $valido = verificarToken('busqueda', $_POST['token'], $_SESSION['param']['sessiontime']);
  if(!$valido)
  {
  echo "El ticket recibido no es válido";
  session_destroy();
  exit();
  }
 */
//creo una coneccion rapida para la consulta
$con = new conexion();

$idguia = $_POST["idguia"];
$token = $_POST['token'];
//$idguia = '1212';
$vacio = true;
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
//aca debo pasarle los parametros quemados
$idconftp = $conexionftp->conexion($ftphost, $ftpusuario, $ftpclave, $ftpdirectorio);
$contenido = $conexionftp->listar();

//if (in_array($idguia . $ext1, $contenido))
    $archivojpg = $idguia . $ext1;
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
//        $objMenu = new Menu($objUser);
//        $objMenu->generarMenu();
        ?>
        <div id="container">
            <div class="full_width big">
                <p>&nbsp;</p>
                Gu&iacute;a <?= $idguia ?></div>
            <p>&nbsp;</p>
            <?
            if ($archivojpg == "")
            {
                echo "Guia $idguia -> Prueba de Entrega  no encontrada";
                //exit();
            } else
            {
                $archivojpg = $ftpdirectorio . $separador . $archivojpg;

                /*                 * ****************************************************************************** 
                  Creacion de archivos locales en el servidor apache para acceso recurrente
                  de clientes
                 * **************************************************************************** */
                $localfile = $dirtmp . '/temp' . $token . '.jpg';
                $handle = fopen($localfile, 'w');

//$localfile = substr_replace($localfile2,$ext1,strlen($localfile2)-4,strlen($localfile2));

                if ($conexionftp->getftp($handle, $archivojpg))
                {
                    fclose($handle);
                    //rename($localfile2, $localfile);

                    echo "<div align='center'> 
			  <a href='$localfile' target='_BLANK' border='0'>
			  <img  src='$localfile' height='$altoimagenguia' width='$anchoimagenguia'>
			</a>
			</div>";
                } else
                {
                    echo "No se Encuentra la prueba de entrega";
                }
            }



            // echo("<br />aca debo mostrar la traza para $idguia");
            consultarTraza($idguia);



//var_dump ($contenido);

            $conexionftp->cerrar();

//session_destroy();




            function consultarTraza($idguia)
            {
                $query = "SELECT 
g.`numero_guia`,
g.`idguia`, DATE(g.`fecha`) AS guiaFecha, gm.fechaManual AS gmFechaManual, DATE(gm.`fechaDescarga`) AS gmFechaAuto, DATE(m.`fechaCreacion`) AS maniFecha,
m.`ciudadDestino`, m.`ciudadOrigen`, m.idmanifiesto,
eg1.idcausal_devolucion AS est1Id, eg1.nombre_causal_devolucion AS est1Nombre, 
eg2.idcausal_devolucion AS est2Id, eg2.nombre_causal_devolucion AS est2Nombre
FROM guia g 
LEFT JOIN guia_manifiesto gm ON g.`numero_guia` = gm.guiId
LEFT JOIN manifiesto m ON m.`idmanifiesto` = gm.manId
INNER JOIN estadoGuia eg1 ON eg1.idcausal_devolucion = g.`causal_devolucion_idcausal_devolucion`
LEFT JOIN estadoGuia eg2 ON eg2.idcausal_devolucion = gm.idEstadoGuia
WHERE g.`numero_guia` = '$idguia'
ORDER BY g.idguia";

                //echo($query);
                echo("<br /><br />");
                if ($res = mysql_query($query))
                {
                    $ultimaFecha = null;
                    $cont = 0;
                    while ($fila = mysql_fetch_assoc($res))
                    {
                        //aca muestro los datos :O
                        if ($cont == 0)
                        {
                            $fechaInicial = $fila['guiaFecha'];
                            echo("Fecha Entrada: " . $fechaInicial);
                            echo("<br />");

                            echo("Estado Actual: " . $fila['est1Nombre']);
                            echo("<br />Historial Guia");
                            echo("<ul>");
                        }
                        $idmanifiesto = $fila['idmanifiesto'];
                        if (!empty($idmanifiesto))
                        {
                            echo("<li>");
                            echo("Agregado a Manifiesto: " . $fila['maniFecha']);
                            $fechaEstado = !empty($fila['gmFechaManual']) ? $fila['gmFechaManual'] : $fila['gmFechaAuto'];
                            echo("<br />Estado: " . $fila['est2Nombre'] . " en " . $fechaEstado);
                            echo("</li>");
                            $ultimaFecha = $fechaEstado;
                            echo("<br />");
                        }



                        $cont++;
                    }
                    echo("<ul>");
                    //$newdate = strtotime ( -strtotime ($ultimaFecha),strtotime ($fechaInicial)  ) ;
                    $datetime1 = date_create($fechaInicial);
                    $datetime2 = date_create($ultimaFecha);
                    $interval = $datetime1->diff($datetime2);
                    //$interval = date_diff($ultimaFecha, $fechaInicial);
                    echo $interval->format('Tiempo De Gestion: %R%a dias');
                    //$newdate = date ( 'Y-m-j' , $newdate );
                    //echo("Tiempo Total: ".$newdate);
                } else
                {

                    die("Error al consultar la guia " . mysql_error());
                }
            }
            $con->cerrar();
            ?>

        </div>
    </body>
</html>
