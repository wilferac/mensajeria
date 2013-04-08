<?php

  session_start();
  include '../../../security/User.php';
  include '../../../clases/Mensajero.php';
  include '../../../clases/DaoMensajero.php';
  include '../../../clases/Zona.php';
  include '../../../clases/DaoZona.php';
  include '../../../clases/Guia.php';
  include '../../../clases/DaoGuia.php';

  include '../../../clases/Manifiesto.php';
  include '../../../clases/DaoManifiesto.php';
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
      //add
      case 2:
          addGuia($objUser);
          break;
      //del
      case 3:
          delGuia();
          break;
      //guardar
      case 4:
          guardar($objUser);
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
            $('#response3').html('');
            $('#response4').html('');
            });
          });
          </script>");

      //resteo el arreglo de guias :D
      $arreGuias = new ArrayObject();
      $_SESSION['arregloGuias'] = serialize($arreGuias);
  }

  //lo pongo a escojer entre destajo y propio
  //filtro con u objeto del tipo usuario
  function showData($objUser)
  {
      $tipo = $_REQUEST['tipo'];

      // el id de la sucursal
      $idSucur = $objUser->getIdSucursal();
      $idCiu = $objUser->getIdCiudad();

      $daoMen = new DaoMensajero();
      $arrayMensajeros = $daoMen->getAll($idSucur, $tipo);

      $daoZona = new DaoZona();
      $arreZonas = $daoZona->getAll($idCiu);

      echo("<table><tr><td>");


      echo("Mensajero: </td><td> <select id='selMensajeroEntrega'>");
      echo("<option value='-1'>Seleccione</option>");
      foreach ($arrayMensajeros as $objMen)
      {
          $idMen = $objMen->getId();
          //$objMen->show();
          echo("<option value='$idMen'>");
          echo($objMen->getNombre());
          echo("</option>");
      }
      echo("</select></td></tr><tr><td>");

      echo("Zona: </td><td><select id='selZonaCiudad'>");
      echo("<option value='-1'>Seleccione</option>");
      foreach ($arreZonas as $objZon)
      {
          $idZona = $objZon->getId();
          //$objMen->show();
          echo("<option value='$idZona'>");
          echo($objZon->getNombre());
          echo("</option>");
      }
      echo('</select></td></tr>
          <tr><td>Plazo(N. dias):</td><td> <input name="plazo" type="number" id="plazo" size="10" require/>
          </td></tr>
          </table>');
      echo("<br /><br />");
      if ($tipo == 8)
      {
          echo('Tarifa: <input name="tarifa" type="text" id="tarifa" size="10" require/> ');
//          echo(' Plazo: <input name="plazo" type="text" id="plazo" size="10" require/>');
      }
      echo("<br /><br />");
      echo('Guia N.: <input name="guia" type="text" id="txtGuia" size="10" require/> ');

      echo("<script type='text/javascript'>$('#txtGuia').keypress(function(event) {
                    if(event.keyCode.toString()== '13')
                    {
                        var guiaNum = document.getElementById('txtGuia').value;
                        if(guiaNum == '')
                        {
                            return;
                        }
                        document.getElementById('txtGuia').value='';
                        event.preventDefault();
                        //alert('se '+guiaNum);
                        
                        $('#response3').load('addManiMensajero.php?option=2&numGuia='+guiaNum);
                    }
                });
                nguias=0;
                </script>");
      echo("<h2 align=center>");
      echo("<button class='btnGuardar' onclick='guardar($tipo);' style=' width: 90px;'>Guardar</button>");
      echo("</h2>");
      //resteo el arreglo de guias :D
      $arreGuias = new ArrayObject();
      $_SESSION['arregloGuias'] = serialize($arreGuias);
  }

  function addGuia($objUser)
  {
      //recupero el arreglo de guias
      $arreGuias = unserialize($_SESSION['arregloGuias']);
      $num = $_REQUEST['numGuia'];
      //validar si se puede agregar
      //echo($num);
//      $arreGuias->size();
      $idDep = $objUser->getIdDepartamento();
      $daoGuia = new DaoGuia();
      if ($daoGuia->checkManifiesto($num, $idDep))
      {
          $last = sizeof($arreGuias);

          $arreGuias[$num] = $num;

          showGuias($arreGuias); //refresco

          echo("<script type='text/javascript'>
             nguias++;
                  </script>");
      }
      else
      {
          echo("<script type='text/javascript'>
             alert('La guia $num no puede ser asignada a este manifiesto');
                  </script>");
          showGuias($arreGuias);
      }
  }

  function delGuia()
  {

      $num = $_REQUEST['numGuia'];

      //recupero el arreglo de guias
      $arreGuias = unserialize($_SESSION['arregloGuias']);
      unset($arreGuias[$num]);
      //echo("xD");
      //var_dump($arreGuias);
      //++ quitar una guia del arreglo
      showGuias($arreGuias); //refresco
  }

  //funcion para mostrar las guias que tengo acumuladas.
  //no recupera el arreglo de guias ya que de eso se encargan add y del
  function showGuias($arreGuias)
  {
      //guardo las guias q llevo
      $_SESSION['arregloGuias'] = serialize($arreGuias);

      echo("<h2>Numero de guias:<b> " . sizeof($arreGuias) . "</b></h2>");

      echo("<table>");
      foreach ($arreGuias as $numero)
      {
          echo("<tr>");
          echo("<td>$numero</td>");
          echo("<td><a onclick='quitar(\"$numero\");'>Quitar</a></td>");
          echo("</tr>");
      }
      echo("</table>");
  }

  //funcion para crear el manifiesto en la BD
  function guardar($objUser)
  {
      $idMen = $_REQUEST['idMensajero'];
      $idZona = $_REQUEST['idZona'];
      $plazo = $_REQUEST['plazo'];
      //debo saber el tipo de mensajero al q le voy a asignar la guia
      $tipo = $_REQUEST['tipo'];
      $arreGuias = unserialize($_SESSION['arregloGuias']);

      $idCreador = $objUser->getId();


      //echo($idMen." zona ".$idZona." plazo ".$plazo." tipo ".$tipo);

      $objManifiesto;
      $daoMani = new DaoManifiesto();

      if ($tipo == 5)
      {
          $objManifiesto = new Manifiesto(-1, NULL, $idCreador, $plazo, $idZona, NULL);

      }

      if ($tipo == 8)
      {
          $tarifa = $_REQUEST['tarifa'];

          $objManifiesto = new Manifiesto(-1, NULL, $idCreador, $plazo, $idZona, $tarifa);
      }

      $objManifiesto->setTerceros(NULL, $idMen, NULL);
      $daoMani->insertar($objManifiesto, $arreGuias);
  }

?>
