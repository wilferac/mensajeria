<?php

  /*
   * este se va a encargar de descargar las guias del manifiesto y mostrar el resultado en un arreglo de resultado :O
   * 
   */
  session_start();
  include '../../../clases/Guia.php';
  include '../../../clases/DaoGuia.php';
  include '../../../clases/DaoManifiesto.php';
  include '../../../security/User.php';

  $objUser = unserialize($_SESSION['currentUser']);


  if ($objUser->getStatus() != 1)
  {
      //$operacion->redireccionar('No Puede entrar', 'index.php');
      return;
  }



  $option = $_REQUEST['option'];

  switch ($option)
  {
      //carga las guias del manifiesto
      case 0:
          loadGuias();
          break;

//descarga una guia del manifiesto :O
      case 1:
          delGuia();
          break;
  }

  //se encarga de cargar las guias del manifiesto
  function loadGuias()
  {
      $idMani = $_REQUEST['idMani'];
      //echo("loading..." . $idMani);

      $daoGuia = new DaoGuia();

      $arreGuias = $daoGuia->getAll($idMani);
      if (sizeof($arreGuias) <= 0)
      {
          echo("<script type='text/javascript'>
                alert('Este manifiesto ya esta descargado!');
          </script>");
          //redirigir a consulta
          die();
      }

      //guarda el numero de guias
      $arreRes = new ArrayObject();
      //guarda los id de guia_manifiesto
      $arreId = new ArrayObject();
      foreach ($arreGuias as $objGuia)
      {
          //$objGuia = new Guia($num, $ciu, $dep);
          $num = $objGuia->getNumero();
          $id = $objGuia->getIdMani();
          $arreRes[$num] = $num;
          $arreId[$num] = $id;
      }





      //los guardo
      $_SESSION['arregloGuias'] = serialize($arreRes);
      $_SESSION['arregloIdGuias'] = serialize($arreId);

      showGuias($arreRes, $arreId);
  }

  function delGuia()
  {
      $numGuia = $_REQUEST['numGuia'];
      $idMani = $_REQUEST['idMani'];

      $daoGuia = new DaoGuia();
      $arreGuias = unserialize($_SESSION['arregloGuias']);
      $arreIdGuias = unserialize($_SESSION['arregloIdGuias']);
      //queda pendiente verificar si la guia existe o no :(
      if (isset($arreGuias[$numGuia]))
      {
          //codigo para dar de alta en la BD
          //echo($arreIdGuias[$numGuia]." num guia ". $numGuia);
          //return;
          if ($daoGuia->altaDeManifiesto($arreIdGuias[$numGuia], $numGuia))
          {
              unset($arreGuias[$numGuia]);
          }
          else
          {
              die("error al dar de alta la guia N: " . $numGuia);
          }
      }
      else
      {
          echo("<script type='text/javascript'>
                alert('Error: intenta digitando guias que esten en el manifiesto');
          </script>");
      }
      if (sizeof($arreGuias) <= 0)
      {
          //dar de alta el manifiesto
          $daoMani = new DaoManifiesto();
          $daoMani->darAlta($idMani);

          echo("<script type='text/javascript'>
                alert('Manifiesto descargado!');
                //codigo para redirigirlo al listado de manifiestos
          </script>");
      }


      //echo("xD");
      //var_dump($arreGuias);
      //++ quitar una guia del arreglo
      showGuias($arreGuias, $arreIdGuias);
  }

//muestra las guias q tengo actualmente
  function showGuias($arreGuias, $arreIdGuias)
  {
      //guardo las guias q llevo
      $_SESSION['arregloGuias'] = serialize($arreGuias);
      $_SESSION['arregloIdGuias'] = serialize($arreIdGuias);

      $ta = sizeof($arreGuias);

      echo("<h2>Numero de guias:<b> " . $ta . "</b></h2>");

      echo("<table>");

      foreach ($arreGuias as $num)
      {
          echo("<tr>");
          echo("<td>$arreIdGuias[$num]</td>");
          echo("<td>$num</td>");
          echo("<td><a onclick='quitar($num);'>Quitar</a></td>");
          echo("</tr>");
      }
      echo("</table>");
  }

?>
