
function dlg_select_new_doc(){
    
    
    id_coa_mtr = $('#tabla_expedientes').jqGrid ('getGridParam', 'selrow');
    estado_proced = $("#tabla_expedientes").getCell(id_coa_mtr, 'estado');
    
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
    adjuntar = $("#adjuntar_const").val();
    
    if(!id_tip_doc){
        mostraralertas('Seleccione un Documento...');
        return false;
    }
    if(id_tip_doc==9){
        $("#vw_coa_acta_apersonamiento").dialog({
            autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,        
            title: "<div class='widget-header'><h4>.: COACTIVA :.</h4></div>",
            buttons: [{
                    html: "<i class='fa fa-fax'></i>&nbsp; Agregar Acta Apersonamiento",
                    "class": "btn btn-primary",
                    click: function () { 
                        graba_apersonamiento();
                    }
                }, {
                    html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                    "class": "btn btn-danger",
                    click: function () {$(this).dialog("close");}
                }],
            open: function(){$("#t_dina_acta_aper > tbody > tr").remove(); $("#nro_cuo_apersonamiento").val(''); $("#nro_cuo_monto").val('')}       
        }).dialog('open');
        
        monto_tot = $("#tabla_expedientes").getCell(id_coa_mtr, 'total_deuda');
        
        $("#nro_cuo_monto").val(formato_numero(monto_tot,2,'.',','));
    }else{
        save_doc(adjuntar,id_coa_mtr,id_tip_doc);
    }
}
function add_cuo_acta_aper(){
    $("#t_dina_acta_aper > tbody > tr").remove();
    nro_cuo=$("#nro_cuo_apersonamiento").val();

    if(nro_cuo==""){
        mostraralertasconfoco("Ingrese el Numero de Cuotas...","#nro_cuo_apersonamiento"); 
        return false;
    }

    for(i=1;i<=nro_cuo;i++){
        $('#t_dina_acta_aper').append(
        "<tr>\n\
            <td style='text-align: center'>" + i + "</td>\n\
            <td><label class='input'><input id='td_din_fch_" + i + "' type='date' class='form-control input-xs'  maxlength='10' placeholder='dd/mm/aaaa'></label></td>\n\
            <td><label class='input'><input id='td_din_porcentaje_" + i + "' type='text' class='form-control input-xs' onkeyup='validamonto_segun_porcent("+i+")' maxlength='3'></label></td>\n\
            <td><label class='input'><input id='td_din_monto_" + i + "' type='text' class='form-control input-xs' disabled='disabled'></label></td>\n\
        </tr>");
    }
}
function validamonto_segun_porcent(num)
{
    monto_tot = $("#nro_cuo_monto").val().replace(',', '');
    porcentaje=$("#td_din_porcentaje_"+num).val();
    $("#td_din_monto_"+num).val(formato_numero(parseFloat(monto_tot)*(parseFloat(porcentaje)/100),2,".",","));
}
function graba_apersonamiento()
{
    MensajeDialogLoadAjax('vw_coa_acta_apersonamiento','Guardando...');
    var rowCount =  $("#t_dina_acta_aper tr").length;
    if(rowCount-1==0){
        mostraralertas('Tabla de Cuotas esta Vacia...'); 
        MensajeDialogLoadAjaxFinish('vw_coa_acta_apersonamiento');
        MensajeDialogLoadAjaxFinish('vw_coa_acta_apersonamiento');
        return false;
    }
                        
    var fechas = new Array();                        
    for(i=1;i<=rowCount-1;i++){
        if($("#td_din_fch_"+i).val()=="")
        {
            mostraralertasconfoco('Falta Ingresar Fecha cuota '+i,"#td_din_fch_"+i);
            MensajeDialogLoadAjaxFinish('vw_coa_acta_apersonamiento');
            return false;
        }
        fechas.push($("#td_din_fch_"+i).val());
    }
    
    var porcentajes = new Array();                        
    for(i=1;i<=rowCount-1;i++){
        if($("#td_din_porcentaje_"+i).val()=="")
        {
            mostraralertasconfoco('Falta Ingresar Monto cuota '+i,"#td_din_fch_"+i);
            MensajeDialogLoadAjaxFinish('vw_coa_acta_apersonamiento');
            return false;
        }
        porcentajes.push($("#td_din_porcentaje_"+i).val());
    }
    var montos = new Array();                        
    for(i=1;i<=rowCount-1;i++){
        montos.push($("#td_din_monto_"+i).val());
    }
//                        cant=fechas.length;
    fechas_cuotas = fechas.join('*');
    porcentaje_cuotas = porcentajes.join('*');
    monto_cuotas = montos.join('*');
    save_doc(adjuntar,id_coa_mtr,id_tip_doc,fechas_cuotas,monto_cuotas,porcentaje_cuotas);
    setTimeout(function(){ 
        MensajeDialogLoadAjaxFinish('vw_coa_acta_apersonamiento');
        dialog_close('vw_coa_acta_apersonamiento');
        dialog_close('dlg_select_doc');
    }, 1000);
}


