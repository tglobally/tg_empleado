let sl_com_sucursal = $("#com_sucursal_id");

let url = get_url("em_anticipo", "data_ajax", {em_empleado_id: "1"});

var datatable = $(".datatables").DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: {
        "url": url,
        'data': function (data) {
            var fecha_inicio = $('#fecha_inicio').val();
            var fecha_final = $('#fecha_final').val();
            //var com_sucursal_id = $( "#com_sucursal_id option:selected" ).val();

            data.filtros = [
                {
                    "key": "em_empleado.fecha_inicio_rel_laboral",
                    "valor": fecha_inicio,
                    "operador": "<=",
                    "comparacion": "AND"
                },
                {
                    "key": "em_empleado.fecha_inicio_rel_laboral",
                    "valor": fecha_final,
                    "operador": ">=",
                    "comparacion": "AND"
                },
                /*{
                    "key": "tg_empleado_sucursal.com_sucursal_id",
                    "valor": com_sucursal_id,
                    "operador": "=",
                    "comparacion": "AND"
                }*/
            ]
        },
        "error": function (jqXHR, textStatus, errorThrown) {
            let response = jqXHR.responseText;
            document.body.innerHTML = response.replace('[]', '')
        },
    },
    columns: [
        {
            title: 'Id',
            data: 'em_empleado_id'
        },
        {
            title: 'Empleado',
            data: 'em_empleado_nombre'
        },
        {
            title: 'Concepto',
            data: 'em_anticipo_descripcion'
        },
        {
            title: 'Monto',
            data: 'em_anticipo_monto'
        },
        {
            title: 'Fecha Prestación',
            data: 'em_anticipo_fecha_prestacion'
        }
    ],
});
/*
$('.filter-checkbox,#fecha_inicio,#fecha_final').on('change', function (e) {
    datatable.draw();
});

sl_com_sucursal.change(function () {
    let selected = $(this).find('option:selected');

    if (selected.val() !== ""){
        datatable.draw();
    }
});*/







