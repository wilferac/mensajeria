<?
session_start();

/**
 * localhost
 */
define('CONEXION', '/var/www/Mensajeria/');
include ('/var/www/Mensajeria/conexion/conexion.php');
define('RAIZ', "http://localhost/Mensajeria");

/**
 * servidor
 */
//define('CONEXION', '/home/innovate/public_html/Mensajeria/');
//include ('/home/innovate/public_html/Mensajeria/conexion/conexion.php');
//define('RAIZ', "http://grupoinnovate.com/Mensajeria");

class operacion
{

    function consultar($SQL, $ord = "", $lim = "")
    {

        global $conn;
        if (!empty($ord))
            $SQL.= " ORDER BY $ord";
        if (!empty($lim))
            $SQL.= " LIMIT $lim";
        $this->stmt = $conn->ejecutar($SQL);
        return $this->stmt;
    }

    function obtenerFechayHora($fechauhora, $formatofecha = 'd/m/Y', $formatohora = 'g:i:a')
    {

        $fecha = date($formatofecha);
        $hora = date('g'); // ojo algunos sistemas aceptan la hora con -1
        $min = date('i');
        $ampm = date('a');
        $hora = $hora . ":" . $min . $ampm;

        if ($fechauhora == "fecha")
            return $fecha;
        if ($fechauhora == "fechayhora")
            return $fecha = $fecha . " " . $hora;
        if ($fechauhora == "hora")
            return $hora;
    }

    function obtenerDatosCiudad($nombreciudaddestino, $nomDep)
    {
        $nombreciudaddestino = strtolower(trim($nombreciudaddestino));
        $nomDep = strtolower(trim($nomDep));
        $SQL = "SELECT c.idciudad, c.nombre_ciudad, c.departamento_iddepartamento,  c.pais_ciudad , d.`nombre_departamento`
FROM ciudad c
INNER JOIN departamento  d ON d.`iddepartamento` = c.`departamento_iddepartamento`
WHERE LOWER( TRIM(c.nombre_ciudad)) = LOWER( TRIM('$nombreciudaddestino' ))
AND LOWER(TRIM(d.nombre_departamento))  =  LOWER( TRIM('$nomDep'))
               ";
        // echo("<br>" . $SQL . "<br>");
        $res = $this->consultar($SQL);
        $fila = mysql_fetch_assoc($res);
        $rta = $fila['idciudad'];

        return $rta;
    }

//+++*************************************************************************
    function calcularTipoProducto($idpaisdorigen, $iddepartamentoorigen, $idciudadorigen, $destino, $idciudaddestino = NULL)
    {


        $destino = strtolower(trim($destino));

        $datosciudaddestino = array();

        if ($idciudaddestino == NULL)
            $SQL = "SELECT idciudad, nombre_ciudad, departamento_iddepartamento, pais_ciudad, trayecto_especial_ciudad from ciudad WHERE LOWER( TRIM(nombre_ciudad)) = \"$destino\"";
        else
            $SQL = "SELECT idciudad, nombre_ciudad, departamento_iddepartamento, pais_ciudad, trayecto_especial_ciudad from ciudad WHERE idciudad = $idciudaddestino";
        $res = $this->consultar($SQL);

        $datosciudaddestino = mysql_fetch_assoc($res);

        if ($datosciudaddestino["trayecto_especial_ciudad"] == NULL)
        {
            if ($idpaisdorigen == $datosciudaddestino["pais_ciudad"])
            {
                if ($iddepartamentoorigen == $datosciudaddestino["departamento_iddepartamento"])
                {  //echo $idciudadorigen." - ".$datosciudaddestino["idciudad"];
                    if ($idciudadorigen == $datosciudaddestino["idciudad"]) //Urbano
                    {
                        return 4;
                    } else // Regional
                    {
                        return 2;
                    }
                } else //Nacional
                {
                    return 1;
                }
            }
            //else Internacional	
        } else //else trayecto especial
        {
            return 3;
        }
    }

    function consultarmultiple($objendondebuscar, $cond)
    {
        $res = $objendondebuscar->consultar($cond);
        if (mysql_num_rows($res) > 0)
        {
            $fila = mysql_fetch_assoc($res);
            return $fila;
        }
        return false;
    }

    function redireccionar($mensaje, $ir_a, $confirmar = false)
    {
        if ($confirmar)
        {
            ?>
            <div align="center" class="error"><?= $mensaje ?></div><br>
            <div align="center"><a href="<?= $ir_a ?>" target="_top">Si</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="index.php" target="_top">No</a></div>

            <?
        } else
        {
            ?>
            <div align="center" class="error"><?= $mensaje ?></div><br>
            <div align="center"><a href="<?= $ir_a ?>" target="_self">Volver</a></div>

            <?
            exit();
        }
    }

//*************************************************************************
    function registrarManifiestoMensajero($arrayGuias, $datosmensajero, $iddestajo, $tiposmensajeros, $plazo, $observaciones, $idciudad, $zonamensajero, $tarifadestajo = 0)
    {
        $estadomovimiento = 3; //Creacion
        $flagbloque = false;
        $idusuario = $_SESSION['datosinicio']['idtercero'];

        $conex = new conexion();
        $qtrans = "SET AUTOCOMMIT=0;";
        $sac = $conex->ejecutar($qtrans);
        $qtrans = "BEGIN;";
        $sac = $conex->ejecutar($qtrans);


        $manifiestos = new manifiesto();
        $movimientos = new movimiento();
        $guias = new guia();
        $operaciones = new operacion();

        $fecha = $operaciones->obtenerFechayHora("fecha");
        $hora = $operaciones->obtenerFechayHora("hora");
        //$datosmensajero = $_POST['mensajero']; // OJO por parametro

        $idtercero = 1;
        $idsucursal = 1; // asignar un numero para saber que no tiene sucursal asignada (deberia ser null)	
//*************** Data para tabla manifiesto*********

        $manifiestos->generarNumeroManifiesto();
        //$plazo = $_POST["plazo"];  // OJO

        if ($tiposmensajeros == 'propio')
            $idmensajero = substr($datosmensajero, 0, strrpos($datosmensajero, "-"));
        elseif ($tiposmensajeros == 'destajo')
            $idmensajero = substr($iddestajo, 0, strrpos($iddestajo, "-"));

        $manifiestos->tercero_idmensajero_recibe = $idmensajero;

        $idzonamensajero = substr($zonamensajero, 0, strrpos($zonamensajero, "-"));
        $manifiestos->zonamensajero = $idzonamensajero;

        $manifiestos->tercero_idmensajero_entrega = null;


        $manifiestos->sucursal_idsucursal = $idsucursal;
        $manifiestos->tercero_idaliado = $idtercero;
        $manifiestos->plazo_entrega_manifiesto = $plazo;
        $manifiestos->tarifadestajo = $tarifadestajo;

        $rmani = $manifiestos->agregar();
        $num_manifiesto = $manifiestos->num_manifiesto;

//***************Data para movimientos**************

        $movimientos->estado_idestado = $estadomovimiento;
        $movimientos->tercero_idusuario = $idusuario;
        $movimientos->tercero_idaliado = $idtercero;

        $movimientos->fecha_movimiento = $fecha;
        $movimientos->hora_movimiento = $hora;
        $movimientos->recogida_idrecogida = null;
        $movimientos->orden_servicio_idorden_servicio = null;
        $movimientos->manifiesto_idmanifiesto = $manifiestos->idmanifiesto;
        $movimientos->sucursal_idsucursal = $idsucursal;
        $movimientos->asignacion_guias_idasignacion_guias = null; //ojo se debe updatear despues para asociarlo
        $movimientos->tarifa_idtarifa = null; //ojo se debe updatear despues para asociarlo
        //$observaciones = $_POST['observaciones']; // OJO
        //$idciudad = $_POST['ciudad']; 
//**************************************************	

        $guiasarray = array();
        $mensguiasYaManif = '';
        $mensguiasnoenc = '';
        $flagmodiguia = false;

        if (!$flagbloque)
        {
            foreach ($arrayGuias as $clave => $valor)
            {

                $cond = "numero_guia = $valor";
                $res = $guias->consultar($cond);
                if (mysql_num_rows($res) > 0)  // existe la guia 
                {

                    $cond = "numero_guia = $valor and manifiesto_idmanifiesto = 0"; // existe la guia 
                    $res = $guias->consultar($cond);
                    if (mysql_num_rows($res) > 0)
                    {
                        $stringset = "manifiesto_idmanifiesto=" . $manifiestos->idmanifiesto;

                        $rmodiguia = $guias->modificar2($stringset, $valor);  //update a manifiesto
                        //	if ($rmodiguia === false)
                        $flagmodiguia = true;

                        //Para obtener el idguia de guia y registrarlo en Movimiento 		
                        $sent = "Select idguia FROM guia WHERE numero_guia = $valor";
                        $r = $operaciones->consultar($sent);
                        if (mysql_num_rows($r) > 0)
                        {
                            $dat = mysql_fetch_assoc($r);

                            $idguia = $dat["idguia"];

                            $movimientos->guia_idguia = $idguia;
                            $rmov = $movimientos->agregar();
                        }
                        //_____________________________________________________________			
                    }
                    else
                        $mensguiasYaManif.= $valor . " \\n ";
                }
                else
                {
                    $mensguiasnoenc.= $valor . " \\n ";
                }
            }
        } else
        {
            for ($k = $desde; $k <= $hasta; $k++)
            {

                $cond = "numero_guia=$k";
                $res = $guias->consultar($cond);
                if (mysql_num_rows($res) > 0)
                {
                    $stringset = "manifiesto_idmanifiesto=" . $manifiestos->idmanifiesto;
                    $rmodiguia = $guias->modificar2($stringset, $valor);  //update a manifiesto
                    if ($rmodiguia === false)
                        $flagmodiguia == false;
                }
                else
                    $mensguiasnoenc.= $valor . " \n ";
            }
        }

        if ($mensguiasYaManif != '' || $mensguiasnoenc != '')
        {
            $texto = "";
            if ($mensguiasYaManif != '')
                $texto = "Guia(s) ya tiene(n) Manifiesto:\\n$mensguiasYaManif\\n\\n";
            if ($mensguiasnoenc != '')
                $texto.= "No se encuentra(n) la(s) siguiente(s) guia(s):\\n$mensguiasnoenc";
            ?>
            <script language="javascript" type="text/javascript">
                alert("<?= $texto ?>");
            </script>	
            <?
        }


        if ($rmani === true && $rmov === true && $flagmodiguia === true)
        {
            $qtrans = "COMMIT";
            $sac = $conex->ejecutar($qtrans);
            ?>	
            <script language="javascript" type="text/javascript">
                var mensaje = "Registro Exitoso. <br>N�mero Manifiesto <?= $num_manifiesto ?>";
                //window.location.href='consulta.php?mensaje='+mensaje;
            </script>

            <?
        }
        else
            
            ?>
            <script language="javascript" type="text/javascript">
                var mensaje = "Registro NO Exitoso";
                //window.location.href='consulta.php?mensaje='+mensaje;
            </script>
        <?
    }

//*************************************************************************
    function menu()
    {
        ?>
        <script src="<?= RAIZ ?>/SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
        <link href="<?= RAIZ ?>/SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css">

        <div id="menu" style=" margin-left:auto; margin-right:auto; width:70%; clear: both;">
            <ul id="MenuBar1" class="MenuBarHorizontal" style="background-color:#FFFF00" >
                <li>
                    <a  href="<?= RAIZ ?>/redireccionador.php">::Inicio::</a>
                </li>

                <li><a class="MenuBarItemSubmenu" style="text-decoration:none">Gesti&oacute;n</a>
                    <ul>
                        <li><a  class="MenuBarItemSubmenu" href="#">Terceros</a>
                            <ul>
                                <li><a href="<?= RAIZ ?>/gestion/terceros/consulta.php">Consultar</a></li>
                                <li><a href="<?= RAIZ ?>/gestion/terceros/add.php">Crear Tercero</a></li>
                                <li><a href="<?= RAIZ ?>/gestion/terceros/addAliaPV.php">Crear Puntos de Venta o Aliados</a></li>
                            </ul>
                        </li>

                        <li><a  class="MenuBarItemSubmenu" href="#">Sucursales</a>
                            <ul>
                                <li><a href="<?= RAIZ ?>/gestion/sucursal/consulta.php">Consultar</a></li>
                                <li><a href="<?= RAIZ ?>/gestion/sucursal/add.php">Crear </a></li>

                            </ul>
                        </li>
                        <li><a  class="MenuBarItemSubmenu" href="#">Productos</a>
                            <ul>
                                <li><a href="<?= RAIZ ?>/gestion/productos/consulta.php">Consultar</a></li>
                                <li><a href="<?= RAIZ ?>/gestion/productos/add.php">Crear </a></li>    
                            </ul>

                        </li>
                    </ul>
                </li>


                <li><a href="#"  style="text-decoration:none">Masivo</a>
                    <ul>
                        <li><a class="MenuBarItemSubmenu" href="#">Orden de Servicio</a>
                            <ul>
                                <li><a href="<?= RAIZ ?>/gestion/ordendeservicio/consulta.php">Consultar</a></li>
                                <li><a href="<?= RAIZ ?>/gestion/ordendeservicio/add.php">Crear</a></li>
                            </ul>
                        </li>
                        <li><a class="MenuBarItemSubmenu" href="#">Manifiesto</a>
                            <ul>
                                <li><a href="<?= RAIZ ?>/gestion/manifiesto/consulta.php">Consultar</a></li>
                                <li><a href="<?= RAIZ ?>/gestion/manifiesto/add.php">Crear</a></li>
                            </ul>
                        </li>
                        <li><a class="MenuBarItemSubmenu" href="#">Gu&iacute;as</a>
                            <ul>
                                <li><a href="<?= RAIZ ?>/gestion/guia/buscar.php">Consultar</a></li>

                            </ul>
                        </li>

                        <li><a  href="<?= RAIZ ?>/gestion/guia/pistoleocausal.php">Pistoleo</a>
                        </li>

                    </ul>

                </li>

                <li><a class="MenuBarItemSubmenu" href="#"  style="text-decoration:none">Unitario</a>
                    <ul>

                        <li><a  class="MenuBarItemSubmenu" href="#">Guia</a>
                            <ul>
                                <li><a  href="<?= RAIZ ?>/gestion/ordendeservicio/addosunitario.php">Digitar</a>
                                </li>
                                <li><a  href="<?= RAIZ ?>/gestion/unitario/guia/consulta.php">Consultar</a>
                                </li>
                            </ul>
                        </li>

                        <li><a  href="<?= RAIZ ?>/gestion/guia/asignar.php">Consultar y/o Asignar Gu&iacute;as</a>
                        </li>
                        <li><a  href="<?= RAIZ ?>/gestion/guia/consultarasignaciones.php">Consultar todas las Asignaciones</a>
                        </li>

                    </ul>
                </li>
                <li><a class="MenuBarItemSubmenu" href="#"  style="text-decoration:none">Informes</a>
                    <ul>
                        <li><a  href="<?= RAIZ ?>/gestion/informes/tiposproducto.php">Tipos de Producto</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <script type="text/javascript">

            var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown: "<?= RAIZ ?>/SpryAssets/SpryMenuBarDownHover.gif", imgRight: "<?= RAIZ ?>/SpryAssets/SpryMenuBarRightHover.gif"});
        </script> 
        <?
    }

}

/* * **********************************************************
  CLASE ORDEN DE SERVICIO

 * *********************************************************** */

class orden_servicio
{

    var $idorden_servicio;
    var $factura_idfactura;
    var $tercero_idcliente;
    var $numero_orden_servicio;
    var $fechaentrada;
    var $observacion_orden_servicio;
    var $unidades;
    var $area_orden_servicio;
    var $plazo_entrega_orden;
    var $plazo_asignacion_orden;
//Variables de Control
    var $stmt; //Cursor para las consultas

//Funciones

