<?php

   /**
    * la clase mensajero va a gestionar la parte de mensajeros(consultas y datos)
    *
    * @author inovate
    */
   class Manifiesto
   {

       private $numero;
       //obtengo la sucursal para poder filtrarlos desde js
       private $idCiudad;
       private $idDepartamento;

       public function __construct($num, $ciu, $dep)
       {
           $this->numero= $num;
           $this->idCiudad= $ciu;
           $this->idDepartamento = $dep;
       }
       
       public function show()
       {
           echo("nombre: $this->numero<br />");
       }
       
       public function getNumero()
       {
           return $this->numero;
       }

   }

?>
