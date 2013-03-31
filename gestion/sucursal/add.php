<?php
//session_start();
   include("../../clases/clases.php");
   include '../../security/User.php';
   include ('../../Menu.php');

   $objUser = unserialize($_SESSION['currentUser']);

   if ($objUser->getStatus() != 1)
   {
       $operacion->redireccionar('No Puede entrar', 'index.php');
       return;
   }
?>
<html>
    <head>
        <title>Sistema de Mensajeria.</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <script type="text/javascript" src="tabber.js"></script>
        <link rel="stylesheet" href="example.css" TYPE="text/css" MEDIA="screen">
        <link rel="stylesheet" href="example-print.css" TYPE="text/css" MEDIA="print">

        <style type="text/css" title="currentStyle">
            @import "../../media/css/demo_page.css";
            @import "../../media/css/demo_table.css";
            @import "media/css/TableTools.css";
        </style>
        <script type="text/javascript">

            /* Optional: Temporarily hide the "tabber" class so it does not "flash"
             on the page as plain HTML. After tabber runs, the class is changed
             to "tabberlive" and it will appear. */

            document.write('<style type="text/css">.tabber{display:none;}<\/style>');
        </script>

        <script language="JavaScript">
<!--FUNCI�N DE VERIFICACI�N DE LOS CAMPOS DEL FORMULARIO

            function validar(formulario)
            {

                if (formulario.CODIGO.value == "")
                {
                    alert("Se encuentra vacio el campo \"CODIGO\"");
                    formulario.CODIGO.focus();
                    return(false);
                }
                var checkOK = "0123456789";
                var checkStr = formulario.CODIGO.value;
                var allValid = true;
                var decPoints = 0;
                var allNum = "";
                for (i = 0; i < checkStr.length; i++)
                {
                    ch = checkStr.charAt(i);
                    for (j = 0; j < checkOK.length; j++)
                        if (ch == checkOK.charAt(j))
                            break;
                    if (j == checkOK.length)
                    {
                        allValid = false;
                        break;
                    }
                    allNum += ch;
                }
                if (!allValid)
                {
                    alert("Escriba solo digitos en el campo \"CODIGO\" ");
                    formulario.CODIGO.focus();
                    return (false);
                }


                if (formulario.NOMBRE.value == "")
                {
                    alert("Se encuentra vacio el campo \"NOMBRE\"");
                    formulario.NOMBRE.focus();
                    return(false);
                }


                if (formulario.DIRECCION.value == "")
                {
                    alert("Se encuentra vacio el campo \"DIRECCION\"");
                    formulario.DIRECCION.focus();
                    return(false);
                }


                if (formulario.DIRECCION.value == "")
                {
                    alert("Se encuentra vacio el campo \"DIRECCION\"");
                    formulario.DIRECCION.focus();
                    return(false);
                }



                var checkOK = "0123456789";
                var checkStr = formulario.TELEFONO.value;
                var allValid = true;
                var decPoints = 0;
                var allNum = "";
                for (i = 0; i < checkStr.length; i++)
                {
                    ch = checkStr.charAt(i);
                    for (j = 0; j < checkOK.length; j++)
                        if (ch == checkOK.charAt(j))
                            break;
                    if (j == checkOK.length)
                    {
                        allValid = false;
                        break;
                    }
                    allNum += ch;
                }
                if (!allValid)
                {
                    alert("Escriba solo digitos en el campo \"TELEFONO 1\" ");
                    formulario.TELEFONO.focus();
                    return (false);
                }


                var checkOK = "0123456789";
                var checkStr = formulario.TELEFONO2.value;
                var allValid = true;
                var decPoints = 0;
                var allNum = "";
                for (i = 0; i < checkStr.length; i++)
                {
                    ch = checkStr.charAt(i);
                    for (j = 0; j < checkOK.length; j++)
                        if (ch == checkOK.charAt(j))
                            break;
                    if (j == checkOK.length)
                    {
                        allValid = false;
                        break;
                    }
                    allNum += ch;
                }
                if (!allValid)
                {
                    alert("Escriba solo digitos en el campo \"TELEFONO 2\" ");
                    formulario.TELEFONO2.focus();
                    return (false);
                }


                var checkOK = "0123456789";
                var checkStr = formulario.CELULAR.value;
                var allValid = true;
                var decPoints = 0;
                var allNum = "";
                for (i = 0; i < checkStr.length; i++)
                {
                    ch = checkStr.charAt(i);
                    for (j = 0; j < checkOK.length; j++)
                        if (ch == checkOK.charAt(j))
                            break;
                    if (j == checkOK.length)
                    {
                        allValid = false;
                        break;
                    }
                    allNum += ch;
                }
                if (!allValid)
                {
                    alert("Escriba solo digitos en el campo \"CELULAR\" ");
                    formulario.CELULAR.focus();
                    return (false);
                }


                var checkOK = "@";
                var checkStr = formulario.CORREO.value;
                var allValid = false;
                var decPoints = 0;
                var allNum = "";
                for (i = 0; i < checkStr.length; i++)
                {
                    ch = checkStr.charAt(i);
                    for (j = 0; j < checkOK.length; j++)
                        if (ch == checkOK.charAt(j))
                        {
                            allValid = true;
                            break;
                        }
                }

                if (formulario.CORREO.value != "")
                {
                    if (allValid == false)
                    {
                        alert("El correo esta erroneamente en el campo \"CORREO ELECTRONICO\" ");
                        formulario.CORREO.focus();
                        return (false);
                    }
                }



                if (formulario.OBSERVACIONES.value.length > 500)
                {
                    alert("Digite menos de 500 caracteres en el campo \"OBSERVACIONES\"");
                    formulario.OBSERVACIONES.focus();
                    return(false);
                }
            }

            //--> FIN DEL SCRIPT DE VERIFICACION
        </script>

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

        parent.frames[0].document.getElementById("a2").innerHTML = "Gestión";
        parent.frames[0].document.getElementById("a2").href = "gestion.php";
        parent.frames[0].document.getElementById("s2").style.visibility = "visible";

        parent.frames[0].document.getElementById("a3").innerHTML = "Ver Sucursales";
        parent.frames[0].document.getElementById("a3").href = "gestion/sucursal/consulta.php";

    </script>

