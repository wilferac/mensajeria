<?php

require("../../../conexion/conexion.php");
require_once('../../../libreria/tcpdf/tcpdf.php');
require "../../../security/User.php";

$objUser = unserialize($_SESSION['currentUser']);
$idUser = $objUser->getId();

$fecha1 = $_REQUEST['fecha1'];
$fecha2 = $_REQUEST['fecha2'];
$AllDatosGuia = getDatosGuia($idUser, $fecha1, $fecha2);

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('A1');
$pdf->SetTitle('Guia');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(5, 1, 0);
$pdf->SetAutoPageBreak(FALSE, 0);
$pdf->setFontSubsetting(false);
$pdf->setCellMargins(0.5, 1, 0.5, 1);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('times', '', 10);
$style = array(
    'position' => '',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    'border' => false,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0, 0, 0),
    'bgcolor' => false, //array(255,255,255),
    'text' => true,
    'font' => 'helvetica',
    'fontsize' => 8,
    'stretchtext' => 4
);
//echo("<pre>");
//print_r($AllDatosGuia);
//echo("</pre>");
//return;

foreach ($AllDatosGuia as $datosGuia)
{
  $pdf->AddPage();
  


  $tabla3 = "
    <table >
<tr>
    <td width=\"48\">FECHA:</td>
    <td width=\"82\">" . $datosGuia["fecha"] . "</td>
</tr>
<tr>
    <td>Ref:</td>
    <td>" . $datosGuia["referencia"] . "</td>
</tr>
<tr>
    <td>C.C/NIT:</td>
    <td>" . $datosGuia["remiDocu"] . "</td>
</tr>
<tr>
    <td>C.C/NIT:</td>
    <td><b>" . $datosGuia["destiDocu"] . "</b></td>
</tr>
<tr>
    <td>PESO:</td>
    <td><b>" . $datosGuia["peso"] . "</b></td>
</tr>
<tr>
    <td>TAMAÑO:</td>
    <td><b>" . round($datosGuia["largo"]) . "x" . round($datosGuia["ancho"]) . "x" . round($datosGuia["alto"]) . "</b></td>
</tr>
</table>";

  $espacio = 32;
//$disminuir = 0.1;
//$pdf->Ln(1);
  $posBarra = 1;
  $yi= array('0' => 10,'1' => 90,'2' => 165,'3' => 245);
  for ($con = 0; $con < 4; $con++)
  {
    $y = $pdf->getY();
    $style['position'] = 'R';
    $pdf->write1DBarcode($datosGuia["nGuia"], 'C128', 120, $posBarra, '', 14, 0.4, $style, 'N');
    $pdf->Ln(1);
//    $pdf->writeHTMLCell(99, '', '', $posBarra + 9, $tabla1, 0, 0, 1, true, 'J', true);
    //tabla1
    $xi=0;
    
    $ancho1 = 28;
    $alto1 = 1;
    $ancho2 = 70;
    $y1 = $yi[$con];
    $x1 = $xi + 5;
    $borde = 0;
    $space = 4;
    $yn1=$y1;
    $pdf->SetXY($x1, $yn1);
    $pdf->Cell($ancho1, $alto1, "REMITENTE:", $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $pdf->SetXY($x1+28, $yn1);
    $pdf->Cell($ancho2, $alto1, $datosGuia['remiNom'] . " " . $datosGuia['remiApel'] . $datosGuia["remiInfo"], $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $yn1+=$space;
    $pdf->SetXY($x1 , $yn1);
    $pdf->Cell($ancho1, $alto1, 'DIRECCION:', $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $pdf->SetXY($x1+28, $yn1);
    $pdf->Cell($ancho2, $alto1, $datosGuia["remiCiu"] . "/" . $datosGuia["remiDep"], $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $yn1+=$space;
    $pdf->SetXY($x1 , $yn1);
    $pdf->Cell($ancho1, $alto1, 'CIUDAD:', $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $pdf->SetXY($x1+28, $yn1);
    $pdf->Cell($ancho2, $alto1,$datosGuia["remiCiu"] . "/" . $datosGuia["remiDep"] , $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $yn1+=$space;
    $pdf->SetXY($x1 , $yn1);
    $pdf->Cell($ancho1, $alto1, 'DESTINATARIO:', $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $pdf->SetXY($x1+28, $yn1);
    $pdf->Cell($ancho2, $alto1, $datosGuia["destiNom"] . $datosGuia["destiInfo"], $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $yn1+=$space;
    $pdf->SetXY($x1 , $yn1);
    $pdf->Cell($ancho1, $alto1, 'DIRECCION:', $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $pdf->SetXY($x1+28, $yn1);
    $pdf->Cell($ancho2, $alto1, $datosGuia["destiDir"], $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $yn1+=$space;
    $pdf->SetXY($x1 , $yn1);
    $pdf->Cell($ancho1, $alto1, 'CIUDAD:', $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $pdf->SetXY($x1+28, $yn1);
    $pdf->Cell($ancho2, $alto1,$datosGuia["destiCiu"] . "/" . $datosGuia["destiDep"]  , $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $yn1+=$space;
    $pdf->SetXY($x1 , $yn1);
    $pdf->Cell($ancho1, $alto1, 'TELEFONO:', $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $pdf->SetXY($x1+28, $yn1);
    $pdf->Cell($ancho2, $alto1, $datosGuia["destiTel"], $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    
    
    $ancho3 = 22;
    $ancho4 = 24;
    $y2 = $yi[$con]+5;
    $x2 = $xi + 105;
    $x4 = $x2+53;
    $ancho5 = 18;
    $space2 = 4;
    $moreX=18;
    $yn1=$y2;
    $pdf->SetXY($x2, $yn1);
    $pdf->Cell($ancho3, $alto1, "PRODUCTO:", $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $pdf->SetXY($x2+22, $yn1);
    $pdf->Cell($ancho4, $alto1,  $datosGuia["producto"], $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $pdf->SetXY($x4, $yn1);
    $pdf->Cell($ancho5, $alto1, "FECHA:", $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $pdf->SetXY($x4+$moreX, $yn1);
    $pdf->Cell($ancho4, $alto1,   $datosGuia["fecha"], $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $yn1+=$space2;
    $pdf->SetXY($x2 , $yn1);
    $pdf->Cell($ancho3, $alto1, 'CONTENIDO:', $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $pdf->SetXY($x2+22, $yn1);
    $pdf->Cell($ancho4, $alto1, substr($datosGuia["contenido"], 0, 32), $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $pdf->SetXY($x4, $yn1);
    $pdf->Cell($ancho5, $alto1, "Ref:", $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $pdf->SetXY($x4+$moreX, $yn1);
    $pdf->Cell($ancho4, $alto1, $datosGuia["referencia"], $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $yn1+=$space2;
    $pdf->SetXY($x2 , $yn1);
    $pdf->Cell($ancho3, $alto1, 'VR. DECLA:', $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $pdf->SetXY($x2+22, $yn1);
    $pdf->Cell($ancho4, $alto1,$datosGuia["vDeclarado"] , $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $pdf->SetXY($x4, $yn1);
    $pdf->Cell($ancho5, $alto1, "C.C/NIT:", $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $pdf->SetXY($x4+$moreX, $yn1);
    $pdf->Cell($ancho4, $alto1,  $datosGuia["remiDocu"], $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $yn1+=$space2;
    $pdf->SetXY($x2 , $yn1);
    $pdf->Cell($ancho3, $alto1, 'FLETE:', $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $pdf->SetXY($x2+22, $yn1);
    $pdf->Cell($ancho4, $alto1, $datosGuia["flete"], $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $pdf->SetXY($x4, $yn1);
    $pdf->Cell($ancho5, $alto1, "C.C/NIT:", $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $pdf->SetXY($x4+$moreX, $yn1);
    $pdf->Cell($ancho4, $alto1,  $datosGuia["destiDocu"], $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $yn1+=$space2;
    $pdf->SetXY($x2 , $yn1);
    $pdf->Cell($ancho3, $alto1, 'PRIMA:', $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $pdf->SetXY($x2+22, $yn1);
    $pdf->Cell($ancho4, $alto1, $datosGuia["prima"], $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $pdf->SetXY($x4, $yn1);
    $pdf->Cell($ancho5, $alto1, "PESO:", $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $pdf->SetXY($x4+$moreX, $yn1);
    $pdf->Cell($ancho4, $alto1,  $datosGuia["peso"], $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $yn1+=$space2;
    $pdf->SetXY($x2 , $yn1);
    $pdf->Cell($ancho3, $alto1, 'TOTAL:', $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $pdf->SetXY($x2+22, $yn1);
    $pdf->Cell($ancho4, $alto1,"" , $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $pdf->SetXY($x4, $yn1);
    $pdf->Cell($ancho5, $alto1, "TAMAÑO:", $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
    $pdf->SetXY($x4+$moreX, $yn1);
    $pdf->Cell($ancho4, $alto1,  round($datosGuia["largo"]) . "x" . round($datosGuia["ancho"]) . "x" . round($datosGuia["alto"]), $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');

    $posBarra+=77;
  }
}
$pdf->lastPage();
$pdf->Output('example_005.pdf', 'I');
echo('<script>opener.location.reload(true);
   self.close();</script>');

function getDatosGuia($idUser, $fecha1, $fecha2)
{

  $fecha1 = $_REQUEST['fecha1'];
  $fecha2 = $_REQUEST['fecha2'];

  if (empty($fecha1))
  {
    $fecha1 = date("Y/m/d");
  }

  if (empty($fecha2))
  {
    $fecha2 = date("Y/m/d");
  }

  $fecha1c = strtotime($fecha1);
  $fecha2c = strtotime($fecha2);
  if ($fecha1c > $fecha2c)
  {
    die("Rango Invalido");
  }

  $query2 = "select g.idguia, g.numero_guia, g.nombre_destinatario_guia, g.direccion_destinatario_guia,
 g.valor_declarado_guia, g.peso_guia, g.contenido, g.prima, g.flete, g.telefono_destinatario_guia,
 g.destinatarioInfo, g.remitenteInfo, date(g.fecha) as fecha, g.referencia,
 g.largo, g.ancho, g.alto, 
 c1.nombre_ciudad as ciudad_nombreorigen, dp1.nombre_departamento as dptoOrigen,
 c2.nombre_ciudad as ciudad_nombredestino, dp2.nombre_departamento as dptoDestino,
 p.nombre_producto, tp.nombre_tipo_producto,
 t.documento_tercero, t.nombres_tercero, t.apellidos_tercero,
 t.direccion_tercero,
 d.documento_destinatario, d.nombres_destinatario
from guia g inner join tercero t on g.tercero_idremitente = t.idtercero
inner join ciudad c1 on c1.idciudad = g.ciudad_idorigen inner join ciudad c2 on c2.idciudad = g.ciudad_iddestino
inner join producto p on p.idproducto = g.producto_idproducto
inner join tipo_producto tp on tp.idtipo_producto = tipo_producto_idtipo_producto
inner join departamento dp1 on dp1.iddepartamento = c1.departamento_iddepartamento
inner join departamento dp2 on dp2.iddepartamento = c2.departamento_iddepartamento
left join destinatario d on d.iddestinatario = g.tercero_iddestinatario
where date(g.`fecha`) BETWEEN '$fecha1' AND '$fecha2' AND g.estado = 1 and documento_destinatario is not null and g.causal_devolucion_idcausal_devolucion <> 3
AND g.owner = $idUser";

//  echo($query2);
//die();
  $results2 = mysql_query($query2) or die(mysql_error());
  if (mysql_affected_rows() <= 0)
  {
    die("No se encontraron guias para ese rango");
  }
  $res = array();
  $cont = 0;
  while ($fila = mysql_fetch_assoc($results2))
  {
    $res[$cont] = array();
    $res[$cont]["largo"] = $fila["largo"];
    $res[$cont]["ancho"] = $fila["ancho"];
    $res[$cont]["alto"] = $fila["alto"];
    $res[$cont]["referencia"] = $fila["referencia"];
    $res[$cont]["fecha"] = $fila["fecha"];
    $res[$cont]["flete"] = $fila["flete"];
    $res[$cont]["prima"] = $fila["prima"];
    $res[$cont]["contenido"] = $fila["contenido"];
    $res[$cont]["remiInfo"] = $fila["remitenteInfo"];
    $res[$cont]["remiDocu"] = $fila["documento_tercero"];
    $res[$cont]["remiNom"] = $fila["nombres_tercero"];
    $res[$cont]["remiApel"] = $fila["apellidos_tercero"];
    $res[$cont]["remiDir"] = $fila["direccion_tercero"];
    $res[$cont]["remiCiu"] = $fila["ciudad_nombreorigen"];
    $res[$cont]["remiDep"] = $fila["dptoOrigen"];
    if ($res[$cont]["remiInfo"] != "" and $res[$cont]["remiInfo"] != NULL)
    {
      $res[$cont]["remiInfo"] = "-" . $res[$cont]["remiInfo"];
    }
    $res[$cont]["destiInfo"] = $fila["destinatarioInfo"];
    $res[$cont]["destiDocu"] = $fila["documento_destinatario"];
    $res[$cont]["destiNom"] = $fila["nombre_destinatario_guia"];
    $res[$cont]["destiTel"] = $fila["telefono_destinatario_guia"];
    $res[$cont]["destiDir"] = $fila["direccion_destinatario_guia"];
    $res[$cont]["destiCiu"] = $fila["ciudad_nombredestino"];
    $res[$cont]["destiDep"] = $fila["dptoDestino"];
    if ($res[$cont]["destiInfo"] != "" and $res[$cont]["destiInfo"] != NULL)
    {
      $res[$cont]["destiInfo"] = "-" . $res[$cont]["destiInfo"];
    }
    $res[$cont]["nGuia"] = $fila["numero_guia"];
    $res[$cont]["peso"] = $fila["peso_guia"];
    $res[$cont]["producto"] = $fila["nombre_producto"] . "/" . $fila["nombre_tipo_producto"];
    $res[$cont]["vDeclarado"] = $fila["valor_declarado_guia"];
    $res[$cont]["Total"] = 0;
    $cont++;
  }
  return $res;
}

?>