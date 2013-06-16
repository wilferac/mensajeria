<?php

session_start();
require("../../conexion/conexion.php");
require_once('../../libreria/tcpdf/tcpdf.php');
require("../../clases/Guia.php");


$idOrdenServicio = $_REQUEST["id"];

//echo($idOrdenServicio);

$guias = new ArrayObject();

getDatosGuia($idOrdenServicio, $guias);
$pdf = new TCPDF('l', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('A1');
$pdf->SetTitle('Guia');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(0, 0, 0);
$pdf->setFontSubsetting(false);
$pdf->SetAutoPageBreak(FALSE, 0);



// ---------------------------------------------------------
// set font
//$pdf->SetFont('times', '', 10);
// add a page
$pdf->AddPage();

$pdf->SetDrawColor(0, 0, 0, 50);
$pdf->SetFillColor(0, 0, 0, 100);
$pdf->SetTextColor(0, 0, 0, 100);
$pdf->SetFont('times', 'B', 6);

$style = array(
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
    'fontsize' => 6,
    'stretchtext' => 4
);


// set cell margins
$pdf->setCellMargins(0, 0, 0, 0);

// set color for background
$pdf->SetFillColor(255, 255, 255);




$espacio = 32;
//$disminuir = 0.1;
//$pdf->Ln(1);
//$posBarra = 1;
//la cantidad de las guias
$total = $cantGuias = sizeof($guias);
//$inicial=0;
//buque principal
for ($inicial = 0; $inicial < $total;)
{
    //la cantidad de guias a imprimir por hoja
    $para = $inicial + 6;
    if ($cantGuias < $para)
    {
        $para = $cantGuias;
    }
    $con = $inicial;

    $xi = 10;
    $yi = 1;
    $cuenta = 0;
    $fila = 1;
    for (; $con < $para; $con++)
    {
        //echo("<br>mostrando la guia numero ".$con." con guia ".$guias[$con]->getNumero()." quedan ".$cantGuias);
        $arre = new ArrayObject();
//        $arre = fillTable($guias[$con]);
//        $tabla1 = $arre[0];
//        $tabla2 = $arre[1];
//        $tabla3 = $arre[2];
//        $tabla4 = $arre[3];
//        $tabla5 = $arre[4];

        $num = $guias[$con]->getNumero();
        $remitente = $guias[$con]->getNombreRemitente();
        $detinatario = $guias[$con]->getNombreDestinatario();
        $dir = $guias[$con]->getDireccion();
        $ciuDesti = $guias[$con]->getCiuDesti();
        $depDesti = $guias[$con]->getDepDesti();
        $fecha = $guias[$con]->getFecha();
        $peso = $guias[$con]->getPeso();

        $extra = $guias[$con]->getDestinatarioInfo();
        $telDesti = $guias[$con]->getTelefonoDesti();
        $docuRemi = $guias[$con]->getIdRemitente();
        $numOS = $guias[$con]->getNumeroOrdenSer();

        $ciuOrigen = $guias[$con]->getCiuOrigen();

        //echo("<br>/*begin table*/".$tabla1." ".$tabla2." ".$tabla3." ".$tabla4." /*fin table*/<br>");
        // $pdf->Cell(32, '', 'aca va lo q voy a decir', 1, 1, 'C', 0, '', 1);

        $pdf->write1DBarcode($guias[$con]->getNumero(), 'C128', $xi + 80, $yi, 50, 12, 0.4, $style, '');


        //$pdf->SetTextColor(245,245,245);

        $ancho1 = 28;
        $alto1 = 3;
        $y1 = $yi + 18;
        $x1 = $xi+3;
        $borde = 0;
        $pdf->SetXY($x1 , $y1);
        $pdf->Cell($ancho1, $alto1, $num, $borde, 0, 'C', 0, 0, 1, false, 'T', 'T');
        $y1+=2;
        $pdf->SetXY($x1 , $y1 + 3);
        $pdf->Cell($ancho1, $alto1, 'Remite:', $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
        $pdf->SetXY($x1 , $y1 + 6);
        $pdf->MultiCell($ancho1, 8, $remitente . "\n", $borde, 'L', 1, 2, '', '', true);
        // $pdf->Cell($ancho1, 40, $remitente, 1, 2, 'L', 0, 0, 4, false,'T','T');
        $pdf->SetXY($x1 , $y1 + 14);
        $pdf->Cell($ancho1, $alto1, 'Destinartario:', $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
        $pdf->SetXY($x1 , $y1 + 17);
        $pdf->MultiCell($ancho1, 6, $detinatario . "\n", $borde, 'L', 1, 2, '', '', true);
        //$pdf->Cell($ancho1, $alto1, $detinatario, 0, 0, 'L');
        $pdf->SetXY($x1 , $y1 + 25);
        $pdf->Cell($ancho1, $alto1, $extra, $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
        $pdf->SetXY($x1 , $y1 + 28);
        $pdf->MultiCell($ancho1, 6, $dir . "\n", $borde, 'L', 1, 2, '', '', true);
        $pdf->SetXY($x1 , $y1 + 34);
        $pdf->Cell($ancho1, $alto1, $ciuDesti, $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
        $pdf->SetXY($x1 , $y1 + 37);
        $pdf->Cell($ancho1, $alto1, $depDesti, $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
        $pdf->SetXY($x1 , $y1 + 40);
        $pdf->Cell($ancho1, $alto1, $fecha, $borde, 0, 'L', 0, 0, 1, false, 'T', 'T');
	$pdf->SetFont('times', 'B', 8);
        $ancho2 = 53;
        $y2 = $yi + 11;
        //  $pdf->writeHTMLCell(53, '', $xi + 35, $yi + 15, $tabla2, 0, 0, 1, true, 'J', true);
        $pdf->SetXY($xi + 37, $y2);
        $pdf->MultiCell($ancho2, 10, $remitente . "\n", $borde, 'L', 1, 2, '', '', true);
        $pdf->SetXY($xi + 37, $y2 + 10);
        $pdf->MultiCell($ancho2, 10, $detinatario . " " . $extra . "\n", $borde, 'L', 1, 2, '', '', true);
        $pdf->SetXY($xi + 37, $y2 + 17);
        $pdf->MultiCell($ancho2, 10, $dir . " " . $telDesti . "\n" . $ciuDesti . " " . $depDesti, $borde, 'L', 1, 2, '', '', true);
        $pdf->SetXY($xi + 37, $y2 + 30);
        $pdf->MultiCell(20, 3, $fecha, $borde, 'L', 1, 2, '', '', true);
	$pdf->SetFont('times', 'B', 6);
        //tabla 4
        //  $pdf->writeHTMLCell(26, '', $xi + 89, $yi + 15, $tabla4, 0, 0, 1, true, 'J', true);
        //tabla 5
        //  $pdf->writeHTMLCell(26, '', $xi + 115, $yi + 15, $tabla5, 0, 0, 1, true, 'J', true);
        $y3 = $yi + 15;
        $x3 = $xi+94;
        $ancho3 = 15;
        $alto3 = 3;
        $pdf->SetXY($x3 , $y3);
        $pdf->Cell($ancho3, $alto3, $docuRemi, $borde, 0, 'C', 0, 0, 1, false, 'T', 'T');
        $pdf->SetFont('times', 'B', 8);
        $pdf->SetXY($x3 , $y3 + 10);
        $pdf->Cell($ancho3, $alto3, $numOS, $borde, 0, 'C', 0, 0, 1, false, 'T', 'T');
        $pdf->SetFont('times', 'B', 6);
        $pdf->SetXY($x3 , $y3 + 18);
        $pdf->Cell($ancho3, $alto3, $peso, $borde, 0, 'C', 0, 0, 1, false, 'T', 'T');


        $y4 = $yi + 15;
        $ancho4 = 18;
        $alto4 = 3;
        $pdf->SetXY($xi + 112, $y4);
        $pdf->Cell($ancho4, $alto3, $ciuOrigen, $borde, 0, 'C', 0, 0, 1, false, 'T', 'T');
        $pdf->SetXY($xi + 112, $y4 + 10);
        $pdf->Cell($ancho4, $alto3, $ciuDesti, $borde, 0, 'C', 0, 0, 1, false, 'T', 'T');




//        $pdf->Text(($xi + 1), ($yi + 22), $num);
//        $pdf->Text(($xi + 1), ($yi + 27), 'Remite:');
//        $pdf->Text(($xi + 1), ($yi + 32), $remitente);
//        $pdf->Text(($xi + 1), ($yi + 37), 'Destinartario:');
//        $pdf->Text(($xi + 1), ($yi + 42), $detinatario);
//        $pdf->Text(($xi + 1), ($yi + 47), $extra);
//        $pdf->Text(($xi + 1), ($yi + 22), $dir);
//        $pdf->Text(($xi + 1), ($yi + 22), $ciuDesti);
//        $pdf->Text(($xi + 1), ($yi + 22), $depDesti);
//        $pdf->Text(($xi + 1), ($yi + 22), $fecha);
        // $pdf->Text($x, $y, $txt, $fstroke, $fclip, $ffill, $border, $ln, $align, $fill);
        //muestro la primera tabla
        //   $pdf->writeHTMLCell(32, '', $xi + 1, $yi + 22, $tabla1, 0, 0, 1, true, 'J', true);
        //tabla 2
        //  $pdf->writeHTMLCell(53, '', $xi + 35, $yi + 15, $tabla2, 0, 0, 1, true, 'J', true);
        //tabla 3
        //   $pdf->writeHTMLCell(26, '', $xi + 35, $yi + 45, $tabla3, 0, 0, 1, true, 'J', true);
        //tabla 4
        //  $pdf->writeHTMLCell(26, '', $xi + 89, $yi + 15, $tabla4, 0, 0, 1, true, 'J', true);
        //tabla 5
        //  $pdf->writeHTMLCell(26, '', $xi + 115, $yi + 15, $tabla5, 0, 0, 1, true, 'J', true);

        $cuenta++;
        if ($cuenta == 2)
        {
            //$yi+=(72+($fila*2));
            if($fila==1)
            {
                $yi=73;
            }
            if($fila==2)
            {
                $yi=145;
            }
            
            $cuenta = 0;
            $fila++;
        }
        if ($xi == 10)
            $xi = 149;
        else
            $xi = 10;
    }
    $inicial = $con;
    if ($inicial < $total)
    {
        $pdf->AddPage();
    }
}




// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// move pointer to last page
//$pdf->lastPage();
// ---------------------------------------------------------
//Close and output PDF document
$pdf->Output('example_005.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
//lo cierro automaticamente
echo('<script>opener.location.reload(true);
   self.close();</script>');

function getDatosGuia($id, $guias)
{
    mysql_query("SET NAMES 'utf8'");
    //echo('nada');
    $result = mysql_query("SELECT numero_guia,   nombre_destinatario_guia, g.destinatarioInfo,
 direccion_destinatario_guia, telefono_destinatario_guia, peso_guia, DATE(fecha) AS fecha, referencia,
 t.`nombres_tercero`, t.`apellidos_tercero`, t.documento_tercero,
 c.`nombre_ciudad` AS ciuOrigen, cd.`nombre_ciudad` AS ciuDesti, dd.`nombre_departamento` AS depDesti,
 os.`numero_orden_servicio`
FROM guia g 
INNER JOIN  tercero t ON t.`idtercero` = g.`tercero_idremitente`
INNER JOIN ciudad c ON c.`idciudad` = g.`ciudad_idorigen`
INNER JOIN ciudad cd ON cd.`idciudad` = g.`ciudad_iddestino`
INNER JOIN departamento dd ON dd.`iddepartamento` = cd.`departamento_iddepartamento`
INNER JOIN orden_servicio os ON os.`idorden_servicio` = g.`orden_servicio_idorden_servicio`
WHERE g.`orden_servicio_idorden_servicio` = $id
");
    if (!$result)
    {
        die(mysql_error());
    }
    $cont = 0;
    //echo('paso');
    while (($row = mysql_fetch_array($result)) != NULL)
    {
        $obj = new Guia($row['numero_guia'], -1, -1);

        $obj->setNombreDestinatario($row['nombre_destinatario_guia']);
        $obj->setDireccion($row['direccion_destinatario_guia']);
        $obj->setTelefonoDesti($row['telefono_destinatario_guia']);
        $obj->setPeso(120);
        $obj->setFecha($row['fecha']);
        $obj->setReferencia($row['referencia']);
        $obj->setNombreRemitente($row['nombres_tercero'] . ' ' . $row['apellidos_tercero']);
        $obj->setCiuOrigen($row['ciuOrigen']);
        $obj->setCiuDesti($row['ciuDesti']);
        $obj->setDepDesti($row['depDesti']);
        $obj->setDestinatarioInfo($row['destinatarioInfo']);
        $obj->setIdRemitente($row['documento_tercero']);
        $obj->setNumeroOrdenSer($row['numero_orden_servicio']);
        //$obj->show();
        $guias[$cont] = $obj;
        $cont++;
    }
    // echo('salio');
    mysqli_free_result($result);
}
//deprecated
function fillTable($objGuia)
{
//       $objGuia = new Guia($num, $ciu, $dep);
    //aca voy a retornar las tablas a mostrar
    $rta = new ArrayObject();



    $num = $objGuia->getNumero();
    $remitente = $objGuia->getNombreRemitente();
    $detinatario = $objGuia->getNombreDestinatario();
    $dir = $objGuia->getDireccion();
    $ciuDesti = $objGuia->getCiuDesti();
    $depDesti = $objGuia->getDepDesti();
    $fecha = $objGuia->getFecha();
    $peso = $objGuia->getPeso();

    $extra = $objGuia->getDestinatarioInfo();
    $telDesti = $objGuia->getTelefonoDesti();
    $docuRemi = $objGuia->getIdRemitente();
    $numOS = $objGuia->getNumeroOrdenSer();

    $ciuOrigen = $objGuia->getCiuOrigen();
    // $objGuia->show();
    //tabla 1


    $rta[0] = <<<EOD
<table>
<tr>
    <td>$num</td>
</tr>
<tr>
    <td>Remite:</td>
</tr>
<tr>
    <td>$remitente</td>
</tr>
<tr>
    <td>Destinartario:</td>
</tr>
<tr>
    <td>$detinatario</td>
</tr>
<tr>
    <td>$extra</td>
</tr>
<tr>
    <td>$dir</td>
<tr>
    <td>$ciuDesti</td>
</tr>
<tr>
    <td>$depDesti</td>
</tr>
<tr>
    <td>$fecha</td>
</tr>
</table>
EOD;

    $rta[1] = <<<EOD
                <table>
<tr>
    <td>$remitente</td>
</tr>
<tr>
    <td></td>
</tr>
<tr>
    <td>$detinatario</td>
</tr>
<tr>
    <td>$extra</td>
<tr>
    <td>$dir - $telDesti</td>
</tr>
<tr>
    <td>$ciuDesti - $depDesti</td>
</tr>
</table>

EOD;

    $rta[2] = <<<EOD
                <table>
<tr>
    <td>$fecha</td>
</tr>
</table>

EOD;


    $rta[3] = <<<EOD
                <table>
<tr>
    <td>$docuRemi</td>
</tr>
<tr>
    <td></td>
</tr>
<tr>
    <td>$numOS</td>
</tr>
<tr>
    <td></td>
</tr>
<tr>
    <td>$peso</td>
</tr>
</table>

EOD;

    $rta[4] = <<<EOD
                <table>
<tr>
    <td>$ciuOrigen</td>
</tr>
<tr>
    <td></td>
</tr>
<tr>
    <td>$ciuDesti</td>
</tr>
</table>

EOD;




    return $rta;
}

?>