function save_doc(adjuntar,id_coa_mtr,id_tip_doc,fechas_cuotas,monto_cuotas,porcentaje_cuotas){
    id_coa_mtr=id_coa_mtr || $('#tabla_expedientes').jqGrid ('getGridParam', 'selrow');
    fechas_cuotas=fechas_cuotas || null; 
    monto_cuotas=monto_cuotas||null;
    porcentaje_cuotas=porcentaje_cuotas||null;
    
    $.ajax({        
        type:'GET',
        url:'add_documento_exped',
        data:{id_coa_mtr:id_coa_mtr,id_tip_doc:id_tip_doc,fechas_cuotas:fechas_cuotas,monto:monto_cuotas,adjuntar:adjuntar,porcentaje:porcentaje_cuotas},
        success:function(data){
            if(data.msg=='si'){
                MensajeExito('COACTIVA','Documento Agregado...');
                dialog_close('dlg_select_doc');
                fn_actualizar_grilla('tabla_doc_coactiva','get_doc_exped?id_coa_mtr='+id_coa_mtr);
            }
        },
        error: function(data){}
    });
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
        data:{id_contrib:$("#exp_notrib_id_contrib").val(),monto:monto.toFixed(3),doc_ini:$("#hiddenexp_notrib_valor").val()},
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




function eliminar_documento(){
    id_doc = $('#tabla_doc_coactiva').jqGrid ('getGridParam', 'selrow');
    
    id_coa_mtr = $('#tabla_expedientes').jqGrid ('getGridParam', 'selrow');
    estado_proced = $("#tabla_expedientes").getCell(id_coa_mtr, 'estado');
    if(estado_proced==0){ 
        mostraralertas('* Expediente Archivado, <br>* No puede Eliminar los Documentos...');
        return false; }
    if(estado_proced==3){ 
        mostraralertas('* Expediente Devuelto, <br>* No puede Eliminar los Documentos...');
        return false; }
    
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
    materia = $("#ges_exped_mat").val();
    fn_actualizar_grilla('all_tabla_expedientes','get_all_exped?contrib='+($("#vw_coa_bus_contrib_exp").val()).toUpperCase()+'&materia='+materia);
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

function habilitar_pago(){
    id_coa_mtr = $('#all_tabla_expedientes').jqGrid ('getGridParam', 'selrow');
    id_contrib = $("#all_tabla_expedientes").getCell(id_coa_mtr, 'id_contrib');
    estado = $("#all_tabla_expedientes").getCell(id_coa_mtr, 'estado');
    
    if(estado=='DEVUELTO'){
        mostraralertas('* Expediente Devuelto.<br>* La Cuenta Cte. Esta Habilitada para realizar pagos.');
        return false;
    }
    
    $("#dlg_enable_pago").dialog({
        autoOpen: false, modal: true, width: 600,height: 'auto', show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.: HABILITAR / DESHABILITAR PAGO COACTIVO :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Habilitar y Guardar",
                "class": "btn btn-primary",
                click: function () { update_pago_trim(); }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir","class": "btn btn-danger",
                click: function () {$(this).dialog("close");}
            }],
        open: function(){fn_actualizar_grilla('t_cta_cte','get_ctacte?id_contrib='+id_contrib); },
        close:function(){ }
    }).dialog('open');
    $("#dlg_enable_pago_idcontrib").val($("#all_tabla_expedientes").getCell(id_coa_mtr, 'id_contrib'));
    $("#dlg_enable_pago_contrib").val($("#all_tabla_expedientes").getCell(id_coa_mtr, 'contribuyente'));
}
trim_checks='';
function trim_select(){
    var Seleccionados = new Array();
    $('input[type=checkbox]:checked').each(function() {
        Seleccionados.push($(this).val());
    });
    cant=Seleccionados.length;    
    trim_checks = Seleccionados.join('*');    
}
function update_pago_trim(){    
    id_coa_mtr = $('#all_tabla_expedientes').jqGrid ('getGridParam', 'selrow');
    id_contrib = $("#all_tabla_expedientes").getCell(id_coa_mtr, 'id_contrib');
    $.ajax({
        url:'update_pago_trim',
        type:'GET',
        data:{id_coa_mtr:id_coa_mtr,id_contrib:id_contrib,trim_checks:trim_checks},
        success: function(data){
            if(data.msg=='si'){
                MensajeExito('Operacion Completada','Ya Puede pagar Impuesto en Ventanilla...');
                dialog_close('dlg_enable_pago');
            }
        }                           
    });
}