    function cargar()
    {
        if (isset($_GET["codigo"]))
        {//se cargan las variables de la base de datos
            $this->buscar($_GET["codigo"]);
        } else
        {
//Cargar las variables de la forma
            $this->idorden_servicio = isset($_POST["idorden_servicio"]) ? $_POST["idorden_servicio"] : '';
            $this->factura_idfactura = isset($_POST["factura_idfactura"]) ? $_POST["factura_idfactura"] : '';
            $this->tercero_idcliente = isset($_POST["tercero_idcliente"]) ? $_POST["tercero_idcliente"] : '';
            $this->numero_orden_servicio = isset($_POST["numero_orden_servicio"]) ? $_POST["numero_orden_servicio"] : '';
            $this->fechaentrada = isset($_POST["fechaentrada"]) ? $_POST["fechaentrada"] : '';
            $this->observacion_orden_servicio = isset($_POST["observacion_orden_servicio"]) ? $_POST["observacion_orden_servicio"] : '';
            $this->unidades = isset($_POST["unidades"]) ? $_POST["unidades"] : '';
            $this->area_orden_servicio = isset($_POST["area_orden_servicio"]) ? $_POST["area_orden_servicio"] : '';
            $this->plazo_entrega_orden = isset($_POST["plazo_entrega_orden"]) ? $_POST["plazo_entrega_orden"] : '';
            $this->plazo_asignacion_orden = isset($_POST["plazo_asignacion_orden"]) ? $_POST["plazo_asignacion_orden"] : '';
        }
    }

