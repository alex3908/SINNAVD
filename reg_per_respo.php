<?php
    
    session_start();
    require 'conexion.php';
    
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
    $qIdAsig="SELECT id_departamentos_asignado 
    from historico_asignaciones_trabajo_social h inner join posible_caso pc 
    on pc.id_asignado_ts=h.id where pc.id=$idReporte";
    $rIdAsig=$mysqli->query($qIdAsig);
    $IdAsig=implode($rIdAsig->fetch_assoc());
    

    
        $reg="SELECT id,num_prespo from acercamiento_familiar where id_reporte='$idReporte'";
        $ereg=$mysqli->query($reg);
        while ($rw=$ereg->fetch_assoc()) {
            $idAcerca=$rw['id'];
            $par = $rw['num_prespo'];
        }
    $qResponsables="SELECT id, nombre, edad, ocupacion , datos_economicos, telefono, direccion, estado_civil,
    escolaridad, religion from respon_nna where id_acerca_fam=$idAcerca and activo=1";
    $rResponsables=$mysqli->query($qResponsables);
    $numResponsables=$rResponsables->num_rows;

    if (isset($_POST['guardar_datos'])) {
        $fecha= date("Y-m-d H:i:s", time());
        $nombre= $_POST['nombre'];
        $edad=$_POST['edad'];
        $ocupacion=$_POST['ocupa'];
        $datos_economicos=$_POST['datosE'];
        $telefono=$_POST['telefono'];
        $direccion=$_POST['direccion'];
        $estado_civil=$_POST['estado'];            
        $religion=$_POST['religion'];
        $escolaridad=$_POST['escolaridad'];

        $sql="INSERT INTO respon_nna (id_acerca_fam, nombre, edad, ocupacion, datos_economicos, telefono, direccion, estado_civil, escolaridad, religion, fecha_registro, respo_reg) VALUES ('$idAcerca','$nombre','$edad','$ocupacion','$datos_economicos','$telefono','$direccion','$estado_civil','$escolaridad','$religion','$fecha','$idDEPTO')";
        $esql=$mysqli->query($sql);
        if($esql>0){
            $qAddRespon="UPDATE acercamiento_familiar set num_prespo=num_prespo+1 where id_reporte=$idAcerca";
            $rAddRespon=$mysqli->query($qAddRespon);
            if($rAddRespon)
                header("Location: reg_per_respo.php?id=$idReporte");
            else echo $qAddRespon;
        }
        else echo $sql;
           
        
    }

    ?>
<!DOCTYPE HTML>

<html>
    <head lang="es">
        <title>Registro de responsables</title>
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
                    <br/>
                    <div class="row uniform">
                        <div class="2u 12u$(xsmall)">
                            <input type="button" name="" class="button special small" value="Atras" onclick="location='acercamiento_ts.php?id=<?php echo $idReporte ?>'">
                        </div>
                        <div class="10u">
                            <h3>Personas adultas responsables del NNA (incluyendo cuidadores):</h3>
                        </div>
                    </div>
                    <br/>
                    <?php if($numResponsables>0) { ?>
                        <div class="box">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Edad</th>
                                        <th>Teléfono</th>
                                        <th>Ocupación</th>
                                        <th>Datos económicos</th>
                                        <th>Religión</th>
                                        <th>Dirección</th>
                                        <th>Estado cívil</th>
                                        <th>Escolaridad</th>
                                    </tr>
                                </thead>
                                <?php while($rwRes=$rResponsables->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?= $rwRes['nombre'] ?></td>
                                        <td><?= $rwRes['edad'] ?></td>
                                        <td><?= $rwRes['telefono'] ?></td>
                                        <td><?= $rwRes['ocupacion'] ?></td>
                                        <td><?= $rwRes['datos_economicos'] ?></td>
                                        <td><?= $rwRes['religion'] ?></td>
                                        <td><?= $rwRes['direccion'] ?></td>
                                        <td><?= $rwRes['estado_civil']?></td>
                                        <td><?= $rwRes['escolaridad']?></td>
                                        <?php if($IdAsig==$idDEPTO or ($idDepartamento==16 and $idPersonal==1)) { ?>
                                            <td><a href="editar_respo_acerca.php?id=<?=$rwRes['id']?>&idRep=<?=$idReporte?>">Editar</a></td>
                                            <td><a href="eliminar_respo_acerca.php?id=<?=$rwRes['id']?>&idRep=<?=$idReporte?>">Eiminar</a></td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                    <?php } ?>
                    <div class="box">
                        <h4>Registrar nuevo</h4>
                        <form id="primer" name="primer" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                            <div class="row uniform">
                                <div class="6u 12u$(xsmall)">Nombre
                                    <input name='nombre' type='text' maxlength="100" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>
                                <div class="3u 12u$(xsmall)">Edad
                                    <input name='edad' type='text' maxlength="20" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>
                                <div class="3u 12u$(xsmall)">Teléfono
                                    <input name='telefono' type='text' maxlength="30" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>
                            </div>
                            <div class="row uniform">
                                <div class="4u 12u$(xsmall)">Ocupación
                                    <input name='ocupa' type='text' maxlength="100" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>
                                <div class="5u 12u$(xsmall)">Datos economicos (concepto, cantidad)
                                    <input name='datosE' type='text' maxlength="300" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>
                                <div class="3u 12u$(xsmall)">Religión
                                    <input name='religion' type='text' maxlength="20" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>
                            </div>
                            <div class="row uniform">
                                <div class="7u 12u$(xsmall)">Dirección
                                    <input name='direccion' type='text' maxlength="300" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>
                                <div class="3u 12u$(xsmall)">Estado civil
                                    <input name='estado' type='text' maxlength="50" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>
                                <div class="2u 12u$(xsmall)">Escolaridad
                                    <input name='escolaridad' type='text' maxlength="50" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>
                            </div>
                            <?php if($IdAsig==$idDEPTO or ($idDepartamento==16 and $idPersonal==1)) { ?>
                                <div class="row uniform">
                                    <div class="12u">
                                        <input name="guardar_datos" class="button special fit" type="submit" value="Guardar datos">
                                    </div>
                                </div>
                            <?php } ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts -->
            <script src="assets/js/jquery.min.js"></script>
            <script src="assets/js/skel.min.js"></script>
            <script src="assets/js/util.js"></script>
            <!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
            <script src="assets/js/main.js"></script>

    </body>
</html>