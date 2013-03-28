<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Gestion del Sistema</title>
<link href="css/default.css" rel="stylesheet" type="text/css">

		<style type="text/css" title="currentStyle">
			@import "media/css/demo_page.css";	
		</style>
       
        <script language="javascript">
  parent.frames[0].document.getElementById("a1").innerHTML = "";
 parent.frames[0].document.getElementById("a2").innerHTML = "";
 parent.frames[0].document.getElementById("a3").innerHTML = "";
 
 parent.frames[0].document.getElementById("s1").style.visibility = "hidden";
 parent.frames[0].document.getElementById("s2").style.visibility = "hidden";
 
 
 parent.frames[0].document.getElementById("a1").innerHTML = "Principal";
 parent.frames[0].document.getElementById("a1").href = "redireccionador.php";
 
</script>
 		<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
        <link href="SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css">
</head>
<body id="dt_example">
<?

 include("clases/clases.php");

 $operacion = new operacion();
if (!isset($_SESSION["ingreso"]))
 {

 $operacion->redireccionar("No Puede entrar","index.php");
 exit();
}

 $operacion -> menu();

?>


<br>
<div class="marco">
		<p>&nbsp;</p>
            	<span class="class_login" style="font-size:14px">PROCESOS
                </span>
             </p>

 <table width="70%" border="0"  align="center"  cellpadding="10">
	<tr>
			<td valign="middle" bgcolor="#DEDEDE" align="center" width="300">
            <div style="margin-left:4px">
                	<a class="class_login" style="text-decoration: none; font-size: 20px;" href="gestion/ordendeservicio/consulta.php"><img src="imagenes/green.gif" border="0">
   	  Digitaci&oacute;n de gu&iacute;a                    </a>             </div>            </td>
           <td valign="middle" bgcolor="#DEDEDE" align="center" width="300">
            <div style="margin-left:4px">
                	<a class="class_login" style="text-decoration: none; font-size: 20px;" href="gestion/manifiesto/consulta.php"><img src="imagenes/green.gif" border="0">
                	 Manifiestos                    </a>             </div>            </td>
           <td valign="middle" bgcolor="#DEDEDE" align="center" width="300">
            <div style="margin-left:4px">
                	<a class="class_login" style="text-decoration: none; font-size: 20px;" href="gestion/informes/tiposproducto.php"><img src="imagenes/green.gif" border="0">
               	    Informes                   </a>             </div>      </td>
	</tr>
	
	<tr>
			<td colspan="3" valign="middle" bgcolor="#DEDEDE" align="center">
            <div style="margin-left:4px">
                	<a class="class_login" style="text-decoration: none; font-size: 20px;" href="gestion/guia/buscar.php"><img src="imagenes/green.gif" border="0">
                	 Consultar gu&iacute;as                    </a>             </div>            </td>
     </tr>
  </table>
 <p>&nbsp;</p>
 <table width="70%" border="0"  align="center"  cellpadding="10">

   <tr>
     <td width="900" align="center" valign="middle" bgcolor="#DEDEDE"><div style="margin-left:4px"> <a class="class_login" style="text-decoration: none; font-size: 20px;" href="gestion/guia/buscar.php"><img src="imagenes/green.gif" border="0"></a><a class="class_login" style="text-decoration: none; font-size: 20px;" href="gestion/ordendeservicio/addosunitario.php"> Ordenes de servicio unitario
     </a></div></td>
   </tr>
 </table>
 <p>&nbsp;</p>
 <p>&nbsp;</p><p>&nbsp;</p>
</div>

<script type="text/javascript">
<!--
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown:"SpryAssets/SpryMenuBarDownHover.gif", imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
//-->
</script>
</body>
</html>
