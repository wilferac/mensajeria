<?php

//global $conn;
  session_start();
  $conn = new conexion();

  class conexion
  {

      var $servidor;
      var $usuario;
      var $clave;
      var $bd;
      var $id;
      var $stmt;
      var $error;
      var $numerror;

      function conexion() // Constructor 
      {
          $this->servidor = $_SESSION['param']['bdhost'];
          $this->usuario = $_SESSION['param']['bduser'];
          $this->clave = $_SESSION['param']['bdpass'];
          $this->bd = $_SESSION['param']['bd'];
          $this->conectar();
      }

      function conectar()
      {
          if ($this->id)
              $this->cerrar();
          $this->id = mysql_connect($this->servidor, $this->usuario, $this->clave);
          $selDB = mysql_select_db($this->bd);

          if (!$this->id || !$selDB)
          {
              echo "Error conectando a la base de datos.";
              exit();
          }
          else
          {
              mysql_query("SET NAMES 'utf8'");
              return $this->id;
          }
      }

      function cerrar()
      {
          if ($this->id)
              return mysql_close($this->id);
      }

      function ejecutar($sql)
      {
          if ($this->id)
          //conectar();		
              if ($this->stmt = @mysql_query($sql))
              {

                  return $this->stmt;
              }
              else
              {
                  echo mysql_error();
                  echo "Error al Ejecutar la consulta: $sql";
                  return false;
              }
      }

      function siguiente($stm)
      {
          if ($stm)
          {
              return mysql_fetch_array($stm);
          }
          else
          {
              return mysql_fetch_array($this->stmt);
          }
          return false;
      }

      function nrofilas($stmt = "")
      {
          if ($stmt)
              return mysql_num_rows($stmt);
          else
              return mysql_num_rows($this->stmt);
      }

  }

  //fin de la clase conexi�n
?>
