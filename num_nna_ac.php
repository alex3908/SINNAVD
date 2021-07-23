<?php
    
    session_start();
    require 'conexion.php';
    date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
    if(!isset($_SESSION["id"])){
        header("Location: welcome.php");
    }
    
    $idDEPTO = $_SESSION['id'];
    
    $idReporte = $_GET['id'];
    $fecha= date("Y-m-d H:i:s", time());


      if(!empty($_POST['personasCon'])){
        
        $nnna=$_POST['nnna'];
        
        $reg="INSERT INTO part1ac (id_reporte, fecha_registro, num_nna, respo_reg) VALUES ('$idReporte','$fecha','$nnna','$idDEPTO')";
        $ereg=$mysqli->query($reg);
        if($ereg>0)
            header("Location: reg_nna_ac.php?id=$idReporte");
            else
            $error = "Error al Registrar";
    }

  
    ?>
<!DOCTYPE HTML>

<html>
    <head>
        <title>Composición</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
        <link rel="stylesheet" href="assets/css/main.css" />
         <link rel="shortcut icon" href="images/favicon.png" type="image/png" />

        
    </head>
    <body>
    <!-- Wrapper -->
            <div id="wrapper">

                <!-- Main -->
                    <div id="main">
                        <div class="inner">
                        <br><br><br>
                        <div class="box">
                        <h2>Registro de acercamientos</h2>
                        <h5>(Indica en el campo la cantidad de nna para quienes se realizo el acercamieto)</h5>
        <form id="primer" name="primer" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
        <div class="box">
        <div class="row uniform">
            
            <div class="6u 12u$(small)">
                Número de NNA
            <input id="nnna" name="nnna" type="text" placeholder="Escribir la cifra con número" required>
            </div>            
            <div class="4u 12u$(small)"><br>
            <input name="personasCon" type="submit" class="button special" value="registrar y Continuar">
            </div>
            <div class="2u 12u$(small)"><br>
            <input  type="button" class="button" value="Cancelar" onclick="location='perfil_posible_caso.php?idPosibleCaso=<?php echo $idReporte; ?>'">
            </div>
        </div>
        </div>
        </form>
        
        </div>


        
                        </div>
                    </div>

                <div id="sidebar">
                        <div class="inner">

                        <?php if($_SESSION['departamento']==7) { ?> 
                            <!-- Menu -->
                                <nav id="menu">
                                    <header class="major">
                                        <h2>Menú</h2>
                                    </header>
                                    <ul><li><a href="welcome.php">Inicio</a></li>
                                        
                                        <li><span class="opener">Usuarios</span>
                                            <ul>
                                                <li><a href="registro_usuarios.php">Alta</a></li>
                                                <li><a href="lista_usuarios.php">Ver</a></li>
                                                
                                            </ul>
                                        </li>
                                        <li><a href="logout.php" ">Cerrar sesión</a></li>
                                    </ul>
                                </nav>
                                                        
                                <?php }elseif ($_SESSION['departamento']==16) { ?>
                            
                            <!-- Menu -->
                                <nav id="menu">
                                    <header class="major">
                                        <h2>Menú</h2>
                                    </header>
                                    <ul><li><a href="welcome.php">Inicio</a></li>
                                        <li><span class="opener">Departamentos</span>
                                            <ul>
                                                <li><a href="registro_personal.php">Alta</a></li>
                                                
                                                
                                            </ul>
                                        </li>
                                        <li><span class="opener">Usuarios</span>
                                            <ul>
                                                
                                                <li><a href="lista_usuarios.php">Ver</a></li>
                                                
                                            </ul>
                                        </li>
                                        <li><a href="logout.php" ">Cerrar sesión</a></li>
                                    </ul>
                                </nav>                      
                                
                                <?php }else { ?>
                                <!-- Menu -->
                                <nav id="menu">
                                    <header class="major">
                                        <h2>Menú</h2>
                                    </header>
                                    <ul><li><a href="welcome.php">Inicio</a></li>
                                        
                                        </li>
                                        <li><span class="opener">Usuarios</span>
                                            <ul>
                                                
                                                <li><a href="lista_usuarios.php">Ver</a></li>
                                                
                                            </ul>
                                        </li>
                                        <li><a href="logout.php" ">Cerrar sesión</a></li>
                                    </ul>
                                </nav>      
                            
                                <?php }
    
                                ?>
                                <section>
                                    <header class="major">
                                        <h4>PROCURADURÍA DE PROTECCIÓN DE NIÑAS, NIÑOS, ADOLESCENTES Y LA FAMILIA</h4>
                                    </header>
                                    <p></p>
                                    <ul class="contact">
                                        <li class="fa-envelope-o"><a href="#">laura.ramirez@hidalgo.gob.mx</a></li>
                                        <li class="fa-phone">(771) 71 6 84 21 ext. 3126</li>
                                        <li class="fa-phone">(771) 71 6 84 23 ext. 3126</li>
                                        <li class="fa-home">Plaza Juarez #118<br />
                                        Col. Centro <br> Pachuca Hidalgo</li>
                                    </ul>
                                </section>
                            <!-- Footer -->
                                <footer id="footer">
                                    <p class="copyright">&copy; Sistema DIF Hidalgo </p>
                                </footer>

                        </div>
                    </div>
                    <!--cierre menu-->

            </div>

        <!-- Scripts -->
            <script src="assets/js/jquery.min.js"></script>
            <script src="assets/js/skel.min.js"></script>
            <script src="assets/js/util.js"></script>
            <!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
            <script src="assets/js/main.js"></script>

    </body>
</html>