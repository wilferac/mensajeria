<?php
//session_start(); 
   include("../../clases/clases.php");
   include("../../libreria/libreria.php");
   include '../../security/User.php';

   include "../../Menu.php";

   $objUser = unserialize($_SESSION['currentUser']);
   //$objUser = new User();
//        echo($objUser->getStatus());
   if ($objUser->getStatus() != 1)
   {
       //$objUser->show();
       $operacion->redireccionar('No Puede entrar', 'index.php');
       return;
   }
   if (!$objUser->checkRol("Usuario"))
   {
       die();
   }
?>
<html>
    <head>
        <title>Sistema de Mensajeria</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <script type="text/javascript" src="tabber.js"></script>



        <script type="text/javascript">

            /* Optional: Temporarily hide the "tabber" class so it does not "flash"
             on the page as plain HTML. After tabber runs, the class is changed
             to "tabberlive" and it will appear. */

            document.write('<style type="text/css">.tabber{display:none;} </style>');
        </script>

        <style type="text/css" title="currentStyle">
            @import "../../media/css/demo_page.css";
            @import "../../media/css/demo_table.css";
            @import "../../media/css/jquery.css";
            @import "example.css";
        </style>


        <script type="text/javascript" language="javascript" src="../../js/jquery.js"></script>
        <script type="text/javascript" language="javascript" src="../../js/jquery.dataTables.js"></script>
        <script type="text/javascript" language="javascript" src="../../js/jquery.validate.js"></script> 
        <script type="text/javascript" src="../../js/jquery_003.js"></script>

        <script language="JavaScript">
<!--FUNCION DE VERIFICACION DE LOS CAMPOS DEL FORMULARIO

            function validar(formulario)
            {




                var numtab = formulario.numtab.value;
                var ape = 'APELLIDOS' + numtab;
                var DOC = 'DOCUMENTO' + numtab;

                if (document.getElementById(DOC).value == "")
                {
                    alert("Se encuentra vacio el campo \"DOCUMENTO\"");
                    document.getElementById(DOC).focus();
                    return(false);
                }



                var checkOK = "0123456789";
                var checkStr = document.getElementById(DOC).value;
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
                    alert("Escriba solo digitos en el campo \"DOCUMENTO\" ");
                    document.getElementById(DOC).focus();
                    return (false);
                }


                if (formulario.APELLIDOS.value == "")
                {
                    alert("Se encuentra vacio el campo \"APELLIDOS\"");
                    document.getElementById(ape).focus();
                    return(false);
                }


                if (formulario.NOMBRES.value == "")
                {
                    alert("Se encuentra vacio el campo \"NOMBRES\"");
                    formulario.NOMBRES.focus();
                    return(false);
                }




                if (formulario.DIRECCION.value == "")
                {
                    alert("Se encuentra vacio el campo \"DIRECCION\"");
                    formulario.DIRECCION.focus();
                    return(false);
                }


                if (formulario.COMISION.value == "")
                {
                    alert("Se encuentra vacio el campo \"COMISION\"");
                    formulario.COMISION.focus();
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



                if (formulario.OBSERVACIONES.value.length > 300)
                {
                    alert("Digite menos de 300 caracteres en el campo \"OBSERVACIONES\"");
                    formulario.OBSERVACIONES.focus();
                    return(false);
                }
            }

            //--> FIN DEL SCRIPT DE VERIFICACION
        </script>

        <script type="text/javascript">
            <!--
            var orden = 2;
            function ciudadadd() {

                var id = document.getElementById("ciudad");
                var nuevos = id.cloneNode(true);

                nuevos.id = 'div' + orden;
                nuevos.name = 'div' + orden;

                contenedor = document.getElementById("nuevasciudades");

                nuevos.lastChild.setAttribute('id', 'ciudad[]');
                nuevos.lastChild.setAttribute('name', 'ciudad[]');

                ele = document.createElement('input');
                ele.type = 'button';
                ele.value = 'Borrar';
                ele.name = 'boton' + orden;
                ele.onclick = function() {
                    borraradd(nuevos.id)
                }
                nuevos.appendChild(ele);

                contenedor.appendChild(nuevos);
                orden++;
            }
            function borraradd(obj) {
                fi2 = document.getElementById('nuevasciudades');
                fi2.removeChild(document.getElementById(obj));
            }

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

        parent.frames[0].document.getElementById("a3").innerHTML = "Ver Terceros";
        parent.frames[0].document.getElementById("a3").href = "gestion/terceros/consulta.php";


    </script>

    <script language="javascript">
        $(document).ready(function() {
            //$('#DOCUMENTO').blur(function(){
            $("input").blur(function() {


                var id = $(this).attr("id");
                var id = String(id);

                var long = id.length - 1;
                var pedazofinal = id.substring(long);

                var val = $(this).attr("value");
                var DOCUMENTO = val;
                var pos = id.indexOf("DOCUMENTO");
                //alert (pos);
                if (pos == 0) {

                    if (DOCUMENTO != "")
                    {
                        //alert (pos+DOCUMENTO);   
                        $('#info' + pedazofinal).html('<img src="../../imagenes/loader.gif" alt="" height="17" />').fadeOut(1000);
                        var dataString = id + '=' + DOCUMENTO;
                        //alert (dataString);
                        $.ajax({
                            type: "POST",
                            url: "prevalidacion.php?DOCUMENTO=" + DOCUMENTO + "&numero=" + pedazofinal,
                            data: dataString,
                            success: function(data) {
                                $('#info' + pedazofinal).fadeIn(1000).html(data);

                            }});

                    } // end if (DOCUMENTO)
                }	// end if (pos)
            });   //BLUR          
        });

    </script>
    <style>
        .nodisponible { color: red; }
        .disponible { color: green;}
    </style>

