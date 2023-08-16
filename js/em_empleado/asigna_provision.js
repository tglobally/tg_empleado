let sl_empleado = $("#em_empleado_id");
let sl_cliente = $("#com_sucursal_id");
let sl_empresa = $("#org_sucursal_id");

sl_cliente.change(function () {
    let empleado = sl_empleado.find('option:selected');
    let selected = $(this).find('option:selected');

    let url = get_url("tg_empleado_sucursal","get_empresas", {em_empleado_id: empleado.val(), com_sucursal_id: selected.val()});

    get_data(url, function (data) {
        sl_empresa.empty();

        integra_new_option(sl_empresa,'Seleccione un registro','-1');

        $.each(data.registros, function( index, registro ) {
            if (registro.org_sucursal_descripcion_select !== null){
                integra_new_option(sl_empresa,registro.org_sucursal_descripcion_select, registro.org_sucursal_id);
            }
        });
        sl_empresa.selectpicker('refresh');
    });
})






