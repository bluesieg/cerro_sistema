
function open_dialog_new_edit_Contribuyente() {   
    $("#dialog_new_edit_Contribuyentes").dialog({
        autoOpen: false, modal: true, width: 800, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: CONTRIBUYENTE :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-success bg-color-green",
                click: function () { new_contrib(); }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () { $(this).dialog("close"); }
            }],
        close: function (event, ui) { limpiar_dlg_contrib(); },
        open: function(){ limpiar_dlg_contrib(); }        
    }).dialog('open');
    MensajeDialogLoadAjax('dialog_new_edit_Contribuyentes','Cargando');
    $("#cb_tip_doc_2").val('02');
    $("#cb_tip_doc_1").val('02');
    $("#contrib_dpto").val('04');
    llenar_combo_prov('contrib_prov');
    llenar_combo_dist('contrib_dist');
    $("#contrib_dist").val('040104');
    MensajeDialogLoadAjaxFinish('dialog_new_edit_Contribuyentes');
}
function selec_dist(val){
    if(val=='040104'){
        autocompletar_av_jr_call('txt_av_jr_calle_psje');
    }else{
        $("#txt_av_jr_calle_psje").autocomplete({source: []});
        $("#hiddentxt_av_jr_calle_psje").val('0');
    }
}
function buscar_contrib(){
    fn_actualizar_grilla('table_Contribuyentes','grid_contribuyentes?buscar='+($("#vw_contrib_buscar").val()).toUpperCase());
}
function active_conyugue(tipo_contrib){
    if(tipo_contrib=='3'){
        $("#contrib_nro_doc_conv,#contrib_conviviente_pat,#contrib_conviviente_mat,#contrib_conviviente_nom").attr('disabled',false);
    }else{
        $("#contrib_nro_doc_conv,#contrib_conviviente_pat,#contrib_conviviente_mat,#contrib_conviviente_nom").attr('disabled',true);
    }
}
global_contrib_conviv=0;
function fn_consultar_persona(num){
    global_contrib_conviv=num;
    if(global_contrib_conviv==1){
        nro_doc=$("#txt_nro_doc").val();        
    }else{
        nro_doc=$("#contrib_nro_doc_conv").val();
    }
    
    if(nro_doc==''){mostraralertas('Ingrese Numero de Documento');return false;}
    tip_doc=$("#cb_tip_doc_1").val();
    if(tip_doc=='02' && nro_doc.length<='7'){
        mostraralertas('El DNI ingresado tiene menos de 8 digitos...');return false;
    }
    if(tip_doc=='00' && nro_doc.length<='10'){
        mostraralertas('El RUC ingresado tiene menos de 11 digitos...');return false;
    }    
    
    $.ajax({        
        url: 'consultar_persona?nro_doc='+nro_doc,
        type: 'GET',        
        success: function (data) {
            if(data){
                if(global_contrib_conviv==1){
                    $("#vw_contrib_contribuyente").val(data.contrib);
                    $("#vw_contrib_id_pers").val(data.id_pers);
                }else{
                    $("#contrib_conviviente_pat").val(data.ape_pat);
                    $("#contrib_conviviente_mat").val(data.ape_mat);
                    $("#contrib_conviviente_nom").val(data.nombres);
                    $("#vw_contrib_id_conv").val(data.id_pers);
                }                
            }else{
                dlg_new_persona(nro_doc);
            }
        },
        error: function (data) { MensajeAlerta('* Error de Red...<br>* Contactese con el Administrador...'); }
    });
}
function crea_dlg_contrib()
{
    $("#dialog_new_edit_Contribuyentes").dialog({
        autoOpen: false, modal: true, width: 800, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: CONTRIBUYENTE :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar Modificaciones",
                "class": "btn btn-success bg-color-green",
                click: function () { update_contrib(); }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () { $(this).dialog("close"); }
            }],
        close: function (event, ui) { limpiar_dlg_contrib(); },
        open: function(){ limpiar_dlg_contrib(); MensajeDialogLoadAjax('dialog_new_edit_Contribuyentes','Cargando');}
    }).dialog('open');
}

