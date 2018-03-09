

function dlg_generica(){
    $("#dlg_gen").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.: Generica :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-primary",
                click: function () { new_generica(); }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {$(this).dialog("close");}
            }],
        open: function(){limpiar_form_gen();}       
    }).dialog('open');
    $("#gen_cod").attr('disabled',false);
}
function new_generica(){
    gen_cod = $("#gen_cod").val();
    gen_desc = $("#gen_desc").val();
    if(gen_cod==""){
        mostraralertasconfoco('Ingrese Codigo','#gen_cod');
        return false;
    }    
    if(gen_desc==""){
        mostraralertasconfoco('Ingrese Descripción','#gen_desc');
        return false;
    }
    $.ajax({
        url: 'generica/create',
        type: 'GET',
        data: {            
            gen_cod:gen_cod,
            gen_desc:gen_desc.toUpperCase()
        },
        success: function (data) {
            if(data){
                MensajeExito('Operación','Guardado Correctamente...');
                fn_actualizar_grilla('table_Generica','get_generica?anio='+$("#vw_gen_anio").val());
                dialog_close('dlg_gen');
            }
        },
        error: function (data) {
            MensajeAlerta('Error de Red.', 'Contactese con el Administrador');
        }
    });
}

function up_dlg_generica(){
    $("#dlg_gen").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.: Generica :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-primary",
                click: function () { up_generica(); }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {$(this).dialog("close");}
            }],
        open: function(){limpiar_form_gen();}       
    }).dialog('open');
    $("#gen_cod").attr('disabled',true);
    id_gener=$('#table_Generica').jqGrid ('getGridParam', 'selrow');
    $("#gen_cod").val($("#table_Generica").getCell(id_gener, 'cod_generica'));
    $("#gen_desc").val($("#table_Generica").getCell(id_gener, 'descr_gen'));
}
function up_generica(){
    gen_cod = $("#gen_cod").val();
    gen_desc = $("#gen_desc").val();
    if(gen_cod==""){
        mostraralertasconfoco('Ingrese Codigo','#gen_cod');
        return false;
    }    
    if(gen_desc==""){
        mostraralertasconfoco('Ingrese Descripción','#gen_desc');
        return false;
    }
    id_gener=$('#table_Generica').jqGrid ('getGridParam', 'selrow');
    $.ajax({
        url: 'generica/'+id_gener+'/edit',
        type: 'GET',
        data: {            
            gen_cod:gen_cod,
            gen_desc:gen_desc.toUpperCase()
        },
        success: function (data) {
            if(data){
                MensajeExito('Operación','Guardado Correctamente...');
                fn_actualizar_grilla('table_Generica','get_generica?anio='+$("#vw_gen_anio").val());
                dialog_close('dlg_gen');
            }
        },
        error: function (data) {
            MensajeAlerta('Error de Red.', 'Contactese con el Administrador');
        }
    });
}
function del_gen(){
    id_gener=$('#table_Generica').jqGrid ('getGridParam', 'selrow');
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'generica/destroy',
        type: 'post',
        data: {_method: 'delete',id:id_gener},
        success: function(data){
            MensajeExito('Operación','Elemento Eliminado...');
            fn_actualizar_grilla('table_Generica','get_generica?anio='+$("#vw_gen_anio").val());
        },
        error: function(data) {
            MensajeAlerta('Error de Red.', 'Contactese con el Administrador');
            console.log('error');
            console.log(data);
        }
    });
}
function limpiar_form_gen(){
    $("#gen_cod,#gen_desc").val('');    
}

function selecciona_anio(){
    
    jQuery("#table_Generica").jqGrid('setGridParam', {
         url: 'get_generica?anio='+$("#vw_gen_anio").val()
    }).trigger('reloadGrid');
}
