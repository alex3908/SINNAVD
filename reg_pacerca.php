<?php
    
    session_start();
    require 'conexion.php';
    date_default_timezone_set('America/Mexico_City');
    if(!isset($_SESSION["id"])){
        header("Location: welcome.php");
    }
    
    $idDEPTO = $_SESSION['id'];
    $pv="SELECT id_depto, id_personal from departamentos where id='$idDEPTO'";
    $epv=$mysqli->query($pv);
    while ($row=$epv->fetch_assoc()) {
        $idDepartamento=$row['id_depto'];
        $idPersonal=$row['id_personal'];
    }
    //para el menu (da el numero de la derecha de reportes = numero de reportes asignados)
    $reportesvd="SELECT id from reportes_vd where atendido='1' 
        and (asignado='$idDEPTO' or asignado_psic='$idDEPTO')";
    $erepo=$mysqli->query($reportesvd);
    $wow=$erepo->num_rows;
    $idReporte = $_GET['id'];

    $qregistrado="SELECT id from acercamiento_familiar where id_reporte=$idReporte"; //verifica q el acermiento no este ya registrado
    $rRegistrado=$mysqli->query($qregistrado);
    if($rRegistrado->num_rows>0){
       header("Location: acercamiento_ts.php?id=$idReporte"); //si ya esta registrado redirecciona
    }


      if(!empty($_POST['personasCon'])){
        $fecha= date("Y-m-d H:i:s", time());
        $fecha_a=$_POST['fecha_a'];
        $reg="INSERT into acercamiento_familiar (id_reporte, fecha_registro, fecha_acercamiento, respo_reg) VALUES ('$idReporte','$fecha', '$fecha_a','$idDEPTO')";
        $ereg=$mysqli->query($reg);
        if($ereg>0)
            header("Location: acercamiento_ts.php?id=$idReporte");
            else
            $error = "Error al Registrar: ".$reg;
    }

  
    ?>
<!DOCTYPE HTML>

