let url = getAbsolutePath();

let session_id = getParameterByName('session_id');


let txt_salario_diario = $('#salario_diario');
let txt_salario_diario_integrado = $('#salario_diario_integrado');
let txt_fecha_inicio_rel_laboral = $('#fecha_inicio_rel_laboral');

let getData = async (url, acciones) => {
    fetch(url)
        .then(response => response.json())
        .then(data => acciones(data))
        .catch(err => {
            alert('Error al ejecutar');
            console.error("ERROR: ", err.message)
        });
}


