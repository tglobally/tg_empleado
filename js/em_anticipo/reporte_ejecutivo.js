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
            var adm_usuario_id = $('#adm_usuario_id').val();
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

            if (adm_usuario_id !== "") {
                data.filtros.filtro.push(
                    {
                        "key": "em_anticipo.usuario_alta_id",
                        "valor": adm_usuario_id,
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
            title: 'Empleado',
            data: 'em_empleado_nombre_completo'
        },
        {
            title: 'Tipo Anticipo',
            data: 'em_tipo_anticipo_descripcion'
        },
        {
            title: 'Descripción',
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

$('.filter-checkbox,#fecha_inicio,#fecha_final,#adm_usuario_id,#em_tipo_anticipo_id').on('change', function (e) {
    datatable.draw();
});





