<?php
session_start();
require 'conexion.php';
date_default_timezone_set('America/Mexico_City');
$zonahoraria = date_default_timezone_get();
if (!isset($_SESSION["id"])) {
    header("Location: index.php");
}
$idDEPTO = $_SESSION['id'];
$pv = "SELECT id_depto, id_personal from departamentos where id='$idDEPTO'";
$epv = $mysqli->query($pv);
while ($row = $epv->fetch_assoc()) {
    $idDepartamento = $row['id_depto'];
    $idPersonal = $row['id_personal'];
}

$idPc = $_GET['idPc'];
$idRep = $_GET['idRep'];
//Recoge los asignados del posible caso para validar que se pueda editar
$qPosibleCaso = "SELECT tabj.id_departamentos_asignado AS juridico, tabts.id_departamentos_asignado AS ts,
    	tabps.id_departamentos_asignado AS ps
		FROM posible_caso LEFT JOIN historico_asignaciones_juridico tabj ON id_asignado_juridico = tabj.id
    	LEFT JOIN historico_asignaciones_trabajo_social tabts ON id_asignado_ts = tabts.id
    	LEFT JOIN historico_asignaciones_psicologia tabps ON id_asignado_ps = tabps.id
		WHERE posible_caso.id ='$idPc'";
$rPosibleCaso = $mysqli->query($qPosibleCaso);
while ($rowPC = $rPosibleCaso->fetch_assoc()) {
    $asignadoJ = $rowPC['juridico'];
    $asignadoTS = $rowPC['ts'];
    $asignadoPS = $rowPC['ps'];
}

$qReporte = "SELECT reportes_vd.folio, date_format(reportes_vd.fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha_registro,
    	cat_recepcion_reporte.recepcion, distritos.distrits, reportes_vd.id_distrito, maltratos as maltrato, reportes_vd.otros_datos,
    	reportes_vd.persona_reporte, reportes_vd.narracion, municipios.municipio, reportes_vd.clm, reportes_vd.num_ext, reportes_vd.num_interior,
    	localidades.localidad, reportes_vd.id_localidad, reportes_vd.calle, reportes_vd.ubicacion, departamentos.responsable,
    	reportes_vd.respo_reg,viajaSA, tipo_migrante
    	from reportes_vd inner join municipios on clm=municipios.id
    	inner join departamentos on departamentos.id=reportes_vd.respo_reg
   		inner join  localidades on localidades.id=reportes_vd.id_localidad
   	 	left join distritos on distritos.id=reportes_vd.id_distrito
    	inner join cat_recepcion_reporte on id_recepcion=cat_recepcion_reporte.id
        where reportes_vd.id=$idRep";
$rReporte = $mysqli->query($qReporte);
while ($rwRep = $rReporte->fetch_assoc()) {
    $folio = $rwRep['folio'];
    $fec_reg = $rwRep['fecha_registro'];
    $recepcion = $rwRep['recepcion'];
    $distrito = $rwRep['distrits'];
    $persona = $rwRep['persona_reporte'];
    $municipio = $rwRep['municipio'];
    $localidad = $rwRep['localidad'];
    $responsable = $rwRep['responsable'];
    $maltrato = $rwRep['maltrato'];
    $otros = $rwRep['otros_datos'];
    $narracion = $rwRep['narracion'];
    $calle = $rwRep['calle'];
    $numE = $rwRep['num_ext'];
    $numI = $rwRep['num_interior'];
    $ubicacion = $rwRep['ubicacion'];
    $ResponRegistro = $rwRep['respo_reg'];
    $viajaSA = $rwRep['viajaSA'];
    $tipoMigrante = $rwRep['tipo_migrante'];
}

//obtiene del catalogo los maltratos que le corresponden a este reporte
$qmaltratosC = "SELECT id FROM tipo_maltrato where '$maltrato'  LIKE CONCAT('%', maltrato, '%')";
$rMaltrato = $mysqli->query($qmaltratosC);
$numMal = $rMaltrato->num_rows;
for ($j = 0; $j < $numMal; $j++) {
    $vecMal = $rMaltrato->fetch_assoc();
    $mal[$j] = $vecMal['id'];
}

