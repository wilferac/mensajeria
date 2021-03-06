<?

/**
 * clase para coneccion ftp.
 */
class conexionftp
{
  var $id;
  var $host;
  var $usuario;
  var $clave;
  var $directorio;

  function conexion($host, $usuario, $clave, $directorio) // Constructor
  {
    $this->host = $host;
    $this->usuario = $usuario;
    $this->clave = $clave;
    $this->directorio = $directorio;

    $this->conectar();
  }

  function conectar()
  {
    if ($this->id)
      $this->cerrar();

    $this->id = ftp_connect($this->host);
    $login_result = ftp_login($this->id, $this->usuario, $this->clave);
    if ((!$this->id) || (!$login_result)) {
      echo "¡La conexión FTP ha fallado!";
      echo "Se intentó conectar al $this->host por el usuario this->$usuario";
      exit;
    } else {
      ftp_pasv($this->id, true);
      return $this->id;
    }
  }

  function listar()
  {
    $contenido = ftp_nlist($this->id, $this->directorio);
    return $contenido;
  }

  function getftp($localfile, $ftpfile, $modo = FTP_BINARY)
  {
    if (ftp_fget($this->id, $localfile, $ftpfile, $modo, 0))
      return true;
    else
      return false;
  }

  function cerrar()
  {
    if ($this->id)
      return ftp_close($this->id);
  }
}

?>
