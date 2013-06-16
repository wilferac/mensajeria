<? 
//session_start();
include ("../../param/param.php");
include ("../../clases/clases.php");

if (isset($_POST["destino"]))
{	
	$flagbloque = false;
	
	if ($_POST["pistoleo"]=="bloque")
	{
		$flagbloque = true;
		$desde = $_POST["desde"];
		$hasta = $_POST["hasta"];
	}
	if ($_POST["destino"]=="mensajero1")
	{
			$manifiestos = new manifiesto();
		$datosmensajero = $_POST['mensajero'];
			$plazo = $_POST["plazo"];

	
	if ( !empty($datosmensajero) )
		{	
			$cedmensajero = substr ($datosmensajero,0,strrpos($datosmensajero,"-"));
			$manifiestos->tercero_idmensajero_recibe = $cedmensajero;
		}
	else		
		$manifiestos->tercero_idmensajero_recibe = null;

	 $manifiestos->tercero_idmensajero_entrega = null;
	 $manifiestos->plazo_entrega_manifiesto = $plazo; 
	
	   $stringset1 = "tercero_idmensajero_recibe=$cedmensajero";
		$stringset2 = "plazo_entrega_manifiesto=$plazo";

		$manifiestos->modificar2($stringset1,$_GET["id"]);
		$manifiestos->modificar2($stringset2,$_GET["id"]);
		
		
	}

		$estadomovimiento = 5; //Alistado	
		//	$estadomovimiento = 1; //Creacion
		
	$idusuario = $_SESSION['datosinicio']['idtercero'];

//	Array ( [destino] => on [ciudad] => 1002 Cali [pistoleo] => pistoleo [desde] => [hasta] => [mensajero] => [plazo] => [idguia] => [observaciones] => [idsucursal] => 1 [guia1] => 19000005 )

	//print_r ($_POST);
	$conex = new conexion();
	$qtrans = "SET AUTOCOMMIT=0;";
	$sac = $conex -> ejecutar($qtrans); 
	$qtrans = "BEGIN;";
	$sac = $conex->ejecutar($qtrans);



	$movimientos = new movimiento();
	$guias = new guia();
	$operaciones = new operacion();
	
	$manifiestos->idmanifiesto = $_GET["id"];
	
	$fecha = $operaciones->obtenerFechayHora("fecha");
	$hora = $operaciones->obtenerFechayHora("hora");
	$datosmensajero = $_POST['mensajero'];

//*************** Data para tabla manifiesto*********

/*
	$manifiestos->generarNumeroManifiesto();
	$plazo = $_POST["plazo"];
	
	if ( !empty($datosmensajero) )
		{	
			$cedmensajero = substr ($datosmensajero,0,strrpos($datosmensajero,"-"));
			$manifiestos->tercero_idmensajero_recibe = $cedmensajero;
		}
	else		
		$manifiestos->tercero_idmensajero_recibe = null;

	 $manifiestos->tercero_idmensajero_entrega = null;
	 $manifiestos->sucursal_idsucursal = $_POST['idsucursal'];
	 $manifiestos->plazo_entrega_manifiesto = $plazo;  

	 $rmani = $manifiestos->agregar();
	*/

	$guiasarray = array();
	$mensguiasnoenc = "";
	$flagmodiguia = true;

	$guiasDigitadas = array();
	$guiasNoAsoc =array();
	$guiasSiAsocNoDig = array();
	
	$cond = "manifiesto_idmanifiesto=$manifiestos->idmanifiesto";
	$res = $guias->consultar($cond);
	while ( $fil = mysql_fetch_assoc($res) )
	{
		$guiasRegistradas[$fil["idguia"]] = $fil["numero_guia"];
	}
	/*echo "Guias registradas";
	print_r($guiasRegistradas);*/

	if (!$flagbloque)
	{
		foreach ( $_POST as $clave => $valor  )
	 {
		$clave = substr ($clave,0,4);
		if ( $clave == "guia"  )
				$guiasDigitadas[$valor]=$valor;
	 }
	}
	else
	{
	  for($k=$desde;$k<=$hasta;$k++ )
		$guiasDigitadas[$k]=$k;
	}
	
	/*echo "DIGITADAS<br>";
	print_r($guiasDigitadas);*/
		$guiasNoAsoc = array_diff($guiasDigitadas,	$guiasRegistradas);	
		$guiasSiAsocNoDig = array_diff($guiasRegistradas,  $guiasDigitadas);
	/*echo "No Asoc";
	print_r($guiasNoAsoc);

	echo "<br><br>Si Asoc No Dig";
	print_r($guiasSiAsocNoDig);*/

	$rmovMani=false;
	if ( empty($guiasSiAsocNoDig) )
	{

	foreach ($guiasRegistradas as $clave => $valor) 
	{
//***************Data para movimientos de Guias**************
		
	$movimientos->guia_idguia = $clave;
	$movimientos->estado_idestado = $estadomovimiento;
	$movimientos->tercero_idusuario = $idusuario;
	
	$movimientos->fecha_movimiento = $fecha;
	$movimientos->hora_movimiento = $hora;	
	$movimientos->recogida_idrecogida == null;
	$movimientos->orden_servicio_idorden_servicio = null;
	$movimientos->manifiesto_idmanifiesto = $manifiestos->idmanifiesto; 
	$movimientos->sucursal_idsucursal = $_POST['idsucursal'];
	$movimientos->asignacion_guias_idasignacion_guias = null; //ojo se debe updatear despues para asociarlo
	$movimientos->tarifa_idtarifa = null; //ojo se debe updatear despues para asociarlo
	

	 $rmovMani = $movimientos->agregar();
//**************************************************	
	}
		
	}
	/*else
	{
		echo "Las siguientes guias no fueron digitadas:";
		print_r($guiasSiAsocNoDig);
	}
*/

	if ( empty($guiasNoAsoc) )
	{
		foreach($guiasDigitadas as $clave => $valor)
			{
				$cond = "numero_guia=$valor";				
				$r = $guias->consultar($cond);
				$f = mysql_fetch_assoc($r);
	//***************Data para movimientos de Guias**************
		
	$movimientos->guia_idguia =  $f["idguia"];;
	$movimientos->estado_idestado = $estadomovimiento;
	$movimientos->tercero_idusuario = $idusuario;
	
	$movimientos->fecha_movimiento = $fecha;
	$movimientos->hora_movimiento = $hora;	
	$movimientos->recogida_idrecogida == null;
	$movimientos->orden_servicio_idorden_servicio = null;
	$movimientos->manifiesto_idmanifiesto = $manifiestos->idmanifiesto; 
	$movimientos->sucursal_idsucursal = $_POST['idsucursal'];
	$movimientos->asignacion_guias_idasignacion_guias = null; //ojo se debe updatear despues para asociarlo
	$movimientos->tarifa_idtarifa = null; //ojo se debe updatear despues para asociarlo
	

	 $rmovMani = $movimientos->agregar();
//**************************************************
	
			}
		}

	if ($rmovMani===true)
	{
			$qtrans = "COMMIT";
			$sac = $conex -> ejecutar($qtrans);
	 ?>	
	<script language="javascript" type="text/javascript">
		var mensaje="Registro Exitoso";
	  window.location.href='consultadetalle.php?id=<?=$manifiestos->idmanifiesto?>';
	</script>
	
<?
	}
	else
		?>
		<script language="javascript" type="text/javascript">
		var mensaje="Registro NO Exitoso";
	  window.location.href='consultadetalle.php?id=<?=$manifiestos->idmanifiesto?>';
		</script>
		<?
}
	$idusuario = $_SESSION['datosinicio']['idtercero'];
	$nombre_ciudad = $_SESSION['datosinicio']['nombre_ciudad'];
	$idsucursal = $_SESSION['datosinicio']['sucursal_idsucursal'];
	$nombresucursal = $_SESSION['datosinicio']["nombre_sucursal"];
	$idsucursal = $_SESSION['datosinicio']["sucursal_idsucursal"];
	$idciudad = $_SESSION['datosinicio']["ciudad_idciudad"];
