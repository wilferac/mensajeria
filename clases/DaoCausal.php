<?php

  /**
   * clase dao causal
   *
   * @author inovate
   */
  class DaoCausal
  {

      //obtengo todo los causales
      public function getAll()
      {
          mysql_query("SET NAMES 'utf8'");
          
          $query = "SELECT c.`idcausales`, c.`nombrecausales` FROM causales c";
          
          $results2 = mysql_query($query) or die(mysql_error());

          $res = array();
          $cont=0;
          while ($fila = mysql_fetch_assoc($results2))
          {
              $obj = new Causal($fila['idcausales'],$fila['nombrecausales']);
              $res[$cont]=$obj;
              $cont++;
          }

          return $res;
          
      }


  }

?>
