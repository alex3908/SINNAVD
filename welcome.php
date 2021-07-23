	<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION["id"])) {
    header("Location: index.php");
}

$idDEPTO = $_SESSION['id'];

$sql = "SELECT id, responsable, id_depto, id_personal FROM departamentos WHERE id= '$idDEPTO'";
$result = $mysqli->query($sql);
$result2 = $mysqli->query($sql);

@$buscar = $_POST['palabra'];

while ($row = $result->fetch_assoc()) {
    $idPersonal = $row['id'];
    $mi = $row['id_depto'];
    $cargo = $row['id_personal'];
    $nom = $row['responsable'];
    if ($cargo == 2 or $cargo == 1) { //recepcioista y administrador
        $listaUsuariosNull = "SELECT historial.id, historial.id_usuario, usuarios.nombre, usuarios.apellido_p, usuarios.apellido_m, depto.departamento, historial.asunto, historial.fecha_ingreso, historial.fecha_salida, historial.responsable FROM historial, usuarios, depto WHERE depto.id=historial.id_departamento && usuarios.id=historial.id_usuario && historial.fecha_salida is null";
        $rUsuariosNull = $mysqli->query($listaUsuariosNull);
        $listaUsuariosNull2 = "SELECT historial.id, historial.id_usuario, usuarios.nombre, usuarios.apellido_p, usuarios.apellido_m, depto.departamento, historial.asunto, historial.fecha_ingreso, historial.fecha_salida, departamentos.responsable FROM historial, usuarios, depto, departamentos WHERE depto.id=historial.id_departamento && departamentos.id=historial.responsable && usuarios.id=historial.id_usuario && historial.fecha_salida is null";
        $rUsuariosNull2 = $mysqli->query($listaUsuariosNull2);
    } else if ($cargo == 3) { //administrativo

        $listaUsuariosNull = "SELECT historial.id, historial.id_usuario, usuarios.nombre, usuarios.apellido_p, usuarios.apellido_m, depto.departamento, historial.asunto, historial.fecha_ingreso, historial.fecha_salida, departamentos.responsable FROM historial, usuarios, depto, departamentos WHERE depto.id=historial.id_departamento && historial.responsable=departamentos.id && usuarios.id=historial.id_usuario && historial.id_departamento=$mi && historial.fecha_salida is null";
        $rUsuariosNull = $mysqli->query($listaUsuariosNull);

        $listaUsuariosNull2 = "SELECT historial.id, historial.id_usuario, usuarios.nombre, usuarios.apellido_p, usuarios.apellido_m, depto.departamento, historial.asunto, historial.fecha_ingreso, historial.fecha_salida, departamentos.responsable FROM historial, usuarios, depto, departamentos WHERE depto.id=historial.id_departamento && historial.responsable=departamentos.id && usuarios.id=historial.id_usuario && historial.id_departamento=$mi && historial.fecha_salida is null";
        $rUsuariosNull2 = $mysqli->query($listaUsuariosNull2);
    } else if ($cargo == 6) { //unidad

        $listaBeneficiariosNull = "SELECT visitas_unidad.id, visitas_unidad.id_benef, visitas_unidad.responsable, visitas_unidad.tipo, visitas_unidad.asunto, visitas_unidad.fecha, benef_unidad.folio, benef_unidad.nombre, benef_unidad.apellido_p, benef_unidad.apellido_m, cat_asuntos_unidad.asunto FROM visitas_unidad, benef_unidad, cat_asuntos_unidad WHERE visitas_unidad.id_benef=benef_unidad.id && visitas_unidad.asunto=cat_asuntos_unidad.id";
        $rBeneficiariosNull = $mysqli->query($listaBeneficiariosNull);
        $listaBeneficiariosNull2 = "SELECT visitas_unidad.id, visitas_unidad.id_benef, visitas_unidad.responsable, visitas_unidad.tipo, visitas_unidad.asunto, visitas_unidad.fecha, benef_unidad.folio, benef_unidad.nombre, benef_unidad.apellido_p, benef_unidad.apellido_m, cat_asuntos_unidad.asunto FROM visitas_unidad, benef_unidad, cat_asuntos_unidad WHERE visitas_unidad.id_benef=benef_unidad.id && visitas_unidad.asunto=cat_asuntos_unidad.id";
        $rBeneficiariosNull2 = $mysqli->query($listaBeneficiariosNull2);

    } else { //atencion a usuarios

        $listaUsuariosNull = "SELECT historial.id, historial.id_usuario, usuarios.nombre, usuarios.apellido_p, usuarios.apellido_m, depto.departamento, historial.asunto, historial.fecha_ingreso, historial.fecha_salida, historial.responsable FROM historial, usuarios, depto WHERE depto.id=historial.id_departamento && usuarios.id=historial.id_usuario && historial.id_departamento=$mi && historial.fecha_salida is null AND ( historial.responsable='$nom' OR historial.responsable is null)";
        $rUsuariosNull = $mysqli->query($listaUsuariosNull);
        $listaUsuariosNull2 = "SELECT historial.id, historial.id_usuario, usuarios.nombre, usuarios.apellido_p, usuarios.apellido_m, depto.departamento, historial.asunto, historial.fecha_ingreso, historial.fecha_salida, departamentos.responsable FROM historial, usuarios, depto, departamentos WHERE depto.id=historial.id_departamento && usuarios.id=historial.id_usuario && historial.responsable=departamentos.id && historial.id_departamento=$mi && historial.fecha_salida is null ";
        $rUsuariosNull2 = $mysqli->query($listaUsuariosNull2);
    }
    $par = "SELECT departamentos.id from departamentos, depto, personal where departamentos.id_personal=personal.id and departamentos.id_depto=depto.id and departamentos.id_depto='10' and departamentos.id_personal='3' and departamentos.id='$idDEPTO'";
    $epar = $mysqli->query($par);
    $eparrows = $epar->num_rows;

    $car = "SELECT personal FROM personal WHERE id='$cargo'";
    $care = $mysqli->query($car);

    $cuenta = "SELECT count(id) as cuenta, count(fecha_salida) as cuenta2, count(atencion_brindada) as cuenta3 FROM historial";
    $ejecuenta = $mysqli->query($cuenta);

    $cuenta2 = "SELECT count(id) as cuenta FROM historial WHERE fecha_salida is null";
    $ejecuenta2 = $mysqli->query($cuenta2);

    $cuentaUnidad = "SELECT count(id) as cuentaUnidad from visitas_unidad";
    $QcuentaUnidad = $mysqli->query($cuentaUnidad);

    $reportes = "SELECT count(id) as cuenta from reportes_int where estado='0'";
    $ereportes = $mysqli->query($reportes);
    while ($row = $ereportes->fetch_assoc()) {
        $pendi = $row['cuenta'];
    }
    $reportesvd = "SELECT pc.id, ps.id_departamentos_asignado as asigPs, ts.id_departamentos_asignado as asigTs, estadoAtencion
from posible_caso pc left join historico_atenciones_pos_casos aten on aten.id=pc.id_estado_atencion
left join historico_asignaciones_psicologia ps on ps.id=pc.id_asignado_ps
left join historico_asignaciones_trabajo_social ts on ts.id=pc.id_asignado_ts
where (ps.id_departamentos_asignado=$idDEPTO or ts.id_departamentos_asignado=$idDEPTO) and (estadoAtencion=1 or pc.id_estado_atencion=0)";
    $erepo = $mysqli->query($reportesvd);
    $wow = $erepo->num_rows;
    ?>
	<!DOCTYPE HTML>

	<html>

	<head>
	    <title>Inicio</title>
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
	                <br> <br>
	                <div class="box alt" align="center">
	                    <div class="row 10% uniform">
	                        <div class="3u"><img src="images/crece.jpg" width="80px" height="75px" /></div>
	                        <div class="3u"><img src="images/dif.jpg" width="45px" height="75px"
	                                onclick="location='reporte1ra.php'" /></div>
	                        <div class="3u"><img src="images/armas.jpg" width="80px" height="80px"
	                                onclick="location='reporte1ra.php'" /></div>
	                        <div class="3u"><img src="images/logo.png" width="120px" height="78px"></div>
	                    </div>
	                    <div class="box">
	                        <h2>-Próximo corte-</h2>
	                        <div class="uniform row">
	                            <?php $corte = "SELECT mes, date_format(fechai, '%d/%m/%Y') as fechai, date_format(fechac,'%d/%m/%Y') as fechac, mensaje from cortes where bandera=1";
    $ecorte = $mysqli->query($corte);
    while ($row = $ecorte->fetch_assoc()) {?>
	                            <div class="3u"><strong>Mes:</strong> <?php echo $row['mes']; ?></div>
	                            <div class="4u"><strong>Periodo:</strong>
	                                <?php echo $row['fechai'] . " - " . $row['fechac']; ?></div>
	                            <div class="5u"><?php echo $row['mensaje']; ?></div>
	                            <?php }?>

	                        </div>
	                    </div>
	                </div>
	                <div class="row uniform">
	                    <div class="3u 12u$(xsmall)">
	                        <center>
	                            <strong>Visitas totales:</strong>
	                            <?php while ($row = $ejecuenta->fetch_assoc()) {?>
	                            <h4><?php echo $row['cuenta']; ?></h4>
	                        </center>
	                    </div>
	                    <div class="3u 12u$(xsmall)">
	                        <center>
	                            <strong>Visitas terminadas:</strong>

	                            <h4><?php echo $row['cuenta2']; ?></h4>
	                        </center>
	                    </div><?php }?>
	                    <div class="3u 12u$(xsmall)">
	                        <center>
	                            <strong>Visitas pendientes:</strong>
	                            <?php while ($row = $ejecuenta2->fetch_assoc()) {?>
	                            <h4> <?php echo $row['cuenta']; ?></h4>
	                            <?php }?>
	                        </center>
	                    </div>
	                    <div class="3u 12u$(xsmall)">
	                        <center>
	                            <strong>Visitas UIENNAVD:</strong>

	                            <?php while ($row = $QcuentaUnidad->fetch_assoc()) {?>
	                            <h4><?php echo $row['cuentaUnidad']; ?></h4>
	                            <?php }?>
	                        </center>
	                    </div>
	                </div>
	                <?php while ($row = $ejecuenta->fetch_assoc()) {
        echo $row['cuenta'];
    }
    while ($row = $result2->fetch_assoc()) {?>
	                <h3 align="center">BIENVENIDO(A): <a href="informe_personal.php">
	                        <?php echo $row['responsable'];} ?></a><br><?php }
