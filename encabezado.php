<?
session_start();
include("clases/clases.php");

include 'security/User.php';

	$operacion = new operacion ();
	//codigo antiguo para verificar el logueo :(
//if ($_SESSION['ingreso'] != 'ingresoseguro')
//	$operacion->redireccionar('No Puede entrar','index.php');
        $objUser = unserialize($_SESSION['currentUser']);
        //$objUser = new User();
//        echo($objUser->getStatus());
        if ($objUser->getStatus() != 1)
        {
            //$objUser->show();
            $operacion->redireccionar('No Puede entrar','index.php');
            return;
        }

?>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>:::Sistema de Mensajeria::</title>

<link href="css/default.css" rel="stylesheet" type="text/css">
<!-- END VALIDADOR -->
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.Estilo3 {color: #CCCC00}

-->
</style>

</head>
<body topmargin="0">
<table  width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
<tbody><tr>
<td>
	<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#CC0000">
	<tbody><tr>
	<td  align="center"><!-- <img src="imagenes/logo.jpg" height="120"> --></td>
	<td valign="top" align="right">
	<div style="margin-top:10px; margin-bottom:10px; margin-right:10px">
	  <p><span class="class_login Estilo3">
	    <?=$objUser->getNombre()." ".$objUser->getApellido()." (".$objUser->getLogin().")";?></span>
	        <br>
</p>
	  <p><hr width="150" align="right"></p>
	  <p><a href="gestion/tercero/cambiarcontrasenha.php" target="mainFrame" style="text-decoration:none" ><span class="class_cargo">Cambiar contrase&ntilde;a</span></a>
	        
	       &nbsp;|&nbsp;
	        <a  href="salir.php" onClick="parent.parent.location.href=this.href" style="text-decoration:none"><span class="class_cargo">Salir</span></a>	      </p>
	</div></td>

	</tr>
	</tbody></table>
</td>
</tr>
<tr class="class_tr" bgcolor="#0099FF">
<td>
    	<table width="100%" border="0" cellpadding="0" cellspacing="0" >

            <tr>

        	<td>
			 <div>
				<p>&nbsp;
                <a id="a1" target="mainFrame" href="" ></a>
                <span id="s1" style="visibility:hidden">&gt;</span>
                <a id="a2" target="mainFrame" href=""  style="text-decoration:none"></a>
                <span id="s2" style="visibility:hidden">&gt;</span>
                <a id="a3" target="mainFrame" href="" style="text-decoration:none"></a>
                </p>
			</div>
            </td>

        </tr>
        </table>	
</td>
</tr>
</tbody></table>
</body></html>