function modificar_contrib(){    
    id_contrib=$('#table_Contribuyentes').jqGrid ('getGridParam', 'selrow');
    crea_dlg_contrib();
    llenar_combo_prov('contrib_prov',$("#table_Contribuyentes").getCell(id_contrib, 'id_dpto'));
    llenar_combo_dist('contrib_dist',$("#table_Contribuyentes").getCell(id_contrib, 'id_prov'));    
    
    $("#txt_nro_doc").val($("#table_Contribuyentes").getCell(id_contrib, 'nro_doc'));
    $("#vw_contrib_sel_tip_contrib").val($("#table_Contribuyentes").getCell(id_contrib, 'tipo_persona'));
    $("#contrib_id_cond_exonerac").val($("#table_Contribuyentes").getCell(id_contrib, 'id_cond_exonerac'));
    $("#vw_contrib_id_pers").val($("#table_Contribuyentes").getCell(id_contrib, 'id_pers'));
    $("#vw_contrib_contribuyente").val($("#table_Contribuyentes").getCell(id_contrib, 'contribuyente'));
    $("#contrib_est_civil").val($("#table_Contribuyentes").getCell(id_contrib, 'est_civil'));
    $("#contrib_tlfno_fijo").val($("#table_Contribuyentes").getCell(id_contrib, 'tlfno_fijo'));
    $("#contrib_tlfono_celular").val($("#table_Contribuyentes").getCell(id_contrib, 'tlfono_celular'));
    $("#contrib_email").val($("#table_Contribuyentes").getCell(id_contrib, 'email'));    
    setTimeout(function(){
        $("#cb_tip_doc_1").val($("#table_Contribuyentes").getCell(id_contrib, 'tip_doc'));
        $("#contrib_dpto").val($("#table_Contribuyentes").getCell(id_contrib, 'id_dpto'));
        $("#contrib_prov").val($("#table_Contribuyentes").getCell(id_contrib, 'id_prov'));
        $("#contrib_dist").val($("#table_Contribuyentes").getCell(id_contrib, 'id_dist'));
        MensajeDialogLoadAjaxFinish('dialog_new_edit_Contribuyentes');        
    }, 1500);
    $("#hiddentxt_av_jr_calle_psje").val($("#table_Contribuyentes").getCell(id_contrib, 'id_via'));
    $("#txt_av_jr_calle_psje").val($("#table_Contribuyentes").getCell(id_contrib, 'nom_via'));
    $("#contrib_nro_mun").val($("#table_Contribuyentes").getCell(id_contrib, 'nro_mun'));
    $("#contrib_dpto_depa").val($("#table_Contribuyentes").getCell(id_contrib, 'dpto'));
    $("#contrib_manz").val($("#table_Contribuyentes").getCell(id_contrib, 'manz'));
    $("#contrib_lote").val($("#table_Contribuyentes").getCell(id_contrib, 'lote'));
    $("#contrib_dom_fiscal").val($("#table_Contribuyentes").getCell(id_contrib, 'ref_dom_fis'));
    $("#cb_tip_doc_2").val('02');
    $("#contrib_nro_doc_conv").val($("#table_Contribuyentes").getCell(id_contrib, 'nro_doc_conv'));
    $("#vw_contrib_id_conv").val($("#table_Contribuyentes").getCell(id_contrib, 'id_conv'));
    $("#contrib_conviviente_pat").val($("#table_Contribuyentes").getCell(id_contrib, 'conv_pat')); 
    $("#contrib_conviviente_mat").val($("#table_Contribuyentes").getCell(id_contrib, 'conv_mat')); 
    $("#contrib_conviviente_nom").val($("#table_Contribuyentes").getCell(id_contrib, 'conv_nombres'));
    $("#contrib_num_expediente").val($("#table_Contribuyentes").getCell(id_contrib, 'num_expediente')); 
   
    if($("#table_Contribuyentes").getCell(id_contrib, 'tipo_persona')=='3'){
        $("#contrib_nro_doc_conv,#contrib_conviviente_pat,#contrib_conviviente_mat,#contrib_conviviente_nom").attr('disabled',false);
    }else{
        $("#contrib_nro_doc_conv,#contrib_conviviente_pat,#contrib_conviviente_mat,#contrib_conviviente_nom").attr('disabled',true);
    }
}