function adjuntar_const_chk(source){
    if($(source).is(':checked')){
        $(source).val(1);
    } else {
        $(source).val(0);      
    }
}

function desactivar_adjuntos(source){
    if($(source).is(':checked')){
        $('#adjuntar_const').val(0);
        $('#adjuntar_const').attr('checked',false);        
    }
}



/////////////////////////////////////////
/////////////////////////////////////7//
/////////////////////////////////////////
/////////////////////////////////////7//
/////////////////////////////////////////
/////////////////////////////////////7//

function devolver_valor(id_coa_mtr){
    

    $.confirm({
        title:'COACTIVA',
        content: 'Seguro que desea Devolver y Cambiar Estado de Expediente...',
        buttons: {
            Aceptar: function () {
                MensajeDialogLoadAjax('all_tabla_expedientes','Cargando...');    
                $.ajax({
                    url:'devolver_valor',
                    type:'GET',
                    data:{id_coa_mtr:id_coa_mtr},
                    success: function(data){
                        if(data.msg=='si'){
                            MensajeExito('DEVOLVER DOCUMENTO','Operacion Completada Correctamente...');
                            select_materia();
                            MensajeDialogLoadAjaxFinish('all_tabla_expedientes');

                        }
                    }                           
                });                
            },
            Cancelar: function () {}
        }
    });
}
function save_aceptar(id_coa_mtr){
    
    if($("#inp_costas_exp").val()=="")
    {
        mostraralertasconfoco("Ingresar Valor de Costas","#inp_costas_exp");
        return false;
    }
    $.confirm({
        title:'COACTIVA',
        content: 'Seguro que desea Aceptar e Iniciar Expediente...',
        buttons: {
            Aceptar: function () {
                MensajeDialogLoadAjax('dlg_aceptar_expediente','Cargando...'); 
                var contenido = CKEDITOR.instances['ckeditor'].getData();
                $.ajax({
                    url:'aceptar_valor',
                    type:'GET',
                    data:{id_coa_mtr:id_coa_mtr,contenido:contenido,costas:$("#inp_costas_exp").val()},
                    success: function(data){
                            MensajeExito('ACEPTAR DOCUMENTO','Operacion Completada Correctamente...');
                            select_materia();
                            MensajeDialogLoadAjaxFinish('dlg_aceptar_expediente');
                            $("#dlg_aceptar_expediente").dialog("close");
                    }                           
                });                
            },
            Cancelar: function () {}
        }
    });
}
iniciar=0;
function aceptar_valor(id_coa_mtr){
    
    if(iniciar==0)
    {
        iniciar=1;
        CKEDITOR.replace('ckeditor');
    }
//    CKEDITOR.instances['ckeditor'].setData('Cerro Colorado,<br>\n\
//    VISTOS: El Escrito de fecha 24 de julio del 2017 de la Gerencia de Administración Tributaria y Rentas de la Municipalidad Distrital de Cerro Colorado, mediante el cual dicha entidad remite a este Despacho el Expediente del contribuyente, Sra. TACO APAZA GRACIELA (en adelante el Obligado), que contiene la Resolución de  Determinacion N° 036-2017-SGFT-MDCC/Arb   de fecha 21 de abril del 2017, con su respectiva Constancia de notificación adjunta a la misma, la Constancia de cosa decidida administrativa, y los actuados que dieron origen a los mismos, respecto de la deuda tributaria de Arbitrios Municipales correspondiente a los periodos 2013, 2014, 2015, 2016; y,<br>\n\
//    CONSIDERANDO:<br>\n\
//    Primero.- Premisa Normativa.- El dispositivo normativo contenido en el Art. 25° de la Ley de Procedimiento de Ejecución Coactiva Ley N° 26979, su Reglamento y sus modificatorias establece: Exigibilidad de la Obligación.- 25.1.a) Se considera Obligación exigible coactivamente, a la establecida mediante Resolución de Determinación o de Multa, emitida por la Entidad conforme a ley, debidamente notificada y no reclamada en el plazo de Ley. 25.4) También serán exigibles en el mismo procedimiento las costas y gastos en que la Entidad hubiera incurrido en la cobranza coactiva de las deudas tributarias. De la misma forma se tiene establecido en el Art. 29° de la Ley de Procedimiento de Ejecución Coactiva, Ley N° 26979, su Reglamento y sus modificatorias, que dispone: Inicio del Procedimiento.- Art. 29°.- El procedimiento es iniciado por el Ejecutor Coactivo mediante notificación al obligado de la resolución de Ejecución Coactiva, la que contiene un mandato de cumplimiento de la obligación exigible coactivamente conforme al Artículo 25° de la presente Ley; y dentro del plazo de siete (7) días hábiles de notificado, bajo apercibimiento de dictarse alguna medida cautelar.<br>\n\
//    Segundo.- Premisa Fáctica.- La Entidad administrativa, en este caso, la Gerencia de Administración Tributaria y Rentas de la Municipalidad Distrital de Cerro Colorado (en adelante la Entidad), mediante Resolución de Determinacion N° 036-2017-SGFT-MDCC/Arb ha determinado la suma de S/. 236.84 (DOS CIENTOS TREINTA Y SEIS  CON 84/100 SOLES) a favor de la Municipalidad Distrital de Cerro Colorado por concepto de Impuesto Predial, correspondiente a los periodos detallados en la respectiva Resolución.<br>\n\
//    Tercero.- De autos se tiene que, la Entidad ha practicado la notificación de la Resolución de Determinacion N° 036-2017-SGFT-MDCC/Arb, la misma que ha sido debidamente notificada al Obligado, tal como se puede verificar en la Constancia de notificación de fecha   de 21 de abril del 2017, sin que el Obligado haya realizado acto de impugnación alguno en la vía administrativa dentro del plazo de Ley, por lo que la Gerencia de Administración Tributaria ha expedido la Constancia de cosa decidida administrativa en la cual indica que el acto administrativo no ha sido objeto de impugnación alguno dentro del plazo de Ley, de tal forma ha quedado consentido y en consecuencia ha adquirido la calidad de Cosa Decidida Administrativamente.<br>\n\
//    Cuarto.- Por tanto, estando a los considerandos y los antecedentes adjuntos, se advierte que la obligación es exigible coactivamente, la deuda tributaria se ha establecido en acto administrativo emitido por la Entidad, esto es, mediante la Resolución de Determinacion N° 036-2017-SGFT-MDCC/Arb, en la cual se ha identificado plenamente al Obligado (deudor tributario), así como la cuantía del Tributo y los Intereses, el monto total de la deuda y el periodo a que corresponde, la misma que ha sido debidamente notificada al Obligado en su oportunidad.<br>\n\
//    Quinto.- En consecuencia, existiendo obligación exigible coactivamente contenido en una Resolución de Determinación debidamente notificada, la misma que ha sido ejecutoriada y/o consentida, y solicitado a este Despacho por la Entidad en contra del Obligado para su cumplimiento, es viable dar inicio al Procedimiento de Ejecución Coactiva en contra del Obligado, otorgándole el plazo de siete días hábiles de notificada la presente, a efectos de que cumpla con pagar la deuda tributaria sobre Impuesto Predial a favor de la Entidad; y estando a lo establecido en el Art. 9° y 192° de la Ley de Procedimiento Administrativo General Ley N° 27444, y a lo establecido en el dispositivo normativo contenido en el Art. 25° y 29° de la Ley de Procedimiento de Ejecución Coactiva Ley N° 26979, su reglamento y sus modificatorias, y dentro de las facultades concedidas por la Ley citada;<br>\n\
//    SE RESUELVE:   <br>\n\
//    PRIMERO.- ADMITIR A TRÁMITE la solicitud presentada por la Entidad, en tal sentido, SE DISPONE EL INICIO del Procedimiento de Ejecución Coactiva en contra del Obligado, Sra. TACO APAZA, GRACIELA, a quién se debe notificar con copia del acto administrativo generador de la Obligación Tributaria, su correspondiente Constancia de notificación, así como la Constancia de cosa decidida administrativa. <br>\n\
//    SEGUNDO.- SE DISPONE REQUERIR al Obligado, Sra.  TACO APAZA GRACIELA, para que en el PLAZO DE SIETE (07) DÍAS HÁBILES de notificado, CUMPLA CON PAGAR A FAVOR DE LA MUNICIPALIDAD DISTRITAL DE CERRO COLORADO, LA SUMA DE S/.236.84 (DOS CIENTOS TREINTA Y SEIS  CON 84/100 SOLES) por concepto de Arbitrios Municipales  correspondiente a los años 2013, 2014, 2015, 2016, del predio ubicado en La Libertad Mariano Melgar 101, Distrito de Cerro Colorado, conforme se expresa en la Resolución de Determinacion N° 036-2017-SGFT-MDCC/Arb, más el pago de los intereses actualizados a la fecha de cancelación de la obligación, así como las costas y gastos ocasionados a la entidad, BAJO APERCIBIMIENTO DE DICTARSE MEDIDA CAUTELAR Y/O EMBARGOS ESTABLECIDAS EN LA LEY DE PROCEDIMIENTO DE EJECUCIÓN COACTIVA, SU REGLAMENTO Y SUS MODIFICATORIAS. <br>\n\
//    Se adjunta: Copia de la Resolución de Determinacion N° 036-2017-SGFT-MDCC/Arb, la Constancia de notificación, y la Constancia de cosa decidida administrativa.<br>\n\
//    Tómese Razón y Hágase Saber');
    CKEDITOR.instances['ckeditor'].setData('CONSIDERANDO:<br>');
    $("#dlg_aceptar_expediente").dialog({
        autoOpen: false, modal: true, width: 1000,height:650, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.: ACEPTAR EXPEDIENTE :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-primary",
                click: function () { save_aceptar(id_coa_mtr); }
            },{
             html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {$(this).dialog("close");}
        }]        
    }).dialog('open');
    
}


