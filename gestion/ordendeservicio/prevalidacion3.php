<?php

   session_start();
   include ("../../conexion/conexion.php");

   if ($_REQUEST)
   {

       $datoArecordar = $_REQUEST['datoArecordar'];
       $encDestinatario = false;


       if ($datoArecordar != "")
       {
           $query = "SELECT * FROM destinatario WHERE  datos1 = '$datoArecordar' OR datos2 = '$datoArecordar'";

           $results = mysql_query($query) or die(mysql_error());
           $datosDestinatario = mysql_fetch_assoc($results);

           if (mysql_num_rows($results) > 0)
               $encDestinatario = true;

           if ($encDestinatario)
           {
               $iddestinatario = $datosDestinatario["iddestinatario"];
               $documento_tercero = $datosDestinatario["documento_destinatario"];
               $nombres_tercero = $datosDestinatario["nombres_destinatario"];
               $apellidos_tercero = $datosDestinatario["apellidos_destinatario"];
               $direccion_tercero = $datosDestinatario["direccion_destinatario"];
               $telefono_destinatario = $datosDestinatario["telefono_destinatario"];
               $celular_destinatario = $datosDestinatario["celular_destinatario"];

               echo "<script>
ElementosDestinatariosVisibles(true,'$documento_tercero','$nombres_tercero','$apellidos_tercero','$direccion_tercero','$telefono_destinatario','$celular_destinatario');
//muestro la capa de datos de peso			
document.getElementById('capaPeso').style.visibility='visible';
//muestro la info extra
document.getElementById('labExtraDestinatario').style.visibility='visible';
                                        document.getElementById('extraDestinatario').style.visibility='visible';
			document.getElementById('direcciondestinatario').focus();
			//document.getElementById('direcciondestinatario').style.color='#00FA00';
			</script>
			 <input type='hidden' id='encDestinatario' name='encDestinatario' value='$encDestinatario'></input>
			 
			 <input type='hidden' id='iddestinatario' name='iddestinatario' value='$iddestinatario'></input>
			 <input type='hidden' id='nombres_terceroOrig' name='nombres_terceroOrig' value='$nombres_tercero'></input>
			 <input type='hidden' id='apellidos_terceroOrig' name='apellidos_terceroOrig' value='$apellidos_tercero'></input>
			 <input type='hidden' id='direccion_terceroOrig' name='direccion_terceroOrig' value='$direccion_tercero'></input>
			 <input type='hidden' id='telefono_destinatarioOrig' name='telefono_destinatarioOrig' value='$telefono_destinatario'></input>
			 <input type='hidden' id='celular_destinatarioOrig' name='celular_destinatarioOrig' value='$celular_destinatario'></input>
			 
			";
           }
           else
           {
               echo "	
				<script>
					ElementosDestinatariosVisibles(false,'','','','','','');
                                        document.getElementById('labExtraDestinatario').style.visibility='visible';
                                        document.getElementById('extraDestinatario').style.visibility='visible';
					document.getElementById('ccdestinatario').focus();
                                        document.getElementById('capaPeso').style.visibility='visible';
				</script>
				<input type='hidden' id='encDestinatario' name='encDestinatario' value='$encDestinatario'></input>
				<!-- <a target='_blank' href='../terceros/addCliente.php?nombre=tercero&cc=$datoArecordar' onClick='return wo(this);'> 
				  Agregar Nuevo Cliente
				  </a>-->
				";
           }
       } // if numguia != ""
   } // if REQUEST
?>