function eliminar_contrib()
{
    Id=$('#table_Contribuyentes').jqGrid ('getGridParam', 'selrow');
    if(Id)
    {
    MensajeDialogLoadAjax('content_2', '.:: Guardando ...');
    $.ajax({url: 'validar_del_contrib',
    type: 'GET',
    data:{id:Id},
    success: function(r) 
    {
        MensajeDialogLoadAjaxFinish('content_2');
        if(r==0)
        {
            $.SmartMessageBox({
            title : "<i class='glyphicon glyphicon-alert' style='color: yellow; margin-right: 20px; font-size: 1.5em;'></i> Confirmación Final!",
            content : 'Esta Por Eliminar Contribuyente, Desea Continuar?',
            buttons : '[Cancelar][Aceptar]'
            }, function(ButtonPressed) {
                    if (ButtonPressed === "Aceptar") {

                            dlg_delete_contrib();
                    }
                    if (ButtonPressed === "Cancelar") {
                            $.smallBox({
                                    title : "No se Guardo",
                                    content : "<i class='fa fa-clock-o'></i> <i>Puede Corregir...</i>",
                                    color : "#C46A69",
                                    iconSmall : "fa fa-times fa-2x fadeInRight animated",
                                    timeout : 3000
                            });
                    }

            });
        }
        else
        {
            mostraralertasconfoco("No se Puede Eliminar COntribuyente, tiene los siguientes Predios<br><div style='height=200px;overflow-y: scroll;height: 100px !important;'>"+r+"</div>Elimine los predios para continuar","#content_2")
        }
        
    },
    error: function(data) {
        mostraralertas("hubo un error, Comunicar al Administrador");
        MensajeDialogLoadAjaxFinish('dlg_reg_dj');
        console.log('error');
        console.log(data);
    }
    }); 
    }
    else
    {
        mostraralertasconfoco("No Hay Predio Seleccionado","#selsec");
    }
    
}

    function dlg_delete_contrib()
    {
        if($("#per_del").val()==0)
        {
            sin_permiso();
            return false;
        }
        Id=$('#table_Contribuyentes').jqGrid ('getGridParam', 'selrow');
        MensajeDialogLoadAjax('content_2', '.:: Eliminando ...');
        $.ajax({
            url: 'contribuyentes/destroy',
            type: 'post',
            data: {_method: 'delete', _token:$("#btn_vw_contribuyentes_eliminar").data('token'),id:Id},
            success: function(r) 
            {
                fn_actualizar_grilla('table_Contribuyentes','grid_contribuyentes');
                MensajeAlerta("Se Eliminó Correctamente","Su Registro Fue eliminado Correctamente...",4000)
                MensajeDialogLoadAjaxFinish('content_2');
            },
            error: function(data) {
                mostraralertas("hubo un error, Comunicar al Administrador");
                MensajeDialogLoadAjaxFinish('table_predios');
                console.log('error');
                console.log(data);
            }
        });
    }
