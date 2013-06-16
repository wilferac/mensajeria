<?
include("conexiongene.php");

?>
<html>
<head>
<title>Generador de Clases</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<div align="left">
  <p><strong><font size="+2">Generador de Clases 0.4</font></strong></p>
 
  <form name="form1" method="post" action="">
  <table width="335" border="1" cellpadding="0" cellspacing="0" bordercolor="#000000">
      <tr> 
        <td colspan="2">Seleccione la Tabla a generar</td>
      </tr>
      <tr> 
        <td width="53">Tabla:</td>
        <td width="276"><select name="tabla"> 
		<?

		$respuesta=$conn->ejecutar("show tables");

		while($opciones=$conn->siguiente($respuesta)){		
		echo "<option value=\"".$opciones[0]."\">".$opciones[0]."</option>";
		}
		?>
     </select>   
         </td>
      </tr>
      <tr align="center"> 
        <td colspan="2"> 
          <input name="generar" type="submit" id="generar" value="Generar">
        </td>
      </tr>
    </table> 
  </form>
  <? 
	if ( isset($_POST["generar"]) ){		
		//extraer los campos de la tabla
		$tabla=$_POST["tabla"];
		$res = $conn->Ejecutar("select * from $tabla");
		$cant = mysql_num_fields($res);
		for ($j = 0 ; $j < $cant; $j++) {
			$campos[] = mysql_field_name($res, $j);
		}
		echo "require(\"conexion/conexion.php\");<br><br>";
		echo "class $tabla{<blockquote>";
		foreach($campos as $campo)
			echo "var \$$campo;<br>";
		echo "//Variables de Control<br>";
		echo "var \$stmt; //Cursor para las consultas<br>";
		echo "//Funciones<br>";
		//CARGAR
		echo "function cargar(){<blockquote>";
		echo "if (isset(\$_GET[\"codigo\"]) ){//se cargan las variables de la base de datos<blockquote>";
		echo " \$this->buscar(\$_GET[\"codigo\"]);<br>";
		echo "</blockquote>}else{<blockquote>";
		echo "//Cargar las variables de la forma<br>";
		foreach($campos as $campo)
			echo "\$this->$campo= isset(\$_POST[\"$campo\"])?\$_POST[\"$campo\"]:'';<br>";
		echo "</blockquote>}<br>";
		echo "</blockquote>}<br>";
		//AGREGAR
		echo "function agregar(){<blockquote>";
		echo "global \$conn;<br>";
		echo "//calcular el codigo<br>";
		echo "\$SQL = \"select max($campos[0]) from $tabla\";<br>";
		echo "if ( \$conn->ejecutar(\$SQL) && (\$row=\$conn->siguiente(NULL)) )";
		echo "<blockquote>\$this->$campos[0] = \$row[0]+1;</blockquote>";
		echo "else<br>";
		echo "\$this->$campos[0] = 1;<br>";
		echo "\$SQL = sprintf(\"INSERT INTO $tabla (";
		$cadena="";
		foreach($campos as $campo)
			$cadena.= "$campo,";
		$cadena=substr($cadena,0,strlen($cadena)-1);
		echo $cadena;
		echo ")<br>";
		echo "values(";
		$cadena="";
		foreach($campos as $campo)
			$cadena.= "'%s',";
		$cadena=substr($cadena,0,strlen($cadena)-1);
		echo $cadena;
		echo ")<br>\"";
		foreach($campos as $campo)
			echo ",\$this->$campo";
		echo ");<br>";
		echo "if (\$conn->ejecutar(\$SQL))<br>";
		echo "return true;<br>";
		echo "else<br>";
		echo "\$this->$campos[0]=\"\";<br>";
		echo "</blockquote>}<br><br>";
		//MODIFICAR
		echo "function modificar(){<blockquote>";
		echo "global \$conn;<br>";
		echo "\$SQL = sprintf(\"UPDATE $tabla SET ";
		$cadena="";
		foreach($campos as $campo)
			$cadena.="$campo='%s',";
		$cadena=substr($cadena,0,strlen($cadena)-1);
		echo $cadena;
		echo " WHERE $campos[0]=%d \"<br>";
		foreach($campos as $campo)
			echo ",\$this->$campo";	
		echo ",\$this->$campos[0]);<br>";
		echo "if (\$conn->ejecutar(\$SQL))<br>";
		echo "return true;<br>";
		echo "</blockquote>}<br><br>";
		//CONSULTAR
		echo "//Realizar una consulta <br>";
		echo "function consultar(\$cond = \"\",\$ord = \"\",\$lim=\"\"){<blockquote>";
		echo "global \$conn;<br>";
		echo "\$SQL = \"SELECT * FROM $tabla\";<br>";
		echo "if (!empty(\$cond))<br>";
		echo "\$SQL.= \" WHERE \$cond\";<br>";
		echo "if (!empty(\$ord))<br>";
		echo "\$SQL.= \" ORDER BY \$ord\";<br>";
		echo "if (!empty(\$lim))<br>";
		echo "\$SQL.= \" LIMIT \$lim\";<br>";
		echo "\$this->stmt = \$conn->ejecutar(\$SQL);<br>";
		echo "return \$this->stmt;<br>";
		echo "</blockquote>}<br><br>";
		//BUSCAR
		echo "function buscar(\$cod){<blockquote>";
		echo "global \$conn;<br>";
		echo "if (\$this->consultar(\"$campos[0]='\$cod'\"))<br>";
		echo "return \$this->siguiente();<br>";
		echo "return false;<br>";
		echo "</blockquote>}<br><br>";
		//SIGUIENTE
		echo "function siguiente(){<blockquote>";
		echo "global \$conn;<br>";
		echo "if (\$row = \$conn->Siguiente(\$this->stmt)){<blockquote> //Cargar los datos de la bd<br>";
		foreach ($campos as $campo)
			echo "\$this->$campo = \$row[\"$campo\"];<br>";
		echo "return \$row;<br>";
		echo "</blockquote>}<br>";
		echo "return false;<br>";
		echo "</blockquote>}<br>";
		//ELIMINAR
		echo "function eliminar(){<blockquote>";
		echo "global \$conn;<br>";
		echo "if (\$this->$campos[0]){<blockquote>";
		echo "\$SQL = sprintf(\"DELETE FROM $tabla WHERE $campos[0]='%s'\",\$this->$campos[0]);<br>";
		echo "return \$conn->ejecutar(\$SQL);<br>";
		echo "</blockquote>}<br>";
		echo "</blockquote>}<br><br>";

		echo "</blockquote>}//fin de la clase $tabla";	
	
	}
?>

</div>
</body>
</html>
