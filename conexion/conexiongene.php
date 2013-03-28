<?php
$conn=new conexion();
class conexion{
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
		$this->servidor = "localhost";
		$this->usuario = "root";
		$this->clave = "";
		$this->bd = "mensajeria";
		$this->conectar();

	}
	function conectar(){
	
			if($this->id)
			$this->cerrar();
		$this->id=mysql_connect($this->servidor,$this->usuario,$this->clave);
		$selDB=mysql_select_db($this->bd);
		if(!$this->id || !$selDB){
			echo "Error conectando a la base de datos.";
			exit();
			}
		else
			{
			return $this->id;
			}
	}
	function cerrar(){
		if ($this->id)
			return mysql_close($this->id);
	}
	function ejecutar($sql){
		if($this->id)
			//conectar();		
		if( $this->stmt=mysql_query($sql) ){
		  
			return $this->stmt;
		}else{
			echo mysql_error();
			echo "Error al Ejecutar la consulta: $sql";
			return false;
		}
	}

	function siguiente($stm){
	if($stm)
		return mysql_fetch_array($stm);
	else
		return mysql_fetch_array($this->stmt);
	return false;
	}
	function nrofilas($stmt=""){
		if($stmt)
			return mysql_num_rows($stmt);
		else
			return mysql_num_rows($this->stmt);
	}
}//fin de la clase conexión

//-----------------------------------------------------------------------------
///Funciones globales varias
//--------------------------------------------------------------------------------
function fecha_bd($fec){
	list($dia,$mes,$ano)=explode('-',$fec);
	if ($dia>0 && $mes>0 && $ano>0)
		return "$ano-$mes-$dia";
	else
		return "1900-01-01";
}
function bd_fecha($fec){
	list($ano,$mes,$dia)=explode('-',$fec);
	if ($dia==1 && $mes==1 && $ano==1900)
		return '';
	else
		return "$dia/$mes/$ano";
}
function num_bd($num){
	return str_replace(",",".",str_replace(".","",$num));
}
function bd_num($num){
	return number_format($num, 2, ",", ".");
}
function autenticar($mod){
	if (( !session_is_registered("usua_codigo")  ) && $_SERVER['PHP_SELF']!="/SICOPRO/index.php"){
		echo "<script> 
						alert('No se puede accesar a esta página sin registrarse en el sistema');
						parent.location.href  = 'index.php';
					</script>";
		exit;
	}else{
		$perm = new permisos();
		$perm->pordos("perm_usua_codigo=$_SESSION[usua_codigo]","perm_modu_codigo=$mod");
		if ( $perm->siguiente() ){
			return $perm->perm_nivel;	
		}else{
			die("<p>&nbsp;</p><p align=center><strong>El Usuario No Tiene Permisos para este Recurso</strong></p>");
		}				
	}
}

import_request_variables("mysql","_");
?>
