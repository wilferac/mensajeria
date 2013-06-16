<?php
//session_start();
   require("../../clases/clases.php");
   require("../../libreria/libreria.php");
?>	
<html>
    <head>
        <title>Sistema de Mensajeria</title>
    </head>
    <body>


        <?php
           $numtab = $_POST["numtab"];
           $DOCUMENTO = $_POST['DOCUMENTO' . $numtab];
           if (isset($_POST['APELLIDOS' . $numtab]))
               $APELLIDOS = $_POST['APELLIDOS' . $numtab];
           else
               $APELLIDOS = "";


           // cabecera($_SESSION['datosinicio']['usuario_tercero'],"Creacion Tercero","../../");  
        ?>

        <br>
        <table width="100%">
            <tr>
                <td width="10%" valign="top">
                    <?php
                       // menu_gestion("../../","menu_terceros.php");
                    ?>

                </td>

                <td width="80%" align="center">
                    <?php
                       $consulta = mysql_query("select * from tercero where documento_tercero='$DOCUMENTO'");
                       $num = mysql_num_rows($consulta);


                       if ($num > 0)
                       {
                           ?>
                           <br>
                           <TABLE border="0">
                               <TR>
                                   <TD align="center"><font size="+1" face="Verdana"></font></TD> 
                               </TR>
                           </TABLE>
                           <script language="javascript" type="text/javascript">
                               var mensaje = "Registro NO Exitoso: El tercero ya existe en el sistema";
                               alert(mensaje);
                               window.location.href = 'add.php';
                           </script>
                           <br>
                           <?php
                       }
                       else
                       {
                           $noaplica = 'N/A';

                           $tercero = new tercero;
                           $tercero_tipo = new tercero_tipo;

                           $tercero->documento_tercero = $DOCUMENTO;
                           $tercero->sucursal_idsucursal = !empty($_POST["SUCURSAL"]) ? $_POST["SUCURSAL"] : "NULL";
                           $tercero->tipo_identificacion_tercero = $_POST["TIPO_DOCUMENTO"];
                           $tercero->nombres_tercero = $_POST["NOMBRES"];
                           $tercero->apellidos_tercero = $APELLIDOS;
                           $tercero->direccion_tercero = $_POST["DIRECCION"];
                           $tercero->telefono_tercero = $_POST["TELEFONO"];
                           $tercero->celular_tercero = $_POST["CELULAR"];
                           $tercero->observaciones_tercero = $_POST["OBSERVACIONES"];
                           $tercero->tercero_idvendedor = "NULL";
                           //agrego el documento como username
                           $tercero->usuario_tercero = $DOCUMENTO;

                           if ($_POST["tipotercero"] == 2) //Tipo Usuario
                           {
                               $tercero->usuario_tercero = !empty($_POST["USUARIO"]) ? $_POST["USUARIO"] : $DOCUMENTO;
                               $tercero->clave_tercero = md5($_POST["CLAVE"]);
                               $tercero->email_tercero = $_POST["CORREO"];
                               $tercero->telefono2_tercero = $_POST["TELEFONO2"];
                               $tercero->tercero_idvendedor = "NULL";
                               $tercero->comision_tercero = $noaplica;
                           }
                           elseif ($_POST["tipotercero"] == 1) //Tipo Cliente
                           {
                               $tercero->usuario_tercero = !empty($_POST["USUARIO"]) ? $_POST["USUARIO"] : $DOCUMENTO;
                               $tercero->clave_tercero = md5($_POST["CLAVE"]);
                               $tercero->email_tercero = $_POST["CORREO"];
                               $tercero->telefono2_tercero = $_POST["TELEFONO2"];
                               $tercero->tercero_idvendedor = $_POST["VENDEDOR"];
                               $tercero->comision_tercero = $noaplica;
                           }
                           elseif ($_POST["tipotercero"] == 6) //Tipo Vendedor
                           {
                               $tercero->usuario_tercero = !empty($_POST["USUARIO"]) ? $_POST["USUARIO"] : $DOCUMENTO;
                               $tercero->clave_tercero = md5($_POST["CLAVE"]);
                               $tercero->email_tercero = $_POST["CORREO"];
                               $tercero->telefono2_tercero = $_POST["TELEFONO2"];
                               $tercero->tercero_idvendedor = "NULL";
                               $tercero->comision_tercero = $_POST["COMISION"];
                           }
                           elseif ($_POST["tipotercero"] == 5) //Tipo mensajero Interno
                           {
                               $tercero->usuario_tercero = !empty($_POST["USUARIO"]) ? $_POST["USUARIO"] : $DOCUMENTO;
                               $tercero->clave_tercero = md5($_POST["CLAVE"]);
                               $tercero->email_tercero = $_POST["CORREO"];
                               $tercero->telefono2_tercero = $_POST["TELEFONO2"];
                               $tercero->tercero_idvendedor = "NULL";
                               $tercero->comision_tercero = $noaplica;
                           }
                           elseif ($_POST["tipotercero"] == 8) //Tipo mensajero DESTAJO
                           {
                               $tercero->usuario_tercero = !empty($_POST["USUARIO"]) ? $_POST["USUARIO"] : $DOCUMENTO;
                               $tercero->clave_tercero = null;
                               $tercero->email_tercero = $noaplica;
                               $tercero->telefono2_tercero = $noaplica;
                               $tercero->tercero_idvendedor = "NULL";
                               $tercero->comision_tercero = $noaplica;
                           }
                           elseif ($_POST["tipotercero"] == 4) //Tipo Punto de Venta
                           {
                               $tercero->sucursal_idsucursal = "NULL";
                               $tercero->email_tercero = $_POST["CORREO"];
                               $tercero->telefono2_tercero = $_POST["TELEFONO2"];
                               $tercero->comision_tercero = $_POST["COMISION"];
                               $tercero->tercero_idvendedor = "NULL";
                           }
                           elseif ($_POST["tipotercero"] == 3) //Tipo Aliado
                           {
                               $tercero->sucursal_idsucursal = "NULL";
                               $tercero->email_tercero = $_POST["CORREO"];
                               $tercero->telefono2_tercero = $_POST["TELEFONO2"];
                               $tercero->comision_tercero = NULL;
                               $tercero->tercero_idvendedor = "NULL";

                               //print_r($_POST);

                               $ciudad_tercero = new ciudad_tercero();

                               $ciudad1 = $_POST["ciudad1"];

                               if (isset($_POST['ciudad']))
                                   $ciudadesArray = $_POST['ciudad'];
                           }

                           $conex = new conexion();
                           $res3 = NULL;
                           $res4 = NULL;
                           $flag1 = false;
                           $flag2 = false;
                           $exito = false;

                           $qtrans = "SET AUTOCOMMIT=0;";
                           $sac = $conex->ejecutar($qtrans);
                           $qtrans = "BEGIN;";
                           $sac = $conex->ejecutar($qtrans);

                           $res = $tercero->agregar();

                           $tercero_tipo->tercero_idtercero = $tercero->idtercero;
                           $tercero_tipo->tipo_tercero_idtipo_tercero = $_POST["tipotercero"];

                           $res2 = $tercero_tipo->agregar();

                           if (isset($ciudad_tercero))
                           {
                               $ciudad_tercero->idtercero = $tercero->idtercero;
                               $ciudad_tercero->idciudad = $ciudad1;

                               $res3 = $ciudad_tercero->agregar();
                               $flag1 = true;
                               $res4 = false;
                               if (isset($_POST['ciudad']))
                               {
                                   foreach ($ciudadesArray as $codigociudad)
                                   {
                                       $ciudad_tercero->idciudad = $codigociudad;
                                       $res4 = $ciudad_tercero->agregar();
                                       $flag2 = true;
                                   }
                               }
                               else
                               {
                                   $res4 = true;
                                   $flag2 = true;
                               }
                           }
                           //echo $flag1."f1<br>$flag2.f2";

                           if ($flag1 == true && $flag2 == true)
                           {
                               if ($res === true && $res2 === true && $res3 === true && $res4 === true)
                                   $exito = true;
                           }
                           else
                           {
                               if ($res === true && $res2 === true)
                                   $exito = true;
                           }

                           if ($exito === true)
                           {

                               $qtrans = "COMMIT";
                               $sac = $conex->ejecutar($qtrans);
                               ?>			

                               <script language="javascript" type="text/javascript">
                                   var mensaje = "Registro Exitoso";
                                   alert(mensaje);
                                   window.location.href = 'add.php';
                               </script>
                               <?
                           }
                           else
                           {
                               ?>
                               <script language="javascript" type="text/javascript">
                                   var mensaje = "Registro NO Exitoso";
                                   alert(mensaje);
                                   window.location.href = 'add.php';
                                   //window.location.href = 'consulta.php?mensaje=' + mensaje;
                               </script>
                               <?php
                           }
                       }
                    ?>

                </td>

                <td width="10%" valign="top"></td>


        </table>
        <br>
        <?php
           //finalizar("../../");
        ?>

    </BODY>
</HTML>
