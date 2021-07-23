 <?php    
    session_start();
    require 'conexion.php';
    date_default_timezone_set('America/Mexico_City');
    if(!isset($_SESSION["id"])){
        header("Location: index.php");
    }
    
    $idDEPTO = $_SESSION['id'];    
    $idReporte= $_GET['id'];
    $idNNA= $_GET['idn'];

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

    $sql="SELECT  ps.id_departamentos_asignado as asignado_psic, d.responsable, ts.id_departamentos_asignado as asignado 
    FROM posible_caso pc left join historico_asignaciones_trabajo_social ts on ts.id_posible_caso=pc.id
    inner join historico_asignaciones_psicologia ps on ps.id_posible_caso=pc.id
    inner join departamentos d on d.id=ps.id_departamentos_asignado
    where ps.id_posible_caso='$idReporte'";
    $esql=$mysqli->query($sql);
    while ($row=$esql->fetch_assoc()) {
        $encargado=$row['responsable']; //encargado
        $asig=$row['asignado']; //trabajo socia
        $asigp=$row['asignado_psic']; //id del encargado
    }

    $qExisteAc="SELECT id, ase_virtual, respo_reg from acercamiento_psic where id_reporte='$idReporte' and id_nna='$idNNA' "; //and id>2547";
    $rExisteAc=$mysqli->query($qExisteAc);
    $hayAcer=$rExisteAc->num_rows;
    if($hayAcer){
        while($rwVirtual=$rExisteAc->fetch_assoc()){
            $colVirtual=$rwVirtual['ase_virtual'];
            $respo_reg=$rwVirtual['respo_reg'];
        }
    }
    if (isset($_POST['rdvirtual'])) {
        $virtu = $_POST['rdvirtual']; 
        $qActAcerVir="UPDATE acercamiento_psic set ase_virtual='$virtu' WHERE id_reporte='$idReporte' and id_nna='$idNNA'";
    echo $colVirtual;
        $rActAcerVir=$mysqli->query($qActAcerVir);
        header("Location:acercamiento_psic.php?id=$idReporte&idn=$idNNA");
    }

    $fecha= date("Y-m-d H:i:s", time());
    $valida="SELECT id, date_format(fecha_acercamiento,'%d/%m/%Y') as fecha_acps, 
    date_format(fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha_reg, tipo, apodo, idioma, 
    adulto, numero, narrativa_libre, molestias, temor, adulto_signi, af1, af2, af3, af4,
    af5, af6, dinamica_familiar, area_salud, area_escolar, actividades_diarias, area_social,
    mecanismos, d_vulne, otros 
    from acercamiento_psic where id_reporte='$idReporte' and id_nna='$idNNA'";
    $evalida=$mysqli->query($valida);
    $pva=$evalida->num_rows;
    while ($row=$evalida->fetch_assoc()) {
        $fechaAC=$row['fecha_acps']; //acercamiento de psicologia 
        $fechaREG=$row['fecha_reg'];
        $tipoPSIC=$row['tipo']; //acercamiento o acompañamiento
        $apodo=$row['apodo'];
        $idioma=$row['idioma'];
        $adulto=$row['adulto'];  //adulto responsable
        $numero=$row['numero'];  //telefono
        $narrativa_libre=$row['narrativa_libre'];
        $molestias=$row['molestias'];
        $temor=$row['temor'];
        $adulto_signi=$row['adulto_signi'];
        $raf1=$row['af1'];
        $raf2=$row['af2'];
        $raf3=$row['af3'];
        $raf4=$row['af4'];
        $raf5=$row['af5'];
        $raf6=$row['af6'];
        $dinamica_familiar=$row['dinamica_familiar']; 
        $area_salud=$row['area_salud'];
        $area_escolar=$row['area_escolar'];
        $actividades_diarias=$row['actividades_diarias'];
        $area_social=$row['area_social'];
        $mecanismos=$row['mecanismos'];
        $d_vulne=$row['d_vulne'];
        $otros=$row['otros'];
        //recuperar datos del formulario
    }
          
    $nnaen="SELECT nna_ac.id, nna_ac.nombre, nna_ac.apellido_p, nna_ac.apellido_m, nna_ac.sexo, nna_ac.fecha_nac, nna_ac.lugar_nac, nna_ac.nacionalidad, nna_ac.ocupacion, nna_ac.religion, nna_ac.fecha_registro as fecha_reg, nna_ac.respo_reg FROM nna_ac, part1ac WHERE nna_ac.id_acerca=part1ac.id and part1ac.id_reporte='$idReporte' and nna_ac.id='$idNNA'"; //selecciona datos de nna
    
    $enna=$mysqli->query($nnaen);
    $connna=$enna->num_rows;

    if (!empty($_POST['gfecha'])) { //primer boton, guarda fecha y tipo
        $fecha_ac=$_POST['fecha_ac'];
        $tips=$_POST['tipo'];
        if (empty($fecha_ac) or empty($tips)) {
            echo "HAY CAMPOS VACIOS";
        }else {
        $psql="INSERT into acercamiento_psic (id_reporte, fecha_registro, fecha_acercamiento, 
        tipo, id_nna, respo_reg) 
        values ('$idReporte', '$fecha', '$fecha_ac', '$tips', '$idNNA', '$idDEPTO')";
       
        $epsql=$mysqli->query($psql);
      if ($epsql>0) {
            header("Location: acercamiento_psic.php?id=$idReporte&idn=$idNNA");
        }else {
            echo "ERROR: ".$psql;
        } 
         }
    }
    
    if (!empty($_POST['parte'])) { //segundo boton, guarda apodo, idioma, adulto responsable y telefono
        $apodo=$_POST['apodo'];
        $idioma=$_POST['idioma'];
        $adulto_respo=$_POST['adulto_respo'];
        $telefono=$_POST['telefono'];
        $actu="UPDATE acercamiento_psic set apodo='$apodo', idioma='$idioma', adulto='$adulto_respo', numero='$telefono' where id_reporte='$idReporte' and id_nna='$idNNA'";
        $eactu=$mysqli->query($actu);
        if ($eactu>0) {
            header("Location: acercamiento_psic.php?id=$idReporte&idn=$idNNA");
        }else {
            echo "NO SE REGISTRO LA INFORMACIÓN: ".$actu;
        }
    }
     if (!empty($_POST['Regnarrativa'])) { //boton narrativa
        $tnarra=$_POST['tnarra'];
        
        $actu="UPDATE acercamiento_psic set narrativa_libre='$tnarra' where id_reporte='$idReporte' and id_nna='$idNNA'";
        $eactu=$mysqli->query($actu);
        if ($eactu>0) {
            header("Location: acercamiento_psic.php?id=$idReporte&idn=$idNNA");
        }else {
            echo "NO SE REGISTRO LA INFORMACIÓN: ".$actu;
        }
    }
     if (!empty($_POST['Regmolestias'])) { //boton molestias 
        $tmole=$_POST['tmole'];
        
        $actu="UPDATE acercamiento_psic set molestias='$tmole' where id_reporte='$idReporte' and id_nna='$idNNA'";
        $eactu=$mysqli->query($actu);
        if ($eactu>0) {
            header("Location: acercamiento_psic.php?id=$idReporte&idn=$idNNA");
        }else {
            echo "NO SE REGISTRO LA INFORMACIÓN: ".$actu;
        }
    }
     if (!empty($_POST['Regtemor'])) { //boton temor
        $ttemor=$_POST['ttemor'];
        
        $actu="UPDATE acercamiento_psic set temor='$ttemor' where id_reporte='$idReporte' and id_nna='$idNNA'";
        $eactu=$mysqli->query($actu);
        if ($eactu>0) {
            header("Location: acercamiento_psic.php?id=$idReporte&idn=$idNNA");
        }else {
            echo "NO SE REGISTRO LA INFORMACIÓN: ".$actu;
        }
    }
     if (!empty($_POST['Regadulto'])) { //boton adulto significativo
        $tadulto=$_POST['tadulto'];
        
        $actu="UPDATE acercamiento_psic set adulto_signi='$tadulto' where id_reporte='$idReporte' and id_nna='$idNNA'";
        $eactu=$mysqli->query($actu);
        if ($eactu>0) {
            header("Location: acercamiento_psic.php?id=$idReporte&idn=$idNNA");
        }else {
            echo "NO SE REGISTRO LA INFORMACIÓN: ".$actu;
        }
    }
     if (!empty($_POST['Regaf'])) {  //registro de las preguntas y la dinamca familiar 
        $taf1=$_POST['taf1'];
        $taf2=$_POST['taf2'];
        $taf3=$_POST['taf3'];
        $taf4=$_POST['taf4'];
        $taf5=$_POST['taf5'];
        $taf6=$_POST['taf6'];
        $tdina=$_POST['tdina'];
        
        $actu="UPDATE acercamiento_psic set af1='$taf1', af2='$taf2', af3='$taf3', af4='$taf4', af5='$taf5', af6='$taf6', dinamica_familiar='$tdina' where id_reporte='$idReporte' and id_nna='$idNNA'";
        $eactu=$mysqli->query($actu);
        if ($eactu>0) {
            header("Location: acercamiento_psic.php?id=$idReporte&idn=$idNNA");
        }else {
            echo "NO SE REGISTRO LA INFORMACIÓN: ".$actu;
        }
    }
    if (!empty($_POST['Regsalud'])) {  //registro de salud 
        $tsalud=$_POST['tsalud'];
        
        $actu="UPDATE acercamiento_psic set area_salud='$tsalud' where id_reporte='$idReporte' and id_nna='$idNNA'";
        $eactu=$mysqli->query($actu);
        if ($eactu>0) {
            header("Location: acercamiento_psic.php?id=$idReporte&idn=$idNNA");
        }else {
            echo "NO SE REGISTRO LA INFORMACIÓN: ".$actu;
        }
    }
    if (!empty($_POST['Regescolar'])) { //registro de area escolar 
        $tescolar=$_POST['tescolar'];
        
        $actu="UPDATE acercamiento_psic set area_escolar='$tescolar' where id_reporte='$idReporte' and id_nna='$idNNA'";
        $eactu=$mysqli->query($actu);
        if ($eactu>0) {
            header("Location: acercamiento_psic.php?id=$idReporte&idn=$idNNA");
        }else {
            echo "NO SE REGISTRO LA INFORMACIÓN: ".$actu;
        }
    }
    if (!empty($_POST['Regact'])) { //registro de actividades diarias 
        $tact=$_POST['tact'];
        
        $actu="UPDATE acercamiento_psic set actividades_diarias='$tact' where id_reporte='$idReporte' and id_nna='$idNNA'";
        $eactu=$mysqli->query($actu);
        if ($eactu>0) {
            header("Location: acercamiento_psic.php?id=$idReporte&idn=$idNNA");
        }else {
            echo "NO SE REGISTRO LA INFORMACIÓN: ".$actu;
        }
    }
    if (!empty($_POST['Regsoc'])) {  //registro area social 
        $tsoc=$_POST['tsoc'];
        
        $actu="UPDATE acercamiento_psic set area_social='$tsoc' where id_reporte='$idReporte' and id_nna='$idNNA'";
        $eactu=$mysqli->query($actu);
        if ($eactu>0) {
            header("Location: acercamiento_psic.php?id=$idReporte&idn=$idNNA");
        }else {
            echo "NO SE REGISTRO LA INFORMACIÓN: ".$actu;
        }
    }
    if (!empty($_POST['Regmec'])) { //registro de mecanismos de defensa 
        $tmec=$_POST['tmec'];
        
        $actu="UPDATE acercamiento_psic set mecanismos='$tmec' where id_reporte='$idReporte' and id_nna='$idNNA'";
        $eactu=$mysqli->query($actu);
        if ($eactu>0) {
            header("Location: acercamiento_psic.php?id=$idReporte&idn=$idNNA");
        }else {
            echo "NO SE REGISTRO LA INFORMACIÓN: ".$actu;
        }
    }
    if (!empty($_POST['Regder'])) { //registro de derechos violentados 
        $ninguno=$_POST['ninguno'];
        foreach((array)@$_POST["dere"] as $valor){ 
            @$cder=$valor.", ".$cder;
        }
        if(!empty($ninguno) and !empty($cder)){ ?>
            <script type="text/javascript">
                alert('Error: no puede selecionar algun derecho y la opción "NINGUNO" a la vez');
            </script>
        <?php } 
        else if(empty($ninguno) and empty($cder)){ ?>
            <script type="text/javascript">
                alert('Debe eligir por lo menos una opción');
            </script>
        <?php } else {
            if (!empty($cder)) { //hay algun derecho lo registra
                $actu="UPDATE acercamiento_psic set d_vulne='$cder' where id_reporte='$idReporte' and id_nna='$idNNA'";
            } else if(!empty($ninguno)){ //escoge la opcion ninguno se registra como 21 q es el valor q tiene del catálogo
                $actu="UPDATE acercamiento_psic set d_vulne='21' where id_reporte='$idReporte' and id_nna='$idNNA'";
            }
            $eactu=$mysqli->query($actu);
            if ($eactu>0) {
                header("Location: acercamiento_psic.php?id=$idReporte&idn=$idNNA");
            } else {
                echo "NO SE REGISTRO LA INFORMACIÓN: ".$actu;
            }
        }
    }
    if (!empty($_POST['Regotros'])) { //registro de otros datos u observaciones
        $totros=$_POST['totros'];
        
        $actu="UPDATE acercamiento_psic set otros='$totros' where id_reporte='$idReporte' and id_nna='$idNNA'";
        $eactu=$mysqli->query($actu);
        if ($eactu>0) {
            header("Location: acercamiento_psic.php?id=$idReporte&idn=$idNNA");
        }else {
            echo "NO SE REGISTRO LA INFORMACIÓN: ".$actu;
        }
    }
    
    ?>
