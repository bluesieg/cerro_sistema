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

function ing_fec_noti(id,num,fec)
{
    $("#input_num_op").val(num);
    $("#input_fec_notifica").val(fec);
    $("#dlg_fec_notificacion").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  Fecha de Notificación O.P.:.</h4></div>",
        buttons: [
            {
                id:"btnsave",
                html: '<span class="btn-label"><i class="glyphicon glyphicon-new-window"></i></span>Grabar Documento',
                "class": "btn btn-labeled bg-color-green txt-color-white",
                click: function () {save_op_fec_noti();}
            },
            {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-primary bg-color-red",
                click: function () {$(this).dialog("close");}
            }]
        }).dialog('open');
}
function save_op_fec_noti()
{
    if($("#input_fec_notifica").val()=="")
    {
        mostraralertasconfoco("Ingresar Fecha de Notificación","#input_fec_notifica");
        return false;
    }
    if($("#per_new").val()==1||$("#per_edit").val()==1)
    {
         Id=$('#table_op').jqGrid ('getGridParam', 'selrow');
        MensajeDialogLoadAjax('dlg_fec_notificacion', '.:: CARGANDO ...');
       $.ajax({url: 'mod_noti_op',
       type: 'GET',
       data:{id:Id,fec:$("#input_fec_notifica").val()},
       success: function(r) 
       {
           $('#table_op').trigger( 'reloadGrid' );
           $("#dlg_fec_notificacion").dialog('close');
           MensajeExito("Modificó Correctamente","Su Registro Fue Modificado con Éxito...",4000);
           MensajeDialogLoadAjaxFinish('dlg_fec_notificacion');
       },
       error: function(data) {
           MensajeDialogLoadAjaxFinish('dlg_fec_notificacion');
           console.log('error');
           console.log(data);
       }
       }); 
    }
    else
    {
        sin_permiso();
    }

}