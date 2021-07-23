<?php	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: index.php");
    }
    $idDEPTO = $_SESSION['id'];
    $idNnaR=($_GET['idNnaR']);
    
    $idPc=($_GET['idPc']);
    date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();
	
	$pv="SELECT id_depto, id_personal from departamentos where id='$idDEPTO'";
	$epv=$mysqli->query($pv);
	while ($row=$epv->fetch_assoc()) {
		$idDepartamento=$row['id_depto'];
		$idPersonal=$row['id_personal'];
    }
    $qVerificarName="SELECT num_control_coespo from relacion_names where id_nna_reportado=$idNnaR";
    $rVerificarName=$mysqli->query($qVerificarName);

    
	//para el menu (da el numero de la derecha de reportes = numero de reportes asignados)
	$reportesvd="SELECT id from reportes_vd where atendido='1' 
    and (asignado='$idDEPTO' or asignado_psic='$idDEPTO')";
    $erepo=$mysqli->query($reportesvd);
    $wow=$erepo->num_rows;

    $qNna="SELECT id_posible_caso, nombre, apellido_p, apellido_m, sexo.id as sexId, sexo.sexo, fecha_nacimiento as fecha_nac, 
    lugar_nacimiento, lugar_registro
    from nna_reportados left join sexo on nna_reportados.sexo=sexo.id
    where nna_reportados.id='$idNnaR'"; //toma los daos del nna de la taba nna_reportados
    $rNna=$mysqli->query($qNna);
    while ($row=$rNna->fetch_assoc()) {
        $nombre=$row['nombre'];
        $apellido_p=$row['apellido_p'];
        $apellido_m=$row['apellido_m'];
        $sexId=$row['sexId'];
        $sexo=$row['sexo'];
        $fecha_nac=$row['fecha_nac'];
        $lugar_nac=$row['lugar_nacimiento'];
        $lugar_reg=$row['lugar_registro'];
    }
    $qName="SELECT id, id_name from relacion_names where id_nna_reportado='$idNnaR' and activo=1";
	$rName=$mysqli->query($qName);
    $numName= $rName->num_rows;
    if($numName>0){
        while ($ridName=$rName->fetch_assoc()) {
            $idName=$ridName['id_name'];
        }
        $urlReporte ='https://name-pruebas.inftelapps.com/api/reportes'; // si es name cra el cliente y toma datos de ws
        $jsonReporte = file_get_contents($urlReporte);        
        $arrReporte = json_decode($jsonReporte);
        $numReportes= count($arrReporte);  //obtiene el numero de reportes en el siname
        for($i=0; $i<$numReportes; $i++) //recorre las posiciones del json para encontrar a cual le correponde al nna que se quiere vincular
        {
            $idArr=$arrReporte[$i]->id;
            if($idName==$idArr){
              $num=$i; //obtiene la posicion del Json 
             $i=$numReportes;
            }
        }
        $nombre=($arrReporte[$num]->nombres);
        $apellido_p=($arrReporte[$num]->primerApellido);
        $apellido_m=($arrReporte[$num]->segundoApellido);       
        $fecha_nac=($arrReporte[$num]->fechaNacimiento);
        $idEstNac=($arrReporte[$num]->entidadNacimiento->clave);
        $qclaveEdo="SELECT clave from estados where id='$idEstNac'";
        $rclaveEdo=$mysqli->query($qclaveEdo);
        while ($rowClave=$rclaveEdo->fetch_assoc()) {
            $claveEdo=$rowClave['clave'];
        }
        $tcurp=($arrReporte[$num]->curp);
        $esNac=($arrReporte[$num]->entidadNacimiento->nombre);
    }
    $query="SELECT * from estados where id!='0'";
	$equery=$mysqli->query($query);
	$countries = array();
	while($rowedo=$equery->fetch_object())	{ $countries[]=$rowedo; }

    //verifica si es una name
    if(!empty($_POST['aceptar']))
	{	
        /*$qregistroAnterior="SELECT folio_c 
            FROM casos c inner join nna_caso na on c.id=na.id_caso
            inner join relacion_nna_nnareportado nr on nr.id_nna=na.id_nna
            where c.id='$idCaso' and nr.id_nna_reportado='$idNna'";
            $rregistroAnterior=$mysqli->query($qregistroAnterior);
            while ($rowA=$rregistroAnterior->fetch_assoc()) {
                $folioCaso=$rowA['folio_c'];
            }
            $yaRegistrado=$rregistroAnterior->num_rows;
        if($yaRegistrado==0){
        $fecha= date("Y-m-d H:i:s", time());
        $curp=mysqli_real_escape_string($mysqli,$_POST['curp']);
        $nombre = mysqli_real_escape_string($mysqli,$_POST['nombre']);
        $ap_paterno = mysqli_real_escape_string($mysqli,$_POST['apellido_p']);
        $ap_materno = mysqli_real_escape_string($mysqli,$_POST['apellido_m']);
        $sexo = $_POST['sexo'];
        $entidad_nacimiento= $_POST['estadoNacimiento'];
		$fecha_nac = mysqli_real_escape_string($mysqli,$_POST['fecha_nacimiento']);
		$lug_nac = mysqli_real_escape_string($mysqli,$_POST['lugar_nacimiento']);
        $lug_reg = mysqli_real_escape_string($mysqli,$_POST['lugar_registro']);
          //folio
        $lnom=substr($nombre, 0,1);
        $lap=substr($ap_paterno, 0,1);
        $lam=substr($ap_materno, 0,1);
        $snum='SELECT terminacion from nfolio where id=1';
        $esnum=$mysqli->query($snum);
        while ($row=$esnum->fetch_assoc()) {
            $ter=$row['terminacion'];
        }
        $ter2=$ter+1;
        $folio=$lnom.$lap.$lam.$ter2;

        if($curp!=""){ // si registra alguna curp

            $qverificarCurp="SELECT id, folio from nna where curp='$curp'";
            $rverificarCurp=$mysqli->query($qverificarCurp);
            $existeCurp=$rverificarCurp->num_rows;  //virifica que esa curp no este registrada ya
            if($existeCurp>0)  {              
                while ($rowNnaRegistrado=$rverificarCurp->fetch_assoc()) {  //si ya esta registrada toma el id y folio de la tabla nna
                    $idNnaReg=$rowNnaRegistrado['id'];
                    $folioNnaReg=$rowNnaRegistrado['folio'];
                }
                $qrelacionNna="INSERT INTO relacion_nna_nnareportado (id_nna_reportado, id_nna) values ('$idNna','$idNnaReg')";
                $rrelacionNna=$mysqli->query($qrelacionNna); //relaciona al nna ya registrado con el nna reportado recientemente 
                echo "<script>
                alert('El(la) NNA ya se se encuentra registrado(a) con el folio $folioNnaReg');   
                window.location= 'verificar_datos_nna.php?idPc=$idPosibleCaso&idCaso=$idCaso'
                </script>"; //manda un mensaje de que ya se encuentra registrado y dice cual es el folio de su registro al mismo tiempo redirecciona a verificar los nna e el registro                 

            } else { //si el curp no se a registrado inserta los datos en la tabla nna 
		      $sqlNino = "INSERT INTO nna (folio, nombre, apellido_p, apellido_m, curp, fecha_nac, sexo,  lugar_nac, lugar_reg, estado, respo_reg, nna_ex, fecha_reg) 
                VALUES ('$folio', '$nombre', '$ap_paterno', '$ap_materno', '$curp', '$fecha_nac', '$sexo', '$lug_nac', '$lug_reg', 'P', '$idDEPTO', '0', '$fecha')";	 
                $resultNino=$mysqli->query($sqlNino);
                $contador="UPDATE nfolio set terminacion='$ter2' where id=1";
			    $econt=$mysqli->query($contador);   
                if ($resultNino){
                    $qidNna="SELECT max(id) from nna where folio='$folio'"; //selecciona el id de la tabla nna que se ha registrado
                    $ridNna=$mysqli->query($qidNna);
                    while ($rowidnna=$ridNna->fetch_assoc()) {
                        $idNnaReg=$rowidnna['max(id)'];
                    }
                    $qrelacion="INSERT INTO nna_caso (id_caso, id_nna, fecha_reg) VALUES ('$idCaso', '$idNnaReg', '$fecha')";
                    $rrelacion=$mysqli->query($qrelacion); //relaciona el caso con el nna
                    $qrelacionNna="INSERT INTO relacion_nna_nnareportado (id_nna_reportado, id_nna) values ($idNna, $idNnaReg)";
                    $rrelacionNna=$mysqli->query($qrelacionNna); //relaciona el registro recien del nna con el del reportado
                    if($rrelacion and $rrelacionNna)
                        header("Location: registro_nna.php?idCaso=$idCaso&idNna=$idNnaReg");
                    else 
                        echo "Error: ".$qrelacion."-".$rrelacionNna;
                }
                else
                echo "Error al Registrar: ".$sqlNino;
            }
        } else {  // no registro alguna curp
            $sqlNino = "INSERT INTO nna (folio, nombre, apellido_p, apellido_m,  fecha_nac, sexo, 
                lugar_nac, lugar_reg, estado, respo_reg, nna_ex, fecha_reg) 
                VALUES ('$folio', '$nombre', '$ap_paterno', '$ap_materno',  '$fecha_nac', '$sexo', 
                '$lug_nac', '$lug_reg', 'P', '$idDEPTO', '0', '$fecha')"; 
                $resultNino=$mysqli->query($sqlNino);   //registra al nna
                $contador="UPDATE nfolio set terminacion='$ter2' where id=1";
			    $econt=$mysqli->query($contador); 
                if ($resultNino){
                    $qidNna="SELECT max(id) from nna where folio='$folio'";
                    $ridNna=$mysqli->query($qidNna);
                    while ($rowidnna=$ridNna->fetch_assoc()) {
                        $idNnaReg=$rowidnna['max(id)']; //obtiene el id del registro recien hecho
                    }
                    $qrelacion="INSERT INTO nna_caso (id_caso, id_nna, fecha_reg) VALUES ('$idCaso', '$idNnaReg', '$fecha')";
                    $rrelacion=$mysqli->query($qrelacion); //relaciona el caso con el nna 
                     $qrelacionNna="INSERT INTO relacion_nna_nnareportado (id_nna_reportado, id_nna) values ($idNna, $idNnaReg)";
                    $rrelacionNna=$mysqli->query($qrelacionNna); //relaciona el registro recien del nna con el del reportado
                    if($rrelacion and $rrelacionNna)
                        header("Location: registro_nna.php?idCaso=$idCaso&idNna=$idNnaReg");
                    else 
                        echo "Error: ".$qrelacion."-".$rrelacionNna;
                }
                else
                    echo "Error al Registrar: ".$sqlNino;
                
        }     
    } else   {
        $qhayResponsable="SELECT n.id_nna, r.id_responsable 
        FROM relacion_responsable_nna r right join relacion_nna_nnareportado n on r.id_nna=n.id_nna
        where n.id_nna_reportado='$idNna'";
        $rhayResponsable=$mysqli->query($qhayResponsable);
        while ($rowResponsable=$rhayResponsable->fetch_assoc()) {  //obtiene datos del nna y responsable
            $idNna=$rowResponsable['id_nna'];
            $idResponsable=$rowResponsable['id_responsable'];
        }
        if(empty($idResponsable))
        echo "<script>
                alert('Esta NNA ya se ha registrado a este caso, por favor complete el registro');   
                window.location= 'registro_nna.php?idCaso=$idCaso&idNna=$idNna'
                </script>";
        else 
        echo "<script>
                alert('Esta NNA ya se ha registrado a este caso');   
                window.location= 'verificar_datos_nna.php?idPc=$idPosibleCaso&idCaso=$idCaso'
                </script>";
    }  */
       
        $curp=mysqli_real_escape_string($mysqli,$_POST['curp']);
        $curp= trim($curp);  //elimina espacio en blanco 
        $nombre = mysqli_real_escape_string($mysqli,$_POST['nombre']);
        $nombre= trim($nombre);
        $ap_paterno = mysqli_real_escape_string($mysqli,$_POST['apellido_p']);
        $ap_paterno= trim($ap_paterno);
        $ap_materno = mysqli_real_escape_string($mysqli,$_POST['apellido_m']);
        $ap_materno = trim($ap_materno);
        $sexo = $_POST['sexo'];
        if($sexo==1)
            $sexo="H";
        else  if($sexo==2)
            $sexo="M";
        $entidad_nacimiento= $_POST['country_id'];
        $fecha_nac = mysqli_real_escape_string($mysqli,$_POST['fecha_nacimiento']);
        $lug_nac = $_POST['country_id'];
        $persona=null;
        $persona= [ 
            "Nombres" => "$nombre",
            "Apellido1" => "$ap_paterno",
            "Apellido2" => "$ap_materno",
            "FechNac" => "$fecha_nac",
            "Sexo" => "$sexo",
            "EntidadRegistro" => "$entidad_nacimiento",
                  
        ];
        $_SESSION['persona'] = $persona;
        if(empty($curp) and (empty($nombre) or empty($ap_paterno) or empty($sexo) ))
        { ?> <script type="text/javascript">
        alert('Por favor, complete la información del NNA');
        </script>
        <?php } else 
            header("Location: registro_curp_nna.php?idNna=$idNnaR&idPc=$idPc&curp=$curp");
	 }

   /* if(!empty($_POST['buscarCurp'])){
        $curp=mysqli_real_escape_string($mysqli,$_POST['curp']);
         header("Location: consultaPorCurp.php?c=$curp&idNna=$idNna&idCaso=$idCaso");
    }*/
