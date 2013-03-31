<?php

  /**
   * la clase dao para gestionar la conexion 
   *
   * @author inovate
   */
  class DaoGuia
  {

      //obtiene todas las guias de un manifiesto :D
      public function getAll($idMani)
      {
          $cons = "SELECT g.numero_guia, gm.gmId  FROM guia g INNER JOIN  guia_manifiesto gm ON  gm.guiId = g.numero_guia
WHERE gm.manId = $idMani AND gm.estado = 1";

          $results2 = mysql_query($cons) or die(mysql_error());

          $res = array();
          $cont = 0;
          while ($fila = mysql_fetch_assoc($results2))
          {
              $obj = new Guia($fila["numero_guia"], 0, 0);
              $obj->setIdMani($fila["gmId"]);
              $res[$cont] = $obj;
              $cont++;
          }
          //retorno un arreglo con guias =)
          return $res;
      }

      //verifico que exista la guia cumple con los requisitos
      public function checkManifiesto($numero, $depCod)
      {
          $cons = "SELECT  * FROM guia g 
INNER JOIN ciudad c ON c.idciudad = g.ciudad_iddestino
WHERE g.estado = 1 AND c.departamento_iddepartamento = $depCod AND g.numero_guia = '$numero' 
    AND (g.causal_devolucion_idcausal_devolucion =1 OR g.causal_devolucion_idcausal_devolucion =2 OR g.causal_devolucion_idcausal_devolucion =5)";

          $results2 = mysql_query($cons) or die(mysql_error());

          $res = array();
          $cont = 0;
          if ($fila = mysql_fetch_assoc($results2))
          {

              return true;
          }

          return false;
      }

      //descarga una guia del manifiesto (llegada a destino)
      public function altaDeManifiesto($idGuiaMani, $idGuia, $estadoGuia,$idCausal=NULL,$fechaManual=NULL )
      {
          $idCausal = !empty($idCausal) ? $idCausal : "NULL";
          $fechaManual= !empty($fechaManual) ? $fechaManual : "NULL";
          mysql_query("BEGIN");
          //descargo de guia_manifiesto
          $query = "UPDATE guia_manifiesto g 
              SET  g.estado =0, fechaDescarga = CURRENT_TIMESTAMP, idEstadoGuia = $estadoGuia , idCausal = $idCausal, fechaManual = $fechaManual
              WHERE g.gmId=$idGuiaMani; ";

          if (mysql_query($query))
          {
              $query2 = "UPDATE guia g SET g.`causal_devolucion_idcausal_devolucion` = $estadoGuia WHERE g.`numero_guia` =$idGuia";
              if (mysql_query($query2))
              {
                  mysql_query("COMMIT");
                  return true;
              }
              else
              {
                  mysql_query("ROLLBACK");
                  echo(mysql_error());
                  return false;
              }
          }
          else
          {
              mysql_query("ROLLBACK");
              echo(mysql_error());
              return false;
          }
      }

  }

?>
