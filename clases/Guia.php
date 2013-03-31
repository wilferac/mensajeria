<?php

   /**
    * la clase mensajero va a gestionar la parte de mensajeros(consultas y datos)
    *
    * @author inovate
    */
   class Guia
   {

       private $numero;
       //obtengo la sucursal para poder filtrarlos desde js
       private $idCiudad;
       private $idDepartamento;
       //este es para guardar el id de su relacion con un manifiesto (guia_manifiesto)
       private $idMani;

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
       
       public function getIdMani()
       {
           return $this->idMani;
       }
       
       public function setIdMani($id)
       {
           $this->idMani=$id;
       }

   }

?>
