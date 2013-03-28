<?php

   session_start();
   require("../../../conexion/conexion.php");
   require_once('../../../libreria/tcpdf/tcpdf.php');


   $idGuia = $_REQUEST["idGuia"];

   $datosGuia = getDatosGuia($idGuia);
//print_r($datosGuia);
//echo($datosGuia);
//return;
// create new PDF document
   $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

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
//set auto page breaks
   $pdf->SetAutoPageBreak(TRUE, 0.5);



// ---------------------------------------------------------
// set font
   $pdf->SetFont('times', '', 10);

// add a page
   $pdf->AddPage();

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



//$pdf->Ln();
// set cell padding
//$pdf->setCellPaddings(1, 1, 1, 1);
// set cell margins
   $pdf->setCellMargins(0.5, 1, 0.5, 1);

// set color for background
//$pdf->SetFillColor(255, 255, 127);
// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
// set some text for example
// set color for background
   $pdf->SetFillColor(255, 255, 255);



   $tabla1 = "
    <table >
<tr>
    <td width=\"80\">REMITENTE:</td>
    <td width=\"197\">" . $datosGuia['remiNom'] . " " . $datosGuia['remiApel'] . $datosGuia["remiInfo"]."</td>
</tr>
<tr>
    <td>DIRECCION:</td>
    <td>" . $datosGuia["remiDir"] . "</td>
</tr>
<tr>
    <td>CIUDAD:</td>
    <td>" . $datosGuia["remiCiu"] . "/" . $datosGuia["remiDep"] . "</td>
</tr>
<tr>
    <td>DESTINATARIO:</td>
    <td><b>" . $datosGuia["destiNom"] . $datosGuia["destiInfo"]."</b></td>
</tr>
<tr>
    <td>DIRECCION:</td>
    <td><b>" . $datosGuia["destiDir"] . "</b></td>
</tr>
<tr>
    <td>CIUDAD:</td>
    <td><b>" . $datosGuia["destiCiu"] . "</b>/" . $datosGuia["destiDep"] . "</td>
</tr>
<tr>
    <td>TELEFONO:</td>
    <td><b>" . $datosGuia["destiTel"] . "</b></td>
</tr>
</table>";

   $tabla2 = "
<table >
<tr>
    <td width=\"65\">PRODUCTO:</td>
    <td width=\"80\">" . $datosGuia["producto"] . "</td>
</tr> 
<tr>
    <td>CONTENIDO:</td>
    <td>".substr($datosGuia["contenido"],0,32) ."</td>
</tr>
<tr>
    <td>VR. DECLA:</td>
    <td>" . $datosGuia["vDeclarado"] . "</td>
</tr>
   
<tr>
    <td>FLETE:</td>
    <td>".$datosGuia["flete"] ."</td>
</tr>
<tr>
    <td>PRIMA:</td>
    <td>".$datosGuia["prima"] ."</td>
</tr>
<tr>
    <td>TOTAL:</td>
    <td></td>
</tr>
</table>

";

   $tabla3 = "
    <table >
<tr>
    <td width=\"48\">FECHA:</td>
    <td width=\"82\">".$datosGuia["fecha"] ."</td>
</tr>
<tr>
    <td>Ref:</td>
    <td>".$datosGuia["referencia"] ."</td>
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
    <td><b>".round($datosGuia["largo"])."x".round($datosGuia["ancho"])."x".round($datosGuia["alto"])."</b></td>
</tr>
</table>";

   $espacio = 32;
   //$disminuir = 0.1;
   //$pdf->Ln(1);
   $posBarra=1;
   for ($con = 0; $con < 4; $con++)
   {
       $y = $pdf->getY();
       $style['position'] = 'R';
       $pdf->write1DBarcode($datosGuia["nGuia"], 'C128', 120, $posBarra, '', 14, 0.4, $style, 'N');
       
       $pdf->Ln(1);
       $pdf->writeHTMLCell(99, '', '', $posBarra + 9, $tabla1, 0, 0, 1, true, 'J', true);
       $x = $pdf->getX();
       $pdf->writeHTMLCell(52, '', $x, $posBarra + 12 , $tabla2, 0, 0, 1, true, 'J', true);
       $x = $pdf->getX();
       $pdf->writeHTMLCell(47, '', $x, $posBarra + 12 , $tabla3, 0, 1, 1, true, 'J', true);
       
       $posBarra+=77;
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


   echo('<script>opener.location.reload(true);
   self.close();</script>');

   function getDatosGuia($id)
   {
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
where g.idguia = $id and documento_destinatario is not null and g.causal_devolucion_idcausal_devolucion <> 3
";

       $results2 = mysql_query($query2) or die(mysql_error());

       $res = array();

       if ($fila = mysql_fetch_assoc($results2))
       {
           $res["largo"] = $fila["largo"];
           $res["ancho"] = $fila["ancho"];
           $res["alto"] = $fila["alto"];
           
           
           $res["referencia"] = $fila["referencia"];
           $res["fecha"] = $fila["fecha"];
           
           
           $res["flete"] = $fila["flete"];
           $res["prima"] = $fila["prima"];
           $res["contenido"] = $fila["contenido"];
           
           
           
           $res["remiInfo"] = $fila["remitenteInfo"];
           $res["remiDocu"] = $fila["documento_tercero"];
           $res["remiNom"] = $fila["nombres_tercero"];
           $res["remiApel"] = $fila["apellidos_tercero"];
           $res["remiDir"] = $fila["direccion_tercero"];
           $res["remiCiu"] = $fila["ciudad_nombreorigen"];
           $res["remiDep"] = $fila["dptoOrigen"];
           if($res["remiInfo"] != "" and $res["remiInfo"] != NULL)
           {
               $res["remiInfo"]="-".$res["remiInfo"];
           }

           $res["destiInfo"] = $fila["destinatarioInfo"];
           $res["destiDocu"] = $fila["documento_destinatario"];
           $res["destiNom"] = $fila["nombre_destinatario_guia"];
           $res["destiTel"] = $fila["telefono_destinatario_guia"];
           $res["destiDir"] = $fila["direccion_destinatario_guia"];
           $res["destiCiu"] = $fila["ciudad_nombredestino"];
           $res["destiDep"] = $fila["dptoDestino"];
           if($res["destiInfo"] != "" and $res["destiInfo"] != NULL)
           {
               $res["destiInfo"]="-".$res["destiInfo"];
           }

           $res["nGuia"] = $fila["numero_guia"];
           //$res["fecha"] = $fila["numero_guia"];
           //$res["ref"] = $fila["numero_guia"];
           $res["peso"] = $fila["peso_guia"];
           $res["producto"] = $fila["nombre_producto"] . "/" . $fila["nombre_tipo_producto"];
           $res["vDeclarado"] = $fila["valor_declarado_guia"];
           //$res["vFlete"] = $fila["numero_guia"];
           //$res["cargoManejo"] = $fila["numero_guia"];
           //este debe ser calculado aca :D
           $res["Total"] = 0;
       }
       else
       {
           echo('<script>
alert("esta Guia esta incompleta o entregada");            
opener.location.reload(true);
   self.close();</script>');
       }

       return $res;
   }

?>