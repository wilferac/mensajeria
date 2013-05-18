<?php 

include('convertir.php'); 
include('generarcodigobarras.php'); 

if ( isset($_POST['PDF_2']) ) 
  { 
     doPDF('ejemplo',$_POST['html'],true,'estiloimprimir.css',false,'letter','landscape'); 
	 
   }
?> 


<!DOCTYPE html >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head> 

<body> 

<?php echo $html ?> 


</body> 
</html>
