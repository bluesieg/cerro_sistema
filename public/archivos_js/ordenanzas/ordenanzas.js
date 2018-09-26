function limpiar(dialogo)
{
    $("#"+dialogo+" input[type='text']").val("");
    $("#"+dialogo+" input[type='hidden']").val(0);
    $("#"+dialogo+" textarea").val("");
}
function fn_bus_ani(id,tip)
{
    num=0;ini=0;fin=0;
    if(tip==3)
    {
        if($("#dlg_bus_num").val()=="")
        {
            mostraralertasconfoco("Ingresar Numero","#dlg_bus_num"); 
            return false;
        }
        ajustar(6,'dlg_bus_num')
        num=$("#dlg_bus_num").val();
    }
    if(tip==4)
    {
       if($("#dlg_bus_fini").val()==""||$("#dlg_bus_ffin").val()=="")
        {
            mostraralertasconfoco("Ingresar Fechas","#dlg_bus_fini"); 
            return false;
        } 
        ini=$("#dlg_bus_fini").val().replace(/\//g,"-");
        fin=$("#dlg_bus_ffin").val().replace(/\//g,"-");
    }
    jQuery("#table_alcab").jqGrid('setGridParam', {url: 'trae_acabala/'+$("#selan").val()+'/'+id+'/'+tip+'/'+num+'/'+ini+'/'+fin}).trigger('reloadGrid');
}
function fn_new(tip)
{
    limpiar('dlg_new_ordenanza');
    if(tip==1)
    {
        $("#dlg_orde_hidden").val(0);
        $("#btn_save_div").show();
        $("#btn_mod_div").hide();
    }
    if(tip==2)
    {
        $("#btn_save_div").hide();
        $("#btn_mod_div").show();
    }
    crear_dlg('dlg_new_ordenanza',1100,'Registrar Nueva Ordenaza');
}
function new_desc_pred()
{
    if($("#dlg_orde_hidden").val()==0)
    {
        mostraralertasconfoco('Grabar Ordenaza Primero',"#dlg_orde_hidden");
        return false;
    }
    limpiar('dlg_new_ordenanza_predial');
    $("#dlg_new_ordenanza_predial").dialog({
    autoOpen: false, modal: true, width: 800, show: {effect: "fade", duration: 300}, resizable: false,
    title: "<div class='widget-header'><h4>.: Registrar Descuento Predial :.</h4></div>",
    buttons: [{
                html: "<i class='fa fa-sign-out'></i>&nbsp; Grabar",
                "class": "btn btn-primary bg-color-green",
                click: function () {save_desc_pred();}
            },
            {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-primary bg-color-red",
                click: function () {$(this).dialog("close");}
            }]
    }).dialog('open');
}

function save_ordenanza()
{
    if($("#dlg_orde_refe").val()=="")
    {
        mostraralertasconfoco('Ingresar Referencia',"#dlg_orde_refe");
        return false;
    }
    if($("#dlg_fec_ini").val()=="")
    {
        mostraralertasconfoco('Ingresar fecha inicio',"#dlg_fec_ini");
        return false;
    }
    if($("#dlg_fec_fin").val()=="")
    {
        mostraralertasconfoco('Ingresar fecha fin',"#dlg_fec_fin");
        return false;
    }
    $.SmartMessageBox({
            title : "Confirmación Final!",
            content : "Está por generar una Nueva Ordenanza, desea Grabar la información",
            buttons : '[Cancelar][Aceptar]'
    }, function(ButtonPressed) {
            if (ButtonPressed === "Aceptar") {

                    fn_save_ordenanza();
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
function fn_save_ordenanza()
{
    MensajeDialogLoadAjax('dlg_new_ordenanza', '.:: CARGANDO ...');
   $.ajax({url: 'ordenanzas/create?tipo=ordenanza',
        type: 'GET',
        data:{refe:$("#dlg_orde_refe").val(),
            fec_ini:$("#dlg_fec_ini").val(),
            fec_fin:$("#dlg_fec_fin").val(),
            glosa:$("#dlg_glosa_orde").val()
        },
        success: function(r) 
        {
            $("#btn_save_div").hide();
            $("#btn_mod_div").show();
            $("#dlg_orde_hidden").val(r);
            MensajeExito("Insertó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            MensajeDialogLoadAjaxFinish("dlg_new_ordenanza");
         
        },
        error: function(data) {
            MensajeAlerta("hubo un error, Comunicar al Administrador","",4000);
            MensajeDialogLoadAjaxFinish("dlg_new_ordenanza");
            console.log('error');
            console.log(data);
        }
        });
}
function save_desc_pred()
{
    if($("#dlg_desc_ip_nat").val()=="")
    {
        mostraralertasconfoco('Ingresar Descuento Impuesto Predial Persona Natural',"#dlg_desc_ip_nat");
        return false;
    }
    if($("#dlg_desc_ip_jur").val()=="")
    {
        mostraralertasconfoco('Ingresar Descuento Impuesto Predial Persona Juridica',"#dlg_desc_ip_jur");
        return false;
    }
    if($("#dlg_desc_multa_nat").val()=="")
    {
        mostraralertasconfoco('Ingresar Monto de Multa',"#dlg_desc_multa_nat");
        return false;
    }
    if($("#dlg_desc_multa_jur").val()=="")
    {
        mostraralertasconfoco('Ingresar Monto de Multa',"#dlg_desc_multa_jur");
        return false;
    }
    if($("#dlg_desc_im_nat").val()=="")
    {
        mostraralertasconfoco('Ingresar Descuento Impuesto de Multa Perona natural',"#dlg_desc_im_nat");
        return false;
    }
    if($("#dlg_desc_im_jur").val()=="")
    {
        mostraralertasconfoco('Ingresar Descuento Impuesto de Multa Perona Juridica',"#dlg_desc_im_jur");
        return false;
    }
    MensajeDialogLoadAjax('dlg_new_ordenanza_predial', '.:: CARGANDO ...');
   $.ajax({url: 'ordenanzas/create?tipo=predial',
        type: 'GET',
        data:{
            porcent_desc_ip_nat:$("#dlg_desc_ip_nat").val(),
            pocent_desc_ip_jur:$("#dlg_desc_ip_jur").val(),
            monto_multa_nat:$("#dlg_desc_multa_nat").val(),
            monto_multa_jur:$("#dlg_desc_multa_jur").val(),
            pocent_desc_im_nat:$("#dlg_desc_im_nat").val(),
            porcent_desc_im_jur:$("#dlg_desc_im_jur").val(),
            anio_ini:$("#anio_ini").val(),
            anio_fin:$("#anio_fin").val(),
            id_orde:$("#dlg_orde_hidden").val(),
        },
        success: function(r) 
        {
            $("#dlg_new_ordenanza_predial").dialog('close');
            MensajeExito("Insertó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            MensajeDialogLoadAjaxFinish("dlg_new_ordenanza_predial");
         
        },
        error: function(data) {
            MensajeAlerta("hubo un error, Comunicar al Administrador","",4000);
            MensajeDialogLoadAjaxFinish("dlg_new_ordenanza_predial");
            console.log('error');
            console.log(data);
        }
        });
}
