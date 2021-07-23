<?php
    
    session_start();
    require 'conexion.php';
    
    if(!isset($_SESSION["id"])){
        header("Location: welcome.php");
    }
    
    $idDEPTO = $_SESSION['id'];
    
    $idReporte = $_GET['id'];
    $fecha= date ("j/n/Y");

        $reg="SELECT id,num_nna from part1ac where id_reporte='$idReporte'";
        $ereg=$mysqli->query($reg);
        while ($rw=$ereg->fetch_assoc()) {
            $idAcerca=$rw['id'];           
            $nnna=$rw['num_nna'];            
        }

        if (isset($_POST['guardar_datos'])) {
          
        foreach ($_POST['nombre'] as $num => $val) {
               $perso = $num + 1;
               
            $nombre= $val;
            $apellido_p=$_POST['apellido_p'][$num];
            $apellido_m=$_POST['apellido_m'][$num];
            $sexo=$_POST['sexo'][$num];
            $fecha_nac=$_POST['fecha_nac'][$num];
            $lugar_nac=$_POST['lugar_nac'][$num];
            $nacionalidad=$_POST['nacionalidad'][$num];         
            $ocupa=$_POST['ocupa'][$num];            
            $religion=$_POST['religion'][$num];            
        
            $error = '';

            $sql="INSERT INTO nna_ac (id_acerca, nombre, apellido_p, apellido_m, sexo, fecha_nac, lugar_nac, nacionalidad, ocupacion, religion, fecha_reg, respo_reg) VALUES ('$idAcerca','$nombre','$apellido_p','$apellido_m','$sexo','$fecha_nac','$lugar_nac','$nacionalidad','$ocupa','$religion','$fecha','$idDEPTO')";
            $esql=$mysqli->query($sql);
            if($esql>0)
            header("Location: acercamiento.php?id=$idReporte");
            else
            $error = "Error al Registrar";
            
        }
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
                      
            <h3>Niñas, Niños y Adolescentes:</h3>
            <form id="registro" name="registro" action="#" method="POST" >
           
                <?php for ($i = 0; $i < $nnna; $i++) { ?>
                    <div class="box">
                    <div class="row uniform">
                    <div class="4u 12u$(xsmall)">
                    <input name='nombre[]' type='text' placeholder='Nombre (s)' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                    </div>

                    <div class="4u 12u$(xsmall)">
                    <input name='apellido_p[]' type='text' placeholder='Apellido paterno' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                    </div>

                    <div class="4u 12u$(xsmall)">
                    <input name='apellido_m[]' type='text' placeholder='Apellido materno' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                    </div>

                    <div class="3u 12u$(xsmall)">
                        <div class="select-wrapper">
                            <select id="sexo" name="sexo[]" required>
                                <option value="">SEXO...</option>
                                <option value="HOMBRE">HOMBRE</option>
                                <option value="MUJER">MUJER</option>
                            </select>
                        </div>
                    </div>

                    <div class="4u 12u$(xsmall)">
                    <input type="text" name="nacionalidad[]" placeholder="Nacionalidad" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">    
                    </div>

                    <div class="5u 12u$(xsmall)">
                    <input name='fecha_nac[]' type='text' placeholder='Fecha de nacimiento DD/MM/AAAA' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                    </div>
                    
                    <div class="4u 12u$(xsmall)">
                    <input name='lugar_nac[]' type='text' placeholder='Lugar de nacimiento' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                    </div>

                    <div class="4u 12u$(xsmall)">
                        <div class="select-wrapper">
                            <select id="ocupa[]" name="ocupa[]" required>
                                <option value="">Ocupación...</option>
                                <option value="ESTUDIA">Estudia</option>
                                <option value="TRABAJA">Trabaja</option>
                                <option class="ESTUDIA Y TRABAJA">Estudia y Trabaja</option>
                                <option value="NINGUNA">Ninguna</option>
                            </select>
                        </div>
                    </div>                       
                   
                    <div class="4u 12u$(xsmall)">
                    <input type="text" name="religion[]" placeholder="Religion" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">    
                    </div>
                                    
                    
                   
                    </div>
                    </div>
                <?php } ?>
               
                <br>
            <input name="guardar_datos" class="button special fit" type="submit" value="Guardar datos">
      
        </form>


        
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