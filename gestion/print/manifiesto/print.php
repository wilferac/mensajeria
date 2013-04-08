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
       //genero la informacion basica
       $terceros = consultarTerceros($idMani);

       //genero la informacion resumida
       $consulta="";
       
       
       //genero el pdf
       generarPdf($terceros, "muhahaha");

   }

   function printCompleto($idMani)
   {
       //genero la informacion basica
       $terceros = consultarTerceros($idMani);

       //genero la informacion resumida
       $consulta="";
       
       
       //genero el pdf
       generarPdf($terceros, "muhahaha");
   }

   
   function consultarGuias()
   {
       
   }
   
   /**
    * Esta funcion realiza la consulta de los terceros relacionado con un manifiesto
    * 
    * @param int $id el id del manifiesto a consultar
    * @return string retorna un string (tabla html) con la informacion de los terceros de la guia.
    * la tabla estara lista para salir en el pdf
    */
   function consultarTerceros($id)
   {
       $conTerceros = "SELECT s.nombre_sucursal ,m.idmanifiesto ,m.sucursal_idsucursal, GROUP_CONCAT(t.apellidos_tercero SEPARATOR ', ') AS apellidos,  GROUP_CONCAT(t.nombres_tercero SEPARATOR ',') AS tercero, GROUP_CONCAT(tm.tipo SEPARATOR ',')  AS tipo,
	m.`fechaCreacion` ,  z.`nombre_zona` , c1.`nombre_ciudad` as origen , c2.`nombre_ciudad` as destino
       FROM manifiesto m INNER JOIN tercero_manifiesto tm ON tm.idmanifiesto = m.idmanifiesto 
       INNER JOIN tercero t ON t.idtercero= tm.idtercero 
       LEFT JOIN sucursal s ON s.idsucursal = m.sucursal_idsucursal       
       LEFT JOIN zona z ON z.`idzona` = m.`zonamensajero`
       INNER JOIN ciudad c1 ON c1.`idciudad` = m.`ciudadOrigen`
       INNER JOIN ciudad c2 ON c2.`idciudad` = m.`ciudadDestino`
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
           $nombre[7] = $filas['origen'];
           $nombre[8] = $filas['destino'];
       }

       $tabla1 = "<table>
           <tr>
            <td>Manifiesto No: $id ($nombre[5])</td>
           </tr>";
       $tabla1 = $tabla1 . "
           <tr>
            <td>Entrega: $nombre[2]</td>
           </tr>";
       if (isset($nombre[3]))
       {
           $tabla1 = $tabla1 . "
           <tr>
            <td>Recibe: $nombre[3]</td>
           </tr>";
       }
       if (isset($nombre[0]))
       {
           $tabla1 = $tabla1 . "
           <tr>
            <td>Para Sucursal: $nombre[0]</td>
           </tr>";
       }
       if (isset($nombre[4]))
       {
           $tabla1 = $tabla1 . "
           <tr>
            <td>Para Aliado: $nombre[4]</td>
           </tr>";
       }
       $tabla1 = $tabla1 . "
           <tr>
            <td>Origen: $nombre[7]</td>
           </tr>";

       if (isset($nombre[8]))
       {
           $tabla1 = $tabla1 . "
           <tr>
            <td>Destino: $nombre[8]</td>
           </tr>";
       }
       if (isset($nombre[6]))
       {
           $tabla1 = $tabla1 . "
           <tr>
            <td>Zona: $nombre[6]</td>
           </tr>";
       }
       $tabla1 = $tabla1 . "
           <tr>
            <td>Creado por: $nombre[1]</td>
           </tr>";
       $tabla1 = $tabla1 . "</table>";

       return $tabla1;
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
       $pdf->SetMargins(18, 15, 15);

       $pdf->SetAutoPageBreak(TRUE, 0);


       $pdf->SetFont('times', '', 20);

       $pdf->AddPage();

//       $style = array(
//           'stretch' => false,
//           'fitwidth' => true,
//           'cellfitalign' => '',
//           'border' => false,
//           'hpadding' => 'auto',
//           'vpadding' => 'auto',
//           'fgcolor' => array(0, 0, 0),
//           'bgcolor' => false, //array(255,255,255),
//           'text' => true,
//           'font' => 'helvetica',
//           'fontsize' => 8,
//           'stretchtext' => 4
//       );
       //$pdf->setCellMargins(1, 1, 1, 1);

       $pdf->SetFillColor(255, 255, 255);

       //$espacio = 32;

//
//       $xi = 0;
//       $yi = 0;
//       $cuenta = 0;


       $pdf->writeHTMLCell(260, '', '', '', $tabla1, 1, 1, 1, true, 'J', true);
       //tabla 2
       $pdf->Ln();
       $pdf->writeHTMLCell(260, '', '', '', $tabla2, 1, 0, 1, true, 'J', true);


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// move pointer to last page
       $pdf->lastPage();



// ---------------------------------------------------------
//Close and output PDF document
       $pdf->Output('resumido.pdf', 'D');

//============================================================+
// END OF FILE
//============================================================+
//lo cierro automaticamente
       echo('<script>opener.location.reload(true);
   self.close();</script>');
   }
   
   
   

?>
