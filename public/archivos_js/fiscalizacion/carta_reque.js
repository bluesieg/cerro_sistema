var inputglobal="";
function fn_bus_contrib_carta(input)
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
function fn_bus_contrib_list_carta(per)
{
    $("#"+inputglobal+"_hidden").val(per);
    $("#"+inputglobal).val($('#table_contrib').jqGrid('getCell',per,'contribuyente'));
    $("#"+inputglobal+"_doc").val($('#table_contrib').jqGrid('getCell',per,'nro_doc'));
    $("#"+inputglobal+"_dom").val($('#table_contrib').jqGrid('getCell',per,'dom_fiscal'));
    if(inputglobal=="dlg_contri")
    {
        call_list_contrib_carta(1);
    }
    $("#dlg_bus_contr").dialog("close");
}

function call_list_contrib_carta(tip)
{
    
    $("#table_cartas").jqGrid("clearGridData", true);
    if(tip==0)
    {
        jQuery("#table_cartas").jqGrid('setGridParam', {url: 'trae_cartas/'+$("#selantra").val()+'/0/0/0/0'}).trigger('reloadGrid');
    }
    if(tip==1)
    {
        jQuery("#table_cartas").jqGrid('setGridParam', {url: 'trae_cartas/'+$("#selantra").val()+'/'+$("#dlg_contri_hidden").val()+'/0/0/0'}).trigger('reloadGrid');
    }
    if(tip==2)
    {
        if($("#dlg_bus_fini").val()==""||$("#dlg_bus_ffin").val()=="")
        {
            mostraralertasconfoco("Ingresar Fechas de busqueda","dlg_bus_fini");
            return false;
        }
        ini=$("#dlg_bus_fini").val().replace(/\//g,"-");
        fin=$("#dlg_bus_ffin").val().replace(/\//g,"-");
        jQuery("#table_cartas").jqGrid('setGridParam', {url: 'trae_cartas/0/0/'+ini+'/'+fin+'/0'}).trigger('reloadGrid');
    }
    if(tip==3)
    {
        if($("#dlg_bus_num").val()=="")
        {
            mostraralertasconfoco("Ingresar Numero","#dlg_bus_num"); 
            return false;
        }
        ajustar(6,'dlg_bus_num')
        num=$("#dlg_bus_num").val();
        jQuery("#table_cartas").jqGrid('setGridParam', {url: 'trae_cartas/'+$("#selantra").val()+'/0/0/0/'+num}).trigger('reloadGrid');
    }
    
}
function fn_new_carta(id)
{
    limpiar_carta_req()
    $("#dlg_new_carta").dialog({
        autoOpen: false, modal: true, width: 1100, 
        show:{ effect: "explode", duration: 500},
        hide:{ effect: "explode", duration: 800}, resizable: false,
        title: "<div class='widget-header'><h4><span class='widget-icon'> <i class='fa fa-align-justify'></i> </span> Generar Nueva Carta de Requerimiento</h4></div>"
        }).dialog('open');
    if(id>0)
    {
        llenar_carta_mod(id);
    }
}
function llenar_carta_mod(id)
{
    $("#hidden_id_carta").val(id);
    MensajeDialogLoadAjax('dlg_new_carta', '.:: CARGANDO ...');
    $.ajax({url: 'carta_reque/'+id,
        type: 'GET',
        success: function(r) 
        {
            $("#dlg_contri_carta,#bus_dlg_contri_carta").prop('disabled', true);
            $("#dlg_contri_carta_hidden").val(r[0].id_contrib)
            $("#dlg_contri_carta_doc").val(r[0].pers_nro_doc);
            $("#dlg_contri_carta").val(r[0].contribuyente);
            $("#dlg_contri_carta_dom").val(r[0].ref_dom_fis);
            $("#dlg_fec_fis").val(r[0].fec_fis_ini);
            $("#dlg_hor_fis").val(r[0].hora_fis);
            $("#selanafis").val(r[0].anio_fis);
            if(r[0].soli_contra==1){$('#cbx_con').prop('checked', true);}
            else{$('#cbx_con').prop('checked', false);}
            if(r[0].soli_licen==1){$('#cbx_lic').prop('checked', true);}
            else{$('#cbx_lic').prop('checked', false);}
            if(r[0].soli_dercl==1){$('#cbx_der').prop('checked', true);}
            else{$('#cbx_der').prop('checked', false);}
            if(r[0].soli_otro==1){
                $('#cbx_otr').prop('checked', true);
                $('#dlg_otros').prop('disabled', false);
            }
            else{
                $('#cbx_otr').prop('checked', false);
                $('#dlg_otros').prop('disabled', true);
            }
            $('#dlg_otros').val(r[0].otro_text);
            if(r[0].flg_anu==1)
            {
                $("#div_anulado").show();
                $("#btn_save,#btn_mod,#btn_anular").hide();
                $("#btn_pon_fiscalizador").prop('disabled', true);
            }
            else
            {
                $("#btn_save").hide();
                $("#btn_mod,#btn_anular").show();
            }
            MensajeDialogLoadAjaxFinish('dlg_new_carta');
            llamar_get_fisca(id);
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            MensajeDialogLoadAjaxFinish('dlg_new_carta');
            $("#dlg_new_carta").dialog('close');
        }
        });
}

function fn_anular_carta()
{

    if($("#hidden_id_carta").val()==0)
    {
        mostraralertasconfoco("Seleccione Carta","#hidden_id_carta");
        return false;
    }
    $.SmartMessageBox({
            title : "<i class='glyphicon glyphicon-alert' style='color: yellow; margin-right: 20px; font-size: 1.5em;'></i> Confirmación Final!",
            content : "Seguro que desea Anular?... Al anular ya no podra Usar ni Modificar la Carta",
            buttons : '[Cancelar][Aceptar]'
    }, function(ButtonPressed) {
            if (ButtonPressed === "Aceptar") 
            {
                fn_anula_db();
            }
            if (ButtonPressed === "Cancelar") {
                    $.smallBox({
                            title : "No se Anuló",
                            content : "<i class='fa fa-clock-o'></i> <i>Puede Corregir...</i>",
                            color : "#C46A69",
                            iconSmall : "fa fa-times fa-2x fadeInRight animated",
                            timeout : 3000
                    });
            }
    });
}
function fn_anula_db()
{
    id=$("#hidden_id_carta").val();
     MensajeDialogLoadAjax('dlg_new_carta', '.:: Anulando ...');
    $.ajax({
        url: 'carta_anula',
        type: 'GET',
        data: {id:id},
        success: function(r) 
        {
            if(r==0)
            {
                MensajeAlerta("No se Puede Anular","ya existe una hoja de Liquidación relacionada...",4000);
            }
            else
            {
                MensajeExito("Se Anuló Correctamente","Su Registro Fue Anulado con Éxito...",4000);
            }
            call_list_contrib_carta(1);
            MensajeDialogLoadAjaxFinish('dlg_new_carta');
            $("#dlg_new_carta").dialog("close");
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dlg_new_carta');
            console.log('error');
            console.log(data);
        }
    });
}

function llamar_get_fisca(id)
{
   $('#table_fiscalizadores tbody tr').each(function() {$(this).remove();});
   MensajeDialogLoadAjax('table_fiscalizadores', '.:: CARGANDO ...');
    $.ajax({url: 'trae_fisca_carta/'+id,
        type: 'GET',
        success: function(r) 
        {
            for(i=0;i<(r.length);i++)
            {
                $('#table_fiscalizadores > tbody').append('<tr id="'+r[i].id_user_fis+'"><td style="border: 1px solid #bbb">'+r[i].id_user_fis+'</td>\n\
                                                           <td style="border: 1px solid #bbb">'+r[i].pers_nro_doc+'</td>\n\
                                                           <td style="border: 1px solid #bbb">'+r[i].fiscalizador+'</td>\n\
                                                           <td class="text-center" style="border: 1px solid #bbb"><i class="fa fa-close" style="color:red; cursor:pointer" onclick="del_fis_bd('+r[i].id_fis_env+','+id+')"></i></td></tr>');
            }
            MensajeDialogLoadAjaxFinish('table_fiscalizadores');
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            MensajeDialogLoadAjaxFinish('table_fiscalizadores');
            $("#dlg_new_carta").dialog('close');
        }
        }); 
}
function del_fis_bd(id,carta)
{
    MensajeDialogLoadAjax('table_fiscalizadores', '.:: Eliminando ...');
    $.ajax({
        url: 'fis_env_del',
        type: 'GET',
        data: {id:id},
        success: function(r) 
        {
            llamar_get_fisca(carta);            
            MensajeAlerta("Se Eliminó Correctamente","Su Registro Fue eliminado Correctamente...",4000)
            MensajeDialogLoadAjaxFinish('table_fiscalizadores');
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('table_fiscalizadores');
            console.log('error');
            console.log(data);
        }
    });
}
function limpiar_carta_req()
{
    
    $("#hidden_id_carta,#dlg_contri_carta_hidden").val(0);
    $("#btn_save").show();
    $("#btn_mod,#div_anulado,#btn_anular").hide();
    $("#dlg_contri_carta_doc,#dlg_contri_carta,#dlg_contri_carta_dom,#dlg_hor_fis,#dlg_otros").val("");
    $('#table_fiscalizadores tbody tr').each(function() {$(this).remove();});
    $('#cbx_con,#cbx_lic,#cbx_der').prop('checked', true);
    $('#cbx_otr').prop('checked', false);
    $('#dlg_otros').prop('disabled', true);
    $("#dlg_contri_carta,#bus_dlg_contri_carta,#btn_pon_fiscalizador").prop('disabled', false);
}
function validarotros()
{
    if($('#cbx_otr').is(':checked'))
    {
        $('#dlg_otros').prop('disabled', false);
        $("#dlg_otros").focus();
    }
    else
    {
        $('#dlg_otros').prop('disabled', true);
    }
}
function poner_fisca()
{
    if($("#selfisca").val()==0)
    {
        mostraralertasconfoco("Seleccione Fiscalizador","#selfisca");
        return false;
    }
    
    if ( $("#table_fiscalizadores tr#"+$("#selfisca").val()).length==0 ) {
        if($("#hidden_id_carta").val()==0)
        {
            $('#table_fiscalizadores > tbody').append('\
                    <tr id="'+$("#selfisca").val()+'"><td style="border: 1px solid #bbb">'+$("#selfisca").val()+'</td>\n\
                    <td style="border: 1px solid #bbb">'+$("#selfisca option:selected").attr("documento")+'</td>\n\
                    <td style="border: 1px solid #bbb">'+$("#selfisca option:selected").text()+'</td>\n\
                    <td class="text-center" style="border: 1px solid #bbb"><i class="fa fa-close" style="color:red; cursor:pointer" onclick="del_fis('+$("#selfisca").val()+')"></i></td></tr>');
        }
        else
        {
            poner_fisca_bd($("#hidden_id_carta").val(),$("#selfisca").val());
        }
    }
    
}
function poner_fisca_bd(car,fisca)
{
    MensajeDialogLoadAjax('dlg_new_carta', '.:: CARGANDO ...');
   $.ajax({url: 'carta_set_fisca',
    type: 'GET',
    data:{car:car,fis: fisca},
    success: function(data) 
    {
        MensajeDialogLoadAjaxFinish('dlg_new_carta');
        MensajeExito("Fiscalizador Insertado","Su Registro Fue Insertado con Éxito...",4000);
        llamar_get_fisca(car);   
    },
    error: function(data) {
        mostraralertas("no inserto Fiscalizador, Comunicar al Administrador");
        MensajeDialogLoadAjaxFinish('dlg_new_carta');
        console.log('error');
        console.log(data);
    }
    }); 
}
function del_fis(fis)
{
    $("#table_fiscalizadores tr#"+fis+"").remove();
}
function fn_confirmar_carta()
{

    if($("#dlg_contri_carta_hidden").val()==0||$("#dlg_contri_carta_hidden").val()=="")
    {
        mostraralertasconfoco("Seleccione Contribuyente a fiscalizar","#dlg_contri_carta");
        return false;
    }
    if($("#dlg_fec_fis").val()==0||$("#dlg_fec_fis").val()=="")
    {
        mostraralertasconfoco("Seleccione Fecha de Fizcalización","#dlg_fec_fis");
        return false;
    }
    if($("#dlg_hor_fis").val()==0||$("#dlg_hor_fis").val()=="")
    {
        mostraralertasconfoco("Seleccione Hora de Fizcalización","#dlg_hor_fis");
        return false;
    }
    if($('#table_fiscalizadores > tbody tr').length==0)
    {
        mostraralertasconfoco("Agregar Fizcalizadores","#selfisca");
        return false;
    }
    if($('#dlg_otros').prop('checked')&&$('#dlg_otros').val()==""){otro=1;}
    $.SmartMessageBox({
            title : "<i class='glyphicon glyphicon-alert' style='color: yellow; margin-right: 20px; font-size: 1.5em;'></i> Confirmación Final!",
            content : "Está por generar Carta de Requerimiento para este Contribuyente, desea Grabar la información?",
            buttons : '[Cancelar][Aceptar]'
    }, function(ButtonPressed) {
            if (ButtonPressed === "Aceptar") {
                    if($("#hidden_id_carta").val()==0)
                    {
                        fn_save_carta();
                    }
                    else
                    {
                        fn_mod_carta();
                    }
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
function fn_save_carta()
{
    con=0;lic=0;der=0;otro=0;otrotext="-"
    if($('#cbx_con').prop('checked')){con=1;}
    if($('#cbx_lic').prop('checked')){lic=1;}
    if($('#cbx_der').prop('checked')){der=1;}
    if($('#cbx_otr').prop('checked')){otro=1;otrotext=$("#dlg_otros").val()}
    
    MensajeDialogLoadAjax('dlg_new_carta', '.:: CARGANDO ...');
        $.ajax({url: 'carta_reque/create',
        type: 'GET',
        data:{contri:$("#dlg_contri_carta_hidden").val(),fec:$("#dlg_fec_fis").val(),hor:$("#dlg_hor_fis").val(),con:con,lic:lic,der:der,otro:otro,otrotext:otrotext,anfis:$("#selanafis").val()},
        success: function(r) 
        {
            if(r>0)
            {
                $('#table_fiscalizadores tbody tr').each(function() {
                    fisca=$(this).attr("id");
                    $.ajax({url: 'carta_set_fisca',
                    type: 'GET',
                    data:{car:r,fis: fisca},
                    success: function(data) 
                    {
                        MensajeExito("Fiscalizador Insertado","Su Registro Fue Insertado con Éxito...",4000);
                    },
                    error: function(data) {
                        mostraralertas("no inserto Fiscalizador, Comunicar al Administrador");
                        console.log('error');
                        console.log(data);
                    }
                    });
                });
            }
            MensajeExito("Insertó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            call_list_contrib_carta(1);
            MensajeDialogLoadAjaxFinish('dlg_new_carta');
            $("#dlg_new_carta").dialog("close");
            vercarta(r);
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dlg_new_carta');
            console.log('error');
            console.log(data);
        }
        });
}
function fn_mod_carta()
{
    con=0;lic=0;der=0;otro=0;otrotext="-"
    if($('#cbx_con').prop('checked')){con=1;}
    if($('#cbx_lic').prop('checked')){lic=1;}
    if($('#cbx_der').prop('checked')){der=1;}
    if($('#cbx_otr').prop('checked')){otro=1;otrotext=$("#dlg_otros").val()}
    id=$("#hidden_id_carta").val();
    MensajeDialogLoadAjax('dlg_new_carta', '.:: CARGANDO ...');
        $.ajax({url: 'carta_reque/'+id+'/edit',
        type: 'GET',
        data:{fec:$("#dlg_fec_fis").val(),hor:$("#dlg_hor_fis").val(),con:con,lic:lic,der:der,otro:otro,otrotext:otrotext,anfis:$("#selanafis").val()},
        success: function(r) 
        {
            MensajeExito("Se Modificó Correctamente","Su Registro Fue Cambiado con Éxito...",4000);
            call_list_contrib_carta(1);
            MensajeDialogLoadAjaxFinish('dlg_new_carta');
            $("#dlg_new_carta").dialog("close");
            vercarta(r);
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dlg_new_carta');
            console.log('error');
            console.log(data);
        }
        });
}
function vercarta(id)
{
    if($("#per_imp").val()==0)
    {
        sin_permiso();
        return false;
    }
    window.open('car_req_rep/'+id);
}
function ponerfechanoti(num)
{
    $("#input_num_op").val(num);
    $("#input_fec_notifica").val('');
    $("#dlg_fec_notificacion").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  Fecha de Notificación O.P.:.</h4></div>",
        buttons: [
            {
                id:"btnsave",
                html: '<span class="btn-label"><i class="glyphicon glyphicon-new-window"></i></span>Grabar Fecha',
                "class": "btn btn-labeled bg-color-green txt-color-white",
                click: function () {save_cr_fec_noti();}
            },
            {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-primary bg-color-red",
                click: function () {$(this).dialog("close");}
            }]
        }).dialog('open');
}
function save_cr_fec_noti()
{
    if($("#input_fec_notifica").val()=="")
    {
        mostraralertasconfoco("Ingresar Fecha de Notificación","#input_fec_notifica");
        return false;
    }
    if($("#per_new").val()==1||$("#per_edit").val()==1)
    {
        Id=$('#table_cartas').jqGrid ('getGridParam', 'selrow');
        MensajeDialogLoadAjax('dlg_fec_notificacion', '.:: CARGANDO ...');
       $.ajax({url: 'mod_noti_carta',
       type: 'GET',
       data:{id:Id,fec:$("#input_fec_notifica").val()},
       success: function(r) 
       {
           $('#table_cartas').trigger( 'reloadGrid' );
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