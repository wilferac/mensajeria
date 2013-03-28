<?


class tercero{

    var $idtercero;
    var $sucursal_idsucursal;
    var $tipo_identificacion_tercero;
    var $documento_tercero;
    var $nombres_tercero;
    var $apellidos_tercero;
    var $direccion_tercero;
    var $telefono_tercero;
    var $telefono2_tercero;
    var $celular_tercero;
    var $email_tercero;
    var $usuario_tercero;
    var $clave_tercero;
    var $tercero_idvendedor;
    var $comision_tercero;
    var $observaciones_tercero;
    //Variables de Control
    var $stmt; //Cursor para las consultas
    //Funciones
    function cargar(){

        if (isset($_GET["codigo"]) ){//se cargan las variables de la base de datos

            $this->buscar($_GET["codigo"]);

        }else{

            //Cargar las variables de la forma
            $this->idtercero= isset($_POST["idtercero"])?$_POST["idtercero"]:'';
            $this->sucursal_idsucursal= isset($_POST["sucursal_idsucursal"])?$_POST["sucursal_idsucursal"]:'';
            $this->tipo_identificacion_tercero= isset($_POST["tipo_identificacion_tercero"])?$_POST["tipo_identificacion_tercero"]:'';
            $this->documento_tercero= isset($_POST["documento_tercero"])?$_POST["documento_tercero"]:'';
            $this->nombres_tercero= isset($_POST["nombres_tercero"])?$_POST["nombres_tercero"]:'';
            $this->apellidos_tercero= isset($_POST["apellidos_tercero"])?$_POST["apellidos_tercero"]:'';
            $this->direccion_tercero= isset($_POST["direccion_tercero"])?$_POST["direccion_tercero"]:'';
            $this->telefono_tercero= isset($_POST["telefono_tercero"])?$_POST["telefono_tercero"]:'';
            $this->telefono2_tercero= isset($_POST["telefono2_tercero"])?$_POST["telefono2_tercero"]:'';
            $this->celular_tercero= isset($_POST["celular_tercero"])?$_POST["celular_tercero"]:'';
            $this->email_tercero= isset($_POST["email_tercero"])?$_POST["email_tercero"]:'';
            $this->usuario_tercero= isset($_POST["usuario_tercero"])?$_POST["usuario_tercero"]:'';
            $this->clave_tercero= isset($_POST["clave_tercero"])?$_POST["clave_tercero"]:'';
            $this->tercero_idvendedor= isset($_POST["tercero_idvendedor"])?$_POST["tercero_idvendedor"]:'';
            $this->comision_tercero= isset($_POST["comision_tercero"])?$_POST["comision_tercero"]:'';
            $this->observaciones_tercero= isset($_POST["observaciones_tercero"])?$_POST["observaciones_tercero"]:'';

        }

    }
    function agregar(){

        global $conn;
        //calcular el codigo
        $SQL = "select max(idtercero) from tercero";
        if ( $conn->ejecutar($SQL) && ($row=$conn->siguiente(NULL)) )

            $this->idtercero = $row[0]+1;

        else
        $this->idtercero = 1;
        $SQL = sprintf("INSERT INTO tercero (idtercero,sucursal_idsucursal,tipo_identificacion_tercero,documento_tercero,nombres_tercero,apellidos_tercero,direccion_tercero,telefono_tercero,telefono2_tercero,celular_tercero,email_tercero,usuario_tercero,clave_tercero,tercero_idvendedor,comision_tercero,observaciones_tercero)
        values('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')
        ",$this->idtercero,$this->sucursal_idsucursal,$this->tipo_identificacion_tercero,$this->documento_tercero,$this->nombres_tercero,$this->apellidos_tercero,$this->direccion_tercero,$this->telefono_tercero,$this->telefono2_tercero,$this->celular_tercero,$this->email_tercero,$this->usuario_tercero,$this->clave_tercero,$this->tercero_idvendedor,$this->comision_tercero,$this->observaciones_tercero);
        if ($conn->ejecutar($SQL))
        return true;
        else
        $this->idtercero="";

    }

    function modificar(){

        global $conn;
        $SQL = sprintf("UPDATE tercero SET idtercero='%s',sucursal_idsucursal='%s',tipo_identificacion_tercero='%s',documento_tercero='%s',nombres_tercero='%s',apellidos_tercero='%s',direccion_tercero='%s',telefono_tercero='%s',telefono2_tercero='%s',celular_tercero='%s',email_tercero='%s',usuario_tercero='%s',clave_tercero='%s',tercero_idvendedor='%s',comision_tercero='%s',observaciones_tercero='%s' WHERE idtercero=%d "
        ,$this->idtercero,$this->sucursal_idsucursal,$this->tipo_identificacion_tercero,$this->documento_tercero,$this->nombres_tercero,$this->apellidos_tercero,$this->direccion_tercero,$this->telefono_tercero,$this->telefono2_tercero,$this->celular_tercero,$this->email_tercero,$this->usuario_tercero,$this->clave_tercero,$this->tercero_idvendedor,$this->comision_tercero,$this->observaciones_tercero,$this->idtercero);
        if ($conn->ejecutar($SQL))
        return true;

    }

    //Realizar una consulta
    function consultar($cond = "",$ord = "",$lim=""){

        global $conn;
        $SQL = "SELECT * FROM tercero";
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
        if ($this->consultar("idtercero='$cod'"))
        return $this->siguiente();
        return false;

    }

    function siguiente(){

        global $conn;
        if ($row = $conn->Siguiente($this->stmt)){

            //Cargar los datos de la bd
            $this->idtercero = $row["idtercero"];
            $this->sucursal_idsucursal = $row["sucursal_idsucursal"];
            $this->tipo_identificacion_tercero = $row["tipo_identificacion_tercero"];
            $this->documento_tercero = $row["documento_tercero"];
            $this->nombres_tercero = $row["nombres_tercero"];
            $this->apellidos_tercero = $row["apellidos_tercero"];
            $this->direccion_tercero = $row["direccion_tercero"];
            $this->telefono_tercero = $row["telefono_tercero"];
            $this->telefono2_tercero = $row["telefono2_tercero"];
            $this->celular_tercero = $row["celular_tercero"];
            $this->email_tercero = $row["email_tercero"];
            $this->usuario_tercero = $row["usuario_tercero"];
            $this->clave_tercero = $row["clave_tercero"];
            $this->tercero_idvendedor = $row["tercero_idvendedor"];
            $this->comision_tercero = $row["comision_tercero"];
            $this->observaciones_tercero = $row["observaciones_tercero"];
            return $row;

        }
        return false;

    }
    function eliminar(){

        global $conn;
        if ($this->idtercero){

            $SQL = sprintf("DELETE FROM tercero WHERE idtercero='%s'",$this->idtercero);
            return $conn->ejecutar($SQL);

        }

    }

}//fin de la clase tercero 
?>
