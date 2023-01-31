let sl_com_sucursal = $("#com_sucursal_id");

let url = get_url("em_empleado", "get_data", {cat_sat_tipo_producto_id: "1"});

var datatable = $(".datatables").DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: {
        "url": url,
        'data': function (data) {
            var fecha_inicio = $('#fecha_inicio').val();
            var fecha_final = $('#fecha_final').val();
            var sucursal = $( "#com_sucursal_id option:selected" ).val();

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
            title: 'RFC',
            data: 'em_empleado_rfc'
        },
        {
            title: 'NSS',
            data: 'em_empleado_nss'
        },
        {
            title: 'Salario Diario',
            data: 'em_empleado_salario_diario'
        },
        {
            title: 'Salario Diario Integrado',
            data: 'em_empleado_salario_diario_integrado'
        },
        {
            title: 'Puesto',
            data: 'org_puesto_descripcion'
        },
        {
            title: 'Departamento',
            data: 'org_departamento_descripcion'
        },
    ],
});

$('.filter-checkbox,#fecha_inicio,#fecha_final').on('change', function (e) {
    datatable.draw();
});

sl_com_sucursal.change(function () {
    let selected = $(this).find('option:selected');

    if (selected.val() !== "") {
        datatable.draw();
    }
});





