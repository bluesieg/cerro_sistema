function fn_bus_contrib()
{
    if($("#dlg_contribuyente").val()=="")
    {
        mostraralertasconfoco("Ingresar Información de busqueda","#dlg_contribuyente"); 
        return false;
    }
    if($("#dlg_contribuyente").val().length<4)
    {
        mostraralertasconfoco("Ingresar al menos 4 caracteres de busqueda","#contribuyente"); 
        return false;
    }

    jQuery("#table_contribuyente").jqGrid('setGridParam', {url: 'obtener_contribuyente?dat='+$("#dlg_contribuyente").val()}).trigger('reloadGrid');

    $("#dlg_bus_contribuyente").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  Busqueda de Contribuyentes :.</h4></div>"       
        }).dialog('open');
       
}
function fn_bus_usuarios_web()
{
    if($("#dlg_usuarios_web").val()=="")
    {
        mostraralertasconfoco("Ingresar Información de busqueda","#dlg_contribuyente"); 
        return false;
    }
    if($("#dlg_usuarios_web").val().length<4)
    {
        mostraralertasconfoco("Ingresar al menos 4 caracteres de busqueda","#contribuyente"); 
        return false;
    }

    jQuery("#table_contribuyente").jqGrid('setGridParam', {url: 'obtener_contribuyente?dat='+$("#dlg_contribuyente").val()}).trigger('reloadGrid');

    $("#dlg_bus_contribuyente").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  Busqueda de Contribuyentes :.</h4></div>"       
        }).dialog('open');
       
}
function fn_llenar_datos(per){
    $("#hidden_id_contribuyente").val(per);
    
    $("#dlg_dni").val($('#table_contribuyente').jqGrid('getCell',per,'nro_doc'));
    $("#dlg_contribuyente").val($('#table_contribuyente').jqGrid('getCell',per,'contribuyente'));
    $("#dlg_codigo_contribuyente").val($('#table_contribuyente').jqGrid('getCell',per,'id_persona'));
    $("#dlg_tipo_persona").val($('#table_contribuyente').jqGrid('getCell',per,'tipo_persona'));
    $("#dlg_condicion").val($('#table_contribuyente').jqGrid('getCell',per,'condicion'));
    $("#dlg_domicilio_fiscal").val($('#table_contribuyente').jqGrid('getCell',per,'domic_fiscal'));
    
    //tam=($('#table_contrib').jqGrid('getCell',per,'contribuyente')).length;
    //$("#vw_caja_est_cta_contrib").attr('maxlength',tam);
    
    id_pers=$('#table_contribuyente').jqGrid('getCell',per,'id_contrib');
    fn_actualizar_grilla('tabla','obtener_predios?id_contrib='+id_pers);
    $("#dlg_bus_contribuyente").dialog("close");    
}



function limpiar(tip)
{
    if(tip==1)
    {
        $('#hidden_id_contribuyente').val("");
        $('#dlg_dni').val("");
        $('#dlg_contribuyente').val("");
        $('#dlg_codigo_contribuyente').val("");
        $('#dlg_tipo_persona').val("");
        $('#dlg_condicion').val("");
        $('#dlg_domicilio_fiscal').val("");
    }
        
}

function limpiar_dl_tim(tip)
{
    if(tip==1)
    {
        $('#documento').val("");
        $('#valor').val("");
        $('#anio').val("");
    }
}

function nuevo_usuario_web()
{
   
    limpiar(1);
    $("#dlg_nuevo_usuario_web").dialog({
        autoOpen: false, modal: true, width: 900, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  NUEVO USUARIO WEB :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Guardar",
            "class": "btn btn-success bg-color-green",
            click: function () {

                guardar_editar_usuario_web(1);
            }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {
                $(this).dialog("close");
            }
        }],
    });
    $("#dlg_nuevo_usuario_web").dialog('open');
}

function actualizar_tim()
{
    var idarray = jQuery('#tabla_tim').jqGrid('getDataIDs');
        if (idarray.length == '') {
        mostraralertasconfoco('* No Existen Registros en la Tabla...', 'dlg_anio');
        }else{
            
        
    $("#dlg_anio").val($("#select_anio option:selected").html());
    $("#id_anio").val($("#select_anio").val());
    limpiar_dl_tim(1);
    $("#dlg_nuevo_tim").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  EDITAR TASA DE INTERES MORATORIO  :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Guardar",
            "class": "btn btn-success bg-color-green",
            click: function () {
                guardar_editar_usuario_web(2);
            }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {
                $(this).dialog("close");
            }
        }],
    });
    $("#dlg_nuevo_tim").dialog('open');


    MensajeDialogLoadAjax('dlg_nuevo_tim', '.:: Cargando ...');

    id = $("#current_id").val();
    $.ajax({url: 'tim/'+id,
        type: 'GET',
        success: function(r)
        {
            $("#id_tim").val(r[0].id_tim);
            $("#documento").val(r[0].documento_aprob);
            $("#valor").val(r[0].tim);
            $("#anio").val(r[0].anio);
            MensajeDialogLoadAjaxFinish('dlg_nuevo_tim');

        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            MensajeDialogLoadAjaxFinish('dlg_nuevo_tim');
        }
    });
    
    }
}

