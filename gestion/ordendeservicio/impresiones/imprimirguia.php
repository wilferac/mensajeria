<!DOCTYPE html >
<html>
<head>
<link rel='stylesheet' href='estiloimprimir.css' type='text/css' /> 
</head>

<body>
<?php 

include('convertir.php'); 
include("../../../clases/clases.php");

require_once('barcodephp/class/BCGFontFile.php');
require_once('barcodephp/class/BCGColor.php');
require_once('barcodephp/class/BCGDrawing.php');
require_once('barcodephp/class/BCGcode39.barcode.php');




	$idos = $_GET["id"];
 	//$idos = 3;  //OJO

//$html= --> Aquí pondriamos por ejemplo la consulta <div style="position: absolute; top: 10mm; left: 10mm; width: 25mm; word-break:break-all;"> Colocar aqui el contenido para class contenido id "contenido" ycfds fsfsfsfsffsf fsdfsfsxx ff</div> 

$sql = "SELECT 
guia.numero_guia,
orden_servicio.numero_orden_servicio,
tercero.nombres_tercero,
tercero.apellidos_tercero,
guia.nombre_destinatario_guia,
guia.direccion_destinatario_guia,
ciudad.idciudad,
ciudad.nombre_ciudad AS CiudadOrigen,
departamento.nombre_departamento,
guia.ciudad_iddestino,
destinatario.documento_destinatario,
guia.peso_guia,
tipo_identificacion.nombre_tipo_identificacion