?>
<!DOCTYPE HTML>
<html>
    <head lang="es-ES">
		<title>Registrar NNA</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="shortcut icon" href="images/favicon.png" type="image/png" />
		<script type="text/javascript" src="jquery.min.js"></script>		
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
					        <div class="4u"><img src="images/crece.jpg" width="80px" height="70px" /></div>
					        <div class="4u"><img src="images/dif.jpg" width="40px" height="80px" /></div>
					        <div class="4u"><img src="images/armas.jpg" width="80px" height="80px"/></div>
				        </div>
			        </div>
                    <h3>Añadir NNA al caso:</h3>
                    <h4>Por favor, complete los datos del NNA para añadirlo al caso.</h4>
                   	<form id="familia" name="familia" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" >
                       <div class="box" >
                            <div class="row uniform">
                                <div class="1u 12u$(xsmall)">
                                	CURP:
                                </div>
                                <div class="11u 12u$(xsmall)">
                                <?php if($numName==0) {?>
                                    <input id="curp" pattern="[A-Z]{4}\d{6}[HM][A-Z]{2}[B-DF-HJ-NP-TV-Z]{3}[A-Z0-9][0-9]" name="curp" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" >
                                <?php } else { ?> 
                                
                                    <input id="curp" pattern="[A-Z]{4}\d{6}[HM][A-Z]{2}[B-DF-HJ-NP-TV-Z]{3}[A-Z0-9][0-9]" name="curp" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" value="<?=$tcurp ?>">
                                <?php } ?>
                                </div>
							
								
							</div>
						</div>
						<div class="box">
							<div class="row unifor">
                                <div class="4u 12u$(xsmall)">Nombre (s):
                                    <input id="nombre" name="nombre" type="text" value="<?php echo $nombre?>" pattern="[A-ZÑ./-‘ ]{0,50}" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                                </div>
                                <div class="4u 12u$(xsmall)">Apellido paterno:
                                    <input id="apellido_p" name="apellido_p" type="text" value="<?php echo $apellido_p?>" pattern="[A-Z./-‘ ]{0,50}" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                                </div>
                                <div class="4u 12u$(xsmall)">Apellido materno:
                                    <input id="apellido_m" name="apellido_m" type="text" value="<?php echo $apellido_m?>" pattern="[A-ZÑ./-‘ ]{0,50}" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="row uniform">
                                <div class="4u 12u$(xsmall)">Sexo
			                        <div class="select-wrapper">
			                            <select id="sexo" name="sexo" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" >
					                        <?php if($sexId==0){?>
                                                <option value="">--Seleccione--</option>
                                            <?php } else { ?>
                                                <option value="<?php echo $sexId?>"><?php echo $sexo?></option>
                                            <?php    }                                        
	                                        $sexo="SELECT id, sexo FROM sexo";
	                                        $resu=$mysqli->query($sexo);
                                            while($rowS = $resu->fetch_assoc()){ ?>
					                    	    <option value="<?php echo $rowS['id']; ?>"><?php echo $rowS['sexo']; ?></option>
					                        <?php }?>
			                            </select>
			                        </div>
                                </div>
                               
                                <div class="4u 12u$(xsmall)">Fecha de nacimiento:
                                    <input id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo $fecha_nac?>" type="date"  >
                                </div>
                            
                                <div class="4u 12u$(xsmall)">Lugar de nacimiento:
                                    <div class="select-wrapper">
										<select id="country_id" class="form-control" name="country_id">
                                        <?php if($numName==0) {?>
      										<option value="">-- Seleccione --</option>
                                              <?php } else { ?> 
                                                <option value="<?=$claveEdo?>"><?=$esNac?></option>
                                                <?php } ?>
											<?php foreach($countries as $c):?>
      											<option value="<?php echo $c->clave; ?>"><?php echo $c->estado; ?></option>
											<?php endforeach; ?>
    									</select>
									</div>
                                </div>
                                
                            </div><br>
                        </div>                       
                        <div class="row uniform">
                            <div class="6u 12u$(xsmall)">
                                <input class="button special fit" name="aceptar" id="aceptar" type="submit" value="Aceptar">	
                            </div>
                            <div class="6u 12u$(xsmall)">
                                <input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='verificar_datos_nna.php?idPc=<?= $idPc; ?>'" >
                            </div>
                        </div>
                    </form>
                    <script type="text/javascript">
						$(document).ready(function(){
							$("#country_id").change(function(){
								$.get("get_states.php","country_id="+$("#country_id").val(), function(data){
									$("#state_id").html(data);
									console.log(data);
								});
							});

							$("#state_id").change(function(){
								$.get("get_cities.php","state_id="+$("#state_id").val(), function(data){
									$("#city_id").html(data);
									console.log(data);
								});
							});
						});
					</script>
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

    
