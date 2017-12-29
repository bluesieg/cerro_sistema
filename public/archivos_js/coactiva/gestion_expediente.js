
function dlg_select_new_doc(){
    rows = jQuery("#tabla_doc_coactiva").jqGrid('getGridParam', 'records');
    
    if(rows==0){ return false; }
    
    id_coa_mtr = $('#tabla_expedientes').jqGrid ('getGridParam', 'selrow');
    estado_proced = $("#tabla_expedientes").getCell(id_coa_mtr, 'estado');
    if(estado_proced==0){ 
        mostraralertas('* Expediente Archivado, <br>* No puede agregar mas Documentos...');
        return false; }
    if(estado_proced==3){ 
        mostraralertas('* Expediente Devuelto, <br>* No puede agregar mas Documentos...');
        return false; }
//    var rowObj = $("#tabla_doc_coactiva").getRowData(0);
    var ids = $("#tabla_doc_coactiva").jqGrid('getDataIDs');
    var fch_recep = $("#tabla_doc_coactiva").jqGrid ('getCell', ids[0], 'fch_recep');
    if(fch_recep==''){
        mostraralertas('* Falta Recepcionar OP/RD...<br>* Recepcione los Documentos Para Iniciar El procedimiento de Ejecución Coactiva');
        return false;
    }
    
    $("#dlg_select_doc").dialog({
        autoOpen: false, modal: true, width: 550, show: {effect: "fade", duration: 300}, resizable: false,        
        title: "<div class='widget-header'><h4>.: DOCUMENTOS :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-fax'></i>&nbsp; Agregar Documento",
                "class": "btn btn-primary",
                click: function () { add_doc_al_exped(); }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {$(this).dialog("close");}
            }],
        open: function(){}       
    }).dialog('open');
}
function add_doc_al_exped(){    
    id_coa_mtr = $('#tabla_expedientes').jqGrid ('getGridParam', 'selrow');
    id_tip_doc = $("input:radio[name ='add_doc_radio']:checked").val();
    if(!id_tip_doc){
        mostraralertas('Seleccione un Documento...');
        return false;
    }
    if(id_tip_doc==9){
        $("#vw_coa_acta_apersonamiento").dialog({
            autoOpen: false, modal: true, width: 400, show: {effect: "fade", duration: 300}, resizable: false,        
            title: "<div class='widget-header'><h4>.: COACTIVA :.</h4></div>",
            buttons: [{
                    html: "<i class='fa fa-fax'></i>&nbsp; Agregar Acta Apersonamiento",
                    "class": "btn btn-primary",
                    click: function () { 
                        MensajeDialogLoadAjax('vw_coa_acta_apersonamiento','Guardando...');
                        var rowCount =  $("#t_dina_acta_aper tr").length;
                        if(rowCount-1==0){
                            mostraralertas('Tabla de Cuotas esta Vacia...'); 
                            MensajeDialogLoadAjaxFinish('vw_coa_acta_apersonamiento');
                            return false;
                        }
                        
                        var fechas = new Array();                        
                        for(i=1;i<=rowCount-1;i++){
                            fechas.push($("#td_din_fch_"+i).val());
                        }
                        var montos = new Array();                        
                        for(i=1;i<=rowCount-1;i++){
                            montos.push($("#td_din_monto_"+i).val());
                        }
//                        cant=fechas.length;
                        fechas_cuotas = fechas.join('*');
                        monto_cuotas = montos.join('*');
//                        monto=$("#nro_cuo_monto").val();
                        save_doc(id_coa_mtr,id_tip_doc,fechas_cuotas,monto_cuotas);
                        setTimeout(function(){ 
                            MensajeDialogLoadAjaxFinish('vw_coa_acta_apersonamiento');
                            dialog_close('vw_coa_acta_apersonamiento');
                            dialog_close('dlg_select_doc');
                        }, 1000);
                    }
                }, {
                    html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                    "class": "btn btn-danger",
                    click: function () {$(this).dialog("close");}
                }],
            open: function(){$("#t_dina_acta_aper > tbody > tr").remove(); $("#nro_cuo_apersonamiento").val(''); $("#nro_cuo_monto").val('')}       
        }).dialog('open');
        
        monto_tot = $("#tabla_expedientes").getCell(id_coa_mtr, 'monto');
        
        $("#nro_cuo_monto").val(formato_numero(monto_tot,2,'.',','));
    }else{
        save_doc(id_coa_mtr,id_tip_doc);
    }
}
function add_cuo_acta_aper(){
    nro_cuo=$("#nro_cuo_apersonamiento").val();
//    monto=parseFloat($("#nro_cuo_monto").val());
    if(nro_cuo==""){
        mostraralertasconfoco("Ingrese el Numero de Cuotas...","#nro_cuo_apersonamiento"); 
        return false;
    }
//    if(isNaN(monto)){
//        mostraralertasconfoco("Ingrese Monto...","#nro_cuo_monto"); 
//        return false;
//    }
    for(i=1;i<=nro_cuo;i++){
        $('#t_dina_acta_aper').append(
        "<tr>\n\
            <td style='text-align: center'>" + i + "</td>\n\
            <td><label class='input'><input id='td_din_fch_" + i + "' type='date' class='form-control input-xs'  maxlength='10' placeholder='dd/mm/aaaa'></label></td>\n\
            <td><label class='input'><input id='td_din_monto_" + i + "' type='text' class='form-control input-xs'></label></td>\n\
        </tr>");
    }
}
function save_doc(id_coa_mtr,id_tip_doc,fechas_cuotas,monto_cuotas){
    id_coa_mtr=id_coa_mtr || $('#tabla_expedientes').jqGrid ('getGridParam', 'selrow');
    fechas_cuotas=fechas_cuotas || null; 
    monto_cuotas=monto_cuotas||null;
    $.ajax({
        type:'GET',
        url:'add_documento_exped',
        data:{id_coa_mtr:id_coa_mtr,id_tip_doc:id_tip_doc,fechas_cuotas:fechas_cuotas,monto:monto_cuotas},
        success:function(data){
            if(data.msg=='si'){
                MensajeExito('COACTIVA','Documento Agregado...');
                dialog_close('dlg_select_doc');
                fn_actualizar_grilla('tabla_expedientes','get_exped?id_contrib='+$("#hidden_vw_ges_exped_codigo").val());
                
                setTimeout(function(){
                    $("#tabla_expedientes").setSelection(id_coa_mtr);
                    fn_actualizar_grilla('tabla_doc_coactiva','get_doc_exped?id_coa_mtr='+id_coa_mtr);
                }, 500);
                fn_actualizar_grilla('all_tabla_expedientes','get_all_exped');
            }
        },
        error: function(data){}
    });
}
function ver_doc(id_doc,id_coa_mtr){
    window.open("abrirdocumento/"+id_doc+"/"+id_coa_mtr); 
}

