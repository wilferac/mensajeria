<?php

   /**
    * la clase sucursal
    *
    * @author inovate
    */
   class Aliado
   {

       private $id;
       //obtengo la sucursal para poder filtrarlos desde js
       private $nombre;
       private $idCiudad;

       public function __construct($id, $nombre,$idCiudad )
       {
           $this->id= $id;
           $this->nombre= $nombre;
           $this->idCiudad = $idCiudad;
       }
       
       public function show()
       {
           echo("nombre: $this->nombre<br />");
       }
       
       public function getId()
       {
           return $this->id;
       }
       
       public function getIdCiudad()
       {
           return $this->idCiudad;
       }
       
       public function getNombre()
       {
           return $this->nombre;
       }
   }

?>
