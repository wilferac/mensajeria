<?
  // include ("../../param/param.php");
   //include ("../../autenticar.php");
  // include ("../../clases/clases.php");

  // include '../../security/User.php';
   //include ('../../Menu.php');

//   $objUser = unserialize($_SESSION['currentUser']);
//
//   if ($objUser->getStatus() != 1)
//   {
//       $operacion->redireccionar('No Puede entrar', 'index.php');
//       return;
//   }





   $formname = "busqueda";
?>
<html>
    <head>
        <style type="text/css" title="currentStyle">
            @import "../../media/css/demo_page.css";
            @import "../../css/default.css";	
        </style>

        <title>Buscar Guia</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

        <script language="JavaScript" type="text/JavaScript">
            <!--
            function MM_reloadPage(init) {  //reloads the window if Nav4 resized
            if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
            document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
            else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
            }
            MM_reloadPage(true);
            //-->
        </script>

        <script language="javascript">

            parent.frames[0].document.getElementById("a1").innerHTML = "";
            parent.frames[0].document.getElementById("a2").innerHTML = "";
            parent.frames[0].document.getElementById("a3").innerHTML = "";

            parent.frames[0].document.getElementById("s1").style.visibility = "hidden";
            parent.frames[0].document.getElementById("s2").style.visibility = "hidden";

            parent.frames[0].document.getElementById("a1").innerHTML = "Principal";
            parent.frames[0].document.getElementById("a1").href = "redireccionador.php";
            parent.frames[0].document.getElementById("s1").style.visibility = "visible";

            parent.frames[0].document.getElementById("a2").innerHTML = "Procesos";
            parent.frames[0].document.getElementById("a2").href = "procesos.php";
        </script>    

    </head>

    <body id="dt_example">
        <?
           //generar menu
         //  $objMenu = new Menu($objUser);
           //$objMenu->generarMenu();
//		   	$operacion = new operacion();
//			 $operacion -> menu();
        ?>
        <div id="container">
            <div class="full_width big">
                <p>&nbsp;</p>
                Consultar gu&iacute;a
            </div>
            <p>&nbsp;</p>

            <div class="spacer"><form name="<?= $formname ?>" method="post" action="busqueda.php">

                    <table width="767" border="0" align="center" cellpadding="0" cellspacing="0"  >

                        <tr>
                            <td>&nbsp;</td>
                            <td><table  class="table_Fondo" width="450" align="center" border="0" cellspacing="0" cellpadding="3" bgcolor="#FFFFFF">

                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><div align="right"> Ingrese n&uacute;mero de gu&iacute;a: </div></td>
                                        <td><input name="idguia" type="text" id="idguia"  size="25" maxlength="10"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><br></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="center"><input name="Submit" type="submit" value="Buscar"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="center">&nbsp;</td>
                                    </tr>

                                </table>
                            </td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                    <input type="hidden" name="token" value="<?= generarToken($formname) ?>" />
                </form></div>
        </div>
    </body>

    <br> 

    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
        <!--DWLayoutTable-->
        <tr>
            <td height="40" class="table_Fondo" align="center"><!--DWLayoutEmptyCell-->&nbsp;</td>
        <tr>
            <td class="table_Fondo" align="center">
                <div align="center"></div></td>
        </tr>
    </table>
    <br> 
    <h2 align="center">&nbsp;</h2>
    <br> 

</body>
</html>
