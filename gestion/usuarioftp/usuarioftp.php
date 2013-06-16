<?
require("../../conexion/conexion.php");

class usuarioftp{

    var $usuario;
    var $clave;
    var $carpeta;
    //Variables de Control
    var $stmt; //Cursor para las consultas
    //Funciones
    function cargar(){

        if (isset($_GET["codigo"]) ){//se cargan las variables de la base de datos

            $this->buscar($_GET["codigo"]);

        }else{

            //Cargar las variables de la forma
            $this->usuario= isset($_POST["usuario"])?$_POST["usuario"]:'';
            $this->clave= isset($_POST["clave"])?$_POST["clave"]:'';
            $this->carpeta= isset($_POST["carpeta"])?$_POST["carpeta"]:'';

        }

    }
    function agregar(){

        global $conn;
        //calcular el codigo
        $SQL = "select max(usuario) from usuarioftp";
        if ( $conn->ejecutar($SQL) && ($row=$conn->siguiente(NULL)) )

            $this->usuario = $row[0]+1;

        else
        $this->usuario = 1;
        $SQL = sprintf("INSERT INTO usuarioftp (usuario,clave,carpeta)
        values('%s','%s','%s')
        ",$this->usuario,$this->clave,$this->carpeta);
        if ($conn->ejecutar($SQL))
        return true;
        else
        $this->usuario="";

    }

    function modificar(){

        global $conn;
        $SQL = sprintf("UPDATE usuarioftp SET usuario='%s',clave='%s',carpeta='%s' WHERE usuario=%d "
        ,$this->usuario,$this->clave,$this->carpeta,$this->usuario);
        if ($conn->ejecutar($SQL))
        return true;

    }

    //Realizar una consulta
    function consultar($cond = "",$ord = "",$lim=""){

        global $conn;
        $SQL = "SELECT * FROM usuarioftp";
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
        if ($this->consultar("usuario='$cod'"))
        return $this->siguiente();
        return false;

    }

    function siguiente(){

        global $conn;
        if ($row = $conn->Siguiente($this->stmt)){

            //Cargar los datos de la bd
            $this->usuario = $row["usuario"];
            $this->clave = $row["clave"];
            $this->carpeta = $row["carpeta"];
            return $row;

        }
        return false;

    }
    function eliminar(){

        global $conn;
        if ($this->usuario){

            $SQL = sprintf("DELETE FROM usuarioftp WHERE usuario='%s'",$this->usuario);
            return $conn->ejecutar($SQL);

        }

    }


}//fin de la clase usuarioftp 
?>