FROM guia, tercero, ciudad, destinatario, orden_servicio, departamento, tipo_identificacion
WHERE
guia.orden_servicio_idorden_servicio = $idos
AND
guia.orden_servicio_idorden_servicio = orden_servicio.idorden_servicio
AND
guia.tercero_idremitente = tercero.idtercero
AND
guia.ciudad_idorigen = ciudad.idciudad
AND
guia.tercero_iddestinatario = destinatario.iddestinatario
AND
ciudad.departamento_iddepartamento = departamento.iddepartamento
AND
destinatario.tipo_identificacion_destinatario = tipo_identificacion.idtipo_identificacion
order by guia.numero_guia ASC
";

 	$operacion = new operacion();
	$res = $operacion->consultar($sql);	
	if (mysql_num_rows($res)>0)
	{
	$html = "<div id='contenido'>";
	 $i = 1;
			while ($fila = mysql_fetch_assoc($res))
		{
				 $numero_guia = $fila["numero_guia"];
				 $numero_orden_servicio = $fila["numero_orden_servicio"];
				
				 $nombres_tercero = ucfirst($fila["nombres_tercero"]);
				 $apellidos_tercero = ucfirst($fila["apellidos_tercero"]);
				 $remite = $nombres_tercero." ".$apellidos_tercero;
				 
				 $nombre_destinatario_guia = ucfirst($fila["nombre_destinatario_guia"]);
				 $direccion_destinatario_guia = $fila["direccion_destinatario_guia"];
				 $idciudad = $fila["idciudad"];
				 $CiudadOrigen = ucfirst($fila["CiudadOrigen"]);
				 $nombre_departamento = ucfirst($fila["nombre_departamento"]);
				 $ciudad_iddestino = $fila["ciudad_iddestino"];
				
				 $nombre_tipo_identificacion = $fila["nombre_tipo_identificacion"];
				 $documento_destinatario = $fila["documento_destinatario"];
				
				$documento =   $nombre_tipo_identificacion.":".$documento_destinatario;
				 
				$peso_guia = $fila["peso_guia"];
				

				
$ruta = "../../../tmp/$numero_guia.jpeg";			

// The arguments are R, G, and B for color.
$colorFront = new BCGColor(0, 0, 0);
$colorBack = new BCGColor(255, 255, 255);
$font = new BCGFontFile('barcodephp/font/Arial.ttf', 18);
$code = new BCGcode39(); // Or another class name from the manual
$code->setScale(2); // Resolution
$code->setThickness(30); // Thickness
$code->setForegroundColor($colorFront); // Color of bars
$code->setBackgroundColor($colorBack); // Color of spaces
$code->setFont($font); // Font (or 0)
$code->parse($numero_guia); // Text


$drawing = new BCGDrawing($ruta,$colorBack);
$drawing->setBarcode($code);
$drawing->draw();

//header('Content-Type: image/jpeg');

$drawing->finish(BCGDrawing::IMG_FORMAT_JPEG);				
				
 $html.= "
<div id='apDiv$i'>
  <div id='Noguia$i'>
    <div align='center' class='Estilo1'>$numero_guia</div>
  </div>
  <div id='DescripcionRemitente$i'>
    <div align='center' class='Estilo1'>
      <p class='Estilo2'>Remitente:<br />
        $remite<br />
        $CiudadOrigen<br />
        <br />
        Destinatario:<br />
        $nombre_destinatario_guia<br />
        <br />
        $direccion_destinatario_guia<br />
        Torre Vallarta<br />
        6058945<br />
        $ciudad_iddestino<br />
        Santander</p>
      <p class='Estilo2'>11/09/2012 18:51</p>
    </div>
  </div>
  <div id='remite$i'>
    <div align='center' class='Estilo1'>$remite</div>
  </div>
  <div id='Destinatario$i'>
    <div align='center' class='Estilo1'>
      <p align='left' class='Estilo2'>$nombre_destinatario_guia<br />
        $direccion_destinatario_guia<br />
        $documento<br />
        - <br />
        </p>
    </div>
  </div>
  <div id='fechaProcesado$i'>
    <div align='center' class='Estilo1'>".date('d/m/Y h:i')."</div>
  </div>
  <div id='codigo$i'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='OrdenNo$i'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='PesoGrs$i'>
    <div align='center' class='Estilo1'>$peso_guia</div>
  </div>
  <div id='origen$i'>
    <div align='center' class='Estilo1'>$CiudadOrigen</div>
  </div>
  <div id='destino$i'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='Valor$i'>
    <div align='center' class='Estilo1'>0</div>
  </div>
  <div id='codigoBarra$i'>
    <div align='center' class='Estilo1'> <img src='".$ruta."'  name='imagen' id='imagen' /> </div>
  </div>
</div>
 ";
				
			if ($i % 6 == 0)
					{
						$i = 0;
						
						//$html .="</div>";	
						//$html .= "<div id='contenido'>";
						//echo "<div style='page-break-after:always'> </div>";
					}				
				$i++;					
	
		}

/*$html="  
<!--
<div style='position: absolute; top: 25mm; left: 10mm; width: 30mm; word-break:break-all;'> Colocar aqui el contenido para class contenido id 'contenido' ycfds fsfsfsfsffsf fsdfsfsxx ff</div>  

<img height='31px' width='139px' src='../../../tmp/imagen.jpeg' >
-->
 
<div id='contenido'>
 <div id='apDiv1'>
<div id='Noguia'>
  <div align='center' class='Estilo1'>123456</div>
</div>
<div id='DescripcionRemitente'>
  <div align='center' class='Estilo1'>
    <p class='Estilo2'>Remitente:<br />
    A1-Entregas S.A<br />
    Cali<br />
      <br />
    Destinatario:<br />
    LEONARDO MONSALVE<br />
      <br />
    Cr.22 No 34.38<br />
    Torre Vallarta<br />
    6058945<br />
    Bucaramanga<br />
    Santander</p>
    <p class='Estilo2'>11/09/2012 18:51</p>
  </div>
</div>
<div id='remite'>
  <div align='center' class='Estilo1'>123456</div>
</div>
<div id='Destinatario'>
  <div align='center' class='Estilo1'>
    <p align='left' class='Estilo2'>ALEJANDRA ALBORNOZ<br />  
      Av. Colombia No.5.32 O<br />
      316443567<br />
      Cali- <br />
      Valle</p>
  </div>
</div>
<div id='fechaProcesado'>
  <div align='center' class='Estilo1'>123456</div>
</div>
<div id='codigo'>
  <div align='center' class='Estilo1'>123456</div>
</div>
<div id='OrdenNo'>
  <div align='center' class='Estilo1'>123456</div>
</div>

<div id='PesoGrs'>
  <div align='center' class='Estilo1'>123456</div>
</div>
<div id='origen'>
  <div align='center' class='Estilo1'>123456</div>
</div>
<div id='destino'>
  <div align='center' class='Estilo1'>123456</div>
</div>
<div id='Valor'>
  <div align='center' class='Estilo1'>123456</div>
</div>
<div id='codigoBarra'>
  <div align='center' class='Estilo1'>
   <img src='../../../tmp/imagen.jpeg' name='imagen' id='imagen' />   </div>
</div>
</div>
<div id='apDiv2'>
  <div id='Noguia2'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='DescripcionRemitente2'>
    <div align='center' class='Estilo1'>
      <p class='Estilo2'>Remitente:<br />
        A1-Entregas S.A<br />
        Cali<br />
        <br />
        Destinatario:<br />
        LEONARDO MONSALVE<br />
        <br />
        Cr.22 No 34.38<br />
        Torre Vallarta<br />
        6058945<br />
        Bucaramanga<br />
        Santander</p>
      <p class='Estilo2'>11/09/2012 18:51</p>
    </div>
  </div>
  <div id='remite2'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='Destinatario2'>
    <div align='center' class='Estilo1'>
      <p align='left' class='Estilo2'>ALEJANDRA ALBORNOZ<br />
        Av. Colombia No.5.32 O<br />
        316443567<br />
        Cali- <br />
        Valle</p>
    </div>
  </div>
  <div id='fechaProcesado2'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='codigo2'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='OrdenNo2'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='PesoGrs2'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='origen2'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='destino2'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='Valor2'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='codigoBarra2'>
    <div align='center' class='Estilo1'> <img src='../../../tmp/imagen.jpeg' alt='' name='imagen' id='imagen' /> </div>
  </div>
</div>
<div id='apDiv3'>
  <div id='Noguia3'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='DescripcionRemitente3'>
    <div align='center' class='Estilo1'>
      <p class='Estilo2'>Remitente:<br />
        A1-Entregas S.A<br />
        Cali<br />
        <br />
        Destinatario:<br />
        LEONARDO MONSALVE<br />
        <br />
        Cr.22 No 34.38<br />
        Torre Vallarta<br />
        6058945<br />
        Bucaramanga<br />
      Santander</p>
      <p class='Estilo2'>11/09/2012 18:51</p>
    </div>
  </div>
  <div id='remite3'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='Destinatario3'>
    <div align='center' class='Estilo1'>
      <p align='left' class='Estilo2'>ALEJANDRA ALBORNOZ<br />
        Av. Colombia No.5.32 O<br />
        316443567<br />
        Cali- <br />
      Valle</p>
    </div>
  </div>
  <div id='fechaProcesado3'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='codigo3'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='OrdenNo3'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='PesoGrs3'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='origen3'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='destino3'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='Valor3'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='codigoBarra3'>
    <div align='center' class='Estilo1'> <img src='../../../tmp/imagen.jpeg' alt='' name='imagen' id='imagen' /> </div>
  </div>
</div>
<div id='apDiv4'>
  <div id='Noguia4'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='DescripcionRemitente4'>
    <div align='center' class='Estilo1'>
      <p class='Estilo2'>Remitente:<br />
        A1-Entregas S.A<br />
        Cali<br />
        <br />
        Destinatario:<br />
        LEONARDO MONSALVE<br />
        <br />
        Cr.22 No 34.38<br />
        Torre Vallarta<br />
        6058945<br />
        Bucaramanga<br />
      Santander</p>
      <p class='Estilo2'>11/09/2012 18:51</p>
    </div>
  </div>
  <div id='remite4'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='Destinatario4'>
    <div align='center' class='Estilo1'>
      <p align='left' class='Estilo2'>ALEJANDRA ALBORNOZ<br />
        Av. Colombia No.5.32 O<br />
        316443567<br />
        Cali- <br />
      Valle</p>
    </div>
  </div>
  <div id='fechaProcesado4'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='codigo4'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='OrdenNo4'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='PesoGrs4'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='origen4'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='destino4'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='Valor4'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='codigoBarra4'>
    <div align='center' class='Estilo1'> <img src='../../../tmp/imagen.jpeg' alt='' name='imagen' id='imagen' /> </div>
  </div>
</div>
<div id='apDiv5'>
  <div id='Noguia5'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='DescripcionRemitente5'>
    <div align='center' class='Estilo1'>
      <p class='Estilo2'>Remitente:<br />
        A1-Entregas S.A<br />
        Cali<br />
        <br />
        Destinatario:<br />
        LEONARDO MONSALVE<br />
        <br />
        Cr.22 No 34.38<br />
        Torre Vallarta<br />
        6058945<br />
        Bucaramanga<br />
        Santander</p>
      <p class='Estilo2'>11/09/2012 18:51</p>
    </div>
  </div>
  <div id='remite5'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='Destinatario5'>
    <div align='center' class='Estilo1'>
      <p align='left' class='Estilo2'>ALEJANDRA ALBORNOZ<br />
        Av. Colombia No.5.32 O<br />
        316443567<br />
        Cali- <br />
        Valle</p>
    </div>
  </div>
  <div id='fechaProcesado5'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='codigo5'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='OrdenNo5'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='PesoGrs5'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='origen5'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='destino5'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='Valor5'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='codigoBarra5'>
    <div align='center' class='Estilo1'> <img src='../../../tmp/imagen.jpeg' alt='' name='imagen' id='imagen' /> </div>
  </div>
</div>
<div id='apDiv6'>
  <div id='Noguia6'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='DescripcionRemitente6'>
    <div align='center' class='Estilo1'>
      <p class='Estilo2'>Remitente:<br />
        A1-Entregas S.A<br />
        Cali<br />
        <br />
        Destinatario:<br />
        LEONARDO MONSALVE<br />
        <br />
        Cr.22 No 34.38<br />
        Torre Vallarta<br />
        6058945<br />
        Bucaramanga<br />
        Santander</p>
      <p class='Estilo2'>11/09/2012 18:51</p>
    </div>
  </div>
  <div id='remite6'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='Destinatario6'>
    <div align='center' class='Estilo1'>
      <p align='left' class='Estilo2'>ALEJANDRA ALBORNOZ<br />
        Av. Colombia No.5.32 O<br />
        316443567<br />
        Cali- <br />
        Valle</p>
    </div>
  </div>
  <div id='fechaProcesado6'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='codigo6'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='OrdenNo6'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='PesoGrs6'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='origen6'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='destino6'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='Valor6'>
    <div align='center' class='Estilo1'>123456</div>
  </div>
  <div id='codigoBarra6'>
    <div align='center' class='Estilo1'> <img src='../../../tmp/imagen.jpeg' alt='' name='imagen' id='imagen' /> </div>
  </div>
</div>
</div> "; */
$html .="</div>";
echo $html;
    // doPDF('ejemplo',$html,true,'estiloimprimir.css',false,'letter','landscape');
}  // FIN if (mysql_num_rows($res)>0)
?> 
</body> 
</html>
