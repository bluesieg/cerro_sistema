var inputglobal="";
function fn_bus_contrib_rep_fis(input)
{
    inputglobal=input;
    if($("#"+input).val()=="")
    {
        mostraralertasconfoco("Ingresar Información del Contribuyente para busqueda","#dlg_contri"); 
        return false;
    }
    if($("#"+input).val().length<4)
    {
        mostraralertasconfoco("Ingresar al menos 4 caracteres de busqueda","#dlg_contri"); 
        return false;
    }
    jQuery("#table_contrib").jqGrid('setGridParam', {url: 'obtiene_cotriname?dat='+$("#"+input).val()}).trigger('reloadGrid');

    $("#dlg_bus_contr").dialog({
        autoOpen: false, modal: true, width:770, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  Busqueda de Contribuyente :.</h4></div>"       
        }).dialog('open');
}
function fn_bus_contrib_list_rep_fis(per)
{
    $("#"+inputglobal+"_hidden").val(per);
    $("#"+inputglobal).val($('#table_contrib').jqGrid('getCell',per,'contribuyente'));
    $("#dlg_bus_contr").dialog("close");
}

function dlg_rep_fisca(tipo)
{
    if (tipo===1) {
        crear_dlg('dialog_contri_fiscalizados',600,'Contribuyentes Fiscalizados',tipo);
    } 
    if (tipo===2) {
        $("#dlg_contri_hidden").val(0);
        crear_dlg('dialog_m2',600,'M2 Determinados x Fiscalizados',tipo);
    } 
   
}
function crear_dlg(id,ancho,titulo,tipo)
{
    $("#"+id).dialog({
        autoOpen: false, modal: true, width: ancho, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: "+titulo+" :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte(tipo); }
        },  {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
}
function abrir_reporte(tip)
{
    if(tip==1)
    {
        window.open('ver_rep_fisca/'+tip+'/'+$("#selantra_r0").val()+"/0");
    }
    if(tip==2)
    {
        if($("#dlg_contri")==""||$("#dlg_contri_hidden").val()==0)
        {
            contri=0;
        }
        else
        {
            contri=$("#dlg_contri_hidden").val();
        }
        window.open('ver_rep_fisca/'+tip+'/'+$("#selantra_r0").val()+"/"+contri);
    }
}


