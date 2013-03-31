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
  }

  //se encarga de cargar las guias del manifiesto
  function show()
  {
      $caso = $_REQUEST['caso'];


      if ($caso == 2)
      {
          echo("<h3>Razon:</h3>");
          echo("<select>");
          echo("<option value='-1'>Seleccione...</option>");
          
          $daoCausal = new DaoCausal();
          $arrayCausales = $daoCausal->getAll();
          
          foreach($arrayCausales as $objCausal)
          {
              $id=$objCausal->getId();
              $nom = $objCausal->getNombre();
              echo("<option value='$id'>$nom</option>");
          }
          echo("</select>");
      }

      echo("<h3>Fecha:</h3>");
      echo("<input size='15' type='date' />");

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
  }

  function delGuia()
  {
      echo('nada');
//      $numGuia = $_REQUEST['numGuia'];
//      $idMani = $_REQUEST['idMani'];
//
//      $daoGuia = new DaoGuia();
//      $arreGuias = unserialize($_SESSION['arregloGuias']);
//      $arreIdGuias = unserialize($_SESSION['arregloIdGuias']);
//      //queda pendiente verificar si la guia existe o no :(
//      if (isset($arreGuias[$numGuia]))
//      {
//          //codigo para dar de alta en la BD
//          //echo($arreIdGuias[$numGuia]." num guia ". $numGuia);
//          //return;
//          if ($daoGuia->altaDeManifiesto($arreIdGuias[$numGuia], $numGuia,5))
//          {
//              unset($arreGuias[$numGuia]);
//          }
//          else
//          {
//              die("error al dar de alta la guia N: " . $numGuia);
//          }
//      }
//      else
//      {
//          echo("<script type='text/javascript'>
//                alert('Error: intenta digitando guias que esten en el manifiesto');
//          </script>");
//      }
//      if (sizeof($arreGuias) <= 0)
//      {
//          //dar de alta el manifiesto
//          $daoMani = new DaoManifiesto();
//          $daoMani->darAlta($idMani);
//
//          echo("<script type='text/javascript'>
//                alert('Manifiesto descargado!');
//                //codigo para redirigirlo al listado de manifiestos
//          </script>");
//      }
//
//
//      //echo("xD");
//      //var_dump($arreGuias);
//      //++ quitar una guia del arreglo
//      showGuias($arreGuias, $arreIdGuias);
  }

//muestra las guias q tengo actualmente
  function showGuias($arreGuias, $arreIdGuias)
  {
//      //guardo las guias q llevo
//      $_SESSION['arregloGuias'] = serialize($arreGuias);
//      $_SESSION['arregloIdGuias'] = serialize($arreIdGuias);
//
//      $ta = sizeof($arreGuias);
//
//      echo("<h2>Numero de guias:<b> " . $ta . "</b></h2>");
//
//      echo("<table>");
//
//      foreach ($arreGuias as $num)
//      {
//          echo("<tr>");
//          echo("<td>$arreIdGuias[$num]</td>");
//          echo("<td>$num</td>");
//          echo("<td><a onclick='quitar($num);'>Quitar</a></td>");
//          echo("</tr>");
//      }
//      echo("</table>");
  }

?>
