<?

  include ("../../clases/clases.php");
  //include ("../../param/param.php");

//agrego la seguridad
  include "../../security/User.php";

  $objUser = unserialize($_SESSION['currentUser']);
  //$objUser = new User();
//        echo($objUser->getStatus());
  if ($objUser->getStatus() != 1)
  {
      //$objUser->show();
      $operacion->redireccionar('No Puede entrar', 'index.php');
      return;
  }

  $numero_guia = $_POST['numguia'];
  if ($objUser->checkRol("Cliente") && !$objUser->checkRol("Admin"))
  {
      $query = "LOCK TABLES guia WRITE, destinatario WRITE;";
      mysql_query($query) or die("<script>alert('No fue posible bloquear las tablas ERR(001)');</script>");


      //codigo para calcular el numero de guia :D

      $query2 = "SELECT numero_guia FROM guia  WHERE numero_guia LIKE 'CC%' ORDER BY idguia DESC LIMIT 1";
      $results2 = mysql_query($query2) or die(mysql_error());

      $nuevoN = 0;
      if ($fila = mysql_fetch_assoc($results2))
      {
          $nuevoN = trim($fila['numero_guia'], "C");
      }
      $nuevoN++;
      $numero_guia = "CC" . substr("0000000", 0, 7 - (strlen($nuevoN))) . $nuevoN;
  }


//print_r ($_POST);
  $encGuia = $_POST['encGuia'];
  $encAsig = $_POST['encAsig'];
  $encDestinatario = $_POST['encDestinatario'];
  $datoArecordar = $_POST['datoArecordar'];
  $resdesti = NULL;

  if (($encAsig && !$encGuia) || (!$encAsig && !$encGuia))
  {


      $iddestinatario = (isset($_POST['iddestinatario']) ? $_POST['iddestinatario'] : "");
      $ccdestinatario = $_POST['ccdestinatario'];
      $nombresdestinatario = $_POST['nombresdestinatario'];
      $apellidosdestinatario = $_POST['apellidosdestinatario'];
      $direcciondestinatario = $_POST['direcciondestinatario'];
      $telefono1destinatario = $_POST['telefono1destinatario'];
      $celulardestinatario = $_POST['celulardestinatario'];

      $nombres_terceroOrig = (isset($_POST['nombres_terceroOrig']) ? $_POST['nombres_terceroOrig'] : "");
      $apellidos_terceroOrig = (isset($_POST['apellidos_terceroOrig']) ? $_POST['apellidos_terceroOrig'] : "");
      $direccion_terceroOrig = (isset($_POST['direccion_terceroOrig']) ? $_POST['direccion_terceroOrig'] : "");
      $telefono_destinatarioOrig = (isset($_POST['telefono_destinatarioOrig']) ? $_POST['telefono_destinatarioOrig'] : "");
      $celular_destinatarioOrig = (isset($_POST['celular_destinatarioOrig']) ? $_POST['celular_destinatarioOrig'] : "");


      //  $peso = $_POST['peso'];
      $idcliente = $_POST['idcliente'];


      if ($encDestinatario)
      {
      }
      else
      {
          //TODO: aca debe estar el problema, no queda de otra mas que hacer una consulta para esto.

          $destinatario = new destinatario();

          $destinatario->tipo_identificacion_destinatario = 1;
          $destinatario->documento_destinatario = $ccdestinatario;
          $destinatario->nombres_destinatario = $nombresdestinatario;
          $destinatario->apellidos_destinatario = $apellidosdestinatario;
          $destinatario->direccion_destinatario = $direcciondestinatario;
          $destinatario->datos1 = $datoArecordar;
          if ($datoArecordar == $ccdestinatario)
              $destinatario->datos2 = $telefono1destinatario;
          else
              $destinatario->datos2 = $ccdestinatario;
          $destinatario->telefono_destinatario = $telefono1destinatario;
          $destinatario->telefono2_destinatario = '';
          $destinatario->celular_destinatario = $celulardestinatario;
          $destinatario->email_destinatario = '';
          $destinatario->observaciones_destinatario = '';

          $resdesti = $destinatario->agregar();
          $iddestinatario = $destinatario->iddestinatario;
      }


      $extraRemitente = $_POST['extraRemitente'];
      $extraDestinatario = $_POST['extraDestinatario'];
      $numReferencia = $_POST['numReferencia'];
      $largo = $_POST['largo'];
      $ancho = $_POST['ancho'];
      $alto = $_POST['alto'];


      

      $peso = $_POST['peso'];
      $idcliente = $_POST['idcliente'];
      //aca esta el bug de la ciudad =(
      // $idciudadorigen = $_SESSION['datosinicio']['ciudad_idciudad'];
      $idciudadorigen = $_POST['idciudadorigen2'];
      $ciudaddestino = $_POST['ciudaddestino'];

      $contenido = $_POST['contenido'];

      $idCliente = $idcliente;
      $creador = $objUser->getId();
      //en caso que sea un cliente :D
      if ($objUser->checkRol("Cliente"))
      {
          $idCliente = $creador;
      }
      //en caso de que el admin cree una guia... cosa que no deberia pasar -_-'
      if ($objUser->checkRol("Admin"))
      {
          $idCliente = $idcliente;
      }


      $nombreproducto = $_POST['nombreproducto'];
      $idtipoproducto = $_POST['idtipoproducto'];
      $valorDeclarado = $_POST['valordeclarado'];
      $idAsignacion = $_POST['idAsignacion'];
      $idAsignacion = trim($idAsignacion) != '' ? $idAsignacion: -1;
//en el registro temporal envio varios nulls
      $query = "
           CALL addGuia (
           $idCliente,$creador,NULL,  
               $idcliente, '$extraRemitente', $idciudadorigen,'$numReferencia',
                 $iddestinatario,'$nombresdestinatario', '$apellidosdestinatario','$telefono1destinatario',
$ciudaddestino, '$direcciondestinatario', '$extraDestinatario', 
               '$numero_guia', '$nombreproducto', $idtipoproducto, $valorDeclarado,
                 $peso,   '$contenido', $largo, $ancho, $alto,1 ,$idAsignacion
               )";
//      echo($query);
      mysql_query($query) or die("0");

      
//      echo($query);
      //return;

      if ($objUser->checkRol("Cliente") && !$objUser->checkRol("Admin"))
      {
          $query = "UNLOCK TABLES;";
          mysql_query($query) or die("0");
      }

      echo("1");
  }
?>