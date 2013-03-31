<?php 

include('convertir.php'); 

//$html= --> Aquí pondriamos por ejemplo la consulta <div style="position: absolute; top: 10mm; left: 10mm; width: 25mm; word-break:break-all;"> Colocar aqui el contenido para class contenido id "contenido" ycfds fsfsfsfsffsf fsdfsfsxx ff</div> 
$html='
    
    <script type="text/javascript" src="../../../js/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="../../../js/jquery-barcode-2.0.2.min.js"></script>
     <script type="text/javascript">
    
      function generateBarcode(){
        var value = "174484";
        var btype = "code39";
        var renderer = "css"; //css svg  bmp canvas
        
       var settings = {
          output:renderer,
          bgColor: "#FFFFFF",
          color: "#000000",
          barWidth: "1",
          barHeight: "50",
          moduleSize: "5",
          posX: "10",
          posY: "20",
          addQuietZone: 1
        };

         
          $("#barcodeTarget").html("").show().barcode(value, btype, settings);
     
      }
              
      $(function(){
           generateBarcode();
		    alert (document.getElementById("barcodeTarget").innerHTML);
      });
  
    </script>

<div style="position: absolute; top: 25mm; left: 10mm; width: 30mm; word-break:break-all;"> Colocar aqui el contenido para class contenido id "contenido" ycfds fsfsfsfsffsf fsdfsfsxx ff</div> 

 <div id="barcodeTarget" class="barcodeTarget">
 
 dsdsdsd
 
 </div>
 
 

  
 '

?> 

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

<script language="javascript">
 alert (document.getElementById('barcodeTarget').innerHTML);
</script>
</body> 
</html>
