
function dlg_procedimiento(){
    $("#dlg_proced").dialog({
        autoOpen: false, modal: true, width: 1000, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.: Procedimientos - TUPA :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-primary",
                click: function () { new_procedimiento(); }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {$(this).dialog("close");}
            }],
        open: function(){limpiar_form_proced();}       
    }).dialog('open');
    autocompletar_oficinas('proced_ofi');
    auto_esp_detalle('proced_esp_det');
}
function new_procedimiento(){
    esp_detalle = $("#proced_esp_det").val();
    oficina = $("#proced_ofi").val();
    desc = $("#proced_desc").val();
    if(esp_detalle==""){
        mostraralertasconfoco('Ingrese Especifica Detalle','#proced_esp_det');
        return false;
    }    
    if(oficina==""){
        mostraralertasconfoco('Seleccione una Oficina','#proced_ofi');
        return false;
    }
    if(desc==""){
        mostraralertasconfoco('Ingrese Descripción','#proced_desc');
        return false;
    }
    vw_procedim_anio
    $.ajax({
        url: 'procedimientos/create',
        type: 'GET',
        data: {            
            descrip_procedim:desc.toUpperCase(),
            id_ofic:$("#hiddenproced_ofi").val(),
            id_espec_det:$("#hiddenproced_esp_det").val(),
            anio:$("#vw_procedim_anio").val()
        },
        success: function (data) {
            if(data){
                MensajeExito('Operación','Guardado Correctamente...');
                fn_actualizar_grilla('table_Procedimiento','get_procedimientos?anio='+$("#vw_procedim_anio").val());
                dialog_close('dlg_proced');
            }
        },
        error: function (data) {
            MensajeAlerta('Error de Red.', 'Contactese con el Administrador');
        }
    });
}
function up_dlg_procedimiento(){
    $("#dlg_proced").dialog({
        autoOpen: false, modal: true, width: 1000, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.: Procedimientos - TUPA :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-primary",
                click: function () { up_procedimiento(); }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {$(this).dialog("close");}
            }],
        open: function(){limpiar_form_proced();}       
    }).dialog('open');
    autocompletar_oficinas('proced_ofi');
    auto_esp_detalle('proced_esp_det');
    
    id_proced = $('#table_Procedimiento').jqGrid ('getGridParam', 'selrow');
    $("#hiddenproced_esp_det").val($("#table_Procedimiento").getCell(id_proced, 'id_espec_det'));
    $("#proced_esp_det").val($("#table_Procedimiento").getCell(id_proced, 'desc_espec_detalle'));
    $("#hiddenproced_ofi").val($("#table_Procedimiento").getCell(id_proced, 'id_ofic'));
    $("#proced_ofi").val($("#table_Procedimiento").getCell(id_proced, 'nombre'));
    $("#proced_desc").val($("#table_Procedimiento").getCell(id_proced, 'desc_proc'));
}
function up_procedimiento(){
    esp_detalle = $("#proced_esp_det").val();
    oficina = $("#proced_ofi").val();
    desc = $("#proced_desc").val();
    if(esp_detalle==""){
        mostraralertasconfoco('Ingrese Especifica Detalle','#proced_esp_det');
        return false;
    }    
    if(oficina==""){
        mostraralertasconfoco('Seleccione una Oficina','#proced_ofi');
        return false;
    }
    if(desc==""){
        mostraralertasconfoco('Ingrese Descripción','#proced_desc');
        return false;
    }
    id_proced = $('#table_Procedimiento').jqGrid ('getGridParam', 'selrow');
    $.ajax({
        url: 'procedimientos/'+id_proced+'/edit',
        type: 'GET',
        data: {            
            descrip_procedim:desc.toUpperCase(),
            id_ofic:$("#hiddenproced_ofi").val(),
            id_espec_det:$("#hiddenproced_esp_det").val()
        },
        success: function (data) {
            if(data){
                MensajeExito('Operación','Guardado Correctamente...');
                fn_actualizar_grilla('table_Procedimiento','get_procedimientos?anio='+$("#vw_procedim_anio").val());
                dialog_close('dlg_proced');
            }
        },
        error: function (data) {
            MensajeAlerta('Error de Red.', 'Contactese con el Administrador');
        }
    });
}
function del_procedimiento(){
    id_proced = $('#table_Procedimiento').jqGrid ('getGridParam', 'selrow');
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'procedimientos/destroy',
        type: 'post',
        data: {_method: 'delete',id:id_proced},
        success: function(data){
            MensajeExito('Operación','Elemento Eliminado...');
            fn_actualizar_grilla('table_Procedimiento','get_procedimientos?anio='+$("#vw_procedim_anio").val());
        },
        error: function(data) {
            MensajeAlerta('Error de Red.', 'Contactese con el Administrador');
            console.log('error');
            console.log(data);
        }
    });
}
function auto_esp_detalle(textbox){
    $.ajax({
        type: 'GET',
        url: 'auto_esp_detalle',
        data: {            
            anio:$("#vw_procedim_anio").val(),
        },
        success: function (data) {
            var $datos = data;
            $("#" + textbox).autocomplete({
                source: $datos,
                focus: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hidden" + textbox).val(ui.item.value);
                    $("#" + textbox).attr('maxlength', ui.item.label.length);
                    return false;
                },
                select: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hidden" + textbox).val(ui.item.value);
                    return false;
                }
            });
        }
    });
}
function autocompletar_oficinas(textbox){
    $.ajax({
        type: 'GET',
        url: 'autocompletar_oficinas',
        success: function (data) {
            var $datos = data;
            $("#" + textbox).autocomplete({
                source: $datos,
                focus: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hidden" + textbox).val(ui.item.value);
                    $("#" + textbox).attr('maxlength', ui.item.label.length);
                    return false;
                },
                select: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hidden" + textbox).val(ui.item.value);
                    return false;
                }
            });
        }
    });
}

     
function seleccionar_anio(){
    
    anio = $("#vw_procedim_anio").val();
    procedimiento = $('#dlg_buscar_procedimiento').val();

    jQuery("#table_Procedimiento").jqGrid('setGridParam', {
         url: 'get_procedimientos?anio=' + anio + '&procedimiento=' + procedimiento
    }).trigger('reloadGrid');

}

function limpiar_form_proced(){
    $("#proced_desc,#hiddenproced_ofi,#proced_ofi,#proced_esp_det,#hiddenproced_esp_det").val('');    
}


function fn_buscar_procedimiento(){
    procedimiento = $('#dlg_buscar_procedimiento').val();
    anio = $("#vw_procedim_anio").val();
            
    MensajeDialogLoadAjax('dlg_buscar_procedimiento', '.:: Cargando ...');
    
    
    jQuery("#table_Procedimiento").jqGrid('setGridParam', {
         url: 'get_procedimientos?anio=' + anio + '&procedimiento=' + procedimiento
    }).trigger('reloadGrid');
    
    MensajeDialogLoadAjaxFinish('dlg_buscar_procedimiento');
    
}