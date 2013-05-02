<?

require_once('barcodephp/class/BCGFontFile.php');
require_once('barcodephp/class/BCGColor.php');
require_once('barcodephp/class/BCGDrawing.php');
require_once('barcodephp/class/BCGcode39.barcode.php');

// The arguments are R, G, and B for color.
$colorFront = new BCGColor(0, 0, 0);
$colorBack = new BCGColor(255, 255, 255);
$font = new BCGFontFile('barcodephp/font/Arial.ttf', 18);
$code = new BCGcode39(); // Or another class name from the manual
$code->setScale(2); // Resolution
$code->setThickness(30); // Thickness
$code->setForegroundColor($colorFront); // Color of bars
$code->setBackgroundColor($colorBack); // Color of spaces
$code->setFont($font); // Font (or 0)
$code->parse('174484'); // Text

$drawing = new BCGDrawing('../../../tmp/imagen.jpeg',$colorBack);
$drawing->setBarcode($code);
$drawing->draw();

//header('Content-Type: image/jpeg');

$drawing->finish(BCGDrawing::IMG_FORMAT_JPEG);


?>