<html>
    <head>
        <title>Acermiento familiar</title>
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
                    <div class="box alt" align="center">
                        <div class="row 10% uniform">
                            <div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
                            <div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
                            <div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
                        </div>
                    </div>
                    <br><br><br>
                    <div class="box">
                        <h2>Registro de primer acercamiento familiar</h2>
                        <h5>(Indique la fecha del acercamiento)</h5>
                        <form id="primer" name="primer" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                            <div class="box">
                                <div class="row uniform">
                                    <div class="4u 12u$(small)">
                                        <label>Fecha de acercamiento</label>
                                        <input id="fecha_a" name="fecha_a" type="date" required>
                                    </div>
                                    <div class="4u 12u$(small)"><br>
                                        <input name="personasCon" type="submit" class="button special" value="registrar y Continuar">
                                    </div>
                                    <div class="4u 12u$(small)"><br>
                                        <input  type="button" class="button" value="Cancelar" onclick="location='registro_nna_ac.php?id=<?php echo $idReporte; ?>'">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div id="sidebar">
                <div class="inner">
                    <?php $_SESSION['spcargo'] = $idPersonal; ?>
                    <?php if($idPersonal==6) { //UIENNAVD?>
                        <!-- Menu -->
                        <nav id="menu">
                            <header class="major">
                                <h2>Menú</h2>
                            </header>
                            <ul>
                                <li><a href="welcome.php">Inicio</a></li>   
                                <li><a href="lista_unidad.php">UIENNAVD</a></li>
                                <li><a href="logout.php">Cerrar sesión</a></li>
                            </ul>
                        </nav>  
                    <?php }else if($idPersonal==5) { //Subprocu ?>
                        <!-- Menu -->
                        <nav id="menu">
                            <header class="major">
                                <h2>Menú</h2>
                            </header>
                            <ul>
                                <li><a href="welcome.php">Inicio</a></li>   
                                <li><a href="lista_personal.php">Personal</a></li>
                                <li><a href="lista_usuarios.php">Usuarios</a></li>          
                                <li><a href="lista_reportes_nueva.php?estRep=0">Reportes VD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $wow; ?></strong></a></li>
                                <li><a href="lista_casos.php">Casos</a></li>
                                <li><a href="lista_nna.php">NNA</a></li>
                                <li><span class="opener">Carpetas</span>
									<ul>
										<li><a href="lista_carpeta.php">Carpetas</a></li>
										<li><a href="lista_imputados.php">Imputados</a></li>			
									</ul>
								</li>       
                                <li><a href="cas.php">CAS</a></li>                          
                                <li><span class="opener">Pendientes</span>
                                    <ul>
                                        <li><a href="carpetas_sasignar.php">Carpetas por asignar</a></li>
                                        <li><a href="nna_pendientes.php">NNA sin curp</a></li>
                                        <li><a href="visitas_fecha.php">Buscador</a></li>           
                                    </ul>
                                </li>
                                <li><a href="lista_documentos.php">Descarga de oficios</a></li>
                                <li><a href="alta_medida.php">Catalogo de medidas</a></li>
                                <li><a href="logout.php">Cerrar sesión</a></li>
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
                                <li><a href="lista_personal.php">Personal</a></li>
                                <li><a href="lista_usuarios.php">Usuarios</a></li>
                                <?php if ($_SESSION['departamento']==7) { ?>
                                    <li><a href="canalizar.php">Canalizar visita</a></li>   
                                <?php } ?>                                              
                                <li><a href="lista_reportes_nueva.php?estRep=0">Reportes VD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $wow; ?></strong></a></li>
                                <li><a href="lista_casos.php">Casos</a></li>
                                <li><a href="lista_nna.php">NNA</a></li>        
                                <li><a href="reg_reporte_migrantes.php">Migrantes</a></li>
                                <li><span class="opener">Carpetas</span>
									<ul>
										<li><a href="lista_carpeta.php">Carpetas</a></li>
										<li><a href="lista_imputados.php">Imputados</a></li>			
									</ul>
								</li>
                                <li><a href="cas.php">CAS</a></li>
                                <li><span class="opener">UIENNAVD</span>
                                    <ul>
                                        <li><a href="lista_unidad.php">Beneficiarios</a></li>
                                        <li><a href="visitas_gen_unidad.php">Historial de visitas</a></li>
                                    </ul>
                                </li>                       
                                <?php if (($_SESSION['departamento']==16) or ($_SESSION['departamento']==7)) { ?>
                                    <li><span class="opener">Visitas</span>
                                        <ul>
                                            <li><a href="editar_visitadepto.php">Editar departamento</a></li>
                                            <li><a href="editar_visitarespo.php">Editar responsable</a></li>
                                            <li><a href="eliminar_visita.php">Eliminar</a></li>
                                        </ul>
                                    </li>
                                <?php } ?>                                  
                                <li><span class="opener">Pendientes</span>
                                    <ul>
                                        <li><a href="carpetas_sasignar.php">Carpetas por asignar</a></li>
                                        <li><a href="nna_pendientes.php">NNA sin curp</a></li>          
                                        <li><a href="visitas_fecha.php">Buscador</a></li>               
                                    </ul>
                                </li>                                   
                                <li>
                                    <span class="opener">Adopciones</span>
                                    <ul>
                                        <li><a href="reg_expAdop.php">Generar expediente</a></li>
                                        <li><a href="">Expedientes</a></li>
                                    </ul>
                                </li>
                                <?php if ($_SESSION['departamento']==16 or $_SESSION['departamento']==14) {  ?>
                                    <li><a href="reg_actccpi.php">CCPI</a></li>
                                <?php } ?>
                                <li><a href="numoficio.php">Numero de oficio</a></li>
                                 
                                <li><a href="lista_documentos.php">Descarga de oficios</a></li>
                                <li><a href="alta_medida.php">Catalogo de medidas</a></li>
                                <li><a href="logout.php">Cerrar sesión</a></li>
                            </ul>
                        </nav>  
                    <?php }?>
                    <section>
                        <header class="major">
                            <h4>PROCURADURÍA DE PROTECCIÓN DE NIÑAS, NIÑOS, ADOLESCENTES Y LA FAMILIA</h4>
                        </header>
                        <p></p>
                        <ul class="contact">
                            <li class="fa-envelope-o">laura.ramirez@hidalgo.gob.mx</li>
                            <li class="fa-phone">(771) 71 6 84 21 ext. 3126</li>
                            <li class="fa-phone">(771) 71 6 84 23 ext. 3126</li>
                            <li class="fa-phone"><a href="directorio.php">Directorio interno</a></li>
                            <li class="fa-home">Plaza Juarez #118<br />
                                Col. Centro <br> Pachuca Hidalgo</li>
                        </ul>
                    </section>
                    <!-- Footer -->
                    <footer id="footer">
                        <p class="copyright">&copy; Sistema DIF Hidalgo </p>
                    </footer>
                </div>
            </div><!--cierre menu-->

            </div>

        <!-- Scripts -->
            <script src="assets/js/jquery.min.js"></script>
            <script src="assets/js/skel.min.js"></script>
            <script src="assets/js/util.js"></script>
            <!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
            <script src="assets/js/main.js"></script>

    </body>
</html>