function update_contrib(){
    id_contrib=$('#table_Contribuyentes').jqGrid ('getGridParam', 'selrow');
    $.ajax({
        url: 'contribuyentes/'+id_contrib+'/edit',
        type: 'GET',
        data:{   
            tipo_doc:$("#cb_tip_doc_1").val(),
            tipo_persona:$("#vw_contrib_sel_tip_contrib").val(),
            nro_doc:$("#txt_nro_doc").val(),
            tlfno_fijo:$("#contrib_tlfno_fijo").val() || '0', 
            tlfono_celular:$("#contrib_tlfono_celular").val() || '0',
            email:$("#contrib_email").val() || '@',
            est_civil:$("#contrib_est_civil").val() || '0',            
            id_dpto:$("#contrib_dpto").val(),
            id_prov:$("#contrib_prov").val(), 
            id_dist:$("#contrib_dist").val(),            
            nro_mun:$("#contrib_nro_mun").val() || '0',
            dpto:$("#contrib_dpto_depa").val() || '0',
            manz:$("#contrib_manz").val() || '0',
            lote:$("#contrib_lote").val() || '0',
            id_cond_exonerac:$("#contrib_id_cond_exonerac").val() || '1', 
            id_via:$("#hiddentxt_av_jr_calle_psje").val() || '0',             
            id_pers:$("#vw_contrib_id_pers").val() || '0', 
            id_conv:$("#vw_contrib_id_conv").val() || '0',
            ref_dom_fis:$("#contrib_dom_fiscal").val() || '-',
            nom_via_2:($("#txt_av_jr_calle_psje").val()).toUpperCase() || '-',
            pat_conv:$("#contrib_conviviente_pat").val(),
            mat_conv:$("#contrib_conviviente_mat").val(),
            nom_conv:$("#contrib_conviviente_nom").val(),
            nro_conv:$("#contrib_nro_doc_conv").val(),
            numero_expediente:$("#contrib_num_expediente").val()
        },
        success: function (data) {
            if (data.msg === 'si'){
                fn_actualizar_grilla('table_Contribuyentes','grid_contribuyentes');
                mostraralertasconfoco('* EL NUMERO DE EXPEDIENTE, ESTA DUPLICADO...');
            }else{
                MensajeExito('Contribuyente','Se ha Modificado el Contribuyente...');
                fn_actualizar_grilla('table_Contribuyentes','grid_contribuyentes');
                dialog_close('dialog_new_edit_Contribuyentes');
                MensajeDialogLoadAjaxFinish('dialog_new_edit_Contribuyentes', '.:: CARGANDO ...');
            }
        },
        error: function (data) {
            MensajeAlerta('* Error de Red...<br>* Contactese con el Administrador...');
        }
    });
}

function dlg_new_persona(nro_doc){
    $("#dialog_Personas").dialog({
        autoOpen: false, modal: true, width: 750, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: PERSONAS :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-success bg-color-green",
                click: function () { new_persona(); }                
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () { $(this).dialog("close"); }
            }],
        close: function (event, ui) { limpiar_personas();},
        open: function (){ limpiar_personas(); }
    }).dialog('open');
    $("#cb_tip_doc_3").val($("#cb_tip_doc_1").val());
    $("#pers_nro_doc").val(nro_doc);
    tipo = $("#cb_tip_doc_3").val();
    if(tipo=='02'){
        get_datos_dni();
        $("#pers_pat,#pers_mat,#pers_nombres").removeAttr('disabled');
        $("#pers_raz_soc").removeAttr('disabled');        
        $("#pers_raz_soc").attr('disabled',true);
        $("#pers_nro_doc").removeAttr('maxlength');
        $("#pers_nro_doc").attr('maxlength',8);        
    }else if (tipo=='00'){        
        get_datos_ruc();
        $("#pers_raz_soc").removeAttr('disabled');
        $("#pers_raz_soc").val('');
        $("#pers_pat,#pers_mat,#pers_nombres").attr('disabled',true);
        $("#pers_nro_doc").removeAttr('maxlength');
        $("#pers_nro_doc").attr('maxlength',11);        
    }
}
function btn_bus_getdatos(){
    tipo = $("#cb_tip_doc_3").val();
    if(tipo=='02'){
        get_datos_dni(); 
    }else if (tipo=='00'){
        get_datos_ruc();
    }
}
function new_persona(){
    if ($("#cb_tip_doc_3").val() == '02') {
        if ($("#pers_sexo").val() == '-') {
            mostraralertasconfoco('Ingrese Sexo', '#pers_sexo');
            return false;
        }
        if ($("#pers_nombres").val() == '-'||$("#pers_nombres").val() == '') {
            mostraralertasconfoco('Ingrese Nombre', '#pers_pat');
            return false;
        }
        if ($("#pers_nro_doc").val() == '-'||$("#pers_mat").val() == '') {
            mostraralertasconfoco('Ingrese DNI', '#pers_nro_doc');
            return false;
        }
        if ($("#pers_pat").val()+$("#pers_mat").val() == '') {
            mostraralertasconfoco('Ingrese al menos un apellido', '#pers_pat');
            return false;
        }
    }
    $.ajax({  
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'insert_personas',
        type: 'POST',
        data:{
            pers_ape_pat : $("#pers_pat").val().toUpperCase() || '-',
            pers_ape_mat : $("#pers_mat").val().toUpperCase() || '-',
            pers_nombres : $("#pers_nombres").val().toUpperCase() || '-',
            pers_raz_soc : $("#pers_raz_soc").val().toUpperCase() || '-',
            pers_tip_doc : $("#cb_tip_doc_3").val() || '-',
            pers_nro_doc : $("#pers_nro_doc").val() || '-',
            pers_sexo : $("#pers_sexo").val() || '-',
            pers_fnac : $("#pers_fnac").val() || '1900-01-01',
            pers_foto:$("#pers_foto").attr("src")
        },
        success: function (data) {
            if(data){
                dialog_close('dialog_Personas');
                fn_consultar_persona(global_contrib_conviv);
            }            
        },
        error: function (data) {
            MensajeAlerta('* Error de Red...<br>* Contactese con el Administrador...');
        }
    }); 
}
function limpiar_personas(){
    $("#pers_nro_doc,#pers_pat,#pers_mat,#pers_nombres,#pers_raz_soc,#pers_fnac").val('');
    $("#pers_foto").attr("src", "img/avatars/male.png");
}

