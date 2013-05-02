<?

   include("../../clases/clases.php");
   $q = strtolower($_GET["q"]);
   $items = array();
   $operaciones = new operacion();

   $qry = "SELECT ciudad.idciudad, ciudad.nombre_ciudad, nombre_departamento
FROM ciudad, departamento
WHERE ciudad.departamento_iddepartamento = departamento.iddepartamento";

   $res2 = $operaciones->consultar($qry);
   if (mysql_num_rows($res2) > 0)
       while ($filas = mysql_fetch_assoc($res2))
           $items[$filas["idciudad"] . " " . $filas["nombre_ciudad"] . "-" . $filas["nombre_departamento"]] = $filas["idciudad"] . " " . $filas["nombre_ciudad"] . "-" . $filas["nombre_departamento"];

   foreach ($items as $key => $value)
   {
       if (empty($q))
           echo "$key|$value\n";
       elseif (strpos(strtolower($value), $q) !== false)
       {
           echo "$key|$value\n";
       }
   }
?>