/*******************************************************************************/
		
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Manifiesto</title>
        		<style type="text/css" title="currentStyle">
			@import "../../media/css/demo_page.css";
			@import "../../media/css/demo_table.css";
			@import "../../media/css/jquery.css";
		</style>
		<script type="text/javascript" language="javascript" src="../../js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../../js/jquery.dataTables.js"></script>
		<script type="text/javascript" language="javascript" src="../../js/jquery.validate.js"></script> 
		<script type="text/javascript" src="../../js/jquery_003.js"></script>

		<script type="text/javascript">
			$().ready(function() 
			{
				$("#ciudad").autocomplete("../ciudad/searchciudad.php", {
				minChars: 0, max:200, width: 350});
			});

			$().ready(function() 
			{
				$("#mensajero").autocomplete("../tercero/searchmensajero.php?tt=5", {
				minChars: 0, max:50, width: 350});
			});

		</script>
        	
			<style type="text/css">
			#formulario { width: 800px; }
			#formulario label { width: 250px; }
			#formulario label.error, #formulario input.submit { margin-left: 253px; }
		    </style>

		<script type="text/javascript">
		$.validator.setDefaults({
		submitHandler: function() { formulario.submit();
		 }
		});

		$().ready(function() {
		$("#formulario").validate();

		});
		</script>

