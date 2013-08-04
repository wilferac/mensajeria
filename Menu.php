<?php
/*
 * Esta clase se va a encargar de generar el menu de la aplicacion
 * consulta la variable global de usuario para mostrar los recursos.
 * @require: security/User.php
 */

class Menu {

    private $raiz;
    private $user;

    public function __construct($user) {
        $this->user = $user;
        //determino la raiz de la aplicacion =)
        /**
         * local
         */
//        $this->raiz = "http://localhost/Mensajeria";
        /**
         * Servidor
         */
        $this->raiz = "http://grupoinnovate.com/Mensajeria";
    }

    public function generarMenu() {
        ?>
        <script src="<?= $this->raiz ?>/SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
        <link href="<?= $this->raiz ?>/SpryAssets/SpryMenuBarHorizontal.css" rel="stylesheet" type="text/css">

        <div id="menu">
            <ul id="MenuBar1" class="MenuBarHorizontal" >
                <li>
                    <a  href="<?= $this->raiz ?>/redireccionador.php">::Inicio::</a>
                </li>
                <?php
                if ($this->user->checkRol("Usuario")) {
                    ?>


                    <li><a class="MenuBarItemSubmenu">Gesti&oacute;n</a>
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
                if ($this->user->checkRol("Usuario")) {
                    ?>


                    <li><a href="#"  >Masivo</a>
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

                            <li><a  href="<?= $this->raiz ?>/gestion/manifiesto/pistoleo/main.php">Pistoleo</a>
                            </li>

                        </ul>

                    </li>



                    <li><a class="MenuBarItemSubmenu" href="#"  >Unitario</a>
                        <ul>

                            <li><a  class="MenuBarItemSubmenu" href="#">Guia</a>
                              <ul>
                                  <li><a  href="<?= $this->raiz ?>/gestion/unitario/guia/loadUnitary.php">Subir Csv</a>
                                    </li>
                                    <li><a  href="<?= $this->raiz ?>/gestion/ordendeservicio/addosunitario.php">Digitar</a>
                                    </li>
                                    <li><a  href="<?= $this->raiz ?>/gestion/unitario/guia/consulta.php">Consultar</a>
                                    </li>
                                    <li><a  href="<?= $this->raiz ?>/gestion/guia/anular/index.php">Anular</a>
                                    </li>
                                </ul>
                            </li>

                            <li><a  href="<?= $this->raiz ?>/gestion/guia/asignar.php">Consultar y/o Asignar Gu&iacute;as</a>
                            </li>
                            <li><a  href="<?= $this->raiz ?>/gestion/guia/consultarasignaciones.php">Consultar todas las Asignaciones</a>
                            </li>

                        </ul>
                    </li>
                    
                    <li><a href="#"  >Consulta</a>
                        <ul>
                            <li><a class="MenuBarItemSubmenu" href="#">Guia</a>
                                <ul>
                                    <li><a href="<?= $this->raiz ?>/gestion/guia/consulta/index.php">Consultar</a></li>
                                </ul>
                            </li>

                            <li><a class="MenuBarItemSubmenu" href="#">Manifiesto</a>
                                <ul>
                                    <li><a href="<?= $this->raiz ?>/gestion/manifiesto/consulta/index.php">Consultar</a></li>
                                </ul>
                            </li>
                            <li><a class="MenuBarItemSubmenu" href="#">Orden Servicio</a>
                                <ul>
                                    <li><a href="<?= $this->raiz ?>/gestion/ordendeservicio/consulta/index.php">Consultar</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <?php
                }

                if ($this->user->checkRol("Usuario") or $this->user->checkRol("Admin")) {
                    ?>
                    <li><a class="MenuBarItemSubmenu" href="#"  >Informes</a>
                        <ul>
                            <li><a  href="<?= $this->raiz ?>/gestion/informes/tiposproducto.php">Tipos de Producto</a>
                            </li>
                        </ul>
                    </li>

                    <?php
                }
                if ($this->user->checkRol("Cliente") && !$this->user->checkRol("Admin")) {
                    ?>
                    <li><a  href="<?= $this->raiz ?>/gestion/ordendeservicio/addosunitario.php"  >Nueva Guia</a>
                    </li>
                    <li><a  href="<?= $this->raiz ?>/gestion/unitario/guia/consulta.php"  >Ver Guias</a>
                    </li>
                    <li><a  href="<?= $this->raiz ?>/gestion/print/guias/index.php"  >Imprimir Guias</a>
                    </li>
                    <li><a  href="<?= $this->raiz ?>/gestion/unitario/guia/loadUnitaryCC.php"  >Subir Csv</a>
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
