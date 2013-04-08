<?php

   session_start();


   include '../../../security/User.php';
   require_once('../../../libreria/tcpdf/tcpdf.php');

   $objUser = unserialize($_SESSION['currentUser']);

   if ($objUser->getStatus() != 1)
   {
       $operacion->redireccionar('No Puede entrar', 'index.php');
       return;
   }
   if (!$objUser->checkRol("Usuario"))
   {
       die("No tienes permiso");
   }


   $option = $_REQUEST['option'];
   $idMani = $_REQUEST['id'];


   switch ($option)
   {
       case 0:
           printResumido($idMani);
           break;
       case 1:
           printCompleto($idMani);
           break;
   }

   function printResumido($idMani)
   {
       echo("imprimiendo Resumido... " . $idMani);
       $terceros = consultarTerceros($idMani);

       echo("<br>");
       echo("creador: " . $terceros[1]);
       echo("<br>");
       echo("entrega: " . $terceros[2]);
       echo("<br>");
       echo("resive: " . $terceros[3]);
       echo("<br>");
       echo("aliado: " . $terceros[4]);
       echo("<br>");
       echo("sucursal: " . $terceros[0]);
       echo("<br>");
       echo("fecha: " . $terceros[5]);
       echo("<br>");
       echo("zona: " . $terceros[6]);

       $tabla1 = "<table>
           <tr>
            <td>Manifiesto No: $idMani</td>
           </tr>";
       $tabla1 = $tabla1."
           <tr>
            <td>Entrega: $terceros[2]</td>
           </tr>";
       if(isset($terceros[3]))
       {
            $tabla1 = $tabla1."
           <tr>
            <td>Recibe: $terceros[2]</td>
           </tr>";
       }
       if(isset($terceros[3]))
       {
            $tabla1 = $tabla1."
           <tr>
            <td>Recibe: $terceros[2]</td>
           </tr>";
       }
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
   }

   function printCompleto($idMani)
   {
       echo("imprimiendo completo... " . $idMani);
   }

   /**
    * Esta funcion realiza la consulta de los terceros relacionado con un manifiesto
    * 
    * @param int $id el id del manifiesto a consultar
    * @return array retorna un array con los resultados de la query
    */
   function consultarTerceros($id)
   {
       $conTerceros = "SELECT s.nombre_sucursal ,m.idmanifiesto ,m.sucursal_idsucursal, GROUP_CONCAT(t.apellidos_tercero SEPARATOR ', ') AS apellidos,  GROUP_CONCAT(t.nombres_tercero SEPARATOR ',') AS tercero, GROUP_CONCAT(tm.tipo SEPARATOR ',')  AS tipo,
	m.`fechaCreacion` ,  z.`nombre_zona`
       FROM manifiesto m INNER JOIN tercero_manifiesto tm ON tm.idmanifiesto = m.idmanifiesto 
       INNER JOIN tercero t ON t.idtercero= tm.idtercero 
       LEFT JOIN sucursal s ON s.idsucursal = m.sucursal_idsucursal       
       LEFT JOIN zona z ON z.`idzona` = m.`zonamensajero`
	WHERE m.`idmanifiesto` = $id
       GROUP BY m.idmanifiesto ";

       $res2 = mysql_query($conTerceros);

       while ($filas = mysql_fetch_assoc($res2))
       {
           $apellidos = $filas['apellidos'];
           $numero_manifiesto = $filas['idmanifiesto'];
           $nombreTerceros = $filas['tercero'];
           $tiposTerceros = $filas['tipo'];

           $cont = 0;
           $nombre = new ArrayObject();
           $apellido = new ArrayObject();
           $tipo = "";

           $nombre[0] = $filas['nombre_sucursal'];

           $idmanifiesto = $filas['idmanifiesto'];

           do
           {
               $tipo = strtok($tiposTerceros, ',');
               $nombre[$tipo] = strtok($nombreTerceros, ',');
               $nombre[$tipo] = $nombre[$tipo] . ' ' . strtok($apellidos, ',');

               $pos = stripos($nombreTerceros, ',');
               $nombreTerceros = substr($nombreTerceros, $pos + 1);
               $pos = stripos($tiposTerceros, ',');
               $tiposTerceros = substr($tiposTerceros, $pos + 1);
               $pos = stripos($apellidos, ',');
               $apellidos = substr($apellidos, $pos + 1);



               $cont++;
           } while (strlen($tiposTerceros) > 0 && $cont < 4);

           $nombre[5] = $filas['fechaCreacion'];
           $nombre[6] = $filas['nombre_zona'];
       }

       return $nombre;
   }

   function generarPdf($tabla1, $tabla2)
   {
       $pdf = new TCPDF('l', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

       $pdf->SetCreator(PDF_CREATOR);
       $pdf->SetAuthor('A1');
       $pdf->SetTitle('Guia');

       $pdf->setPrintHeader(false);
       $pdf->setPrintFooter(false);
       $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
       $pdf->SetMargins(5, 1, 0);

       $pdf->SetAutoPageBreak(TRUE, 0);


       $pdf->SetFont('times', '', 10);

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


       $pdf->setCellMargins(1, 1, 1, 1);

       $pdf->SetFillColor(255, 255, 255);

       $espacio = 32;

//
//       $xi = 0;
//       $yi = 0;
//       $cuenta = 0;


       $pdf->writeHTMLCell(250, '', '', '', $tabla1, 1, 0, 1, true, 'J', true);
       //tabla 2
       $pdf->writeHTMLCell(250, '', '', '', $tabla2, 1, 0, 1, true, 'J', true);


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
   }

?>