<!******************************************************
	Establece Visible a Input Ciudad o Mensajero
******************************************************>

<script type="text/javascript">
function seleciumens(nombre)
{
		var i=0;
	
	
	if (nombre == "ciudad")
		{
			document.getElementById("ciudad").style.visibility = "visible";		
			document.getElementById("mensajero").style.visibility = "hidden"

			document.getElementById("tagpistoleo").style.visibility = "visible";
			
			for (i=0;i<document.formulario.pistoleo.length;i++)
			{
			document.formulario.pistoleo[i].style.visibility = "visible";		
			}	

			document.getElementById("tagbloque").style.visibility = "visible";
			document.getElementById("tagdesde").style.visibility = "visible";
			document.getElementById("taghasta").style.visibility = "visible";
			document.getElementById("desde").style.visibility = "visible";
			document.getElementById("hasta").style.visibility = "visible";

			document.getElementById("tagplazo").style.visibility = "hidden";
			document.getElementById("plazo").style.visibility = "hidden";
			if (document.formulario.pistoleo[0].checked==true)
			{
		document.getElementById("tagdesde").style.visibility = "hidden";
		document.getElementById("taghasta").style.visibility = "hidden";
		document.getElementById("desde").style.visibility = "hidden";
		document.getElementById("hasta").style.visibility = "hidden";
			}
			
		}	
	else if(nombre == "mensajero")
		{
	
			document.getElementById("mensajero").style.visibility = "visible";
			document.getElementById("ciudad").style.visibility = "hidden";

			document.getElementById("tagpistoleo").style.visibility = "hidden";
			document.getElementById("pistoleo").style.visibility = "hidden";

			
			for (i=0;i<document.formulario.pistoleo.length;i++)
			{
			document.formulario.pistoleo[i].style.visibility = "hidden";		
			}			
			
			document.getElementById("tagbloque").style.visibility = "hidden";
			document.getElementById("tagdesde").style.visibility = "hidden";
			document.getElementById("taghasta").style.visibility = "hidden";
			document.getElementById("desde").style.visibility = "hidden";
			document.getElementById("hasta").style.visibility = "hidden";

			
			document.getElementById("tagplazo").style.visibility = "visible";
			document.getElementById("plazo").style.visibility = "visible";
		}
	if(nombre == "bloque")
	{
		document.getElementById("tagdesde").style.visibility = "visible";
		document.getElementById("taghasta").style.visibility = "visible";
		document.getElementById("desde").style.visibility = "visible";
		document.getElementById("hasta").style.visibility = "visible";
	} 
	else if(nombre == "pistoleo")
	{
		document.getElementById("tagdesde").style.visibility = "hidden";
		document.getElementById("taghasta").style.visibility = "hidden";	
		document.getElementById("desde").style.visibility = "hidden";
		document.getElementById("hasta").style.visibility = "hidden";	
		document.getElementById("idguia").focus();	
	}
}
</script>
<!*************************************************>

