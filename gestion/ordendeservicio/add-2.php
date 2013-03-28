<? 
 include("../clases/clases.php");

if (isset($_POST['registrar']))
{

$comandas = new comandas();
$comandasplatos = new comandas_platos();
$adicionales = new adicionales();
$preparadata = new preparaciondatos();
$conex = new conexion();



$qtrans = "SET AUTOCOMMIT=0;";
$sac = $conex -> ejecutar($qtrans); 

$qtrans = "BEGIN;";
$sac = $conex -> ejecutar($qtrans);


$comandas->serial = $_POST["serial"];
$comandas->fecha = $_POST["fecha"];
$comandas->mesero = $_POST["mesero"];
$comandas->mesa = $_POST["mesa"];
$comandas->habitacion = $_POST["habitacion"];
$comandas->pax = $_POST["pax"];

	//if ( $comandas->agregar() )
	//{	
		$dataoutplatos=array();
		
		$clavesnovalidas = array ("serial","fecha","mesero","mesa","habitacion","pax","unidaddemedida","registrar");
		$clavesvalidas = array ("cantidad_plato", "plato");
		$itemexcluido = array ("cantidad_producto","unidaddemedidas","cantidadadicional","producto");
			
		$preparadata->datain = $_POST;
		//print_r ($preparadata->datain);
		//echo "<br><br><br>";
		$preparadata->modulo = 2; // segun los elementos de $clavesvalidas
		$preparadata->itemcod = "plato";
		
		$preparadata->clavesnovalidas = $clavesnovalidas;
		$preparadata->clavesvalidas = $clavesvalidas;
		$preparadata->itemexcluido = $itemexcluido;
		
		$preparadata->preparadata();
		
		$dataoutplatos=$preparadata->dataout;
		print_r($dataoutplatos);
		echo "<br><br><br>";
		/*********************************PARA PRODUCTOS ADICIONALES*************************************************************/
		$dataoutadd=array();
		$clavesnovalidas = array ("serial","fecha","mesero","mesa","habitacion","pax","unidaddemedida","registrar");
		$clavesvalidas = array ("unidaddemedidas", "cantidadadicional","producto");
		$itemexcluido = array ("cantidad_plato", "plato","cantidad_producto");
		
		$preparadata->datain = $_POST;
		$preparadata->modulo = 3; // segun los elementos de $clavesvalidas
		$preparadata->itemcod = "producto";
		//print_r ($preparadata->datain);
		//echo "<br><br><br>";

		$preparadata->clavesnovalidas = $clavesnovalidas;
		$preparadata->clavesvalidas = $clavesvalidas;
		$preparadata->itemexcluido = $itemexcluido;
		
		$preparadata->preparadata();
		
		$dataoutadd=$preparadata->dataout;
		print_r($dataoutadd); 
		
		
		
		
	
	//} //  if comandas agregar


/*


if ( $platos->agregar() )
{
$platosid=$platos->id;
$detalles=array();
$i=1;
$numdetalles=4;
	foreach($_POST as $indice=>$valor)
	{ //echo $indice." | ".$valor."<br>";
		if ($indice!="nombre" && $indice!="tiposplatos" && $indice!="cantidad_personas" && $indice!="preparacion" && $indice!="unidaddemedida" && $indice!="registrar")
			{
			$offset1 = substr($indice,-1);
			$offset2 = substr($indice,-2);
			
			if  ( is_numeric($offset2) )
				$indicenew = substr_replace($indice,"",strlen($indice)-2);
			else
				$indicenew = substr_replace($indice,"",strlen($indice)-1);
			
				// echo $indicenew."<br>";
			if ($indicenew=="producto")
				$valor = substr($valor,0,strpos($valor,"-"));
			$detalles[$i][$indicenew]=$valor;	
			$i++;
			}
	}  
	
	 $detallefinal = array ();
	$k=1;
	for ($j=1;$j<$i;$j++)
	{
		if ( key($detalles[$j]) =="cantidad_producto")
		
			$detallefinal[$k]["cantidad_producto"] = $detalles[$j]["cantidad_producto"];
			
		elseif ( key($detalles[$j]) == "medida")
		
			$detallefinal[$k]["medida"] = $detalles[$j]["medida"];
			
		elseif ( key($detalles[$j]) == "unidaddemedidas")
		
			$detallefinal[$k]["unidaddemedidas"] = $detalles[$j]["unidaddemedidas"];
			
		elseif ( key($detalles[$j]) == "producto")
		
			$detallefinal[$k]["producto"] = $detalles[$j]["producto"];
			
			if ( ( $j%4 ) == 0 )
				$k++;
				
	}
	
foreach($detallefinal as $i => $val)
	{
	 $detallesplatos->platos_id = $platosid;
	 $detallesplatos->unidaddemedidas_id = $detallefinal[$i]["unidaddemedidas"];
	 $detallesplatos->productos_id = $detallefinal[$i]["producto"];
	 $detallesplatos->cantidadproducto = $detallefinal[$i]["cantidad_producto"];
	 
	 if ( $detallesplatos->agregar() )
	 	{
	 		?>
				<script language="javascript" type="text/javascript">
				var mensaje="Registro  Exitoso";
				window.location.href='platos.php?mensaje='+mensaje; 
				</script>
			<?
	 	}
		else 
			{
			?>
				<script language="javascript" type="text/javascript">
				var mensaje="Registro No Exitoso";
				window.location.href='platos.php?mensaje='+mensaje; 
				</script>
			<?
			}
	}

	
print_r($detallefinal);
echo "<br><br><br>";echo "<br><br><br>";
print_r($_POST);


}
else {
?>
<script language="javascript" type="text/javascript">
var mensaje="Registro NO Exitoso";
window.location.href='platos.php?mensaje='+mensaje;
</script>
<?

}

exit; */
//print_r($_POST);
}


