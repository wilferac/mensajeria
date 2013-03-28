<?php
   /*
    * Esta clase se va a encargar de generar el menu de la aplicacion
    * consulta la variable global de usuario para mostrar los recursos.
    * @require: security/User.php
    */

   //define ('$this->raiz', '/home/inovate/public_html/Mensajeria'); // leuss
//define ('$this->raiz', '/Mensajeria');  // localhost
   //define('$this->raiz', "http://localhost/~inovate/Mensajeria");  // Innovate

   class Menu
   {

       private $raiz;
       private $user;

       public function __construct($user)
       {
           $this->user = $user;
           //determino la raiz de la aplicacion =)
           $this->raiz = "http://localhost/~inovate/Mensajeria";
       }

       public function generarMenu()
       {
           ?>
           <script src="<?= $this->raiz ?>/SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
           <link href="<?= $this->raiz ?>/SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css">

           <div id="menu" style=" margin-left:auto; margin-right:auto; width:70%; clear: both;">
               <ul id="MenuBar1" class="MenuBarHorizontal" style="background-color:#FFFF00" >
                   <li>
                       <a  href="<?= $this->raiz ?>/redireccionador.php">::Inicio::</a>
                   </li>
                   <?php
                   if ($this->user->checkRol("Admin"))
                   {
                       ?>


                       <li><a class="MenuBarItemSubmenu" style="text-decoration:none">Gesti&oacute;n</a>
                           <ul>
                               <li><a  class="MenuBarItemSubmenu" href="#">Terceros</a>
                                   <ul>
                                       <li><a href="<?= $this->raiz ?>/gestion/terceros/consulta.php">Consultar</a></li>
                                       <li><a href="<?= $this->raiz ?>/gestion/terceros/add.php">Crear Tercero</a></li>
                                       <li><a href="<?= $this->raiz ?>/gestion/terceros/addAliaPV.php">Crear Puntos de Venta o Aliados</a></li>
                                   </ul>
                               </li>

                               <li><a  class="MenuBarItemSubmenu" href="#">Sucursales</a>
                                   <ul>
                                       <li><a href="<?= $this->raiz ?>/gestion/sucursal/consulta.php">Consultar</a></li>
                                       <li><a href="<?= $this->raiz ?>/gestion/sucursal/add.php">Crear </a></li>

                                   </ul>
                               </li>
                               <li><a  class="MenuBarItemSubmenu" href="#">Productos</a>
                                   <ul>
                                       <li><a href="<?= $this->raiz ?>/gestion/productos/consulta.php">Consultar</a></li>
                                       <li><a href="<?= $this->raiz ?>/gestion/productos/add.php">Crear </a></li>    
                                   </ul>

                               </li>
                           </ul>
                       </li>

                       <?php
                   }
                   if ($this->user->checkRol("Usuario"))
                   {
                       ?>


                       <li><a href="#"  style="text-decoration:none">Masivo</a>
                           <ul>
                               <li><a class="MenuBarItemSubmenu" href="#">Orden de Servicio</a>
                                   <ul>
                                       <li><a href="<?= $this->raiz ?>/gestion/ordendeservicio/consulta.php">Consultar</a></li>
                                       <li><a href="<?= $this->raiz ?>/gestion/ordendeservicio/add.php">Crear</a></li>
                                   </ul>
                               </li>
                               <li><a class="MenuBarItemSubmenu" href="#">Manifiesto</a>
                                   <ul>
                                       <li><a href="<?= $this->raiz ?>/gestion/manifiesto/consulta.php">Consultar</a></li>
                                       <li><a href="<?= $this->raiz ?>/gestion/manifiesto/add/main.php">Crear</a></li>
                                   </ul>
                               </li>
                               <li><a class="MenuBarItemSubmenu" href="#">Gu&iacute;as</a>
                                   <ul>
                                       <li><a href="<?= $this->raiz ?>/gestion/guia/buscar.php">Consultar</a></li>

                                   </ul>
                               </li>

                               <li><a  href="<?= $this->raiz ?>/gestion/guia/pistoleocausal.php">Pistoleo</a>
                               </li>

                           </ul>

                       </li>



                       <li><a class="MenuBarItemSubmenu" href="#"  style="text-decoration:none">Unitario</a>
                           <ul>

                               <li><a  class="MenuBarItemSubmenu" href="#">Guia</a>
                                   <ul>
                                       <li><a  href="<?= $this->raiz ?>/gestion/ordendeservicio/addosunitario.php">Digitar</a>
                                       </li>
                                       <li><a  href="<?= $this->raiz ?>/gestion/unitario/guia/consulta.php">Consultar</a>
                                       </li>
                                   </ul>
                               </li>

                               <li><a  href="<?= $this->raiz ?>/gestion/guia/asignar.php">Consultar y/o Asignar Gu&iacute;as</a>
                               </li>
                               <li><a  href="<?= $this->raiz ?>/gestion/guia/consultarasignaciones.php">Consultar todas las Asignaciones</a>
                               </li>

                           </ul>
                       </li>
                       <?php
                   }

                   if ($this->user->checkRol("Usuario") or $this->user->checkRol("Admin"))
                   {
                       ?>
                       <li><a class="MenuBarItemSubmenu" href="#"  style="text-decoration:none">Informes</a>
                           <ul>
                               <li><a  href="<?= $this->raiz ?>/gestion/informes/tiposproducto.php">Tipos de Producto</a>
                               </li>
                           </ul>
                       </li>

                       <?php
                   }
                   if ($this->user->checkRol("Cliente"))
                   {
                       ?>
                       <li><a  href="<?= $this->raiz ?>/gestion/ordendeservicio/addosunitario.php"  style="text-decoration:none">Nueva Guia</a>
                       </li>
                       <?php
                   }
                   ?>


               </ul>
           </div>
           <script type="text/javascript">

               var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgDown: "<?= $this->raiz ?>/SpryAssets/SpryMenuBarDownHover.gif", imgRight: "<?= $this->raiz ?>/SpryAssets/SpryMenuBarRightHover.gif"});
           </script> 
           <?
       }

   }
?>
