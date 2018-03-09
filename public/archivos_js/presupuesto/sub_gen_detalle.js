function subgen_detalle(tipe){
    $("#dlg_subgen_detalle").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.: Sub - Generica Detalle :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-primary",
                click: function () { if(tipe==1){new_subgen_detalle();}else{up_subgen_detalle();} }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {$(this).dialog("close");}
            }],
        open: function(){limpiar_form_subgendeta();}       
    }).dialog('open');
}
function dlg_subgen_detalle(){    
    subgen_detalle(1);
    $("#subgendetalle_cod").attr('disabled',false);
}
function new_subgen_detalle(){
    cod = $("#subgendetalle_cod").val();
    desc = $("#subgendetalle_desc").val();
    if(cod==""){
        mostraralertasconfoco('Ingrese Codigo','#subgendetalle_cod');
        return false;
    }    
    if(desc==""){
        mostraralertasconfoco('Ingrese Descripción','#subgendetalle_desc');
        return false;
    }
    id_sub_gen = $('#table_SubGenerica').jqGrid ('getGridParam', 'selrow');
    $.ajax({
        url: 'sub_gen_detalle/create',
        type: 'GET',
        data: { 
            id_sub_gen:id_sub_gen,
            cod:cod,
            desc:desc.toUpperCase()
        },
        success: function (data) {
            if(data){
                MensajeExito('Operación','Guardado Correctamente...');
                fn_actualizar_grilla('table_SubGen_Detalle','get_subgenerica_detalle?anio='+$("#vw_subgen_anio").val()+'&id_sub_gen='+id_sub_gen);
                dialog_close('dlg_subgen_detalle');
            }
        },
        error: function (data) {
            MensajeAlerta('Error de Red.', 'Contactese con el Administrador');
        }
    });
}

function up_dlg_subgen_detalle(){
    subgen_detalle(2);
    $("#subgendetalle_cod").attr('disabled',true);
    id_sub_gen_det=$('#table_SubGen_Detalle').jqGrid ('getGridParam', 'selrow');
    $("#subgendetalle_cod").val($("#table_SubGen_Detalle").getCell(id_sub_gen_det, 'cod_subgendeta'));
    $("#subgendetalle_desc").val($("#table_SubGen_Detalle").getCell(id_sub_gen_det, 'desc'));
}

function up_subgen_detalle(){
    cod = $("#subgendetalle_cod").val();
    desc = $("#subgendetalle_desc").val();
    if(cod==""){
        mostraralertasconfoco('Ingrese Codigo','#subgendetalle_cod');
        return false;
    }    
    if(desc==""){
        mostraralertasconfoco('Ingrese Descripción','#subgendetalle_desc');
        return false;
    }
    
    id_sub_gen=$('#table_SubGenerica').jqGrid ('getGridParam', 'selrow');
    id_sub_gen_det = $('#table_SubGen_Detalle').jqGrid ('getGridParam', 'selrow');
    
    $.ajax({
        url: 'sub_gen_detalle/'+id_sub_gen_det+'/edit',
        type: 'GET',
        data: {
            desc:desc.toUpperCase()
        },
        success: function (data) {
            if(data){
                MensajeExito('Operación','Guardado Correctamente...');
                fn_actualizar_grilla('table_SubGen_Detalle','get_subgenerica_detalle?anio='+$("#vw_subgen_anio").val()+'&id_sub_gen='+id_sub_gen);
                dialog_close('dlg_subgen_detalle');
            }
        },
        error: function (data) {
            MensajeAlerta('Error de Red.', 'Contactese con el Administrador');
        }
    });
}
function del_subgen_detalle(){
    id_sub_gen=$('#table_SubGenerica').jqGrid ('getGridParam', 'selrow');
    id_sub_gen_det = $('#table_SubGen_Detalle').jqGrid ('getGridParam', 'selrow');
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'sub_gen_detalle/destroy',
        type: 'post',
        data: {_method: 'delete',id:id_sub_gen_det},
        success: function(data){
            MensajeExito('Operación','Se ha eliminado un Elemento...');
            fn_actualizar_grilla('table_SubGen_Detalle','get_subgenerica_detalle?anio='+$("#vw_subgen_anio").val()+'&id_sub_gen='+id_sub_gen);
        },
        error: function(data) {
            MensajeAlerta('Error de Red.', 'Contactese con el Administrador');
        }
    });
}
function limpiar_form_subgendeta(){
    $("#subgendetalle_cod,#subgendetalle_desc").val('');    
}


function selecciona_anio(){
    
    jQuery("#table_Generica").jqGrid('setGridParam', {
         url: 'get_generica?anio='+$("#vw_subgen_anio").val()
    }).trigger('reloadGrid');
}