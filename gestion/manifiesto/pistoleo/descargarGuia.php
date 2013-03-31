<?php

  /*
   * este se va a encargar de descargar las guias del manifiesto y mostrar el resultado en un arreglo de resultado :O
   * 
   */
  session_start();
  include '../../../clases/Guia.php';
  include '../../../clases/DaoGuia.php';
  include '../../../clases/DaoManifiesto.php';
  include '../../../clases/Causal.php';
  include '../../../clases/DaoCausal.php';
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
          show();
          break;

//descarga una guia del manifiesto :O
      case 1:
          delGuia();
          break;

      case 2:
          guardar();
          break;

      case 3:
          quitar();
          break;
  }

  function quitar()
  {
      $numGuia = $_REQUEST['numGuia'];


      $arrayGuias = unserialize($_SESSION['arregloGuias']);
      $arrayId = unserialize($_SESSION['arregloId']);
      $arrayMani = unserialize($_SESSION['arregloMani']);
      $arrayNombres = unserialize($_SESSION['arregloNombres']);


      unset($arrayGuias[$numGuia]);
      unset($arrayId[$numGuia]);
      unset($arrayMani[$numGuia]);
      unset($arrayNombres[$numGuia]);

      showGuias($arrayGuias, $arrayId, $arrayMani, $arrayNombres);
  }

  //se encarga de cargar las guias del manifiesto
  function show()
  {
      $caso = $_REQUEST['caso'];


      if ($caso == 2)
      {
          echo("<h3>Razon:</h3>");
          echo("<select id='selRazon'>");
          echo("<option value='-1'  >Seleccione...</option>");

          $daoCausal = new DaoCausal();
          $arrayCausales = $daoCausal->getAll();

          foreach ($arrayCausales as $objCausal)
          {
              $id = $objCausal->getId();
              $nom = $objCausal->getNombre();
              echo("<option value='$id'>$nom</option>");
          }
          echo("</select>");
      }

      echo("<h3>Fecha:</h3>");
      echo("<input size='15' id='fechaManual' type='date' />");

      echo("<h3>Guia N.:</h3>");
      echo("<input size='15' id='txtNGuia' type='text' />");
      echo("<br />");
      echo("<h3 align=center><button type='button' value='Guardar' onclick='guardar($caso)'>Guardar</button></h3>");


      echo('<script type="text/javascript">');

      echo("$('input[type=date]').datepicker({
                    dateFormat: 'yy/mm/dd'
                });");


      echo("$('#txtNGuia').keypress(function(event) {
                    if(event.keyCode.toString()== '13')
                    {
                        var guiaNum = document.getElementById('txtNGuia').value;
                        if(guiaNum == '')
                        {
                            return;
                        }
                        

                        document.getElementById('txtNGuia').value='';
                        event.preventDefault();
                        //alert('se '+guiaNum);
                        
                       $('#response2').load('descargarGuia.php?option=1&numGuia='+guiaNum);
                    }
                });
                nguias=0;
                ");

      echo('</script>');
      //creo los arreglos para guardar los id =D
      $arrayGuias = new ArrayObject();
      $arrayId = new ArrayObject();
      $arrayMani = new ArrayObject();
      $arrayNombres = new ArrayObject();

      $_SESSION['arregloGuias'] = serialize($arrayGuias);
      $_SESSION['arregloId'] = serialize($arrayId);
      $_SESSION['arregloMani'] = serialize($arrayMani);
      $_SESSION['arregloNombres'] = serialize($arrayNombres);
  }

  function delGuia()
  {
      echo('nada');
      $numGuia = $_REQUEST['numGuia'];

      $daoGuia = new DaoGuia();
      //reupero ellistado q tenia
      $arrayGuias = unserialize($_SESSION['arregloGuias']);
      $arrayId = unserialize($_SESSION['arregloId']);
      $arrayMani = unserialize($_SESSION['arregloMani']);
      $arrayNombres = unserialize($_SESSION['arregloNombres']);
//      
//      
      if (!isset($arrayGuias[$numGuia]))
      {
          $obj = $daoGuia->checkAlta($numGuia);
          //si se puede descargar =D
          if ($obj != NULL)
          {
              $arrayGuias[$numGuia] = $numGuia;
              $arrayId[$numGuia] = $obj->getIdMani();
              $arrayMani[$numGuia] = $obj->getIdManifiesto();
              $arrayNombres[$numGuia] = $obj->getMensajero();
              echo('<script type="text/javascript">
                  //alert("agregando");
                  nguias++;
                  </script>');
          }
          else
          {
              echo('<script type="text/javascript">
                  alert("No se puede descargar esta guia!");
                  </script>');
          }
      }
      showGuias($arrayGuias, $arrayId, $arrayMani, $arrayNombres);
  }

//muestra las guias q tengo actualmente
  function showGuias($arreGuias, $arreIdGuias, $arrayMani, $arrayNombres)
  {
      //guardo las guias q llevo
      $_SESSION['arregloGuias'] = serialize($arreGuias);
      $_SESSION['arregloId'] = serialize($arreIdGuias);
      $_SESSION['arregloMani'] = serialize($arrayMani);
      $_SESSION['arregloNombres'] = serialize($arrayNombres);

      $ta = sizeof($arreGuias);

      echo("<h2>Numero de guias:<b> " . $ta . "</b></h2>");

      echo("<table>");

      echo("<tr>
            <td>id maniguia</td>          
            <td>numguia</td>
            <td>id mani</td>
            <td>mensajero</td>
            </tr>");

      foreach ($arreGuias as $num)
      {
          echo("<tr>");
          echo("<td>$arreIdGuias[$num]</td>");
          echo("<td>$num</td>");
          echo("<td>$arrayMani[$num]</td>");
          echo("<td>$arrayNombres[$num]</td>");
          echo("<td><a onclick='quitar($num);'>Quitar</a></td>");
          echo("</tr>");
      }
      echo("</table>");
  }

  function guardar()
  {
      //echo("xD");
      $fechaManual = $_REQUEST['fechaManual'];
      $option = $_REQUEST['option'];
      $razon = $_REQUEST['razon'];
      $tipo = $_REQUEST['tipo'];

      $arrayGuias = unserialize($_SESSION['arregloGuias']);
      $arrayId = unserialize($_SESSION['arregloId']);
      $arrayMani = unserialize($_SESSION['arregloMani']);
      $arrayNombres = unserialize($_SESSION['arregloNombres']);

      $objGuia = new DaoGuia();



      if ($tipo == 1)
      {
          if ($objGuia->altaDeManifiestoEnMasa($arrayId, $arrayGuias, 3, NULL, $fechaManual))
          {
              descargarManifiesto($arrayMani);
              echo('1');
          }
      }
      if ($tipo == 2)
      {
          if ($objGuia->altaDeManifiestoEnMasa($arrayId, $arrayGuias, 2, $razon, $fechaManual))
          {
              descargarManifiesto($arrayMani);
              echo('1');
          }
      }
  }

  //verifica si un manifiesto quedo sin guias y lo cierra
  function descargarManifiesto($arrayMani)
  {
      $arMani = new ArrayObject();
      foreach ($arrayMani as $Idmani)
      {
          $arMani[$Idmani] = $Idmani;
      }
      $daoMani = new DaoManifiesto();
      foreach ($arMani as $Idmani)
      {
          $daoMani->darAlta($Idmani);
      }
  }

?>
