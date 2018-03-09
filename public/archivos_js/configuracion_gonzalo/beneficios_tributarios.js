function limpiar_dl_ben_trib(tip)
{
    if(tip==1)
    {
        $('#dlg_documento').val("");
        $('#dlg_tim').val("");
        $('#dlg_multa_tributaria').val("");
        $('#dlg_interes_multa_tributaria').val("");
        $('#dlg_arbitrios').val("");
    }
}

function nuevo_ben_trib()
{
    limpiar_dl_ben_trib(1);
    $("#dlg_nuevo_beneficio_tributario").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  NUEVO BENEFICIO TRIBUTARIO :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Guardar",
            "class": "btn btn-success bg-color-green",
            click: function () {

                guardar_editar_beneficio_tributario(1);
            }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {
                $(this).dialog("close");
            }
        }],
    });
    $("#dlg_nuevo_beneficio_tributario").dialog('open');
}

function actualizar_ben_trib()
{
    var idarray = jQuery('#tabla_beneficios_tributarios').jqGrid('getDataIDs');
        if (idarray.length == '') {
        mostraralertasconfoco('* No Existen Registros en la Tabla...');
        }else{
            
    limpiar_dl_ben_trib(1);
    $("#dlg_nuevo_beneficio_tributario").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  EDITAR BENEFICIOS TRIBUTARIOS  :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Guardar",
            "class": "btn btn-success bg-color-green",
            click: function () {
                guardar_editar_beneficio_tributario(2);
            }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {
                $(this).dialog("close");
            }
        }],
    });
    $("#dlg_nuevo_beneficio_tributario").dialog('open');


    MensajeDialogLoadAjax('dlg_nuevo_beneficio_tributario', '.:: Cargando ...');

    id = $("#current_id").val();
    $.ajax({url: 'beneficios_tributarios/'+id,
        type: 'GET',
        success: function(r)
        {
            $("#dlg_documento").val(r[0].documento);
            $("#dlg_fecha_emision").val(r[0].fecha_emision);
            $("#dlg_inicio_vigencia").val(r[0].inicio_vigencia);
            $("#dlg_fin_vigencia").val(r[0].fin_vigencia);
            $("#dlg_tim").val(r[0].tim);
            $("#dlg_multa_tributaria").val(r[0].multa_tributaria);
            $("#dlg_interes_multa_tributaria").val(r[0].interes_multa_tributaria);
            $("#dlg_arbitrios").val(r[0].arbitrios);
            MensajeDialogLoadAjaxFinish('dlg_nuevo_beneficio_tributario');

        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            MensajeDialogLoadAjaxFinish('dlg_nuevo_beneficio_tributario');
        }
    });
    
    }
}