/*****************************************************CARGA *******************/
$tiposplatos=new tiposplatos();
  
   $res=$tiposplatos->consultar();
   if ( mysql_num_rows($res)>0 )
   {
   $optionTP="";
    while ($filas=mysql_fetch_assoc($res))
	 { 
	 $optionTP.="<option value='$filas[id]'>".ucfirst($filas['nombre'])."</option>";
	 }
   }



$unidaddemedidas=new unidaddemedidas();
  
   $resUM=$unidaddemedidas->consultar("","nombre ASC");
   if ( mysql_num_rows($res)>0 )
   {
   $optionUM="";
    while ($filas=mysql_fetch_assoc($resUM))
	 { 
	 $optionUM.="<option value='$filas[id]'>".ucfirst($filas['nombre'])."</option>";
	 }
   }

$tiposplatos=new tiposplatos("","nombre ASC");
  
   $res=$tiposplatos->consultar();
   if ( mysql_num_rows($res)>0 )
   {
   $optionTP="";
    while ($filas=mysql_fetch_assoc($res))
	 { 
	 $optionTP.="<option value='$filas[id]'>".ucfirst($filas['nombre'])."</option>";
	 }
   }

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Comandas</title>
        <link rel="stylesheet" type="text/css" media="screen" href="../estilos/screen.css" />

		<style type="text/css" title="currentStyle">
			@import "../media/css/demo_page.css";
			@import "../media/css/demo_table.css";
		</style>
        	
			<style type="text/css">
			#formulario { width: 800px; }
			#formulario label { width: 250px; }
			#formulario label.error, #formulario input.submit { margin-left: 253px; }
		    </style>

		<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
      		<script type="text/javascript" language="javascript" src="../js/jquery.validate.js"></script> 
      		<!-- <script type="text/javascript" src="../clases/jquery_002.js"></script> -->
		<script type="text/javascript" src="../clases/jquery.js"></script>
		<script type="text/javascript" src="../clases/jquery_003.js"></script>
		<link rel="stylesheet" type="text/css" href="../clases/jquery.css">

		<script type="text/javascript">
		$.validator.setDefaults({
		submitHandler: function() { formulario.submit();
		 }
		});

		$().ready(function() {
		// validate the comment form when it is submitted
		$("#formulario").validate();

		// validate signup form on keyup and submit
		});
		</script>

<script type="text/javascript">
$().ready(function() {
	
	$("#plato").autocomplete("searchplatos.php", {
		minChars: 0, max:200, width: 350
	});
	
	
	$("#producto").autocomplete("searchproductos.php", {
		minChars: 0, max:200, width: 350
	});
});

