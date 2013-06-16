<?php 

include('convertir.php'); 
include('generarcodigobarras.php'); 


//$html= --> Aquí pondriamos por ejemplo la consulta <div style="position: absolute; top: 10mm; left: 10mm; width: 25mm; word-break:break-all;"> Colocar aqui el contenido para class contenido id "contenido" ycfds fsfsfsfsffsf fsdfsfsxx ff</div> 
$html='  

<style type="text/css">
<!--
#debajoNoGuia {
	position:absolute;
	left:0.503cm;
	top:8cm;
	width:3cm;
	height:1cm;
	z-index:100;
	background-color: #FFFFFF;
}
-->
</style>
<div style="position: absolute; top: 25mm; left: 10mm; width: 30mm; word-break:break-all;"> Colocar aqui el contenido para class contenido id "contenido" ycfds fsfsfsfsffsf fsdfsfsxx ff</div> 

 <div id="barcodeTarget" class="barcodeTarget"><img src="../../../tmp/imagen.jpeg" />

 </div>
 <div id="debajoNoGuia">1213131313</div>
 '?> 

<?php 

if ( isset($_POST['PDF_1']) ) 
    doPDF('ejemplo',$html,false); 

if ( isset($_POST['PDF_2']) ) 
    doPDF('ejemplo',$html,true,'style.css'); 

if ( isset($_POST['PDF_3']) ) 
    doPDF('',$html,true,'style.css'); 
             
if ( isset($_POST['PDF_4']) ) 
    doPDF('ejemplo',$html,true,'style.css',false,'letter','landscape');  
     
if ( isset($_POST['PDF_5']) ) 
    doPDF('ejemplo',$html,true,'',true); //asignamos los tags <html><head>... pero no tiene css 

if ( isset($_POST['PDF_6']) ) 
    doPDF('',$html,true,'style.css',true); 
     
if ( isset($_POST['PDF_7']) ) 
    doPDF('pdfs/nuevo-ejemplo',$html,true,'style.css',true); //lo guardamos en la carpeta pdfs     
?> 
<!DOCTYPE html >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="style.css" type="text/css" /> 
</head> 

<body> 

<?php echo $html ?> 


<form  action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST"> 
<table> 
  <tr> 
   
    <td><input name="PDF_2" type="submit" value="CREAR" /></td> 
  </tr>   
</table> 

</form> 
</body> 
</html>
