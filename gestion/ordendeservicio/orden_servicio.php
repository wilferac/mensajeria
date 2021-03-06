<?
//include ("../../conexion/conexion.php");
class orden_servicio{

    var $idorden_servicio;
    var $factura_idfactura;
    var $tercero_idcliente;
    var $numero_orden_servicio;
    var $observacion_orden_servicio;
    var $area_orden_servicio;
    var $plazo_entrega_orden;
    var $plazo_asignacion_orden;
    //Variables de Control
    var $stmt; //Cursor para las consultas
    //Funciones
    function cargar(){

        if (isset($_GET["codigo"]) ){//se cargan las variables de la base de datos

            $this->buscar($_GET["codigo"]);

        }else{

            //Cargar las variables de la forma
            $this->idorden_servicio= isset($_POST["idorden_servicio"])?$_POST["idorden_servicio"]:'';
            $this->factura_idfactura= isset($_POST["factura_idfactura"])?$_POST["factura_idfactura"]:'';
            $this->tercero_idcliente= isset($_POST["tercero_idcliente"])?$_POST["tercero_idcliente"]:'';
            $this->numero_orden_servicio= isset($_POST["numero_orden_servicio"])?$_POST["numero_orden_servicio"]:'';
            $this->observacion_orden_servicio= isset($_POST["observacion_orden_servicio"])?$_POST["observacion_orden_servicio"]:'';
            $this->area_orden_servicio= isset($_POST["area_orden_servicio"])?$_POST["area_orden_servicio"]:'';
            $this->plazo_entrega_orden= isset($_POST["plazo_entrega_orden"])?$_POST["plazo_entrega_orden"]:'';
            $this->plazo_asignacion_orden= isset($_POST["plazo_asignacion_orden"])?$_POST["plazo_asignacion_orden"]:'';

        }

    }
    function agregar(){

        global $conn;
        //calcular el codigo
        $SQL = "select max(idorden_servicio) from orden_servicio";
        if ( $conn->ejecutar($SQL) && ($row=$conn->siguiente(NULL)) )

            $this->idorden_servicio = $row[0]+1;

        else
        $this->idorden_servicio = 1;
        $SQL = sprintf("INSERT INTO orden_servicio (idorden_servicio,factura_idfactura,tercero_idcliente,numero_orden_servicio,observacion_orden_servicio,area_orden_servicio,plazo_entrega_orden,plazo_asignacion_orden)
        values('%s','%s','%s','%s','%s','%s','%s','%s')
        ",$this->idorden_servicio,$this->factura_idfactura,$this->tercero_idcliente,$this->numero_orden_servicio,$this->observacion_orden_servicio,$this->area_orden_servicio,$this->plazo_entrega_orden,$this->plazo_asignacion_orden);
        if ($conn->ejecutar($SQL))
        return true;
        else
        $this->idorden_servicio="";

    }

    function modificar(){

        global $conn;
        $SQL = sprintf("UPDATE orden_servicio SET idorden_servicio='%s',factura_idfactura='%s',tercero_idcliente='%s',numero_orden_servicio='%s',observacion_orden_servicio='%s',area_orden_servicio='%s',plazo_entrega_orden='%s',plazo_asignacion_orden='%s' WHERE idorden_servicio=%d "
        ,$this->idorden_servicio,$this->factura_idfactura,$this->tercero_idcliente,$this->numero_orden_servicio,$this->observacion_orden_servicio,$this->area_orden_servicio,$this->plazo_entrega_orden,$this->plazo_asignacion_orden,$this->idorden_servicio);
        if ($conn->ejecutar($SQL))
        return true;

    }

    //Realizar una consulta
    function consultar($cond = "",$ord = "",$lim=""){

        global $conn;
        $SQL = "SELECT * FROM orden_servicio";
        if (!empty($cond))
        $SQL.= " WHERE $cond";
        if (!empty($ord))
        $SQL.= " ORDER BY $ord";
        if (!empty($lim))
        $SQL.= " LIMIT $lim";
        $this->stmt = $conn->ejecutar($SQL);
        return $this->stmt;

    }

    function buscar($cod){

        global $conn;
        if ($this->consultar("idorden_servicio='$cod'"))
        return $this->siguiente();
        return false;

    }

    function siguiente(){

        global $conn;
        if ($row = $conn->Siguiente($this->stmt)){

            //Cargar los datos de la bd
            $this->idorden_servicio = $row["idorden_servicio"];
            $this->factura_idfactura = $row["factura_idfactura"];
            $this->tercero_idcliente = $row["tercero_idcliente"];
            $this->numero_orden_servicio = $row["numero_orden_servicio"];
            $this->observacion_orden_servicio = $row["observacion_orden_servicio"];
            $this->area_orden_servicio = $row["area_orden_servicio"];
            $this->plazo_entrega_orden = $row["plazo_entrega_orden"];
            $this->plazo_asignacion_orden = $row["plazo_asignacion_orden"];
            return $row;

        }
        return false;

    }
    function eliminar(){

        global $conn;
        if ($this->idorden_servicio){

            $SQL = sprintf("DELETE FROM orden_servicio WHERE idorden_servicio='%s'",$this->idorden_servicio);
            return $conn->ejecutar($SQL);

        }

    }

}//fin de la clase orden_servicio 

?>