</script>
<script type="text/javascript">
<!--
var num=0;
function crearadd(obj) {
	
	var sAux="";
	var frm = document.getElementById("fiel2");
	for (i=0;i<frm.elements.length;i++)
	{
	//if (frm.elements[i].name=="producto")
		sAux = frm.elements[i].value;
		if (sAux==document.getElementById("producto").value)
		{
		alert ("Este producto ya fue agregado");
		return;
		}
	}
if (document.getElementById("producto").value=="" || document.getElementById("cantidadadd").value=="")
{
 alert ("Producto o cantidad está vacio");
 return;
}
else{

 document.getElementById('titulodetalles2').style.visibility="visible";
 
  num++;
  fi = document.getElementById('fiel2'); 
  contenedor = document.createElement('div');
  contenedor.id = 'div'+num; 
  fi.appendChild(contenedor); 

  objSelect=document.getElementById("unidaddemedida");
  indice=objSelect.selectedIndex;
  ele = document.createElement('input'); 
  ele.type = 'text';
  ele.name = 'cantidad_producto'+num; 
  ele.width=20;
  ele.value=document.getElementById("cantidadadd").value+" "+document.getElementById("unidaddemedida").options[indice].text;
  ele.size = 20;
  ele.readOnly=true;
  contenedor.appendChild(ele); 
  
  ele3 = document.createElement('input'); 
  ele3.type = 'hidden'; 
  ele3.name = 'unidaddemedidas'+num;
  ele3.value=document.getElementById("unidaddemedida").options[indice].value;
  contenedor.appendChild(ele3);
  
  ele4 = document.createElement('input'); 
  ele4.type = 'hidden'; 
  ele4.name = 'cantidadadicional'+num;
  ele4.value=document.getElementById("cantidadadd").value;
  contenedor.appendChild(ele4);
  
  ele2 = document.createElement('input'); 
  ele2.type = 'text'; 
  ele2.name = 'producto'+num;
  ele2.value=document.getElementById("producto").value;
  ele2.size = 50;
  ele2.readOnly=true;
  contenedor.appendChild(ele2); 
  
 
  
  
  ele = document.createElement('input'); 
  ele.type = 'button';
  ele.value = 'Borrar'; 
  ele.name = 'div'+num; 
  ele.onclick = function () {borraradd(this.name)} 
  contenedor.appendChild(ele); 
  
   document.getElementById("producto").value="";
    document.getElementById("producto").focus();
 }
} 


 var numpla=0;
 function crearplato(obj) {
	
	var sAux="";
	var frm = document.getElementById("fiel");
	for (i=0;i<frm.elements.length;i++)
	{
	//if (frm.elements[i].name=="producto")
		sAux = frm.elements[i].value;
		if (sAux==document.getElementById("plato").value)
		{
		alert ("Este plato ya fue agregado");
		return;
		}
	}
if (document.getElementById("plato").value=="" || document.getElementById("cantidad_plato").value=="")
{
 alert ("Plato o cantidad está vacio");
 return;
}
else{

 document.getElementById('titulodetalles').style.visibility="visible";
 
  numpla++;
  fi = document.getElementById('fiel'); 
  contenedor = document.createElement('div');
  contenedor.id = 'div'+numpla; 
  fi.appendChild(contenedor); 

  ele = document.createElement('input'); 
  ele.type = 'text';
  ele.name = 'cantidad_plato'+numpla; 
  ele.width=10;
  ele.value=document.getElementById("cantidad_plato").value;
  ele.size = 10;
  ele.readOnly=true;
  contenedor.appendChild(ele); 
  
  ele3 = document.createElement('input'); 
  ele3.type = 'text'; 
  ele3.name = 'plato'+numpla;
  ele3.value=document.getElementById("plato").value;
  ele3.width=20;
  ele3.size = 20;
  ele3.readOnly=true;  
  contenedor.appendChild(ele3);
  
  
  ele = document.createElement('input'); 
  ele.type = 'button';
  ele.value = 'Borrar'; 
  ele.name = 'div'+numpla; 
  ele.onclick = function () {borrarplato(this.name)} 
  contenedor.appendChild(ele); 
  
   document.getElementById("plato").value="";
    document.getElementById("plato").focus();
 }
 
 
}
function borrarplato(obj) {
  fi = document.getElementById('fiel'); 
  fi.removeChild(document.getElementById(obj)); 

   if ( document.getElementById("fiel").elements.length == 0)
    document.getElementById('titulodetalles').style.visibility="hidden";
 
}

