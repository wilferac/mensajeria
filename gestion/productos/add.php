<?php
   require("../../clases/clases.php");
   require("../../libreria/libreria.php");
   include '../../security/User.php';
   include ('../../Menu.php');
   

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
        <title>Sistema de Mensajeria</title>
        <style type="text/css" title="currentStyle">
            @import "../../media/css/demo_page.css";
        </style>

        <script language="JavaScript">


            function validar(formulario)
            {



                if (formulario.NOMBRE.value == "")
                {
                    alert("Se encuentra vacio el campo \"NOMBRE\"");
                    formulario.NOMBRE.focus();
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

        parent.frames[0].document.getElementById("a2").innerHTML = "Gestion";
        parent.frames[0].document.getElementById("a2").href = "gestion.php";
        parent.frames[0].document.getElementById("s2").style.visibility = "visible";

        parent.frames[0].document.getElementById("a3").innerHTML = "Ver Productos";
        parent.frames[0].document.getElementById("a3").href = "gestion/productos/consulta.php";

    </script>

</head>
<body id="dt_example">
    <?
       $objMenu = new Menu($objUser);
       $objMenu->generarMenu();
//		   	$operacion = new operacion();
//			 $operacion -> menu();
    ?>
    <p>&nbsp;</p><p>&nbsp;</p>	

    <h2 align="left">&nbsp;&nbsp;&nbsp;CREAR PRODUCTO</h2>           	
    <table border="0" align="center" width="80%">
        <tr>    
            <td width="10%" valign="top">&nbsp;</td> 
            <td width="80%" valing="top" ALIGN="CENTER" >
                <br>  	
                <table border="0" width="80%" >
                    <tr ALING="CENTER">
                        <td>	 
                            <FORM name="formulario" onSubmit="return validar(this)" action="validacion_producto.php" method="post" >
                                <TABLE width="100%" border="1" cellpadding="4" cellspacing="4" bgcolor="#FFFFFF" >
                                    <tr><td><font size="1" face="Verdana"><strong>CODIGO</strong></font>
                                            <input type="text" size="30" maxlength="10" name="CODIGO">
                                        </td></tr>
                                    <TR>
                                        <TD colspan="1"><font size="1" face="Verdana"><strong>TIPO PRODUCTO</strong></font><br>
                                            <select name="TPRODUCTO" size="1">
                                                <?php
                                                   $consulta = mysql_query("select idtipo_producto, nombre_tipo_producto 
                                                from tipo_producto");
                                                   while ($row = mysql_fetch_array($consulta, MYSQL_BOTH))
                                                   {
                                                       ?>

                                                       <option value=<?php echo $row[0] ?>><?php echo $row[1] ?></option>
                                                       <?php
                                                   }
                                                ?>
                                            </select>
                                        </TD>
                                    </TR>

                                    <TR>
                                        <TD><font size="1" face="Verdana"><strong>NOMBRE</strong></font><br>
                                            <input type="text" size="30" maxlength="30" name="NOMBRE">
                                        </TD>   
                                    </TR>
                                    <TR>
                                        <TD><font size="1" face="Verdana"><strong>PORCENTAJE SEGURO</strong></font><br>
                                            <input type="text" size="10" maxlength="10" name="PORCENTAJE">
                                        </TD>
                                    </TR>
                                    <TR>
                                        <TD COLSPAN="2" align="right">
                                            <input name="INSERTAR" type="submit" value="Crear">
                                            <input name="BORRAR" type="reset" VALUE="Limpiar"></TD>
                                    </TR>
                                </TABLE>
                            </FORM>
                    <font face="Verdana" size="1"><center>(*) Campo Opcional</center></font>
            </TD>


        </tr>

    </table>


</td>

<td width="10%" valign="top"></td> 

</tr>     
</table>
</body>
</html>
