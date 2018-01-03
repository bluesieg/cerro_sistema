function limpiar_dl_tributo(tip)
{
    if(tip==1)
    {
        //$("#select_tipo_via").val(0);
        //$("#select_hab_urb").val(0);
        $('#nombre_tributo').val("");
        $('#valor_tributo').val("");
        $('#nombre_procedimiento').val("");
    }
}

function nuevo_tributo()
{
    if ($("#hiddenproced_ofi").val()==0) {
        mostraralertasconfoco("Seleccione Oficina ","#proced_ofi");
        return false;
    }
    $("#dlg_ofi").val($("#proced_ofi").val());
    
    limpiar_dl_tributo(1);
    $("#dlg_nuevo_tributo").dialog({
        autoOpen: false, modal: true, width: 1100, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  NUEVO TRIBUTO :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Guardar",
            "class": "btn btn-success bg-color-green",
            click: function () {

                guardar_editar_tributo(1);
            }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {
                $(this).dialog("close");
            }
        }],
    });
    $("#dlg_nuevo_tributo").dialog('open');
    autocompletar_procedimientos('nombre_procedimiento');
}

function actualizar_tributo()
{
    if ($("#hiddenproced_ofi").val()==0) {
        mostraralertasconfoco("Seleccione Oficina ","#proced_ofi");
        return false;
    }
    $("#dlg_ofi").val($("#proced_ofi").val());
    limpiar_dl_tributo(1);
    $("#dlg_nuevo_tributo").dialog({
        autoOpen: false, modal: true, width: 1100, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  EDITAR TRIBUTO  :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Guardar",
            "class": "btn btn-success bg-color-green",
            click: function () {
                guardar_editar_tributo(2);
            }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {
                $(this).dialog("close");
            }
        }],
    });
    $("#dlg_nuevo_tributo").dialog('open');
    autocompletar_procedimientos('nombre_procedimiento');


    MensajeDialogLoadAjax('dlg_nuevo_tributo', '.:: Cargando ...');

    id = $("#current_id").val();
    $.ajax({url: 'tributos/'+id,
        type: 'GET',
        success: function(r)
        {
            $("#id_tributo").val(r[0].id_tributo);
            $("#hidden_nombre_procedimiento").val(r[0].id_procedimiento);
            $("#nombre_procedimiento").val(r[0].descrip_procedim);
            $("#nombre_tributo").val(r[0].descrip_tributo);
            $("#valor_tributo").val(r[0].soles);
            MensajeDialogLoadAjaxFinish('dlg_nuevo_tributo');

        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            MensajeDialogLoadAjaxFinish('dlg_nuevo_tributo');
        }
    });
}

