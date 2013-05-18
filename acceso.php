<?php
  session_start();
  require 'conexion/conexion.php';
  require_once 'security/User.php';
?>
<html>
    <head>
        <title>Acceso</title>
        <script type="text/javascript" language="javascript" src="./js/funciones.js"></script>	

    </head>

    <body>
        <?php
          $login = $_REQUEST["login"];
          $pass = $_REQUEST["password"];

          $objUser = new User($login, $pass);

          if ($objUser->login())
          {
              $_SESSION['currentUser'] = serialize($objUser);
              $objUser->show();
              //return;
              echo "<script language=javascript>
		var ancho = screen.width;
		var alto = screen.height;
		
		ancho = ancho-50;
		alto = alto-100;
		
		window.open('main.php','Principal','location=0,toolbar=0,menubar=0,resizable=1,width='+ancho+',height='+alto);
		
		</script>";
          }
          else
          {
              echo ("<script> alert('Error in Login!');
                   location.href='index.php'
                       </script>");
              // header('Location: index.php');
          }
        ?>
    </body>
</html>
