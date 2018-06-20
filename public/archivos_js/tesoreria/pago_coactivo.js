var inicoactivo=0;
function dialog_pago_coactivo() {
    $("#dlg_pago_coactivo").dialog({
        autoOpen: false, modal: true, width: 1000, show: {effect: "fade", duration: 300}, resizable: false,
        position: ['auto',10],
        create: function (event) { $(event.target).parent().css('position', 'fixed');},
        title: "<div class='widget-header'><h4>.: RECIBO COACTIVO :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-fax'></i>&nbsp; Generar Recibo",
                "class": "btn btn-primary",
                click: function () {gen_recibo_imp_coactivo();}
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {$(this).dialog("close");}
            }],
        open: function(){limpiar_form_rec_arbitrios();}       
    }).dialog('open');
    if(inicoactivo==0)
    {
        inicoactivo=1;
        grid_apersonamiento();
    }
    else
    {
        $("#vw_emi_rec_arbitrios_contrib_hidden").val(0)
        $("#vw_emi_rec_arbitrios_contrib,#vw_emi_rec_arbitrios_contrib_cod").val("");
        $("#table_Predios_Arbitrios").jqGrid("clearGridData", true);
        $("#table_cta_Arbitrios").jqGrid("clearGridData", true);
    }
    $("#vw_emision_rec_Arbitrios_tot").val(0);
}

function grid_apersonamiento(){
    jQuery("#table_apersonamiento").jqGrid({
        url: '',
        datatype: 'json', mtype: 'GET',
        height: 200, autowidth: true,
        colNames: ['Cuota', 'N° Resolución','Contribuyente / Razon Social', 'Fecha Pago', 'Monto', 'estado'],
        rowNum: 50, sortname: 'id_aper', sortorder: 'asc', viewrecords: true,caption:'Lista de Cuotas Coactiva', align: "center",
        colModel: [
            {name: 'nro_cuo', index: 'nro_cuo', width: 10},
            {name: 'nro_resol', index: 'nro_resol',align:'center', width: 40},
            {name: 'contribuyente', index: 'contribuyente', width: 120},
            {name: 'fch_pago', index: 'fch_pago', width: 60},
            {name: 'monto', index: 'monto',  width: 40,align:'right'},
            {name: 'estado', index: 'estado',align:'center', width: 20}
        ],
        pager: '#pager_table_apersonamiento',
        rowList: [50, 100],
        gridComplete: function () {
            var rows = $("#table_apersonamiento").getDataIDs();
            if (rows.length > 0) {
                var firstid = jQuery('#table_apersonamiento').jqGrid('getDataIDs')[0];
                $("#table_apersonamiento").setSelection(firstid);
            }
        },  
        onSelectRow: function (Id){
        }
    });
     
}
function calc_tot_coactivo(valor,esto){
   total=$("#inp_coactivo_tot").val();
    if($(esto).is(':checked')){
        suma=parseFloat(total)+parseFloat(valor);
    } 
    else
    {
        suma=parseFloat(total)-parseFloat(valor);
    }
    $("#inp_coactivo_tot").val(redondeo(suma,2));
    
}

function gen_recibo_imp_coactivo(){
    var Seleccionados = new Array();
    $('input[type=checkbox][name=chk_coactivo]:checked').each(function() {
        Seleccionados.push($(this).attr( "id_aper" )+"-"+$(this).attr("id_tributo")+"-"+$(this).val());
    });
    s_checks=Seleccionados.join("and");
    total_pago = parseFloat($("#inp_coactivo_tot").val());
    if(total_pago==0){
        mostraralertas('No hay cuotas seleccionadas');
        return false;
    }
    
    MensajeDialogLoadAjax('dlg_pago_coactivo', '.:: Guardando ...');
    id_contrib=$("#inp_coactivo_contrib_hidden").val();
    $.ajax({
        url:'insertar_pago_coactivo',
        type:'GET',
        data:{check:s_checks,id_contrib:id_contrib,anio:$("#inp_coactivo_contrib_anio").val(),total:$("#inp_coactivo_tot").val().replace(',', '')},
        success: function(data){
            MensajeExito("Insertó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            MensajeDialogLoadAjaxFinish('dlg_pago_coactivo');
            $("#dlg_pago_coactivo").dialog('close');
            $('#table_apersonamiento').jqGrid('clearGridData');
           fn_actualizar_grilla('table_Resumen_Recibos', 'grid_Resumen_recibos?fecha=' + $("#vw_emision_reg_pag_fil_fecha").val());
        }        
    });
}
