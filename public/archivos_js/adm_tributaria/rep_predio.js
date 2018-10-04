function fn_bus_contrib()
{
    if($("#dlg_contribuyente").val()=="")
    {
        mostraralertasconfoco("Ingresar Información de busqueda","#dlg_contribuyente"); 
        return false;
    }
    if($("#dlg_contribuyente").val().length<4)
    {
        mostraralertasconfoco("Ingresar al menos 4 caracteres de busqueda","#contribuyente"); 
        return false;
    }

    jQuery("#table_contribuyente").jqGrid('setGridParam', {url: 'obtener_contribuyentes?dat='+$("#dlg_contribuyente").val()}).trigger('reloadGrid');

    $("#dlg_bus_contribuyente").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  Busqueda de Contribuyentes :.</h4></div>"       
        }).dialog('open');
       
}

function fn_bus_contrib_predio(per){
    $("#dlg_contribuyente_hidden").val(per);
    anio_predio = $("#select_anio_rep_predio").val();   
    $("#dlg_contribuyente").val($('#table_contribuyente').jqGrid('getCell',per,'contribuyente'));
    
    //tam=($('#table_contrib').jqGrid('getCell',per,'contribuyente')).length;
    //$("#vw_caja_est_cta_contrib").attr('maxlength',tam);
    
    id_pers=$('#table_contribuyente').jqGrid('getCell',per,'id_contrib');
    fn_actualizar_grilla('tabla_predio','obtener_predios_contribuyente?id_contrib='+id_pers+ '&anio=' + anio_predio);
    $("#dlg_bus_contribuyente").dialog("close");    
}

function fn_rep_predio(){
    
    contribuyente = $("#dlg_contribuyente_hidden").val();
    anio_desde = $('#select_anio_desde').val();
    anio_hasta = $('#select_anio_hasta').val();
    
    id_pred = $('#tabla_predio').jqGrid ('getGridParam', 'selrow');
    id_pred_anio = $('#tabla_predio').jqGrid ('getCell', id_pred, 'id_pred_anio');
    
    if (contribuyente == 0) {
        mostraralertasconfoco('* Seleccione un Contribuyente...', 'contribuyente');
        return false;
    }
    if (anio_desde == '') {
        mostraralertasconfoco('* Seleccione un Año...', 'anio_desde');
        return false;
    }
    var idarray = jQuery('#tabla_predio').jqGrid('getDataIDs');
        if (idarray.length == '') {
        mostraralertasconfoco('* No Existen Registros en la Tabla...');
        }else{
    
    $.ajax({
        url: 'replicar_predios?id_predio=' + id_pred + '&anio_desde=' + anio_desde + '&anio_hasta=' + anio_hasta + '&id_pred_anio=' + id_pred_anio,
        type: 'GET',
        success: function (data) {
            if (data.msg == 'si'){
                MensajeExito('EL PREDIO FUE REPLICADO SATISFACTORIAMNETE','OPERACION EXITOSA');      
            }else{
                mostraralertas('* Error al Generar Replica del Predio.<br>* Contactese con el Administrador.');
            }
        },
        error: function (data) {
            mostraralertas('* Error al Generar Replica del Predio.<br>* Contactese con el Administrador.');
        }
    });
    
   }
}

$(document).ready(function () {
jQuery("#tabla_predio").jqGrid({
        url: 'obtener_predios_contribuyente?id_contrib=0&anio=0',
        datatype: 'json', mtype: 'GET',
        height: 'auto', autowidth: true,
        toolbarfilter: true,
        colNames: ['ID','CODIGO CATASTRAL', 'DIRECCION', 'NRO PISOS', 'ID_PRED_ANIO'],
        rowNum: 12, sortname: 'id_pred', sortorder: 'asc', viewrecords: true, caption: 'REPLICAR PREDIOS', align: "center",
        colModel: [
            {name: 'id_pred', index: 'id_pred',width: 20,align:'center',hidden:true},
            {name: 'cod_cat', index: 'cod_cat',width: 20,align:'center'},
            {name: 'direccion', index: 'direccion',width: 60,align:'center'},
            {name: 'nro_pisos', index: 'nro_pisos',width: 30, align:'center'},
            {name: 'id_pred_anio', index: 'id_pred_anio',width: 10, align:'center',hidden:true}
        ],
        pager: '#pager_tabla_predio',
        rowList: [10, 20],
        gridComplete: function () {
            var idarray = jQuery('#tabla_predio').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tabla_predio').jqGrid('getDataIDs')[0];
                            $("#tabla_predio").setSelection(firstid);
                        }            
   
        },            
        onSelectRow: function (Id){
                $('#current_id_tabla').val($("#tabla_predio").getCell(Id, "id_pred"));

            }
    });  
       
       
});