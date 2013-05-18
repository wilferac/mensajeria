<?
   // session_start();
   include("../../clases/clases.php");
   include '../../security/User.php';
   
   $objUser = unserialize($_SESSION['currentUser']);
   
   
   if (isset($_POST['registrar']))
   {
//print_r($_POST);
       $usuarios = new tercero();

//       $usuarios->usuario_tercero = $_SESSION['datosinicio']['usuario_tercero'];
       $usuarios->usuario_tercero = $objUser->getLogin();
       $usuarios->clave_tercero = md5($_POST['password']);


       if ($usuarios->cambiarcontrasenha())
       {
           ?>	
           <script language="javascript" type="text/javascript">
               var mensaje = "Modificacion Exitosa";
               alert(mensaje);
               window.location.href = "../../redireccionador.php";
           </script>
           <?
       }
       else
       {
           ?>
           <script language="javascript" type="text/javascript">
               var mensaje = "Modificacion NO Exitosa";
               alert(mensaje);
               window.location.href = "../../redireccionador.php";
           </script>
           <?
       }

       return;
   }
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Tablas</title>
        <style type="text/css" title="currentStyle">
            @import "../../media/css/demo_page.css";
            @import "../../media/css/demo_table.css";
            @import "media/css/TableTools.css";
        </style>
        <link rel="stylesheet" type="text/css" media="screen" href="../../css/cmxform.css" />
        <style type="text/css">
            #formulario { width: 500px; }
            #formulario label { width: 250px; }
            #formulario label.error, #formulario input.submit { margin-left: 253px; }
        </style>

        <script type="text/javascript" language="javascript" src="../../js/jquery.js"></script>
        <script type="text/javascript" language="javascript" src="../../js/jquery.dataTables.js"></script>
        <script type="text/javascript" language="javascript" src="../../js/funciones.js"></script>	
        <script type="text/javascript" charset="utf-8" src="media/js/ZeroClipboard.js"></script>
        <script type="text/javascript" charset="utf-8" src="media/js/TableTools.js"></script>	
        <script type="text/javascript" language="javascript" src="../../js/jquery.validate.js"></script>

        <script type="text/javascript">
            $.validator.setDefaults({
                submitHandler: function() {
                    formulario.submit();
                }
            });

            $().ready(function() {
                // validate the comment form when it is submitted
                $("#formulario").validate(
                        {
                            rules: {
                                password: {
                                    required: true,
                                    minlength: 5
                                },
                                confirm_password: {
                                    required: true,
                                    minlength: 5,
                                    equalTo: "#password"
                                },
                            }});

            });
        </script>

    </head>
    <body id="dt_example">
        <div id="container">
            <div class="full_width big">
                <p class="navegacion"><a href="../../redireccionador.php">Página principal</a></p>
                Modificar contraseña
            </div>
            <p>&nbsp;</p>
            <div id="dynamic">
                <form class="formulario" id="formulario" method="post" action="">
                    <fieldset>

                        <p>
                            <label for="login">NOMBRE DE USUARIO</label>
                            <input readonly value="<?= $objUser->getLogin() ?>" />

                        </p>   
                        <p>
                            <label for="password">NUEVA CONTRASEÑA </label>
                            <input id="password" name="password"  type="password" class="required"  maxlength="35"/>
                        </p>
                        <p>
                            <label for="confirm_password">CONFIRMAR CONTRASEÑA </label>
                            <input id="confirm_password" name="confirm_password"  type="password" class="required" maxlength="35"/>
                        </p>   
                        <p>
                            <input class="submit" type="submit"  id="registrar" name="registrar" value="Cambiar"/>
                        </p>
                    </fieldset>
                </form>
            </div>
            <div class="spacer"></div>
        </div>
    </body>
</html>
