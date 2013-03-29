<?php

  /**
   * la clase dao para gestionar la conexion 
   *
   * @author inovate
   */
  class DaoAliado
  {

      //filtro los aliados por ciudades
      //y por su rol de aliado.
      public function getAll($idCiu)
      {
          mysql_query("SET NAMES 'utf8'");
          $tipoAliado = 3;
          //consulta para recuperar los que tengan el rol de mensajeros o destajos
          $cons = "SELECT t.idtercero,  t.nombres_tercero, t.apellidos_tercero
               FROM tercero t
               INNER JOIN tercero_tipo tp ON tp.tercero_idtercero = t.idtercero
               INNER JOIN ciudad_tercero ct ON ct.idtercero = t.idtercero
               WHERE tp.tipo_tercero_idtipo_tercero=$tipoAliado AND ct.idciudad =  $idCiu
               ";


          $results2 = mysql_query($cons) or die(mysql_error());

          $res = array();
          $cont = 0;
          while ($fila = mysql_fetch_assoc($results2))
          {
              $obj = new Aliado($fila["idtercero"],$fila["nombres_tercero"],$fila["nombres_tercero"],$idCiu);
              $res[$cont] = $obj;
              $cont++;
          }
          //retorno el arreglo :D
          return $res;
      }

  }

?>