<script type="text/javascript">

document.onkeypress=function(e){
var esIE=(document.all);
tecla=(esIE) ? event.keyCode : e.which;
if (tecla==13)
{ guiaadd(); 
}
return (tecla);
};



var num=0;
function guiaadd() {

	var fs = document.getElementById("fiel");
	var inps = fs.getElementsByTagName("INPUT");
	
	var sAux="";
	var frm = document.getElementById("fiel");
	for (i=0;i<inps.length;i++)
	{	
		sAux = inps[i].value		
		if (sAux==document.getElementById("idguia").value || document.getElementById("idguia").value=="")
		{
		document.getElementById("idguia").value="";
		return false;
		}
	}


 document.getElementById('titulodetalles').style.visibility="visible";
 
  num++;
  fi = document.getElementById('fiel'); 
  contenedor = document.createElement('div');
  contenedor.id = 'div'+num; 
  fi.appendChild(contenedor); 

  objSelect=document.getElementById("idguia");
  indice=objSelect.selectedIndex;
  ele = document.createElement('input'); 
  ele.type = 'text';
  ele.name = 'guia'+num; 
  ele.width=20;
ele.value=document.getElementById("idguia").value;
  ele.size = 20;
  ele.readOnly=true;
  contenedor.appendChild(ele); 
  
  ele = document.createElement('input'); 
  ele.type = 'button';
  ele.value = 'Borrar'; 
  ele.name = 'div'+num; 
  ele.onclick = function () {borraradd(this.name)} 
  contenedor.appendChild(ele); 
  
   document.getElementById("idguia").value="";
   document.getElementById("idguia").focus();

}
function borraradd(obj) {
  fi2 = document.getElementById('fiel'); 
  fi2.removeChild(document.getElementById(obj)); 
   if ( fi2.getElementsByTagName("INPUT").length == 0)
    document.getElementById('titulodetalles').style.visibility="hidden";
 
}
function enviar()
{
	var fs = document.getElementById("fiel");
	var inps = fs.getElementsByTagName("INPUT");

	var ciudad = document.getElementById("ciudad").value;
	var mensajero = document.getElementById("mensajero").value;

	var plazo = document.getElementById("plazo").value;

	var msg ="";

		
	 if ( ciudad =="" && mensajero =="" )
		msg=msg+"Debe seleccionar una ciudad o un mensajero\n";
	else if(mensajero != "")
		if (plazo == "")
			msg=msg+"Debe Ingresar un plazo de entrega\n";
	
	if (document.formulario.pistoleo[1].checked)
	{
		if (document.getElementById("desde").value=="" || document.getElementById("hasta").value=="")
			{
			msg=msg+"Debe ingresar rango para carga en bloque\n";
			alert ("Error:\n"+msg);
			return false;
			}
		else if (document.getElementById("desde").value!="" && document.getElementById("hasta").value!="")
			{document.formulario.submit();
				return false;		
			}
		else 
		{
			msg=msg+"Debe ingresar rango para carga en bloque\n";
			alert ("Error:\n"+msg);
			return false;	
		}
		
	}

	if ( inps.length == 0)
	{
		msg=msg+"Debe agregar al menos una guia\n";
		//return false;
	}
	if (msg=="")
	 document.formulario.submit();
	else
		alert("Error:\n"+msg);
}

</script>

