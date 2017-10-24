var inputglobal="";
function fn_bus_contrib_hl(input)
{
    inputglobal=input;
    if($("#"+input).val()=="")
    {
        mostraralertasconfoco("Ingresar Información del Contribuyente para busqueda","#"+inputglobal); 
        return false;
    }
    if($("#"+input).val().length<4)
    {
        mostraralertasconfoco("Ingresar al menos 4 caracteres de busqueda","#"+inputglobal); 
        return false;
    }
    jQuery("#table_contrib").jqGrid('setGridParam', {url: 'obtiene_cotriname?dat='+$("#"+input).val()}).trigger('reloadGrid');

    $("#dlg_bus_contr").dialog({
        autoOpen: false, modal: true, width:770, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  Busqueda de Contribuyente :.</h4></div>"       
        }).dialog('open');
}
function fn_bus_lis_hl(per)
{
    $("#"+inputglobal+"_hidden").val(per);
    $("#"+inputglobal).val($('#table_contrib').jqGrid('getCell',per,'contribuyente'));
    if(inputglobal=="dlg_contri")
    {
        buscar_carta(1);
    }
    if(inputglobal=="dlg_contri_carta")
    {
        buscar_carta(2);
    }
    $("#dlg_bus_contr").dialog("close");
}

function buscar_carta(tip)
{
    if(tip==0)
    {
        $("#table_hojas").jqGrid("clearGridData", true);
        jQuery("#table_hojas").jqGrid('setGridParam', {url: 'trae_hojas_liq/'+$("#selantra").val()+'/0/0/0/0'}).trigger('reloadGrid');
    }
    if(tip==1)
    {
        $("#table_hojas").jqGrid("clearGridData", true);
        jQuery("#table_hojas").jqGrid('setGridParam', {url: 'trae_hojas_liq/'+$("#selantra").val()+'/'+$("#dlg_contri_hidden").val()+'/0/0/0'}).trigger('reloadGrid');
    }
    if(tip==2)
    {
        $("#table_sel_cartas").jqGrid("clearGridData", true);
        jQuery("#table_sel_cartas").jqGrid('setGridParam', {url: 'trae_cartas/'+$("#selantra").val()+'/'+$("#dlg_contri_carta_hidden").val()+'/0/0/0'}).trigger('reloadGrid');
    }
    if(tip==3)
    {
        if($("#dlg_bus_num_carta").val()=="")
        {
            mostraralertasconfoco("Ingresar Numero","#dlg_bus_num_carta"); 
            return false;
        }
        ajustar(6,'dlg_bus_num_carta')
        num=$("#dlg_bus_num_carta").val();
        jQuery("#table_sel_cartas").jqGrid('setGridParam', {url: 'trae_cartas/'+$("#selantra").val()+'/0/0/0/'+num}).trigger('reloadGrid');
    }
    if(tip==4)
    {
        if($("#dlg_bus_num").val()=="")
        {
            mostraralertasconfoco("Ingresar Numero","#dlg_bus_num"); 
            return false;
        }
        ajustar(6,'dlg_bus_num')
        num=$("#dlg_bus_num").val();
        jQuery("#table_hojas").jqGrid('setGridParam', {url: 'trae_hojas_liq/'+$("#selantra").val()+'/0/0/0/'+num}).trigger('reloadGrid');
    }
    if(tip==5)
    {
        if($("#dlg_bus_fini").val()==""||$("#dlg_bus_ffin").val()=="")
        {
            mostraralertasconfoco("Ingresar Fechas de busqueda","dlg_bus_fini");
            return false;
        }
        ini=$("#dlg_bus_fini").val().replace(/\//g,"-");
        fin=$("#dlg_bus_ffin").val().replace(/\//g,"-");
        jQuery("#table_hojas").jqGrid('setGridParam', {url: 'trae_hojas_liq/0/0/'+ini+'/'+fin+'/0'}).trigger('reloadGrid');
    }
}
function fn_sel_carta()
{
    $("#dlg_sel_carta").dialog({
        autoOpen: false, modal: true, width: 1100, 
        show:{ effect: "explode", duration: 500},
        hide:{ effect: "explode", duration: 800}, resizable: false,
        title: "<div class='widget-header'><h4><span class='widget-icon'> <i class='fa fa-align-justify'></i> </span> Crear Hoja de Liquidación</h4></div>"
        }).dialog('open');
        jQuery("#table_sel_cartas").jqGrid('setGridParam', {url: 'trae_cartas/'+$("#selantra").val()+'/0/0/0/0'}).trigger('reloadGrid');
}
function new_hoja($id)
{
    limpiar_datos();
    $("#dlg_new_hoja").dialog({
        autoOpen: false, modal: true, width: 1300, 
        show:{ effect: "explode", duration: 500},
        hide:{ effect: "explode", duration: 800}, resizable: false,
        title: "<div class='widget-header'><h4><span class='widget-icon'> <i class='fa fa-align-justify'></i> </span> Datos Generales de Carta</h4></div>"
        }).dialog('open');
        traer_carta($id);
}
function limpiar_datos()
{
    $("#dlg_nro_car,#dlg_contri_disable,#dlg_hoja_plazo,#dlg_hoja_insitu").val("");
}
function traer_carta($id)
{
    MensajeDialogLoadAjax('dlg_new_hoja', '.:: CARGANDO ...');
    $.ajax({url: 'carta_reque/'+$id,
        type: 'GET',
        success: function(r) 
        {
            $("#dlg_nro_car").val(r[0].nro_car);
            $("#dlg_contri_disable").val(r[0].contribuyente);
            jQuery("#table_predios_contri").jqGrid('setGridParam', {url: 'trae_pred_carta/'+$id}).trigger('reloadGrid');
            MensajeDialogLoadAjaxFinish('dlg_new_hoja');

        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            MensajeDialogLoadAjaxFinish('dlg_new_hoja');
            $("#dlg_new_hoja").dialog('close');
        }
        });
}
function fn_confirmar_hoja()
{
    if($("#dlg_hoja_plazo").val()==0||$("#dlg_hoja_plazo").val()=="")
    {
        mostraralertasconfoco("Ingresar dias de plazo a pagar","#dlg_hoja_plazo");
        return false;
    }
    if($("#dlg_hoja_insitu").val()=="")
    {
        mostraralertasconfoco("Ingresar Días en que se hicieron la Fiscalización in situ.","#dlg_hoja_insitu");
        return false;
    }

    $.SmartMessageBox({
            title : "Confirmación Final!",
            content : "Está por generar Hoja de Liquidacion para esta Contribuyente, Seguro que deseea Grabar la información?, esta no podra ser modificada...",
            buttons : '[Cancelar][Aceptar]'
    }, function(ButtonPressed) {
            if (ButtonPressed === "Aceptar") {

                    fn_save_hoja();
            }
            if (ButtonPressed === "Cancelar") {
                    $.smallBox({
                            title : "No se Guardo",
                            content : "<i class='fa fa-clock-o'></i> <i>Puede Corregir...</i>",
                            color : "#C46A69",
                            iconSmall : "fa fa-times fa-2x fadeInRight animated",
                            timeout : 3000
                    });
            }
    });
}
function fn_save_hoja()
{
    Id=$('#table_sel_cartas').jqGrid ('getGridParam', 'selrow');
    MensajeDialogLoadAjax('dlg_new_hoja', '.:: CARGANDO ...');
        $.ajax({url: 'hoja_liquidacion/create',
        type: 'GET',
        data:{car:Id,plazo:$("#dlg_hoja_plazo").val(),insitu:$("#dlg_hoja_insitu").val()},
        success: function(r) 
        {
            MensajeDialogLoadAjaxFinish('dlg_new_hoja');
            if(r==0)
            {
                mostraralertas("No se puede Generar Hoja de Liquidación, Falta completar fiscalización de predios");
            }
            if(r==-1)
            {
                MensajeAlerta("No se Puede Crear","La Carta Relacionada está Anulada...",4000);
            }
            if(r>0)
            {
                MensajeExito("Insertó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
                $("#dlg_new_hoja").dialog("close");
                $("#dlg_sel_carta").dialog("close");
                buscar_carta(0);
                verhoja(r);
            }
            
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dlg_new_hoja');
            console.log('error');
            console.log(data);
        }
        });
}
function verhoja(id)
{
    window.open('hoja_liq_rep/'+id);
}