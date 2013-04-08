<?
require("../../conexion/conexion.php");

class guia{

    var $idguia;
    var $numero_guia;
    var $orden_servicio_idorden_servicio;
    var $zona_idzona;
    var $causal_devolucion_idcausal_devolucion;
    var $manifiesto_idmanifiesto;
    var $producto_idproducto;
    var $ciudad_iddestino;
    var $valor_declarado_guia;
    var $nombre_destinatario_guia;
    var $direccion_destinatario_guia;
    var $telefono_destinatario_guia;
    var $dato1_destinatario_guia;
    var $dato2_destinatario_guia;
    var $peso_guia;
    var $ciudad_idorigen;
    var $tercero_idremitente;
    var $tercero_iddestinatario;
    //Variables de Control
    var $stmt; //Cursor para las consultas
    //Funciones
    function cargar(){

        if (isset($_GET["codigo"]) ){//se cargan las variables de la base de datos

            $this->buscar($_GET["codigo"]);

        }else{

            //Cargar las variables de la forma
            $this->idguia= isset($_POST["idguia"])?$_POST["idguia"]:'';
            $this->numero_guia= isset($_POST["numero_guia"])?$_POST["numero_guia"]:'';
            $this->orden_servicio_idorden_servicio= isset($_POST["orden_servicio_idorden_servicio"])?$_POST["orden_servicio_idorden_servicio"]:'';
            $this->zona_idzona= isset($_POST["zona_idzona"])?$_POST["zona_idzona"]:'';
            $this->causal_devolucion_idcausal_devolucion= isset($_POST["causal_devolucion_idcausal_devolucion"])?$_POST["causal_devolucion_idcausal_devolucion"]:'';
            $this->manifiesto_idmanifiesto= isset($_POST["manifiesto_idmanifiesto"])?$_POST["manifiesto_idmanifiesto"]:'';
            $this->producto_idproducto= isset($_POST["producto_idproducto"])?$_POST["producto_idproducto"]:'';
            $this->ciudad_iddestino= isset($_POST["ciudad_iddestino"])?$_POST["ciudad_iddestino"]:'';
            $this->valor_declarado_guia= isset($_POST["valor_declarado_guia"])?$_POST["valor_declarado_guia"]:'';
            $this->nombre_destinatario_guia= isset($_POST["nombre_destinatario_guia"])?$_POST["nombre_destinatario_guia"]:'';
            $this->direccion_destinatario_guia= isset($_POST["direccion_destinatario_guia"])?$_POST["direccion_destinatario_guia"]:'';
            $this->telefono_destinatario_guia= isset($_POST["telefono_destinatario_guia"])?$_POST["telefono_destinatario_guia"]:'';
            $this->dato1_destinatario_guia= isset($_POST["dato1_destinatario_guia"])?$_POST["dato1_destinatario_guia"]:'';
            $this->dato2_destinatario_guia= isset($_POST["dato2_destinatario_guia"])?$_POST["dato2_destinatario_guia"]:'';
            $this->peso_guia= isset($_POST["peso_guia"])?$_POST["peso_guia"]:'';
            $this->ciudad_idorigen= isset($_POST["ciudad_idorigen"])?$_POST["ciudad_idorigen"]:'';
            $this->tercero_idremitente= isset($_POST["tercero_idremitente"])?$_POST["tercero_idremitente"]:'';
            $this->tercero_iddestinatario= isset($_POST["tercero_iddestinatario"])?$_POST["tercero_iddestinatario"]:'';

        }

    }
    function agregar(){

        global $conn;
        //calcular el codigo
        $SQL = "select max(idguia) from guia";
        if ( $conn->ejecutar($SQL) && ($row=$conn->siguiente(NULL)) )

            $this->idguia = $row[0]+1;

        else
        $this->idguia = 1;
        $SQL = sprintf("INSERT INTO guia (idguia,numero_guia,orden_servicio_idorden_servicio,zona_idzona,causal_devolucion_idcausal_devolucion,manifiesto_idmanifiesto,producto_idproducto,ciudad_iddestino,valor_declarado_guia,nombre_destinatario_guia,direccion_destinatario_guia,telefono_destinatario_guia,dato1_destinatario_guia,dato2_destinatario_guia,peso_guia,ciudad_idorigen,tercero_idremitente,tercero_iddestinatario)
        values('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')
        ",$this->idguia,$this->numero_guia,$this->orden_servicio_idorden_servicio,$this->zona_idzona,$this->causal_devolucion_idcausal_devolucion,$this->manifiesto_idmanifiesto,$this->producto_idproducto,$this->ciudad_iddestino,$this->valor_declarado_guia,$this->nombre_destinatario_guia,$this->direccion_destinatario_guia,$this->telefono_destinatario_guia,$this->dato1_destinatario_guia,$this->dato2_destinatario_guia,$this->peso_guia,$this->ciudad_idorigen,$this->tercero_idremitente,$this->tercero_iddestinatario);
        if ($conn->ejecutar($SQL))
        return true;
        else
        $this->idguia="";

    }

    function modificar(){

        global $conn;
        $SQL = sprintf("UPDATE guia SET idguia='%s',numero_guia='%s',orden_servicio_idorden_servicio='%s',zona_idzona='%s',causal_devolucion_idcausal_devolucion='%s',manifiesto_idmanifiesto='%s',producto_idproducto='%s',ciudad_iddestino='%s',valor_declarado_guia='%s',nombre_destinatario_guia='%s',direccion_destinatario_guia='%s',telefono_destinatario_guia='%s',dato1_destinatario_guia='%s',dato2_destinatario_guia='%s',peso_guia='%s',ciudad_idorigen='%s',tercero_idremitente='%s',tercero_iddestinatario='%s' WHERE idguia=%d "
        ,$this->idguia,$this->numero_guia,$this->orden_servicio_idorden_servicio,$this->zona_idzona,$this->causal_devolucion_idcausal_devolucion,$this->manifiesto_idmanifiesto,$this->producto_idproducto,$this->ciudad_iddestino,$this->valor_declarado_guia,$this->nombre_destinatario_guia,$this->direccion_destinatario_guia,$this->telefono_destinatario_guia,$this->dato1_destinatario_guia,$this->dato2_destinatario_guia,$this->peso_guia,$this->ciudad_idorigen,$this->tercero_idremitente,$this->tercero_iddestinatario,$this->idguia);
        if ($conn->ejecutar($SQL))
        return true;

    }

    //Realizar una consulta
    function consultar($cond = "",$ord = "",$lim=""){

        global $conn;
        $SQL = "SELECT * FROM guia";
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
        if ($this->consultar("idguia='$cod'"))
        return $this->siguiente();
        return false;

    }

    function siguiente(){

        global $conn;
        if ($row = $conn->Siguiente($this->stmt)){

            //Cargar los datos de la bd
            $this->idguia = $row["idguia"];
            $this->numero_guia = $row["numero_guia"];
            $this->orden_servicio_idorden_servicio = $row["orden_servicio_idorden_servicio"];
            $this->zona_idzona = $row["zona_idzona"];
            $this->causal_devolucion_idcausal_devolucion = $row["causal_devolucion_idcausal_devolucion"];
            $this->manifiesto_idmanifiesto = $row["manifiesto_idmanifiesto"];
            $this->producto_idproducto = $row["producto_idproducto"];
            $this->ciudad_iddestino = $row["ciudad_iddestino"];
            $this->valor_declarado_guia = $row["valor_declarado_guia"];
            $this->nombre_destinatario_guia = $row["nombre_destinatario_guia"];
            $this->direccion_destinatario_guia = $row["direccion_destinatario_guia"];
            $this->telefono_destinatario_guia = $row["telefono_destinatario_guia"];
            $this->dato1_destinatario_guia = $row["dato1_destinatario_guia"];
            $this->dato2_destinatario_guia = $row["dato2_destinatario_guia"];
            $this->peso_guia = $row["peso_guia"];
            $this->ciudad_idorigen = $row["ciudad_idorigen"];
            $this->tercero_idremitente = $row["tercero_idremitente"];
            $this->tercero_iddestinatario = $row["tercero_iddestinatario"];
            return $row;

        }
        return false;

    }
    function eliminar(){

        global $conn;
        if ($this->idguia){

            $SQL = sprintf("DELETE FROM guia WHERE idguia='%s'",$this->idguia);
            return $conn->ejecutar($SQL);

        }

    }

}//fin de la clase guia 
?>
