<?php

   /**
    * la clase ciudad
    *
    * @author inovate
    */
   class Ciudad
   {

       private $id;
       //obtengo la sucursal para poder filtrarlos desde js
       private $nombre;
       private $idDepartamento;
       private $nomDepartamento;

       public function __construct($id, $nombre, $dep , $nomDep)
       {
           $this->id= $id;
           $this->nombre= $nombre;
           $this->idDepartamento = $dep;
           $this->nomDepartamento = $nomDep;
       }
       
       public function show()
       {
           echo("nombre: $this->nombre<br />");
       }
       
       public function getId()
       {
           return $this->id;
       }
       
       public function getNomDepartamento()
       {
           return $this->nomDepartamento;
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
