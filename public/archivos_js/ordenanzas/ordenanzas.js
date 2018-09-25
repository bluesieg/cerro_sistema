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
function fn_new()
{
    limpiar('dlg_new_ordenanza');
    $("#dlg_new_ordenanza").dialog({
        autoOpen: false, modal: true, width: 1100, 
        show:{ effect: "explode", duration: 500},
        hide:{ effect: "explode", duration: 800}, resizable: false,
        title: "<div class='widget-header'><h4><span class='widget-icon'> <i class='fa fa-align-justify'></i> </span> Registrar Nueva Ordenaza</h4></div>"
        }).dialog('open');
}

function fn_confirmar()
{
    $.SmartMessageBox({
            title : "Confirmación Final!",
            content : "Está por generar impuesto de Alcabala para este Contribuyente, desea Grabar la información",
            buttons : '[Cancelar][Aceptar]'
    }, function(ButtonPressed) {
            if (ButtonPressed === "Aceptar") {

                    fn_save_alcab();
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
function fn_save_alcab()
{
    MensajeDialogLoadAjax('dlg_new_alcabala', '.:: CARGANDO ...');
   $.ajax({url: 'alcabala/create',
        type: 'GET',
        data:{pred:$("#selpredios").val(),adqui:$("#dlg_adquire_hidden").val(),adqui_rl:$("#dlg_adquire_rep").val(),
              trans:$("#dlg_trans_hidden").val(),trans_rl:$("#dlg_trans_rep").val(),
          contra:$("#selcontrato").val(),doctrans:$("#selcontrato").val(),fectrans:$("#dlg_fec_trans").val(),
            notaria:$("#dlg_notaria").val(),bimpo:$("#dlg_autovaluo").val().replace(/,/g,""),
            vtrans:$("#dlg_pre_trans").val().replace(/,/g,""),poradq:$("#dlg_por_aplicado").val().replace(/,/g,""),
        bafecta:$("#dlg_fin_afecta").val().replace(/,/g,""),imp_tot:$("#dlg_fin_pagar").val().replace(/,/g,""),tip_camb:$("#dlg_tip_cam").val().replace(/,/g,""),
        id_tip_camb:$("#selmonedas").val(),inafec:$("#selinafec").val()},
        success: function(r) 
        {
            
            MensajeExito("Insertó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            MensajeDialogLoadAjaxFinish("dlg_new_alcabala");
            $("#dlg_new_alcabala").dialog("close");
            veralcab(r)
            fn_bus_ani(0,0)
        },
        error: function(data) {
            MensajeAlerta("hubo un error, Comunicar al Administrador","",4000);
            MensajeDialogLoadAjaxFinish("dlg_new_alcabala");
            console.log('error');
            console.log(data);
        }
        });
}
function veralcab(r)
{
    if($("#per_imp").val()==0)
    {
        sin_permiso();
        return false;
    }
    window.open('alcab_rep/'+r);
}