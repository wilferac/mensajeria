<?php

   /**
    * esta clase se va a encargar del logueo del usuario
    * guardando los datos del mismo en la variable de session
    *
    * @author inovate
    */
   session_start();
   //miro los archivos incluidos para no caer en el error de incluir de nuevo el mismo
   $archivos_incluidos = get_included_files();

    $mirar=false;
   foreach ($archivos_incluidos as $nombre_archivo)
   {
       if($nombre_archivo == "/home/inovate/public_html/Mensajeria/conexion/conexion.php")
       {
           $mirar=true;
           break;
       }
   }
   if(!$mirar)
   {
       require "../conexion/conexion.php";
   }

   class User
   {

       private $id;
       private $login;
       private $pass;
       private $roles;
       private $isLog;
       private $nombre;
       private $apellido;
       private $numDocu;
       private $idSucursal;
       
       public function __construct($login, $pass)
       {

           $this->id = -1;
           $this->login = $login;
           $this->pass = $pass;
           $this->isLog = false;
           $roles = array();
           $this->nombre = "";
           $this->apellido = "";
           $numDocu=-1;
       }

       //funcion para saber si esta logueado 
       public function getStatus()
       {
           //returno el estado
           return $this->isLog;
       }
       
       public function getNumDocu()
       {
           return $this->numDocu;
           
       }

       //hago la query para loguarlo :D
       public function login()
       {
           $query2 = "select t.nombres_tercero, t.apellidos_tercero, t.documento_tercero, t.idtercero, s.idsucursal
               from tercero t
               inner join sucursal s 
               on t.sucursal_idsucursal = s.idsucursal
               where usuario_tercero= '$this->login' 
                   and clave_tercero = MD5('$this->pass')
                   and estado = 'Activo'";

           $results2 = mysql_query($query2) or die(mysql_error());

           if ($fila = mysql_fetch_assoc($results2))
           {
               $this->nombre = $fila['nombres_tercero'];
               $this->apellido=$fila['apellidos_tercero'];
               $this->numDocu=$fila['documento_tercero'];
               $this->idSucursal=$fila['idsucursal'];
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
           echo($this->pass);
           echo("<br>");
           echo("id de sucursal: ".$this->idSucursal);
           echo("<br>");
           print_r($this->roles);
       }
       
       public function checkRol($rol)
       {
           //el admin tiene todos los roles :D
           if($this->roles["Admin"]==1)
           {
               return true;
           }
               
           
           if($this->roles[$rol] == 1)
           {
               return true;
           }
           else
               return false;
       }

   }

?>
