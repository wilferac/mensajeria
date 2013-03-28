<?php

   /**
    * la clase dao para gestionar la conexion 
    *
    * @author inovate
    */
   class DaoMensajero
   {

       
       public function getAll()
       {
           //consulta para recuperar los que tengan el rol de mensajeros  ^-^
           $cons = "SELECT t.idtercero, t.sucursal_idsucursal, t.nombres_tercero
               from tercero t
               inner join tercero_tipo tp on tp.idtercero_tipo = t.idtercero";

           $results2 = mysql_query($cons) or die(mysql_error());

           $res = array();
           $cont=0;
           while ($fila = mysql_fetch_assoc($results2))
           {
                 
               $obj = new Mensajero($fila["nombres_tercero"],$fila["idtercero"],$fila["sucursal_idsucursal"] );
               $res[$cont]=$obj;
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
