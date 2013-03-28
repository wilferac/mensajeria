<? 
 include("../clases/clases.php");

	if ( isset($_GET["id"]) )
		{
		$id=$_GET["id"];
		$categorias=new categorias();
		$categorias->id=$id;
			if ( $categorias->eliminar() )
				{
				?>
                <script language="javascript" type="text/javascript">
				var mensaje="Eliminación Exitosa";
				window.location.href='categorias.php?mensaje='+mensaje;
				</script>
                <?
				}else
				{
				?>
                <script language="javascript" type="text/javascript">
				var mensaje="Eliminación No Exitosa";
				window.location.href='categorias.php?mensaje='+mensaje;
				</script>
                <?
				}
		}

?>