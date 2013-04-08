<?php

   /**
    * User class is for manage user login and more :D
    *
    * @author Wilson Fernando Andrade Cordoba <wilferac@gmail.com>
    * @author Grupo Innovate 
    * @version 0.9
    */
   session_start();
   //miro los archivos incluidos para no caer en el error de incluir de nuevo el mismo
   $archivos_incluidos = get_included_files();

   $mirar = false;
   foreach ($archivos_incluidos as $nombre_archivo)
   {
       if ($nombre_archivo == "/home/inovate/public_html/Mensajeria/conexion/conexion.php")
       {
           $mirar = true;
           break;
       }
   }
   if (!$mirar)
   {
       require "/home/inovate/public_html/Mensajeria/conexion/conexion.php";
   }

   class User
   {
       /**
        * @var int the id of the user in the data base
        */
       private $id;
       /**
        * @var string 
        */
       private $login;
       /**
        * @var string
        */
       private $pass;
       /**
        * @var array store user's roles
        */
       private $roles;
        /**
        * @var boolean
        */
       private $isLog;
        /**
        * @var string
        */
       private $nombre;
       /**
        * @var string
        */
       private $apellido;
       /**
        * @var string
        */
       private $numDocu;
       /**
        * @var int
        */
       private $idSucursal;
       /**
        * @var int the id of user's city
        */
       private $idCiudad;
       /**
        * @var int the id of user's departamento
        */
       private $idDepartamento;

       public function __construct($login, $pass)
       {

           $this->id = -1;
           $this->login = $login;
           $this->pass = $pass;
           $this->isLog = false;
           $roles = array();
           $this->nombre = "";
           $this->apellido = "";
           $numDocu = -1;
       }

       /**
        * getStatus se usa para saber si el usuario esta logueado o no
        * 
        * @return boolean
        */
       public function getStatus()
       {
           //returno el estado
           return $this->isLog;
       }

       public function getNumDocu()
       {
           return $this->numDocu;
       }

       public function getIdSucursal()
       {
           return $this->idSucursal;
       }

       public function getIdCiudad()
       {
           return $this->idCiudad;
       }

       public function getIdDepartamento()
       {
           return $this->idDepartamento;
       }

       /**
        * login method , loged a user into the system
        * 
        * @return boolean return true o success login or false on fail
        */
       public function login()
       {
           $query2 = "select t.nombres_tercero, t.apellidos_tercero, t.documento_tercero, t.idtercero, s.idsucursal, s.ciudad_idciudad, c.departamento_iddepartamento
               from tercero t
               inner join sucursal s 
               on t.sucursal_idsucursal = s.idsucursal
               inner join ciudad c on c.idciudad = s.ciudad_idciudad
               where usuario_tercero= '$this->login' 
                   and clave_tercero = MD5('$this->pass')
                   and estado = 'Activo'";

           $results2 = mysql_query($query2) or die(mysql_error());

           if ($fila = mysql_fetch_assoc($results2))
           {
               $this->nombre = $fila['nombres_tercero'];
               $this->apellido = $fila['apellidos_tercero'];
               $this->numDocu = $fila['documento_tercero'];
               $this->idSucursal = $fila['idsucursal'];
               $this->idCiudad = $fila['ciudad_idciudad'];
               $this->idDepartamento = $fila['departamento_iddepartamento'];
               $this->isLog = true;

               $this->id = $fila['idtercero'];
               //recupero los datos de los roles
               $query = "select tt.nombre_tipo_tercero
                   from tercero_tipo tp 
                   inner join tipo_tercero tt 
                   on tt.idtipo_tercero = tp.tipo_tercero_idtipo_tercero 
                   where tp.tercero_idtercero = $this->id";

               $results = mysql_query($query) or die(mysql_error());
               while ($registros = mysql_fetch_assoc($results))
               {
                   $rol = $registros['nombre_tipo_tercero'];
                   $this->roles[$rol] = 1;
               }

               return true;
           }
           else
           {
               return false;
           }
       }

       public function getId()
       {
           return $this->id;
       }

       public function getLogin()
       {
           return $this->login;
       }

       public function getNombre()
       {
           return $this->nombre;
       }

       public function getApellido()
       {
           return $this->apellido;
       }

       /**
        * show the basic user data
        */
       public function show()
       {
           echo($this->id);
           echo("<br>");
           echo($this->numDocu);
           echo("<br>");
           echo($this->login);
           echo("<br>");
           echo("is log: " . $this->isLog);
           echo("<br>");
           //echo($this->pass);
           //echo("<br>");
           echo("id de sucursal: " . $this->idSucursal);
           echo("<br>");
           echo("id de ciudad: " . $this->idCiudad);
           echo("<br>");
           echo("id de departamento: " . $this->idDepartamento);
           echo("<br>");
           print_r($this->roles);
       }

       /**
        * check if the user own a specific rol
        * 
        * @param string $rol the rol for compare
        * @return boolean true o false depending if the user have the rol or not
        */
       public function checkRol($rol)
       {
           //el admin tiene todos los roles :D
           if ($this->roles["Admin"] == 1)
           {
               return true;
           }


           if ($this->roles[$rol] == 1)
           {
               return true;
           }
           else
               return false;
       }

   }

?>
