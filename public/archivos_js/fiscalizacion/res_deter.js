var inputglobal="";
function fn_bus_contrib_rd(input)
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
function fn_bus_lis_rd(per)
{
    $("#"+inputglobal+"_hidden").val(per);
    $("#"+inputglobal).val($('#table_contrib').jqGrid('getCell',per,'contribuyente'));
    if(inputglobal=="dlg_contri")
    {
        buscar_rd(1);
    }
    if(inputglobal=="dlg_contri_hoja")
    {
        buscar_rd(2);
    }
    $("#dlg_bus_contr").dialog("close");
}

function buscar_rd(tip)
{
    if(tip==0)
    {
        $("#table_rd").jqGrid("clearGridData", true);
        jQuery("#table_rd").jqGrid('setGridParam', {url: 'trae_rd/'+$("#selantra").val()+'/0/0/0/0'}).trigger('reloadGrid');
    }
    if(tip==1)
    {
        $("#table_rd").jqGrid("clearGridData", true);
        jQuery("#table_rd").jqGrid('setGridParam', {url: 'trae_rd/'+$("#selantra").val()+'/'+$("#dlg_contri_hidden").val()+'/0/0/0'}).trigger('reloadGrid');
    }
    if(tip==2)
    {
        $("#table_sel_hojas").jqGrid("clearGridData", true);
        jQuery("#table_sel_hojas").jqGrid('setGridParam', {url: 'trae_hojas_liq/'+$("#selantra").val()+'/'+$("#dlg_contri_carta_hidden").val()+'/0/0/0'}).trigger('reloadGrid');
    }
    if(tip==3)
    {
        if($("#dlg_bus_num_hoja").val()=="")
        {
            mostraralertasconfoco("Ingresar Numero","#dlg_bus_num_carta"); 
            return false;
        }
        ajustar(6,'dlg_bus_num_hoja')
        num=$("#dlg_bus_num_hoja").val();
        jQuery("#table_sel_hojas").jqGrid('setGridParam', {url: 'trae_hojas_liq/'+$("#selantra").val()+'/0/0/0/'+num}).trigger('reloadGrid');
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
        $("#table_rd").jqGrid("clearGridData", true);
        jQuery("#table_rd").jqGrid('setGridParam', {url: 'trae_rd/'+$("#selantra").val()+'/0/0/0/'+num}).trigger('reloadGrid');
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
        $("#table_rd").jqGrid("clearGridData", true);
        jQuery("#table_rd").jqGrid('setGridParam', {url: 'trae_rd/0/0/'+ini+'/'+fin+'/0'}).trigger('reloadGrid');
    }
}
function fn_sel_hoja()
{
    $("#dlg_sel_hoja").dialog({
        autoOpen: false, modal: true, width: 1100, 
        show:{ effect: "explode", duration: 500},
        hide:{ effect: "explode", duration: 800}, resizable: false,
        title: "<div class='widget-header'><h4><span class='widget-icon'> <i class='fa fa-align-justify'></i> </span> Crear Resolución de Determinación</h4></div>"
        }).dialog('open');
        jQuery("#table_sel_hojas").jqGrid('setGridParam', {url: 'trae_hojas_liq/'+$("#selantra").val()+'/0/0/0/0'}).trigger('reloadGrid');
}


function valida_rd(id)
{
   if($("#per_new").val()==0)
   {
        sin_permiso();
        return false;
   }
   
    $.SmartMessageBox({
            title : "Confirmación Final!",
            content : "Está por generar la Resolución de Determinación para este Contribuyente, Seguro que deseea Grabar la información?, esta no podra ser modificada...",
            buttons : '[Cancelar][Aceptar]'
    }, function(ButtonPressed) {
            if (ButtonPressed === "Aceptar") {

                    fn_save_rd(id);
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
function fn_save_rd(id)
{
    MensajeDialogLoadAjax('dlg_sel_hoja', '.:: CARGANDO ...');
        $.ajax({url: 'reso_deter/create',
        type: 'GET',
        data:{hoja:id},
        success: function(r) 
        {
            MensajeDialogLoadAjaxFinish('dlg_sel_hoja');
            MensajeExito("Insertó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            $("#dlg_sel_hoja").dialog("close");
            buscar_rd(0);
            verrd(r);
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dlg_sel_hoja');
            console.log('error');
            console.log(data);
        }
        });
}
function verrd(id)
{
    if($("#per_imp").val()==0)
    {
        sin_permiso();
        return false;
    }
    window.open('rd_rep/'+id);
}
function ponerfechanoti(num)
{
    $("#input_num_rd").val(num);
    $("#input_fec_notifica").val('');
    $("#dlg_fec_notificacion").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  Fecha de Notificación Resolución de Determinación :.</h4></div>",
        buttons: [
            {
                id:"btnsave",
                html: '<span class="btn-label"><i class="glyphicon glyphicon-new-window"></i></span>Grabar Fecha',
                "class": "btn btn-labeled bg-color-green txt-color-white",
                click: function () {save_rd_fec_noti();}
            },
            {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-primary bg-color-red",
                click: function () {$(this).dialog("close");}
            }]
        }).dialog('open');
}

function save_rd_fec_noti()
{
    if($("#input_fec_notifica").val()=="")
    {
        mostraralertasconfoco("Ingresar Fecha de Notificación","#input_fec_notifica");
        return false;
    }
    if($("#per_new").val()==1||$("#per_edit").val()==1)
    {
        Id=$('#table_rd').jqGrid ('getGridParam', 'selrow');
        MensajeDialogLoadAjax('dlg_fec_notificacion', '.:: CARGANDO ...');
       $.ajax({url: 'mod_noti_rd',
       type: 'GET',
       data:{id:Id,fec:$("#input_fec_notifica").val()},
       success: function(r) 
       {
           $('#table_rd').trigger( 'reloadGrid' );
           $("#dlg_fec_notificacion").dialog('close');
           MensajeExito("Modificó Correctamente","Su Registro Fue Modificado con Éxito...",4000);
           MensajeDialogLoadAjaxFinish('dlg_fec_notificacion');
       },
       error: function(data) {
           MensajeAlerta("No Modificó Correctamente","Contacte con el Administrador..",4000);
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