//para el menu (da el numero de la derecha de reportes = numero de reportes asignados)
$reportesvd = "SELECT pc.id, ps.id_departamentos_asignado as asigPs, ts.id_departamentos_asignado as asigTs, estadoAtencion
from posible_caso pc left join historico_atenciones_pos_casos aten on aten.id=pc.id_estado_atencion
left join historico_asignaciones_psicologia ps on ps.id=pc.id_asignado_ps
left join historico_asignaciones_trabajo_social ts on ts.id=pc.id_asignado_ts
where (ps.id_departamentos_asignado=$idDEPTO or ts.id_departamentos_asignado=$idDEPTO) and (estadoAtencion=1 or pc.id_estado_atencion=0)";
$erepo = $mysqli->query($reportesvd);
$wow = $erepo->num_rows;

$tmaltrato = "SELECT maltrato, id FROM tipo_maltrato where id!='0' order by id";
$etmaltrato = $mysqli->query($tmaltrato);

if (!empty($_POST['registar'])) {
    foreach ((array) @$_POST["maltrato"] as $valor) {
        @$maltratoN = $valor . ", " . $maltratoN;

    }if (empty($maltratoN)) {
        echo "Seleccione al menos un tipo de maltrato";
    } else {
        $fecha = date("Y-m-d H:i:s", time());
        $calle = mysqli_real_escape_string($mysqli, $_POST['txtCalle']);
        $numExt = mysqli_real_escape_string($mysqli, $_POST['txtNE']);
        $numInt = mysqli_real_escape_string($mysqli, $_POST['txtNI']);
        $ubicacion = mysqli_real_escape_string($mysqli, $_POST['txtubicacion']);
        $descripcion = mysqli_real_escape_string($mysqli, $_POST['txtNarracion']);
        $otros = mysqli_real_escape_string($mysqli, $_POST['txtOtros']);
        $viajaSoloAcomp = $_POST['viaja'];
        $mig = $_POST['migrante'];
        if ($viajaSoloAcomp == "-- SELECCIONE --" || !$mig) {
            $viajaSoloAcomp = "";
        }

        $qhistorico = "INSERT INTO historico_reportes (id_reporte, id_responsable, fecha_actualizacion,
			maltratos, otros, narracion, calle, ubicacion, num_ext, num_int)
			SELECT $idRep, $idDEPTO, '$fecha', maltratos, otros_datos, narracion, calle,
			ubicacion, num_ext, num_interior  from reportes_vd where id=$idRep";
        $rHistorico = $mysqli->query($qhistorico);
        if ($rHistorico) {
            $qActualizar = "UPDATE reportes_vd set respo_actualizacion=$idDEPTO, fecha_actualizacion='$fecha',
				maltratos='$maltratoN' , otros_datos='$otros', narracion='$descripcion', calle='$calle',
				ubicacion='ubicacion', num_ext='$numExt', num_interior='$numInt', viajaSA = '$viajaSoloAcomp', tipo_migrante='$mig' where id=$idRep";
            $rAct = $mysqli->query($qActualizar);
            if ($rAct) {
                echo "<script>
                	alert('La información ha sido actualizada');
window.location= 'perfil_posible_caso.php?idPosibleCaso=$idPc'
</script>";
            } else {
                echo $qActualizar;
            }

        } else {
            echo $qhistorico;
        }

    }
}

?>
<!DOCTYPE HTML>
<html>

<head lang="es-ES">
    <title>Editar reporte</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="shortcut icon" href="images/favicon.png" type="image/png" />
</head>

