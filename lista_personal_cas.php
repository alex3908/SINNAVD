<?php
    
    session_start();
    require 'conexion.php';
    
    if(!isset($_SESSION["id"])){
        header("Location: welcome.php");
    }
    
    $idDEPTO = $_SESSION['id'];
    
    $id_centro = $_GET['id'];
    $fecha= date ("j/n/Y");
    $sql="SELECT puesto, nombre, apellido_p, apellido_m, fecha_nac, sexo, curp, instruccion, profesion, cedula, funcion, jornada, telefono1, telefono2, correo1, correo2 from prof_cas where id_cas='$id_centro'";
    $esql=$mysqli->query($sql);
          

    ?>
<!DOCTYPE HTML>

<html>
    <head>
        <title>Profesionales en el Centro</title>
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
                        <br><br>
                      
            <input type="button" class="button special small" value="Regresar" onclick="location='listaxcentro.php?id=<?php echo $id_centro; ?>'"><br><h3>Personal que labora en el CAS</h3>
           
            
                <?php while ($row=$esql->fetch_assoc()) { ?>
                    <div class="box alt">
            <div class="row uniform">
                <div class="4u 12u$(xsmall)" align="left">
                    <ul class="alt">
                        <li><b>Puesto:</b> <?php echo $row['puesto'];?></li>
                        <li><b>Nombre:</b> <?php echo $row['nombre'].' '.$row['apellido_p'].' '.$row['apellido_m'];?></li>
                        <li><b>Fecha de nacimiento:</b> <?php echo $row['fecha_nac'];?></li>
                        <li><b>Sexo:</b> <?php echo $row['sexo'];?></li>
                        <li><b>Curp:</b> <?php echo $row['curp'];?></li>
                    </ul>
                </div>
                <div class="4u 12u$(xsmall)" align="left">
                    <ul class="alt">
                        <li><b>Instrucción profesional:</b> <?php echo $row['instruccion'];?></li>
                        <li><b>Profesion:</b> <?php echo $row['profesion'];?></li>   
                        <li><b>Cedula profesional:</b> <?php echo $row['cedula'];?></li>
                        <li><b>Jornada de trabajo:</b> <?php echo $row['jornada'];?></li>
                        <li><b>Telefono 1:</b> <?php echo $row['telefono1'];?></li>                 
                    </ul>
                </div>
                <div class="4u 12u$(xsmall)" align="left">
                    <ul class="alt">
                        <li><b>Telefono 2:</b> <?php echo $row['telefono2'];?></li>
                        <li><b>Correo 1:</b> <?php echo $row['correo1'];?></li>     
                        <li><b>Correo 2:</b> <?php echo $row['correo2'];?></li>                    
                        <li><b>Funcion de desempeña:</b> <?php echo $row['funcion']; ?></li>   
                    </ul>
                </div>  
            </div>  
        </div><hr class="major" /> <?php } ?>
               
             
           


        
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
                                    <ul>
                                        <li><a href="welcome.php">Inicio</a></li>
                                        <li><a href="logout.php" ">Cerrar sesión</a></li>
                                    </ul>
                                </nav>
                                                        
                                <?php }elseif ($_SESSION['departamento']==16) { ?>
                            
                            <!-- Menu -->
                                <nav id="menu">
                                    <header class="major">
                                        <h2>Menú</h2>
                                    </header>
                                    <ul>
                                        <li><a href="welcome.php">Inicio</a></li>                 
                                        <li><a href="logout.php" ">Cerrar sesión</a></li>
                                    </ul>
                                </nav>                      
                                
                                <?php }else { ?>
                                <!-- Menu -->
                                <nav id="menu">
                                    <header class="major">
                                        <h2>Menú</h2>
                                    </header>
                                    <ul>
                                        <li><a href="welcome.php">Inicio</a></li>    
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