function select_materia(){
    materia = $("#ges_exped_mat").val();
    fn_actualizar_grilla('all_tabla_expedientes','get_all_exped?contrib='+($("#vw_coa_bus_contrib_exp").val()).toUpperCase()+'&materia='+materia+'&anio='+$("#selanio_tra").val());
    if(materia==0){
       $("#btn_hab_pago_coa").attr('disabled',true); 
    }else{$("#btn_hab_pago_coa").attr('disabled',false); }
}
function lista_aceptados(){
    $('#tabla_expedientes').jqGrid('clearGridData');
    fn_actualizar_grilla('tabla_expedientes','get_exped?anio='+$("#selanio_tra").val());
}

function documentos(id)
{
    $('#tabla_doc_coactiva').jqGrid('clearGridData');

    $("#dlg_vista_documentos").dialog({
        autoOpen: false, modal: true, width: 1000,height:650, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.: GESTIONAR EXPEDIENTE :.</h4></div>",
        buttons: [{
             html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {$(this).dialog("close");}
        }]        
    }).dialog('open');
    fn_actualizar_grilla('tabla_doc_coactiva','get_doc_exped?id_coa_mtr='+id);

}
function ver_doc(id_doc,id_coa_mtr){
    window.open("abrirdocumento/"+id_doc+"/"+id_coa_mtr); 
}








