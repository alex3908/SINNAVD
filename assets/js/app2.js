$(document).ready(function() {
    fetchProcedimiento();
    idP = 0;
    
    $('#juicios-form').submit(function (e) {
        var overlay = document.getElementById('overlay');
        var popup = document.getElementById('popup');
        const postData = {
            abogadoResp: $('#txtResponsable').val(),
            promoventes: $('#txtPromoventes').val(),
            consentimiento: $('#txtConsentimiento').val(),
            juzgado: $('#txtJuzgado').val(),
            numExpediente: $('#txtExpediente').val(),
            fechaInicial: $('#txtFechainicial').val(),
            fechaSentencia: $('#txtFechasentencia').val(),
            fechaEjecucion: $('#txtFechaejecucion').val()
        }
        $.post('add_procedimiento.php', postData, function(response) {
            fetchProcedimiento();
            $('#juicios-form').trigger('reset');
        })
        
        e.preventDefault();
        overlay.classList.remove('active');
        popup.classList.remove('active');
    })


    function fetchProcedimiento() {
        $.ajax({
            url: 'listar_procedimientos.php',
            type: 'GET',
            data: {},
            success: function(response){
                let seguimientos = JSON.parse(response);
                template = '';
                var fechaInicial="";
                var fechaSentencia="";
                var fechaEjecucion="";
                seguimientos.forEach(element => {
                    
                    if(element.fechaInicial == "01/01/1000"){
                        fechaInicial = "---";
                    } else {
                        fechaInicial =element.fechaInicial;
                    }
                    if(element.fechaSentencia == "01/01/1000"){
                        fechaSentencia = "---";
                    } else {
                        fechaSentencia =element.fechaSentencia;
                    }
                    if(element.fechaEjecucion == "01/01/1000"){
                        fechaEjecucion = "---";
                    } else {
                        fechaEjecucion =element.fechaEjecucion;
                    }
                    template += `
                    <tr>
                        <td>${element.responsable}</td>
                        <td>${element.promoventes}</td>
                        <td>${element.consentimiento}</td>
                        <td>${element.juzgado}\n${element.numExpediente}</td>
                        <td>${fechaInicial}</td>
                        <td>${fechaSentencia}</td>
                        <td>${fechaEjecucion}</td>
                        
                        <td><input type="button" class="button special fit small" onclick="mostrarPopup(${element.id})" value="Editar"></td>
                        
                        <td><input id="btn-st" type="submit" onclick="getId(${element.id})" class="button special fit small" value="Eliminar"> </td>

                        
                    </tr>
                    `;
                });
                
                $('#procedimientos').html(template);
                
            }
        });
    }
    
    
    $('#juicios-form-edit').submit(function (e) {
        var overlay = document.getElementById('overlay');
        var popup = document.getElementById('popup');
        const postData = {
            id: idP,
            abogadoResp: $('#txtResponsable2').val(),
            promoventes: $('#txtPromoventes2').val(),
            consentimiento: $('#txtConsentimiento2').val(),
            juzgado: $('#txtJuzgado2').val(),
            numExpediente: $('#txtExpediente2').val(),
            fechaInicial: $('#txtFechainicial2').val(),
            fechaSentencia: $('#txtFechasentencia2').val(),
            fechaEjecucion: $('#txtFechaejecucion2').val()
        }
        $.post('editar_procedimiento.php', postData, function(response) {
            fetchProcedimiento();
            $('#juicios-form').trigger('reset');
        })
        
        e.preventDefault();
        overlay2.classList.remove('active');
        popup2.classList.remove('active');
    })

    $('#juicios-form-eliminar').submit(function (e) {
        
        mensaje = confirm("¿Esta seguro que desea eliminar este registro?");

        if(mensaje){

            const postData = {
                id: idP,
            }
            $.post('eliminar_procedimiento.php', postData, function(response) {
                fetchProcedimiento();
            })
            
        } else {
            
        }
        e.preventDefault();

    })
    
})

function getId(id){
    idP = id;
}
function eliminarProcedimiento(idE){
    const postData = {
        id: idE
    }
    $.post('eliminar_procedimiento.php', postData, function(response) {
        
        
    })
    
    
}

