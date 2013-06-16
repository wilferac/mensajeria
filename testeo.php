<?php

  /*
   * To change this template, choose Tools | Templates
   * and open the template in the editor.
   */
  for($c=1;$c<=9999;$c++)
  {
      $con=0;
      $res=$c;
      while (true)
      {
          
          $res= (int) $res/10;
          //echo($res." el resultado<br />");
          if($res>0)
          {
              $con++;
          }
          else
              break;
          
      }
      $numero_guia = "CC" . substr("0000000", 0, 7 - ($con)) . $c;
      echo(strlen($c)." digitos2<br />");
      echo($con." digitos<br />");
      echo($numero_guia." numero<br />");
  }
  
  
  
?>
