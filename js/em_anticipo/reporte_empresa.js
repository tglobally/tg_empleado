let url = get_url("em_anticipo", "data_ajax", {em_anticipo_id: "1"});

var datatable = $(".datatables").DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    "searching": false,
    ajax: {
        "url": url,
        'data': function (data) {
            var fecha_inicio = $('#fecha_inicio').val();
            var fecha_final = $('#fecha_final').val();
            var org_sucursal_id = $('#org_sucursal_id').val();
            var em_tipo_anticipo_id = $('#em_tipo_anticipo_id').val();

            data.filtros = {
                filtro_especial: [
                    {
                        "key": "em_anticipo.fecha_prestacion",
                        "valor": fecha_inicio,
                        "operador": "<=",
                        "comparacion": "AND"
                    },
                    {
                        "key": "em_anticipo.fecha_prestacion",
                        "valor": fecha_final,
                        "operador": ">=",
                        "comparacion": "AND"
                    }
                ],
                filtro: []
            }

            if (em_tipo_anticipo_id !== "") {
                data.filtros.filtro.push(
                    {
                    "key": "em_tipo_anticipo.id",
                    "valor": em_tipo_anticipo_id
                })
            }

            if (org_sucursal_id !== "") {
                data.filtros.filtro.push(
                    {
                        "key": "org_sucursal.id",
                        "valor": org_sucursal_id,
                    }
                )


            }
        },
        "error": function (jqXHR, textStatus, errorThrown) {
            let response = jqXHR.responseText;
            document.body.innerHTML = response.replace('[]', '')
        },
    },
    columns: [
        {
            title: 'Id',
            data: 'em_anticipo_id'
        },
        {
            title: 'Código',
            data: 'em_anticipo_codigo'
        },
        {
            title: 'Descripcion',
            data: 'em_anticipo_descripcion'
        },
        {
            title: 'Empleado',
            data: 'em_empleado_nombre'
        },
        {
            title: 'Monto',
            data: 'em_anticipo_monto'
        },
        {
            title: 'Fecha Prestacion',
            data: 'em_anticipo_fecha_prestacion'
        }
    ],
});

$('.filter-checkbox,#fecha_inicio,#fecha_final,#org_sucursal_id,#em_tipo_anticipo_id').on('change', function (e) {
    datatable.draw();
});





