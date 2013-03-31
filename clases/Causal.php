<?php

   /**
    * esta clase maneja los causales, los cuales son razones por las cuales una guia fue devuelta
    *
    * @author inovate
    */
   class Causal
   {

       private $id;
       //obtengo la sucursal para poder filtrarlos desde js
       private $nombre;

       public function __construct($id, $nombre)
       {
           $this->id= $id;
           $this->nombre= $nombre;
           
       }
       
       public function show()
       {
           echo("nombre: $this->nombre<br />");
       }
       
       public function getId()
       {
           return $this->id;
       }

       
       public function getNombre()
       {
           return $this->nombre;
       }

       

   }

?>