function guardar_editar_beneficio_tributario(tipo) {

    documento = $("#dlg_documento").val();
    f_emision = $("#dlg_fecha_emision").val();
    f_ini_vigencia = $("#dlg_inicio_vigencia").val();
    f_fin_vigencia = $("#dlg_fin_vigencia").val();
    tim = $("#dlg_tim").val();
    multa_tributaria = $("#dlg_multa_tributaria").val();
    interes_multa_tributaria = $("#dlg_interes_multa_tributaria").val();
    arbitrios = $("#dlg_arbitrios").val();
   
    if (documento == '') {
        mostraralertasconfoco('* El campo Documento es obligatorio...', 'documento');
        return false;
    }
    if (tim == '') {
        mostraralertasconfoco('* El campo tim obligatorio...', 'tim');
        return false;
    }
    if (multa_tributaria == '') {
        mostraralertasconfoco('* El campo Multa Tributaria obligatorio...', 'multa_tributaria');
        return false;
    }
    if (interes_multa_tributaria == '') {
        mostraralertasconfoco('* El campo Interes de Multa Tributaria obligatorio...', 'interes_multa_tributaria');
        return false;
    }
    if (arbitrios == '') {
        mostraralertasconfoco('* El campo Arbitrios obligatorio...', 'arbitrios');
        return false;
    }

    if (tipo == 1) {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'beneficios_tributarios/create',
            type: 'GET',
            data: {
                documento: documento,
                f_emision: f_emision,
                f_ini_vigencia: f_ini_vigencia,
                f_fin_vigencia: f_fin_vigencia,
                tim:tim,
                multa_tributaria:multa_tributaria,
                interes_multa_tributaria:interes_multa_tributaria,
                arbitrios:arbitrios
            },
            success: function (data) {
                dialog_close('dlg_nuevo_beneficio_tributario');
                fn_actualizar_grilla('tabla_beneficios_tributarios');
                MensajeExito('Nuevo Beneficio Tributario', 'El Beneficio Tributario se a creado correctamente.');
            },
            error: function (data) {
                mostraralertas('* Contactese con el Administrador...');
            }
        });
    }
    else if (tipo == 2) {
        id = $("#current_id").val();
        MensajeDialogLoadAjax('dlg_nuevo_beneficio_tributario', '.:: CARGANDO ...');
        $.confirm({
            title: '.:Cuidado... !',
            content: 'Los Cambios no se podran revertir...',
            buttons: {
                Confirmar: function () {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: 'beneficios_tributarios/'+id+'/edit',
                        type: 'GET',
                        data: {
                            id_ben_trib:id,
                            documento: documento,
                            f_emision: f_emision,
                            f_ini_vigencia: f_ini_vigencia,
                            f_fin_vigencia: f_fin_vigencia,
                            tim:tim,
                            multa_tributaria:multa_tributaria,
                            interes_multa_tributaria:interes_multa_tributaria,
                            arbitrios:arbitrios
                        },
                        success: function (data) {
                            MensajeExito('Editar Beneficio Tributario', 'Beneficio Tributario: '+ id + '  -  Ha sido Modificado.');
                            fn_actualizar_grilla('tabla_beneficios_tributarios');
                            dialog_close('dlg_nuevo_beneficio_tributario');
                            MensajeDialogLoadAjaxFinish('dlg_nuevo_beneficio_tributario', '.:: CARGANDO ...');
                        },
                        error: function (data) {
                            mostraralertas('* Contactese con el Administrador...');
                            MensajeAlerta('Editar Beneficio Tributario','Ocurrio un Error en la Operacion.');
                            dialog_close('dlg_nuevo_beneficio_tributario');
                            MensajeDialogLoadAjaxFinish('dlg_nuevo_beneficio_tributario', '.:: CARGANDO ...');
                        }
                    });
                },
                Cancelar: function () {
                    MensajeAlerta('Editar Beneficio Tributario','Operacion Cancelada.');
                    MensajeDialogLoadAjaxFinish('dlg_nuevo_beneficio_tributario', '.:: CARGANDO ...');
                    
                }
            }
        });

    }
}

function eliminar_ben_trib() {
    
    var idarray = jQuery('#tabla_beneficios_tributarios').jqGrid('getDataIDs');
        if (idarray.length == '') {
        mostraralertasconfoco('* No Existen Registros en la Tabla...');
        }else{
    
    id = $("#current_id").val();

    $.confirm({
        title: '.:Cuidado... !',
        content: 'Los Cambios no se podran revertir...',
        buttons: {
            Confirmar: function () {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: 'beneficios_tributarios/destroy',
                    type: 'POST',
                    data: {_method: 'delete',id_ben_trib: id},
                    success: function (data) {
                        fn_actualizar_grilla('tabla_beneficios_tributarios');
                        MensajeExito('Eliminar Beneficios Tributario', id + ' - Ha sido Eliminado');
                    },
                    error: function (data) {
                        MensajeAlerta('Eliminar eneficios Tributario', id + ' - No se pudo Eliminar.');
                    }
                });
            },
            Cancelar: function () {
                MensajeAlerta('Eliminar eneficios Tributario','Operacion Cancelada.');
            }

        }
    });
    
    }
}

function VerEstado(id_bene_tribu,estado)
{
    $.ajax({
        url:'actualizar_estado?id_bene_tribu='+id_bene_tribu+'&estado='+estado,
        type:'GET',
        success: function(data){
            
                MensajeExito('Cambio de Estado', 'El Beneficio Tributario a cambiado de estado.');
                fn_actualizar_grilla('tabla_beneficios_tributarios');
    
        }        
    });
}
