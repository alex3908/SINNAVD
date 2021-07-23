<?php
    
    session_start();
    require 'conexion.php';
    
    if(!isset($_SESSION["id"])){
        header("Location: index.php");
    }
    
    $idDEPTO = $_SESSION['id'];
    
    $idReporte= $_GET['id'];
    $fecha= date ("j/n/Y");
    $ev="SELECT num_nna from part1ac where id_reporte='$idReporte'"; //selecciona el numero de nna del reporte dado en num_nna_ac
    $eev=$mysqli->query($ev);
    while ($row=$eev->fetch_assoc()) {
        $num_nna=$row['num_nna'];
    }
    
    $nnaen="SELECT nna_ac.id, nna_ac.nombre, nna_ac.apellido_p, nna_ac.apellido_m, nna_ac.sexo, nna_ac.fecha_nac, nna_ac.lugar_nac, nna_ac.nacionalidad, nna_ac.ocupacion, nna_ac.religion, nna_ac.fecha_reg, nna_ac.respo_reg FROM nna_ac, part1ac WHERE nna_ac.id_acerca=part1ac.id and part1ac.id_reporte='$idReporte'";  //recupera los datos de los nna del reporte 
    $enna=$mysqli->query($nnaen);
    $connna=$enna->num_rows; //numero de filas arrojadas por la consulta consuta 

    $acts="SELECT id from acercamiento_familiar where id_reporte='$idReporte'";  //recupera el id del registro de trabajo social 
    $eacts=$mysqli->query($acts);
    while ($row=$eacts->fetch_assoc()) {
        $idacts=$row['id']; //recupera si hay datos
    }
  
    if (isset($_POST['eliminar'])) {
        $pid="SELECT id from acercamiento_familiar where id_reporte='$idReporte'";
        $epid=$mysqli->query($pid);
        while ($row=$epid->fetch_assoc()) {
            $idacf=$row['id'];
        }
        $erf="DELETE from redes_familiares where id_acerca='$idacf'";
        $eerf=$mysqli->query($erf);
        $ern="DELETE from respon_nna where id_acerca_fam='$idacf'";
        $eern=$mysqli->query($ern);
        $elfam="DELETE from acercamiento_familiar where id_reporte='$idReporte'";
        $eelfam=$mysqli->query($elfam);
        $elpsic="DELETE from acercamiento_psic where id_reporte='$idReporte'";
        $eelpsic=$mysqli->query($elpsic);
        $cons="SELECT id from part1ac where id_reporte='$idReporte'";
        $econs=$mysqli->query($cons);
        while ($row=$econs->fetch_assoc()) {
            $acer=$row['id'];
        }
        $elnna="DELETE from nna_ac where id_acerca='$acer'";
        $eelnna=$mysqli->query($eelnna);
        $elpart1="DELETE from part1ac where id_reporte='$idReporte'";
        $eelpart1=$mysqli->query($elpart1);

        header("Location: perfil_posible_caso.php?idPosibleCaso=$idReporte");
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
                        <br><br>
                        <input type="button" name="" class="button special small" value="Atras" onclick="location='perfil_posible_caso.php?idPosibleCaso=<?php echo $idReporte ?>'">  <br><br>
        <div class="uniform row">
            <div class="10u 12u$(xsmall)">
                <h2>Acercamientos</h2>
            </div>
            <div class="2u 12u$(xsmall)">
                <?php if ($_SESSION['departamento']==16) { ?>
                    <form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >   
                <input type="submit" class="button special small fit" value="Eliminar todo" name="eliminar">
                    </form>
                <?php } ?>
            </div>
        </div>
            

    <div class="box">

        <h4>Niñas, Niños y Adolescentes en el acercamiento: <?php echo $num_nna; ?></h4>
        <?php if (empty($connna)) { ?> <!--si no hay filas no hay registro por lo que debe registrar datos del nna-->

            <input type="button" name="" value="Registrar" onclick="location='reg_nna_ac.php?id=<?php echo $idReporte; ?>'"> 
        <?php } else { ?> <!--muestra datos de los nna-->
            
            <?php while ($row=$enna->fetch_assoc()) { ?> <!--obtiene una fila de resultado como una array-->
            <div class="box">
                <div class="row uniform">
                    <div class="7u 12u$(xsmall)">
                        <strong>Nombre:</strong> <?php echo $row['nombre'].' '.$row['apellido_p'].' '.$row['apellido_m']; ?>
                    </div>
                    <div class="4u 12u$(xsmall)">
                        <strong>Fecha de nacimiento:</strong> <?php echo $row['fecha_nac']; ?>
                    </div>
                    <div class="3u 12u$(xsmall)">
                        <strong>Sexo:</strong> <?php echo $row['sexo']; ?>
                    </div>
                   
                    <div class="4u 12u$(xsmall)">
                        <strong>Lugar de nacimiento:</strong> <?php echo $row['lugar_nac']; ?>
                    </div>
                    <div class="4u 12u$(xsmall)">
                        <strong>Nacionalidad:</strong> <?php echo $row['nacionalidad']; ?>
                    </div>
                    <div class="4u 12u$(xsmall)">
                        <strong>Ocupación:</strong> <?php echo $row['ocupacion']; ?>   
                    </div>                   
                    <div class="4u 12u$(xsmall)">
                        <strong>Religion:</strong> <?php echo $row['religion']; ?>
                    </div>
                                     
                    <div class="4u 12u$(xsmall)">
                        <input type="button" value="psicologia" onclick="location='acercamiento_psic.php?id=<?php echo $idReporte; ?>&idn=<?php echo $row['id']; ?>'"> <!--el acercamiento de psicologia es por nna involucrado-->
                    </div>
                 
                </div>
            </div>
        <?php } ?><?php  } ?>  
        
    </div>
    <div class="row uniform">      
            <div class="2u 12u$(xsmall)"></div>
            <div class="8u 12u$(xsmall)" >
                <?php if(empty($connna)) { ?>
                    <input class="button special fit" type="button" name="cancelar" value="Trabajo social" disabled> <!--boton inhabilitado sino hay registro de nna-->
                <?php } else {
                        if (empty($idacts)) { ?> <!--si no ha realizado acercamiento direcciona a reg_pacerca-->
                            <input class="button special fit" type="button" name="cancelar" value="Trabajo social" onclick="location='reg_pacerca.php?id=<?php echo $idReporte; ?>'" >
                        <?php }else { ?> <!--si ya registro direcciona a acercamiento_t donde muestra los datos de acercamiento-->
                            <input class="button special fit" type="button" name="cancelar" value="Trabajo social" onclick="location='acercamiento_ts.php?id=<?php echo $idReporte; ?>'" >
                        <?php } ?>
                <?php } ?>
            </div>
            <div class="2u 12u$(xsmall)"></div>
        </div>
        <br>


        
                        </div>
                    </div>

               <!-- Sidebar -->
                    <div id="sidebar">
                        <div class="inner">
                            <nav id="menu">
                                <header class="major">
                                    <h2>Menú</h2>
                                </header>
                                    <ul><li><a href="welcome.php">Inicio</a></li>               
                                        <li><a href="logout.php" >Cerrar sesión</a></li>
                                    </ul>
                            </nav>  
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