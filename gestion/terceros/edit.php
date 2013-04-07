<?php
//session_start();
   require("../../clases/clases.php");
   require("../../libreria/libreria.php");




   /*    * ***********Modificacion************************ */
   if (isset($_POST["idtercero"]) && $_POST["idtercero"] != "")
   {
       $noaplica = 'N/A';

       $tercero = new tercero;
       $tercero_tipo = new tercero_tipo;
       $idterceroNew = $_POST["idtercero"];
       $tercero->idtercero = $_POST["idtercero"];
       $tercero->documento_tercero = $_POST["DOCUMENTO"];
       $tercero->sucursal_idsucursal = !empty($_POST["SUCURSAL"]) ? $_POST["SUCURSAL"] : "NULL";
       $tercero->tipo_identificacion_tercero = $_POST["TIPO_DOCUMENTO"];
       $tercero->nombres_tercero = $_POST["NOMBRES"];
       $tercero->apellidos_tercero = $_POST["APELLIDOS"];
       $tercero->direccion_tercero = $_POST["DIRECCION"];
       $tercero->telefono_tercero = $_POST["TELEFONO"];
       $tercero->celular_tercero = $_POST["CELULAR"];
       $tercero->observaciones_tercero = $_POST["OBSERVACIONES"];

       if ($_POST["idtipotercero"] == 2) //Tipo Usuario
       {
           $tercero->usuario_tercero = $_POST["USUARIO"];

           $tercero->email_tercero = $_POST["CORREO"];
           $tercero->telefono2_tercero = $_POST["TELEFONO2"];
           $tercero->tercero_idvendedor = "NULL";
           $tercero->comision_tercero = $noaplica;
       }
       elseif ($_POST["idtipotercero"] == 1) //Tipo Cliente
       {
           $tercero->usuario_tercero = $_POST["USUARIO"];

           $tercero->email_tercero = $_POST["CORREO"];
           $tercero->telefono2_tercero = $_POST["TELEFONO2"];
           $tercero->tercero_idvendedor = $_POST["VENDEDOR"];
           $tercero->comision_tercero = $noaplica;
       }
       elseif ($_POST["idtipotercero"] == 6) //Tipo Vendedor
       {
           $tercero->usuario_tercero = $_POST["USUARIO"];
           $tercero->email_tercero = $_POST["CORREO"];
           $tercero->telefono2_tercero = $_POST["TELEFONO2"];
           $tercero->tercero_idvendedor = "NULL";
           $tercero->comision_tercero = $_POST["COMISION"];
       }
       elseif ($_POST["idtipotercero"] == 5) //Tipo mensajero Interno
       {
           $tercero->usuario_tercero = $_POST["USUARIO"];

           $tercero->email_tercero = $_POST["CORREO"];
           $tercero->telefono2_tercero = $_POST["TELEFONO2"];
           $tercero->tercero_idvendedor = "NULL";
           $tercero->comision_tercero = $noaplica;
       }
       elseif ($_POST["idtipotercero"] == 8) //Tipo mensajero DESTAJO
       {
           $tercero->usuario_tercero = $noaplica;
           $tercero->clave_tercero = null;
           $tercero->email_tercero = $noaplica;
           $tercero->telefono2_tercero = $noaplica;
           $tercero->tercero_idvendedor = "NULL";
           $tercero->comision_tercero = $noaplica;
       }
       elseif ($_POST["idtipotercero"] == 4) //Tipo mensajero PUNTO DE VENTA
       {
           $tercero->email_tercero = $_POST["CORREO"];
           $tercero->telefono2_tercero = $_POST["TELEFONO2"];
           $tercero->tercero_idvendedor = "NULL";
           $tercero->comision_tercero = $_POST["COMISION"];
       }
       elseif ($_POST["idtipotercero"] == 3) //Tipo ALIADO
       {
           $tercero->sucursal_idsucursal = "NULL";
           $tercero->email_tercero = $_POST["CORREO"];
           $tercero->telefono2_tercero = $_POST["TELEFONO2"];
           $tercero->tercero_idvendedor = "NULL";


           $cont = 1;
           $ciudadesArray = new ArrayObject();
           while (isset($_POST['ciudad' . $cont]))
           {
               $numCiu = $_POST['ciudad' . $cont];
               $ciudadesArray[$numCiu] = $numCiu;
               $cont++;
           }

           //consulto los valores actuales para saber cual dropear ....
           $ciudadesArrayOld = new ArrayObject();
           $consultaCiu = mysql_query("SELECT idciudad FROM ciudad_tercero ct  WHERE ct.`idtercero` =  $idterceroNew");
           while ($row = mysql_fetch_assoc($consultaCiu))
           {
               $idCiu = $row["idciudad"];
               $ciudadesArrayOld[$idCiu] = $idCiu;
           }
       }


       $conex = new conexion();
$num=0;
       $qtrans = "SET AUTOCOMMIT=0;";
       $sac = $conex->ejecutar($qtrans);
       $qtrans = "BEGIN;";
       $sac = $conex->ejecutar($qtrans);

       //si es aliado
       if ($_POST["idtipotercero"] == 3)
       {
//           echo("nuevo<br>");
//           print_r($ciudadesArray);
//           echo("<br>viejo<br>");
//           print_r($ciudadesArrayOld);
//           echo("<br>-------------<br>");
           //en esta parte busco las ciudades q salen y las que se van a agregar
           foreach ($ciudadesArrayOld as $old)
           {
               echo("<br>");
              // print_r($ciudadesArrayOld);

               if (isset($ciudadesArray[$old]))
               {
                   $ciudadesArray[$old] = 0;
                   //continue;
               }
               else
               {
                   $ciudadesArrayOld[$old] = 0;
                   echo("<br>borrando del viejo $old<br>");
                   $delViejo = "DELETE FROM ciudad_tercero WHERE idciudad = $old AND idtercero = $idterceroNew";
                   if (!mysql_query($delViejo))
                   {
                       die("error al desasignar una ciudad" . ' ' . mysql_error());
                   }
                   $num++;
                   //continue;
                   //lo borro del viejo
               }
           }
           
//           echo("<br>nuevo<br>");
//           print_r($ciudadesArray);
//           echo("<br>viejo<br>");
//           print_r($ciudadesArrayOld);
//           echo("<br>-------------<br>");
           
           //
           foreach ($ciudadesArray as $newCity)
           {
               if ($newCity != 0)
               {
                   echo("<br>agregando Ciudad $newCity<br>");
                   $addNew = "insert into ciudad_tercero(idciudad,idtercero) values($newCity,$idterceroNew)";
                   if (!mysql_query($addNew))
                   {
                       die("error al agregar una ciudad" . ' ' . mysql_error());
                   }
                   $num++;
               }

               //continue;
               //lo borro del viejo
           }
       }




       $res = $tercero->modificar();
       $num += mysql_affected_rows();

       if ($num > 0)
       {
           $qtrans = "COMMIT";
           $sac = $conex->ejecutar($qtrans);
           ?>			

           <script language="javascript" type="text/javascript">
               var mensaje = "Modificacion Exitosa";
               alert(mensaje);
               window.location.href = 'consulta.php';
           </script>
           <?
       }
       else
       {
           ?>
           <script language="javascript" type="text/javascript">
               var mensaje = "Los datos no cambiaron (Error 002)";
               alert(mensaje);
               window.location.href = 'consulta.php';
           </script>
           <?php
       }
       exit();
   }
   /*    * ***********FIN Modificacion************************ */