function guardar_editar_tributo(tipo) {

    tipo_procedimiento = $("#hidden_nombre_procedimiento").val();
    valor_tipo_procedimiento = $("#nombre_procedimiento").val();
    nombre_tributo = $("#nombre_tributo").val();
    valor_tributo = $("#valor_tributo").val();

   // alert(id_sect);
   if (valor_tipo_procedimiento == '') {
        mostraralertasconfoco('* El campo Tipo de Procedimiento es obligatorio...', 'valor_tipo_procedimiento');
        return false;
    }
    if (nombre_tributo == '') {
        mostraralertasconfoco('* El campo Nombre de Tributo es obligatorio...', 'nombre_tributo');
        return false;
    }
    if (valor_tributo == '') {
        mostraralertasconfoco('* El campo Valor de Tributo es obligatorio...', 'valor_tributo');
        return false;
    }
    

    if (tipo == 1) {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'tributos/create',
            type: 'GET',
            data: {
                id_procedimiento: tipo_procedimiento,
                descrip_tributo: nombre_tributo,
                soles: valor_tributo
            },
            success: function (data) {
                if (data.msg === 'si'){
                    dialog_close('dlg_nuevo_tributo');
                    fn_actualizar_grilla('tabla_tributo');
                    mostraralertasconfoco('* FALTA INGRESAR EL PROCEDIMIENTO');
                }else{
                    dialog_close('dlg_nuevo_tributo');
                    fn_actualizar_grilla('tabla_tributo');
                    MensajeExito('Nuevo Tributo', 'El Item se a Creado Correctamente.');
                }
            },
            error: function (data) {
                mostraralertas('* Contactese con el Administrador...');
            }
        });
    }
    else if (tipo == 2) {
        id = $("#current_id").val();
        MensajeDialogLoadAjax('dlg_nuevo_tributo', '.:: CARGANDO ...');
        $.confirm({
            title: '.:Cuidado... !',
            content: 'Los Cambios no se podran revertir...',
            buttons: {
                Confirmar: function () {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: 'tributos/'+id+'/edit',
                        type: 'GET',
                        data: {
                            id_tributo:id,
                            id_procedimiento: tipo_procedimiento,
                            descrip_tributo: nombre_tributo,
                            soles: valor_tributo
                        },
                        success: function (data) {
                            MensajeExito('Editar Tributo', 'Tributo : '+ id + '  -  Ha sido Modificado.');
                            fn_actualizar_grilla('tabla_tributo');
                            dialog_close('dlg_nuevo_tributo');
                            MensajeDialogLoadAjaxFinish('dlg_nuevo_tributo', '.:: CARGANDO ...');
                        },
                        error: function (data) {
                            mostraralertas('* Contactese con el Administrador...');
                            MensajeAlerta('Editar Tributo','Ocurrio un Error en la Operacion.');
                            dialog_close('dlg_nuevo_tributo');
                            MensajeDialogLoadAjaxFinish('dlg_nuevo_tributo', '.:: CARGANDO ...');
                        }
                    });
                },
                Cancelar: function () {
                    MensajeAlerta('Editar Tributo','Operacion Cancelada.');
                    MensajeDialogLoadAjaxFinish('dlg_nuevo_tributo', '.:: CARGANDO ...');
                    
                }
            }
        });

    }
}

function eliminar_tributo() {
    if ($("#hiddenproced_ofi").val()==0) {
        mostraralertasconfoco("Seleccione Oficina ","#proced_ofi");
        return false;
    }
    id = $("#current_id").val();

    $.confirm({
        title: '.:Cuidado... !',
        content: 'Los Cambios no se podran revertir...',
        buttons: {
            Confirmar: function () {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: 'tributos/destroy',
                    type: 'POST',
                    data: {_method: 'delete', id_tributo: id},
                    success: function (data) {
                        fn_actualizar_grilla('tabla_tributo');
                        MensajeExito('Eliminar Tributo', id + ' - Ha sido Eliminado');
                    },
                    error: function (data) {
                        MensajeAlerta('Eliminar Tributo', id + ' - No se pudo Eliminar.');
                    }
                });
            },
            Cancelar: function () {
                MensajeAlerta('Eliminar Tributo','Operacion Cancelada.');
            }

        }
    });
}

     
function selecciona_anio_oficina(){
    
    aniox = $("#select_anio").val();
    oficinax = $("#hiddenproced_ofi").val();

    jQuery("#tabla_tributo").jqGrid('setGridParam', {
         url: 'listar_tributos?anio=' + aniox + '&id_ofi=' + oficinax
    }).trigger('reloadGrid');

}

function autocompletar_procedimientos(textbox){
    
    $.ajax({
        type: 'GET',
        url: 'autocomplete_procedimientos?ofi=' + $("#hiddenproced_ofi").val(),
        data: {            
            anio:$("#select_anio").val(),
        },
        success: function (data) {
            var $datos = data;
            $("#nombre_procedimiento").autocomplete({
                source: $datos,
                focus: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hidden" + textbox).val(ui.item.value);
                    $("#" + textbox).attr('maxlength', ui.item.label.length);
                    return false;
                },
                select: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hidden_" + textbox).val(ui.item.value);
                    return false;
                }
            });
        }
    });
}


