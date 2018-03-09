function traer_contri_cod(input, doc) {
    MensajeDialogLoadAjax(input, '.:: Cargando ...');
    $.ajax({
        url: 'autocomplete_contrib?doc=0&cod=' + doc,
        type: 'GET',
        success: function (data) {
            if (data.msg == 'si') {
                $("#" + input + "_hidden").val(data.id_pers);
                $("#" + input).val(data.contribuyente);
                
            } else {
                $("#" + input + "_hidden").val(0);
                $("#" + input).val("");
                
                mostraralertas('* El Documento Ingresado no Existe, registre al contribuyente o intente con otro número ... !');
            }
            MensajeDialogLoadAjaxFinish(input);
            callfilltab();

        },
        error: function (data) {
            mostraralertas('* Error Interno !  Comuniquese con el Administrador...');
            MensajeDialogLoadAjaxFinish(input);
        }
    });
}
function callfilltab()
{
    if($("#dlg_contri_hidden").val()>0)
    {
        jQuery("#table_predios").jqGrid('setGridParam', {url: 'gridpredio?tpre=0&mnza=0&ctr='+$("#dlg_contri_hidden").val()+'&an='+$("#selantra").val()}).trigger('reloadGrid');
    }
    else
    {
        $("#table_predios").jqGrid("clearGridData", true);
    }
}
function abrir_rep(tip)
{
    Id=$('#table_predios').jqGrid ('getGridParam', 'selrow');
    rowNum=$('#table_predios').jqGrid('getCell', Id, 'rn');
    
    if(Id==null)
    {
        mostraralertas("No hay Predio seleccionado para impresión");
        return false;
    }
    if(tip=="PR" && $('#table_predios').jqGrid('getCell',Id,'tp')!="RUS")
    {
        mostraralertas("El predio seleccionado no es Rustico.");
        return false;
    }
    if(tip=="PU" && $('#table_predios').jqGrid('getCell',Id,'tp')!="URB")
    {
        
        mostraralertas("El predio seleccionado no es Urbano.");
        return false;
    }
    window.open('pre_rep/'+tip+'/'+Id+'/'+$("#selantra").val()+'/'+$("#dlg_contri_hidden").val()+'/'+rowNum);
    
    
}
function fn_bus_contrib()
{
    if($("#dlg_contri").val()=="")
    {
        mostraralertasconfoco("Ingresar Información de busqueda","#dlg_contri"); 
        return false;
    }
    if($("#dlg_contri").val().length<4)
    {
        mostraralertasconfoco("Ingresar al menos 4 caracteres de busqueda","#dlg_contri"); 
        return false;
    }
    jQuery("#table_contrib").jqGrid('setGridParam', {url: 'obtiene_cotriname?dat='+$("#dlg_contri").val()}).trigger('reloadGrid');

    $("#dlg_bus_contr").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  Busqueda de Contribuyente :.</h4></div>"       
        }).dialog('open');
       
}
function fn_bus_contrib_list(per)
{
    $("#dlg_contri_hidden").val(per);
    $("#dlg_dni").val($('#table_contrib').jqGrid('getCell',per,'id_per'));
    $("#dlg_contri").val($('#table_contrib').jqGrid('getCell',per,'contribuyente'));
    jQuery("#table_predios").jqGrid('setGridParam', {url: 'gridpredio?tpre=0&mnza=0&ctr='+per+'&an='+$("#selantra").val()}).trigger('reloadGrid');
    $("#dlg_bus_contr").dialog("close");
    
}