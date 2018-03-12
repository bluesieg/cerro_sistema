function dialog_especifica(tipe){
    $("#dlg_especifica").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.: Especifica :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-primary",
                click: function () { if(tipe==1){new_especifica();}else{up_especifica();} }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {$(this).dialog("close");}
            }],
        open: function(){limpiar_form_especifica();}       
    }).dialog('open');
}
function dlg_especifica(){    
    dialog_especifica(1);
    $("#especifica_cod").attr('disabled',false);
}
function new_especifica(){
    cod = $("#especifica_cod").val();
    desc = $("#especifica_desc").val();
    if(cod==""){
        mostraralertasconfoco('Ingrese Codigo','#especifica_cod');
        return false;
    }    
    if(desc==""){
        mostraralertasconfoco('Ingrese Descripción','#especifica_desc');
        return false;
    }
    id_sub_gen_det = $('#table_SubGen_Detalle').jqGrid ('getGridParam', 'selrow');
    $.ajax({
        url: 'especifica/create',
        type: 'GET',
        data: { 
            id_sub_gen_det:id_sub_gen_det,
            cod:cod,
            desc:desc.toUpperCase()
        },
        success: function (data) {
            if(data){
                MensajeExito('Operación','Guardado Correctamente...');
                fn_actualizar_grilla('table_Especifica','get_especifica?anio='+$("#vw_especifica_anio").val()+'&id_sub_gen_det='+id_sub_gen_det);
                dialog_close('dlg_especifica');
            }
        },
        error: function (data) {
            MensajeAlerta('Error de Red.', 'Contactese con el Administrador');
        }
    });
}

function up_dlg_especifica(){
    dialog_especifica(2);
    $("#especifica_cod").attr('disabled',true);
    id_especif=$('#table_Especifica').jqGrid ('getGridParam', 'selrow');
    $("#especifica_cod").val($("#table_Especifica").getCell(id_especif, 'cod_especifica'));
    $("#especifica_desc").val($("#table_Especifica").getCell(id_especif, 'desc'));
}
function up_especifica(){
    cod = $("#especifica_cod").val();
    desc = $("#especifica_desc").val();
    if(cod==""){
        mostraralertasconfoco('Ingrese Codigo','#especifica_cod');
        return false;
    }    
    if(desc==""){
        mostraralertasconfoco('Ingrese Descripción','#especifica_desc');
        return false;
    }    
    
    id_sub_gen_det = $('#table_SubGen_Detalle').jqGrid ('getGridParam', 'selrow');
    id_especif=$('#table_Especifica').jqGrid ('getGridParam', 'selrow');
    $.ajax({
        url: 'especifica/'+id_especif+'/edit',
        type: 'GET',
        data: {
            desc:desc.toUpperCase()
        },
        success: function (data) {
            if(data){
                MensajeExito('Operación','Guardado Correctamente...');
                fn_actualizar_grilla('table_Especifica','get_especifica?anio='+$("#vw_especifica_anio").val()+'&id_sub_gen_det='+id_sub_gen_det);
                dialog_close('dlg_especifica');
            }
        },
        error: function (data) {
            MensajeAlerta('Error de Red.', 'Contactese con el Administrador');
        }
    });
}
function del_especifica(){    
    id_sub_gen_det = $('#table_SubGen_Detalle').jqGrid ('getGridParam', 'selrow');
    id_especif=$('#table_Especifica').jqGrid ('getGridParam', 'selrow');
    
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'especifica/destroy',
        type: 'post',
        data: {_method: 'delete',id:id_especif},
        success: function(data){
            MensajeExito('Operación','Se ha eliminado un Elemento...');
            fn_actualizar_grilla('table_Especifica','get_especifica?anio='+$("#vw_especifica_anio").val()+'&id_sub_gen_det='+id_sub_gen_det);
        },
        error: function(data) {
            MensajeAlerta('Error de Red.', 'Contactese con el Administrador');
        }
    });
}

function limpiar_form_especifica(){
    $("#especifica_cod,#especifica_desc").val('');    
}

function selecciona_anio(){
    
    jQuery("#table_Generica").jqGrid('setGridParam', {
         url: 'get_generica?anio='+$("#vw_especifica_anio").val()
    }).trigger('reloadGrid');
}