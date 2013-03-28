<?
/*
 * hago cambios para que valide mejor los datos.
 * 
 */   
   
   

   include ("../../clases/clases.php");
   
   
   include "../../security/User.php";

   $objUser = unserialize($_SESSION['currentUser']);
   //$objUser = new User();
//        echo($objUser->getStatus());
   if ($objUser->getStatus() != 1)
   {
       //$objUser->show();
       $operacion->redireccionar('No Puede entrar', 'index.php');
       return;
   }
   



   $operaciones = new operacion();
//  Array ( [idtercero] => 2 [sucursal_idsucursal] => 5 [nombres_tercero] => innovate2 [apellidos_tercero] => group2 [usuario_tercero] => admin [ciudad_idciudad] => 17 [codigo_sucursal] => 4 [nombre_sucursal] => Vallarta2 [nombre_ciudad] => BARBOSA [] => 5 [] => 57 [] => 79 ) 

   $nombreciudaddestino = $_REQUEST['ciudaddestino'];
   $departamentodestino = $_REQUEST['departamentodestino'];
   $idciudaddestino = $_REQUEST['idciudad'];
   $idciudadorigen2 = $_REQUEST['idciudadorigen2'];


   $idciudadorigen = $_SESSION['datosinicio']['ciudad_idciudad'];

   if ($idciudadorigen2 == $idciudadorigen)
   {
       $idpaisdorigen = $_SESSION['datosinicio']['pais_ciudad'];
       $iddepartamentoorigen = $_SESSION['datosinicio']['departamento_iddepartamento'];
       $ciudadorigen = $_SESSION['datosinicio']['codigo_ciudad'];
       $idciudadorigen = $_SESSION['datosinicio']['ciudad_idciudad'];
   }
   else
   {
       $SQL = "SELECT * from ciudad WHERE idciudad = $idciudadorigen2";

       $res = $operaciones->consultar($SQL);
       $datosciudadorigen = mysql_fetch_assoc($res);

       $idpaisdorigen = $datosciudadorigen['pais_ciudad'];
       $iddepartamentoorigen = $datosciudadorigen['departamento_iddepartamento'];
       $ciudadorigen = $datosciudadorigen['codigo_ciudad'];
       $idciudadorigen = $datosciudadorigen['idciudad'];
       //echo "<br>Entro $idciudadorigen -  $idciudaddestino<br>";
   }

   $idTipoProducto = $operaciones->calcularTipoProducto($idpaisdorigen, $iddepartamentoorigen, $idciudadorigen, $nombreciudaddestino, $idciudaddestino);

   $tipoproducto = new tipo_producto();

   $cond = "idtipo_producto = $idTipoProducto";
   $res = $tipoproducto->consultar($cond);
   $fila = mysql_fetch_assoc($res);
   $nombre_tipo_producto = $fila ['nombre_tipo_producto'];


//aca muestra cosas que valido en las consultas
   echo "
	<script>
		document.getElementById('tipoproducto').value = '$nombre_tipo_producto';
		document.getElementById('idtipoproducto').value = '$idTipoProducto';
                    //muestro el boton para guardar temporal 

	</script>
	<input type='hidden'  id='idciudaddestino' name='idciudaddestino' value='$idciudaddestino'/>
";
   
   if (!$objUser->checkRol("Cliente") || $objUser->checkRol("Admin"))
   {
       echo("<script>document.getElementById('savetemp').style.visibility='visible';</script>");
   }
   
   
//muestro la parte de informacion del destinatario
   echo "<script>
    //ElementosClientesInvisibles();
    //ElementosDatosABuscarDestinatarioInvisibles();						
    //ElementosDestinatariosInvisibles();
// ELEMENTOS DEL CLIENTE VISIBLES	
// ElementosClientesVisibles(true,'$idtercero','$documento_tercero','$nombres_tercero','$apellidos_tercero','$direccion_tercero');
ElementosDatosABuscarDestinatarioVisibles();
// ELEMENTOS DEL DESTINATARIO VISIBLES
document.getElementById('datoArecordar').focus();
</script>
<input type='hidden' id='encCliente' name='encCliente' value='$encCliente'></input>";
?>