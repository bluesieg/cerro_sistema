function cambiar_estado(){
    if( $('#mostrar_foto').is(':checked') ) {  
        $("#vw_foto").attr('value', '1'); 
    }else{
        $("#vw_foto").attr('value', '0');
    }
}

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
    $("#email").val($('#table_contrib').jqGrid('getCell',per,'email'));
    
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
        mostraralertasconfoco('* Ingrese Un Contribuyente con Predios Declarados...');
        return false;
    }
    
    id_contrib = $("#vw_caja_est_cta_id_contrib").val();
    window.open('caja_imp_est_cta/'+id_contrib+'/'+$("#vw_caja_ets_cta_anio_desde").val()+'/'+$("#vw_caja_ets_cta_anio_hasta").val()+'?foto='+$('#vw_foto').val()+'');
}



/********************************ENVIAR_CORREO_ELECTRONICO**************************************************/

function crear_dialogo()
{
    anio_desde = $("#vw_caja_ets_cta_anio_desde").val();
    anio_hasta = $("#vw_caja_ets_cta_anio_hasta").val();
    id_contrib = $("#vw_caja_est_cta_id_contrib").val();
    $("#dlg_persona").val($("#vw_caja_est_cta_contrib").val());
    $("#dlg_correo").val($("#email").val());
    //alert(anio_desde);
    //alert(hasta);
    //alert(id_contrib);

    $("#dlg_enviar_correo").dialog({
        autoOpen: false, modal: true, width: 1400, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Predios Ingresados Por Usuario :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Enviar Correo"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { mail_send(); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]        
    }).dialog('open');

    traer_reporte(anio_desde,anio_hasta,id_contrib);
}

function traer_reporte(anio_desde,anio_hasta,id_contrib){
    
    $('#ifrafile').attr('src','caja_imp_est_cta/'+id_contrib+'/'+anio_desde+'/'+anio_hasta+'?foto='+$('#vw_foto').val()+'');   
}

function mail_send()
{
    window.open('enviar_correo?persona='+$('#dlg_persona').val()+'&correo='+$('#dlg_correo').val()+'&imagen='+$('#archivo_reporte').val()+'');
}

function print_est_cta_correo(tipo)
{
    if (tipo===0) {
        rows = $("#tabla_est_Cuenta").getRowData().length;
        if(rows==0){
            mostraralertasconfoco('* Ingrese Un Contribuyente con Predios Declarados...');
            return false;
        }
        crear_dialogo();
    } 
}

