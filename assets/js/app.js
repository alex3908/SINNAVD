$(document).ready(function() {
    $('#btn-concluida').hide();
    params={};location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(s,k,v){params[k]=v});
    fetchSeguimiento();

    var estatus = $('#status').val();

    if(estatus=="CONCLUIDA"){
        $('#seguimiento-form').hide();
    }

    $('#seguimiento-form').submit(function (e) {
        const postData = {
            idSolicitud :params['id'],
            noSeguimiento: $('#txtNoASeg').val(),
            fecha: $('#txtFechaReg').val(),
            responsable: $('#txtResponsable').val(),
            observaciones: $('#txtObservaciones').val()
        }
        $.post('add_seguimiento.php', postData, function(response) {
            fetchSeguimiento();
            $('#seguimiento-form').trigger('reset');
        })
        e.preventDefault();
    })

    function fetchSeguimiento() {
        $.ajax({
            url: 'listar_seguimiento.php',
            type: 'GET',
            data: {idSolicitud:params['id']},
            success: function(response){
                let seguimientos = JSON.parse(response);
                template = '';

                $('#list').hide();

                seguimientos.forEach(element => {
                    $('#list').show();
                    template += `
                    <tr>
                        <td>${element.noSeguimiento}</td>
                        <td>${element.fecha}</td>
                        <td>${element.responsable}</td>
                        <td style="max-width: 450px;">${element.observaciones}</td>
                    </tr>
                    `
                    if(element.totalSeguimientos >= 6){
                        $('#btn-concluida').show();
                    }
                    
                });
                $('#seguimientos').html(template);
                
            }
        });
    }
})