function fecha_resep_notif(id_doc){    
    $.confirm({
        title:'COACTIVA',
        content: 'Agregar Fecha, Recepción de Notificación' +
        "<input type='date' id='dateinputnotif'>",
        buttons: {
            Guardar: function () {
                date =  $("#dateinputnotif").val();
                $.ajax({
                    url:'agreg_fch_recep_notif',
                    type:'GET',
                    data:{id_doc:id_doc,fch_recep:date},
                    success: function(data){
                        ver_docum_exped();
                    }                           
                });                
            },
            Cancelar: function () {}
        }
    });
    get_fecha_actual('dateinputnotif');
}
function editar_doc(id_doc,id_coa_mtr){
    $("#dlg_editor").dialog({
        autoOpen: false, modal: true, width: 800,height:620, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.: EDITAR RESOLUCION :.</h4></div>",
        buttons: [{
             html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {$(this).dialog("close");}
        }]        
    }).dialog('open');
    MensajeDialogLoadAjax('dlg_editor','Cargando...');    
    $('#ck_editor_resol').attr('src','editar_resol?id_doc='+id_doc+'&id_coa_mtr='+id_coa_mtr); 
    setTimeout(function(){ MensajeDialogLoadAjaxFinish('dlg_editor'); }, 1500);
}
function editar_acta(id_doc){
    $("#dlg_editor").dialog({
        autoOpen: false, modal: true, width: 800,height:620, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.: EDITAR DOCUMENTO :.</h4></div>",
        buttons: [{
             html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {$(this).dialog("close");}
        }]        
    }).dialog('open');
    MensajeDialogLoadAjax('dlg_editor','Cargando...'); 
    id_coa_mtr = $('#tabla_expedientes').jqGrid ('getGridParam', 'selrow');
    $('#ck_editor_resol').attr('src','editar_acta_aper?id_doc='+id_doc+'&id_coa_mtr='+id_coa_mtr); 
    setTimeout(function(){ MensajeDialogLoadAjaxFinish('dlg_editor'); }, 1500);
}

