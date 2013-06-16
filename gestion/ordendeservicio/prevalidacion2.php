<?php

   session_start();
   include ("../../conexion/conexion.php");

   if ($_REQUEST)
   {

       $cccliente = $_REQUEST['cccliente'];
       $encCliente = false;


       if ($cccliente != "")
       {
           $query = "SELECT * FROM tercero WHERE  documento_tercero = '$cccliente'";

           $results = mysql_query($query) or die(mysql_error());
           $datosCliente = mysql_fetch_assoc($results);

           if (mysql_num_rows($results) > 0)
               $encCliente = true;

           if ($encCliente)
           {
               $idtercero = $datosCliente["idtercero"];
               $documento_tercero = $datosCliente["documento_tercero"];
               $nombres_tercero = $datosCliente["nombres_tercero"];
               $apellidos_tercero = $datosCliente["apellidos_tercero"];
               $direccion_tercero = $datosCliente["direccion_tercero"];

               echo "<script>  document.getElementById('capadatosguia').style.visibility='visible';
                          document.getElementById('labExtraRemitente').style.visibility = 'visible';
                            document.getElementById('extraRemitente').style.visibility = 'visible';
                            ElementosClientesVisibles(true,'$idtercero','$documento_tercero','$nombres_tercero','$apellidos_tercero','$direccion_tercero');
                              document.getElementById('savetemp').style.visibility='hidden';
                            </script>";

//		echo "<script>
//					
//					ElementosClientesInvisibles();
//					ElementosDatosABuscarDestinatarioInvisibles();						
//					ElementosDestinatariosInvisibles();
//												
//					// ELEMENTOS DEL CLIENTE VISIBLES	
//		ElementosClientesVisibles(true,'$idtercero','$documento_tercero','$nombres_tercero','$apellidos_tercero','$direccion_tercero');
//				ElementosDatosABuscarDestinatarioVisibles();
//										
//					// ELEMENTOS DEL DESTINATARIO VISIBLES
//					
//					document.getElementById('datoArecordar').focus();
//					</script>
//					<input type='hidden' id='encCliente' name='encCliente' value='$encCliente'></input>";
           }
           else
           {
               echo "<script>
					document.getElementById('labnombrescliente').style.visibility='hidden';
					document.getElementById('nombrescliente').style.visibility='hidden';
					document.getElementById('nombrescliente').value = '';
									
					document.getElementById('labapellidoscliente').style.visibility='hidden';
					document.getElementById('apellidoscliente').style.visibility='hidden';
					document.getElementById('apellidoscliente').value = '';
					
					document.getElementById('labdireccioncliente').style.visibility='hidden';
					document.getElementById('direccioncliente').style.visibility='hidden';
					document.getElementById('direccioncliente').value = '';
                                        document.getElementById('savetemp').style.visibility='hidden';
                                        ElementosDatosABuscarDestinatarioInvisibles();
				</script>
				 <a id='AgregarNuevoCliente' name='AgregarNuevoCliente' target='_blank' href='../terceros/addCliente.php?nombre=tercero&cc=$cccliente' onClick='return wo(this);'>
				  Agregar Nuevo Cliente
				  </a>
				  <script> document.getElementById('AgregarNuevoCliente').focus(); </script>
				  <input type='hidden' id='encCliente' name='encCliente' value='$encCliente'></input>
				";
           }
       } // if numguia != ""
   } // if REQUEST
?>