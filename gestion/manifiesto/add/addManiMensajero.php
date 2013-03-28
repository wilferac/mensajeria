<?php
  session_start();
  include '../../../security/User.php';
  include '../../../clases/Mensajero.php';
  include '../../../clases/DaoMensajero.php';
  // include '../../../conexion/conexion.php';

  $objUser = unserialize($_SESSION['currentUser']);

  if ($objUser->getStatus() != 1)
  {
      //$operacion->redireccionar('No Puede entrar', 'index.php');
      return;
  }

  $option = $_REQUEST['option'];

  switch ($option)
  {
      case 0:
          showOption();
          break;
      case 1:
          showData($objUser);
          break;
  }

  function showOption()
  {
      echo('<h3>Tipo de Mensajero</h3>
          <button class="btnMensajero" style=" width: 90px;" href="addManiMensajero.php?option=1&tipo=5">Propio</button>
          <button class="btnMensajero" style=" width: 90px;" href="addManiMensajero.php?option=1&tipo=8">Destajo</button>');
      echo("<script type='text/javascript'>
          $(document).ready(function() {
            $('.btnMensajero').click(function(event) {
            event.preventDefault();
            $('#response2').load($(this).attr('href'));
            });
          });
          </script>");
  }

  //lo pongo a escojer entre destajo y propio
  //filtro con u objeto del tipo usuario
  function showData($objUser)
  {
      $tipo = $_REQUEST['tipo'];
      
      // el id de la sucursal
      $idSucur=$objUser->getIdSucursal();

      $dao = new DaoMensajero();
      $arrayMensajeros = $dao->getAll($idSucur, $tipo);
      
              echo("<select>");
      echo("<option value='-1'>Seleccione</option>");
      foreach ($arrayMensajeros as $objMen)
      {
          //$objMen->show();
          echo("<option>");
          echo($objMen->getNombre());
          echo("</option>");
      }
      echo("</select>");
  }
?>
