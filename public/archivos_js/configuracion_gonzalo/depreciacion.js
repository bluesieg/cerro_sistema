function limpiar_dl_depreciacion(tip)
{
    if(tip==1)
    {
        $('#por_dep').val("");
       
    }
}

function actualizar_depreciacion()
{
    var idarray = jQuery('#tabla_depreciacion').jqGrid('getDataIDs');
        if (idarray.length == '') {
        mostraralertasconfoco('* No Existen Registros en la Tabla...', 'dlg_anio');
        }else{
            
        
  
    limpiar_dl_depreciacion(1);
    $("#dlg_nueva_depreciacion").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  EDITAR  DEPRECIACIÓN  :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Guardar",
            "class": "btn btn-success bg-color-green",
            click: function () {
                guardar_editar_depreciacion(2);
            }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {
                $(this).dialog("close");
            }
        }],
    });
    $("#dlg_nueva_depreciacion").dialog('open');


    MensajeDialogLoadAjax('dlg_nueva_depreciacion', '.:: Cargando ...');

    id = $("#current_id").val();
    $.ajax({url: 'depreciacion/'+id,
        type: 'GET',
        success: function(r)
        {
            
            $("#id_dep").val(r[0].id_dep);
            $("#por_dep").val(r[0].por_dep);

            MensajeDialogLoadAjaxFinish('dlg_nueva_depreciacion');

        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            MensajeDialogLoadAjaxFinish('dlg_nueva_depreciacion');
        }
    });
    
    }
}

function guardar_editar_depreciacion(tipo) {

    depreciacion = $("#por_dep").val();
    
    if (depreciacion == '') {
        mostraralertasconfoco('* El campo Documento es obligatorio...', 'documento');
        return false;
    }
   
   

    if (tipo == 2) {
        id = $("#current_id").val();
        MensajeDialogLoadAjax('dlg_nueva_depreciacion', '.:: CARGANDO ...');
        $.confirm({
            title: '.:Cuidado... !',
            content: 'Los Cambios no se podran revertir...',
            buttons: {
                Confirmar: function () {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: 'modificar_depreciacion',
                        type: 'POST',
                        data: {
                            id_dep:id,
                            por_dep: depreciacion,
                            
                        },
                        success: function (data) {
                            MensajeExito('Editar Depreciación', 'Depreciación: '+ id + '  -  Ha sido Modificado.');
                            fn_actualizar_grilla('tabla_depreciacion');
                            dialog_close('dlg_nueva_depreciacion');
                            MensajeDialogLoadAjaxFinish('dlg_nueva_depreciacion', '.:: CARGANDO ...');
                        },
                        error: function (data) {
                            mostraralertas('* Contactese con el Administrador...');
                            MensajeAlerta('Editar Depreciación','Ocurrio un Error en la Operacion.');
                            dialog_close('dlg_nueva_depreciacion');
                            MensajeDialogLoadAjaxFinish('dlg_nueva_depreciacion', '.:: CARGANDO ...');
                        }
                    });
                },
                Cancelar: function () {
                    MensajeAlerta('Editar Depreciación','Operacion Cancelada.');
                    MensajeDialogLoadAjaxFinish('dlg_nueva_depreciacion', '.:: CARGANDO ...');
                    
                }
            }
        });

    }
}

function eliminar_tim() {
    
    var idarray = jQuery('#tabla_depreciacion').jqGrid('getDataIDs');
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
                        fn_actualizar_grilla('tabla_depreciacion');
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

function selecciona_edificacion(){
    
    edificacion = $("#select_edificacion").val();

    jQuery("#tabla_depreciacion").jqGrid('setGridParam', {
         url: 'listar_depreciacion?edificacion=' + edificacion 
    }).trigger('reloadGrid');

}