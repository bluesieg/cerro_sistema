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
iniciar=0;
iniciar2=0;
function dlg_rep_fisca(tipo)
{
    if (tipo===1) {
        if(iniciar==0)
        {
            iniciar=1;
            autocompletar_haburb('dlg_bus_zonas');
        }
        crear_dlg('dialog_contri_fiscalizados',600,'Contribuyentes Fiscalizados',tipo);
    } 
    if (tipo===2) {
        $("#dlg_contri_hidden").val(0);
        if(iniciar2==0)
        {
            iniciar2=1;
            autocompletar_haburb('dlg_bus_zonas_2');
        }
        crear_dlg('dialog_m2',600,'M2 Determinados x Fiscalizados',tipo);
    }
    if (tipo===3) {
        crear_dlg('dialog_estado_hoja_liq',600,'Estado de Hoja de Liquidación',tipo);
    }
     if (tipo===4) {
        crear_dlg('dialog_estado_resolucion_det',500,'Estado de Resolución de Determinación',tipo);
    }
     if (tipo===5) {
        crear_dlg('dialog_estado_resolucion_det_coactivo',500,'RD enviado a Coactivo',tipo);
    }
   
}
function autocompletar_haburb(textbox){
    $.ajax({
        type: 'GET',
        url: 'autocomplete_hab_urba',
        success: function (data) {
            
            var $datos = data;
            $("#"+ textbox).autocomplete({
                source: $datos,
                focus: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    return false;
                },
                select: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hidden_"+ textbox).val(ui.item.value);
                    return false;
                }
            });
        }
    });
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
        zona=$("#hidden_dlg_bus_zonas").val();
        if($("#hidden_dlg_bus_zonas").val()==0||$("#dlg_bus_zonas").val()=='')
        {
            zona=0;
        }
        tipo=$("#sel_tip_1").val();
        window.open('ver_rep_fisca/'+tip+'/'+$("#selantra_r0").val()+"/0/"+zona+"/"+tipo);
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
        zona=$("#hidden_dlg_bus_zonas_2").val();
        if($("#hidden_dlg_bus_zonas_2").val()==0||$("#dlg_bus_zonas_2").val()=='')
        {
            zona=0;
        }
        tipo=$("#sel_tip_2").val();
        window.open('ver_rep_fisca/'+tip+'/'+$("#selantra_r0").val()+"/"+contri+"/"+zona+"/"+tipo);
    }
    if(tip==3)
    {
        window.open('ver_rep_estado_hoja_liq/'+tip+'/'+$("#select_anio_hoja_liq").val()+"/"+$("#select_estado_hl").val());
    }
    if(tip==4)
    {
        window.open('ver_rep_estado_r_d/'+tip+'/'+$("#select_anio_rd").val()+"/"+$("#select_estado_rd").val());
    }
    if(tip==5)
    {
        window.open('ver_rep_estado_r_d/4/'+$("#select_anio_rd_coactivo").val()+"/4");
    }
    
    
}


