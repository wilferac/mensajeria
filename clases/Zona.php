<?php

   /**
    * la clase mensajero va a gestionar la parte de mensajeros(consultas y datos)
    *
    * @author inovate
    */
   class Zona
   {

       private $nombre;
       private $id;
       //obtengo la sucursal para poder filtrarlos desde js
       private $idCiudad;

       public function __construct($id, $nom ,$ciu)
       {
           $this->id= $id;
           $this->idCiudad= $ciu;
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