function new_contrib() {
    
    if ($("#txt_nro_doc").val() == '') {
        mostraralertasconfoco('Ingrese un Numero de Documento...', '#txt_nro_doc');
        return false;
    }
    if ($("#contrib_num_expediente").val() == '') {
        mostraralertasconfoco('Ingrese un Numero de Expediente...', '#contrib_num_expediente');
        return false;
    }
    if ($("#vw_contrib_id_pers").val() == '') {
        mostraralertasconfoco('Ingrese un Numero de Documento...', '#txt_nro_doc');
        return false;
    }
    
    if($("#contrib_dist").val()!='040104'){
        $("#hiddentxt_av_jr_calle_psje").val('0');
    }
    
    $.ajax({  
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'contribuyentes/create',
        type: 'GET',
        data:{   
            tipo_doc:$("#cb_tip_doc_1").val(),
            tipo_persona:$("#vw_contrib_sel_tip_contrib").val(),
            nro_doc:$("#txt_nro_doc").val(),
            tlfno_fijo:$("#contrib_tlfno_fijo").val() || '0', 
            tlfono_celular:$("#contrib_tlfono_celular").val() || '0',
            email:$("#contrib_email").val() || '@',
            est_civil:$("#contrib_est_civil").val() || '0',            
            id_dpto:$("#contrib_dpto").val(),
            id_prov:$("#contrib_prov").val(), 
            id_dist:$("#contrib_dist").val(),            
            nro_mun:$("#contrib_nro_mun").val() || '0',
            dpto:$("#contrib_dpto_depa").val() || '0',
            manz:$("#contrib_manz").val() || '0',
            lote:$("#contrib_lote").val() || '0',
            id_cond_exonerac:$("#contrib_id_cond_exonerac").val() || '1', 
            id_via:$("#hiddentxt_av_jr_calle_psje").val() || '0',             
            id_pers:$("#vw_contrib_id_pers").val() || '0', 
            id_conv:$("#vw_contrib_id_conv").val() || '0',
            nro_conv:$("#contrib_nro_doc_conv").val(),
            pat_conv:$("#contrib_conviviente_pat").val(),
            mat_conv:$("#contrib_conviviente_mat").val(),
            nom_conv:$("#contrib_conviviente_nom").val(),
            ref_dom_fis:($("#contrib_dom_fiscal").val()).toUpperCase() || '-',
            nom_via_2:($("#txt_av_jr_calle_psje").val()).toUpperCase() || '-',
            numero_expediente:$("#contrib_num_expediente").val()
        },
        success: function (data) {
            if (data.msg === 'si'){
                    fn_actualizar_grilla('table_Contribuyentes','grid_contribuyentes');
                    mostraralertasconfoco('* EL NUMERO DE EXPEDIENTE, ESTA DUPLICADO...');
                }else{
                    dialog_close('dialog_new_edit_Contribuyentes');
                    fn_actualizar_grilla('table_Contribuyentes','grid_contribuyentes');
                    MensajeExito('Contribuyente', 'Se ha Guardado un Nuevo Contribuyente...');
                }
        },
        error: function (data) {
            mostraralertas('* EL CONTRIBUYENTE YA EXISTE ...<br> <p style="font-size:10px">(Si el Problema Persiste Contactese con el Administrador)</p>');
        }
    });   
}

