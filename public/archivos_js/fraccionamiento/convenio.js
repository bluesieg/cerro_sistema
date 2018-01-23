
function dialog_conve_fracc() {
    $("#vw_conve_fracc").dialog({
        autoOpen: false, modal: true, width: 950, show: {effect: "fade", duration: 300}, resizable: false,
        position: ['auto',50],        
        title: "<div class='widget-header'><h4>.: CONVENIO DE FRACCIONAMIENTO :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-fax'></i>&nbsp; Realizar Fraccionamiento",
                "class": "btn btn-success",
                click: function () {
                    if($("#vw_conve_fracc_id_pers").val()!=''){
                        fraccionamiento();
                    }else{
                        mostraralertasconfoco('Ingrese un Contribuyente','#vw_conve_fracc_contrib');
                    }                 
                }
            },{
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {$(this).dialog("close");}
            }],
        open: function(){limpiar_form_principal();fn_actualizar_grilla('table_Deuda_Contrib_Arbitrios','grid_deu_contrib_arbitrios?id_contrib=0&desde=0&hasta=0');},
        close: function(){limpiar_form_principal();fn_actualizar_grilla('table_Deuda_Contrib_Arbitrios','grid_deu_contrib_arbitrios?id_contrib=0&desde=0&hasta=0');}       
    }).dialog('open');
    
    grid_deuda_arbitrios();
}

global_tipo=0;
function fraccionamiento(){
    var Seleccionados = new Array();
    $('input[type=checkbox][name=chk_2017]:checked').each(function() {
        Seleccionados.push($(this).val());
    });
    $('input[type=checkbox][name=chk_2018]:checked').each(function() {
        Seleccionados.push($(this).val());
    });
    s_ch= Seleccionados.length;
    if(s_ch==2){
        global_tipo=3;
        //alert(global_tipo);
    }else{
        global_tipo = Seleccionados.join('');
        //alert(global_tipo);
    }    
    if(global_tipo==''){
        mostraralertasconfoco('Seleccione una Deuda para realizar el Fraccionamiento','#vw_conve_fracc_contrib');
        return false;
        //alert(global_tipo);
    }
    
    $("#vw_conve_fracc_fraccionar").dialog({
        autoOpen: false, modal: true, width: 750, show: {effect: "fade", duration: 300}, resizable: false,        
        title: "<div class='widget-header'><h4>.: CONVENIO DE FRACCIONAMIENTO :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-trash-o'></i>&nbsp; Limpiar Tabla",
                "class": "btn btn-warning",
                click: function () {limpiar_vista_fraccionamiento();}
            },{
                html: "<i class='fa fa-print'></i>&nbsp; Guardar e Imprimir Convenio",
                "class": "btn btn-primary",
                click: function () {insert_convenio();}
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {$(this).dialog("close");}
            }],
        open: function(){
            limpiar_form_conve();
            $("#vw_conve_fracc_fracc_porc_cuo_ini,#vw_conve_fracc_fracc_porc_cuo_ini_min").val('20');
        },
        close: function(){limpiar_form_conve();}
    }).dialog('open');
    $("#vw_conve_fracc_fracc_tot").val($("#vw_conve_fracc_ttotal").val().replace(',',''));    
    
    
}