function borraradd(obj) {
  fi2 = document.getElementById('fiel2'); 
  fi2.removeChild(document.getElementById(obj)); 

   if ( document.getElementById("fiel2").elements.length == 0)
    document.getElementById('titulodetalles2').style.visibility="hidden";
 
}

function validar()
{
var frm = document.getElementById("fiel");
var frm2 = document.getElementById("fiel2");
var $seguro = "¿Segur@ que desea ingresar esta comanda?";	
	if ( frm.elements.length == 0)
	{
		if ( frm2.elements.length == 0)
		{
		alert ("Debe agregar al menos un plato o un producto adicional");
		return false;
		}
		else
		return confirm ($seguro);
	}
	else
		return confirm ($seguro);
}

--> 
</script>
</head>
	<body id="dt_example">
		<div id="container">
			<div class="full_width big">
				<p class="navegacion"><a href="../redireccionador.php">Página principal</a>&gt;<a href="../gestiondelsistema.php">Gestión del sistema</a>&gt;<a href="platos.php">Comandas</a></p>
				Comandas 
                </div>
             <p>&nbsp;</p>

        <div id="dynamic">
        <form class="formulario" id="formulario" method="post" action="">
	<fieldset>

       	<p>
			<label for="serial">Serial (Obligatorio)</label>
			<input id="serial" name="serial" type="number"  class="required" maxlength="10"/>
       </p>
       	<p>
       	  <label for="fecha">Fecha (Obligatorio)</label>
			<input id="fecha" name="fecha"  class="required" maxlength="10"/></p>
       	<p>
            <label for="mesero">Mesero (Obligatorio)</label>
			<input id="mesero" name="mesero" class="required" maxlength="25"/></p>
            
       	<p><label for="mesa">Mesa (Obligatorio)</label>
			<input id="mesa" name="mesa" class="required" maxlength="3"/></p>
       	<p>
             <label for="habitacion">Habitación (Opcional)</label>
			<input id="habitacion" name="habitacion" maxlength="4"/>
            </p>
       	<p>
        <label for="pax">Pax (Opcional)</label>
			<input id="pax" name="pax" maxlength="3"/>
        </p>


      <p align="center"><strong>PLATOS</strong></p>
        <p>
			<label for="cantidad_plato">Cantidad (Obligatorio)</label>
			<input  id="cantidad_plato" type="number"  maxlength="2">

       </p>      
        <p>
          <label for="plato">Plato (Obligatorio)</label>
			<input  id="plato" type="text" size="20" />
   		    <input value="Agregar" type="button" onClick="crearplato(this)">
            <input name="limpiar" type="button" id="limpiar" onClick="document.getElementById('plato').value=''" value="Limpiar">
       </p>
      <p align="center"><strong>ADICIONALES</strong> </p>
        <p>         
        
         <label for="cantidadadd">Cantidad adicional (Obligatorio)</label>
			<input  id="cantidadadd" type="text" size="20"  value="1"/>
      </p>
  <p>
			<label for="unidaddemedida">Unidad de medida </label>
			<select id="unidaddemedida" name="unidaddemedida">
            	<?=$optionUM?>
            </select>
	</p>
  <p> <label for="producto">Producto (Obligatorio)</label>
			<input  id="producto" type="text" size="45" />
   		    <input value="Agregar" type="button" onClick="crearadd(this)"> 
   		    <input name="limpiar2" type="button" id="limpiar2" onClick="document.getElementById('producto').value=''" value="Limpiar">
  </p>
  <p>&nbsp;</p>
         

         <p><input class="submit" type="submit"  id="registrar" name="registrar" value="Registrar" onClick="return validar();"/></p>
      </fieldset>
      
      <div class="spacer"></div>
		
		<div class="full_width big" >
		  <p id="titulodetalles" style="visibility:hidden">Platos</p>
		</div>
		
		<div align="center">
			<fieldset id="fiel"  style="border:none">
                       
            </fieldset>
		</div>
        
          
        <div class="spacer"></div>
		
		<div class="full_width big" >
		  <p id="titulodetalles2" style="visibility:hidden">Adicionales</p>
		</div>
		
		<div align="center">
			<fieldset id="fiel2"  style="border:none">
            </fieldset>
		</div>
      </form>
      </div>
  </div>
	</body>
</html>