</head>
<body id="dt_example">
    <?
       $objMenu = new Menu($objUser);
       $objMenu->generarMenu();
//       $operacion = new operacion();
//       $operacion->menu();
//    
    ?>

    <?php
       $objUser = unserialize($_SESSION['currentUser']);

       if ($objUser->getStatus() == 1)
       {
           $noaplica = "N/A";
           ?>


           <p>&nbsp;</p> <p>&nbsp;</p>
           <table border="0">

               <tr>    

                   <td width="10%" valign="top">
                       <br><br>
                       <?php
                       //menu_gestion("../../","menu_terceros.php"); 
                       ?>
                   </td> 

                   <td width="100%" valing="top" >
                       <div class="tabber">


                           <?
                           /*                            * **********************************************
                             Tab para PUNTO DE VENTA   <input type=hidden name='tipotercero' value='4'>
                            * ********************************************** */
                           ?>        
                           <div class="tabbertab">
                               <h2>CREAR PUNTO DE VENTA</h2>   
                               <br>  	
                               <table border="0" width="100%">
                                   <tr>
                                       <td>	 

                                           <FORM name="formulario" onSubmit="return validar(this)" action="validacion_tercero.php" method="post" >
                                               <input type=hidden name='tipotercero' value='4'>
                                               <input type="hidden" name="APELLIDOS" value="N/A">	
                                               <input type="hidden" name="SUCURSAL" value="1">	
                                               <input type=hidden name='numtab' id='numtab' value='1'>
                                               <? /* Se asignó 1 para sucursal para que no haya problema de clave foranea. 
                                                 Aqui idsucursal es un dato muerto o quemado para Punto de Venta
                                                */
                                               ?>	
                                               <TABLE width="80%" align="center" border="1" cellpadding="4" cellspacing="4" bgcolor="#FFFFFF" >
                                                   <TR>

                                                       <TD><font size="1" face="Verdana"><strong>TIPO DE DOCUMENTO</strong></font><br>
                                                           <select name="TIPO_DOCUMENTO" size="1">
                                                               <?php
                                                               $consulta = mysql_query("select idtipo_identificacion, nombre_tipo_identificacion 

                                          from tipo_identificacion");
                                                               while ($row = mysql_fetch_array($consulta, MYSQL_BOTH))
                                                               {
                                                                   ?>
                                                                   <option value=<?php echo $row["idtipo_identificacion"] ?>><?php echo $row["nombre_tipo_identificacion"] ?></option>
                                                                   <?php
                                                               }
                                                               ?>
                                                           </select>			</TD>
                                                       <TD><font size="1" face="Verdana"><strong>DOCUMENTO</strong></font><br>
                                                           <input type="text" size="15" maxlength="15" name="DOCUMENTO1" id="DOCUMENTO1">
                                                           <div id="info1" style="display:inline"></div>			</TD>
                                                   </TR>

                                                   <TR>
                                                       <TD colspan="2"><font size="1" face="Verdana"><strong>NOMBRE</strong></font><br>
                                                           <input type="text" size="30" maxlength="30" name="NOMBRES" id="NOMBRES">
                                                       </TD>
                                                   </TR>

                                                   <TR>
                                                       <TD colspan="2"><font size="1" face="Verdana"><strong>DIRECCION </strong></font><br>
                                                           <input type="text" size="60" maxlength="40" name="DIRECCION">

                                                       </TD>
                                                   </TR>

                                                   <TR>
                                                       <TD><font size="1" face="Verdana"><strong>TELEFONO 1</strong></font><font size="1" face="Verdana">(*)</font><br>
                                                           <input type="text" size="12" maxlength="7" name="TELEFONO">			</TD>
                                                       <TD colspan="1"><font size="1" face="Verdana"><strong>TELEFONO 2</strong></font><font size="1" face="Verdana">(*)</font><br>
                                                           <input type="text" size="12" maxlength="11" name="TELEFONO2">			</TD>
                                                   </TR>

                                                   <TR>
                                                       <TD><font size="1" face="Verdana"><strong>CELULAR</strong></font><font size="1" face="Verdana">(*)</font><br>
                                                           <input type="text" size="15" maxlength="11" name="CELULAR">			</TD>
                                                       <TD colspan="1"><font size="1" face="Verdana"><strong>CORREO ELECTRONICO</strong></font><font size="1" face="Verdana">(*)</font><br>
                                                           <input type="text" size="30" maxlength="30" name="CORREO">			</TD>
                                                   </TR>

                                                   <tr>
                                                       <TD colspan="2"><font size="1" face="Verdana"><strong>COMISION %</strong></font><font size="1" face="Verdana"></font><br>
                                                           <input type="text" size="10" maxlength="2" name="COMISION">			</TD>
                                                   </tr>


                                                   <TR>
                                                       <TD valign="top">
                                                           <font size="1" face="Verdana"><strong>OBSERVACIONES</strong></font><font size="1" face="Verdana">(*)</font></TD>
                                                       <TD COLSPAN="1" align="left">
                                                           <textarea name="OBSERVACIONES" cols="50" rows="6"></textarea></TD>
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

                           </div>

                           <?
                           /*                            * **********************************************
                             Tab para ALIADO  <input type=hidden name='tipotercero' value='3'>
                            * ********************************************** */
                           ?>
                           <div class="tabbertab">
                               <h2>CREAR ALIADO</h2>   
                               <br>  	
                               <table border="0" width="100%">
                                   <tr>
                                       <td>	 

                                           <FORM name="formulario" onSubmit="return validar(this)" action="validacion_tercero.php" method="post" >
                                               <input type=hidden name='tipotercero' value='3'>
                                               <input type="hidden" name="APELLIDOS" value="N/A">	
                                               <input type="hidden" name="SUCURSAL" value="1">	
                                               <input type=hidden name='numtab' id='numtab' value='2'>

                                               <TABLE width="100%" border="1" cellpadding="4" cellspacing="4" bgcolor="#FFFFFF" >
                                                   <TR>

                                                       <TD><font size="1" face="Verdana"><strong>TIPO DE DOCUMENTO</strong></font><br>
                                                           <select name="TIPO_DOCUMENTO" size="1">
                                                               <?php
                                                               $consulta = mysql_query("select idtipo_identificacion, nombre_tipo_identificacion 

                                          from tipo_identificacion");
                                                               while ($row = mysql_fetch_array($consulta, MYSQL_BOTH))
                                                               {
                                                                   ?>
                                                                   <option value=<?php echo $row["idtipo_identificacion"] ?>><?php echo $row["nombre_tipo_identificacion"] ?></option>
                                                                   <?php
                                                               }
                                                               ?>
                                                           </select>			</TD>
                                                       <TD><font size="1" face="Verdana"><strong>DOCUMENTO</strong></font><br>
                                                           <input type="text" size="15" maxlength="15" name="DOCUMENTO2" id="DOCUMENTO2">
                                                           <div id="info2" style="display:inline"></div>			</TD>
                                                   </TR>

                                                   <TR>
                                                       <TD><font size="1" face="Verdana"><strong>NOMBRES</strong></font><br>
                                                           <input type="text" size="30" maxlength="30" name="NOMBRES" id="NOMBRES">			</TD>
                                                       <TD>
                                                           <input type="button" value="Agregar Ciudad" onClick="javascript:ciudadadd()">

                                                           <div id="ciudad">
                                                               <font size="1" face="Verdana"><strong>CIUDAD</strong></font><br>  
                                                               <select id="ciudad1" name="ciudad1" size="1">   
                                                                   <?php
                                                                   $consulta = mysql_query("
		  SELECT ciudad.idciudad, ciudad.nombre_ciudad, ciudad.departamento_iddepartamento, departamento.iddepartamento, departamento.nombre_departamento
FROM ciudad, departamento
WHERE ciudad.departamento_iddepartamento = departamento.iddepartamento
ORDER BY ciudad.nombre_ciudad
		  ");
                                                                   while ($row = mysql_fetch_assoc($consulta))
                                                                   {
                                                                       ?>
                                                                       <option value=<?php echo $row["idciudad"] ?>><?php echo $row["nombre_ciudad"] . " - " . $row["nombre_departamento"] ?></option><?php
                                                        }
                                                                   ?></select></div>
                                                           <div id="nuevasciudades"></div>



                                                       </TD>
                                                   </TR>

                                                   <TR>
                                                       <TD colspan="2"><font size="1" face="Verdana"><strong>DIRECCION</strong></font> <font size="1" face="Verdana"><strong>PRINCIPAL</strong></font><br>
                                                           <input type="text" size="60" maxlength="60" name="DIRECCION">			</TD>
                                                   </TR>

                                                   <TR>
                                                       <TD><font size="1" face="Verdana"><strong>TELEFONO 1</strong></font><font size="1" face="Verdana">(*)</font><br>
                                                           <input type="text" size="12" maxlength="7" name="TELEFONO">			</TD>
                                                       <TD colspan="1"><font size="1" face="Verdana"><strong>TELEFONO 2</strong></font><font size="1" face="Verdana">(*)</font><br>
                                                           <input type="text" size="12" maxlength="11" name="TELEFONO2">			</TD>
                                                   </TR>

                                                   <TR>
                                                       <TD><font size="1" face="Verdana"><strong>CELULAR</strong></font><font size="1" face="Verdana">(*)</font><br>
                                                           <input type="text" size="15" maxlength="11" name="CELULAR">			</TD>
                                                       <TD colspan="1"><font size="1" face="Verdana"><strong>CORREO ELECTRONICO</strong></font><font size="1" face="Verdana">(*)</font><br>
                                                           <input type="text" size="30" maxlength="30" name="CORREO">			</TD>
                                                   </TR>


                                                   <TR>
                                                       <TD valign="top">
                                                           <font size="1" face="Verdana"><strong>OBSERVACIONES</strong></font><font size="1" face="Verdana">(*)</font></TD>
                                                       <TD COLSPAN="1" align="left">
                                                           <textarea name="OBSERVACIONES" cols="50" rows="6"></textarea></TD>
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

                           </div>

                       </div>      
                       </div>

                   </td>

                   <td width="10%" valign="top"></td> 

               </tr>     
           </table>




           <br>
           <?php
           //finalizar("../../");
       }
       else
       {

           denegada("SESION NO INICIADA", "../../");
       }
    ?>

</body>
</html>
