<?php

   /**
    * la clase dao para gestionar la conexion 
    *
    * @author inovate
    */
   class DaoManifiesto
   {

       //por defecto busca mensajeros, a menos que queramos destajos
       public function getAll($idSucur = NULL, $tipo = 5)
       {
           
       }

       public function getOne()
       {
           //++ falta...
       }

       public function insertar($objMani, $arreGuias)
       {
//          $objMani= new Manifiesto($id, $idSucursal, $idCreador, $plazo, $idZona, $tarifa);
           //verifico si es null

           $idSucur = !empty($objMani->idSucursal) ? $objMani->idSucursal : "NULL";
           $tarifa = !empty($objMani->tarifa) ? $objMani->tarifa : "NULL";
           $idZona = !empty($objMani->idZona) ? $objMani->idZona : "NULL";


           $peso = !empty($objMani->peso) ? $objMani->peso : "NULL";

           $idDesti = $objMani->getIdCiuDesti();
//          $idCiuDesti = $objMani->getIdCiuDesti();
           $idCiuDesti = !empty($idDesti) ? $idDesti : "NULL";
           $idCiuOri = $objMani->getIdCiuOri();

           mysql_query("BEGIN");
//          
//          mysql_query("COMMIT");
//
//            mysql_query("ROLLBACK");

           $query = "INSERT INTO manifiesto (sucursal_idsucursal, plazo_entrega_manifiesto,tarifadestajo, zonamensajero,peso,ciudadDestino,ciudadOrigen) 
              VALUES($idSucur, $objMani->plazo, $tarifa,$idZona,$peso ,$idCiuDesti,$idCiuOri)";

           if (mysql_query($query))
           {
               $idMani = mysql_insert_id();
               //inserto los terceros del manifiesto
               $query2 = "INSERT INTO tercero_manifiesto (idtercero, idmanifiesto, tipo) values";
               //miro si tengo algo para agregar
               //agrego al creador
               if (!empty($objMani->idCreador))
               {
                   //el tipo 4 es para el aliado
                   $query2 = $query2 . "($objMani->idCreador,$idMani,1)";
               }


               if (!empty($objMani->idAliado))
               {
                   //el tipo 4 es para el aliado
                   $query2 = $query2 . ",($objMani->idAliado,$idMani,4)";
               }

               if (!empty($objMani->idMenEntrega))
               {
                   //el tipo 3 es para el aliado
                   $query2 = $query2 . ",($objMani->idMenEntrega,$idMani,2)";
               }

               if (!empty($objMani->idMenResive))
               {
                   //el tipo 3 es para el aliado
                   $query2 = $query2 . ",($objMani->idMenResive,$idMani,3)";
               }

               if (mysql_query($query2))
               {
                   $query4 = "UPDATE guia set causal_devolucion_idcausal_devolucion = 4 where ";
                   //ojo, el guiId no referencia el id de la guia sino el numero_guia
                   $query3 = "INSERT INTO guia_manifiesto(manId,guiId) values";
                   foreach ($arreGuias as $numero)
                   {
                       //cambio de estado para las guias implicadas
                       if (mysql_query($query4 . " numero_guia = '$numero'"))
                       {
                           $query3 = $query3 . "($idMani,'$numero'),";
                       }
                       else
                       {
                           mysql_query("ROLLBACK");
                           die("-1");
                       }
                   }
                   //quito la ultima coma :)
                   $query3 = substr("$query3", 0, -1);
                   if (mysql_query($query3))
                   {
                       
                       //si llega aca es que todas las consulta fueron ejecutadas bien
                       echo($idMani);
                       mysql_query("COMMIT");
                   }
                   else
                   {
                       mysql_query("ROLLBACK");
                       die("-1");
                   }
               }
               else
               {
                   mysql_query("ROLLBACK");
                   die("-1");
               }
           }
           else
           {
               mysql_query("ROLLBACK");
               die("-1");
           }
       }

       public function darAlta($idMani)
       {
           //debo verificar si de verdad el manifiesto esta sin guias :(

           $cons = "SELECT g.numero_guia, gm.gmId  FROM guia g INNER JOIN  guia_manifiesto gm ON  gm.guiId = g.numero_guia
WHERE gm.manId = $idMani AND gm.estado = 1";

           $results2 = mysql_query($cons) or die(mysql_error());

           if ($fila = mysql_fetch_assoc($results2))
           {
               //echo("el manifiesto aun contiene guias activas");
               return false;
           }
           else
           {
               $query = "UPDATE manifiesto m SET m.estado = 0 , m.fechaCierre = CURRENT_TIMESTAMP WHERE m.idmanifiesto =$idMani AND m.fechaCierre is NULL";

               if (mysql_query($query))
               {
                   return true;
               }
               else
               {
                   echo(mysql_error());
                   return false;
               }
           }
       }

   }
?>

