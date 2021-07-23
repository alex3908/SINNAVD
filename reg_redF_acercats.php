<?php
    
    session_start();
    require 'conexion.php';
    
    if(!isset($_SESSION["id"])){
        header("Location: welcome.php");
    }
    
    $idDEPTO = $_SESSION['id'];
    
    $idReporte = $_GET['id'];
    $fecha= date ("j/n/Y");
    $qIdAsig="SELECT id_departamentos_asignado 
    from historico_asignaciones_trabajo_social h inner join posible_caso pc 
    on pc.id_asignado_ts=h.id where pc.id=$idReporte";
    $rIdAsig=$mysqli->query($qIdAsig);
    $IdAsig=implode($rIdAsig->fetch_assoc());
      $pv="SELECT id_depto, id_personal from departamentos where id='$idDEPTO'";
    $epv=$mysqli->query($pv);
    while ($row=$epv->fetch_assoc()) {
        $idDepartamento=$row['id_depto'];
        $idPersonal=$row['id_personal'];
    }

        $reg="SELECT id, num_redes from acercamiento_familiar where id_reporte='$idReporte'";
        $ereg=$mysqli->query($reg);
        while ($rw=$ereg->fetch_assoc()) {
            $idAcerca=$rw['id'];           
            $rfc=$rw['num_redes'];
        }

    $qRed="SELECT r.id, parentesco, nombre, edad, direccion, telefono, observa 
    from redes_familiares r inner join acercamiento_familiar a on r.id_acerca=a.id
    where id_reporte=$idReporte and r.activo=1 ";
    $rRed=$mysqli->query($qRed);
    $numRedes=$rRed->num_rows;
        if (isset($_POST['guardar_datos'])) {

            $fecha= date("Y-m-d H:i:s", time());   
            $nombre= $_POST['nombre'];
            $edad=$_POST['edad'];
            $direccion=$_POST['direccion'];
            $telefono=$_POST['telefono'];
            $observa=$_POST['observa'];            
            $parentesco=$_POST['parentesco'];
            
        
            $error = '';

            $sql="INSERT INTO redes_familiares (id_acerca, parentesco, nombre, edad, direccion, telefono, observa, respo_reg, fecha_registro) VALUES ('$idAcerca','$parentesco','$nombre','$edad','$direccion','$telefono','$observa','$idDEPTO','$fecha')";
            $esql=$mysqli->query($sql);
            if($esql)
            {
                $qAddRespon="UPDATE acercamiento_familiar set num_redes=num_redes+1 where id_reporte=$idAcerca";
                $rAddRespon=$mysqli->query($qAddRespon);
                if($rAddRespon)
                header("Location: reg_redF_acercats?id=$idReporte");
                else echo $qAddRespon;
            }
            else
            echo "Error al Registrar: ".$sql;
            
        
    }

    ?>
<!DOCTYPE HTML>

<html>
    <head lang="es">
        <title>Registrar redes familiares</title>
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
                            <h3>Información aportada por la familia sobre redes familiares y comunitarias:</h3>
                        </div>
                    </div>
                    <br>
                    <?php if($numRedes>0) { ?>
                        <div class="box">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Parentesco</th>
                                        <th>Nombre</th>
                                        <th>Edad</th>
                                        <th>Dirección</th>
                                        <th>Teléfono</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <?php while($rwRes=$rRed->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?= $rwRes['parentesco'] ?></td>
                                        <td><?= $rwRes['nombre'] ?></td>
                                        <td><?= $rwRes['edad'] ?></td>
                                        <td><?= $rwRes['direccion'] ?></td>
                                        <td><?= $rwRes['telefono'] ?></td>
                                        <td><?= $rwRes['observa'] ?></td>
                                        <?php if($IdAsig==$idDEPTO or ($idDepartamento==16 and $idPersonal==1)) { ?>
                                            <td><a href="editar_redes_acerca.php?id=<?=$rwRes['id']?>&idRep=<?=$idReporte?>">Editar</a></td>
                                            <td><a href="eliminar_redes_acerca.php?id=<?=$rwRes['id']?>&idRep=<?=$idReporte?>">Eiminar</a></td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                    <?php } ?>
                    <form id="registro" name="registro" action="#" method="POST" >
                        <div class="box">
                            <h4>Registrar nuevo</h4>
                            <div class="row uniform">
                                <div class="3u 12u$(xsmall)">Parentesco
                                    <input name='parentesco' type='text' maxlength="20" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>
                                <div class="6u 12u$(xsmall)">Nombre
                                    <input name='nombre' type='text' maxlength="50" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>
                                <div class="3u 12u$(xsmall)">Edad
                                    <input name='edad' type='text' maxlength="20" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>
                            </div>
                            <div class="row uniform">
                                <div class="9u 12u$(xsmall)">Dirección
                                    <input type="text" name="direccion" maxlength="120" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>
                                <div class="3u 12u$(xsmall)">Teléfono
                                    <input name='telefono' type='text' maxlength="20" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required>
                                </div>
                            </div>
                            <div class="row uniform">
                                <div class="12u 12u$(xsmall)">Observaciones
                                    <textarea name="observa" maxlength="600" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></textarea>
                                </div>
                            </div>
                            <?php if($IdAsig==$idDEPTO or ($idDepartamento==16 and $idPersonal==1)) { ?>
                                <div class="row uniform">
                                    <div class="12u">
                                        <input name="guardar_datos" class="button special fit" type="submit" value="Guardar datos">
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <br>
                    </form>
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