<!******************************************************-->
<script language="javascript">
  parent.frames[0].document.getElementById("a1").innerHTML = "";
 parent.frames[0].document.getElementById("a2").innerHTML = "";
 parent.frames[0].document.getElementById("a3").innerHTML = "";
 
 parent.frames[0].document.getElementById("s1").style.visibility = "hidden";
 parent.frames[0].document.getElementById("s2").style.visibility = "hidden";
 
 
 parent.frames[0].document.getElementById("a1").innerHTML = "Principal";
 parent.frames[0].document.getElementById("a1").href = "redireccionador.php";
 parent.frames[0].document.getElementById("s1").style.visibility = "visible";
 
 parent.frames[0].document.getElementById("a2").innerHTML = "Procesos";
 parent.frames[0].document.getElementById("a2").href = "procesos.php";
 parent.frames[0].document.getElementById("s2").style.visibility = "visible";
 
 parent.frames[0].document.getElementById("a3").innerHTML = "Ver Manifiestos";
 parent.frames[0].document.getElementById("a3").href = "gestion/manifiesto/consulta.php";
 
</script> 
</head>
	<body id="dt_example">
		<div id="container">
			<div class="full_width big">
				<p>&nbsp;</p>
                Manifiesto
                </div>
             <p>&nbsp;</p>
				<div>Ciudad:   <?=$nombre_ciudad?> </div>
				<div>Sucursal:   <?=$nombresucursal?> </div>
		<p>&nbsp;</p>
        <div id="dynamic">
        <form class="formulario" id="formulario" name='formulario' method="post" action="" >
	<fieldset>
		<table border=0 cellspacing=20>
		<tr><td>			
			<input checked type="radio" id="destino"  name="destino" onClick="seleciumens('ciudad')"/>Ciudad destino: 
			<input id="ciudad"  name="ciudad" value="<?=$idciudad." ".$nombre_ciudad?>" maxlength="45" style="visibility:visible"/>
			<br>
			<label id="tagpistoleo" name="tagpistoleo" style="visibility:visible">Pistoleo guia por guia: </label>
			<input checked id="pistoleo" name="pistoleo" type="radio" value="pistoleo"  onClick="seleciumens('pistoleo')">
			<br>
			<label id="tagbloque" name="tagbloque" style="visibility:visible">Por bloque: </label>
			<input id="pistoleo" name="pistoleo" type="radio" value="bloque"  onClick="seleciumens('bloque')">
			<br>

			<label id="tagdesde" name="tagdesde" style="visibility:hidden">Desde: </label>			
			<input id="desde" name="desde"   type="text" maxlength="45" size="35" style="visibility:hidden"/><br>
			<label id="taghasta" name="taghasta" style="visibility:hidden">Hasta: </label>			
			<input id="hasta" name="hasta"   type="text" maxlength="45" size="35" style="visibility:hidden"/>
		</td>

		<td>
			<input type="radio" id="destino" name="destino" value="mensajero1" onClick="seleciumens('mensajero')"/>Mensajero: 
			<input id="mensajero" name="mensajero"   value="" maxlength="45" size="35" style="visibility:hidden"/>
			<br>
			<label id="tagplazo" name="tagplazo" style="visibility:hidden">Plazo de entrega: </label><input size="30" id="plazo" name="plazo" type="text" style="visibility:hidden">
       </td>
		</tr>
		<tr><td align=right>
       	  <label for="idguia">Guia: </label>
			
		</td>
		<td><input id="idguia" name="idguia" maxlength="30"  /></td>
		</tr>
		<tr><td align=right>	
         <label for="observaciones">Observaciones: </label>
			
    	</td>
		<td><textarea id="observaciones" name="observaciones"></textarea></td>
		</tr>
  <tr><td>
			<input id="idsucursal" name="idsucursal" type="hidden" value="<?=$idsucursal?>" />
	        <input class="submit" type="button"  id="registrar" name="registrar" value="Registrar" onClick="enviar()" />
	</td>
	<td>&nbsp;</td>
	</tr>
</table>
      </fieldset>

      <div class="spacer"></div>
			<div class="full_width big" >
		  <p id="titulodetalles" style="visibility:hidden">Guias</p>
		</div>
		<div align="center">
			<fieldset id="fiel" name="fiel"  style="border:none">
            </fieldset>
		</div>
      </form>
      </div>
  </div>
	</body>
</html>
