<?php 
/*----------------------------------------------------------/* 
     
$path     : nombre y/o ruta del pdf (sin la extensión) 
                p.e: --> 'ejemplo' , 'pdfs/nuevo-ejemplo' 
                si se deja vacío --> se genera uno aleatorio 

$content  : contenido del pdf 

$body     : true o false. 
                true  --> Añade; <doctype>, <body>, <head> a $content 
                false --> no altera el $content 
                 
$style    : la ruta de la CSS. Puede estar vacía 
                 Para cargar una css --> necesita $body = true; 

$mode     : true o false. 
                true  --> guarda el pdf en un directorio y lo muestra  
                false --> pregunta si guarda o abre el archivo  
             
$paper_1  : tamaño del papel[*] 
$paper_2  : estilo del papel[*] 
     
    [*] como ver las opciones disponibles:  
        --> http://code.google.com/p/dompdf/wiki/Usage#Invoking_dompdf_via_the_command_line 

/*----------------------------------------------------------*/  

require_once("../../../libreria/dompdf/dompdf_config.inc.php"); 

function doPDF($path='',$content='',$body=false,$style='',$mode=false,$paper_1='a4',$paper_2='portrait') 
{     
    if( $body!=true and $body!=false ) $body=false; 
    if( $mode!=true and $mode!=false ) $mode=false; 
     
    if( $body == true ) 
    { 
        $content=' 
        <!doctype html> 
        <html> 
        <head> 
            <link rel="stylesheet" href="'.$style.'" type="text/css" /> 
        </head> 
        <body>' 
            .$content. 
        '</body> 
        </html>'; 
    } 
     
    if( $content!='' ) 
    {         
        //Añadimos la extensión del archivo. Si está vacío el nombre lo creamos 
        $path!='' ? $path .='.pdf' : $path = crearNombre(10);   

        //Las opciones del papel del PDF. Si no existen se asignan las siguientes:[*] 
        if( $paper_1=='' ) $paper_1='a4'; 
        if( $paper_2=='' ) $paper_2='portrait'; 
             
        $dompdf =  new DOMPDF(); 
        $dompdf -> set_paper($paper_1,$paper_2); 
        $dompdf -> load_html(utf8_encode($content)); 
        //ini_set("memory_limit","32M"); //opcional  
        $dompdf -> render(); 
         
        //Creamos el pdf 
        if($mode==false) 
            $dompdf->stream("muestra.pdf"); 
             
        //Lo guardamos en un directorio y lo mostramos 
        //if($mode==true) 
         //   if( file_put_contents($path, $dompdf->output()) ) header('Location: '.$path); 
    } 
} 

function crearNombre($length) 
{ 
    if( ! isset($length) or ! is_numeric($length) ) $length=6; 
     
    $str  = "0123456789abcdefghijklmnopqrstuvwxyz"; 
    $path = ''; 
     
    for($i=1 ; $i<$length ; $i++) 
      $path .= $str{rand(0,strlen($str)-1)}; 

    return $path.'_'.date("d-m-Y_H-i-s").'.pdf';     
} 

?>