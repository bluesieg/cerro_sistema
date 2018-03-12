function dialog_esp_detalle(tipe){
    $("#dlg_esp_detalle").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.: Especifica Detalle :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-primary",
                click: function () { if(tipe==1){new_esp_detalle();}else{up_esp_detalle();} }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {$(this).dialog("close");}
            }],
        open: function(){limpiar_form_esp_det();}       
    }).dialog('open');
}
function dlg_esp_detalle(){    
    dialog_esp_detalle(1);
    $("#esp_det_cod").attr('disabled',false);
}
function new_esp_detalle(){
    cod = $("#esp_det_cod").val();
    desc = $("#esp_det_desc").val();
    if(cod==""){
        mostraralertasconfoco('Ingrese Codigo','#esp_det_cod');
        return false;
    }    
    if(desc==""){
        mostraralertasconfoco('Ingrese Descripción','#esp_det_desc');
        return false;
    }
    id_espec = $('#table_Especifica').jqGrid ('getGridParam', 'selrow');
    $.ajax({
        url: 'especifica_detalle/create',
        type: 'GET',
        data: { 
            id_espec:id_espec,
            cod:cod,
            desc:desc.toUpperCase()
        },
        success: function (data) {
            if(data){
                MensajeExito('Operación','Guardado Correctamente...');
                fn_actualizar_grilla('table_Esp_Detalle','get_esp_detalle?anio='+$("#vw_esp_det_anio").val()+'&id_espec='+id_espec);
                dialog_close('dlg_esp_detalle');
            }
        },
        error: function (data) {
            MensajeAlerta('Error de Red.', 'Contactese con el Administrador');
        }
    });
}
function up_dlg_esp_detalle(){
    dialog_esp_detalle(2);
    $("#esp_det_cod").attr('disabled',true);
    id=$('#table_Esp_Detalle').jqGrid ('getGridParam', 'selrow');    
    $("#esp_det_cod").val($("#table_Esp_Detalle").getCell(id, 'cod_esp_det'));
    $("#esp_det_desc").val($("#table_Esp_Detalle").getCell(id, 'desc'));
}
function up_esp_detalle(){
    cod = $("#esp_det_cod").val();
    desc = $("#esp_det_desc").val();
    if(cod==""){
        mostraralertasconfoco('Ingrese Codigo','#esp_det_cod');
        return false;
    }    
    if(desc==""){
        mostraralertasconfoco('Ingrese Descripción','#esp_det_desc');
        return false;
    }
    
    id_espec=$('#table_Especifica').jqGrid ('getGridParam', 'selrow');
    id_esp_det = $('#table_Esp_Detalle').jqGrid ('getGridParam', 'selrow');
    $.ajax({
        url: 'especifica_detalle/'+id_esp_det+'/edit',
        type: 'GET',
        data: {
            desc:desc.toUpperCase()
        },
        success: function (data) {
            if(data){
                MensajeExito('Operación','Guardado Correctamente...');
                fn_actualizar_grilla('table_Esp_Detalle','get_esp_detalle?anio='+$("#vw_esp_det_anio").val()+'&id_espec='+id_espec);
                dialog_close('dlg_esp_detalle');
            }
        },
        error: function (data) {
            MensajeAlerta('Error de Red.', 'Contactese con el Administrador');
        }
    });
}
function del_esp_detalle(){    
    id_espec=$('#table_Especifica').jqGrid ('getGridParam', 'selrow');
    id_esp_det = $('#table_Esp_Detalle').jqGrid ('getGridParam', 'selrow');
    
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'especifica_detalle/destroy',
        type: 'post',
        data: {_method: 'delete',id:id_esp_det},
        success: function(data){
            MensajeExito('Operación','Se ha eliminado un Elemento...');
            fn_actualizar_grilla('table_Esp_Detalle','get_esp_detalle?anio='+$("#vw_esp_det_anio").val()+'&id_espec='+id_espec);
        },
        error: function(data) {
            MensajeAlerta('Error de Red.', 'Contactese con el Administrador');
        }
    });
}

function limpiar_form_esp_det(){
    $("#esp_det_cod,#esp_det_desc").val('');    
}

function selecciona_anio(){
    
    jQuery("#table_Generica").jqGrid('setGridParam', {
         url: 'get_generica?anio='+$("#vw_esp_det_anio").val()
    }).trigger('reloadGrid');
}

