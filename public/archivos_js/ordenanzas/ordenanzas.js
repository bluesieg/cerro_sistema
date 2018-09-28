function limpiar(dialogo)
{
    $("#"+dialogo+" input[type='text']").val("");
    $("#"+dialogo+" input[type='checkbox']").prop( "checked", false );
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
    $("#table_ordenanzas_predial").jqGrid("clearGridData", true);
    $("#table_ordenanzas_arbitrios").jqGrid("clearGridData", true);
    crear_dlg('dlg_new_ordenanza',1100,'Registrar Nueva Ordenaza');
    if(tip==1)
    {
        $("#dlg_orde_hidden").val(0);
        $("#btn_save_div").show();
        $("#btn_mod_div").hide();
    }
    if(tip==2)
    {
        Id=$('#table_ordenanzas').jqGrid ('getGridParam', 'selrow');
        if(Id)
        {
            fn_get_ordenanza(Id);
            $("#btn_save_div").hide();
            $("#btn_mod_div").show();
        }
        else
        {
            mostraralertasconfoco("No hay Ordenanza Seleccinada","#table_ordenanzas");
            return false;
        }
    }
    
}
function fn_get_ordenanza(Id)
{
    MensajeDialogLoadAjax('dlg_new_ordenanza', '.:: CARGANDO ...');
   $.ajax({url: 'ordenanzas/'+Id,
        type: 'GET',
        success: function(r) 
        {
            $("#dlg_orde_hidden").val(r[0].id_orde);
            $("#dlg_orde_refe").val(r[0].refe_orde);
            $("#dlg_fec_ini").val(r[0].fec_ini);
            $("#dlg_fec_fin").val(r[0].fec_fin);
            $("#dlg_glosa_orde").val(r[0].glosa);
            r[0].flg_act==1?($("#cbx_act_orde").prop( "checked", true )) : ($("#cbx_act_orde").prop( "checked", false ));
            MensajeDialogLoadAjaxFinish("dlg_new_ordenanza");
            fn_actualizar_grilla('table_ordenanzas_predial', 'ordenanzas/0?grid=ordenanzas_predial&id='+Id);
            fn_actualizar_grilla('table_ordenanzas_arbitrios', 'ordenanzas/0?grid=ordenanzas_arbitrios&id='+Id);

        },
        error: function(data) {
            MensajeAlerta("hubo un error, Comunicar al Administrador","",4000);
            MensajeDialogLoadAjaxFinish("dlg_new_ordenanza");
            console.log('error');
            console.log(data);
        }
        });
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

function save_ordenanza(tip)
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
                    
                    fn_save_ordenanza(tip);
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
function fn_save_ordenanza(tip)
{
    MensajeDialogLoadAjax('dlg_new_ordenanza', '.:: CARGANDO ...');
    if(tip==1)
    {
        url='ordenanzas/create?tipo=ordenanza';
    }
    if(tip==2)
    {
        url='ordenanzas/'+$("#dlg_orde_hidden").val()+'/edit?tipo=ordenanza';
    }
   $.ajax({url: url,
        type: 'GET',
        data:{refe:$("#dlg_orde_refe").val(),
            fec_ini:$("#dlg_fec_ini").val(),
            fec_fin:$("#dlg_fec_fin").val(),
            glosa:$("#dlg_glosa_orde").val(),
            activo:$("#cbx_act_orde").is(':checked')?1:0
        },
        success: function(r) 
        {
            $("#btn_save_div").hide();
            $("#btn_mod_div").show();
            $("#dlg_orde_hidden").val(r);
            fn_actualizar_grilla('table_ordenanzas', 'ordenanzas/0?grid=ordenanzas');
            MensajeExito("Guardó Correctamente","Su Registro Fue Guardado con Éxito...",4000);
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
            fn_actualizar_grilla('table_ordenanzas_predial', 'ordenanzas/0?grid=ordenanzas_predial&id='+$("#dlg_orde_hidden").val());
            MensajeExito("Insertó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            MensajeDialogLoadAjaxFinish("dlg_new_ordenanza_predial");
            $("#dlg_new_ordenanza_predial").dialog('close');
         
        },
        error: function(data) {
            MensajeAlerta("hubo un error, Comunicar al Administrador","",4000);
            MensajeDialogLoadAjaxFinish("dlg_new_ordenanza_predial");
            console.log('error');
            console.log(data);
        }
        });
}
function activar_orde(esto)
{
    if($("#dlg_orde_hidden").val()==0)
    {
        return false;
    }
    MensajeDialogLoadAjax('dlg_new_ordenanza', '.:: CARGANDO ...');
   $.ajax({url: 'ordenanzas/'+$("#dlg_orde_hidden").val()+'/edit?tipo=activa_ordenanza',
        type: 'GET',
        data:{activo:$(esto).is(':checked')?1:0},
        success: function(r) 
        {
           
            fn_actualizar_grilla('table_ordenanzas', 'ordenanzas/0?grid=ordenanzas');
            MensajeExito("Guardó Correctamente","Su Registro Fue Guardado con Éxito...",4000);
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

/////////////arbitrios

function new_desc_arb()
{
    if($("#dlg_orde_hidden").val()==0)
    {
        mostraralertasconfoco('Grabar Ordenaza Primero',"#dlg_orde_hidden");
        return false;
    }
    limpiar('dlg_new_ordenanza_arbitrios');
     $("#dlg_new_ordenanza_arbitrios input[type='checkbox']").prop( "checked", true );
    $("#dlg_new_ordenanza_arbitrios").dialog({
    autoOpen: false, modal: true, width: 800, show: {effect: "fade", duration: 300}, resizable: false,
    title: "<div class='widget-header'><h4>.: Registrar Descuento Arbitrios :.</h4></div>",
    buttons: [{
                html: "<i class='fa fa-sign-out'></i>&nbsp; Grabar",
                "class": "btn btn-primary bg-color-green",
                click: function () {save_desc_arb();}
            },
            {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-primary bg-color-red",
                click: function () {$(this).dialog("close");}
            }]
    }).dialog('open');
}
function save_desc_arb()
{
    if($("#dlg_desc_arb_nat").val()=="")
    {
        mostraralertasconfoco('Ingresar Descuento Arbitrios Persona Natural',"#dlg_desc_arb_nat");
        return false;
    }
    if($("#dlg_desc_arb_jur").val()=="")
    {
        mostraralertasconfoco('Ingresar Descuento Arbitrios Persona Juridica',"#dlg_desc_arb_jur");
        return false;
    }
    if($("#dlg_desc_iarb_nat").val()=="")
    {
        mostraralertasconfoco('Ingresar Descuento Impuesto de Arbitrios Persona Natural',"#dlg_desc_iarb_nat");
        return false;
    }
   
    if($("#dlg_desc_iarb_jur").val()=="")
    {
        mostraralertasconfoco('Ingresar Descuento Impuesto de Arbitrios Persona Juridica',"#dlg_desc_iarb_jur");
        return false;
    }
   
    MensajeDialogLoadAjax('dlg_new_ordenanza_arbitrios', '.:: CARGANDO ...');
   $.ajax({url: 'ordenanzas/create?tipo=arbitrios',
        type: 'GET',
        data:{
            porcent_desc_arb_nat:$("#dlg_desc_arb_nat").val(),
            porcent_desc_arb_jur:$("#dlg_desc_arb_jur").val(),
            porcent_desc_ia_nat:$("#dlg_desc_iarb_nat").val(),
            porcent_desc_ia_jur:$("#dlg_desc_iarb_jur").val(),
            flg_barrido:$("#cbx_barrido_arb").is(':checked')?1:0,
            flg_recojo:$("#cbx_recojo_arb").is(':checked')?1:0,
            flg_seguridad:$("#cbx_seguridad_arb").is(':checked')?1:0,
            flg_parques:$("#cbx_parques_arb").is(':checked')?1:0,
            id_cond_arb:$("#sel_condicion_arb").val(),
            id_orde:$("#dlg_orde_hidden").val(),
        },
        success: function(r) 
        {
            fn_actualizar_grilla('table_ordenanzas_arbitrios', 'ordenanzas/0?grid=ordenanzas_arbitrios&id='+$("#dlg_orde_hidden").val());
            MensajeExito("Insertó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            MensajeDialogLoadAjaxFinish("dlg_new_ordenanza_arbitrios");
            $("#dlg_new_ordenanza_arbitrios").dialog('close');
         
        },
        error: function(data) {
            MensajeAlerta("hubo un error, Comunicar al Administrador","",4000);
            MensajeDialogLoadAjaxFinish("dlg_new_ordenanza_predial");
            console.log('error');
            console.log(data);
        }
        });
}