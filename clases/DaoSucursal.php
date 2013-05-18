<?php

  /**
   * la clase dao para gestionar la conexion 
   *
   * @author inovate
   */
  class DaoSucursal
  {

      //filtro las sucursales por departamento
      public function getAll($idDep)
      {
          mysql_query("SET NAMES 'utf8'");
          
          $query = "SELECT s.nombre_sucursal , s.ciudad_idciudad , s.idsucursal
              FROM sucursal s inner join ciudad c on s.ciudad_idciudad = c.idciudad
              where c.departamento_iddepartamento = $idDep and s.Activa =1
              order by s.nombre_sucursal";
          
          $results2 = mysql_query($query) or die(mysql_error());

          $res = array();
          $cont=0;
          while ($fila = mysql_fetch_assoc($results2))
          {
              $objSucur = new Sucursal($fila['idsucursal'],$fila['nombre_sucursal'],$fila['ciudad_idciudad'],$idDep);
              $res[$cont]=$objSucur;
              $cont++;
          }

          return $res;
          
      }


  }

?>