function guardar_editar_usuario_web(tipo) {

    usu = $("#dlg_usuario").val();
    password = $("#dlg_password").val();
    
    id_contrib = $("#hidden_id_contribuyente").val();
    nro_doc = $("#dlg_dni").val();
    contribuyente = $("#dlg_contribuyente").val();
    id_persona = $("#dlg_codigo_contribuyente").val();
    tipo_persona = $("#dlg_tipo_persona").val();
    condicion = $("#dlg_condicion").val();  
    domic_fiscal = $("#dlg_domicilio_fiscal").val();

    if (usu == '') {
        mostraralertasconfoco('* El campo USUARIO es obligatorio...', 'usu');
        return false;
    }
    if (password == '') {
        mostraralertasconfoco('* El campo CONTRASEÑA obligatorio...', 'password');
        return false;
    }
    
    if (tipo == 1) {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'usuarios_web/create',
            type: 'GET',
            data: {
                usuario: usu,
                password: password,
                id_contrib: id_contrib,
                nro_documento: nro_doc,
                contribuyente: contribuyente,
                cod_contribuyente: id_persona,
                tipo_persona: tipo_persona,
                condicion: condicion,
                domicilio_fiscal: domic_fiscal
                
            },
            success: function (data) {
                dialog_close('dlg_nuevo_usuario_web');
                fn_actualizar_grilla('tabla_usuarios_web');
                MensajeExito('Nuevo USUARIO', 'El USUARIO se a creado correctamente.');
            },
            error: function (data) {
                mostraralertas('* Contactese con el Administrador...');
            }
        });
    }
    else if (tipo == 2) {
        id = $("#current_id").val();
        MensajeDialogLoadAjax('dlg_nuevo_tim', '.:: CARGANDO ...');
        $.confirm({
            title: '.:Cuidado... !',
            content: 'Los Cambios no se podran revertir...',
            buttons: {
                Confirmar: function () {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: 'modificar_tim',
                        type: 'POST',
                        data: {
                            id_tim:id,
                            documento_aprob: documento,
                            tim: valor,
                            anio: anio
                        },
                        success: function (data) {
                            MensajeExito('Editar TIM', 'TIM: '+ id + '  -  Ha sido Modificado.');
                            fn_actualizar_grilla('tabla_tim');
                            dialog_close('dlg_nuevo_tim');
                            MensajeDialogLoadAjaxFinish('dlg_nuevo_tim', '.:: CARGANDO ...');
                        },
                        error: function (data) {
                            mostraralertas('* Contactese con el Administrador...');
                            MensajeAlerta('Editar TIM','Ocurrio un Error en la Operacion.');
                            dialog_close('dlg_nuevo_tim');
                            MensajeDialogLoadAjaxFinish('dlg_nuevo_tim', '.:: CARGANDO ...');
                        }
                    });
                },
                Cancelar: function () {
                    MensajeAlerta('Editar TIM','Operacion Cancelada.');
                    MensajeDialogLoadAjaxFinish('dlg_nuevo_tim', '.:: CARGANDO ...');
                    
                }
            }
        });

    }
}

function eliminar_tim() {
    
    var idarray = jQuery('#tabla_tim').jqGrid('getDataIDs');
        if (idarray.length == '') {
        mostraralertasconfoco('* No Existen Registros en la Tabla...', 'dlg_anio');
        }else{
    
    id = $("#current_id").val();

    $.confirm({
        title: '.:Cuidado... !',
        content: 'Los Cambios no se podran revertir...',
        buttons: {
            Confirmar: function () {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: 'eliminar_tim',
                    type: 'POST',
                    data: {id_tim: id},
                    success: function (data) {
                        fn_actualizar_grilla('tabla_tim');
                        MensajeExito('Eliminar TIM', id + ' - Ha sido Eliminado');
                    },
                    error: function (data) {
                        MensajeAlerta('Eliminar TIM', id + ' - No se pudo Eliminar.');
                    }
                });
            },
            Cancelar: function () {
                MensajeAlerta('Eliminar TIM','Operacion Cancelada.');
            }

        }
    });
    
    }
}


