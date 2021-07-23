<?php
    
    session_start();
    require 'conexion.php';
    
    if(!isset($_SESSION["id"])){
        header("Location: welcome.php");
    }
    
    $idDEPTO = $_SESSION['id'];
    
    $id_centro = $_GET['id'];
    $pro= $_GET['pro'];
    $fecha= date ("j/n/Y");

        $reg="SELECT id,cantidad from cas3 where id_cas='$id_centro' and profesion='$pro'";
        $ereg=$mysqli->query($reg);
        
        if (isset($_POST['guardar_datos'])) {
          
        foreach ($_POST['puesto'] as $num => $val) {
               $perso = $num + 1;
               
            $puesto= $val;
            $nombre=$_POST['nombre'][$num];
            $apellido_p=$_POST['apellido_p'][$num];
            $apellido_m=$_POST['apellido_m'][$num];
            $f_nac=$_POST['f_nac'][$num];
            $curp=$_POST['curp'][$num];
            $sexo=$_POST['sexo'][$num];
            $inspro=$_POST['inspro'][$num];
            $profesion=$_POST['profesion'][$num];            
            $cedula=$_POST['cedula'][$num];
            $jornada=$_POST['jornada'][$num];
            $tel1=$_POST['tel1'][$num];         
            $tel2=$_POST['tel2'][$num];            
            $correo1=$_POST['correo1'][$num];            
            $correo2=$_POST['correo2'][$num];            
            $funcion=$_POST['funcion'][$num];            
        
            $error = '';

            $sql="INSERT INTO prof_cas(fecha_reg, respo_reg, id_cas, tipo, puesto, nombre, apellido_p, apellido_m, fecha_nac, sexo, curp, instruccion, profesion, cedula, funcion, jornada, telefono1, telefono2, correo1, correo2) VALUES ('$fecha', '$idDEPTO', '$id_centro', '$pro', '$puesto', '$nombre', '$apellido_p', '$apellido_m', '$f_nac', '$sexo', '$curp', '$inspro', '$profesion', '$cedula', '$funcion', '$jornada', '$tel1', '$tel2', '$correo1', '$correo2')";
            $esql=$mysqli->query($sql);
            if($esql>0)
            header("Location: listaxcentro.php?id=$id_centro");
            else
            $error = "Error al Registrar";
            
        }
    }

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
                        <br><br><br>
                      
            <h3>Registro del personal que labora en el CAS</h3>
            <form id="registro" name="registro" action="#" method="POST" >
            
                <?php while ($rw=$ereg->fetch_assoc()) {
                     $cantidad=$rw['cantidad']; ?>
                    <div class="box">
                        <strong> <?php echo $pro; ?>   </strong>                  
                        <?php     for ($i = 0; $i < $cantidad; $i++) { ?>
                    <div class="box">                       
                    <div class="row uniform">
                    <div class="3u 12u$(xsmall)">
                    <input name='puesto[]' type='text' placeholder='puesto' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                    </div>
                    <div class="3u 12u$(xsmall)">
                    <input name='nombre[]' type='text' placeholder='Nombre (s)' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                    </div>

                    <div class="3u 12u$(xsmall)">
                    <input name='apellido_p[]' type='text' placeholder='Apellido paterno' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                    </div>

                    <div class="3u 12u$(xsmall)">
                    <input name='apellido_m[]' type='text' placeholder='Apellido materno' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                    </div>
                    <div class="3u 12u$(xsmall)">
                    <input name='f_nac[]' type='text' placeholder='fecha de nacimiento' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                    </div>
                    <div class="4u 12u$(xsmall)">
                    <input name='curp[]' type='text' placeholder='curp' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                    </div>
                    <div class="2u 12u$(xsmall)">
                        <div class="select-wrapper">
                            <select id="sexo" name="sexo[]" required>
                                <option value="">SEXO...</option>
                                <option value="HOMBRE">HOMBRE</option>
                                <option value="MUJER">MUJER</option>
                            </select>
                        </div>
                    </div>
                    <div class="3u 12u$(xsmall)">
                        <div class="select-wrapper">
                            <select id="inspro" name="inspro[]" required>
                                <option value="">INSTRUCCION PROFESIONAL</option>
                                <option value="SIN ESTUDIOS">SIN ESTUDIOS</option>
                                <option value="PRIMARIA">PRIMARIA</option>
                                <option value="SECUNDARIA">SECUNDARIA</option>
                                <option value="PREPARATORIA">PREPARATORIA</option>
                                <option value="LICENCIATURA">LICENCIATURA</option>
                                <option value="INGENIERIA">INGENIERIA</option>
                                <option value="ESPECIALIDAD">ESPECIALIDAD</option>
                                <option value="MAESTRIA">MAESTRIA</option>
                                <option value="DOCTORADO">DOCTORADO</option>
                            </select>
                        </div>
                    </div>
                    <div class="4u 12u$(xsmall)">
                    <input type="text" name="profesion[]" placeholder="profesion" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                    </div>
                    <div class="3u 12u$(xsmall)">
                    <input name='cedula[]' type='text' placeholder='cedula profesional' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                    </div>                   
                    <div class="3u 12u$(xsmall)">
                    <input name='jornada[]' type='text' placeholder='jornada de trabajo' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                    </div>
                    <div class="2u 12u$(xsmall)">
                    <input name='tel1[]' type='text' placeholder='telefono 1' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                    </div>
                    <div class="2u 12u$(xsmall)">
                    <input name='tel2[]' type='text' placeholder='telefono 2' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                    </div>
                    <div class="5u 12u$(xsmall)">
                    <input name='correo1[]' type='text' placeholder='CORREO 1' required>
                    </div>
                    <div class="5u 12u$(xsmall)">
                    <input name='correo2[]' type='text' placeholder='CORREO 2' required>
                    </div>                    
                    <div class="12u 12u$(xsmall)">
                    <textarea name="funcion[]" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required placeholder="Funcion que desempeña"></textarea>
                    </div>                     
                  
                    </div>
                    </div>
                <?php } ?> </div> <?php } ?>
               
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