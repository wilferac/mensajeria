<?php

  /**
   * la clase dao para gestionar la conexion 
   *
   * @author inovate
   */
  class DaoGuia
  {

      //por defecto busca mensajeros, a menos que queramos destajos
      public function getAll()
      {
          
      }

      //verifico que exista la guia cumple con los requisitos
      public function checkManifiesto($numero, $depCod)
      {
          $cons = "SELECT  * FROM guia g 
INNER JOIN ciudad c ON c.idciudad = g.ciudad_iddestino
WHERE g.estado = 1 AND c.departamento_iddepartamento = $depCod AND g.numero_guia = '$numero'";

          $results2 = mysql_query($cons) or die(mysql_error());

          $res = array();
          $cont = 0;
          if ($fila = mysql_fetch_assoc($results2))
          {

              return true;
          }
          
          return false;
      }

  }

?>