function cerrarPopup(){
    var overlay2 = document.getElementById('overlay2'),
        popup2 = document.getElementById('popup2');
    overlay2.classList.remove('active');
    popup2.classList.remove('active');
}

function mostrarPopup(id){
    idP = id;
    var resp = "";
    overlay2 = document.getElementById('overlay2');
    popup2 = document.getElementById('popup2');
    overlay2.classList.add('active');
    popup2.classList.add('active');

        $.ajax({
            type: "POST",
            url: "edit_procedimiento.php",
            data: { "id" :  id },
            success: function(response){
                var seguimientos = JSON.parse(response);

                template = '';
                seguimientos.forEach(element => {
                    if(element.fechaInicial == "1000-01-01"){
                        fechaInicial = "";
                    } else {
                        fechaInicial =element.fechaInicial;
                    }
                    if(element.fechaSentencia == "1000-01-01"){
                        fechaSentencia = "";
                    } else {
                        fechaSentencia =element.fechaSentencia;
                    }
                    if(element.fechaEjecucion == "1000-01-01"){
                        fechaEjecucion = "";
                    } else {
                        fechaEjecucion =element.fechaEjecucion;
                    }
                    template += `<div class="row uniform">
                    <div class="4u">
                        <label>Abogado responsable</label>
                        <input id="txtResponsable2" name="txtResponsable" type="text" maxlength="50"
                            value="${element.responsable}" style="text-transform:uppercase;"
                            onkeyup="this.value=this.value.toUpperCase();">
                    </div>
                    <div class="5u">
                        <label>Promoventes</label>
                        <textarea id="txtPromoventes2" rows="2"
                            style="text-transform:uppercase;"
                            onkeyup="this.value=this.value.toUpperCase();">${element.promoventes}</textarea>
                    </div>
                    <div class="3u">
                        <label>Adopción con consentimiento</label>
                        <input id="txtConsentimiento2" name="txtConsentimiento" type="text"
                            maxlength="50" value="${element.consentimiento}" style="text-transform:uppercase;"
                            onkeyup="this.value=this.value.toUpperCase();">
                    </div>
                </div>
                <div class="row uniform">
                    <div class="3u">
                        <label>Juzgado</label>
                        <input id="txtJuzgado2" name="txtJuzgado" type="text" value="${element.juzgado}">
                    </div>
                    <div class="2u">
                        <label>No. de expediente</label>
                        <input id="txtExpediente2" name="txtExpediente" type="text" value="${element.numExpediente}"
                            style="text-transform:uppercase;"
                            onkeyup="this.value=this.value.toUpperCase();">
                    </div>
                </div>
                <div class="row uniform">

                    <div class="3u">
                        <label>Fecha de escrito inicial</label>
                        <input id="txtFechainicial2" name="txtFechainicial" type="date" value="${fechaInicial}">
                    </div>
                    <div class="3u">
                        <label>Fecha de sentencía</label>
                        <input id="txtFechasentencia2" name="txtFechasentencia" type="date" value="${fechaSentencia}">
                    </div>
                    <div class="3u">
                        <label>Fecha ejecución de sentencía</label>
                        <input id="txtFechaejecucion2" name="txtFechaejecucion" type="date" value="${fechaEjecucion}">
                    </div>
                </div>
                <br>
                <br>
                <div class="row uniform">
                    <div class="3u">
                    </div>
                    <div class="3u">
                    </div>
                    <div class="3u">
                        <input type="button" onclick="cerrarPopup()" class="button fit" value="Cancelar">
                    </div>
                    <div class="3u">
                        <input type="submit" class="button special fit" value="Actualizar">
                    </div>
                </div>`;

                });

                $('#juicios-form-edit').html(template);
               
            }
        });
}

    var btnAbrirPopup = document.getElementById('btn-abrir-popup'),
        overlay = document.getElementById('overlay'),
        popup = document.getElementById('popup'),
        btnCerrarPopup = document.getElementById('btn-cerrar-popup');

    btnAbrirPopup.addEventListener('click', function() {
        overlay.classList.add('active');
        popup.classList.add('active');
        
    });
    btnCerrarPopup.addEventListener('click', function(e) {
        e.preventDefault();
        overlay.classList.remove('active');
        popup.classList.remove('active');
    });