function exped_no_trib(){
    if($("#vw_ges_exped_contrib").val()=='' || $("#hidden_vw_ges_exped_codigo").val()=='0'){
        mostraralertasconfoco("Seleccione un Contribuyente para crear un Expediente","#vw_ges_exped_contrib");
        return false;
    }
    $.confirm({
        columnClass: 'col-md-6 col-md-offset-3',
        title:'CREAR NUEVO EXPEDIENTE PARA:',
        content: 'Codigo: '+$("#vw_ges_exped_codigo").val()+'<br>\n\
                  Contribuyente: '+$("#vw_ges_exped_contrib").val(),
        buttons: {
            Aceptar: function () {
                $("#dlg_new_exp_notrib").dialog({
                    autoOpen: false, modal: true, width: 600,height: 'auto', show: {effect: "fade", duration: 300}, resizable: false,
                    title: "<div class='widget-header'><h4>.: NUEVO EXPEDIENTE :.</h4></div>",
                    buttons: [{
                            html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                            "class": "btn btn-primary",
                            click: function () { save_exp_notrib(); }
                        }, {
                            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                            "class": "btn btn-danger",
                            click: function () {$(this).dialog("close");}
                        }],
                    open: function(){limpiar_form_notrib();}
                }).dialog('open');
                trae_valores('exp_notrib_valor');
                $("#exp_notrib_contrib").val($("#vw_ges_exped_contrib").val());
                $("#exp_notrib_id_contrib").val($("#hidden_vw_ges_exped_codigo").val());         
            },
            Cancelar: function () {}
        }
    });
}
function save_exp_notrib(){
    monto=parseFloat($("#exp_notrib_monto").val());
    if(isNaN(monto)){
        mostraralertasconfoco('Ingrese un Monto','#exp_notrib_monto');
        return false;
    }
    if($("#exp_notrib_valor").val()=='' || $("#hiddenexp_notrib_valor").val()==''){
        mostraralertasconfoco("Ingrese Valor...","#exp_notrib_valor");
        return false;
    }
    
    $.ajax({
        url:'new_exp_notrib',
        type:'GET',
        data:{id_contrib:$("#exp_notrib_id_contrib").val(),monto:monto,doc_ini:$("#hiddenexp_notrib_valor").val()},
        success: function(data){
            fn_actualizar_grilla('tabla_expedientes','get_exped?id_contrib='+$("#hidden_vw_ges_exped_codigo").val());
            fn_actualizar_grilla('all_tabla_expedientes','get_all_exped');
            dialog_close('dlg_new_exp_notrib');
        }                           
    });
}
function fn_trae_val(){
    $("#exp_notrib_valor,#hiddenexp_notrib_valor").val('');
    trae_valores('exp_notrib_valor');
}
function new_otro_valor(){
    $("#dlg_new_valor").dialog({
        autoOpen: false, modal: true, width: 550,height: 'auto', show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.: COACTIVA :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-primary",
                click: function () { 
                    $.ajax({
                        url:'add_new_valor',
                        type:'GET',
                        data:{
                            desc_val:($("#dlg_new_val_txt_valor").val()).toUpperCase(),
                            abrev:($("#dlg_new_val_txt_abrev").val()).toUpperCase(),
                            cod_mat:$("#hiddendlg_new_val_txt_mat").val(),
                            desc_mat:$("#dlg_new_val_txt_mat").val()
                        },
                        success: function(data){
                            $("#exp_notrib_valor").val(data.desc_val);
                            $("#hiddenexp_notrib_valor").val(data.id_val);
                            $("#exp_notrib_valor").attr('maxlength', data.desc_val.length);
                            dialog_close('dlg_new_valor');
                        }                           
                    });  
                }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {$(this).dialog("close");}
            }],
        open: function(){$("#dlg_new_val_txt_valor,#dlg_new_val_txt_abrev").val('');}
    }).dialog('open');
    $("#hiddendlg_new_val_txt_mat").val($("#exp_notrib_codmateria").val());
    $("#dlg_new_val_txt_mat").val($("#exp_notrib_codmateria option:selected").text());
    
}
function trae_valores(textbox){
    $.ajax({
        type: 'GET',
        url: 'autocompletar_valores?cod_mat=' + $("#exp_notrib_codmateria").val(),
        success: function (data) {
            var $local_sourcevalores = data;
            $("#" + textbox).autocomplete({
                source: $local_sourcevalores,
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
function devolver_valor(){
    id_coa_mtr = $('#all_tabla_expedientes').jqGrid ('getGridParam', 'selrow');
    id_contrib = $("#all_tabla_expedientes").getCell(id_coa_mtr, 'id_contrib');
    id_val = $("#all_tabla_expedientes").getCell(id_coa_mtr, 'id_val');

    $.confirm({
        title:'COACTIVA',
        content: '* Devolver y Cambiar Estado de Expediente...',
        buttons: {
            Aceptar: function () {
                $.ajax({
                    url:'devolver_valor',
                    type:'GET',
                    data:{id_coa_mtr:id_coa_mtr,id_contrib:id_contrib,id_val:id_val},
                    success: function(data){
                        if(data.msg=='si'){
                            MensajeExito('DEVOLVER DOCUMENTO','Operacion Completada Correctamente...');
                            fn_actualizar_grilla('all_tabla_expedientes','get_all_exped');
                        }
                    }                           
                });                
            },
            Cancelar: function () {}
        }
    });
}
function eliminar_documento(){
    id_doc = $('#tabla_doc_coactiva').jqGrid ('getGridParam', 'selrow');
    $.confirm({
        title:'COACTIVA',
        content: '* Eliminar Documento Seleccionado...',
        buttons: {
            Aceptar: function () {
                $.ajax({
                    url:'eliminar_documento',
                    type:'GET',
                    data:{id_doc:id_doc},
                    success: function(data){
                        if(data.msg=='si'){
                            ver_docum_exped();
                        }
                    }                           
                });                
            },
            Cancelar: function () {}
        }
    });
}
function activar_exped(){
    id_coa_mtr = $('#all_tabla_expedientes').jqGrid ('getGridParam', 'selrow');
    id_contrib = $("#all_tabla_expedientes").getCell(id_coa_mtr, 'id_contrib');
    id_val = $("#all_tabla_expedientes").getCell(id_coa_mtr, 'id_val');
    $.confirm({
        title:'COACTIVA',
        content: '* Activar Expediente...',
        buttons: {
            Aceptar: function () {
                $.ajax({
                    url:'activar_exped',
                    type:'GET',
                    data:{id_coa_mtr:id_coa_mtr,id_contrib:id_contrib,id_val:id_val},
                    success: function(data){
                        if(data.msg=='si'){
                            MensajeExito('Cambiar Estado de Expediente','Operacion Completada Correctamente...');
                            fn_actualizar_grilla('all_tabla_expedientes','get_all_exped');
                        }
                    }                           
                });                
            },
            Cancelar: function () {}
        }
    });
}
function bus_contrib(){
    if($("#vw_ges_exped_contrib").val()==""){
        mostraralertasconfoco("Ingrese un Contribuyente para Buscar","#vw_ges_exped_contrib"); 
        return false;
    }
    if($("#vw_ges_exped_contrib").val().length<4){
        mostraralertasconfoco("Ingresar al menos 4 caracteres de busqueda","#vw_ges_exped_contrib"); 
        return false;
    }

    fn_actualizar_grilla('table_contrib','obtiene_cotriname?dat='+$("#vw_ges_exped_contrib").val());
    jQuery('#table_contrib').jqGrid('bindKeys', {"onEnter":function( rowid ){fn_bus_contrib_select(rowid);} } ); 
    $("#dlg_bus_contr").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  Busqueda de Contribuyente :.</h4></div>"       
        }).dialog('open');
}

function fn_bus_contrib_select(per){    
    $("#hidden_vw_ges_exped_codigo").val(per);
   
    $("#vw_ges_exped_codigo").val($('#table_contrib').jqGrid('getCell',per,'id_per'));    
    $("#vw_ges_exped_contrib").val($('#table_contrib').jqGrid('getCell',per,'contribuyente'));
    tam=($('#table_contrib').jqGrid('getCell',per,'contribuyente')).length;

    
    $("#vw_ges_exped_contrib").attr('maxlength',tam);

    fn_actualizar_grilla('tabla_expedientes','get_exped?id_contrib='+$("#hidden_vw_ges_exped_codigo").val());
    $('#tabla_doc_coactiva').jqGrid('clearGridData');
    $("#dlg_bus_contr").dialog("close");    
}
function ver_docum_exped(id_coa_mtr){
    id_coa_mtr = id_coa_mtr || $('#tabla_expedientes').jqGrid ('getGridParam', 'selrow');
    fn_actualizar_grilla('tabla_doc_coactiva','get_doc_exped?id_coa_mtr='+id_coa_mtr);
}
function bus_contrib_expediente(){
    fn_actualizar_grilla('all_tabla_expedientes','get_all_exped?contrib='+($("#vw_coa_bus_contrib_exp").val()).toUpperCase());
}
function limpiar_form_notrib(){
    $("#exp_notrib_monto,#exp_notrib_contrib,#exp_notrib_id_contrib,#exp_notrib_valor,#hiddenexp_notrib_valor").val('');
}

/*******************************************************************************************************************************************/
function editar_notificacion(id_doc,id_coa_mtr,texto){
    $("#exp_notif_txt").val('');
    $("#dlg_up_doc_adjuntos").dialog({
        autoOpen: false, modal: true, width: 600,height: 'auto', show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.: CONSTANCIA DE NOTIFICACION :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-primary",
                click: function () { 
                    $.ajax({
                        url:'notif_up_texto',
                        type:'GET',
                        data:{id_doc:id_doc,texto:($("#exp_notif_txt").val()).toUpperCase()},
                        success: function(data){
                            fn_actualizar_grilla('tabla_doc_coactiva','get_doc_exped?id_coa_mtr='+id_coa_mtr);
                            dialog_close('dlg_up_doc_adjuntos');
                        }                           
                    });
                }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {$(this).dialog("close");}
            }],
        open: function(){ $("#exp_notif_txt").val(texto); },
        close:function(){ $("#exp_notif_txt").val(''); }
    }).dialog('open');
    
}

