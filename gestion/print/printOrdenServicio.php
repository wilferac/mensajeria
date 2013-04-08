<?php

   session_start();
   require("../../conexion/conexion.php");
   require_once('../../libreria/tcpdf/tcpdf.php');
   require("../../clases/Guia.php");


   $idOrdenServicio = $_REQUEST["id"];

   //echo($idOrdenServicio);

   $guias = new ArrayObject();

   getDatosGuia($idOrdenServicio, $guias);
//   return;
//   $datosGuia = getDatosGuia($idGuia);
   //lo horiento horizontalmente 'l'
   $pdf = new TCPDF('l', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//$pdf->setPageOrientation(PDF_PAGE_ORIENTATION);
// set document information
   $pdf->SetCreator(PDF_CREATOR);
   $pdf->SetAuthor('A1');
   $pdf->SetTitle('Guia');

   $pdf->setPrintHeader(false);
   $pdf->setPrintFooter(false);
   $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
   $pdf->SetMargins(5, 1, 0);
//set margins
//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

   //auto salto de linea a false para hacerlo manualmente
   $pdf->SetAutoPageBreak(FALSE, 0);



// ---------------------------------------------------------
// set font
   $pdf->SetFont('times', '', 10);

// add a page
   $pdf->AddPage();

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
       'fontsize' => 8,
       'stretchtext' => 4
   );


// set cell margins
   $pdf->setCellMargins(0, 0, 0, 0);

// set color for background
   $pdf->SetFillColor(255, 255, 255);





//   $tabla2 = "
//<table >
//<tr>
//    <td width=\"65\">PRODUCTO:</td>
//    <td width=\"80\">" . $datosGuia["producto"] . "</td>
//</tr> 
//<tr>
//    <td>CONTENIDO:</td>
//    <td>" . substr($datosGuia["contenido"], 0, 32) . "</td>
//</tr>
//<tr>
//    <td>VR. DECLA:</td>
//    <td>" . $datosGuia["vDeclarado"] . "</td>
//</tr>
//   
//<tr>
//    <td>FLETE:</td>
//    <td>" . $datosGuia["flete"] . "</td>
//</tr>
//<tr>
//    <td>PRIMA:</td>
//    <td>" . $datosGuia["prima"] . "</td>
//</tr>
//<tr>
//    <td>TOTAL:</td>
//    <td></td>
//</tr>
//</table>
//
//";
//
//   $tabla3 = "
//    <table >
//<tr>
//    <td width=\"48\">FECHA:</td>
//    <td width=\"82\">" . $datosGuia["fecha"] . "</td>
//</tr>
//<tr>
//    <td>Ref:</td>
//    <td>" . $datosGuia["referencia"] . "</td>
//</tr>
//<tr>
//    <td>C.C/NIT:</td>
//    <td>" . $datosGuia["remiDocu"] . "</td>
//</tr>
//<tr>
//    <td>C.C/NIT:</td>
//    <td><b>" . $datosGuia["destiDocu"] . "</b></td>
//</tr>
//<tr>
//    <td>PESO:</td>
//    <td><b>" . $datosGuia["peso"] . "</b></td>
//</tr>
//<tr>
//    <td>TAMAÑO:</td>
//    <td><b>" . round($datosGuia["largo"]) . "x" . round($datosGuia["ancho"]) . "x" . round($datosGuia["alto"]) . "</b></td>
//</tr>
//</table>";

   $espacio = 32;
   //$disminuir = 0.1;
   //$pdf->Ln(1);
   //$posBarra = 1;

   $xi = 0;
   $yi = 0;
   $cuenta = 0;

   for ($con = 0; $con < 6; $con++)
   {
       $arre = fillTable($guias[$con]);
       $tabla1 = $arre[0];
       $tabla2 = $arre[1];
       $tabla3 = $arre[2];
       $tabla4 = $arre[3];
       $tabla5 = $arre[4];

       //$y = $pdf->getY();
       //$style['position'] = 'R';
       //muestro el codigo de barras
       $pdf->write1DBarcode($guias[$con]->getNumero(), 'C128', $xi + 110, $yi, 50, 14, 0.4, $style, '');
       //muestro la primera tabla
       $pdf->writeHTMLCell(32, '', $xi + 1, $yi + 22, $tabla1, 1, 0, 1, true, 'J', true);
       //tabla 2
       $pdf->writeHTMLCell(53, '', $xi + 35, $yi + 15, $tabla2, 1, 0, 1, true, 'J', true);
       //tabla 3
       $pdf->writeHTMLCell(26, '', $xi + 35, $yi + 45, $tabla3, 1, 0, 1, true, 'J', true);
       //tabla 4
       $pdf->writeHTMLCell(26, '', $xi + 89, $yi + 15, $tabla4, 1, 0, 1, true, 'J', true);
       //tabla 5
       $pdf->writeHTMLCell(26, '', $xi + 115, $yi + 15, $tabla5, 1, 0, 1, true, 'J', true);




       $cuenta++;
       if ($cuenta == 2)
       {
           $yi+=70;
           $cuenta = 0;
       }

       if ($xi == 0)
           $xi = 145;
       else
           $xi = 0;

       //$pdf->Ln(1);
//       $x = $pdf->getX();
//       $pdf->writeHTMLCell(52, '', $x, $posBarra + 12, $tabla2, 0, 0, 1, true, 'J', true);
//       $x = $pdf->getX();
//       $pdf->writeHTMLCell(47, '', $x, $posBarra + 12, $tabla3, 0, 1, 1, true, 'J', true);
       //$pdf->MultiCell(29, 40, $datos, 1, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
       //pdf->MultiCell(65, 40, $datosFill, 1, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
       //$pdf->MultiCell(40, 40, $txt, 1, 'L', 1, 0, '', '', true, 0, false, true, 40, 'T');
       //$pdf->MultiCell(17, 40, $fecha, 1, 'L', 1, 1, '', '', true, 0, false, true, 40, 'T');
       //$pdf->MultiCell(30, 40, $txt, 1, 'L', 1, 1, '', '', true, 0, false, true, 40, 'T');
       //$espacio-=$disminuir;
       //$disminuir+=1.8;
       //$pdf->Ln($espacio);
   }

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// move pointer to last page
   $pdf->lastPage();



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
       //echo('nada');
       $result = mysql_query("SELECT numero_guia,   nombre_destinatario_guia, g.destinatarioInfo,
 direccion_destinatario_guia, telefono_destinatario_guia, peso_guia, DATE(fecha) as fecha, referencia,
 t.`nombres_tercero`, t.`apellidos_tercero`, t.documento_tercero,
 c.`nombre_ciudad` AS ciuOrigen, cd.`nombre_ciudad` AS ciuDesti, dd.`nombre_departamento` AS depDesti
FROM guia g 
INNER JOIN  tercero t ON t.`idtercero` = g.`tercero_idremitente`
INNER JOIN ciudad c ON c.`idciudad` = g.`ciudad_idorigen`
INNER JOIN ciudad cd ON cd.`idciudad` = g.`ciudad_iddestino`
INNER JOIN departamento dd ON dd.`iddepartamento` = cd.`departamento_iddepartamento`
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
           $obj->setNumeroOrdenSer($id);
           //$obj->show();
           $guias[$cont] = $obj;
           $cont++;
       }
       // echo('salio');
       mysqli_free_result($result);
   }

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