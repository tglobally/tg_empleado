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
            var com_sucursal_id = $('#com_sucursal_id').val();
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

            if (com_sucursal_id !== "") {
                data.filtros.extra_join = [
                    {
                        "entidad": "tg_empleado_sucursal",
                        "key": "em_empleado_id",
                        "enlace": "em_empleado",
                        "key_enlace": "id",
                        "renombre": "tg_empleado_sucursal"
                    },
                ];
                data.filtros.filtro.push(
                    {
                        "key": "tg_empleado_sucursal.com_sucursal_id",
                        "valor": com_sucursal_id,
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
            title: 'NSS',
            data: 'em_empleado_nss'
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

$('.filter-checkbox,#fecha_inicio,#fecha_final,#com_sucursal_id,#em_tipo_anticipo_id').on('change', function (e) {
    datatable.draw();
});





