

function fn_bus_contrib(){
    if($("#vw_caja_est_cta_contrib").val()=="")
    {
        mostraralertasconfoco("Ingrese un Contribuyente para Buscar","#vw_caja_est_cta_contrib"); 
        return false;
    }
    if($("#vw_caja_est_cta_contrib").val().length<4)
    {
        mostraralertasconfoco("Ingresar al menos 4 caracteres de busqueda","#vw_caja_est_cta_contrib"); 
        return false;
    }
    jQuery("#table_contrib").jqGrid('setGridParam', {url: 'obtiene_cotriname?dat='+$("#vw_caja_est_cta_contrib").val()}).trigger('reloadGrid');

    $("#dlg_bus_contr").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  Busqueda de Contribuyente :.</h4></div>"       
        }).dialog('open');       
}
function fn_bus_contrib_caja_est_cta(per){
    $("#vw_caja_est_cta_id_contrib").val(per);
    
    $("#vw_caja_est_cta_cod_contrib").val($('#table_contrib').jqGrid('getCell',per,'id_per'));    
    $("#vw_caja_est_cta_contrib").val($('#table_contrib').jqGrid('getCell',per,'contribuyente'));
    
    tam=($('#table_contrib').jqGrid('getCell',per,'contribuyente')).length;
    $("#vw_caja_est_cta_contrib").attr('maxlength',tam);
    
    id_pers=$('#table_contrib').jqGrid('getCell',per,'id_pers');
    fn_actualizar_grilla('tabla_est_Cuenta','caja_est_cta_contrib?id_pers='+id_pers+'&desde='+$("#vw_caja_ets_cta_anio_desde").val()+'&hasta='+$("#vw_caja_ets_cta_anio_hasta").val());
    $("#dlg_bus_contr").dialog("close");    
}

function selanio_est_cta(){
    desde = $("#vw_caja_ets_cta_anio_desde").val();
    hasta = $("#vw_caja_ets_cta_anio_hasta").val();
    fn_actualizar_grilla('tabla_est_Cuenta','caja_est_cta_contrib?id_pers='+id_pers+'&desde='+desde+'&hasta='+hasta);
}

function print_est_cta(){
    rows = $("#tabla_est_Cuenta").getRowData().length;
    if(rows==0){
        mostraralertas('* Ingrese Un Contribuyente con Predios Declarados...');
        return false;
    }
    
    id_contrib = $("#vw_caja_est_cta_id_contrib").val();
    window.open('caja_imp_est_cta/'+id_contrib+'/'+$("#vw_caja_ets_cta_anio_desde").val()+'/'+$("#vw_caja_ets_cta_anio_hasta").val());
}
function enviar_est_cta(){
    rows = $("#tabla_est_Cuenta").getRowData().length;
    if(rows==0){
        mostraralertas('* Ingrese Un Contribuyente con Predios Declarados...');
        return false;
    }
    $("#vw_vista_previa").dialog({
        autoOpen: false, modal: true, width: 850,height:600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:ESTADO DE CUENTA:.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Confirmar y Enviar",
            "class": "btn btn-primary",
            click: function (){
    //                    printTrigger('imp_pago_rec?id_rec='+id_recibo);
                dialog_close('vw_vista_previa');
                dialog_close('vw_caja_mov_realizar_pago');
                MensajeExito('Conforme', 'EL Pago se ha realizado con Exito');
                $("#print_recibo_pagado").contents().find("body").html('');
            }
        }]        
    }).dialog('open');
    id_contrib = $("#vw_caja_est_cta_id_contrib").val();
    //window.open('caja_imp_est_cta/'+id_contrib+'/'+$("#vw_caja_ets_cta_anio_desde").val()+'/'+$("#vw_caja_ets_cta_anio_hasta").val());

        window.open('caja_env_est_cta/'+id_contrib+'/'+$("#vw_caja_ets_cta_anio_desde").val()+'/'+$("#vw_caja_ets_cta_anio_hasta").val());
}
