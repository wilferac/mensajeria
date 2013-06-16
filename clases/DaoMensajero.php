<?php

   /**
    * la clase dao para gestionar la conexion 
    *
    * @author inovate
    */
   class DaoMensajero
   {
       
       //por defecto busca mensajeros, a menos que queramos destajos
       public function getAll($idSucur = NULL, $tipo = 5)
       {
           //consulta para recuperar los que tengan el rol de mensajeros o destajos
           $cons = "SELECT t.idtercero, t.sucursal_idsucursal, t.nombres_tercero, t.apellidos_tercero
               from tercero t
               inner join tercero_tipo tp on tp.tercero_idtercero = t.idtercero";
           //busco por rol
           $cons=$cons." where tipo_tercero_idtipo_tercero=".$tipo;
           
           if($idSucur != NULL)
           {
               //agrego la sucursal a la consulta
               $cons=$cons." and t.sucursal_idsucursal=".$idSucur;
           }
           $results2 = mysql_query($cons) or die(mysql_error());

           $res = array();
           $cont=0;
           while ($fila = mysql_fetch_assoc($results2))
           {
                 
               $obj = new Mensajero($fila["nombres_tercero"]." ".$fila["apellidos_tercero"],$fila["idtercero"],$fila["sucursal_idsucursal"] );
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
