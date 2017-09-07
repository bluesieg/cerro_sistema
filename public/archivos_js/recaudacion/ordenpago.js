function callpredtab()
{
    $("#selmnza").html('');
    MensajeDialogLoadAjax('dvselmnza', '.:: CARGANDO ...');
    $.ajax({url: 'selmzna?sec='+$("#selsec").val(),
    type: 'GET',
    success: function(r) 
    {
        $(r).each(function(i, v){ // indice, valor
            $("#selmnza").append('<option value="' + v.id_mzna + '">' + v.codi_mzna + '</option>');
        })
        MensajeDialogLoadAjaxFinish('dvselmnza');
    },
    error: function(data) {
        console.log('error');
        console.log(data);
    }
    });
}
function fn_bus_contrib()
{
    if($("#dlg_contri").val()=="")
    {
        mostraralertasconfoco("Ingresar Información del Contribuyente para busqueda","#dlg_contri"); 
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
    call_list_contrib(1);
    $("#dlg_bus_contr").dialog("close");
}


function traer_contri_cod(input, doc) {
    MensajeDialogLoadAjax(input, '.:: Cargando ...');
    $.ajax({
        url: 'autocomplete_contrib?doc=0&cod=' + doc,
        type: 'GET',
        success: function (data) {
            if (data.msg == 'si') {
                $("#" + input + "_hidden").val(data.id_pers);
                $("#" + input).val(data.contribuyente);
                call_list_contrib(1);
                
            } else {
                $("#" + input + "_hidden").val(0);
                $("#" + input).val("");
                $("#table_op").jqGrid("clearGridData", true);
                mostraralertas('* El Documento Ingresado no Existe, registre al contribuyente o intente con otro número ... !');
            }
            MensajeDialogLoadAjaxFinish(input);

        },
        error: function (data) {
            mostraralertas('* Error Interno !  Comuniquese con el Administrador...');
            MensajeDialogLoadAjaxFinish(input);
        }
    });
}
function call_list_contrib(tip)
{
    
    $("#table_op").jqGrid("clearGridData", true);
    if(tip==1)
    {
        jQuery("#table_op").jqGrid('setGridParam', {url: 'obtiene_op/'+$("#dlg_contri_hidden").val()+'/0/0/'+$("#selantra").val()+'/0/0'}).trigger('reloadGrid');
    }
    if(tip==2)
    {
        jQuery("#table_op").jqGrid('setGridParam', {url: 'obtiene_op/0/'+$("#selsec option:selected").text()+'/'+$("#selmnza option:selected").text()+'/'+$("#selantra").val()+'/0/0'}).trigger('reloadGrid');
    }
    if(tip==3)
    {
        if($("#dlg_bus_fini").val()==""||$("#dlg_bus_ffin").val()=="")
        {
            mostraralertasconfoco("Ingresar Fechas","#dlg_bus_fini"); 
            return false;
        } 
        ini=$("#dlg_bus_fini").val().replace(/\//g,"-");
        fin=$("#dlg_bus_ffin").val().replace(/\//g,"-");
        jQuery("#table_op").jqGrid('setGridParam', {url: 'obtiene_op/0/0/0/0/'+ini+'/'+fin}).trigger('reloadGrid');
    }
}
function generar_op(tip,ctb)
{
    Id_contrib=$("#dlg_contri_hidden").val();
    if(Id_contrib==0&&tip==1)
    {
        mostraralertas("No hay Contribuyente seleccionado para generar");
        return false;
    }
    if(tip==3||tip==4)
    {
       Id_contrib=ctb;
    }
    
    sec=$("#selsec option:selected").text();
    man=$("#selmnza option:selected").text();
    if(tip==1||tip==3||tip==4)
    {
        if(tip==1)
        {
            $("body").block({
            message: "<p class='ClassMsgBlock'><img src='"+getServidorUrl()+"img/cargando.gif' style='width: 18px;position: relative;top: -1px;'/>Generando</p>",
            css: { border: '2px solid #006000',background:'white',width: '62%'}
            });
        }
        if(tip==3||tip==4)
        {
            MensajeDialogLoadAjax("dlg_ctrb_sector", '.:: Cargando ...');

        }
        $.ajax({url: 'ordenpago/create',
        type: 'GET',
        data:{per:Id_contrib,sec:sec,man:man,tip:tip},
        success: function(r) 
        {
            window.open('fis_rep/'+tip+'/'+r+'/'+sec+'/'+man);
            MensajeExito("Insertó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            $('body').unblock();
            MensajeDialogLoadAjaxFinish("dlg_ctrb_sector");
            call_list_contrib(tip);
        },
        error: function(data) {
            MensajeAlerta("hubo un error, Comunicar al Administrador","",4000);
            $('body').unblock();
            console.log('error');
            console.log(data);
        }
        });
    }
    if(tip==2)
    {
        jQuery("#table_contrib_bysec").jqGrid('setGridParam', {url: 'obtiene_con_sec?sec='+sec+'&man='+man+"&an="+$("#selantra").val()}).trigger('reloadGrid');
        $("#dlg_ctrb_sector").dialog({
        autoOpen: false, modal: true, width: 700, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  Contribuyente por sector :.</h4></div>"       
        }).dialog('open');
    }
    if(tip==5)
    {
        if($("#dlg_bus_fini").val()==""||$("#dlg_bus_ffin").val()=="")
        {
            mostraralertasconfoco("Ingresar Fechas","#dlg_bus_fini"); 
            return false;
        } 
        ini=$("#dlg_bus_fini").val().replace(/\//g,"-");
        fin=$("#dlg_bus_ffin").val().replace(/\//g,"-");
        window.open('fis_rep/'+tip+'/0/'+ini+'/'+fin);
    }
}
function verop(idop)
{
    Id=$('#table_op').jqGrid ('getGridParam', 'selrow');
    if(Id==null&&tip==1)
    {
        mostraralertas("No hay Contribuyente seleccionado para impresión");
        return false;
    }
    //MensajeDialogLoadAjax('widget-grid', '.:: Generando ...');
    sec=$("#selsec option:selected").text();
    man=$("#selmnza option:selected").text();
    window.open('fis_rep/1/'+idop+'/'+sec+'/'+man);
}