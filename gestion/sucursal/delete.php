<?php
  require("../../clases/clases.php");
  require("../../libreria/libreria.php");

  include '../../security/User.php';
 
  $objUser = unserialize($_SESSION['currentUser']);

  if ($objUser->getStatus() != 1)
  {
      $operacion->redireccionar('No Puede entrar', 'index.php');
      return;
  }
  if (!$objUser->checkRol("Admin"))
  {
      die("No tienes permiso");
  }
?>

<html>
    <head>
        <title>Sistema de Mensajeria.</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    </head>
    <body>

<?php
  $usuario = $_SESSION['datosinicio']['usuario_tercero'];
  $codigo = $_GET["documento"];
?>
        <br>
        <table width="100%">
            <tr>
                <td width="10%" valign="top">
                </td>  

                <td width="80%" align="center">
<?php
  $consulta = mysql_query("select *
		                          from sucursal 
				          where codigo_sucursal='$codigo'");

  $num = mysql_num_rows($consulta);
  if ($num < 1)
  {
      ?>
                          <TABLE align="center" border="0">
                              <TR>
                                  <TD align="center"><font size="+1" face="Verdana">La sucursal No Existe</font></TD> 
                              </TR>
                          </TABLE>
      <?php
  }
  else
  {
      $fila = mysql_fetch_assoc($consulta);
      $activa = $fila["Activa"];
      $activa = (int) !$activa;
      $consulta = mysql_query("update  sucursal SET Activa = $activa WHERE codigo_sucursal='$codigo'");
      echo mysql_error();
      if (mysql_affected_rows() > 0)
      {
          ?>

                              <script language="javascript" type="text/javascript">
                                  var mensaje="Desactivacion Exitosa";
                                  window.location.href='consulta.php?mensaje='+mensaje;
                              </script>
          <?
      }
      else
      {
          ?>
                              <script language="javascript" type="text/javascript">
                                  var mensaje="Desactivacion NO Exitosa";
                                  window.location.href='consulta.php?mensaje='+mensaje;
                              </script>
                          <? }
                          ?>

                          <?php
                      }
                    ?>

                </td>

                <td width="10%"></td>

        </table>


    </body>
</html>