    function agregar()
    {
        global $conn;
//calcular el codigo
        $SQL = "select max(idorden_servicio) from orden_servicio";
        if ($conn->ejecutar($SQL) && ($row = $conn->siguiente(NULL)))
            $this->idorden_servicio = $row[0] + 1;
        else
            $this->idorden_servicio = 1;
        $SQL = sprintf("INSERT INTO orden_servicio (idorden_servicio,factura_idfactura,tercero_idcliente,numero_orden_servicio,fechaentrada,observacion_orden_servicio,unidades,area_orden_servicio,plazo_entrega_orden,plazo_asignacion_orden)
values('%s',%s,'%s','%s','%s','%s','%s','%s','%s','%s')
", $this->idorden_servicio, $this->factura_idfactura, $this->tercero_idcliente, $this->numero_orden_servicio, $this->fechaentrada, $this->observacion_orden_servicio, $this->unidades, $this->area_orden_servicio, $this->plazo_entrega_orden, $this->plazo_asignacion_orden);
        if ($conn->ejecutar($SQL))
            return true;
        else
            $this->idorden_servicio = "";
    }

    function modificar()
    {
        global $conn;
        $SQL = sprintf("UPDATE orden_servicio SET idorden_servicio='%s',factura_idfactura='%s',tercero_idcliente='%s',numero_orden_servicio='%s',fechaentrada='%s',observacion_orden_servicio='%s',unidades='%s',area_orden_servicio='%s',plazo_entrega_orden='%s',plazo_asignacion_orden='%s' WHERE idorden_servicio=%d "
                , $this->idorden_servicio, $this->factura_idfactura, $this->tercero_idcliente, $this->numero_orden_servicio, $this->fechaentrada, $this->observacion_orden_servicio, $this->unidades, $this->area_orden_servicio, $this->plazo_entrega_orden, $this->plazo_asignacion_orden, $this->idorden_servicio);
        if ($conn->ejecutar($SQL))
            return true;
    }

    function modificar2($stringset, $idos)
    {
        global $conn;
        $SQL = "UPDATE orden_servicio SET $stringset  WHERE idorden_servicio=$idos";
        if ($conn->ejecutar($SQL))
            return true;
    }

//Realizar una consulta 
    function consultar($cond = "", $ord = "", $lim = "")
    {
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

    function buscar($cod)
    {
        global $conn;
        if ($this->consultar("idorden_servicio='$cod'"))
            return $this->siguiente();
        return false;
    }

    function siguiente()
    {
        global $conn;
        if ($row = $conn->Siguiente($this->stmt))
        {
//Cargar los datos de la bd
            $this->idorden_servicio = $row["idorden_servicio"];
            $this->factura_idfactura = $row["factura_idfactura"];
            $this->tercero_idcliente = $row["tercero_idcliente"];
            $this->numero_orden_servicio = $row["numero_orden_servicio"];
            $this->fechaentrada = $row["fechaentrada"];
            $this->observacion_orden_servicio = $row["observacion_orden_servicio"];
            $this->unidades = $row["unidades"];
            $this->area_orden_servicio = $row["area_orden_servicio"];
            $this->plazo_entrega_orden = $row["plazo_entrega_orden"];
            $this->plazo_asignacion_orden = $row["plazo_asignacion_orden"];
            return $row;
        }
        return false;
    }

    function eliminar()
    {
        global $conn;
        if ($this->idorden_servicio)
        {
            $SQL = sprintf("DELETE FROM orden_servicio WHERE idorden_servicio='%s'", $this->idorden_servicio);
            return $conn->ejecutar($SQL);
        }
    }

    function generarNumeroOS()
    {
        global $conn;

        $SQL = "select max(numero_orden_servicio) from orden_servicio";
        if ($conn->ejecutar($SQL) && ($row = $conn->siguiente(NULL)))
        {
            return $this->numero_orden_servicio = $row[0] + 1;
        } else
        {
            $this->numero_orden_servicio = 1;
        }
    }

}

//fin de la clase orden_servicio


/* * **********************************************************
  CLASE GUIA2 // creada para no "da�ar" la clase original guia

 * *********************************************************** */

class guia2
{

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
    var $peso_guia;
    var $ciudad_idorigen;
    var $tercero_idremitente;
    var $tercero_iddestinatario;
//Variables de Control
    var $stmt; //Cursor para las consultas

//Funciones

    function cargar()
    {
        if (isset($_GET["codigo"]))
        {//se cargan las variables de la base de datos
            $this->buscar($_GET["codigo"]);
        } else
        {
//Cargar las variables de la forma
            $this->idguia = isset($_POST["idguia"]) ? $_POST["idguia"] : '';
            $this->numero_guia = isset($_POST["numero_guia"]) ? $_POST["numero_guia"] : '';
            $this->orden_servicio_idorden_servicio = isset($_POST["orden_servicio_idorden_servicio"]) ? $_POST["orden_servicio_idorden_servicio"] : '';
            $this->zona_idzona = isset($_POST["zona_idzona"]) ? $_POST["zona_idzona"] : '';
            $this->causal_devolucion_idcausal_devolucion = isset($_POST["causal_devolucion_idcausal_devolucion"]) ? $_POST["causal_devolucion_idcausal_devolucion"] : '';
            $this->manifiesto_idmanifiesto = isset($_POST["manifiesto_idmanifiesto"]) ? $_POST["manifiesto_idmanifiesto"] : '';
            $this->producto_idproducto = isset($_POST["producto_idproducto"]) ? $_POST["producto_idproducto"] : '';
            $this->ciudad_iddestino = isset($_POST["ciudad_iddestino"]) ? $_POST["ciudad_iddestino"] : '';
            $this->valor_declarado_guia = isset($_POST["valor_declarado_guia"]) ? $_POST["valor_declarado_guia"] : '';
            $this->nombre_destinatario_guia = isset($_POST["nombre_destinatario_guia"]) ? $_POST["nombre_destinatario_guia"] : '';
            $this->direccion_destinatario_guia = isset($_POST["direccion_destinatario_guia"]) ? $_POST["direccion_destinatario_guia"] : '';
            $this->telefono_destinatario_guia = isset($_POST["telefono_destinatario_guia"]) ? $_POST["telefono_destinatario_guia"] : '';
            $this->peso_guia = isset($_POST["peso_guia"]) ? $_POST["peso_guia"] : '';
            $this->ciudad_idorigen = isset($_POST["ciudad_idorigen"]) ? $_POST["ciudad_idorigen"] : '';
            $this->tercero_idremitente = isset($_POST["tercero_idremitente"]) ? $_POST["tercero_idremitente"] : '';
            $this->tercero_iddestinatario = isset($_POST["tercero_iddestinatario"]) ? $_POST["tercero_iddestinatario"] : '';
        }
    }

    function agregar()
    {
        global $conn;
//calcular el codigo
//        $SQL = "select max(idguia) from guia";
//        if ($conn->ejecutar($SQL) && ($row = $conn->siguiente(NULL)))
//            $this->idguia = $row[0] + 1;
//        else
//            $this->idguia = 1;
        $SQL = "INSERT INTO guia (numero_guia,orden_servicio_idorden_servicio,zona_idzona,causal_devolucion_idcausal_devolucion,manifiesto_idmanifiesto,producto_idproducto,ciudad_iddestino,valor_declarado_guia,nombre_destinatario_guia,direccion_destinatario_guia,telefono_destinatario_guia,peso_guia,ciudad_idorigen,tercero_idremitente,tercero_iddestinatario)
            values('$this->numero_guia', $this->orden_servicio_idorden_servicio, $this->zona_idzona, $this->causal_devolucion_idcausal_devolucion, $this->manifiesto_idmanifiesto, $this->producto_idproducto, $this->ciudad_iddestino, '$this->valor_declarado_guia', '$this->nombre_destinatario_guia', '$this->direccion_destinatario_guia', '$this->telefono_destinatario_guia', '$this->peso_guia', $this->ciudad_idorigen, $this->tercero_idremitente,$this->tercero_iddestinatario) ON DUPLICATE KEY UPDATE orden_servicio_idorden_servicio=$this->orden_servicio_idorden_servicio,zona_idzona=$this->zona_idzona,causal_devolucion_idcausal_devolucion=$this->causal_devolucion_idcausal_devolucion,manifiesto_idmanifiesto=$this->manifiesto_idmanifiesto,producto_idproducto=$this->producto_idproducto,ciudad_iddestino=$this->ciudad_iddestino,valor_declarado_guia='$this->valor_declarado_guia',nombre_destinatario_guia='$this->nombre_destinatario_guia',direccion_destinatario_guia='$this->direccion_destinatario_guia',telefono_destinatario_guia='$this->telefono_destinatario_guia',peso_guia='$this->peso_guia',ciudad_idorigen=$this->ciudad_idorigen,tercero_idremitente=$this->tercero_idremitente,tercero_iddestinatario=$this->tercero_iddestinatario";
        if ($conn->ejecutar($SQL))
            return true;
        else
            $this->idguia = "";
    }

    function agregarTemp()
    {
        global $conn;
//calcular el codigo
//        $SQL = "select max(idguia) from guia";
//        if ($conn->ejecutar($SQL) && ($row = $conn->siguiente(NULL)))
//            $this->idguia = $row[0] + 1;
//        else
//            $this->idguia = 1;
        $SQL = "INSERT INTO guia (numero_guia,orden_servicio_idorden_servicio,zona_idzona,causal_devolucion_idcausal_devolucion,manifiesto_idmanifiesto,producto_idproducto,ciudad_iddestino,valor_declarado_guia,nombre_destinatario_guia,direccion_destinatario_guia,telefono_destinatario_guia,peso_guia,ciudad_idorigen,tercero_idremitente)
        values('$this->numero_guia', $this->orden_servicio_idorden_servicio, $this->zona_idzona, $this->causal_devolucion_idcausal_devolucion, $this->manifiesto_idmanifiesto, $this->producto_idproducto, $this->ciudad_iddestino, '$this->valor_declarado_guia', '$this->nombre_destinatario_guia', '$this->direccion_destinatario_guia', '$this->telefono_destinatario_guia', '$this->peso_guia', $this->ciudad_idorigen, $this->tercero_idremitente) ON DUPLICATE KEY UPDATE orden_servicio_idorden_servicio=$this->orden_servicio_idorden_servicio,zona_idzona=$this->zona_idzona,causal_devolucion_idcausal_devolucion=$this->causal_devolucion_idcausal_devolucion,manifiesto_idmanifiesto=$this->manifiesto_idmanifiesto,producto_idproducto=$this->producto_idproducto,ciudad_iddestino=$this->ciudad_iddestino,valor_declarado_guia='$this->valor_declarado_guia',nombre_destinatario_guia='$this->nombre_destinatario_guia',direccion_destinatario_guia='$this->direccion_destinatario_guia',telefono_destinatario_guia='$this->telefono_destinatario_guia',peso_guia='$this->peso_guia',ciudad_idorigen=$this->ciudad_idorigen,tercero_idremitente=$this->tercero_idremitente";
        if ($conn->ejecutar($SQL))
            return true;
        else
            $this->idguia = "";
    }

    function modificar()
    {
        global $conn;
        $SQL = sprintf("UPDATE guia SET idguia='%s',numero_guia='%s',orden_servicio_idorden_servicio='%s',zona_idzona='%s',causal_devolucion_idcausal_devolucion='%s',manifiesto_idmanifiesto='%s',producto_idproducto='%s',ciudad_iddestino='%s',valor_declarado_guia='%s',nombre_destinatario_guia='%s',direccion_destinatario_guia='%s',telefono_destinatario_guia='%s',peso_guia='%s',ciudad_idorigen='%s',tercero_idremitente='%s',tercero_iddestinatario='%s' WHERE idguia=%d "
                , $this->idguia, $this->numero_guia, $this->orden_servicio_idorden_servicio, $this->zona_idzona, $this->causal_devolucion_idcausal_devolucion, $this->manifiesto_idmanifiesto, $this->producto_idproducto, $this->ciudad_iddestino, $this->valor_declarado_guia, $this->nombre_destinatario_guia, $this->direccion_destinatario_guia, $this->telefono_destinatario_guia, $this->peso_guia, $this->ciudad_idorigen, $this->tercero_idremitente, $this->tercero_iddestinatario, $this->idguia);
        if ($conn->ejecutar($SQL))
            return true;
    }

//Realizar una consulta 
    function consultar($cond = "", $ord = "", $lim = "")
    {
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

    function buscar($cod)
    {
        global $conn;
        if ($this->consultar("idguia='$cod'"))
            return $this->siguiente();
        return false;
    }

    function siguiente()
    {
        global $conn;
        if ($row = $conn->Siguiente($this->stmt))
        {
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
            $this->peso_guia = $row["peso_guia"];
            $this->ciudad_idorigen = $row["ciudad_idorigen"];
            $this->tercero_idremitente = $row["tercero_idremitente"];
            $this->tercero_iddestinatario = $row["tercero_iddestinatario"];
            return $row;
        }
        return false;
    }

    function eliminar()
    {
        global $conn;
        if ($this->idguia)
        {
            $SQL = sprintf("DELETE FROM guia WHERE idguia='%s'", $this->idguia);
            return $conn->ejecutar($SQL);
        }
    }

}

//fin de la clase guia


/* * **********************************************************
  CLASE GUIA

 * *********************************************************** */

class guia
{

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
    var $peso_guia;
    var $ciudad_idorigen;
    var $tercero_idremitente;
    var $tercero_iddestinatario;
    var $extraDestinatario;
    //atributos agregados por mi
    var $owner;
//Variables de Control
    var $stmt; //Cursor para las consultas

//Funciones

    function cargar()
    {
        if (isset($_GET["codigo"]))
        {//se cargan las variables de la base de datos
            $this->buscar($_GET["codigo"]);
        } else
        {
//Cargar las variables de la forma
            $this->idguia = isset($_POST["idguia"]) ? $_POST["idguia"] : '';
            $this->numero_guia = isset($_POST["numero_guia"]) ? $_POST["numero_guia"] : '';
            $this->orden_servicio_idorden_servicio = isset($_POST["orden_servicio_idorden_servicio"]) ? $_POST["orden_servicio_idorden_servicio"] : '';
            $this->zona_idzona = isset($_POST["zona_idzona"]) ? $_POST["zona_idzona"] : '';
            $this->causal_devolucion_idcausal_devolucion = isset($_POST["causal_devolucion_idcausal_devolucion"]) ? $_POST["causal_devolucion_idcausal_devolucion"] : '';
            $this->manifiesto_idmanifiesto = isset($_POST["manifiesto_idmanifiesto"]) ? $_POST["manifiesto_idmanifiesto"] : '';
            $this->producto_idproducto = isset($_POST["producto_idproducto"]) ? $_POST["producto_idproducto"] : '';
            $this->ciudad_iddestino = isset($_POST["ciudad_iddestino"]) ? $_POST["ciudad_iddestino"] : '';
            $this->valor_declarado_guia = isset($_POST["valor_declarado_guia"]) ? $_POST["valor_declarado_guia"] : '';
            $this->nombre_destinatario_guia = isset($_POST["nombre_destinatario_guia"]) ? $_POST["nombre_destinatario_guia"] : '';
            $this->direccion_destinatario_guia = isset($_POST["direccion_destinatario_guia"]) ? $_POST["direccion_destinatario_guia"] : '';
            $this->telefono_destinatario_guia = isset($_POST["telefono_destinatario_guia"]) ? $_POST["telefono_destinatario_guia"] : '';
            $this->peso_guia = isset($_POST["peso_guia"]) ? $_POST["peso_guia"] : '';
            $this->ciudad_idorigen = isset($_POST["ciudad_idorigen"]) ? $_POST["ciudad_idorigen"] : '';
            $this->tercero_idremitente = isset($_POST["tercero_idremitente"]) ? $_POST["tercero_idremitente"] : '';
            $this->tercero_iddestinatario = isset($_POST["tercero_iddestinatario"]) ? $_POST["tercero_iddestinatario"] : '';
        }
    }

    function agregar()
    {
        global $conn;
//calcular el codigo
        $SQL = "SELECT g.`numero_guia` FROM guia g WHERE g.`numero_guia` LIKE 'MM%' ORDER BY g.`idguia` DESC LIMIT 1";
        if ($conn->ejecutar($SQL) && ($row = $conn->siguiente(NULL)))
            $this->numero_guia = 'MM' . (trim($row[0], 'M') + 1);
        else
            $this->numero_guia = 'MM1';
        $SQL = sprintf("INSERT INTO guia (destinatarioInfo,owner,numero_guia,orden_servicio_idorden_servicio,causal_devolucion_idcausal_devolucion,producto_idproducto,ciudad_iddestino,valor_declarado_guia,nombre_destinatario_guia,direccion_destinatario_guia,telefono_destinatario_guia,peso_guia,ciudad_idorigen,tercero_idremitente,tercero_iddestinatario)
values('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')
", $this->extraDestinatario, $this->owner, $this->numero_guia, $this->orden_servicio_idorden_servicio, $this->causal_devolucion_idcausal_devolucion, $this->producto_idproducto, $this->ciudad_iddestino, $this->valor_declarado_guia, $this->nombre_destinatario_guia, $this->direccion_destinatario_guia, $this->telefono_destinatario_guia, $this->peso_guia, $this->ciudad_idorigen, $this->tercero_idremitente, $this->tercero_iddestinatario);
        if ($conn->ejecutar($SQL))
            return true;
        else
            $this->idguia = "";
    }
    
    function agregarCC($idCreador)
 {
        global $conn;
//calcular el codigo
        $query2 = "SELECT numero_guia FROM guia  WHERE numero_guia LIKE 'CC%' ORDER BY idguia DESC LIMIT 1";
        $results2 = mysql_query($query2) or die(mysql_error());

        $nuevoN = 0;
        if ($fila = mysql_fetch_assoc($results2)) {
            $nuevoN = trim($fila['numero_guia'], "C");
        }
        $nuevoN++;
        $numero_guia = "CC" . substr("0000000", 0, 7 - (strlen($nuevoN))) . $nuevoN;
        $this->numero_guia=$numero_guia;
        echo("Generando guia: " . $this->numero_guia . " numero<br>");

        $query = "
           CALL addGuia (
           $this->owner,$idCreador,NULL,  
               $this->owner, '', $this->ciudad_idorigen,'',
                 $this->tercero_iddestinatario,'$this->nombre_destinatario_guia', '',' $this->telefono_destinatario_guia',
$this->ciudad_iddestino, '$this->direccion_destinatario_guia', '$this->extraDestinatario', 
               '$this->numero_guia', 'Unitario', $this->producto_idproducto, $this->valor_declarado_guia,
                 $this->peso_guia,   '', 1, 1, 1,1 ,-1
               )";

//        echo($query);
        if ($conn->ejecutar($query))
            return true;
        else {
            return false;
        }
    }

    function modificar()
    {
        global $conn;
        $SQL = sprintf("UPDATE guia SET idguia='%s',numero_guia='%s',orden_servicio_idorden_servicio='%s',zona_idzona='%s',causal_devolucion_idcausal_devolucion='%s',manifiesto_idmanifiesto='%s',producto_idproducto='%s',ciudad_iddestino='%s',valor_declarado_guia='%s',nombre_destinatario_guia='%s',direccion_destinatario_guia='%s',telefono_destinatario_guia='%s',peso_guia='%s',ciudad_idorigen='%s',tercero_idremitente='%s',tercero_iddestinatario='%s' WHERE idguia=%d "
                , $this->idguia, $this->numero_guia, $this->orden_servicio_idorden_servicio, $this->zona_idzona, $this->causal_devolucion_idcausal_devolucion, $this->manifiesto_idmanifiesto, $this->producto_idproducto, $this->ciudad_iddestino, $this->valor_declarado_guia, $this->nombre_destinatario_guia, $this->direccion_destinatario_guia, $this->telefono_destinatario_guia, $this->peso_guia, $this->ciudad_idorigen, $this->tercero_idremitente, $this->tercero_iddestinatario, $this->idguia);
        if ($conn->ejecutar($SQL))
            return true;
    }

//Realizar una consulta 
    function consultar($cond = "", $ord = "", $lim = "")
    {
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

    function buscar($cod)
    {
        global $conn;
        if ($this->consultar("idguia='$cod'"))
            return $this->siguiente();
        return false;
    }

    function siguiente()
    {
        global $conn;
        if ($row = $conn->Siguiente($this->stmt))
        {
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
            $this->peso_guia = $row["peso_guia"];
            $this->ciudad_idorigen = $row["ciudad_idorigen"];
            $this->tercero_idremitente = $row["tercero_idremitente"];
            $this->tercero_iddestinatario = $row["tercero_iddestinatario"];
            return $row;
        }
        return false;
    }

    function eliminar()
    {
        global $conn;
        if ($this->idguia)
        {
            $SQL = sprintf("DELETE FROM guia WHERE idguia='%s'", $this->idguia);
            return $conn->ejecutar($SQL);
        }
    }

    function modificar2($stringset, $numero_guia)
    {
        global $conn;
//$SQL = "UPDATE guia SET $stringset  WHERE idmanifiesto=$idos";
        $SQL = "UPDATE  guia SET  $stringset WHERE  numero_guia = $numero_guia";
//UPDATE  mensajeria.guia SET  manifiesto_idmanifiesto =  '4' WHERE  guia.numero_guia =15001;
//UPDATE  mensajeria.guia SET  manifiesto_idmanifiesto =  '4' WHERE  guia.idguia=44;

        if ($conn->ejecutar($SQL))
            return true;
    }

    /*     * *****************************************
      Funcion para verificar que un grupo de guias pertenecen a un manifiesto (Por pistoleo o bloque)

     * *************************** */

    function verificar($idmanifiesto, $guia)
    {
        $cond = "manifiesto_idmanifiesto=$idmanifiesto AND numero_guia=$guia";
        $res = $this->consultar($cond);
        if (mysql_num_rows($res) > 0)
            return true;
        else
            return false;
    }

}

//fin de la clase guia 


/* * **********************************************************
  CLASE TERCERO

 * *********************************************************** */

class tercero extends conexion
{

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
    var $conn;

    //$conn = new conexion();

    function cargar()
    {

        if (isset($_GET["codigo"]))
        {//se cargan las variables de la base de datos
            $this->buscar($_GET["codigo"]);
        } else
        {

            //Cargar las variables de la forma
            $this->idtercero = isset($_POST["idtercero"]) ? $_POST["idtercero"] : '';
            $this->sucursal_idsucursal = isset($_POST["sucursal_idsucursal"]) ? $_POST["sucursal_idsucursal"] : '';
            $this->tipo_identificacion_tercero = isset($_POST["tipo_identificacion_tercero"]) ? $_POST["tipo_identificacion_tercero"] : '';
            $this->documento_tercero = isset($_POST["documento_tercero"]) ? $_POST["documento_tercero"] : '';
            $this->nombres_tercero = isset($_POST["nombres_tercero"]) ? $_POST["nombres_tercero"] : '';
            $this->apellidos_tercero = isset($_POST["apellidos_tercero"]) ? $_POST["apellidos_tercero"] : '';
            $this->direccion_tercero = isset($_POST["direccion_tercero"]) ? $_POST["direccion_tercero"] : '';
            $this->telefono_tercero = isset($_POST["telefono_tercero"]) ? $_POST["telefono_tercero"] : '';
            $this->telefono2_tercero = isset($_POST["telefono2_tercero"]) ? $_POST["telefono2_tercero"] : '';
            $this->celular_tercero = isset($_POST["celular_tercero"]) ? $_POST["celular_tercero"] : '';
            $this->email_tercero = isset($_POST["email_tercero"]) ? $_POST["email_tercero"] : '';
            $this->usuario_tercero = isset($_POST["usuario_tercero"]) ? $_POST["usuario_tercero"] : '';
            $this->clave_tercero = isset($_POST["clave_tercero"]) ? $_POST["clave_tercero"] : '';
            $this->tercero_idvendedor = isset($_POST["tercero_idvendedor"]) ? $_POST["tercero_idvendedor"] : '';
            $this->comision_tercero = isset($_POST["comision_tercero"]) ? $_POST["comision_tercero"] : '';
            $this->observaciones_tercero = isset($_POST["observaciones_tercero"]) ? $_POST["observaciones_tercero"] : '';
        }
    }

    function agregar()
    {

        global $conn;
        //$conn = $this->conn;
        //calcular el codigo
        $SQL = "select max(idtercero) from tercero";
        if ($conn->ejecutar($SQL) && ($row = $conn->siguiente(NULL)))
            $this->idtercero = $row[0] + 1;
        else
            $this->idtercero = 1;
        $SQL = sprintf("INSERT INTO tercero (idtercero,sucursal_idsucursal,tipo_identificacion_tercero,documento_tercero,nombres_tercero,apellidos_tercero,direccion_tercero,telefono_tercero,telefono2_tercero,celular_tercero,email_tercero,usuario_tercero,clave_tercero,tercero_idvendedor,comision_tercero,observaciones_tercero)
        values('%s',%s,'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s',%s,'%s','%s')
        ", $this->idtercero, $this->sucursal_idsucursal, $this->tipo_identificacion_tercero, $this->documento_tercero, $this->nombres_tercero, $this->apellidos_tercero, $this->direccion_tercero, $this->telefono_tercero, $this->telefono2_tercero, $this->celular_tercero, $this->email_tercero, $this->usuario_tercero, $this->clave_tercero, $this->tercero_idvendedor, $this->comision_tercero, $this->observaciones_tercero);
        if ($conn->ejecutar($SQL))
            return true;
        else
            $this->idtercero = "";
    }

    function modificar()
    {
        global $conn;
        // $conn = $this->conn;
        $SQL = sprintf("UPDATE tercero SET idtercero='%s',sucursal_idsucursal=%s,tipo_identificacion_tercero='%s',documento_tercero='%s',nombres_tercero='%s',apellidos_tercero='%s',direccion_tercero='%s',telefono_tercero='%s',telefono2_tercero='%s',celular_tercero='%s',email_tercero='%s',usuario_tercero='%s',tercero_idvendedor=%s,comision_tercero='%s',observaciones_tercero='%s' WHERE idtercero=%d "
                , $this->idtercero, $this->sucursal_idsucursal, $this->tipo_identificacion_tercero, $this->documento_tercero, $this->nombres_tercero, $this->apellidos_tercero, $this->direccion_tercero, $this->telefono_tercero, $this->telefono2_tercero, $this->celular_tercero, $this->email_tercero, $this->usuario_tercero, $this->tercero_idvendedor, $this->comision_tercero, $this->observaciones_tercero, $this->idtercero);

        if ($conn->ejecutar($SQL))
            return true;
    }

    //Realizar una consulta
    function consultar($cond = "", $ord = "", $lim = "")
    {
        global $conn;
        //$conn = $this->conn;
        $SQL = "SELECT * FROM tercero";
        if (!empty($cond))
            $SQL.= " WHERE $cond";
        if (!empty($ord))
            $SQL.= " ORDER BY $ord";
        if (!empty($lim))
            $SQL.= " LIMIT $lim";
        // $this->stmt = $conn->ejecutar($SQL);
        $this->stmt = conexion::ejecutar($SQL);
        return $this->stmt;
    }

    function buscar($cod)
    {
        global $conn;
        // $conn = $this->conn;
        if ($this->consultar("idtercero='$cod'"))
            return $this->siguiente();
        return false;
    }

    function siguiente()
    {

        $conn = $this->conn;
        if ($row = $conn->Siguiente($this->stmt))
        {

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

    function eliminar()
    {
        global $conn;
        //$conn = $this->conn;
        if ($this->idtercero)
        {

            $SQL = sprintf("DELETE FROM tercero WHERE idtercero='%s'", $this->idtercero);
            return $conn->ejecutar($SQL);
        }
    }

    function cambiarcontrasenha()
    {
        global $conn;
        //$conn = $this->conn;
        $SQL = sprintf("UPDATE tercero SET clave_tercero='%s' WHERE usuario_tercero='%s' ", $this->clave_tercero, $this->usuario_tercero);
        if ($conn->ejecutar($SQL))
            return true;
    }

}

//fin de la clase tercero 


/* * **********************************************************
  CLASE PRODUCTO

 * *********************************************************** */

class producto
{

    var $idproducto;
    var $codigo;
    var $tipo_producto_idtipo_producto;
    var $nombre_producto;
    var $porcentaje_seguro_producto;
//Variables de Control
    var $stmt; //Cursor para las consultas

//Funciones

    function cargar()
    {
        if (isset($_GET["codigo"]))
        {//se cargan las variables de la base de datos
            $this->buscar($_GET["codigo"]);
        } else
        {
//Cargar las variables de la forma
            $this->idproducto = isset($_POST["idproducto"]) ? $_POST["idproducto"] : '';
            $this->codigo = isset($_POST["codigo"]) ? $_POST["codigo"] : '';
            $this->tipo_producto_idtipo_producto = isset($_POST["tipo_producto_idtipo_producto"]) ? $_POST["tipo_producto_idtipo_producto"] : '';
            $this->nombre_producto = isset($_POST["nombre_producto"]) ? $_POST["nombre_producto"] : '';
            $this->porcentaje_seguro_producto = isset($_POST["porcentaje_seguro_producto"]) ? $_POST["porcentaje_seguro_producto"] : '';
        }
    }

    function agregar()
    {
        global $conn;
//calcular el codigo
        $SQL = "select max(idproducto) from producto";
        if ($conn->ejecutar($SQL) && ($row = $conn->siguiente(NULL)))
            $this->idproducto = $row[0] + 1;
        else
            $this->idproducto = 1;
        $SQL = sprintf("INSERT INTO producto (idproducto,codigo,tipo_producto_idtipo_producto,nombre_producto,porcentaje_seguro_producto)
values('%s','%s','%s','%s','%s')
", $this->idproducto, $this->codigo, $this->tipo_producto_idtipo_producto, $this->nombre_producto, $this->porcentaje_seguro_producto);
        if ($conn->ejecutar($SQL))
            return true;
        else
            $this->idproducto = "";
    }

    function modificar()
    {
        global $conn;
        $SQL = sprintf("UPDATE producto SET idproducto='%s',codigo='%s',tipo_producto_idtipo_producto='%s',nombre_producto='%s',porcentaje_seguro_producto='%s' WHERE idproducto=%d "
                , $this->idproducto, $this->codigo, $this->tipo_producto_idtipo_producto, $this->nombre_producto, $this->porcentaje_seguro_producto, $this->idproducto);
        if ($conn->ejecutar($SQL))
            return true;
    }

//Realizar una consulta 
    function consultar($cond = "", $ord = "", $lim = "")
    {
        global $conn;
        $SQL = "SELECT * FROM producto";
        if (!empty($cond))
            $SQL.= " WHERE $cond";
        if (!empty($ord))
            $SQL.= " ORDER BY $ord";
        if (!empty($lim))
            $SQL.= " LIMIT $lim";
        $this->stmt = $conn->ejecutar($SQL);
        return $this->stmt;
    }

    function buscar($cod)
    {
        global $conn;
        if ($this->consultar("idproducto='$cod'"))
            return $this->siguiente();
        return false;
    }

    function siguiente()
    {
        global $conn;
        if ($row = $conn->Siguiente($this->stmt))
        {
//Cargar los datos de la bd
            $this->idproducto = $row["idproducto"];
            $this->codigo = $row["codigo"];
            $this->tipo_producto_idtipo_producto = $row["tipo_producto_idtipo_producto"];
            $this->nombre_producto = $row["nombre_producto"];
            $this->porcentaje_seguro_producto = $row["porcentaje_seguro_producto"];
            return $row;
        }
        return false;
    }

    function eliminar()
    {
        global $conn;
        if ($this->idproducto)
        {
            $SQL = sprintf("DELETE FROM producto WHERE idproducto='%s'", $this->idproducto);
            return $conn->ejecutar($SQL);
        }
    }

}

//fin de la clase producto

/* * **********************************************************
  CLASE CIUDAD

 * *********************************************************** */

class ciudad extends conexion
{

    var $idciudad;
    var $nombre_ciudad;
    var $cubrimiento_ciudad;
    var $departamento_iddepartamento;
    var $pais_ciudad;
    var $codigo_ciudad;
    var $trayecto_especial_ciudad;
    var $ciudad_padre;
//Variables de Control
    var $stmt; //Cursor para las consultas

//Funciones

    function cargar()
    {
        if (isset($_GET["codigo"]))
        {//se cargan las variables de la base de datos
            $this->buscar($_GET["codigo"]);
        } else
        {
//Cargar las variables de la forma
            $this->idciudad = isset($_POST["idciudad"]) ? $_POST["idciudad"] : '';
            $this->nombre_ciudad = isset($_POST["nombre_ciudad"]) ? $_POST["nombre_ciudad"] : '';
            $this->cubrimiento_ciudad = isset($_POST["cubrimiento_ciudad"]) ? $_POST["cubrimiento_ciudad"] : '';
            $this->departamento_iddepartamento = isset($_POST["departamento_iddepartamento"]) ? $_POST["departamento_iddepartamento"] : '';
            $this->pais_ciudad = isset($_POST["pais_ciudad"]) ? $_POST["pais_ciudad"] : '';
            $this->codigo_ciudad = isset($_POST["codigo_ciudad"]) ? $_POST["codigo_ciudad"] : '';
            $this->trayecto_especial_ciudad = isset($_POST["trayecto_especial_ciudad"]) ? $_POST["trayecto_especial_ciudad"] : '';
            $this->ciudad_padre = isset($_POST["ciudad_padre"]) ? $_POST["ciudad_padre"] : '';
        }
    }

    function agregar()
    {
        global $conn;
//calcular el codigo
        $SQL = "select max(idciudad) from ciudad";
        if ($conn->ejecutar($SQL) && ($row = $conn->siguiente(NULL)))
            $this->idciudad = $row[0] + 1;
        else
            $this->idciudad = 1;
        $SQL = sprintf("INSERT INTO ciudad (idciudad,nombre_ciudad,cubrimiento_ciudad,departamento_iddepartamento,pais_ciudad,codigo_ciudad,trayecto_especial_ciudad,ciudad_padre)
values('%s','%s','%s','%s','%s','%s','%s','%s')
", $this->idciudad, $this->nombre_ciudad, $this->cubrimiento_ciudad, $this->departamento_iddepartamento, $this->pais_ciudad, $this->codigo_ciudad, $this->trayecto_especial_ciudad, $this->ciudad_padre);
        if ($conn->ejecutar($SQL))
            return true;
        else
            $this->idciudad = "";
    }

    function modificar()
    {
        global $conn;
        $SQL = sprintf("UPDATE ciudad SET idciudad='%s',nombre_ciudad='%s',cubrimiento_ciudad='%s',departamento_iddepartamento='%s',pais_ciudad='%s',codigo_ciudad='%s',trayecto_especial_ciudad='%s',ciudad_padre='%s' WHERE idciudad=%d "
                , $this->idciudad, $this->nombre_ciudad, $this->cubrimiento_ciudad, $this->departamento_iddepartamento, $this->pais_ciudad, $this->codigo_ciudad, $this->trayecto_especial_ciudad, $this->ciudad_padre, $this->idciudad);
        if ($conn->ejecutar($SQL))
            return true;
    }

//Realizar una consulta 
    function consultar($cond = "", $ord = "", $lim = "")
    {
        global $conn;
        $SQL = "SELECT * FROM ciudad";
        if (!empty($cond))
            $SQL.= " WHERE $cond";
        if (!empty($ord))
            $SQL.= " ORDER BY $ord";
        if (!empty($lim))
            $SQL.= " LIMIT $lim";
        $this->stmt = conexion::ejecutar($SQL);

//$this->stmt = $conn->ejecutar($SQL);
        return $this->stmt;
    }

    function buscar($cod)
    {
        global $conn;
        if ($this->consultar("idciudad='$cod'"))
            return $this->siguiente();
        return false;
    }

    function siguiente()
    {
        global $conn;
        if ($row = $conn->Siguiente($this->stmt))
        {
//Cargar los datos de la bd
            $this->idciudad = $row["idciudad"];
            $this->nombre_ciudad = $row["nombre_ciudad"];
            $this->cubrimiento_ciudad = $row["cubrimiento_ciudad"];
            $this->departamento_iddepartamento = $row["departamento_iddepartamento"];
            $this->pais_ciudad = $row["pais_ciudad"];
            $this->codigo_ciudad = $row["codigo_ciudad"];
            $this->trayecto_especial_ciudad = $row["trayecto_especial_ciudad"];
            $this->ciudad_padre = $row["ciudad_padre"];
            return $row;
        }
        return false;
    }

    function eliminar()
    {
        global $conn;
        if ($this->idciudad)
        {
            $SQL = sprintf("DELETE FROM ciudad WHERE idciudad='%s'", $this->idciudad);
            return $conn->ejecutar($SQL);
        }
    }

}

//fin de la clase ciudad

/* * **********************************************************
  CLASE FACTURA

 * *********************************************************** */

class factura
{

    var $idfactura;
//Variables de Control
    var $stmt; //Cursor para las consultas

//Funciones

    function cargar()
    {
        if (isset($_GET["codigo"]))
        {//se cargan las variables de la base de datos
            $this->buscar($_GET["codigo"]);
        } else
        {
//Cargar las variables de la forma
            $this->idfactura = isset($_POST["idfactura"]) ? $_POST["idfactura"] : '';
        }
    }

    function agregar()
    {
        global $conn;
//calcular el codigo
        $SQL = "select max(idfactura) from factura";
        if ($conn->ejecutar($SQL) && ($row = $conn->siguiente(NULL)))
            $this->idfactura = $row[0] + 1;
        else
            $this->idfactura = 1;
        $SQL = sprintf("INSERT INTO factura (idfactura)
values('%s')
", $this->idfactura);
        if ($conn->ejecutar($SQL))
            return true;
        else
            $this->idfactura = "";
    }

    function modificar()
    {
        global $conn;
        $SQL = sprintf("UPDATE factura SET idfactura='%s' WHERE idfactura=%d "
                , $this->idfactura, $this->idfactura);
        if ($conn->ejecutar($SQL))
            return true;
    }

//Realizar una consulta 
    function consultar($cond = "", $ord = "", $lim = "")
    {
        global $conn;
        $SQL = "SELECT * FROM factura";
        if (!empty($cond))
            $SQL.= " WHERE $cond";
        if (!empty($ord))
            $SQL.= " ORDER BY $ord";
        if (!empty($lim))
            $SQL.= " LIMIT $lim";
        $this->stmt = $conn->ejecutar($SQL);
        return $this->stmt;
    }

    function buscar($cod)
    {
        global $conn;
        if ($this->consultar("idfactura='$cod'"))
            return $this->siguiente();
        return false;
    }

    function siguiente()
    {
        global $conn;
        if ($row = $conn->Siguiente($this->stmt))
        {
//Cargar los datos de la bd
            $this->idfactura = $row["idfactura"];
            return $row;
        }
        return false;
    }

    function eliminar()
    {
        global $conn;
        if ($this->idfactura)
        {
            $SQL = sprintf("DELETE FROM factura WHERE idfactura='%s'", $this->idfactura);
            return $conn->ejecutar($SQL);
        }
    }

}

//fin de la clase factura


/* * **********************************************************
  CLASE MANIFIESTO

 * *********************************************************** */


/*
  class manifiesto{
  var $idmanifiesto;
  var $num_manifiesto;
  var $tercero_idmensajero_recibe;
  var $tercero_idmensajero_entrega;
  var $sucursal_idsucursal;
  var $tercero_idaliado;
  var $plazo_entrega_manifiesto;
  //Variables de Control
  var $stmt; //Cursor para las consultas
  //Funciones
  function cargar(){
  if (isset($_GET["codigo"]) ){//se cargan las variables de la base de datos
  $this->buscar($_GET["codigo"]);
  }else{
  //Cargar las variables de la forma
  $this->idmanifiesto= isset($_POST["idmanifiesto"])?$_POST["idmanifiesto"]:'';
  $this->num_manifiesto= isset($_POST["num_manifiesto"])?$_POST["num_manifiesto"]:'';
  $this->tercero_idmensajero_recibe= isset($_POST["tercero_idmensajero_recibe"])?$_POST["tercero_idmensajero_recibe"]:'';
  $this->tercero_idmensajero_entrega= isset($_POST["tercero_idmensajero_entrega"])?$_POST["tercero_idmensajero_entrega"]:'';
  $this->sucursal_idsucursal= isset($_POST["sucursal_idsucursal"])?$_POST["sucursal_idsucursal"]:'';
  $this->tercero_idaliado= isset($_POST["tercero_idaliado"])?$_POST["tercero_idaliado"]:'';
  $this->plazo_entrega_manifiesto= isset($_POST["plazo_entrega_manifiesto"])?$_POST["plazo_entrega_manifiesto"]:'';
  }
  }
  function agregar(){
  global $conn;
  //calcular el codigo
  $SQL = "select max(idmanifiesto) from manifiesto";
  if ( $conn->ejecutar($SQL) && ($row=$conn->siguiente(NULL)) )
  $this->idmanifiesto = $row[0]+1;
  else
  $this->idmanifiesto = 1;
  $SQL = sprintf("INSERT INTO manifiesto (idmanifiesto,num_manifiesto,tercero_idmensajero_recibe,tercero_idmensajero_entrega,sucursal_idsucursal,tercero_idaliado,plazo_entrega_manifiesto)
  values('%s','%s','%s','%s','%s','%s','%s')
  ",$this->idmanifiesto,$this->num_manifiesto,$this->tercero_idmensajero_recibe,$this->tercero_idmensajero_entrega,$this->sucursal_idsucursal,$this->tercero_idaliado,$this->plazo_entrega_manifiesto);
  if ($conn->ejecutar($SQL))
  return true;
  else
  $this->idmanifiesto="";
  }

  function generarNumeroManifiesto(){
  global $conn;

  $SQL = "select max(num_manifiesto) from manifiesto";
  if ( $conn->ejecutar($SQL) && ($row=$conn->siguiente(NULL)) )
  {
  return $this->num_manifiesto = $row[0]+1;
  }
  else
  {
  $this->num_manifiesto = 1;
  }
  }


  function modificar(){
  global $conn;
  $SQL = sprintf("UPDATE manifiesto SET idmanifiesto='%s',num_manifiesto='%s',tercero_idmensajero_recibe='%s',tercero_idmensajero_entrega='%s',sucursal_idsucursal='%s',tercero_idaliado='%s',plazo_entrega_manifiesto='%s' WHERE idmanifiesto=%d "
  ,$this->idmanifiesto,$this->num_manifiesto,$this->tercero_idmensajero_recibe,$this->tercero_idmensajero_entrega,$this->sucursal_idsucursal,$this->tercero_idaliado,$this->plazo_entrega_manifiesto,$this->idmanifiesto);
  if ($conn->ejecutar($SQL))
  return true;
  }

  //Realizar una consulta
  function consultar($cond = "",$ord = "",$lim=""){
  global $conn;
  $SQL = "SELECT * FROM manifiesto";
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
  if ($this->consultar("idmanifiesto='$cod'"))
  return $this->siguiente();
  return false;
  }

  function siguiente(){
  global $conn;
  if ($row = $conn->Siguiente($this->stmt)){
  //Cargar los datos de la bd
  $this->idmanifiesto = $row["idmanifiesto"];
  $this->num_manifiesto = $row["num_manifiesto"];
  $this->tercero_idmensajero_recibe = $row["tercero_idmensajero_recibe"];
  $this->tercero_idmensajero_entrega = $row["tercero_idmensajero_entrega"];
  $this->sucursal_idsucursal = $row["sucursal_idsucursal"];
  $this->tercero_idaliado = $row["tercero_idaliado"];
  $this->plazo_entrega_manifiesto = $row["plazo_entrega_manifiesto"];
  return $row;
  }
  return false;
  }
  function eliminar(){
  global $conn;
  if ($this->idmanifiesto){
  $SQL = sprintf("DELETE FROM manifiesto WHERE idmanifiesto='%s'",$this->idmanifiesto);
  return $conn->ejecutar($SQL);
  }
  }

  } */ //fin de la clase manifiesto 

/* * **********************************************************
  CLASE MANIFIESTO

 * *********************************************************** */

class manifiesto
{

    var $idmanifiesto;
    var $num_manifiesto;
    var $tercero_idmensajero_recibe;
    var $tercero_idmensajero_entrega;
    var $sucursal_idsucursal;
    var $tercero_idaliado;
    var $plazo_entrega_manifiesto;
    var $tarifadestajo;
    var $zonamensajero;
    //Variables de Control
    var $stmt; //Cursor para las consultas

    //Funciones

    function cargar()
    {

        if (isset($_GET["codigo"]))
        {//se cargan las variables de la base de datos
            $this->buscar($_GET["codigo"]);
        } else
        {

            //Cargar las variables de la forma
            $this->idmanifiesto = isset($_POST["idmanifiesto"]) ? $_POST["idmanifiesto"] : '';
            $this->num_manifiesto = isset($_POST["num_manifiesto"]) ? $_POST["num_manifiesto"] : '';
            $this->tercero_idmensajero_recibe = isset($_POST["tercero_idmensajero_recibe"]) ? $_POST["tercero_idmensajero_recibe"] : '';
            $this->tercero_idmensajero_entrega = isset($_POST["tercero_idmensajero_entrega"]) ? $_POST["tercero_idmensajero_entrega"] : '';
            $this->sucursal_idsucursal = isset($_POST["sucursal_idsucursal"]) ? $_POST["sucursal_idsucursal"] : '';
            $this->tercero_idaliado = isset($_POST["tercero_idaliado"]) ? $_POST["tercero_idaliado"] : '';
            $this->plazo_entrega_manifiesto = isset($_POST["plazo_entrega_manifiesto"]) ? $_POST["plazo_entrega_manifiesto"] : '';
            $this->tarifadestajo = isset($_POST["tarifadestajo"]) ? $_POST["tarifadestajo"] : '';
            $this->zonamensajero = isset($_POST["zonamensajero"]) ? $_POST["zonamensajero"] : '';
        }
    }

    function agregar()
    {

        global $conn;
        //calcular el codigo
        $SQL = "select max(idmanifiesto) from manifiesto";
        if ($conn->ejecutar($SQL) && ($row = $conn->siguiente(NULL)))
            $this->idmanifiesto = $row[0] + 1;
        else
            $this->idmanifiesto = 1;
        $SQL = sprintf("INSERT INTO manifiesto (idmanifiesto,num_manifiesto,tercero_idmensajero_recibe,tercero_idmensajero_entrega,sucursal_idsucursal,tercero_idaliado,plazo_entrega_manifiesto,tarifadestajo,zonamensajero)
        values('%s','%s','%s','%s','%s','%s','%s','%s','%s')
        ", $this->idmanifiesto, $this->num_manifiesto, $this->tercero_idmensajero_recibe, $this->tercero_idmensajero_entrega, $this->sucursal_idsucursal, $this->tercero_idaliado, $this->plazo_entrega_manifiesto, $this->tarifadestajo, $this->zonamensajero);
        if ($conn->ejecutar($SQL))
            return true;
        else
            $this->idmanifiesto = "";
    }

    function modificar()
    {

        global $conn;
        $SQL = sprintf("UPDATE manifiesto SET idmanifiesto='%s',num_manifiesto='%s',tercero_idmensajero_recibe='%s',tercero_idmensajero_entrega='%s',sucursal_idsucursal='%s',tercero_idaliado='%s',plazo_entrega_manifiesto='%s',tarifadestajo='%s',zonamensajero='%s' WHERE idmanifiesto=%d "
                , $this->idmanifiesto, $this->num_manifiesto, $this->tercero_idmensajero_recibe, $this->tercero_idmensajero_entrega, $this->sucursal_idsucursal, $this->tercero_idaliado, $this->plazo_entrega_manifiesto, $this->tarifadestajo, $this->zonamensajero, $this->idmanifiesto);
        if ($conn->ejecutar($SQL))
            return true;
    }

    //Realizar una consulta
    function consultar($cond = "", $ord = "", $lim = "")
    {

        global $conn;
        $SQL = "SELECT * FROM manifiesto";
        if (!empty($cond))
            $SQL.= " WHERE $cond";
        if (!empty($ord))
            $SQL.= " ORDER BY $ord";
        if (!empty($lim))
            $SQL.= " LIMIT $lim";
        $this->stmt = $conn->ejecutar($SQL);
        return $this->stmt;
    }

    function buscar($cod)
    {

        global $conn;
        if ($this->consultar("idmanifiesto='$cod'"))
            return $this->siguiente();
        return false;
    }

    function siguiente()
    {

        global $conn;
        if ($row = $conn->Siguiente($this->stmt))
        {

            //Cargar los datos de la bd
            $this->idmanifiesto = $row["idmanifiesto"];
            $this->num_manifiesto = $row["num_manifiesto"];
            $this->tercero_idmensajero_recibe = $row["tercero_idmensajero_recibe"];
            $this->tercero_idmensajero_entrega = $row["tercero_idmensajero_entrega"];
            $this->sucursal_idsucursal = $row["sucursal_idsucursal"];
            $this->tercero_idaliado = $row["tercero_idaliado"];
            $this->plazo_entrega_manifiesto = $row["plazo_entrega_manifiesto"];
            $this->tarifadestajo = $row["tarifadestajo"];
            $this->zonamensajero = $row["zonamensajero"];
            return $row;
        }
        return false;
    }

    function eliminar()
    {

        global $conn;
        if ($this->idmanifiesto)
        {

            $SQL = sprintf("DELETE FROM manifiesto WHERE idmanifiesto='%s'", $this->idmanifiesto);
            return $conn->ejecutar($SQL);
        }
    }

    function generarNumeroManifiesto()
    {
        global $conn;

        $SQL = "select max(num_manifiesto) from manifiesto";
        if ($conn->ejecutar($SQL) && ($row = $conn->siguiente(NULL)))
        {
            return $this->num_manifiesto = $row[0] + 1;
        } else
        {
            $this->num_manifiesto = 1;
        }
    }

}

//fin de la clase manifiesto 





/* * **********************************************************
  CLASE movimiento


 * *********************************************************** */

class movimiento
{

    var $idmovimiento;
    var $estado_idestado;
    var $tercero_idusuario;
    var $tercero_idaliado;
    var $fecha_movimiento;
    var $hora_movimiento;
    var $recogida_idrecogida;
    var $orden_servicio_idorden_servicio;
    var $manifiesto_idmanifiesto;
    var $guia_idguia;
    var $sucursal_idsucursal;
    var $asignacion_guias_idasignacion_guias;
    var $tarifa_idtarifa;
//Variables de Control
    var $stmt; //Cursor para las consultas

//Funciones

    function cargar()
    {
        if (isset($_GET["codigo"]))
        {//se cargan las variables de la base de datos
            $this->buscar($_GET["codigo"]);
        } else
        {
//Cargar las variables de la forma
            $this->idmovimiento = isset($_POST["idmovimiento"]) ? $_POST["idmovimiento"] : '';
            $this->estado_idestado = isset($_POST["estado_idestado"]) ? $_POST["estado_idestado"] : '';
            $this->tercero_idusuario = isset($_POST["tercero_idusuario"]) ? $_POST["tercero_idusuario"] : '';
            $this->tercero_idaliado = isset($_POST["tercero_idaliado"]) ? $_POST["tercero_idaliado"] : '';
            $this->fecha_movimiento = isset($_POST["fecha_movimiento"]) ? $_POST["fecha_movimiento"] : '';
            $this->hora_movimiento = isset($_POST["hora_movimiento"]) ? $_POST["hora_movimiento"] : '';
            $this->recogida_idrecogida = isset($_POST["recogida_idrecogida"]) ? $_POST["recogida_idrecogida"] : '';
            $this->orden_servicio_idorden_servicio = isset($_POST["orden_servicio_idorden_servicio"]) ? $_POST["orden_servicio_idorden_servicio"] : '';
            $this->manifiesto_idmanifiesto = isset($_POST["manifiesto_idmanifiesto"]) ? $_POST["manifiesto_idmanifiesto"] : '';
            $this->guia_idguia = isset($_POST["guia_idguia"]) ? $_POST["guia_idguia"] : '';
            $this->sucursal_idsucursal = isset($_POST["sucursal_idsucursal"]) ? $_POST["sucursal_idsucursal"] : '';
            $this->asignacion_guias_idasignacion_guias = isset($_POST["asignacion_guias_idasignacion_guias"]) ? $_POST["asignacion_guias_idasignacion_guias"] : '';
            $this->tarifa_idtarifa = isset($_POST["tarifa_idtarifa"]) ? $_POST["tarifa_idtarifa"] : '';
        }
    }

    function agregar()
    {
        global $conn;
//calcular el codigo
        $SQL = "select max(idmovimiento) from movimiento";
        if ($conn->ejecutar($SQL) && ($row = $conn->siguiente(NULL)))
            $this->idmovimiento = $row[0] + 1;
        else
            $this->idmovimiento = 1;
        $SQL = sprintf("INSERT INTO movimiento (idmovimiento,estado_idestado,tercero_idusuario,tercero_idaliado,fecha_movimiento,hora_movimiento,recogida_idrecogida,orden_servicio_idorden_servicio,manifiesto_idmanifiesto,guia_idguia,sucursal_idsucursal,asignacion_guias_idasignacion_guias,tarifa_idtarifa)
values('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')
", $this->idmovimiento, $this->estado_idestado, $this->tercero_idusuario, $this->tercero_idaliado, $this->fecha_movimiento, $this->hora_movimiento, $this->recogida_idrecogida, $this->orden_servicio_idorden_servicio, $this->manifiesto_idmanifiesto, $this->guia_idguia, $this->sucursal_idsucursal, $this->asignacion_guias_idasignacion_guias, $this->tarifa_idtarifa);
        if ($conn->ejecutar($SQL))
            return true;
        else
            $this->idmovimiento = "";
    }

    function modificar()
    {
        global $conn;
        $SQL = sprintf("UPDATE movimiento SET idmovimiento='%s',estado_idestado='%s',tercero_idusuario='%s',tercero_idaliado='%s',fecha_movimiento='%s',hora_movimiento='%s',recogida_idrecogida='%s',orden_servicio_idorden_servicio='%s',manifiesto_idmanifiesto='%s',guia_idguia='%s',sucursal_idsucursal='%s',asignacion_guias_idasignacion_guias='%s',tarifa_idtarifa='%s' WHERE idmovimiento=%d "
                , $this->idmovimiento, $this->estado_idestado, $this->tercero_idusuario, $this->tercero_idaliado, $this->fecha_movimiento, $this->hora_movimiento, $this->recogida_idrecogida, $this->orden_servicio_idorden_servicio, $this->manifiesto_idmanifiesto, $this->guia_idguia, $this->sucursal_idsucursal, $this->asignacion_guias_idasignacion_guias, $this->tarifa_idtarifa, $this->idmovimiento);
        if ($conn->ejecutar($SQL))
            return true;
    }

//Realizar una consulta 
    function consultar($cond = "", $ord = "", $lim = "")
    {
        global $conn;
        $SQL = "SELECT * FROM movimiento";
        if (!empty($cond))
            $SQL.= " WHERE $cond";
        if (!empty($ord))
            $SQL.= " ORDER BY $ord";
        if (!empty($lim))
            $SQL.= " LIMIT $lim";
        $this->stmt = $conn->ejecutar($SQL);
        return $this->stmt;
    }

    function buscar($cod)
    {
        global $conn;
        if ($this->consultar("idmovimiento='$cod'"))
            return $this->siguiente();
        return false;
    }

    function siguiente()
    {
        global $conn;
        if ($row = $conn->Siguiente($this->stmt))
        {
//Cargar los datos de la bd
            $this->idmovimiento = $row["idmovimiento"];
            $this->estado_idestado = $row["estado_idestado"];
            $this->tercero_idusuario = $row["tercero_idusuario"];
            $this->tercero_idaliado = $row["tercero_idaliado"];
            $this->fecha_movimiento = $row["fecha_movimiento"];
            $this->hora_movimiento = $row["hora_movimiento"];
            $this->recogida_idrecogida = $row["recogida_idrecogida"];
            $this->orden_servicio_idorden_servicio = $row["orden_servicio_idorden_servicio"];
            $this->manifiesto_idmanifiesto = $row["manifiesto_idmanifiesto"];
            $this->guia_idguia = $row["guia_idguia"];
            $this->sucursal_idsucursal = $row["sucursal_idsucursal"];
            $this->asignacion_guias_idasignacion_guias = $row["asignacion_guias_idasignacion_guias"];
            $this->tarifa_idtarifa = $row["tarifa_idtarifa"];
            return $row;
        }
        return false;
    }

    function eliminar()
    {
        global $conn;
        if ($this->idmovimiento)
        {
            $SQL = sprintf("DELETE FROM movimiento WHERE idmovimiento='%s'", $this->idmovimiento);
            return $conn->ejecutar($SQL);
        }
    }

}

//fin de la clase movimiento

/* * **********************************************************
  CLASE SUCURSAL


 * *********************************************************** */

class sucursal extends conexion
{

    var $idsucursal;
    var $ciudad_idciudad;
    var $codigo_sucursal;
    var $nombre_sucursal;
    var $direccion_sucursal;
    var $telefono_sucursal;
    var $observaciones_sucursal;
//Variables de Control
    var $stmt; //Cursor para las consultas

//Funciones

    function cargar()
    {
        if (isset($_GET["codigo"]))
        {//se cargan las variables de la base de datos
            $this->buscar($_GET["codigo"]);
        } else
        {
//Cargar las variables de la forma
            $this->idsucursal = isset($_POST["idsucursal"]) ? $_POST["idsucursal"] : '';
            $this->ciudad_idciudad = isset($_POST["ciudad_idciudad"]) ? $_POST["ciudad_idciudad"] : '';
            $this->codigo_sucursal = isset($_POST["codigo_sucursal"]) ? $_POST["codigo_sucursal"] : '';
            $this->nombre_sucursal = isset($_POST["nombre_sucursal"]) ? $_POST["nombre_sucursal"] : '';
            $this->direccion_sucursal = isset($_POST["direccion_sucursal"]) ? $_POST["direccion_sucursal"] : '';
            $this->telefono_sucursal = isset($_POST["telefono_sucursal"]) ? $_POST["telefono_sucursal"] : '';
            $this->observaciones_sucursal = isset($_POST["observaciones_sucursal"]) ? $_POST["observaciones_sucursal"] : '';
        }
    }

    function agregar()
    {
        global $conn;
//calcular el codigo
        $SQL = "select max(idsucursal) from sucursal";
        if ($conn->ejecutar($SQL) && ($row = $conn->siguiente(NULL)))
            $this->idsucursal = $row[0] + 1;
        else
            $this->idsucursal = 1;
        $SQL = sprintf("INSERT INTO sucursal (idsucursal,ciudad_idciudad,codigo_sucursal,nombre_sucursal,direccion_sucursal,telefono_sucursal,observaciones_sucursal)
values('%s','%s','%s','%s','%s','%s','%s')
", $this->idsucursal, $this->ciudad_idciudad, $this->codigo_sucursal, $this->nombre_sucursal, $this->direccion_sucursal, $this->telefono_sucursal, $this->observaciones_sucursal);
        if ($conn->ejecutar($SQL))
            return true;
        else
            $this->idsucursal = "";
    }

    function modificar()
    {
        global $conn;
        $SQL = sprintf("UPDATE sucursal SET idsucursal='%s',ciudad_idciudad='%s',codigo_sucursal='%s',nombre_sucursal='%s',direccion_sucursal='%s',telefono_sucursal='%s',observaciones_sucursal='%s' WHERE idsucursal=%d "
                , $this->idsucursal, $this->ciudad_idciudad, $this->codigo_sucursal, $this->nombre_sucursal, $this->direccion_sucursal, $this->telefono_sucursal, $this->observaciones_sucursal, $this->idsucursal);
        if ($conn->ejecutar($SQL))
            return true;
    }

//Realizar una consulta 
    function consultar($cond = "", $ord = "", $lim = "")
    {
        global $conn;
        $SQL = "SELECT * FROM sucursal";
        if (!empty($cond))
            $SQL.= " WHERE $cond";
        if (!empty($ord))
            $SQL.= " ORDER BY $ord";
        if (!empty($lim))
            $SQL.= " LIMIT $lim";
//$this->stmt = $conn->ejecutar($SQL);
        $this->stmt = conexion::ejecutar($SQL);
        return $this->stmt;
    }

    function buscar($cod)
    {
        global $conn;
        if ($this->consultar("idsucursal='$cod'"))
            return $this->siguiente();
        return false;
    }

    function siguiente()
    {
        global $conn;
        if ($row = $conn->Siguiente($this->stmt))
        {
//Cargar los datos de la bd
            $this->idsucursal = $row["idsucursal"];
            $this->ciudad_idciudad = $row["ciudad_idciudad"];
            $this->codigo_sucursal = $row["codigo_sucursal"];
            $this->nombre_sucursal = $row["nombre_sucursal"];
            $this->direccion_sucursal = $row["direccion_sucursal"];
            $this->telefono_sucursal = $row["telefono_sucursal"];
            $this->observaciones_sucursal = $row["observaciones_sucursal"];
            return $row;
        }
        return false;
    }

    function eliminar()
    {
        global $conn;
        if ($this->idsucursal)
        {
            $SQL = sprintf("DELETE FROM sucursal WHERE idsucursal='%s'", $this->idsucursal);
            return $conn->ejecutar($SQL);
        }
    }

}

//fin de la clase sucursal
/* * **********************************************************
  CLASE DETALLE PRODUCTO ESPECIAL

 * *********************************************************** */

class detalle_producto_especial
{

    var $iddetalle_producto_especial;
    var $producto_idproducto;
    var $orden_servicio_idorden_servicio;
//Variables de Control
    var $stmt; //Cursor para las consultas

//Funciones

    function cargar()
    {
        if (isset($_GET["codigo"]))
        {//se cargan las variables de la base de datos
            $this->buscar($_GET["codigo"]);
        } else
        {
//Cargar las variables de la forma
            $this->iddetalle_producto_especial = isset($_POST["iddetalle_producto_especial"]) ? $_POST["iddetalle_producto_especial"] : '';
            $this->producto_idproducto = isset($_POST["producto_idproducto"]) ? $_POST["producto_idproducto"] : '';
            $this->orden_servicio_idorden_servicio = isset($_POST["orden_servicio_idorden_servicio"]) ? $_POST["orden_servicio_idorden_servicio"] : '';
        }
    }

    function agregar()
    {
        global $conn;
//calcular el codigo
        $SQL = "select max(iddetalle_producto_especial) from detalle_producto_especial";
        if ($conn->ejecutar($SQL) && ($row = $conn->siguiente(NULL)))
            $this->iddetalle_producto_especial = $row[0] + 1;
        else
            $this->iddetalle_producto_especial = 1;
        $SQL = sprintf("INSERT INTO detalle_producto_especial (iddetalle_producto_especial,producto_idproducto,orden_servicio_idorden_servicio)
values('%s','%s','%s')
", $this->iddetalle_producto_especial, $this->producto_idproducto, $this->orden_servicio_idorden_servicio);
        if ($conn->ejecutar($SQL))
            return true;
        else
            $this->iddetalle_producto_especial = "";
    }

    function modificar()
    {
        global $conn;
        $SQL = sprintf("UPDATE detalle_producto_especial SET iddetalle_producto_especial='%s',producto_idproducto='%s',orden_servicio_idorden_servicio='%s' WHERE iddetalle_producto_especial=%d "
                , $this->iddetalle_producto_especial, $this->producto_idproducto, $this->orden_servicio_idorden_servicio, $this->iddetalle_producto_especial);
        if ($conn->ejecutar($SQL))
            return true;
    }

//Realizar una consulta 
    function consultar($cond = "", $ord = "", $lim = "")
    {
        global $conn;
        $SQL = "SELECT * FROM detalle_producto_especial";
        if (!empty($cond))
            $SQL.= " WHERE $cond";
        if (!empty($ord))
            $SQL.= " ORDER BY $ord";
        if (!empty($lim))
            $SQL.= " LIMIT $lim";
        $this->stmt = $conn->ejecutar($SQL);
        return $this->stmt;
    }

    function buscar($cod)
    {
        global $conn;
        if ($this->consultar("iddetalle_producto_especial='$cod'"))
            return $this->siguiente();
        return false;
    }

    function siguiente()
    {
        global $conn;
        if ($row = $conn->Siguiente($this->stmt))
        {
//Cargar los datos de la bd
            $this->iddetalle_producto_especial = $row["iddetalle_producto_especial"];
            $this->producto_idproducto = $row["producto_idproducto"];
            $this->orden_servicio_idorden_servicio = $row["orden_servicio_idorden_servicio"];
            return $row;
        }
        return false;
    }

    function eliminar()
    {
        global $conn;
        if ($this->iddetalle_producto_especial)
        {
            $SQL = sprintf("DELETE FROM detalle_producto_especial WHERE iddetalle_producto_especial='%s'", $this->iddetalle_producto_especial);
            return $conn->ejecutar($SQL);
        }
    }

}

//fin de la clase detalle_producto_especial

/* * **********************************************************
  CLASE CAUSAL DEVOLUCION

 * *********************************************************** */

class causal_devolucion
{

    var $idcausal_devolucion;
    var $codigo_causal_devolucion;
    var $nombre_causal_devolucion;
    var $observaciones_causal_devolucion;
//Variables de Control
    var $stmt; //Cursor para las consultas

//Funciones

    function cargar()
    {
        if (isset($_GET["codigo"]))
        {//se cargan las variables de la base de datos
            $this->buscar($_GET["codigo"]);
        } else
        {
//Cargar las variables de la forma
            $this->idcausal_devolucion = isset($_POST["idcausal_devolucion"]) ? $_POST["idcausal_devolucion"] : '';
            $this->codigo_causal_devolucion = isset($_POST["codigo_causal_devolucion"]) ? $_POST["codigo_causal_devolucion"] : '';
            $this->nombre_causal_devolucion = isset($_POST["nombre_causal_devolucion"]) ? $_POST["nombre_causal_devolucion"] : '';
            $this->observaciones_causal_devolucion = isset($_POST["observaciones_causal_devolucion"]) ? $_POST["observaciones_causal_devolucion"] : '';
        }
    }

    function agregar()
    {
        global $conn;
//calcular el codigo
        $SQL = "select max(idcausal_devolucion) from causal_devolucion";
        if ($conn->ejecutar($SQL) && ($row = $conn->siguiente(NULL)))
            $this->idcausal_devolucion = $row[0] + 1;
        else
            $this->idcausal_devolucion = 1;

        $SQL = sprintf("INSERT INTO causal_devolucion (idcausal_devolucion,codigo_causal_devolucion,nombre_causal_devolucion,observaciones_causal_devolucion)
values('%s','%s','%s','%s')
", $this->idcausal_devolucion, $this->codigo_causal_devolucion, $this->nombre_causal_devolucion, $this->observaciones_causal_devolucion);
        if ($conn->ejecutar($SQL))
            return true;
        else
            $this->idcausal_devolucion = "";
    }

    function modificar()
    {
        global $conn;
        $SQL = sprintf("UPDATE causal_devolucion SET idcausal_devolucion='%s',codigo_causal_devolucion='%s',nombre_causal_devolucion='%s',observaciones_causal_devolucion='%s' WHERE idcausal_devolucion=%d "
                , $this->idcausal_devolucion, $this->codigo_causal_devolucion, $this->nombre_causal_devolucion, $this->observaciones_causal_devolucion, $this->idcausal_devolucion);
        if ($conn->ejecutar($SQL))
            return true;
    }

//Realizar una consulta 
    function consultar($cond = "", $ord = "", $lim = "")
    {
        global $conn;
        $SQL = "SELECT * FROM causal_devolucion";
        if (!empty($cond))
            $SQL.= " WHERE $cond";
        if (!empty($ord))
            $SQL.= " ORDER BY $ord";
        if (!empty($lim))
            $SQL.= " LIMIT $lim";
        $this->stmt = $conn->ejecutar($SQL);
        return $this->stmt;
    }

    function buscar($cod)
    {
        global $conn;
        if ($this->consultar("idcausal_devolucion='$cod'"))
            return $this->siguiente();
        return false;
    }

    function siguiente()
    {
        global $conn;
        if ($row = $conn->Siguiente($this->stmt))
        {
//Cargar los datos de la bd
            $this->idcausal_devolucion = $row["idcausal_devolucion"];
            $this->codigo_causal_devolucion = $row["codigo_causal_devolucion"];
            $this->nombre_causal_devolucion = $row["nombre_causal_devolucion"];
            $this->observaciones_causal_devolucion = $row["observaciones_causal_devolucion"];
            return $row;
        }
        return false;
    }

    function eliminar()
    {
        global $conn;
        if ($this->idcausal_devolucion)
        {
            $SQL = sprintf("DELETE FROM causal_devolucion WHERE idcausal_devolucion='%s'", $this->idcausal_devolucion);
            return $conn->ejecutar($SQL);
        }
    }

}

//fin de la clase causal_devolucion

/* * **********************************************************

  CLASE ZONA

 * *********************************************************** */

class zona
{

    var $idzona;
    var $ciudad_idciudad;
    var $nombre_zona;
    var $desde_calle_numero_zona;
    var $desde_calle_letra_zona;
    var $desde_calle_anden_zona;
    var $desde_carrera_numero_zona;
    var $desde_carrera_letra_zona;
    var $desde_carrera_anden_zona;
    var $hasta_calle_numero_zona;
    var $hasta_calle_letra_zona;
    var $hasta_calle_anden_zona;
    var $hasta_carrera_numero_zona;
    var $hasta_carrera_letra_zona;
    var $hasta_carrera_anden_zona;
//Variables de Control
    var $stmt; //Cursor para las consultas

//Funciones

    function cargar()
    {
        if (isset($_GET["codigo"]))
        {//se cargan las variables de la base de datos
            $this->buscar($_GET["codigo"]);
        } else
        {
//Cargar las variables de la forma
            $this->idzona = isset($_POST["idzona"]) ? $_POST["idzona"] : '';
            $this->ciudad_idciudad = isset($_POST["ciudad_idciudad"]) ? $_POST["ciudad_idciudad"] : '';
            $this->nombre_zona = isset($_POST["nombre_zona"]) ? $_POST["nombre_zona"] : '';
            $this->desde_calle_numero_zona = isset($_POST["desde_calle_numero_zona"]) ? $_POST["desde_calle_numero_zona"] : '';
            $this->desde_calle_letra_zona = isset($_POST["desde_calle_letra_zona"]) ? $_POST["desde_calle_letra_zona"] : '';
            $this->desde_calle_anden_zona = isset($_POST["desde_calle_anden_zona"]) ? $_POST["desde_calle_anden_zona"] : '';
            $this->desde_carrera_numero_zona = isset($_POST["desde_carrera_numero_zona"]) ? $_POST["desde_carrera_numero_zona"] : '';
            $this->desde_carrera_letra_zona = isset($_POST["desde_carrera_letra_zona"]) ? $_POST["desde_carrera_letra_zona"] : '';
            $this->desde_carrera_anden_zona = isset($_POST["desde_carrera_anden_zona"]) ? $_POST["desde_carrera_anden_zona"] : '';
            $this->hasta_calle_numero_zona = isset($_POST["hasta_calle_numero_zona"]) ? $_POST["hasta_calle_numero_zona"] : '';
            $this->hasta_calle_letra_zona = isset($_POST["hasta_calle_letra_zona"]) ? $_POST["hasta_calle_letra_zona"] : '';
            $this->hasta_calle_anden_zona = isset($_POST["hasta_calle_anden_zona"]) ? $_POST["hasta_calle_anden_zona"] : '';
            $this->hasta_carrera_numero_zona = isset($_POST["hasta_carrera_numero_zona"]) ? $_POST["hasta_carrera_numero_zona"] : '';
            $this->hasta_carrera_letra_zona = isset($_POST["hasta_carrera_letra_zona"]) ? $_POST["hasta_carrera_letra_zona"] : '';
            $this->hasta_carrera_anden_zona = isset($_POST["hasta_carrera_anden_zona"]) ? $_POST["hasta_carrera_anden_zona"] : '';
        }
    }

    function agregar()
    {
        global $conn;
//calcular el codigo
        $SQL = "select max(idzona) from zona";
        if ($conn->ejecutar($SQL) && ($row = $conn->siguiente(NULL)))
            $this->idzona = $row[0] + 1;
        else
            $this->idzona = 1;
        $SQL = sprintf("INSERT INTO zona (idzona,ciudad_idciudad,nombre_zona,desde_calle_numero_zona,desde_calle_letra_zona,desde_calle_anden_zona,desde_carrera_numero_zona,desde_carrera_letra_zona,desde_carrera_anden_zona,hasta_calle_numero_zona,hasta_calle_letra_zona,hasta_calle_anden_zona,hasta_carrera_numero_zona,hasta_carrera_letra_zona,hasta_carrera_anden_zona)
values('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')
", $this->idzona, $this->ciudad_idciudad, $this->nombre_zona, $this->desde_calle_numero_zona, $this->desde_calle_letra_zona, $this->desde_calle_anden_zona, $this->desde_carrera_numero_zona, $this->desde_carrera_letra_zona, $this->desde_carrera_anden_zona, $this->hasta_calle_numero_zona, $this->hasta_calle_letra_zona, $this->hasta_calle_anden_zona, $this->hasta_carrera_numero_zona, $this->hasta_carrera_letra_zona, $this->hasta_carrera_anden_zona);
        if ($conn->ejecutar($SQL))
            return true;
        else
            $this->idzona = "";
    }

    function modificar()
    {
        global $conn;
        $SQL = sprintf("UPDATE zona SET idzona='%s',ciudad_idciudad='%s',nombre_zona='%s',desde_calle_numero_zona='%s',desde_calle_letra_zona='%s',desde_calle_anden_zona='%s',desde_carrera_numero_zona='%s',desde_carrera_letra_zona='%s',desde_carrera_anden_zona='%s',hasta_calle_numero_zona='%s',hasta_calle_letra_zona='%s',hasta_calle_anden_zona='%s',hasta_carrera_numero_zona='%s',hasta_carrera_letra_zona='%s',hasta_carrera_anden_zona='%s' WHERE idzona=%d "
                , $this->idzona, $this->ciudad_idciudad, $this->nombre_zona, $this->desde_calle_numero_zona, $this->desde_calle_letra_zona, $this->desde_calle_anden_zona, $this->desde_carrera_numero_zona, $this->desde_carrera_letra_zona, $this->desde_carrera_anden_zona, $this->hasta_calle_numero_zona, $this->hasta_calle_letra_zona, $this->hasta_calle_anden_zona, $this->hasta_carrera_numero_zona, $this->hasta_carrera_letra_zona, $this->hasta_carrera_anden_zona, $this->idzona);
        if ($conn->ejecutar($SQL))
            return true;
    }

//Realizar una consulta 
    function consultar($cond = "", $ord = "", $lim = "")
    {
        global $conn;
        $SQL = "SELECT * FROM zona";
        if (!empty($cond))
            $SQL.= " WHERE $cond";
        if (!empty($ord))
            $SQL.= " ORDER BY $ord";
        if (!empty($lim))
            $SQL.= " LIMIT $lim";
        $this->stmt = $conn->ejecutar($SQL);
        return $this->stmt;
    }

    function buscar($cod)
    {
        global $conn;
        if ($this->consultar("idzona='$cod'"))
            return $this->siguiente();
        return false;
    }

    function siguiente()
    {
        global $conn;
        if ($row = $conn->Siguiente($this->stmt))
        {
//Cargar los datos de la bd
            $this->idzona = $row["idzona"];
            $this->ciudad_idciudad = $row["ciudad_idciudad"];
            $this->nombre_zona = $row["nombre_zona"];
            $this->desde_calle_numero_zona = $row["desde_calle_numero_zona"];
            $this->desde_calle_letra_zona = $row["desde_calle_letra_zona"];
            $this->desde_calle_anden_zona = $row["desde_calle_anden_zona"];
            $this->desde_carrera_numero_zona = $row["desde_carrera_numero_zona"];
            $this->desde_carrera_letra_zona = $row["desde_carrera_letra_zona"];
            $this->desde_carrera_anden_zona = $row["desde_carrera_anden_zona"];
            $this->hasta_calle_numero_zona = $row["hasta_calle_numero_zona"];
            $this->hasta_calle_letra_zona = $row["hasta_calle_letra_zona"];
            $this->hasta_calle_anden_zona = $row["hasta_calle_anden_zona"];
            $this->hasta_carrera_numero_zona = $row["hasta_carrera_numero_zona"];
            $this->hasta_carrera_letra_zona = $row["hasta_carrera_letra_zona"];
            $this->hasta_carrera_anden_zona = $row["hasta_carrera_anden_zona"];
            return $row;
        }
        return false;
    }

    function eliminar()
    {
        global $conn;
        if ($this->idzona)
        {
            $SQL = sprintf("DELETE FROM zona WHERE idzona='%s'", $this->idzona);
            return $conn->ejecutar($SQL);
        }
    }

}

//fin de la clase zona

/* * **********************************************************
  CLASE TIPO PRODUCTO

 * *********************************************************** */

class tipo_producto
{

    var $idtipo_producto;
    var $nombre_tipo_producto;
//Variables de Control
    var $stmt; //Cursor para las consultas

//Funciones

    function cargar()
    {
        if (isset($_GET["codigo"]))
        {//se cargan las variables de la base de datos
            $this->buscar($_GET["codigo"]);
        } else
        {
//Cargar las variables de la forma
            $this->idtipo_producto = isset($_POST["idtipo_producto"]) ? $_POST["idtipo_producto"] : '';
            $this->nombre_tipo_producto = isset($_POST["nombre_tipo_producto"]) ? $_POST["nombre_tipo_producto"] : '';
        }
    }

    function agregar()
    {
        global $conn;
//calcular el codigo
        $SQL = "select max(idtipo_producto) from tipo_producto";
        if ($conn->ejecutar($SQL) && ($row = $conn->siguiente(NULL)))
            $this->idtipo_producto = $row[0] + 1;
        else
            $this->idtipo_producto = 1;
        $SQL = sprintf("INSERT INTO tipo_producto (idtipo_producto,nombre_tipo_producto)
values('%s','%s')
", $this->idtipo_producto, $this->nombre_tipo_producto);
        if ($conn->ejecutar($SQL))
            return true;
        else
            $this->idtipo_producto = "";
    }

    function modificar()
    {
        global $conn;
        $SQL = sprintf("UPDATE tipo_producto SET idtipo_producto='%s',nombre_tipo_producto='%s' WHERE idtipo_producto=%d "
                , $this->idtipo_producto, $this->nombre_tipo_producto, $this->idtipo_producto);
        if ($conn->ejecutar($SQL))
            return true;
    }

//Realizar una consulta 
    function consultar($cond = "", $ord = "", $lim = "")
    {
        global $conn;
        $SQL = "SELECT * FROM tipo_producto";
        if (!empty($cond))
            $SQL.= " WHERE $cond";
        if (!empty($ord))
            $SQL.= " ORDER BY $ord";
        if (!empty($lim))
            $SQL.= " LIMIT $lim";
        $this->stmt = $conn->ejecutar($SQL);
        return $this->stmt;
    }

    function buscar($cod)
    {
        global $conn;
        if ($this->consultar("idtipo_producto='$cod'"))
            return $this->siguiente();
        return false;
    }

    function siguiente()
    {
        global $conn;
        if ($row = $conn->Siguiente($this->stmt))
        {
//Cargar los datos de la bd
            $this->idtipo_producto = $row["idtipo_producto"];
            $this->nombre_tipo_producto = $row["nombre_tipo_producto"];
            return $row;
        }
        return false;
    }

    function eliminar()
    {
        global $conn;
        if ($this->idtipo_producto)
        {
            $SQL = sprintf("DELETE FROM tipo_producto WHERE idtipo_producto='%s'", $this->idtipo_producto);
            return $conn->ejecutar($SQL);
        }
    }

}

//fin de la clase tipo_producto

/* * **********************************************************
  CLASE ESTADO

 * *********************************************************** */

class estado
{

    var $idestado;
    var $nombre_estado;
//Variables de Control
    var $stmt; //Cursor para las consultas

//Funciones

    function cargar()
    {
        if (isset($_GET["codigo"]))
        {//se cargan las variables de la base de datos
            $this->buscar($_GET["codigo"]);
        } else
        {
//Cargar las variables de la forma
            $this->idestado = isset($_POST["idestado"]) ? $_POST["idestado"] : '';
            $this->nombre_estado = isset($_POST["nombre_estado"]) ? $_POST["nombre_estado"] : '';
        }
    }

    function agregar()
    {
        global $conn;
//calcular el codigo
        $SQL = "select max(idestado) from estado";
        if ($conn->ejecutar($SQL) && ($row = $conn->siguiente(NULL)))
            $this->idestado = $row[0] + 1;
        else
            $this->idestado = 1;
        $SQL = sprintf("INSERT INTO estado (idestado,nombre_estado)
values('%s','%s')
", $this->idestado, $this->nombre_estado);
        if ($conn->ejecutar($SQL))
            return true;
        else
            $this->idestado = "";
    }

    function modificar()
    {
        global $conn;
        $SQL = sprintf("UPDATE estado SET idestado='%s',nombre_estado='%s' WHERE idestado=%d "
                , $this->idestado, $this->nombre_estado, $this->idestado);
        if ($conn->ejecutar($SQL))
            return true;
    }

//Realizar una consulta 
    function consultar($cond = "", $ord = "", $lim = "")
    {
        global $conn;
        $SQL = "SELECT * FROM estado";
        if (!empty($cond))
            $SQL.= " WHERE $cond";
        if (!empty($ord))
            $SQL.= " ORDER BY $ord";
        if (!empty($lim))
            $SQL.= " LIMIT $lim";
        $this->stmt = $conn->ejecutar($SQL);
        return $this->stmt;
    }

    function buscar($cod)
    {
        global $conn;
        if ($this->consultar("idestado='$cod'"))
            return $this->siguiente();
        return false;
    }

    function siguiente()
    {
        global $conn;
        if ($row = $conn->Siguiente($this->stmt))
        {
//Cargar los datos de la bd
            $this->idestado = $row["idestado"];
            $this->nombre_estado = $row["nombre_estado"];
            return $row;
        }
        return false;
    }

    function eliminar()
    {
        global $conn;
        if ($this->idestado)
        {
            $SQL = sprintf("DELETE FROM estado WHERE idestado='%s'", $this->idestado);
            return $conn->ejecutar($SQL);
        }
    }

}

//fin de la clase estado

/* * **********************************************************
  CLASE Tipo Identificacion

 * *********************************************************** */

class tipo_identificacion
{

    var $idtipo_identificacion;
    var $nombre_tipo_identificacion;
//Variables de Control
    var $stmt; //Cursor para las consultas

//Funciones

    function cargar()
    {
        if (isset($_GET["codigo"]))
        {//se cargan las variables de la base de datos
            $this->buscar($_GET["codigo"]);
        } else
        {
//Cargar las variables de la forma
            $this->idtipo_identificacion = isset($_POST["idtipo_identificacion"]) ? $_POST["idtipo_identificacion"] : '';
            $this->nombre_tipo_identificacion = isset($_POST["nombre_tipo_identificacion"]) ? $_POST["nombre_tipo_identificacion"] : '';
        }
    }

    function agregar()
    {
        global $conn;
//calcular el codigo
        $SQL = "select max(idtipo_identificacion) from tipo_identificacion";
        if ($conn->ejecutar($SQL) && ($row = $conn->siguiente(NULL)))
            $this->idtipo_identificacion = $row[0] + 1;
        else
            $this->idtipo_identificacion = 1;
        $SQL = sprintf("INSERT INTO tipo_identificacion (idtipo_identificacion,nombre_tipo_identificacion)
values('%s','%s')
", $this->idtipo_identificacion, $this->nombre_tipo_identificacion);
        if ($conn->ejecutar($SQL))
            return true;
        else
            $this->idtipo_identificacion = "";
    }

    function modificar()
    {
        global $conn;
        $SQL = sprintf("UPDATE tipo_identificacion SET idtipo_identificacion='%s',nombre_tipo_identificacion='%s' WHERE idtipo_identificacion=%d "
                , $this->idtipo_identificacion, $this->nombre_tipo_identificacion, $this->idtipo_identificacion);
        if ($conn->ejecutar($SQL))
            return true;
    }

//Realizar una consulta 
    function consultar($cond = "", $ord = "", $lim = "")
    {
        global $conn;
        $SQL = "SELECT * FROM tipo_identificacion";
        if (!empty($cond))
            $SQL.= " WHERE $cond";
        if (!empty($ord))
            $SQL.= " ORDER BY $ord";
        if (!empty($lim))
            $SQL.= " LIMIT $lim";
        $this->stmt = $conn->ejecutar($SQL);
        return $this->stmt;
    }

    function buscar($cod)
    {
        global $conn;
        if ($this->consultar("idtipo_identificacion='$cod'"))
            return $this->siguiente();
        return false;
    }

    function siguiente()
    {
        global $conn;
        if ($row = $conn->Siguiente($this->stmt))
        {
//Cargar los datos de la bd
            $this->idtipo_identificacion = $row["idtipo_identificacion"];
            $this->nombre_tipo_identificacion = $row["nombre_tipo_identificacion"];
            return $row;
        }
        return false;
    }

    function eliminar()
    {
        global $conn;
        if ($this->idtipo_identificacion)
        {
            $SQL = sprintf("DELETE FROM tipo_identificacion WHERE idtipo_identificacion='%s'", $this->idtipo_identificacion);
            return $conn->ejecutar($SQL);
        }
    }

}

//fin de la clase tipo_identificacion

/* * **********************************************************
  CLASE Tercero_Tipo

 * *********************************************************** */

class tercero_tipo
{

    var $idtercero_tipo;
    var $tercero_idtercero;
    var $tipo_tercero_idtipo_tercero;
//Variables de Control
    var $stmt; //Cursor para las consultas

//Funciones

    function cargar()
    {
        if (isset($_GET["codigo"]))
        {//se cargan las variables de la base de datos
            $this->buscar($_GET["codigo"]);
        } else
        {
//Cargar las variables de la forma
            $this->idtercero_tipo = isset($_POST["idtercero_tipo"]) ? $_POST["idtercero_tipo"] : '';
            $this->tercero_idtercero = isset($_POST["tercero_idtercero"]) ? $_POST["tercero_idtercero"] : '';
            $this->tipo_tercero_idtipo_tercero = isset($_POST["tipo_tercero_idtipo_tercero"]) ? $_POST["tipo_tercero_idtipo_tercero"] : '';
        }
    }

    function agregar()
    {
        global $conn;
//calcular el codigo
        $SQL = "select max(idtercero_tipo) from tercero_tipo";
        if ($conn->ejecutar($SQL) && ($row = $conn->siguiente(NULL)))
            $this->idtercero_tipo = $row[0] + 1;
        else
            $this->idtercero_tipo = 1;
        $SQL = sprintf("INSERT INTO tercero_tipo (idtercero_tipo,tercero_idtercero,tipo_tercero_idtipo_tercero)
values('%s','%s','%s')
", $this->idtercero_tipo, $this->tercero_idtercero, $this->tipo_tercero_idtipo_tercero);
        if ($conn->ejecutar($SQL))
            return true;
        else
            $this->idtercero_tipo = "";
    }

    function modificar()
    {
        global $conn;
        $SQL = sprintf("UPDATE tercero_tipo SET idtercero_tipo='%s',tercero_idtercero='%s',tipo_tercero_idtipo_tercero='%s' WHERE idtercero_tipo=%d "
                , $this->idtercero_tipo, $this->tercero_idtercero, $this->tipo_tercero_idtipo_tercero, $this->idtercero_tipo);
        if ($conn->ejecutar($SQL))
            return true;
    }

//Realizar una consulta 
    function consultar($cond = "", $ord = "", $lim = "")
    {
        global $conn;
        $SQL = "SELECT * FROM tercero_tipo";
        if (!empty($cond))
            $SQL.= " WHERE $cond";
        if (!empty($ord))
            $SQL.= " ORDER BY $ord";
        if (!empty($lim))
            $SQL.= " LIMIT $lim";
        $this->stmt = $conn->ejecutar($SQL);
        return $this->stmt;
    }

    function buscar($cod)
    {
        global $conn;
        if ($this->consultar("idtercero_tipo='$cod'"))
            return $this->siguiente();
        return false;
    }

    function siguiente()
    {
        global $conn;
        if ($row = $conn->Siguiente($this->stmt))
        {
//Cargar los datos de la bd
            $this->idtercero_tipo = $row["idtercero_tipo"];
            $this->tercero_idtercero = $row["tercero_idtercero"];
            $this->tipo_tercero_idtipo_tercero = $row["tipo_tercero_idtipo_tercero"];
            return $row;
        }
        return false;
    }

    function eliminar()
    {
        global $conn;
        if ($this->idtercero_tipo)
        {
            $SQL = sprintf("DELETE FROM tercero_tipo WHERE idtercero_tipo='%s'", $this->idtercero_tipo);
            return $conn->ejecutar($SQL);
        }
    }

}

//fin de la clase tercero_tipo

/* * **********************************************************
  CLASE ciudad_tercero

 * *********************************************************** */

class ciudad_tercero
{

    var $idciudad_tercero;
    var $idtercero;
    var $idciudad;
//Variables de Control
    var $stmt; //Cursor para las consultas

//Funciones

    function cargar()
    {
        if (isset($_GET["codigo"]))
        {//se cargan las variables de la base de datos
            $this->buscar($_GET["codigo"]);
        } else
        {
//Cargar las variables de la forma
            $this->idciudad_tercero = isset($_POST["idciudad_tercero"]) ? $_POST["idciudad_tercero"] : '';
            $this->idtercero = isset($_POST["idtercero"]) ? $_POST["idtercero"] : '';
            $this->idciudad = isset($_POST["idciudad"]) ? $_POST["idciudad"] : '';
        }
    }

    function agregar()
    {
        global $conn;
//calcular el codigo
        $SQL = "select max(idciudad_tercero) from ciudad_tercero";
        if ($conn->ejecutar($SQL) && ($row = $conn->siguiente(NULL)))
            $this->idciudad_tercero = $row[0] + 1;
        else
            $this->idciudad_tercero = 1;
        $SQL = sprintf("INSERT INTO ciudad_tercero (idciudad_tercero,idtercero,idciudad)
values('%s','%s','%s')
", $this->idciudad_tercero, $this->idtercero, $this->idciudad);
        if ($conn->ejecutar($SQL))
            return true;
        else
            $this->idciudad_tercero = "";
    }

    function modificar()
    {
        global $conn;
        $SQL = sprintf("UPDATE ciudad_tercero SET idciudad_tercero='%s',idtercero='%s',idciudad='%s' WHERE idciudad_tercero=%d "
                , $this->idciudad_tercero, $this->idtercero, $this->idciudad, $this->idciudad_tercero);
        if ($conn->ejecutar($SQL))
            return true;
    }

//Realizar una consulta 
    function consultar($cond = "", $ord = "", $lim = "")
    {
        global $conn;
        $SQL = "SELECT * FROM ciudad_tercero";
        if (!empty($cond))
            $SQL.= " WHERE $cond";
        if (!empty($ord))
            $SQL.= " ORDER BY $ord";
        if (!empty($lim))
            $SQL.= " LIMIT $lim";
        $this->stmt = $conn->ejecutar($SQL);
        return $this->stmt;
    }

    function buscar($cod)
    {
        global $conn;
        if ($this->consultar("idciudad_tercero='$cod'"))
            return $this->siguiente();
        return false;
    }

    function siguiente()
    {
        global $conn;
        if ($row = $conn->Siguiente($this->stmt))
        {
//Cargar los datos de la bd
            $this->idciudad_tercero = $row["idciudad_tercero"];
            $this->idtercero = $row["idtercero"];
            $this->idciudad = $row["idciudad"];
            return $row;
        }
        return false;
    }

    function eliminar()
    {
        global $conn;
        if ($this->idciudad_tercero)
        {
            $SQL = sprintf("DELETE FROM ciudad_tercero WHERE idciudad_tercero='%s'", $this->idciudad_tercero);
            return $conn->ejecutar($SQL);
        }
    }

}

//fin de la clase ciudad_tercero

/* * **********************************************************
  CLASE departamento

 * *********************************************************** */

class departamento
{

    var $iddepartamento;
    var $nombre_departamento;
    var $pais_idpais;
//Variables de Control
    var $stmt; //Cursor para las consultas

//Funciones

    function cargar()
    {
        if (isset($_GET["codigo"]))
        {//se cargan las variables de la base de datos
            $this->buscar($_GET["codigo"]);
        } else
        {
//Cargar las variables de la forma
            $this->iddepartamento = isset($_POST["iddepartamento"]) ? $_POST["iddepartamento"] : '';
            $this->nombre_departamento = isset($_POST["nombre_departamento"]) ? $_POST["nombre_departamento"] : '';
            $this->pais_idpais = isset($_POST["pais_idpais"]) ? $_POST["pais_idpais"] : '';
        }
    }

    function agregar()
    {
        global $conn;
//calcular el codigo
        $SQL = "select max(iddepartamento) from departamento";
        if ($conn->ejecutar($SQL) && ($row = $conn->siguiente(NULL)))
            $this->iddepartamento = $row[0] + 1;
        else
            $this->iddepartamento = 1;
        $SQL = sprintf("INSERT INTO departamento (iddepartamento,nombre_departamento,pais_idpais)
values('%s','%s','%s')
", $this->iddepartamento, $this->nombre_departamento, $this->pais_idpais);
        if ($conn->ejecutar($SQL))
            return true;
        else
            $this->iddepartamento = "";
    }

    function modificar()
    {
        global $conn;
        $SQL = sprintf("UPDATE departamento SET iddepartamento='%s',nombre_departamento='%s',pais_idpais='%s' WHERE iddepartamento=%d "
                , $this->iddepartamento, $this->nombre_departamento, $this->pais_idpais, $this->iddepartamento);
        if ($conn->ejecutar($SQL))
            return true;
    }

//Realizar una consulta 
    function consultar($cond = "", $ord = "", $lim = "")
    {
        global $conn;
        $SQL = "SELECT * FROM departamento";
        if (!empty($cond))
            $SQL.= " WHERE $cond";
        if (!empty($ord))
            $SQL.= " ORDER BY $ord";
        if (!empty($lim))
            $SQL.= " LIMIT $lim";
        $this->stmt = $conn->ejecutar($SQL);
        return $this->stmt;
    }

    function buscar($cod)
    {
        global $conn;
        if ($this->consultar("iddepartamento='$cod'"))
            return $this->siguiente();
        return false;
    }

    function siguiente()
    {
        global $conn;
        if ($row = $conn->Siguiente($this->stmt))
        {
//Cargar los datos de la bd
            $this->iddepartamento = $row["iddepartamento"];
            $this->nombre_departamento = $row["nombre_departamento"];
            $this->pais_idpais = $row["pais_idpais"];
            return $row;
        }
        return false;
    }

    function eliminar()
    {
        global $conn;
        if ($this->iddepartamento)
        {
            $SQL = sprintf("DELETE FROM departamento WHERE iddepartamento='%s'", $this->iddepartamento);
            return $conn->ejecutar($SQL);
        }
    }

}

//fin de la clase departamento

/* * **********************************************************
  CLASE destinatario

 * *********************************************************** */

class destinatario
{

    var $iddestinatario;
    var $tipo_identificacion_destinatario;
    var $documento_destinatario;
    var $nombres_destinatario;
    var $apellidos_destinatario;
    var $direccion_destinatario;
    var $datos1;
    var $datos2;
    var $telefono_destinatario;
    var $telefono2_destinatario;
    var $celular_destinatario;
    var $email_destinatario;
    var $observaciones_destinatario;
//Variables de Control
    var $stmt; //Cursor para las consultas

//Funciones

    function cargar()
    {
        if (isset($_GET["codigo"]))
        {//se cargan las variables de la base de datos
            $this->buscar($_GET["codigo"]);
        } else
        {
//Cargar las variables de la forma
            $this->iddestinatario = isset($_POST["iddestinatario"]) ? $_POST["iddestinatario"] : '';
            $this->tipo_identificacion_destinatario = isset($_POST["tipo_identificacion_destinatario"]) ? $_POST["tipo_identificacion_destinatario"] : '';
            $this->documento_destinatario = isset($_POST["documento_destinatario"]) ? $_POST["documento_destinatario"] : '';
            $this->nombres_destinatario = isset($_POST["nombres_destinatario"]) ? $_POST["nombres_destinatario"] : '';
            $this->apellidos_destinatario = isset($_POST["apellidos_destinatario"]) ? $_POST["apellidos_destinatario"] : '';
            $this->direccion_destinatario = isset($_POST["direccion_destinatario"]) ? $_POST["direccion_destinatario"] : '';
            $this->datos1 = isset($_POST["datos1"]) ? $_POST["datos1"] : '';
            $this->datos2 = isset($_POST["datos2"]) ? $_POST["datos2"] : '';
            $this->telefono_destinatario = isset($_POST["telefono_destinatario"]) ? $_POST["telefono_destinatario"] : '';
            $this->telefono2_destinatario = isset($_POST["telefono2_destinatario"]) ? $_POST["telefono2_destinatario"] : '';
            $this->celular_destinatario = isset($_POST["celular_destinatario"]) ? $_POST["celular_destinatario"] : '';
            $this->email_destinatario = isset($_POST["email_destinatario"]) ? $_POST["email_destinatario"] : '';
            $this->observaciones_destinatario = isset($_POST["observaciones_destinatario"]) ? $_POST["observaciones_destinatario"] : '';
        }
    }

    function agregar()
    {
        global $conn;
//calcular el codigo
        $SQL = "select max(iddestinatario) from destinatario";
        if ($conn->ejecutar($SQL) && ($row = $conn->siguiente(NULL)))
            $this->iddestinatario = $row[0] + 1;
        else
            $this->iddestinatario = 1;
        $SQL = sprintf("INSERT INTO destinatario (iddestinatario,tipo_identificacion_destinatario,documento_destinatario,nombres_destinatario,apellidos_destinatario,direccion_destinatario,datos1,datos2,telefono_destinatario,telefono2_destinatario,celular_destinatario,email_destinatario,observaciones_destinatario)
values('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')
", $this->iddestinatario, $this->tipo_identificacion_destinatario, $this->documento_destinatario, $this->nombres_destinatario, $this->apellidos_destinatario, $this->direccion_destinatario, $this->datos1, $this->datos2, $this->telefono_destinatario, $this->telefono2_destinatario, $this->celular_destinatario, $this->email_destinatario, $this->observaciones_destinatario);
        if ($conn->ejecutar($SQL))
            return true;
        else
            $this->iddestinatario = "";
    }

    function modificar()
    {
        global $conn;
        $SQL = sprintf("UPDATE destinatario SET iddestinatario='%s',tipo_identificacion_destinatario='%s',documento_destinatario='%s',nombres_destinatario='%s',apellidos_destinatario='%s',direccion_destinatario='%s',datos1='%s',datos2='%s',telefono_destinatario='%s',telefono2_destinatario='%s',celular_destinatario='%s',email_destinatario='%s',observaciones_destinatario='%s' WHERE iddestinatario=%d "
                , $this->iddestinatario, $this->tipo_identificacion_destinatario, $this->documento_destinatario, $this->nombres_destinatario, $this->apellidos_destinatario, $this->direccion_destinatario, $this->datos1, $this->datos2, $this->telefono_destinatario, $this->telefono2_destinatario, $this->celular_destinatario, $this->email_destinatario, $this->observaciones_destinatario, $this->iddestinatario);
        if ($conn->ejecutar($SQL))
            return true;
    }

//Realizar una consulta 
    function consultar($cond = "", $ord = "", $lim = "")
    {
        global $conn;
        $SQL = "SELECT * FROM destinatario";
        if (!empty($cond))
            $SQL.= " WHERE $cond";
        if (!empty($ord))
            $SQL.= " ORDER BY $ord";
        if (!empty($lim))
            $SQL.= " LIMIT $lim";
        $this->stmt = $conn->ejecutar($SQL);
        return $this->stmt;
    }

    function buscar($cod)
    {
        global $conn;
        if ($this->consultar("iddestinatario='$cod'"))
            return $this->siguiente();
        return false;
    }

    function siguiente()
    {
        global $conn;
        if ($row = $conn->Siguiente($this->stmt))
        {
//Cargar los datos de la bd
            $this->iddestinatario = $row["iddestinatario"];
            $this->tipo_identificacion_destinatario = $row["tipo_identificacion_destinatario"];
            $this->documento_destinatario = $row["documento_destinatario"];
            $this->nombres_destinatario = $row["nombres_destinatario"];
            $this->apellidos_destinatario = $row["apellidos_destinatario"];
            $this->direccion_destinatario = $row["direccion_destinatario"];
            $this->datos1 = $row["datos1"];
            $this->datos2 = $row["datos2"];
            $this->telefono_destinatario = $row["telefono_destinatario"];
            $this->telefono2_destinatario = $row["telefono2_destinatario"];
            $this->celular_destinatario = $row["celular_destinatario"];
            $this->email_destinatario = $row["email_destinatario"];
            $this->observaciones_destinatario = $row["observaciones_destinatario"];
            return $row;
        }
        return false;
    }

    function eliminar()
    {
        global $conn;
        if ($this->iddestinatario)
        {
            $SQL = sprintf("DELETE FROM destinatario WHERE iddestinatario='%s'", $this->iddestinatario);
            return $conn->ejecutar($SQL);
        }
    }

}

//fin de la clase destinatario
/* * **********************************************************
  CLASE asignacion_guias

 * *********************************************************** */

class asignacion_guias
{

    var $idasignacion_guias;
    var $sucursal_idsucursal;
    var $tercero_idtercero;
    var $fecha_asignacion;
    var $hora_asignacion;
    var $inicial_asignacion;
    var $cantidad_asignacion;
    var $saldo_asignnacion;
    var $estado_asignacion;
    var $observaciones_asignacion;
//Variables de Control
    var $stmt; //Cursor para las consultas
    var $tipo;

//Funciones

    function cargar()
    {
        if (isset($_GET["codigo"]))
        {//se cargan las variables de la base de datos
            $this->buscar($_GET["codigo"]);
        } else
        {
//Cargar las variables de la forma
            $this->idasignacion_guias = isset($_POST["idasignacion_guias"]) ? $_POST["idasignacion_guias"] : '';
            $this->sucursal_idsucursal = isset($_POST["sucursal_idsucursal"]) ? $_POST["sucursal_idsucursal"] : '';
            $this->tercero_idtercero = isset($_POST["tercero_idtercero"]) ? $_POST["tercero_idtercero"] : '';
            $this->fecha_asignacion = isset($_POST["fecha_asignacion"]) ? $_POST["fecha_asignacion"] : '';
            $this->hora_asignacion = isset($_POST["hora_asignacion"]) ? $_POST["hora_asignacion"] : '';
            $this->inicial_asignacion = isset($_POST["inicial_asignacion"]) ? $_POST["inicial_asignacion"] : '';
            $this->cantidad_asignacion = isset($_POST["cantidad_asignacion"]) ? $_POST["cantidad_asignacion"] : '';
            $this->saldo_asignnacion = isset($_POST["saldo_asignnacion"]) ? $_POST["saldo_asignnacion"] : '';
            $this->estado_asignacion = isset($_POST["estado_asignacion"]) ? $_POST["estado_asignacion"] : '';
            $this->observaciones_asignacion = isset($_POST["observaciones_asignacion"]) ? $_POST["observaciones_asignacion"] : '';
        }
    }

    function agregar()
    {
        global $conn;
//calcular el codigo
        $SQL = "select max(idasignacion_guias) from asignacion_guias";
        if ($conn->ejecutar($SQL) && ($row = $conn->siguiente(NULL)))
            $this->idasignacion_guias = $row[0] + 1;
        else
            $this->idasignacion_guias = 1;
        $SQL = sprintf("INSERT INTO asignacion_guias (asigTipo,idasignacion_guias,sucursal_idsucursal,tercero_idtercero,inicial_asignacion,cantidad_asignacion,saldo_asignnacion,observaciones_asignacion)
values(%s,'%s','%s','%s','%s','%s','%s','%s')
", $this->tipo, $this->idasignacion_guias, $this->sucursal_idsucursal, $this->tercero_idtercero, $this->inicial_asignacion, $this->cantidad_asignacion, $this->saldo_asignnacion, $this->observaciones_asignacion);
        if ($conn->ejecutar($SQL))
            return true;
        else
            $this->idasignacion_guias = "";
    }

    function modificar()
    {
        global $conn;
        $SQL = sprintf("UPDATE asignacion_guias SET idasignacion_guias='%s',sucursal_idsucursal='%s',tercero_idtercero='%s',fecha_asignacion='%s',hora_asignacion='%s',inicial_asignacion='%s',cantidad_asignacion='%s',saldo_asignnacion='%s',estado_asignacion='%s',observaciones_asignacion='%s' WHERE idasignacion_guias=%d "
                , $this->idasignacion_guias, $this->sucursal_idsucursal, $this->tercero_idtercero, $this->fecha_asignacion, $this->hora_asignacion, $this->inicial_asignacion, $this->cantidad_asignacion, $this->saldo_asignnacion, $this->estado_asignacion, $this->observaciones_asignacion, $this->idasignacion_guias);
        if ($conn->ejecutar($SQL))
            return true;
    }

//Realizar una consulta 
    function consultar($cond = "", $ord = "", $lim = "")
    {
        global $conn;
        $SQL = "SELECT * FROM asignacion_guias";
        if (!empty($cond))
            $SQL.= " WHERE $cond";
        if (!empty($ord))
            $SQL.= " ORDER BY $ord";
        if (!empty($lim))
            $SQL.= " LIMIT $lim";
        $this->stmt = $conn->ejecutar($SQL);
        return $this->stmt;
    }

    function buscar($cod)
    {
        global $conn;
        if ($this->consultar("idasignacion_guias='$cod'"))
            return $this->siguiente();
        return false;
    }

    function siguiente()
    {
        global $conn;
        if ($row = $conn->Siguiente($this->stmt))
        {
//Cargar los datos de la bd
            $this->idasignacion_guias = $row["idasignacion_guias"];
            $this->sucursal_idsucursal = $row["sucursal_idsucursal"];
            $this->tercero_idtercero = $row["tercero_idtercero"];
            $this->fecha_asignacion = $row["fecha_asignacion"];
            $this->hora_asignacion = $row["hora_asignacion"];
            $this->inicial_asignacion = $row["inicial_asignacion"];
            $this->cantidad_asignacion = $row["cantidad_asignacion"];
            $this->saldo_asignnacion = $row["saldo_asignnacion"];
            $this->estado_asignacion = $row["estado_asignacion"];
            $this->observaciones_asignacion = $row["observaciones_asignacion"];
            return $row;
        }
        return false;
    }

    function eliminar()
    {
        global $conn;
        if ($this->idasignacion_guias)
        {
            $SQL = sprintf("DELETE FROM asignacion_guias WHERE idasignacion_guias='%s'", $this->idasignacion_guias);
            return $conn->ejecutar($SQL);
        }
    }

}

//fin de la clase asignacion_guias
//*********************************************************************************************************************
//CLASE CAUSALES
//**********************************************************************************//


class causales
{

    var $idcausales;
    var $nombrecausales;
    var $descripcioncausales;
    //Variables de Control
    var $stmt; //Cursor para las consultas

    //Funciones

    function cargar()
    {

        if (isset($_GET["codigo"]))
        {//se cargan las variables de la base de datos
            $this->buscar($_GET["codigo"]);
        } else
        {

            //Cargar las variables de la forma
            $this->idcausales = isset($_POST["idcausales"]) ? $_POST["idcausales"] : '';
            $this->nombrecausales = isset($_POST["nombrecausales"]) ? $_POST["nombrecausales"] : '';
            $this->descripcioncausales = isset($_POST["descripcioncausales"]) ? $_POST["descripcioncausales"] : '';
        }
    }

    function agregar()
    {

        global $conn;
        //calcular el codigo
        $SQL = "select max(idcausales) from causales";
        if ($conn->ejecutar($SQL) && ($row = $conn->siguiente(NULL)))
            $this->idcausales = $row[0] + 1;
        else
            $this->idcausales = 1;
        $SQL = sprintf("INSERT INTO causales (idcausales,nombrecausales,descripcioncausales)
        values('%s','%s','%s')
        ", $this->idcausales, $this->nombrecausales, $this->descripcioncausales);
        if ($conn->ejecutar($SQL))
            return true;
        else
            $this->idcausales = "";
    }

    function modificar()
    {

        global $conn;
        $SQL = sprintf("UPDATE causales SET idcausales='%s',nombrecausales='%s',descripcioncausales='%s' WHERE idcausales=%d "
                , $this->idcausales, $this->nombrecausales, $this->descripcioncausales, $this->idcausales);
        if ($conn->ejecutar($SQL))
            return true;
    }

    //Realizar una consulta
    function consultar($cond = "", $ord = "", $lim = "")
    {

        global $conn;
        $SQL = "SELECT * FROM causales";
        if (!empty($cond))
            $SQL.= " WHERE $cond";
        if (!empty($ord))
            $SQL.= " ORDER BY $ord";
        if (!empty($lim))
            $SQL.= " LIMIT $lim";
        $this->stmt = $conn->ejecutar($SQL);
        return $this->stmt;
    }

    function buscar($cod)
    {

        global $conn;
        if ($this->consultar("idcausales='$cod'"))
            return $this->siguiente();
        return false;
    }

    function siguiente()
    {

        global $conn;
        if ($row = $conn->Siguiente($this->stmt))
        {

            //Cargar los datos de la bd
            $this->idcausales = $row["idcausales"];
            $this->nombrecausales = $row["nombrecausales"];
            $this->descripcioncausales = $row["descripcioncausales"];
            return $row;
        }
        return false;
    }

    function eliminar()
    {

        global $conn;
        if ($this->idcausales)
        {

            $SQL = sprintf("DELETE FROM causales WHERE idcausales='%s'", $this->idcausales);
            return $conn->ejecutar($SQL);
        }
    }

}

//fin de la clase causales 
?>

