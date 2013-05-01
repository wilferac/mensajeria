<?php

  header("Content-Type: text/html;charset=utf-8");
  session_start();
  include '../../../security/User.php';
  include '../../../clases/Mensajero.php';
  include '../../../clases/DaoMensajero.php';
  include '../../../clases/Guia.php';
  include '../../../clases/DaoGuia.php';
  include '../../../clases/Manifiesto.php';
  include '../../../clases/DaoManifiesto.php';
  include '../../../clases/Ciudad.php';
  include '../../../clases/DaoCiudad.php';
  include '../../../clases/Sucursal.php';
  include '../../../clases/DaoSucursal.php';
  include '../../../clases/Aliado.php';
  include '../../../clases/DaoAliado.php';
  // include '../../../conexion/conexion.php';

  $objUser = unserialize($_SESSION['currentUser']);


  if ($objUser->getStatus() != 1)
  {
      //$operacion->redireccionar('No Puede entrar', 'index.php');
      return;
  }

  $option = $_REQUEST['option'];

  switch ($option)
  {
      case 0:
          showOption();
          break;
      case 1:
          showData($objUser);
          break;
      //add
      case 2:
          addGuia($objUser);
          break;
      //del
      case 3:
          delGuia();
          break;
      //guardar
      case 4:
          guardar($objUser);
          break;
      //mostrar ingresar las guias
      case 5:
          showLastData();
          break;
      //agrego guia en un rango
      case 6:
          addRango();
          break;
  }

  function showOption()
  {
      $daoCiudad = new DaoCiudad();
      $arreCiudades = $daoCiudad->getAll();

      echo('<h3>Ciudad:</h3>');

      echo("<select id='selCiudad'>");
      echo("<option value='-1'>Seleccione</option>");
      foreach ($arreCiudades as $objCiu)
      {
          $id = $objCiu->getId();
          $idDep = $objCiu->getIdDepartamento();
          //$objMen->show();
          echo("<option value='$id' label='$idDep'>");
          echo($objCiu->getNombre() . " (" . $objCiu->getNomDepartamento() . ")");
          echo("</option>");
      }
      echo("</select>");

      echo("<script type='text/javascript'>
          $(document).ready(function() {
            $('#selCiudad').change(function(event) {
            var el = document.getElementById('selCiudad');
            var text = el.options[el.selectedIndex].label;
            var idCiu= el.value;
            //alert(text+' '+idCiu);
            //return;
            $('#response2').load('addManiCiudad.php?option=1&idDep='+text+'&idCiu='+idCiu);
            $('#response3').html('');
            $('#response4').html('');
            });
          });
          </script>");

      //resteo el arreglo de guias :D
      $arreGuias = new ArrayObject();
      $_SESSION['arregloGuias'] = serialize($arreGuias);
  }

  //lo pongo a escojer entre destajo y propio
  //filtro con u objeto del tipo usuario
  function showData($objUser)
  {
      $idDep = $_REQUEST['idDep'];

      // echo("consultando aliados y sucursales");
      //busco solo mensajeros de la casa (propios)
      $tipo = 5;
      // el id de la sucursal
      $idSucur = $objUser->getIdSucursal();
      $idCiu = $objUser->getIdCiudad();

      $daoMen = new DaoMensajero();
      $arrayMensajeros = $daoMen->getAll($idSucur, $tipo);

      echo("<table><tr><td>");


      echo("Mensajero que Entrega: </td><td> <select id='selMensajeroEntrega'>");
      echo("<option value='-1'>Seleccione</option>");
      foreach ($arrayMensajeros as $objMen)
      {
          $idMen = $objMen->getId();
          //$objMen->show();
          echo("<option value='$idMen'>");
          echo($objMen->getNombre());
          echo("</option>");
      }
      echo("</select></td></tr><tr><td>");

      echo("<h3>Peso</h3>");
      echo('<input name="peso" type="text" id="peso" size="5" require/> ');

      $daoSucur = new DaoSucursal();
      $arreSucurs = $daoSucur->getAll($idDep);


      echo('<h3>Sucursales:</h3>');

      echo("<select id='selSucursal' onchange='selDestino(1)'>");
      echo("<option value='-1'>Seleccione</option>");



      foreach ($arreSucurs as $obj)
      {
          $id = $obj->getId();

          //$objMen->show();
          echo("<option value='$id' >");
          echo($obj->getNombre());
          echo("</option>");
      }
      echo("</select>");



      $idCiu = $_REQUEST['idCiu'];

      $daoAli = new DaoAliado();
      $arreAlis = $daoAli->getAll($idCiu);

      //aca van los aliados :O
      echo('<h3>Aliados:</h3>');

      echo("<select id='selAli' onchange='selDestino(2)'>");
      echo("<option value='-1'>Seleccione</option>");
      foreach ($arreAlis as $obj)
      {
          $id = $obj->getId();

          //$objMen->show();
          echo("<option value='$id' >");
          echo($obj->getNombre());
          echo("</option>");
      }
      echo("</select>");

      $arreGuias = new ArrayObject();
      $_SESSION['arregloGuias'] = serialize($arreGuias);
  }

  function showLastData()
  {

      $idAli = $_REQUEST['idAli'];
      $idSucur = $_REQUEST['idSucur'];

      if ($idSucur != -1)
      {
          //muestro los mensajeros de la sucursal :D
          $daoMen = new DaoMensajero();
          //busco los mensajeros de la sucursal seleccionada , tipo = 5 (mensajero)
          $arrayMensajeros = $daoMen->getAll($idSucur, 5);

          echo("Mensajero que Recibe: 
              <select id='selMensajeroResibe'>");
          echo("<option value='-1'>Seleccione</option>");
          foreach ($arrayMensajeros as $objMen)
          {
              $idMen = $objMen->getId();
              //$objMen->show();
              echo("<option value='$idMen'>");
              echo($objMen->getNombre());
              echo("</option>");
          }
          echo("</select>");
      }

      //muestro la parte de guias consecutivas 
      echo('<h3>Rango de Guias.</h3>');
      echo('<input name="rango1" type="text" id="rango1" size="10" require/> ');
      echo('<input name="rango2" type="text" id="rango2" size="10" require/> ');
      echo("<button class='btnAgregarRango'  style=' width: 90px;'  onclick='agregarRango()' >Agregar</button>");

      echo('<h3>Guia N.</h3>');

      echo('<input name="txtGuiaCiu" type="text" id="txtGuiaCiu" size="10" require/> ');

      echo("<script type='text/javascript'>$('#txtGuiaCiu').keypress(function(event) {
                    if(event.keyCode.toString()== '13')
                    {
                        var guiaNum = document.getElementById('txtGuiaCiu').value;
                        if(guiaNum == '')
                        {
                            return;
                        }
                        var el = document.getElementById('selCiudad');
                        var idDep = el.options[el.selectedIndex].label;

                        document.getElementById('txtGuiaCiu').value='';
                        event.preventDefault();
                        //alert('se '+guiaNum);
                        
                       $('#response3').load('addManiCiudad.php?option=2&numGuia='+guiaNum+'&idDep='+idDep);
                    }
                });
                nguias=0;
                </script>");

      echo("<br /><br />");

//onclick='guardar($tipo);'
      echo("<h2 align=center>");
      echo("<button class='btnGuardar' id='btnGuardarManiCiudad' style=' width: 90px;' onclick='guardarManiCiudad()' >Guardar</button>");
      echo("</h2>");
      //resteo el arreglo de guias :D
      $arreGuias = new ArrayObject();
      $_SESSION['arregloGuias'] = serialize($arreGuias);
  }

  function addRango()
  {
      //recupero el arreglo de guias
      $arreGuias = unserialize($_SESSION['arregloGuias']);
      $r1 = $_REQUEST['r1'];
      $r2 = $_REQUEST['r2'];

      $idDep = $_REQUEST['idDep'];

      //echo($r1 . " - " . $r2);

      $daoGuia = new DaoGuia();
      for ($cont = $r1; $cont <= $r2; $cont++)
      {
          if ($daoGuia->checkManifiesto($cont, $idDep))
          {
              $arreGuias[$cont] = $cont;
              //le sumo uno a las guias
              echo("<script type='text/javascript'>
             nguias++;
                  </script>");
          }
          else
          {
              echo("La guia numero $cont no se pudo agregar<br>");
          }
      }
      showGuias($arreGuias);
  }

  function addGuia($objUser)
  {
      //recupero el arreglo de guias
      $arreGuias = unserialize($_SESSION['arregloGuias']);
      $num = $_REQUEST['numGuia'];
      $idDep = $_REQUEST['idDep'];

      $daoGuia = new DaoGuia();
      //valido si la puedo agregar, pasando el departamento al q la voy a enviar
      if ($daoGuia->checkManifiesto($num, $idDep))
      {
          $last = sizeof($arreGuias);

          $arreGuias[$num] = $num;

          showGuias($arreGuias); //refresco

          echo("<script type='text/javascript'>
             nguias++;
                  </script>");
      }
      else
      {
          echo("<script type='text/javascript'>
             alert('La guia $num no puede ser asignada a este manifiesto');
                  </script>");
          showGuias($arreGuias);
      }
  }

//se reutiliza la funcion de addMainMensajero :D
//  function delGuia()
//  {
//
//      $num = $_REQUEST['numGuia'];
//
//      //recupero el arreglo de guias
//      $arreGuias = unserialize($_SESSION['arregloGuias']);
//      unset($arreGuias[$num]);
//      //echo("xD");
//      //var_dump($arreGuias);
//      //++ quitar una guia del arreglo
//      showGuias($arreGuias); //refresco
//  }
  //funcion para mostrar las guias que tengo acumuladas.
  //no recupera el arreglo de guias ya que de eso se encargan add y del
  function showGuias($arreGuias)
  {
      //guardo las guias q llevo
      $_SESSION['arregloGuias'] = serialize($arreGuias);

      echo("<h2>Numero de guias:<b> " . sizeof($arreGuias) . "</b></h2>");

      echo("<table>");
      foreach ($arreGuias as $numero)
      {
          echo("<tr>");
          echo("<td>$numero</td>");
          echo("<td><a onclick='quitar(\"$numero\");'>Quitar</a></td>");
          echo("</tr>");
      }
      echo("</table>");
  }

  //funcion para crear el manifiesto en la BD
  function guardar($objUser)
  {
      $idMen = $_REQUEST['idMensajero'];
      $peso = $_REQUEST['peso'];
      $idCiuDesi = $_REQUEST['idDesti'];
      $arreGuias = unserialize($_SESSION['arregloGuias']);

      $idCreador = $objUser->getId();
      $idCiuOri = $objUser->getIdCiudad();
      
      
      $idAli = $_REQUEST['idAli'];
      $idSucur = $_REQUEST['idSucur'];
      $idMenResibe = $_REQUEST['idMenResibe'];

      //echo($idMen." ali ".$idAli." sucur ".$idSucur." menResibe ".$idMenResibe);

      if ($idAli == -1)
      {
          $idAli = NULL;
      }
      if ($idSucur == -1)
      {
          $idSucur = NULL;
      }
      if ($idMenResibe == -1)
      {
          $idMenResibe = NULL;
      }


      $objManifiesto = new Manifiesto(-1, $idSucur, $idCreador, 0, NULL, NULL);
      $objManifiesto->peso = $peso;
      $objManifiesto->setIdCiuDesti($idCiuDesi);
      $objManifiesto->setIdCiuOri($idCiuOri);

      $daoMani = new DaoManifiesto();


      $objManifiesto->setTerceros($idAli, $idMen, $idMenResibe);

      $daoMani->insertar($objManifiesto, $arreGuias);
  }

?>
