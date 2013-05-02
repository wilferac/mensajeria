<?php

   /**
    * la clase sucursal
    *
    * @author inovate
    */
   class Sucursal
   {

       private $id;
       //obtengo la sucursal para poder filtrarlos desde js
       private $nombre;
       private $idCiudad;
       private $idDepartamento;

       public function __construct($id, $nombre,$idCiudad,$dep )
       {
           $this->id= $id;
           $this->nombre= $nombre;
           $this->idDepartamento = $dep;
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
       
       public function getIdDepartamento()
       {
           return $this->idDepartamento;
       }
       

   }

?>
