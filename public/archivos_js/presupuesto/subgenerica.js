function open_dlg(tipe){
    $("#dlg_subgen").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.: Sub - Generica :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-primary",
                click: function () { if(tipe==1){new_subgenerica();}else{up_subgenerica();} }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {$(this).dialog("close");}
            }],
        open: function(){limpiar_form_subgen();}       
    }).dialog('open');
}
function dlg_subgenerica(){
    open_dlg(1);
    $("#subgen_cod").attr('disabled',false);
}
function new_subgenerica(){
    cod = $("#subgen_cod").val();
    desc = $("#subgen_desc").val();
    if(cod==""){
        mostraralertasconfoco('Ingrese Codigo','#subgen_cod');
        return false;
    }    
    if(desc==""){
        mostraralertasconfoco('Ingrese Descripción','#subgen_desc');
        return false;
    }
    id_gener = $('#table_Generica').jqGrid ('getGridParam', 'selrow');
    $.ajax({
        url: 'sub_generica/create',
        type: 'GET',
        data: { 
            id_gener:id_gener,
            subgen_cod:cod,
            subgen_desc:desc.toUpperCase()
        },
        success: function (data) {
            if(data){
                MensajeExito('Operación','Guardado Correctamente...');
                fn_actualizar_grilla('table_SubGenerica','get_subgenerica?anio='+$("#vw_subgen_anio").val()+'&id_gener='+id_gener);
                dialog_close('dlg_subgen');
            }
        },
        error: function (data) {
            MensajeAlerta('Error de Red.', 'Contactese con el Administrador');
        }
    });
}

function up_dlg_subgenerica(){
    open_dlg(2);
    $("#subgen_cod").attr('disabled',true);
    id_sub_gener=$('#table_SubGenerica').jqGrid ('getGridParam', 'selrow');
    $("#subgen_cod").val($("#table_SubGenerica").getCell(id_sub_gener, 'cod_sub_generica'));
    $("#subgen_desc").val($("#table_SubGenerica").getCell(id_sub_gener, 'desc_sub_gen'));
}
function up_subgenerica(){
    cod = $("#subgen_cod").val();
    desc = $("#subgen_desc").val();
    if(cod==""){
        mostraralertasconfoco('Ingrese Codigo','#subgen_cod');
        return false;
    }    
    if(desc==""){
        mostraralertasconfoco('Ingrese Descripción','#subgen_desc');
        return false;
    }
    id_gener = $('#table_Generica').jqGrid ('getGridParam', 'selrow');
    id_sub_gener=$('#table_SubGenerica').jqGrid ('getGridParam', 'selrow');
    $.ajax({
        url: 'sub_generica/'+id_sub_gener+'/edit',
        type: 'GET',
        data: {
            subgen_desc:desc.toUpperCase(),
        },
        success: function (data) {
            if(data){
                MensajeExito('Operación','Guardado Correctamente...');
                fn_actualizar_grilla('table_SubGenerica','get_subgenerica?anio='+$("#vw_subgen_anio").val()+'&id_gener='+id_gener);
                dialog_close('dlg_subgen');
            }
        },
        error: function (data) {
            MensajeAlerta('Error de Red.', 'Contactese con el Administrador');
        }
    });
}
function del_subgen(){
    id_gener = $('#table_Generica').jqGrid ('getGridParam', 'selrow');
    id_sub_gener=$('#table_SubGenerica').jqGrid ('getGridParam', 'selrow');
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'sub_generica/destroy',
        type: 'post',
        data: {_method: 'delete',id:id_sub_gener},
        success: function(data){
            MensajeExito('Operación','Se ha eliminado un Elemento...');
            fn_actualizar_grilla('table_SubGenerica','get_subgenerica?anio='+$("#vw_subgen_anio").val()+'&id_gener='+id_gener);
        },
        error: function(data) {
            MensajeAlerta('Error de Red.', 'Contactese con el Administrador');
        }
    });
}
function limpiar_form_subgen(){
    $("#subgen_cod,#subgen_desc").val('');    
}


function selecciona_anio(){
    
    jQuery("#table_Generica").jqGrid('setGridParam', {
         url: 'get_generica?anio='+$("#vw_subgen_anio").val()
    }).trigger('reloadGrid');
}