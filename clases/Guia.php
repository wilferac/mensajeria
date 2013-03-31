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
       //este es el id del manifiesto (manifiesto) //no confundir con el anterior
       private $idManifiesto;
       //guarda el nombre del mensajero q entrega la guia
       private $mensajero;

       public function __construct($num, $ciu, $dep)
       {
           $this->numero= $num;
           $this->idCiudad= $ciu;
           $this->idDepartamento = $dep;
           
           $this->idManifiesto = NULL;
           $this->idMani = NULL;
           $this->mensajero = NULL;
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
       
       public function getIdManifiesto()
       {
           return $this->idManifiesto;
       }
       
       public function setIdManifiesto($id)
       {
           $this->idManifiesto=$id;
       }
       
       public function getMensajero()
       {
           return $this->mensajero;
       }
       
       public function setMensajero($nom)
       {
           $this->mensajero=$nom;
       }

   }

?>
