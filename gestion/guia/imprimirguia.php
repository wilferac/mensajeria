<? require_once("../../libreria/dompdf/dompdf_config.inc.php"); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
</head>
<?
/*
$mode = true;
$content = "<b>texto</b>";
$dompdf =  new DOMPDF(); 
  $paper_1='a4'; 
   $paper_2='portrait';
		
   $dompdf -> set_paper($paper_1,$paper_2); 
   $dompdf -> load_html(utf8_encode($content)); 
   ini_set("memory_limit","32M"); 
    $dompdf -> render(); 
      $dompdf->stream('guia.pdf');      
        //Creamos el pdf 
        if($mode==false) 
            $dompdf->stream(); 
             
        //Lo guardamos en un directorio y lo mostramos 
        if($mode==true) 
            if( file_put_contents($path, $dompdf->output()) ) header('Location: '.$path); 
			*/
?>

<?php
//se incluye la libreria de dompdf

$code = '<html><head><title>Hola</title></head>
<body><h1>Hola Mundo</h1></body></html>';    
//se crea una nueva instancia al DOMPDF
$dompdf = new DOMPDF();
//se carga el codigo html
$dompdf->load_html($code);
//aumentamos memoria del servidor si es necesario
ini_set("memory_limit","32M"); 
//lanzamos a render
$dompdf->render();
//guardamos a PDF
$dompdf->stream("mipdf.pdf");
?>
<body>
</body>
</html>