while ($row = $care->fetch_assoc()) {
    echo $row['personal'];
}?></h3>




	                <div class="box">
	                    <?php if ($cargo == 6) {?>
	                    <!--mostrar para UIENNAVD-->


	                    <div class="table-wrapper">
	                        <h4>Visitas</h4>
	                        <table class="alt">

	                            <thead>
	                                <tr>
	                                    <th>FOLIO</th>
	                                    <th>NOMBRE</th>
	                                    <th>RESPONSABLE</th>
	                                    <th>TIPO</th>
	                                    <th>ASUNTO</th>
	                                    <th>FECHA DE INGRESO</th>

	                                </tr>
	                            </thead>
	                            <tbody>
	                                <?php while ($row = $rBeneficiariosNull2->fetch_assoc()) {?>

	                                <tr>
	                                    <td><?php echo $row['folio']; ?></td>
	                                    <td><?php echo $row['nombre'] . " " . $row['apellido_p'] . " " . $row['apellido_m']; ?>
	                                    </td>
	                                    <td><?php echo $row['responsable']; ?></td>
	                                    <td><?php echo $row['tipo']; ?></td>

	                                    <td><?php echo $row['asunto']; ?></td>

	                                    <td><?php echo $row['fecha']; ?></td>


	                                </tr>
	                                <?PHP }?>

	                            </tbody>

	                        </table>
	                    </div>
	                    <?php } else {?>


	                    <div class="table-wrapper">
	                        <h4>Pendientes</h4>
	                        <table class="alt">

	                            <thead>
	                                <tr>

	                                    <th>FOLIO</th>
	                                    <th>NOMBRE</th>

	                                    <th>DEPARTAMENTO</th>
	                                    <th>RESPONSABLE</th>
	                                    <th>ASUNTO</th>

	                                    <th>FECHA DE INGRESO</th>
	                                    <th>FECHA DE SALIDA</th>

	                                </tr>
	                            </thead>
	                            <tbody><?php while ($row = $rUsuariosNull2->fetch_assoc()) {
    $res = $row['responsable'];?>
	                                <tr>
	                                    <td><?php echo $row['id_usuario']; ?></td>
	                                    <td><?php echo $row['nombre'] . " " . $row['apellido_p'] . " " . $row['apellido_m']; ?>
	                                    </td>
	                                    <td><?php echo $row['departamento']; ?></td>
	                                    <td><?php echo $row['responsable']; ?></td>

	                                    <td><?php echo $row['asunto']; ?></td>

	                                    <td><?php echo $row['fecha_ingreso']; ?></td>

	                                    <?php
if ($mi == 7 or $mi == 16) {?>

	                                    <td><span class="button special disabled">Visita en curso</span></td>

	                                    <?php } else if ($cargo == 3 or $cargo == 5) { //administrativo y subprocu
        if (empty($res)) {?>

	                                    <td><input type="button" class="button special fit small" name="Terminar visita"
	                                            value="Canalizar visita"
	                                            onclick="location='canalizar_visita.php?id=<?php echo $row['id']; ?>'">
	                                    </td>
	                                    <?php } else if ($row['responsable'] == $nom) {?>
	                                    <td><input type="button" class="button special fit small" name="Terminar visita"
	                                            value="Terminar visita"
	                                            onclick="location='terminar_visita.php?id=<?php echo $row['id']; ?>'">
	                                    </td> <?php } else {?>
	                                    <td><span class="button special disabled">Visita canalizada</span></td>


	                                    <?php }

    } else {
        if (empty($res)) {?>

	                                    <td><span class="button special disabled">No asignada</span></td>

	                                    <?php } else {
            if ($row['responsable'] == $nom) {?>
	                                    <td><input type="button" class="button special fit small" name="Terminar visita"
	                                            value="Terminar visita"
	                                            onclick="location='terminar_visita.php?id=<?php echo $row['id']; ?>'">
	                                    </td>
	                                    <?php } else {?>
	                                    <td></td>

	                                    <?php }}}?>
	                                </tr>
	                                <?PHP }?>
	                            </tbody>
	                        </table>
	                    </div>
	                    <?php }?>
	                </div>
	                <br>

	            </div>
	        </div>

	        <!-- Sidebar -->
	        <div id="sidebar">
	            <div class="inner">
	                <?php $_SESSION['spcargo'] = $cargo;?>
	                <?php if ($cargo == 6) { //UIENNAVD?>
	                <!-- Menu -->
	                <nav id="menu">
	                    <header class="major">
	                        <h2>Menú</h2>
	                    </header>
	                    <ul>
	                        <li><a href="lista_unidad.php">UIENNAVD</a></li>
	                        <li><a href="logout.php">Cerrar sesión</a></li>
	                    </ul>
	                </nav>
	                <?php } else if ($cargo == 5) { //Subprocu ?>
	                <!-- Menu -->
	                <nav id="menu">
	                    <header class="major">
	                        <h2>Menú</h2>
	                    </header>
	                    <ul>
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