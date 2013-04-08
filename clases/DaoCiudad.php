<?php

  /**
   * la clase dao para gestionar la conexion 
   *
   * @author inovate
   */
  class DaoCiudad
  {

      //por defecto busca mensajeros, a menos que queramos destajos
      public function getAll()
      {
          mysql_query("SET NAMES 'utf8'");
          
          $query = "SELECT c.idciudad, c.nombre_ciudad, c.departamento_iddepartamento, d.nombre_departamento
              FROM ciudad c inner join departamento d on c.departamento_iddepartamento = d.iddepartamento
              order by c.nombre_ciudad";
          
          $results2 = mysql_query($query) or die(mysql_error());

          $res = array();
          $cont=0;
          while ($fila = mysql_fetch_assoc($results2))
          {
              $objCiudad = new Ciudad($fila['idciudad'],$fila['nombre_ciudad'] ,$fila['departamento_iddepartamento'], $fila['nombre_departamento']);
              $res[$cont]=$objCiudad;
              $cont++;
          }

          return $res;
          
      }


  }

?>
