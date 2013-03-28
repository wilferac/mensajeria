<?php

   /**
    * la clase mensajero va a gestionar la parte de mensajeros(consultas y datos)
    *
    * @author inovate
    */
   class Mensajero
   {

       private $nombre;
       private $id;
       //obtengo la sucursal para poder filtrarlos desde js
       private $idSucursal;

       public function __construct($nom, $id,$sucur)
       {
           $this->id= $id;
           $this->idSucursal= $sucur;
           $this->nombre = $nom;
       }
       
       public function show()
       {
           echo("nombre: $this->nombre<br />");
       }
       
       public function getNombre()
       {
           return $this->nombre;
       }
       
       public function getId()
       {
           return $this->id;
       }

   }

?>
