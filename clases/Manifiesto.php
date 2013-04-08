<?php

   /**
    * la clase mensajero va a gestionar la parte de mensajeros(consultas y datos)
    *
    * @author inovate
    */
   class Manifiesto
   {
       //es q me aburro , pero despues toca poner seters :(
       public $id;
       public $idSucursal;
       public $idCreador;
       public $plazo;
       public $idZona;
       public $tarifa;
       public $idAliado;
       public $idMenEntrega;
       public $idMenResive;
       public $peso;
       
       private $idCiuDesti;
       private $idCiuOri;

       public function getIdCiuDesti()
       {
           return $this->idCiuDesti;
       }

       public function setIdCiuDesti($idCiuDesti)
       {
           $this->idCiuDesti = $idCiuDesti;
       }

       public function getIdCiuOri()
       {
           return $this->idCiuOri;
       }

       public function setIdCiuOri($idCiuOri)
       {
           $this->idCiuOri = $idCiuOri;
       }

              
       public function __construct($id,$idSucursal,$idCreador,$plazo,$idZona,$tarifa)
       {
           $this->id=$id;
           $this->idSucursal=$idSucursal;
           $this->idCreador=$idCreador;
           $this->plazo=$plazo;
           $this->idZona=$idZona;
           $this->tarifa=$tarifa;
           
           $this->idAliado=NULL;
           $this->idMenEntrega=NULL;
           $this->idMenResive=NULL;
           $this->peso = NULL;
       }
       
       //seteo los terceros extra.
       public function setTerceros($idAliado,$idMenEntrega,$idMenResive)
       {
           $this->idAliado=$idAliado;
           $this->idMenEntrega=$idMenEntrega;
           $this->idMenResive=$idMenResive;
       }
       
       public function show()
       {
           
       }
       
       public function getNumero()
       {
           
       }

   }

?>