<!DOCTYPE HTML>

<html>
    <head>
        <title>Acercamiento psicológico</title>
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
                    <div class="row uniform">
                        <div class="2u 12u$(xsmall)">
                            <input type="button" name="atras" class="button special small" value="Atrás" onclick="location='registro_nna_ac.php?id=<?php echo $idReporte ?>'">
                        </div>
                        <div class="7u 12u$(xsmall)">
                            <h3>Intervencion psicologica: <?php echo @$tipoPSIC; ?></h3>
                        </div>
                        <div class="1.5u 12u$(xsmall)">
                            <?php if ($_SESSION['departamento']==16 or $asigp==$idDEPTO) { ?>
                                <input type="button" name="editar" class="button special small fit" value="Editar" onclick="location='editar_acer_psico.php?id=<?= $idReporte ?>&idn=<?= $idNNA?>'"<?php if(empty($pva)) { ?> disabled <?php } ?>>
                            <?php } ?>
                        </div>
                        <div class="1.5u 12$(xsmall)">
                            <?php if(!empty($otros) and !empty($apodo) and !empty($narrativa_libre) and !empty($molestias) and !empty($temor) and !empty($adulto_signi) and !empty($dinamica_familiar) and !empty($area_salud) and !empty($area_escolar) and !empty($actividades_diarias) and !empty($area_social) and !empty($mecanismos) and !empty($d_vulne)) { ?>
                                <input type="button" class="button special small fit" name="btnImprimir" value="Imprimir" onclick="location='pdfAcercaPsico.php?idReporte=<?php echo $idReporte; ?>&idNNA=<?=$idNNA?>'"> <?php } ?>
                            </div>
                    </div>
                    <br> A cargo de <strong> <?php echo $encargado; ?></strong><br>
                    <?php if($hayAcer>0 and $respo_reg==$idDEPTO) { ?>
                        <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                            <input type="radio" id="vir" name="rdvirtual" value="1" onchange="this.form.submit()" <?php if($colVirtual=='1') { ?> checked <?php } if(!empty($colVirtual)) { ?> disabled <?php } ?>>
                            <label for="vir">Virtual</label>
                            <input type="radio" id="pre" name="rdvirtual" value="0" onchange="this.form.submit()" <?php if($colVirtual=='0') { ?> checked <?php } if(!empty($colVirtual)) { ?> disabled <?php } ?>>
                            <label for="pre">Presencial</label>
                        </form>
                    <?php } ?>
                    <div class="box">
                        <form id="frmfecha" name="fecha" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                            <div class="row uniform">
                                <?php if (empty($pva)) { ?> <!-- si no acercamiento registrado pide los datos de los registros -->
                                    <div class="3u 12u$(xsmall)">
                                        <strong>Fecha de acercamiento:</strong>
                                        <input type="date" name="fecha_ac" placeholder="DD/MM/AAAA" required>
                                    </div>
                                    <div class="6u 12u$(xsmall)">
                                        <br>
                                        <table class="alt">
                                            <thead>
                                                <tr><th></th></tr>
                                                <tr>
                                                    <th><strong>Seleccione una opcion:</strong></th>    
                                                    <th>
                                                        <input type="radio" id="AC" name="tipo" value="ACERCAMIENTO">
                                                        <label for="AC">Acercamiento</label>
                                                        <input type="radio" id="ACP" name="tipo" value="ACOMPAÑAMIENTO">
                                                        <label for="ACP">Acompañamiento</label>
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                    <div class="3u 12u$(xsmall)">
                                        <br>
                                        <?php if ($_SESSION['departamento']==16 or $asigp==$idDEPTO) { ?>
                                            <!--si es control de informacion o el asignado de psico--> 
                                            <input type="submit" name="gfecha" value="Guardar">
                                        <?php } ?>
                                    </div>
                                <?php }else { ?>
                                    <div class="6u 12u$(xsmall)">
                                        <strong>Fecha de acercamiento:</strong> <?php echo $fechaAC; ?>
                                    </div>
                                    <div class="6u 12u$(xsmall)">
                                        <strong>Fecha de registro:</strong> <?php echo $fechaREG; ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </form>

                        <div class="box">
                            <h4>Niñas, Niños y Adolescentes</h4>
                            <?php while ($row=$enna->fetch_assoc()) { ?> <!--mostras datos de nna_ac-->
                                <div class="row uniform">
                                    <div class="7u 12u$(xsmall)">
                                        <label>Nombre:</label>
                                        <input type="text" name="" value="<?php echo $row['nombre'].' '.$row['apellido_p'].' '.$row['apellido_m']; ?>" disabled>
                                    </div>
                                    <div class="2u 12u$(xsmall)">
                                        <label>Sexo:</label>
                                        <input type="text" name="" value="<?php echo $row['sexo']; ?>" disabled>
                                    </div>
                                    <div class="3u 12u$(xsmall)">
                                        <label>Fecha de nacimiento:</label>
                                        <input type="text" name="" value="<?php echo $row['fecha_nac']; ?>" disabled>
                                    </div>
                                </div>
                                <div class="row uniform">
                                    <div class="4u 12u$(xsmall)">
                                        <label>Lugar de nacimiento:</label>
                                        <input type="text" name="" value="<?php echo $row['lugar_nac']; ?>" disabled>
                                    </div>
                                    <div class="4u 12u$(xsmall)">
                                        <label>Ocupación:</label>
                                        <input type="text" name="" value="<?php echo $row['ocupacion']; ?>" disabled>
                                    </div>
                                    <div class="4u 12u$(xsmall)">
                                        <label>Nacionalidad:</label>
                                        <input type="text" name="" value="<?php echo $row['nacionalidad']; ?>" disabled>
                                    </div>
                                </div>
                            <?php } ?>
                            <hr class="major" />
                            <form id="frmapodo" name="frmapodo" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                <?php if (empty($apodo)) { ?> <!--si no existen estos datos los pide-->
                                    <div class="row uniform">
                                        <div class="6u 12u$(xsmall)">
                                            <input name='apodo' type='text' placeholder='¿Cómo le gusta que le llamen?' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required maxlength="20">
                                        </div>
                                        <div class="6u 12u$(xsmall)">
                                            <input name='idioma' type='text' placeholder='Idioma' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required maxlength="30">
                                        </div>
                                    </div>
                                    <div class="row uniform">
                                        <div class="6u 12u$(xsmall)">
                                            <input name='adulto_respo' type='text' placeholder='Adulto responsable' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required maxlength="40">
                                        </div>
                                        <div class="6u 12u$(xsmall)">
                                            <input name='telefono' type='text' placeholder='Teléfono' style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required maxlength="20">
                                        </div>
                                    </div>
                                    <?php if ($_SESSION['departamento']==16 or $asigp==$idDEPTO) { ?>
                                        <div class="row uniform">
                                            <div class="12u 12u$(xsmall)">
                                                <input type="submit" name="parte" value="Guardar" <?php if(empty($pva)) { ?> disabled <?php } ?> >
                                            </div>
                                        </div>
                                    <?php }
                                }else { ?> <!--hay registro de estos datos solo los muestra-->
                                    <div class="row uniform">
                                        <div class="6u 12u$(xsmall)">
                                            ¿Como le gusta que le llamen?
                                            <input name='apodo' type='text' placeholder='¿Cómo le gusta que le llamen?' value="<?php echo $apodo; ?>" disabled>
                                        </div>
                                        <div class="6u 12u$(xsmall)">Idioma
                                            <input name='idioma' type='text' placeholder='Idioma' value="<?php echo $idioma; ?>" disabled>
                                        </div>
                                    </div>
                                    <div class="row uniform">
                                        <div class="6u 12u$(xsmall)">Adulto responsable
                                            <input name='adulto_respo' type='text' placeholder='Adulto responsable' value="<?php echo $adulto; ?>" disabled>
                                        </div>
                                        <div class="6u 12u$(xsmall)">Numero de telefono
                                            <input name='telefono' type='text' placeholder='Teléfono' value="<?php echo $numero; ?>" disabled>
                                        </div>
                                    </div>
                                <?php } ?>
                            </form>
                        </div>

                        <div class="box">
                            <h4>Registro textual de la narrativa libre o proyeccion a traves del juego y/o dibujo</h4>
                            <?php if (empty($narrativa_libre)) { ?>
                                <form id="frmnarrativa" name="frmnarrativa" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                    <div class="row uniform">
                                        <div class="12u$">
                                            <textarea name="tnarra" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required maxlength="1500"></textarea>
                                        </div>
                                    </div>
                                    <?php if ($_SESSION['departamento']==16 or $asigp==$idDEPTO) { ?>
                                        <div class="row uniform">
                                            <div class="12u$">
                                                <input type="submit" name="Regnarrativa" value="Guardar" <?php if (empty($pva)) { ?>disabled <?php } ?> >
                                            </div>
                                        </div>
                                    <?php } ?>
                                </form>
                            <?php } else { ?>
                                <strong><?php echo $narrativa_libre; ?></strong>
                            <?php } ?> 
                        </div>

                        <div class="box">
                            <h4>Registro textual de lo que dijo al preguntarle si hay algo que le molesta o lastima</h4>
                            <?php if (empty($molestias)) { ?>
                                <form id="frmmolestia" name="frmmolestia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                    <div class="row uniform">
                                        <div class="12u$">
                                            <textarea name="tmole" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required maxlength="1500"></textarea>
                                        </div>
                                    </div>
                                    <?php if ($_SESSION['departamento']==16 or $asigp==$idDEPTO) { ?>
                                        <div class="row uniform">
                                            <div class="12u$">
                                                <input type="submit" name="Regmolestias" value="Guardar"   <?php if (empty($pva)) { ?> disabled <?php } ?>>
                                            </div>
                                        </div>
                                    <?php  } ?>
                                </form>
                            <?php } else { ?>
                                <strong><?= $molestias; ?></strong>
                            <?php } ?>
                        </div>

                        <div class="box">
                            <h4>¿A quien dice (o muestra) temer?</h4>
                            <?php if (empty($temor)) { ?>
                                <form id="frmtemor" name="frmtemor" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                    <div class="row uniform">
                                        <div class="12u$">
                                            <textarea name="ttemor" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" maxlength="1500" required></textarea>
                                        </div>
                                    </div>
                                    <?php if ($_SESSION['departamento']==16 or $asigp==$idDEPTO) { ?>
                                        <div class="row uniform">
                                            <div class="12u$">
                                                <input type="submit" name="Regtemor" value="Guardar" <?php if (empty($pva)) { ?> disabled <?php } ?> >
                                            </div>
                                        </div>
                                    <?php } ?>
                                </form>
                            <?php } else { ?>
                                <strong><?= $temor; ?></strong>
                            <?php } ?>
                        </div>

                        <div class="box">
                            <h4>¿Que adulto le resulta significativo y quiere tener cerca de si?(Hay adultos que sean de su confianza)</h4>
                            <?php if (empty($adulto_signi)) { ?>
                                <form id="frmadulto" name="frmadulto" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                    <div class="row uniform">
                                        <div class="12u$">
                                            <textarea name="tadulto" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required maxlength="1500"></textarea>
                                        </div>
                                    </div>
                                    <?php if ($_SESSION['departamento']==16 or $asigp==$idDEPTO) { ?>
                                        <div class="row uniform">
                                            <div class="12u$">
                                                <input type="submit" name="Regadulto" value="Guardar" <?php if (empty($pva)) { ?> disabled <?php } ?>>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </form>
                            <?php } else { ?>
                                <strong><?= $adulto_signi; ?></strong>
                            <?php } ?>
                        </div>

                        <div class="box">
                            <h4>Area Familiar</h4>
                            <?php if (empty($raf1)) { ?>
                                <form id="frmfamilia" name="frmfamilia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                    <div class="row uniform">
                                        <div class="12u$">¿Ha dejado de ver a alguien que quiere mucho? ¿Quien (es)? ¿Porque?
                                            <textarea name="taf1" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required maxlength="1000"></textarea>
                                        </div>
                                    </div>
                                    <div class="row uniform">
                                        <div class="12u$">¿Que pasa en casa cuando opina sobre algo?
                                            <textarea name="taf2" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required maxlength="1000"></textarea>
                                        </div>
                                    </div>
                                    <div class="row uniform">
                                        <div class="12u$">¿Quien lo cuida la mayor parte del tiempo?
                                            <textarea name="taf3" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required maxlength="1000"></textarea>
                                        </div>
                                    </div>
                                    <div class="row uniform">
                                        <div class="12u$">¿Que sucede cuando se porta mal?
                                            <textarea name="taf4" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required maxlength="1000"></textarea>
                                        </div>
                                    </div>
                                    <div class="row uniform">
                                        <div class="12u$">¿Ha visto peleas o cualquier otro tipo de violencia?
                                            <textarea name="taf5" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required maxlength="1000"></textarea>
                                        </div>
                                    </div>
                                    <div class="row uniform">
                                        <div class="12u$">¿Ha recibido golpes o insultos? ¿De quien?
                                            <textarea name="taf6" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required maxlength="1000"></textarea>
                                        </div>
                                    </div>
                                    <div class="row uniform">
                                        <div class="12u$">Dinamica Familiar
                                            <textarea name="tdina" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required maxlength="1500"></textarea>
                                        </div>
                                    </div>
                                    <?php if ($_SESSION['departamento']==16 or $asigp==$idDEPTO) { ?>
                                        <div class="row uniform">
                                            <div class="12u$">
                                                <input type="submit" name="Regaf" value="Guardar" <?php if (empty($pva)) { ?> disabled <?php } ?>>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </form>
                            <?php } else { ?>
                                <div class="row uniform">
                                    <div class="12u$">¿Ha dejado de ver a alguien que quiere mucho? ¿Quien (es)? ¿Porque?
                                        <textarea name="af1" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled><?php echo $raf1; ?></textarea>
                                    </div>
                                </div>
                                <div class="row uniform">
                                    <div class="12u$">¿Que pasa en casa cuando opina sobre algo?
                                        <textarea name="af2" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled><?php echo $raf2; ?></textarea>
                                    </div>
                                </div>
                                <div class="row uniform">
                                    <div class="12u$">¿Quien lo cuida la mayor parte del tiempo?
                                        <textarea name="af3" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled><?php echo $raf3; ?></textarea>
                                    </div>
                                </div>
                                <div class="row uniform">
                                    <div class="12u$">¿Que sucede cuando se porta mal?
                                        <textarea name="af4" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled><?php echo $raf4; ?></textarea>
                                    </div>
                                </div>
                                <div class="row uniform">
                                    <div class="12u$">¿Ha visto peleas o cualquier otro tipo de violencia?
                                        <textarea name="af5" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled><?php echo $raf5; ?></textarea>
                                    </div>
                                </div>
                                <div class="row uniform">
                                    <div class="12u$">¿Ha recibido golpes o insultos? ¿De quien?
                                        <textarea name="af6" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" disabled><?php echo $raf6; ?></textarea>
                                    </div>
                                </div>
                                <div class="row uniform">
                                    <div class="12u$">Dinamica Familiar
                                        <textarea name="dinamica" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();"><?php echo $dinamica_familiar; ?></textarea>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="box">
                            <h4>Area de salud (Asistencia medica/psicologica/otros)</h4>
                            <?php if (empty($area_salud)) { ?>
                                <form id="frmsalud" name="frmsalud" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                    <div class="row uniform">
                                        <div class="12u">
                                            <textarea name="tsalud" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required maxlength="1500"></textarea>
                                        </div>
                                    </div>
                                    <?php if ($_SESSION['departamento']==16 or $asigp==$idDEPTO) { ?>
                                        <div class="row uniform">
                                            <div class="12u$">
                                                <input type="submit" name="Regsalud" value="Guardar" <?php if (empty($pva)) { ?> disabled <?php } ?>>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </form>
                            <?php } else { ?>
                                <strong><?= $area_salud; ?></strong>
                            <?php } ?>
                        </div>

                        <div class="box">
                            <h4>Area escolar</h4>
                            <?php if (empty($area_escolar)) { ?>
                                <form id="frmescolar" name="frmescolar" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                    <div class="row uniform">
                                        <div class="12u">
                                            <textarea name="tescolar" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" placeholder="¿Va a la escuela? ¿Que refiere de sus amistades y profesores? ¿Cual es su clase favorita? ¿Cual es la clase que se le dificulta?" required maxlength="1500"></textarea>
                                        </div>
                                    </div>
                                    <?php if ($_SESSION['departamento']==16 or $asigp==$idDEPTO) { ?>
                                        <div class="row uniform">
                                            <div class="12u$">
                                                <input type="submit" name="Regescolar" value="registrar" <?php if (empty($pva)) { ?> disabled <?php } ?>>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </form>
                            <?php } else { ?>
                                <strong><?php echo $area_escolar; ?></strong>
                            <?php } ?>
                        </div>

                        <div class="box">
                            <h4>Actividades diarias</h4>
                            <?php if (empty($actividades_diarias)) { ?>
                                <form id="frmDiario" name="frmDiario" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                    <div class="row uniform">
                                        <div class="12u">
                                            <textarea name="tact" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" placeholder="Habitos alimenticios, ¿A que hora se duerme? ¿A que hora se levanta?" required maxlength="1500"></textarea>
                                        </div>
                                    </div>
                                    <?php if ($_SESSION['departamento']==16 or $asigp==$idDEPTO) { ?>
                                        <div class="row uniform">
                                            <div class="12u$">
                                                <input type="submit" name="Regact" value="registrar" <?php if (empty($pva)) { ?> disabled <?php } ?>>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </form>
                            <?php } else { ?>
                                <strong><?php echo $actividades_diarias; ?></strong>
                            <?php } ?>
                        </div>

                        <div class="box">
                            <h4>Area social (Juego/actividades recreativas)</h4>
                            <?php if (empty($area_social)) { ?>
                                <form id="frmSocial" name="frmSocial" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                    <div class="row uniform">
                                        <div class="12u">
                                            <textarea name="tsoc" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" placeholder="¿Con quien juega? ¿A que juega? ¿Cuando juega? ¿Que actividades realiza?" required maxlength="1500"></textarea>
                                        </div>
                                    </div>
                                    <?php if ($_SESSION['departamento']==16 or $asigp==$idDEPTO) { ?>
                                        <div class="row uniform">    
                                            <div class="12u$">
                                                <input type="submit" name="Regsoc" value="registrar" <?php if (empty($pva)) { ?>disabled <?php } ?> >
                                            </div>
                                        </div>
                                    <?php } ?>
                                </form>
                            <?php } else { ?>
                                <strong><?php echo $area_social; ?></strong>
                            <?php } ?>
                        </div>

                        <div class="box">
                            <h4>Mecanismos de defensa identificados</h4>
                            <?php if (empty($mecanismos)) { ?>
                                <form id="frmMeca" name="frmMeca" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                    <div class="row uniform">
                                        <div class="12u">
                                            <textarea name="tmec" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required maxlength="1500"></textarea>
                                        </div>
                                    </div>
                                    <?php if ($_SESSION['departamento']==16 or $asigp==$idDEPTO) { ?>
                                        <div class="row uniform">
                                            <div class="12u$">
                                                <input type="submit" name="Regmec" value="registrar" <?php if (empty($pva)) { ?> disabled <?php } ?>>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </form>
                            <?php } else { ?>
                                <strong><?php echo $mecanismos; ?></strong> 
                            <?php } ?>
                        </div>

                        <div class="box">
                            <h4>Derechos vulnerados identificados</h4>
                            <?php if (empty($d_vulne)) { ?>
                                <form id="frmDerechos" name="frmDerechos" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                    <div class="row uniform"> 
                                        <div class="table-wrapper">
                                            <table>
                                                <?php $der="SELECT id, derecho from derechos_nna WHERE id!=21";
                                                $eder=$mysqli->query($der);
                                                while($row=$eder->fetch_assoc()){ $dv='dere'.$row['id']; ?>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" id="<?php echo $dv; ?>" name="dere[]" value='<?php echo $row['id'];?>'>
                                                        <label for="<?php echo $dv; ?>"><?php echo $row['derecho']; ?></label>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" id="ninguno" name="ninguno" value='21'>
                                                        <label for="ninguno">NINGUNO</label>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <?php if ($_SESSION['departamento']==16 or $asigp==$idDEPTO) { ?>
                                        <div class="row uniform">
                                            <div class="12u$">
                                                <input type="submit" name="Regder" value="registrar" <?php if (empty($pva)) { ?> disabled <?php } ?>>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </form>
                            <?php }else { ?>
                                <strong>
                                    <?php $dv=$d_vulne;
                                    $vder=explode(",", $dv); //array 5 
                                    $ext=count($vder); //5
                                    $cdv=0;
                                    for ($i=0; $i <$ext ; $i++) { 
                                        $cdv=$vder[$i];
                                        $sql="SELECT derecho from derechos_nna where id='$cdv'";
                                        $esql=$mysqli->query($sql);
                                        while ($row=$esql->fetch_assoc()) {
                                            echo $row['derecho'].'<br>';
                                        }
                                    } ?>
                                </strong> 
                            <?php } ?>
                        </div>

                        <div class="box">
                            <h4>Otros datos u observaciones relevantes</h4>
                            <?php if (empty($otros)) { ?>
                                <form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                                    <div class="row uniform">
                                        <div class="12u">
                                            <textarea name="totros" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required></textarea>
                                        </div>
                                    </div>
                                    <?php if ($_SESSION['departamento']==16 or $asigp==$idDEPTO) { ?>
                                        <div class="row uniform">
                                            <div class="12u$">
                                                <input type="submit" name="Regotros" value="registrar" <?php if (empty($pva)) { ?> disabled <?php } ?>>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </form>
                            <?php } else { ?>
                                <strong><?php echo $otros; ?></strong>
                            <?php } ?>
                        </div>
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
        </div>  <!--cierre de wrapper-->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/skel.min.js"></script>
        <script src="assets/js/util.js"></script>
        <!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
        <script src="assets/js/main.js"></script>
    </body>
</html>