</head>
<body id="dt_example">

    <?
       $objMenu = new Menu($objUser);
       $objMenu->generarMenu();
//       $operacion = new operacion();
//       $operacion->menu();
    ?>
    <p>&nbsp;</p><p>&nbsp;</p>
    <div class="tabber">

        <div class="tabbertab">
            <h2>CREAR SUCURSAL</h2>
            <br>  	
            <table border="0" align="center" width="70%">
                <tr>
                    <td>	 

                        <FORM name="formulario" onSubmit="return validar(this)" action="validacion_sucursal.php" method="post" >
                            <TABLE width="100%" border="1" cellpadding="4" cellspacing="4" bgcolor="e9e7ed" >
                                <TR>
                                    <TD><font size="1" face="Verdana"><strong>CODIGO</strong></font><br>
                                        <input type="text" size="10" maxlength="10" name="CODIGO">
                                    </TD>

                                    <TD><font size="1" face="Verdana"><strong>NOMBRE</strong></font><br>
                                        <input type="text" size="30" maxlength="30" name="NOMBRE">
                                    </TD>

                                </TR>

                                <TR>

                                    <TD><font size="1" face="Verdana"><strong>DIRECCION</strong></font><br>
                                        <input type="text" size="30" maxlength="30" name="DIRECCION">
                                    </TD>
                                    <TD><font size="1" face="Verdana"><strong>TELEFONO</strong></font><font size="1" face="Verdana">(*)</font><br>
                                        <input type="text" size="12" maxlength="7" name="TELEFONO">
                                    </TD>

                                </TR>

                                <TR>
                                    <TD><font size="1" face="Verdana"><strong>CIUDAD</strong></font><br>
                                        <select name="CIUDAD" size="1">
                                            <?php
                                               $ciudad = new ciudad();
                                               //$cond = "select idciudad, nombre_ciudad from ciudad order by nombre_ciudad";

                                               $res = $ciudad->consultar('', 'nombre_ciudad');

                                               while ($row = mysql_fetch_assoc($res))
                                               {
                                                   ?>

                                                   <option value=<?php echo $row['idciudad'] ?> ><?php echo $row["nombre_ciudad"] ?></option>
                                                   <?php
                                               }
                                            ?>

                                        </select>
                                    <TD colspan="1"><font size="1" face="Verdana"><strong>RESOLUCION DE FACTURACION</strong></font><font size="1" face="Verdana">(*)</font><br>
                                        <input type="text" size="12" maxlength="11" name="TELEFONO2">
                                    </TD>

                                </TR>


                                <TR>
                                    <TD valign="top">
                                        <font size="1" face="Verdana"><strong>OBSERVACIONES</strong></font><font size="1" face="Verdana">(*)</font></TD>
                                    <TD COLSPAN="4" align="left">
                                        <textarea name="OBSERVACIONES" cols="70" rows="6"></textarea></TD>
                                </TR>
                                <TR>
                                    <TD COLSPAN="4" align="right">
                                        <input name="INSERTAR" type="submit" value="Crear">
                                        <input name="BORRAR" type="reset" VALUE="Limpiar"></TD>
                                </TR>

                            </TABLE>
                        </FORM>
                <font face="Verdana" size="1"><center>(*) Campo Opcional</center></font>
                </TD>
                </tr>

            </table>
        </div>

</body>
</html>
