<?php

  /*
   * este se va a encargar de descargar las guias del manifiesto y mostrar el resultado en un arreglo de resultado :O
   * 
   */


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
      
  }
//muestra las guias q tengo actualmente
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
          echo("<td><a onclick='quitar($numero);'>Quitar</a></td>");
          echo("</tr>");
      }
      echo("</table>");
  }

?>
