var inigridarb=0;
function dialog_emi_rec_pag_arbitrios() {
    $("#vw_emision_rec_pag_arbitrios").dialog({
        autoOpen: false, modal: true, width: 1350, show: {effect: "fade", duration: 300}, resizable: false,
        position: ['auto',10],
        create: function (event) { $(event.target).parent().css('position', 'fixed');},
        title: "<div class='widget-header'><h4>.: RECIBO ARBITRIOS :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-fax'></i>&nbsp; Generar Recibo",
                "class": "btn btn-primary",
                click: function () {gen_recibo_imp_arbitrios();}
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {$(this).dialog("close");}
            }],
        open: function(){limpiar_form_rec_arbitrios();}       
    }).dialog('open');
    if(inigridarb==0)
    {
        inigridarb=1;
        grid_predios_arbitrios();
    }
    else
    {
        $("#vw_emi_rec_arbitrios_contrib_hidden").val(0)
        $("#vw_emi_rec_arbitrios_contrib,#vw_emi_rec_arbitrios_contrib_cod").val("");
        $("#table_Predios_Arbitrios").jqGrid("clearGridData", true);
        $("#table_cta_Arbitrios").jqGrid("clearGridData", true);
    }
}
function gen_recibo_imp_arbitrios(){
    var Seleccionados = new Array();
    $('input[type=checkbox][name=chk_abr]:checked').each(function() {
        Seleccionados.push($(this).attr( "pago" )+"-"+$(this).attr( "mes" ));
    });
    s_checks=Seleccionados.join("and");
    tot_trim = parseFloat($("#vw_emision_rec_Arbitrios_tot").val());
    if(tot_trim==0){
        mostraralertas('No hay meses seleccionados');
        return false;
    }
    
    MensajeDialogLoadAjax('vw_emision_rec_pag_arbitrios', '.:: Guardando ...');
    id_contrib=$("#vw_emi_rec_arbitrios_contrib_hidden").val();
    firstid=$('#table_Predios_Arbitrios').jqGrid ('getGridParam', 'selrow');
    $.ajax({
        url:'insertar_pago_arbitrio',
        type:'GET',
        data:{check:s_checks,id_contrib:id_contrib,anio:$("#vw_emi_rec_arbitrios_contrib_anio").val()},
        success: function(data){
            MensajeExito("Insertó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            MensajeDialogLoadAjaxFinish('vw_emision_rec_pag_arbitrios');
            $("#vw_emision_rec_pag_arbitrios").dialog('close');
           fn_actualizar_grilla('table_Resumen_Recibos', 'grid_Resumen_recibos?fecha=' + $("#vw_emision_reg_pag_fil_fecha").val());
//            $("#vw_emision_rec_Arbitrios_tot").val(0);
//            fn_actualizar_grilla('table_cta_Arbitrios', 'grid_cta_pago_arbitrios?id_contrib='+id_contrib+'&id_pred='+firstid);
        }        
    });
}
tope_parques=0;
tope_seguridad=0;
tope_recojo=0;
tope_barrido=0;
function grid_predios_arbitrios(){
    jQuery("#table_Predios_Arbitrios").jqGrid({
        url: 'grid_pred_arbitrios?id_contrib=0&anio='+$("#vw_emi_rec_arbitrios_contrib_anio").val(),
        datatype: 'json', mtype: 'GET',
        height: 100, autowidth: true,
        colNames: ['id_pred', 'id_contrib','Sec','Mzna','Lote','Contribuyente / Razon Social', 'Tip.Predio', 'Est.Construccion', 'S/. Terreno', 'S/. Contruccion'],
        rowNum: 5, sortname: 'id_pred', sortorder: 'desc', viewrecords: true,caption:'Lista de Predios', align: "center",
        colModel: [
            {name: 'id_pred', index: 'id_pred', hidden: true},
            {name: 'id_contrib', index: 'id_contrib', hidden: true},
            {name: 'sec', index: 'sec', width: 60},
            {name: 'mzna', index: 'mzna', width: 60},
            {name: 'lote', index: 'lote', width: 60},
            {name: 'contribuyente', index: 'contribuyente', hidden: true},
            {name: 'tp', index: 'tp', width: 80},
            {name: 'descripcion', index: 'descripcion',  width: 120},
            {name: 'val_ter', index: 'val_ter',align:'right', width: 80},
            {name: 'val_const', index: 'val_const',align:'right', width: 80}            
        ],
        pager: '#pager_table_Predios_Arbitrios',
        rowList: [10, 20],
        gridComplete: function () {
            var rows = $("#table_Predios_Arbitrios").getDataIDs();
            if (rows.length > 0) {
                var firstid = jQuery('#table_Predios_Arbitrios').jqGrid('getDataIDs')[0];
                $("#table_Predios_Arbitrios").setSelection(firstid);
            }
            id_contrib =$("#table_Predios_Arbitrios").getCell(firstid, 'id_contrib');
            fn_actualizar_grilla('table_cta_Arbitrios', 'grid_cta_pago_arbitrios?id_contrib='+id_contrib+'&id_pred='+firstid);
            deuda_total=0;
        },  
        onSelectRow: function (Id){
            id_contrib =$("#table_Predios_Arbitrios").getCell(Id, 'id_contrib');
            fn_actualizar_grilla('table_cta_Arbitrios', 'grid_cta_pago_arbitrios?id_contrib='+id_contrib+'&id_pred='+Id);
            deuda_total=0;
        }
    });
    jQuery("#table_cta_Arbitrios").jqGrid({
        url: 'grid_cta_pago_arbitrios?id_contrib=0&id_pred=0&anio=0',
        datatype: 'json', mtype: 'GET',
        height: '150', autowidth: true,
        colNames: ['id', 'id_contri', 'Uso','Piso','Descripcion', 'Ene', 'abo_ene','Feb', 'abo_feb','Mar', 'abo_mar','Abr', 'abo_abr','May', 'abo_may','Jun', 'abo_jun',
        'Jul', 'abo_jul','Ago', 'abo_ago','Sep', 'abo_sep','Oct', 'abo_oct','Nov', 'abo_nov','Dic', 'abo_dic','Total Debe'],
        rowNum: 20, sortname: 'id_cta_arb', sortorder: 'asc', viewrecords: true,caption:'Arbitrios del Predio', align: "center",
        colModel: [
            {name: 'id', index: 'id', hidden: true},
            {name: 'id_contri', index: 'id_contri', hidden: true},
            {name: 'uso_arbitrio', index: 'uso_arbitrio',  width: 50},
            {name: 'cod_piso', index: 'cod_piso',  width: 20},
            {name: 'descripcion', index: 'descripcion',  width: 70},
            {name: 'pgo1',index: 'pgo1', align:'left', width: 35},
            {name: 'abo1',index: 'abo1', hidden: true},
            {name: 'pgo2',index: 'pgo2', align:'left', width: 35},
            {name: 'abo2',index: 'abo2', hidden: true},
            {name: 'pgo3',index: 'pgo3', align:'left', width: 35},
            {name: 'abo3',index: 'abo3', hidden: true},
            {name: 'pgo4',index: 'pgo4', align:'left', width: 35},
            {name: 'abo4',index: 'abo4', hidden: true},
            {name: 'pgo5',index: 'pgo5', align:'left', width: 35},
            {name: 'abo5',index: 'abo5', hidden: true},
            {name: 'pgo6',index: 'pgo6', align:'left', width: 35},
            {name: 'abo6',index: 'abo6', hidden: true},
            {name: 'pgo7',index: 'pgo7', align:'left', width: 35},
            {name: 'abo7',index: 'abo7', hidden: true},
            {name: 'pgo8',index: 'pgo8', align:'left', width: 35},
            {name: 'abo8',index: 'abo8', hidden: true},
            {name: 'pgo9',index: 'pgo9', align:'left', width: 35},
            {name: 'abo9',index: 'abo9', hidden: true},
            {name: 'pgo10',index: 'pgo10', align:'left', width: 35},
            {name: 'abo10',index: 'abo10', hidden: true},
            {name: 'pgo11',index: 'pgo11', align:'left', width: 35},
            {name: 'abo11',index: 'abo11', hidden: true},
            {name: 'pgo12',index: 'pgo12', align:'left', width: 35},
            {name: 'abo12',index: 'abo12', hidden: true},            
            {name: 'deuda_arb',index: 'deuda_arb',align:'right', width: 35}
        ],
        pager: '#pager_table_cta_Arbitrios',
        rowList: [10, 20],
        gridComplete: function () {
            var rows = $("#table_cta_Arbitrios").getDataIDs();
            for (var i = 0; i < rows.length; i++) {
                   
                    for (var a = 1; a <= 12; a++) {
                        var val = $("#table_cta_Arbitrios").getCell(rows[i], 'abo'+a);
                        var pag = $("#table_cta_Arbitrios").getCell(rows[i], 'pgo'+a);
                        var id = rows[i];
                        if (val == 0) {
                            $("#table_cta_Arbitrios").jqGrid("setCell", rows[i], 'pgo'+a,
                                    "<input type='checkbox' name='chk_abr' value='" + pag + "' pago='"+id+"' mes='"+a+"' onchange='calc_tot_arbitrios(this.value,this,"+id+","+a+")'>"+pag);
                        }
                        else
                        {
                            $("#table_cta_Arbitrios").jqGrid("setCell", rows[i], 'pgo'+a,
                            '<a href="javascript:void(0);" class="btn bg-color-green txt-color-white btn-circle" style="font-size: 8px;width: 15px; height: 15px; padding-top: 0px; margin-right: 7px"><i class="glyphicon glyphicon-ok"></i></a>'+val);
                        }
                    }
                }
            
            if (rows.length > 0) {
                var firstid = jQuery('#table_cta_Arbitrios').jqGrid('getDataIDs')[0];
                $("#table_cta_Arbitrios").setSelection(firstid);
            }  
        },            
        ondblClickRow: function (Id) {}
    });    
}
function calc_tot_arbitrios(valor,esto,id,mes){
   total=$("#vw_emision_rec_Arbitrios_tot").val();
    if($(esto).is(':checked')){
        suma=parseFloat(total)+parseFloat(valor);
        
        $("#vw_emision_rec_Arbitrios_tot").val(redondeo(suma,4));
    } 
    else
    {
        resta=parseFloat(total)-parseFloat(valor);
        $("#vw_emision_rec_Arbitrios_tot").val(redondeo(resta,4));
    }
    
}
deuda_total=0;
function check_anio(name,source,deuda){ 
    
    checkboxes = document.getElementsByName(name);
    if(name==57){
        for(var i=0;i<=tope_parques-1;i++) {
            checkboxes[i].checked = source.checked;
        }   
    }
    if(name==56){
        for(var i=0;i<=tope_seguridad-1;i++) {
            checkboxes[i].checked = source.checked;
        }   
    }
    if(name==55){
        for(var i=0;i<=tope_recojo-1;i++) {
            checkboxes[i].checked = source.checked;
        }   
    }
    if(name==54){
        for(var i=0;i<=tope_barrido-1;i++) {
            checkboxes[i].checked = source.checked;
        }   
    }

    if($(checkboxes).is(':checked')){
        deuda_total=0;
        deuda_total=deuda_total+deuda;
    } else {
        deuda_total=deuda_total-deuda;      
    }
    $("#vw_emision_rec_Arbitrios_tot").val(formato_numero(deuda_total,3,'.',','));
}
function selanio_arbi_pred(anio){
    rowId=$('#table_Predios_Arbitrios').jqGrid ('getGridParam', 'selrow');
    if($("#vw_emi_rec_arbitrios_contrib_hidden").val()>0)
    {
        fn_actualizar_grilla('table_Predios_Arbitrios','grid_pred_arbitrios?id_contrib='+id_contrib+'&anio='+anio);
    }
}

function fn_bus_contrib_list_arb(per){
    $("#vw_emi_rec_arbitrios_contrib_hidden").val(per);
    
    $("#vw_emi_rec_arbitrios_contrib_cod").val($('#table_contrib').jqGrid('getCell',per,'id_per'));    
    $("#vw_emi_rec_arbitrios_contrib").val($('#table_contrib').jqGrid('getCell',per,'contribuyente'));
    tam=($('#table_contrib').jqGrid('getCell',per,'contribuyente')).length;
//    anio=$("#vw_emi_rec_imp_pre_anio").val();
    
    $("#vw_emi_rec_arbitrios_contrib").attr('maxlength',tam);
//    id_pers=$('#table_contrib').jqGrid('getCell',per,'id_pers');
    fn_actualizar_grilla('table_Predios_Arbitrios','grid_pred_arbitrios?id_contrib='+$("#vw_emi_rec_arbitrios_contrib_hidden").val());
    $("#dlg_bus_contr").dialog("close");    
}


function limpiar_form_rec_arbitrios(){
    tope_parques=0;
    tope_seguridad=0;
    tope_recojo=0;
    tope_barrido=0;
    deuda_total=0;
}