function eliminar_contribuyente(id) {
//    raz_soc = $.trim($("#table_Contribuyentes").getCell(id, "contribuyente"));
//    $.confirm({
//        title: '.:Cuidado... !',
//        content: 'Los Cambios no se podran revertir...',
//        buttons: {
//            Confirmar: function () {
//                $.ajax({
//                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
//                    url: 'contribuyente_delete',
//                    type: 'POST',
//                    data: {id: id},
//                    success: function (data) {                     
//                        fn_actualizar_grilla('table_Contribuyentes', 'grid_contribuyentes');
//                        dialog_close('dialog_new_edit_Contribuyentes');
//                        MensajeExito('Eliminar Contribuyente', raz_soc + '- Ha sido Eliminado');
//                    },
//                    error: function (data) {
//                        MensajeAlerta('Eliminar Contribuyente', raz_soc + '- No se pudo Eliminar.');
//                    }
//                });
//            },
//            Cancelar: function () {
//                MensajeAlerta('Eliminar Contribuyente','Operacion Cancelada.');
//            }
//        }
//    });
}

function filtro_tipo_doc(tipo) {   
    if(tipo=='02'){
        $("#vw_contrib_sel_tip_contrib").val(1);
        $("#txt_nro_doc").removeAttr('maxlength');
        $("#txt_nro_doc").attr('maxlength',8);
        $("#txt_nro_doc,#vw_contrib_contribuyente").val('');
    }else if(tipo=='00'){
        $("#txt_nro_doc").removeAttr('maxlength');
        $("#txt_nro_doc").attr('maxlength',11);
        $("#txt_nro_doc,#vw_contrib_contribuyente").val('');
        $("#vw_contrib_sel_tip_contrib").val(2);
    }
}
function filtro_tipo_doc_pers(tipo) {   
    if(tipo=='02'){
        $("#pers_pat,#pers_mat,#pers_nombres").removeAttr('disabled');
//        $("#pers_pat,#pers_mat,#pers_nombres").val('');
        $("#pers_raz_soc").attr('disabled',true);
        $("#pers_nro_doc").removeAttr('maxlength');
        $("#pers_nro_doc").attr('maxlength',8);
        $("#pers_nro_doc").val('');
        $("#pers_raz_soc").val('');
    }else if(tipo=='00'){
        $("#pers_raz_soc").removeAttr('disabled');
        $("#pers_pat,#pers_mat,#pers_nombres").attr('disabled',true);
        
        $("#pers_nro_doc").removeAttr('maxlength');
        $("#pers_nro_doc").attr('maxlength',11);
        $("#pers_nro_doc").val('');
        $("#pers_pat,#pers_mat,#pers_nombres").val('');
        $("#pers_foto").attr("src", "img/avatars/male.png");
    }
}

function limpiar_dlg_contrib(){
    $("#txt_nro_doc,#vw_contrib_contribuyente,#contrib_tlfno_fijo,#contrib_tlfono_celular,#contrib_email,#contrib_nro_mun,#txt_av_jr_calle_psje,#contrib_num_expediente").val('');
    $("#contrib_dpto_depa,#contrib_manz,#contrib_lote,#contrib_dom_fiscal,#contrib_nro_doc_conv,#contrib_conviviente_pat,#contrib_conviviente_mat,#contrib_conviviente_nom").val('');
    $("#vw_contrib_id_conv").val('');
    $("#contrib_est_civil").val('select');
    $("#hiddentxt_av_jr_calle_psje").val('0');
    $("#contrib_nro_doc_conv,#contrib_conviviente_pat,#contrib_conviviente_mat,#contrib_conviviente_nom").attr('disabled',true);
    $("#vw_contrib_sel_tip_contrib").prop("selectedIndex", 0);
    $("#contrib_id_cond_exonerac").prop("selectedIndex", 0);
}


function abrir_rep()
{
    $sector = $('#selsec').val();
    $mzna = $('#selmnza').val();
    //Id=$('#table_Contribuyentes').jqGrid ('getGridParam', 'selrow');
    //alert(Id + "/" + $sector + "/" + $mzna);
    window.open('pre_rep_contr/'+$sector+'/'+$mzna);
}

