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
       //campos agregados para imprimir guias.
       private $referencia;
       private $idDestinatario;
       private $nombreDestinatario;
       private $idRemitente;
       private $nombreRemitente;
       private $destinatarioInfo;
       private $direccion;
       private $fecha;
       private $ciuDesti;
       private $depDesti;
       private $telefonoDesti;
       private $ciuOrigen;
       private $peso;
       private $numeroOrdenSer;
       private $clienteNom;

       /**
        *
        * @var string el estado de la guia (devuelta, entregada, etc...)
        */
       private $estado;
       private $idEstado;
       private $idOrdenServi;

       public function getIdEstado()
       {
           return $this->idEstado;
       }

       public function setIdEstado($idEstado)
       {
           $this->idEstado = $idEstado;
       }

       public function getClienteNom()
       {
           return $this->clienteNom;
       }

       public function setClienteNom($clienteNom)
       {
           $this->clienteNom = $clienteNom;
       }

       public function getIdOrdenServi()
       {
           return $this->idOrdenServi;
       }

       public function setIdOrdenServi($idOrdenServi)
       {
           $this->idOrdenServi = $idOrdenServi;
       }

       public function getEstado()
       {
           return $this->estado;
       }

       public function setEstado($estado)
       {
           $this->estado = $estado;
       }

       public function getNumeroOrdenSer()
       {
           return $this->numeroOrdenSer;
       }

       public function setNumeroOrdenSer($numeroOrdenSer)
       {
           $this->numeroOrdenSer = $numeroOrdenSer;
       }

       public function getIdRemitente()
       {
           return $this->idRemitente;
       }

       public function setIdRemitente($idRemitente)
       {
           $this->idRemitente = $idRemitente;
       }

       public function getIdDestinatario()
       {
           return $this->idDestinatario;
       }

       public function setIdDestinatario($idDestinatario)
       {
           $this->idDestinatario = $idDestinatario;
       }

       public function getDestinatarioInfo()
       {
           return $this->destinatarioInfo;
       }

       public function setDestinatarioInfo($destinatarioInfo)
       {
           $this->destinatarioInfo = $destinatarioInfo;
       }

       public function getReferencia()
       {
           return $this->referencia;
       }

       public function setReferencia($referencia)
       {
           $this->referencia = $referencia;
       }

       public function getNombreDestinatario()
       {
           return $this->nombreDestinatario;
       }

       public function setNombreDestinatario($nombreDestinatario)
       {
           $this->nombreDestinatario = $nombreDestinatario;
       }

       public function getNombreRemitente()
       {
           return $this->nombreRemitente;
       }

       public function setNombreRemitente($nombreRemitente)
       {
           $this->nombreRemitente = $nombreRemitente;
       }

       public function getDireccion()
       {
           return $this->direccion;
       }

       public function setDireccion($direccion)
       {
           $this->direccion = $direccion;
       }

       public function getFecha()
       {
           return $this->fecha;
       }

       public function setFecha($fecha)
       {
           $this->fecha = $fecha;
       }

       public function getCiuDesti()
       {
           return $this->ciuDesti;
       }

       public function setCiuDesti($ciuDesti)
       {
           $this->ciuDesti = $ciuDesti;
       }

       public function getDepDesti()
       {
           return $this->depDesti;
       }

       public function setDepDesti($depDesti)
       {
           $this->depDesti = $depDesti;
       }

       public function getTelefonoDesti()
       {
           return $this->telefonoDesti;
       }

       public function setTelefonoDesti($telefonoDesti)
       {
           $this->telefonoDesti = $telefonoDesti;
       }

       public function getCiuOrigen()
       {
           return $this->ciuOrigen;
       }

       public function setCiuOrigen($ciuOrigen)
       {
           $this->ciuOrigen = $ciuOrigen;
       }

       public function getPeso()
       {
           return $this->peso;
       }

       public function setPeso($peso)
       {
           $this->peso = $peso;
       }

//       private $mensajero;

       public function __construct($num, $ciu, $dep)
       {
           $this->numero = $num;
           $this->idCiudad = $ciu;
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
           $this->idMani = $id;
       }

       public function getIdManifiesto()
       {
           return $this->idManifiesto;
       }

       public function setIdManifiesto($id)
       {
           $this->idManifiesto = $id;
       }

       public function getMensajero()
       {
           return $this->mensajero;
       }

       public function setMensajero($nom)
       {
           $this->mensajero = $nom;
       }

   }

?>