<body>
    <div id="wrapper">
        <div id="main">
            <div class="inner">
                <div class="box alt" align="center">
                    <div class="row 10% uniform">
                        <div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
                        <div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
                        <div class="4u"><img src="images/armas.jpg" width="80px" height="80px" /></div>
                    </div>
                </div>
                <!--cod -->
                <form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF'];?>" method="POST">
                    <div class="box">
                        <h2>Edicion del reporte <?=$folio?></h2>
                        <div class="row uniform">
                            <div class="3u">
                                <label for="txtFechaReg">Fecha de registro</label>
                                <input type="text" name="txtFechaReg" value="<?=$fec_reg?>" disabled="true">
                            </div>
                            <div class="3u">
                                <label for="txtRecep">Recepcion</label>
                                <input type="text" name="txtRecep" value="<?=$recepcion?>" disabled="true">
                            </div>
                            <div class="2u">
                                <label for="txtDistrito">Distrito</label>
                                <input type="text" name="txtDistrito" value="<?=$distrito?>" disabled="true">
                            </div>
                            <div class="4u">
                                <label for="txtRespoReg">Responsable de registro</label>
                                <input type="text" name="txtRespoReg" value="<?=$responsable?>" disabled="true">
                            </div>
                        </div>
                        <div class="row uniform">
                            <div class="4u">
                                <label for="txtPersona">Persona que reportó</label>
                                <input type="text" name="txtPersona" value="<?=$persona?>" disabled="true">
                            </div>
                            <div class="4u">
                                <label for="txtMun">Municipio</label>
                                <input type="text" name="txtMun" value="<?=$municipio?>" disabled="true">
                            </div>
                            <div class="4u">
                                <label for="txtLocalidad">Localidad</label>
                                <input type="text" name="txtLocalidad" value="<?=$localidad?>" disabled="true">
                            </div>
                        </div><br>
                        <div class="box">
                            <h4>Editar</h4>
                            <div class="row uniform">
                                <div class="6u">
                                    <label for="txtCalle">Vialidad</label>
                                    <input type="text" name="txtCalle" value="<?=$calle?>" maxlength="100"
                                        style="text-transform:uppercase;"
                                        onkeyup="this.value=this.value.toUpperCase();">
                                </div>
                                <div class="3u">
                                    <label for="txtNE">Número exterior</label>
                                    <input type="text" name="txtNE" value="<?=$calle?>" maxlength="20"
                                        style="text-transform:uppercase;"
                                        onkeyup="this.value=this.value.toUpperCase();">
                                </div>
                                <div class="3u">
                                    <label for="txtNI">Número interior</label>
                                    <input type="text" name="txtNI" value="<?=$calle?>" maxlength="20"
                                        style="text-transform:uppercase;"
                                        onkeyup="this.value=this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="row uniform">
                                <div class="12u">
                                    <label for="txtubicacion">Ubicación</label>
                                    <input type="text" name="txtubicacion" value="<?=$ubicacion?>" maxlength="300"
                                        style="text-transform:uppercase;"
                                        onkeyup="this.value=this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="row uniform">
                                <div class="12u">Tipos de maltrato identificados:
                                    <div class="box">
                                        <div class="row justify-content-end">

                                            <?php $i = 0;
