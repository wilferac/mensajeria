<?php

  session_start();


  include '../../../security/User.php';
  include '../../../clases/Guia.php';
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
      $guias = consultarGuias($idMani);


      $ordenes = new ArrayObject();
      $clientes = new ArrayObject();
      $numeros = new ArrayObject();

      $entregadas = new ArrayObject();
      $devueltas = new ArrayObject();
      $enDestino = new ArrayObject();
      $enManifiesto = new ArrayObject();

///de aca voy a sacar los numero de las OS
      $numeros[0] = 0;

      $entregadas[0] = 0;
      $devueltas[0] = 0;
      $enDestino[0] = 0;
      $enManifiesto[0] = 0;
      $ordenes[0] = 0;
      $clientes[0] = "Unitario";
      //aca acumulo las guias por ordenes de servicio
      foreach ($guias as $guia)
      {
          //$guia = new Guia($num, $ciu, $dep);
          $numOs = $guia->getNumeroOrdenSer();
          if (empty($numOs))
          {
              $ordenes[0]++;
          }
          else
          {
              if (isset($ordenes[$numOs]))
              {
                  $ordenes[$numOs]++;
              }
              else
              {
                  $numeros[$numOs] = $numOs;
                  $clientes[$numOs] = $guia->getClienteNom();
                  $ordenes[$numOs] = 1;
                  $entregadas[$numOs] = 0;
                  $devueltas[$numOs] = 0;
                  $enDestino[$numOs] = 0;
                  $enManifiesto[$numOs] = 0;
              }
              $estado = $guia->getIdEstado();
              switch ($estado)
              {
                  case 2:
                      $devueltas[$numOs]++;
                      break;
                  case 3:
                      $entregadas[$numOs]++;
                      break;
                  case 4:
                      $enManifiesto[$numOs]++;
                      break;
                  case 5:
                      $enDestino[$numOs]++;
                      break;
              }
          }
      }

      $table2 = "<table border=\"1\" cellpadding=\"10\">
           <tr>
           <td>N. Orden</td>
           <td>Cliente</td>
           <td>Cantidad</td>
           <td>Entregadas</td>
           <td>Devueltas</td>
           <td>En Manifiesto</td>
           <td>En Destino</td>
           </tr>";

      foreach ($numeros as $num)
      {
          if ($ordenes[$num] <= 0)
          {
              continue;
          }
          $table2 = $table2 . "
               <tr>
               <td>$num</td>
               <td>$clientes[$num]</td>
               <td>$ordenes[$num]</td>
               <td>$entregadas[$num]</td>
               <td>$devueltas[$num]</td>
               <td>$enManifiesto[$num]</td>
               <td>$enDestino[$num]</td>
               </tr>";
      }
      $table2 = $table2."</table>";

      //genero el pdf
      generarPdf($terceros, $table2);
  }

  function printCompleto($idMani)
  {
      //genero la informacion basica
      $terceros = consultarTerceros($idMani);

      //genero la informacion resumida
      $guias = consultarGuias($idMani);

      $tabla2 = "<table border=\"1\" cellpadding=\"10\">
           <tr>
            <td>Guia N.</td>
            <td>Dirección</td>
            <td>Destinatario</td>
            <td>Estado</td>
           </tr>";

      foreach ($guias as $guia)
      {
          // $guia = new Guia($num, $ciu, $dep);
          $numOS = $guia->getNumeroOrdenSer();
          $num = $guia->getNumero() . ' ' . (!empty($numOS) ? '(' . $numOS . ')' : "");
          $dir = $guia->getDireccion();
          $desti = $guia->getNombreDestinatario();
          $estado = $guia->getEstado();

          $tabla2 = $tabla2 .
                  "<tr>
            <td>$num</td>
            <td>$dir</td>
            <td>$desti</td>
            <td>$estado</td>
           </tr>";
      }
      $tabla2 = $tabla2 . "</table>";

      //genero el pdf
      generarPdf($terceros, $tabla2);
  }

  function consultarGuias($idMani)
  {
      $consulta = "SELECT g.numero_guia, gm.gmId  , os.numero_orden_servicio, os.idorden_servicio ,
g.direccion_destinatario_guia, g.nombre_destinatario_guia , g.contenido, es.nombre_causal_devolucion, 
t.nombres_tercero|| ' '||t.apellidos_tercero AS clienteOs, gm.idEstadoGuia
FROM guia g INNER JOIN  guia_manifiesto gm ON  gm.guiId = g.numero_guia
INNER JOIN manifiesto m ON m.idmanifiesto= gm.gmId
INNER JOIN orden_servicio os ON g.orden_servicio_idorden_servicio = os.idorden_servicio
INNER JOIN estadoGuia es ON es.idcausal_devolucion = gm.idEstadoGuia
INNER JOIN tercero t ON t.idtercero = os.tercero_idcliente
WHERE gm.manId =  $idMani ";


      if(!$res2 = mysql_query($consulta))
      {
          die(mysql_error());
      }
      $res = new ArrayObject();
      $cont = 0;
      while ($filas = mysql_fetch_assoc($res2))
      {
          $objGuia = new Guia($filas['numero_guia'], -1, -1);
          $objGuia->setDireccion($filas['direccion_destinatario_guia']);
          $objGuia->setNombreDestinatario($filas['nombre_destinatario_guia']);
          $objGuia->setEstado($filas['nombre_causal_devolucion']);
          $objGuia->setNumeroOrdenSer($filas['numero_orden_servicio']);
          $objGuia->setIdOrdenServi($filas['idorden_servicio']);
          $objGuia->setClienteNom($filas['clienteOs']);
          $objGuia->setIdEstado($filas['idEstadoGuia']);
          $res[$cont] = $objGuia;
          $cont++;
      }
      return $res;
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
       LEFT JOIN ciudad c1 ON c1.`idciudad` = m.`ciudadOrigen`
       LEFT JOIN ciudad c2 ON c2.`idciudad` = m.`ciudadDestino`
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
           </tr>
           <tr>
            <td></td>
           </tr>    
               ";
      $tabla1 = $tabla1 . "</table>";

      return $tabla1;
  }

  function generarPdf($tabla1, $tabla2)
  {
      $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

      $pdf->SetCreator(PDF_CREATOR);
      $pdf->SetAuthor('A1');
      $pdf->SetTitle('Guia');

      $pdf->setPrintHeader(false);
      $pdf->setPrintFooter(false);
      $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
      $pdf->SetMargins(18, 15, 15);

      $pdf->SetAutoPageBreak(TRUE, 0);


      $pdf->SetFont('times', '', 12);

      $pdf->AddPage();


      $pdf->SetFillColor(255, 255, 255);



      $pdf->writeHTMLCell(180, '', '', '', $tabla1, 0, 1, 1, true, 'J', true);
      //tabla 2
//       $pdf->Ln();
      $pdf->SetFont('times', '', 10);
      $pdf->writeHTMLCell(180, '', '', '', $tabla2, 0, 1, 1, true, 'J', true);

      $pdf->Ln(25);

      $firma = "Firma:_______________  C.C:_______________";

      $pdf->writeHTMLCell(180, '', '', 280, $firma, 0, 1, 1, true, 'C', true);

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