function insert_convenio(){
    var rowCount =  $("#t_dina_conve_fracc tr").length;
    if(rowCount-1==0){
        mostraralertas('Tabla de Fraccionamiento Vacia');
        return false;
    }
    
    $.confirm({
        title: '.:Convenio:.',
        content: 'Realizar Convenio de Fraccionamiento',
        buttons: {
            Confirmar: function () {
                MensajeDialogLoadAjax('vw_conve_fracc_fraccionar', 'Guardando Convenio...');
                $.ajax({
                    url: 'conve_fraccionamiento/create',
                    type: 'GET',
                    data: {
                        nro_convenio     :1,
                        id_contribuyente :$("#vw_conve_fracc_id_pers").val(),            
                        interes          :$("#vw_conve_fracc_fracc_tif").val(),
                        nro_cuotas       :$("#vw_conve_fracc_fracc_n_cuo").val(),
                        total_convenio   :$("#vw_conve_fracc_fracc_tot").val().replace(',',''),
                        estado           :1,
                        detalle_fracci   :($("#vw_conve_fracc_fracc_glosa").val()).toUpperCase(),
                        period_desde     :($("#td_din_fecha_1").val()).substr(-4),
                        period_hast      :($("#td_din_fecha_"+$("#vw_conve_fracc_fracc_n_cuo").val()).val()).substr(-4),
                        porc_cuo_inic    :$("#vw_conve_fracc_fracc_porc_cuo_ini").val(),
                        cuota_inicial    :$("#vw_conve_fracc_fracc_inicial").val(),
                        id_tip_fracc     :$("#vw_conve_fracc_fracc_tip_fracc").val(),
                        tipo             :global_tipo,
                        periodo          :$("#vw_conve_fracc_anio_desde").val()+' al '+$("#vw_conve_fracc_anio_hasta").val()                        
                    },
                    success: function (data) {
                        if (data) {
                            array_det_convenio(data);
                        } else {
                            mostraralertas('* Ha Ocurrido un Error al Guardar Convenio.<br>* Actualice el Sistema e Intentelo Nuevamente.');
                        }
                    },
                    error: function (data) {
                        mostraralertas('* Error de Red.<br>* Contactese con el Administrador.');
                        MensajeDialogLoadAjaxFinish('vw_conve_fracc_fraccionar');
                    }
                });
            },
            Cancelar: function () {}
        }
    });
    
}
function array_det_convenio(id_conv) {
    n_cuotas = $("#vw_conve_fracc_fracc_n_cuo").val();
    for (i = 1; i <= n_cuotas; i++) {
        console.log(i);
        btn_insert_det_conv(i, id_conv);                       
    }
//    var i = 0;
//    var id = window.setInterval(function(){
//        if(i >= n_cuotas) {
//            clearInterval(id);
//            return;
//        } 
//        btn_insert_det_conv(i+1, id_conv);
//        console.log(i+1);
//        i++;
//    }, 300);
    fn_actualizar_grilla('table_Convenios','grid_Convenios?anio='+$("#vw_conve_fracc_cb_anio").val());
    setTimeout(function(){
        MensajeDialogLoadAjaxFinish('vw_conve_fracc_fraccionar');
        window.open('imp_cronograma_Pago_Fracc?id_conv='+id_conv+'&id_contrib='+$("#vw_conve_fracc_id_pers").val());    
    }, 3000);   
    dialog_close('vw_conve_fracc_fraccionar');
    dialog_close('vw_conve_fracc');    
   
    MensajeExito('CONVENIO MDCC', 'El Convenio se Realizó Exitosamente.');
    
}
function btn_insert_det_conv(n_cuo, id_conv) {
    $.ajax({
        url: 'convenio_detalle/create',
        type: 'GET',
        data: {
            id_conv     :id_conv,
            nro_cuota   :n_cuo,
            monto       :$("#td_din_amor_"+n_cuo).val(),
            interes     :$("#td_din_inter_"+n_cuo).val(),
            fec_pago    :$("#td_din_fecha_"+n_cuo).val(),
            total       :$("#td_din_cc_"+n_cuo).val(),
            estado      :0,
            fecha_q_pago:'',
            saldo       :$("#td_din_saldo_"+n_cuo).val()
        },
        success: function (data) {
            if (data) {
                return true;
            }
        },
        error: function (data) {
            return false;
        }
    });
}