while ($rwMal = $etmaltrato->fetch_assoc()) {
    $che = false;
    $idMal = $rwMal['id'];
    for ($j = 0; $j < $numMal; $j++) {
        $idMal2 = $mal[$j];
        if ($idMal2 == $idMal) {
            $che = true;
        }
    }
    ?>
                                            <div class="4u">
                                                <input type="checkbox" id="<?php echo 'demo-' . $i; ?>"
                                                    name="maltrato[]" onchange=" comprobar2(this)"
                                                    value="<?php echo $rwMal['maltrato']; ?>" <?php if ($che) {?>
                                                    checked="true" <?php }?>>
                                                <label for="<?php echo 'demo-' . $i; ?>"><?php echo $rwMal['maltrato'];
    $i++; ?></label>
                                            </div>
                                            <?php $i++;}?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- editar opcion migrantes -->
                            <div class="row uniform" id="divOpcionM" style="display: none;">
                                <div class="12u">
                                    <p>Opciones Migrantes</p>
                                    <div class="box ">
                                        <div class="row miw2">

                                            <input type="checkbox" id="cbox1" name="migrante" onchange="comprobar(this)"
                                                value="NNA MIGRANTE EXTRANJERO">
                                            <label for="cbox1">
                                                NNA MIGRANTE EXTRANJERO
                                            </label>


                                            <input type="checkbox" id="cbox2" name="migrante"
                                                onchange=" comprobar(this)" value="NNA REPATRIADO MEXICANO">
                                            <label for="cbox2">NNA
                                                REPATRIADO MEXICANO
                                            </label>

                                        </div>
                                        <div class="row " id="divSolicitud">
                                            <div class="">Viaja Solo/Acompañado
                                                <div class="select-wrapper">
                                                    <select name="viaja" required="true" id="opciones">
                                                        <option value="" selected="selected">-- SELECCIONE --</option>
                                                        <option value="VIAJA SOLO">VIAJA SOLO</option>
                                                        <option value="VIAJA ACOMPAÑADO">VIAJA ACOMPAÑADO</option>
                                                    </select>
                                                </div>
                                            </div>



                                        </div>

                                    </div>
                                </div>
                                <div class="" style=" text-align: left; padding: 0px; padding-left:20px;">
                                    <label style="color:red;" id="lbopcion"></label>
                                </div>
                            </div>

                            <div class="row uniform">
                                <div class="6u">
                                    <label>Descripcion de la situación</label>
                                    <textarea name="txtNarracion" rows="6" cols="20" maxlength="1000"
                                        style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();"
                                        required><?=$narracion?></textarea>
                                </div>
                                <div class="6u">
                                    <label>Otros datos u observaciones relevantes</label>
                                    <textarea name="txtOtros" rows="6" cols="20" maxlength="1000"
                                        style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();"
                                        required><?=$otros?></textarea>
                                </div>

                            </div>
                        </div>

                    </div>
                    <br>
                    <div class="row uniform">
                        <div class="6u 12u$(xsmall)">
                            <?php if ($idDEPTO == $asignadoJ or $idDEPTO == $asignadoPS or $idDEPTO == $asignadoTS or $idDEPTO == $ResponRegistro or ($idDepartamento == 16 and $idPersonal == 1)) {?>
                            <input class="button special fit" name="registar" type="submit" onclick="quitar();"
                                value="Actualizar">
                            <?php } else {?>
                            <input class="button special fit" name="registar1" type="submit" value="Actualizar"
                                disabled="true">
                            <?php }?>
                        </div>
                        <div class="6u 12u$(xsmall)">
                            <input class="button fit" type="button" name="cancelar" value="Cancelar"
                                onclick="location='perfil_posible_caso.php?idPosibleCaso=<?=$idPc?>'">
                        </div>
                    </div>
                </form>

            </div>
        </div>


        <div id="sidebar">
            <div class="inner">
                <?php $_SESSION['spcargo'] = $idPersonal;?>
                <?php if ($idPersonal == 6) { //UIENNAVD?>
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
                <?php } else if ($idPersonal == 5) { //Subprocu ?>
                <!-- Menu -->
                <nav id="menu">
                    <header class="major">
                        <h2>Menú</h2>
                    </header>
                    <ul>
                        <li><a href="welcome.php">Inicio</a></li>
                        <li><a href="lista_personal.php">Personal</a></li>
                        <li><a href="lista_usuarios.php">Usuarios</a></li>
                        <li><a href="lista_reportes_nueva.php?estRep=0">Reportes
                                VD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $wow; ?></strong></a>
                        </li>
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
                <?php } else {?>
                <!-- Menu -->
                <nav id="menu">
                    <header class="major">
                        <h2>Menú</h2>
                    </header>
                    <ul>
                        <li><a href="welcome.php">Inicio</a></li>
                        <li><a href="lista_personal.php">Personal</a></li>
                        <li><a href="lista_usuarios.php">Usuarios</a></li>
                        <?php if ($_SESSION['departamento'] == 7) {?>
                        <li><a href="canalizar.php">Canalizar visita</a></li>
                        <?php }?>
                        <li><a href="lista_reportes_nueva.php?estRep=0">Reportes
                                VD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?php echo $wow; ?></strong></a>
                        </li>
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
                        <?php if (($_SESSION['departamento'] == 16) or ($_SESSION['departamento'] == 7)) {?>
                        <li><span class="opener">Visitas</span>
                            <ul>
                                <li><a href="editar_visitadepto.php">Editar departamento</a></li>
                                <li><a href="editar_visitarespo.php">Editar responsable</a></li>
                                <li><a href="eliminar_visita.php">Eliminar</a></li>
                            </ul>
                        </li>
                        <?php }?>
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
                        <?php if ($_SESSION['departamento'] == 16 or $_SESSION['departamento'] == 14) {?>
                        <li><a href="reg_actccpi.php">CCPI</a></li>
                        <?php }?>
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
        </div>
    </div>
    <!--cierre de wrapper-->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/skel.min.js"></script>
    <script src="assets/js/util.js"></script>
    <!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
    <script src="assets/js/main.js"></script>
    <script type="text/javascript">
    var cb1 = document.getElementById('cbox1');
    var cb2 = document.getElementById('cbox2');
    cb2.checked = false;
    cb1.checked = false;
    var viaja = "<?php echo $viajaSA ?>";
    var tipoM = "<?php echo $tipoMigrante ?>";


    if (tipoM == "NNA MIGRANTE EXTRANJERO") {
        cb1.checked = true;
    } else if (tipoM == "NNA REPATRIADO MEXICANO") {
        cb2.checked = true;
    }

    if (viaja) {
        if (viaja == 'VIAJA SOLO') {
            $("#opciones").val('VIAJA SOLO')
        } else {
            $("#opciones").val('VIAJA ACOMPAÑADO')
        }
    }
    var check = "";
    var valor = "";





    function quitar() {
        var checkbox = document.getElementById('cbox1');
        var checkbox2 = document.getElementById('cbox2');
        if (check.checked && valor == "MIGRANTES") {
            if (checkbox.checked || checkbox2.checked) {
                var opcion = "";
                document.getElementById('lbopcion').innerHTML = opcion;
                $('#opciones').prop("required", true);
            } else {
                var opcion = "*Seleccione al menos una opción";
                document.getElementById('lbopcion').innerHTML = opcion;
                $('#cbox1').prop("required", true);
            }
        } else {
            $('#opciones').removeAttr("required");
        }

    }

    function comprobar(checkbox) {
        otro = checkbox.parentNode.querySelector("[type=checkbox]:not(#" + checkbox.id + ")");
        document.getElementById('lbopcion').innerHTML = "";
        $('#cbox1').removeAttr("required");

        if (otro.checked) {
            otro.checked = false;
        } else {
            $("#opciones").val('')
        }
    }

    function comprobar2(checkbox) {


        check = document.getElementById(checkbox.id);
        valor = check.value;

        if (check.checked && valor == "MIGRANTES") {
            document.getElementById('lbopcion').innerHTML = "";
            div = document.getElementById('divOpcionM');
            div.style.display = '';
            var checkbox = document.getElementById('cbox1');
            var checkbox2 = document.getElementById('cbox2');
            checkbox.checked = false;
            checkbox2.checked = false;
            $("#opciones").val('')
            $('#cbox1').prop("required", true);

        } else {
            if (!check.checked && valor == "MIGRANTES") {
                div = document.getElementById('divOpcionM');
                div.style.display = 'none';
                $('#cbox1').removeAttr("required");
                $("#opciones").val('')
                cb1.checked == false;
                cb2.checked == false;
            }

        }



    }
    var check1 = document.getElementById('demo-28');
    console.log(check1);
    if (check1.checked == true) {
        div = document.getElementById('divOpcionM');
        div.style.display = '';
    }
    </script>

</body>

</html>