<?php	
	session_start();
	require 'conexion.php';
	
	if(!isset($_SESSION["id"])){
		header("Location: welcome.php");
    }
    $idDEPTO = $_SESSION['id'];
    $idNna=($_GET['idNna']);
    $idCaso=($_GET['idCaso']);
    date_default_timezone_set('America/Mexico_City');
    $zonahoraria = date_default_timezone_get();

    $qNna="SELECT id_posible_caso, nombre, apellido_p, apellido_m, sexo.id as sexId, sexo.sexo, fecha_nacimiento as fecha_nac, 
    lugar_nacimiento, lugar_registro
    from nna_reportados left join sexo on nna_reportados.sexo=sexo.id
    where nna_reportados.id='$idNna'"; //toma los daos del nna de la taba nna_reportados
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
        $idPosibleCaso=$row['id_posible_caso'];
    }

    //verifica si es una name

    if(!empty($_POST['aceptar']))
	{	
        $query="SELECT * from estados where id!='0'";
        $equery=$mysqli->query($query);
        $countries = array();
        while($rowEdo=$equery->fetch_object())	
            { $countries[]=$rowEdo; }
        $qregistroAnterior="SELECT folio_c 
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
                    $sqlNino = "INSERT INTO nna (folio, nombre, apellido_p, apellido_m, curp, fecha_nac, sexo,  lugar_nac, lugar_reg, estado, respo_reg, nna_ex, fecha_reg) 
                VALUES ('$folio', '$nombre', '$ap_paterno', '$ap_materno', '$curp', '$fecha_nac', '$sexo', '$lug_nac', '$lug_reg', 'P', '$idDEPTO', '0', '$fecha')";     
                $resultNino=$mysqli->query($sqlNino);
                $contador="UPDATE nfolio set terminacion='$ter2' where id=1";
                $econt=$mysqli->query($contador);
                 }
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
        }
    }
?>
<!DOCTYPE HTML>
<html>
	<head>
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
                                <div class="6u 12u$(xsmall)">
									<input id="curp" pattern="[A-Z0-9Ñ]{18,18}" name="curp" type="text"  style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();">
								</div>
							
								<div class="5u 12u$(xsmall)">
									 <input class="button special fit" name="buscarCurp" id="buscarCurp" type="submit" value="Buscar datos del NNA">
								</div>
							</div>
						</div>
						<div class="box">
							<div class="row unifor">
                                <div class="3u 12u$(xsmall)">Nombre (s):
                                    <input id="nombre" name="nombre" type="text" value="<?php echo $nombre?>" maxlength="50" required>
                                </div>
                                <div class="2u 12u$(xsmall)">Apellido paterno:
                                    <input id="apellido_p" name="apellido_p" type="text" value="<?php echo $apellido_p?>" maxlength="50">
                                </div>
                                <div class="2u 12u$(xsmall)">Apellido materno:
                                    <input id="apellido_m" name="apellido_m" type="text" value="<?php echo $apellido_m?>" maxlength="50">
                                </div>
                                <div class="2u 12u$(xsmall)">Sexo
			                        <div class="select-wrapper">
			                            <select id="sexo" name="sexo" style="text-transform:uppercase;" onkeyup="this.value=this.value.toUpperCase();" required >
					                        <option value="<?php echo $sexId?>"><?php echo $sexo?></option>
                                            <?php                                            
	                                        $sexo="SELECT id, sexo FROM sexo";
	                                        $resu=$mysqli->query($sexo);
                                            while($rowS = $resu->fetch_assoc()){ ?>
					                    	    <option value="<?php echo $rowS['id']; ?>"><?php echo $rowS['sexo']; ?></option>
					                        <?php }?>
			                            </select>
			                        </div>
                                </div>
                                <div class="3u 12u$(xsmall)">Fecha de nacimiento:
                                    <input id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo $fecha_nac?>" type="date"  >
                                </div>
                            </div>
                            <div class="row uniform">
                                <div class="4u 12u$(xsmall)">Lugar de nacimietno:
                                    <input id="lugar_nacimiento" name="lugar_nacimiento"  value="<?php echo $lugar_nac?>" type="text" maxlength="50">
                                </div>
                                <div class="4u 12u$(xsmall)">Lugar de registro:
                                    <input id="lugar_registro" name="lugar_registro" value="<?php echo $lugar_reg?>" type="text" maxlength="50">
                                </div>
                                <div class="4u 12u$(xsmall)">
                                	<br>
									 <input class="button special fit" name="buscarDatos" id="buscarDatos" type="submit" value="Buscar CURP">
								</div>
                            </div><br>
                        </div>                       
                        <div class="row uniform">
                            <div class="6u 12u$(xsmall)">
                                <input class="button special fit" name="aceptar" id="aceptar" type="submit" value="Aceptar">	
                            </div>
                            <div class="6u 12u$(xsmall)">
                                <input class="button fit" type="button" name="cancelar" value="Cancelar" onclick="location='verificar_datos_nna.php?idPc=<?php echo $idPosibleCaso; ?>&idCaso=<?php echo $idCaso; ?>'" >
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
        </div>
        <!-- Scripts -->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/skel.min.js"></script>
		<script src="assets/js/util.js"></script>
		<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
		<script src="assets/js/main.js"></script>
	</body>
</html>

    