function realizar_table_fracc(){
    
    tif=parseFloat($("#vw_conve_fracc_fracc_tif").val())/100;
    total=parseFloat($("#vw_conve_fracc_fracc_tot").val());
    inicial=parseFloat($("#vw_conve_fracc_fracc_inicial").val());
    n_cuotas=parseFloat($("#vw_conve_fracc_fracc_n_cuo").val());
//    cod_conv=$("#vw_conve_fracc_fracc_cod_conve").val();
    glosa=$("#vw_conve_fracc_fracc_glosa").val();
    deuda_total=(total-inicial);
    
    if(isNaN(inicial)){
        mostraralertasconfoco('Ingrese Monto Inicial: (0.00)','#vw_conve_fracc_fracc_inicial');
        return false;
    }
    if(isNaN(n_cuotas)){
        mostraralertasconfoco('Ingrese Numero de Cuotas','#vw_conve_fracc_fracc_n_cuo');
        return false;
    }
//    if(cod_conv==''){
//        mostraralertasconfoco('Ingrese Codigo Convenio','#vw_conve_fracc_fracc_cod_conve');
//        return false;
//    }
    if(glosa==''){
        mostraralertasconfoco('Ingrese Glosa','#vw_conve_fracc_fracc_glosa');
        return false;
    }
    
    cc=((tif*Math.pow(1+tif,n_cuotas))/(Math.pow(1+tif,n_cuotas)-1))*deuda_total;
    fecha=$("#vw_conve_fracc_fracc_fecha").val();    
    
    amor=0;saldo=0;interes=0;deuda=0;saldo_1=0;
    t_deuda=0;t_amor=0;t_inter=0;t_cc=0;
    for(i=1;i<=n_cuotas;i++){
        
        if(i==1){
            saldo=total-inicial;
            interes=tif*saldo;
            amor=cc-interes;
        }else{
            saldo=saldo-amor;
            interes=tif*saldo;
            amor=cc-interes;
        }
//        saldo=deuda;
//        if(deuda==-0.00){deuda=-1*(0.00);}
        $('#t_dina_conve_fracc').append(
        "<tr>\n\
            <td>" + i + "</td>\n\
            <td><label class='input'><input id='td_din_saldo_" + i + "' type='text' value='" + formato_numero(saldo,3,'.') + "' disabled='' class='input-xs text-align-right'></label></td>\n\
            <td><label class='input'><input id='td_din_amor_" + i + "' type='text' value='" + formato_numero(amor,3,'.') + "' disabled='' class='input-xs text-align-right' style='font-size:12px'></label></td>\n\
            <td><label class='input'><input id='td_din_inter_" + i + "' type='text' value='" + formato_numero(interes,3,'.') + "' disabled='' class='input-xs text-align-right' style='font-size:12px'></label></td>\n\
            <td><label class='input'><input id='td_din_cc_" + i + "' type='text' value='" + formato_numero(cc,3,'.') + "' disabled='' class='input-xs text-align-right' style='font-size:12px'></label></td>\n\
            <td><label class='input'><input id='td_din_fecha_" + i + "' type='text' value='" + sumaFecha(i*30,fecha) + "' disabled='' class='input-xs text-align-right' style='font-size:12px'></label></td>\n\
        </tr>");
//        t_deuda=t_deuda+deuda;
        t_amor=t_amor+amor;
        t_inter=t_inter+interes;
        t_cc=t_cc+cc;
    }
    $("#vw_con_fracc_tot_amor").val(formato_numero(t_amor,2,'.',','));
    $("#vw_con_fracc_tot_inter").val(formato_numero(t_inter,2,'.',','));
    $("#vw_con_fracc_tot_cc").val(formato_numero(t_cc,2,'.',','));
}

