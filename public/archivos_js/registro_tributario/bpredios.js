

function limpiar(){
    $("#hidden_dlg_direccion").val("");
    $("#dlg_direccion").val("");
}

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
    $("#dlg_id_contribuyente").val(per); 
    $("#dlg_contribuyente").val($('#table_contribuyente').jqGrid('getCell',per,'contribuyente'));
    
    id_contrib=$('#table_contribuyente').jqGrid('getCell',per,'id_contrib');
    
    fn_actualizar_grilla('tabla_predio','get_predios_contribuyente?id_contrib='+id_contrib);
    $("#dlg_bus_contribuyente").dialog("close");    
}

function fn_buscar_predios(){
    direccion = $("#dlg_direccion").val();
    
    if (direccion == 0) {
        mostraralertasconfoco('* Ingrese una direccion...', 'direccion');
        return false;
    }
     jQuery("#tabla_predio").jqGrid('setGridParam', {url: 'get_predios?direccion='+direccion}).trigger('reloadGrid');
   
    
}
