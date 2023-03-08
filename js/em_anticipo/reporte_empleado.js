let url = get_url("em_anticipo", "data_ajax", {em_anticipo_id: "1"});

let sl_com_sucursal = $("#com_sucursal_id");
let sl_em_empleado = $("#em_empleado_id");


var datatable = $(".datatables").DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    "searching": false,
    ajax: {
        "url": url,
        'data': function (data) {
            var com_sucursal_id = $('#com_sucursal_id').val();
            var fecha_inicio = $('#fecha_inicio').val();
            var fecha_final = $('#fecha_final').val();
            var em_empleado_id = sl_em_empleado.find('option:selected').data(`em_empleado_id`);
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

            if (em_empleado_id !== undefined) {
                data.filtros.filtro.push(
                    {
                        "key": "em_empleado.id",
                        "valor": em_empleado_id,
                    }
                )
            }

            if (em_tipo_anticipo_id !== "") {
                data.filtros.filtro.push(
                    {
                        "key": "em_tipo_anticipo.id",
                        "valor": em_tipo_anticipo_id
                    })
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
            title: 'Empresa',
            data: 'org_sucursal_descripcion'
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

sl_com_sucursal.change(function () {
    let selected = $(this).find('option:selected');

    get_data2("tg_empleado_sucursal",
        "get_empleados",
        {com_sucursal_id: selected.val()},
        sl_em_empleado, ["em_empleado_id"]);

    var em_empleado_id = $('#em_empleado_id').val();

    console.log(em_empleado_id)
});

$('#com_sucursal_id,.filter-checkbox,#fecha_inicio,#fecha_final,#em_empleado_id,#em_tipo_anticipo_id').on('change', function (e) {
    datatable.draw();
});