function calc_inicial(value){    
    monto_min=$("#vw_conve_fracc_fracc_porc_cuo_ini_min").val();   
    if(parseFloat(value)<parseFloat(monto_min)){
        mostraralertas('Minimo: '+monto_min+'<br>Ingrese un valor mayor');
        $("#vw_conve_fracc_fracc_porc_cuo_ini").val(monto_min);
        return false;
    }
    total=parseFloat($("#vw_conve_fracc_fracc_tot").val());
    inicial=(value/100)*total;
    
    $("#vw_conve_fracc_fracc_inicial").val(formato_numero(inicial,2,'.'));
}
function calc_deuda(value){
    n_cuo=parseFloat($("#vw_conve_fracc_fracc_n_cuo").val());
    limit=parseFloat($("#vw_conve_fracc_fracc_limit_cuo").val());
    if(n_cuo>limit){
        mostraralertasconfoco("Limite de Cuotas: " + limit,"#vw_conve_fracc_fracc_n_cuo");        
        return false;
    }
    
    value = value || parseFloat($("#vw_conve_fracc_fracc_inicial").val());
    total=parseFloat($("#vw_conve_fracc_fracc_tot").val());
    $("#vw_conve_fracc_fracc_deuda").val((total-value).toFixed(2));
}

function sel_tip_fracc(porcent){
    por = (porcent).substr(-3, 2);    
    $("#vw_conve_fracc_fracc_porc_cuo_ini_min,#vw_conve_fracc_fracc_porc_cuo_ini").val(por);
    calc_inicial(por);
}

function fn_bus_contrib(){
    if($("#vw_conve_fracc_contrib").val()=="")
    {
        mostraralertasconfoco("Ingrese un Contribuyente para Buscar","#vw_conve_fracc_contrib"); 
        return false;
    }
    if($("#vw_conve_fracc_contrib").val().length<4)
    {
        mostraralertasconfoco("Ingresar al menos 4 caracteres de busqueda","#vw_conve_fracc_contrib"); 
        return false;
    }
    jQuery("#table_contrib").jqGrid('setGridParam', {url: 'obtiene_cotriname?dat='+$("#vw_conve_fracc_contrib").val()}).trigger('reloadGrid');

    $("#dlg_bus_contr").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  Busqueda de Contribuyente :.</h4></div>"       
        }).dialog('open');
       
}
function fn_bus_contrib_list(per){
    $("#vw_conve_fracc_id_pers").val(per);
    
    $("#vw_conve_fracc_cod_contrib").val($('#table_contrib').jqGrid('getCell',per,'id_per'));    
    $("#vw_conve_fracc_contrib").val($('#table_contrib').jqGrid('getCell',per,'contribuyente'));
    $("#vw_conve_fracc_domicilio").val($('#table_contrib').jqGrid('getCell',per,'email'));
    tam=($('#table_contrib').jqGrid('getCell',per,'contribuyente')).length;
    anio=$("#vw_emi_rec_imp_pre_anio").val();
    
    $("#vw_conve_fracc_contrib").attr('maxlength',tam);
    fn_actualizar_grilla('table_Deuda_Contrib_Arbitrios','grid_deu_contrib_arbitrios?id_contrib='+per+'&desde='+$("#vw_conve_fracc_anio_desde").val()+'&hasta='+$("#vw_conve_fracc_anio_hasta").val());
    $("#dlg_bus_contr").dialog("close");
    
}