function reniec(){
    
    $.ajax({
        url: 'https://ws.ehg.pe?cache=false&dni=40524154&ws=getDatosDni',
        type:'POST',
        data:{dni : 80673320, pas : "Pr0gr4m4", ruc : 20159515240},
//        data:{ cache : "false", dni : 40524154, ws :"getDatosDni"},
        success: function(data){
            
        },
        error: function(data){}
    });
}

function limpiar_reporte(){
    $('#selsec').val('0');
    $('#selmnza').val('0');
    $('#selantra').val('0');
    $('#selec_hab_urb').val('0');
    $('#hab_urb').val('');
}

function dlg_new_reporte(){
    limpiar_reporte();
    $("#dialog_reporte_contr").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: REPORTE DE CONTRIBUYENTES :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte",
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_rep(); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }],
        close: function (event, ui) { limpiar_personas();},
        open: function (){ limpiar_personas(); }
    }).dialog('open');


    autocompletar_hab_urb('hab_urb');

}
$current_tab=1;
function abrir_rep()
{
    if($current_tab == 1) {
        $sector = $('#selsec').val();
        $mzna = $('#selmnza').val();
        $anio = $('#selantra').val();
        //Id=$('#table_Contribuyentes').jqGrid ('getGridParam', 'selrow');
        //alert(Id + "/" + $sector + "/" + $mzna);
        window.open('pre_rep_contr/'+$sector+'/'+$mzna+'/'+$anio);
    }
    if($current_tab == 2){
        var anio_hab_urb = $('#selec_hab_urb').val();
        var id_hab_urba = $('#hiddentxt_hab_urb').val();
        /*
        var val = $('#hab_urb').val();
        var xyz = $('#list option').filter(function() {
            return this.value == val;
        }).data('xyz');
        var id_hab_urba = xyz ? '' + xyz : 'No Match';*/
        if(id_hab_urba != '')
            window.open('pre_rep_contr_hab_urb/'+id_hab_urba+'/'+anio_hab_urb);
        else
            MensajeAlerta('Reporte Contrituyentes', ' Habilitación Urbana no Válida.');

        //alert(id_hab_urba);
    }

    if($current_tab == 3) {
        $sector = $('#selsec').val();
        $mzna = $('#selmnza').val();
        $anio = $('#selantra').val();
        //Id=$('#table_Contribuyentes').jqGrid ('getGridParam', 'selrow');
        //alert(Id + "/" + $sector + "/" + $mzna);
        window.open('pre_rep_contr_otro');
    }


}

function autocompletar_hab_urb(textbox) {
    $.ajax({
        type: 'GET',
        url: 'autocomplete_hab_urb',
        success: function (data) {
            var $local_sourcedoctotodo = data;
            $("#" + textbox).autocomplete({
                source: $local_sourcedoctotodo,
                focus: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hiddentxt_" + textbox).val(ui.item.value);
                    $("#" + textbox).attr('maxlength', ui.item.label.length);
                    return false;
                },
                select: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hiddentxt_" + textbox).val(ui.item.value);
                    return false;
                }
            });
        }
    });
}

function current_tab(id_report){
    $current_tab=id_report;
}

//function eliminar_contrib(){
//    id_contrib = $('#table_Contribuyentes').jqGrid ('getGridParam', 'selrow');
//    $.confirm({
//        title: '.:Cuidado... !',
//        content: 'Los Cambios no se podran revertir...',
//        buttons: {
//            Confirmar: function () {
//                $.ajax({
//                    type:'GET'
//                    url:'desactivar_contrib',
//                    data:{id_contrib:id_contrib},
//                    success: function(data){
//                        if(data.msg=='si'){
//                            MensajeExito('Eliminar','Contribuyente ha sido eliminado...');
//                            fn_actualizar_grilla('table_Contribuyentes', 'grid_contribuyentes');                                                
//                        }
//                    }
//                });
//            },
//            Cancelar: function () {}
//        }
//    });    
//}


function limpiarcontri()
{
    $("#vw_contrib_contribuyente").val("");
    $("#vw_contrib_id_pers").val('');
}