?>
<html>
    <head>
        <title>Sistema de Mensajeria</title>
        <style type="text/css" title="currentStyle">
            @import "../../media/css/demo_page.css";
            @import "../../media/css/demo_table.css";
            @import "../../media/css/jquery.css";
        </style>
        <script type="text/javascript">
            <!--
            var orden = 2;
            function ciudadadd(num) {
                num || (num = 1002);
                //num = num || 5;
                var id = document.getElementById("ciudad");
                var nuevos = id.cloneNode(true);

                nuevos.id = 'div' + orden;
                nuevos.name = 'div' + orden;

                contenedor = document.getElementById("nuevasciudades");

                nuevos.lastChild.setAttribute('id', 'ciudad' + orden);
                nuevos.lastChild.setAttribute('name', 'ciudad' + orden);

                ele = document.createElement('input');
                ele.type = 'button';
                ele.value = 'Borrar';
                ele.name = 'boton' + orden;
                ele.onclick = function() {
                    borraradd(nuevos.id)
                }
                nuevos.appendChild(ele);

                contenedor.appendChild(nuevos);
                document.getElementById("ciudad" + orden).value = num;
                orden++;
            }
            function borraradd(obj) {
                fi2 = document.getElementById('nuevasciudades');
                fi2.removeChild(document.getElementById(obj));
                orden--;
            }

        </script>
        <script language="JavaScript">

            function validar(formulario)
            {
                if (formulario.DOCUMENTO.value == "")
                {
                    alert("Se encuentra vacio el campo \"DOCUMENTO\"");
                    formulario.DOCUMENTO.focus();
                    return(false);
                }
                var checkOK = "0123456789";
                var checkStr = formulario.DOCUMENTO.value;
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
                    formulario.DOCUMENTO.focus();
                    return (false);
                }

                if (formulario.APELLIDOS.value == "")
                {
                    alert("Se encuentra vacio el campo \"APELLIDOS\"");
                    formulario.APELLIDOS.focus();
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

        parent.frames[0].document.getElementById("a3").innerHTML = "Ver Terceros";
        parent.frames[0].document.getElementById("a3").href = "gestion/terceros/consulta.php";

    </script>
</head>
<body id="dt_example">
<?php
   $DOCUMENTO = $_GET["documento"];
   $id = $_GET["id"];
   $noaplica = "N/A";
?> 		

    <p>&nbsp;</p>
    <table border="0">
        <tr>
            <td width="10%" valign="top">
                <br>
            </td> 
<?php
   $consulta = mysql_query("select * 
                                  from tercero 
                                  where documento_tercero='$DOCUMENTO'");
   $num = mysql_num_rows($consulta);


   if ($num <> 1)
   {
       ?>

               <TABLE align="center" border="0">
                   <TR>
                       <TD align="center"><font size="+1" face="Verdana">El Documento No Existe en el Sistema</font></TD> 
                   </TR>
               </TABLE>

       <?php
   }
   else
   {
       $row = mysql_fetch_array($consulta, MYSQL_BOTH);
       $idtercero = $row["idtercero"];
       $tipo_identificacion_tercero = $row["tipo_identificacion_tercero"];

       $tipo_identificacion = new tipo_identificacion();

       $cond = "idtipo_identificacion=$tipo_identificacion_tercero";

       $res5 = $tipo_identificacion->consultar($cond);
       $fil = mysql_fetch_assoc($res5);
       $idtipo_identificacion = $fil["idtipo_identificacion"];
       $nombre_tipo_identificacion = $fil["nombre_tipo_identificacion"];

       $tercero_tipo = new tercero_tipo();

       $cond = "tercero_idtercero=$idtercero";
       $res = $tercero_tipo->consultar($cond);
       $fila = mysql_fetch_assoc($res);
       $idtipotercero = $fila["tipo_tercero_idtipo_tercero"];
       ?>     

               <td width="80%" valing="top" >
                   <table border="0" width="100%">
                       <tr>
                           <td>	 

                               <FORM name="formulario" onSubmit="return validar(this)" action="#" method="post" >
                                   <input type=hidden name=idtercero value="<?= $row['idtercero'] ?>" >
                                   <input type=hidden name=idtipotercero value="<?= $idtipotercero ?>" >

                                   <TABLE width="100%" border="1" cellpadding="4" cellspacing="4" bgcolor="#FFFFFF" >
                                       <TR>
                                           <TD colspan="1"><font size="1" face="Verdana"><strong>DOCUMENTO</strong></font><br>
                                               <input disabled type="text" size="10" maxlength="10" value="<?php echo $row["documento_tercero"] ?>">
                                               <input type="hidden" size="10" maxlength="10" value="<?php echo $row["documento_tercero"] ?>" name="DOCUMENTO">                        </TD>

                                           <TD><font size="1" face="Verdana"><strong>TIPO DE DOCUMENTO</strong></font><br>
                                               <select name="TIPO_DOCUMENTO" size="1">

       <?php
       $consulta = mysql_query("select idtipo_identificacion, nombre_tipo_identificacion 
                                          from tipo_identificacion");
       $selected = "";
       while ($row2 = mysql_fetch_array($consulta, MYSQL_BOTH))
       {
           if ($idtipo_identificacion == $row2["idtipo_identificacion"])
               $selected = "selected";
           ?>


                                                       <option <?= $selected ?>  value=<?php echo $row2["idtipo_identificacion"] ?>><?php echo $row2["nombre_tipo_identificacion"] ?></option>
           <?php
           $selected = "";
       }
       ?>
                                               </select>			</TD>
                                       </TR>

                                       <TR>
       <?
       $colspan = "colspan=1";
       if ($idtipotercero != 4 && $idtipotercero != 3)
       {
           ?>
                                               <TD><font size="1" face="Verdana"><strong>APELLIDOS</strong></font><br>
                                                   <input type="text" size="30" maxlength="30" value="<?php echo $row["apellidos_tercero"] ?>" name="APELLIDOS">								       		</TD>
           <?
           $colspan = "colspan=1";
       }
       else
       {
           $colspan = "colspan=2";
           ?>	
                                           <input type="hidden" name="APELLIDOS" value="<?= $noaplica ?>">	
                                               <?
                                           }
                                           ?>
                                       <TD <?= $colspan ?> ><font size="1" face="Verdana"><strong>NOMBRES</strong></font><br>
                                           <input type="text" size="30" maxlength="30" value="<?php echo $row["nombres_tercero"] ?>" name="NOMBRES">			</TD>
                                       </TR>

                                       <TR>
       <?
       $colspan = "colspan = 1";
       if ($idtipotercero != 4 && $idtipotercero != 3)
       {
           ?>    

                                               <TD><font size="1" face="Verdana"><strong>SUCURSAL</strong></font><br>
                                                   <select name="SUCURSAL" size="1">
           <?php
           $consulta2 = mysql_query("select idsucursal, nombre_sucursal 
                                          from sucursal");
           $selected = "";
           while ($row2 = mysql_fetch_array($consulta2, MYSQL_BOTH))
           {

               if ($row["sucursal_idsucursal"] == $row2["idsucursal"])
                   $selected = "selected";
               ?>

                                                           <option <?= $selected ?> value=<?php echo $row2["idsucursal"] ?>><?php echo $row2["nombre_sucursal"] ?></option>
               <?php
               $selected = "";
           }
           ?>
                                                   </select>			</TD>
                                                       <?
                                                       $colspan = "colspan=1";
                                                   }else
                                                   {
                                                       $colspan = "colspan=2";
                                                       ?>	
                                           <input type="hidden" name="SUCURSAL" value="1">	
                                               <?
                                               /* Se asignó 1 para sucursal para que no haya problema de clave foranea. 
                                                 Aqui idsucursal es un dato muerto o quemado
                                                */
                                           }
                                           ?>
                                       <TD <?= $colspan ?>><font size="1" face="Verdana"><strong>DIRECCION</strong></font><br>
                                           <input type="text" size="40" maxlength="40" value="<?php echo $row['direccion_tercero'] ?>" name="DIRECCION">											   			</TD>
                                       </TR>
       <?
       $colspan = 1;
       if ($idtipotercero != 1 && $idtipotercero != 6 && $idtipotercero != 2 && $idtipotercero != 5 && $idtipotercero != 4 && $idtipotercero != 3) // Cliente, Vendedor, usuario,  mensajero , aliado
       {
           $colspan = 2;
       }
       ?>			
                                       <TR>
                                           <TD colspan=<?= $colspan ?> ><font size="1" face="Verdana"><strong>TELEFONO 1</strong></font><font size="1" face="Verdana">(*)</font><br>
                                               <input type="text" size="12" maxlength="7" value="<?php echo $row['telefono_tercero'] ?>" name="TELEFONO">			</TD>
       <?
       if ($idtipotercero == 1 || $idtipotercero == 6 || $idtipotercero == 2 || $idtipotercero == 5 || $idtipotercero == 4 || $idtipotercero == 3)// Cliente, Vendedor, usuario,  mensajero, Punto de Venta, Aliado
       {
           ?>

                                               <TD colspan="1"><font size="1" face="Verdana"><strong>TELEFONO 2</strong></font><font size="1" face="Verdana">(*)</font><br>
                                                   <input type="text" size="12" maxlength="11" value="<?php echo $row['telefono2_tercero'] ?>" name="TELEFONO2">			</TD>
       <? } ?>			
                                       </TR>


                                       <TR>
                                           <TD  colspan=<?= $colspan ?> ><font size="1" face="Verdana"><strong>CELULAR</strong></font><font size="1" face="Verdana">(*)</font><br>
                                               <input type="text" size="15" maxlength="11" value="<?php echo $row["celular_tercero"] ?>" name="CELULAR">			</TD>
       <?
       if ($idtipotercero == 1 || $idtipotercero == 6 || $idtipotercero == 2 || $idtipotercero == 5 || $idtipotercero == 4 || $idtipotercero == 3) // Cliente, Vendedor, usuario,  mensajero, Punto de Venta, aliado
       {
           ?>            
                                               <TD colspan="1"><font size="1" face="Verdana"><strong>CORREO ELECTRONICO</strong></font><font size="1" face="Verdana">(*)</font><br>
                                                   <input type="text" size="30" maxlength="30" value="<?php echo $row["email_tercero"] ?>" name="CORREO">			</TD>
       <? } ?>           
                                       </TR>

       <?
       if ($idtipotercero == 1 || $idtipotercero == 6 || $idtipotercero == 2 || $idtipotercero == 5) // Cliente, Vendedor, usuario,  mensajero 
       {
           ?>                
                                           <TR>
                                               <TD colspan="2"><font size="1" face="Verdana"><strong>USUARIO</strong></font><font size="1" face="Verdana">(*)</font><br>
                                                   <input type="text" name="USUARIO" id="USUARIO" value="<?php echo $row["usuario_tercero"] ?>">                   </TD>
                                           </TR>
       <? } ?>		



       <?
       if ($idtipotercero == 1) // SI ES CLIENTE
       {
           ?><TR>
                                               <TD colspan="2">

                                                   <font size="1" face="Verdana"><strong>VENDEDOR</strong></font><font size="1" face="Verdana"></font><br>
                                                   <select name="VENDEDOR" size="1">
           <?php
           $tercero_idvendedor = $row['tercero_idvendedor'];
           $consulta = mysql_query("select idtercero,nombres_tercero, apellidos_tercero from tercero,tercero_tipo
                                          where tercero_tipo.tercero_idtercero=tercero.idtercero
                                          and tercero_tipo.tipo_tercero_idtipo_tercero=6");
           while ($row3 = mysql_fetch_array($consulta, MYSQL_BOTH))
           {
               if ($row3["idtercero"] == $tercero_idvendedor)
                   $selected = "selected";
               ?>
                                                           <option <?= $selected ?> value=<?= $row3["idtercero"] ?> ><?php echo $row3["nombres_tercero"] . " " . $row3["apellidos_tercero"] ?></option>
                                                           <?php
                                                           $selected = "";
                                                       }
                                                       ?>
                                                       <? if ($tercero_idvendedor == 1) $selected = "selected"; ?>
                                                       <option <?= $selected ?> value=1>Ninguno</option>
                                                   </select>		</TD>    </TR>          
       <? } //fin de if ($idtipotercero == 1) // SI ES CLIENTE 
       ?>	
                                       <?
                                       if ($idtipotercero == 6 || $idtipotercero == 4) // SI ES VENDEDOR o Punto de venta
                                       {
                                           ?>

                                           <TR>
                                               <TD colspan="2" valign="top"><font size="1" face="Verdana"><strong>COMISION %</strong></font><font size="1" face="Verdana">&nbsp;</font><br>
                                                   <input type="text" size="30" maxlength="30" value="<?php echo $row['comision_tercero'] ?>" name="COMISION">
                                               </TD>
                                           </TR>   <? } //fin de if ($idtipotercero == 6) // SI ES VENDEDOR 
                            ?>
                                       <?
                                       if ($idtipotercero == 3) // SI ES aliado
                                       {
                                           ?>
                                           <tr><td>
                                                   <input type="button" value="Agregar Ciudad" onClick="javascript:ciudadadd()">

                                                   <div id="ciudad">
                                                       <font size="1" face="Verdana"><strong>CIUDAD</strong></font><br>  
                                                       <select id="ciudad1" name="ciudad1" size="1">   
           <?php
           $consulta = mysql_query("SELECT ciudad.idciudad, ciudad.nombre_ciudad, ciudad.departamento_iddepartamento, departamento.iddepartamento, departamento.nombre_departamento
                                                            FROM ciudad, departamento
                                                            WHERE ciudad.departamento_iddepartamento = departamento.iddepartamento
                                                            ORDER BY ciudad.nombre_ciudad");
           while ($row = mysql_fetch_assoc($consulta))
           {
               ?>
                                                               <option value=<?php echo $row["idciudad"] ?>><?php echo $row["nombre_ciudad"] . " - " . $row["nombre_departamento"] ?></option><?php
                                                           }
                                                           ?></select></div>
                                                   <div id="nuevasciudades"></div>

           <?php
           $consulta2 = mysql_query("SELECT idciudad FROM ciudad_tercero ct  WHERE ct.`idtercero` =  $id");
           if ($row = mysql_fetch_assoc($consulta2))
           {
               $idCiu = $row["idciudad"];
               echo("<script language='javascript'>
                                                                   document.getElementById('ciudad1').value=$idCiu;
                                                                   </script>");
           }
           //agrego las demas =)
           while ($row = mysql_fetch_assoc($consulta2))
           {
               $idCiu = $row["idciudad"];
               echo("<script language='javascript'>
                                                                   ciudadadd($idCiu);
                                                                   </script>");
           }
       } //fin de if ($idtipotercero == 6) // SI ES aliado 
       ?>
                                           </td></tr>

                                       <TR>
                                           <TD valign="top">
                                               <font size="1" face="Verdana"><strong>OBSERVACIONES</strong></font><font size="1" face="Verdana">(*)</font></TD>
                                           <TD COLSPAN="1" align="left">
                                               <textarea name="OBSERVACIONES" cols="50" rows="6"><?php echo $row["observaciones_tercero"] ?></textarea></TD>
                                       </TR>
                                       <TR>
                                           <TD COLSPAN="2" align="right">
                                               <input name="INSERTAR" type="submit" value="Modificar">			</TD>
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

       <br>
       <?php
   }

   // finalizar("../../");
?>

</body>
</html>