function grid_deuda_arbitrios(){
    jQuery("#table_Deuda_Contrib_Arbitrios").jqGrid({
        url: 'grid_deu_contrib_arbitrios?id_contrib=0&desde=0&hasta=0',
        datatype: 'json', mtype: 'GET',
        height: 100, autowidth: true,
        colNames: ['id_tipo','tipo', 'Deuda','Año','Seleccionar'],
        rowNum: 5, sortname: 'anio', sortorder: 'desc', viewrecords: true,caption:'Deuda Contribuyente', align: "center",
        colModel: [
            {name: 'id_tipo', index: 'id_tipo', hidden:true},
            {name: 'tipo', index: 'tipo', width: 60},
            {name: 'deuda_arb', index: 'deuda_arb',align:'center', width: 60},
            {name: 'anio_deu', index: 'anio',align:'center', width: 60},
            {name: 'check', index: 'check',align:'center', width: 60}
        ],
        pager: '#pager_table_Deuda_Contrib_Arbitrios',
        rowList: [10, 20],
        gridComplete: function () {
            var rows = $("#table_Deuda_Contrib_Arbitrios").getDataIDs();
            if (rows.length > 0) {
                var firstid = jQuery('#table_Deuda_Contrib_Arbitrios').jqGrid('getDataIDs')[0];
                $("#table_Deuda_Contrib_Arbitrios").setSelection(firstid);
            }
            $("#vw_conve_fracc_ttotal").val('0.00');
            tot_deuda=0;
        },            
        ondblClickRow: function (Id) {}
    });    
}
tot_deuda=0;
function check_tot_fracc(val,source){
    
    if($(source).is(':checked')){
        tot_deuda=tot_deuda+val;
    } else {
        tot_deuda=tot_deuda-val;      
    }
    $("#vw_conve_fracc_ttotal").val(formato_numero(tot_deuda,2,'.',','));
}
function limpiar_form_principal(){
    tot_deuda=0;
    global_tipo=0;
    $("#vw_conve_fracc_contrib,#vw_conve_fracc_cod_contrib,#vw_conve_fracc_domicilio").val('');
}
function limpiar_form_conve(){
    $("#vw_conve_fracc_fracc_inicial,#vw_conve_fracc_fracc_n_cuo,#vw_conve_fracc_fracc_deuda,#vw_conve_fracc_fracc_glosa,#vw_conve_fracc_fracc_cod_conve").val('');
    limpiar_vista_fraccionamiento();
}

function limpiar_vista_fraccionamiento(){
    $("#t_dina_conve_fracc > tbody > tr").remove();
    $("#vw_con_fracc_tot_amor,#vw_con_fracc_tot_inter,#vw_con_fracc_tot_cc").val('000.00');
}

function act_des_hast(){
    per = $("#vw_conve_fracc_id_pers").val();
    desde = $("#vw_conve_fracc_anio_desde").val();
    hasta = $("#vw_conve_fracc_anio_hasta").val();
    fn_actualizar_grilla('table_Deuda_Contrib_Arbitrios','grid_deu_contrib_arbitrios?id_contrib='+per+'&desde='+desde+'&hasta='+hasta);
    
    
//    $("#vw_conve_fracc_ttotal").val('000.00');
//    tot_deuda=0;
}

sumaFecha = function(d, fecha){
    var Fecha = new Date();
    var sFecha = fecha || (Fecha.getDate() + "/" + (Fecha.getMonth() +1) + "/" + Fecha.getFullYear());
    var sep = sFecha.indexOf('/') != -1 ? '/' : '-'; 
    var aFecha = sFecha.split(sep);
    var fecha = aFecha[2]+'/'+aFecha[1]+'/'+aFecha[0];
    fecha= new Date(fecha);
    fecha.setDate(fecha.getDate()+parseInt(d));
    var anno=fecha.getFullYear();
    var mes= fecha.getMonth()+1;
    var dia= fecha.getDate();
    mes = (mes < 10) ? ("0" + mes) : mes;
    dia = (dia < 10) ? ("0" + dia) : dia;
    
    switch (mes) {
        case '01':
            mes='ene';
            break
        case '02':            
            mes='feb';
            break
        case '03':            
            mes='mar';
            break
        case '04':            
            mes='abr';
            break
        case '05':            
            mes='may';
            break
        case '06':            
            mes='jun';
            break
        case '07':            
            mes='jul';
            break
        case '08':            
            mes='ago';
            break
        case '09':            
            mes='sep';
            break
        case 10:            
            mes='oct';
            break
        case 11:            
            mes='nov';
            break
        case 12:            
            mes='dic';
            break;        
        default:
    }
    
    var fechaFinal = dia+sep+mes+sep+anno;
    return (fechaFinal);
};