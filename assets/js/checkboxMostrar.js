var checkbox = document.getElementById('cbox1');
    var checkbox2 = document.getElementById('cbox2');
    checkbox.addEventListener("change", mostrar, false);
    checkbox2.addEventListener("change", mostrar, false);


    function mostrar() {
        if (checkbox.checked || checkbox2.checked) {
            div = document.getElementById('divSolicitud');
            div.style.display = '';
        } else {
            div = document.getElementById('divSolicitud');
            div.style.display = 'none';

        }
    }