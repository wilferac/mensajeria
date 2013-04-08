<?php

  /**
   * la clase dao para gestionar la conexion 
   *
   * @author wilferac
   */
  class DaoZona
  {

      //por defecto busca mensajeros, a menos que queramos destajos
      public function getAll($idCiudad)
      {
          //consulta para recuperar la zonas de una ciudad
          $cons = "SELECT  z.idzona, z.ciudad_idciudad, z.nombre_zona  FROM zona z";
          $cons = $cons . " where z.ciudad_idciudad=" . $idCiudad;


          $results2 = mysql_query($cons) or die(mysql_error());

          $res = array();
          $cont = 0;
          while ($fila = mysql_fetch_assoc($results2))
          {

              $obj = new Zona($fila["idzona"], $fila["nombre_zona"], $fila["ciudad_idciudad"]);
              $res[$cont] = $obj;
              $cont++;
          }
          //retorno el arreglo :D
          return $res;
      }

      public function getOne()
      {
          //++ falta...
      }

  }

?>
