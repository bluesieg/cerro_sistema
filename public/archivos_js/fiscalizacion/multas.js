var inputglobal="";
function fn_bus_contrib_multa(input)
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
function fn_bus_contrib_list_multa(per)
{
    $("#"+inputglobal+"_hidden").val(per);
    $("#"+inputglobal).val($('#table_contrib').jqGrid('getCell',per,'contribuyente'));
    $("#"+inputglobal+"_doc").val($('#table_contrib').jqGrid('getCell',per,'nro_doc'));
    $("#"+inputglobal+"_dom").val($('#table_contrib').jqGrid('getCell',per,'dom_fiscal'));
    if(inputglobal=="dlg_contri")
    {
        call_list_contrib_multa(1);
    }
    $("#dlg_bus_contr").dialog("close");
}
function fn_selecciona_multa_criterio(id)
{
    $("#sel_multa_registrada_hidden").val(id);
    $("#sel_multa_registrada").val($('#table_multas_criterios').jqGrid('getCell',id,'des_multa'));
    $("#costo_multa_registrada").val($('#table_multas_criterios').jqGrid('getCell',id,'cos_multa'));
    $("#buscar_multa").dialog("close");
}

function call_list_contrib_multa(tip)
{
    
    $("#table_multas").jqGrid("clearGridData", true);
    if(tip==0)
    {
        jQuery("#table_multas").jqGrid('setGridParam', {url: 'trae_multas/'+$("#selantra").val()+'/0/0/0/0'}).trigger('reloadGrid');
    }
    if(tip==1)
    {
        jQuery("#table_multas").jqGrid('setGridParam', {url: 'trae_multas/'+$("#selantra").val()+'/'+$("#dlg_contri_hidden").val()+'/0/0/0'}).trigger('reloadGrid');
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
        jQuery("#table_multas").jqGrid('setGridParam', {url: 'trae_multas/0/0/'+ini+'/'+fin+'/0'}).trigger('reloadGrid');
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
        jQuery("#table_multas").jqGrid('setGridParam', {url: 'trae_multas/'+$("#selantra").val()+'/0/0/0/'+num}).trigger('reloadGrid');
    }
    
}
function new_multa()
{
    
    limpiar_nueva_multa()
    $("#dlg_new_multa").dialog({
        autoOpen: false, modal: true, width: 600, 
        resizable: false,
        title: "<div class='widget-header'><h4><span class='widget-icon'> <i class='fa fa-align-justify'></i> </span> Crear Criterio de Multa</h4></div>",
        buttons: [
            {
                id:"btnsave",
                html: '<span class="btn-label"><i class="glyphicon glyphicon-new-window"></i></span>Grabar Multa',
                "class": "btn btn-labeled bg-color-green txt-color-white",
                click: function () {save_multa();}
            },
            {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-primary bg-color-red",
                click: function () {$(this).dialog("close");}
            }]
        }).dialog('open');
   
}
function limpiar_nueva_multa()
{
    $("#sel_multa_registrada_hidden,#dlg_contri_multa_registrada_hidden").val(0);
    $("#dlg_contri_multa_registrada,#dlg_contri_multa_registrada_doc,#dlg_contri_multa_registrada_dom,#sel_multa_registrada,#costo_multa_registrada").val("");
    
}
function save_multa()
{
    if($("#dlg_des_multa").val()=="")
    {
        mostraralertasconfoco("Ingresar Descripcion de multa","#dlg_des_multa");
        return false;
    }
    if($("#dlg_cost_multa").val()=="")
    {
        mostraralertasconfoco("Ingresar Costo de multa","#dlg_cost_multa");
        return false;
    }
    if($("#per_new").val()==1||$("#per_edit").val()==1)
    {
       MensajeDialogLoadAjax('dlg_new_multa', '.:: CARGANDO ...');
       $.ajax({url: 'fisca_multa/create',
       type: 'GET',
       data:{des:$("#dlg_des_multa").val(),costo:$("#dlg_cost_multa").val(),tip:1},
       success: function(r) 
       {
           $("#dlg_new_multa").dialog('close');
           MensajeExito("Se Creó Correctamente","Su Registro Fue Modificado con Éxito...",4000);
           MensajeDialogLoadAjaxFinish('dlg_new_multa');
       },
       error: function(data) {
            MensajeAlerta("No Modificó Correctamente","Contacte con el Administrador..",4000);
           MensajeDialogLoadAjaxFinish('dlg_new_multa');
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
function fn_multa_registada()
{
    limpiar_multa_registrada()
    $("#dlg_new_multa_registada").dialog({
        autoOpen: false, modal: true, width: 1100, 
        resizable: false,
        title: "<div class='widget-header'><h4><span class='widget-icon'> <i class='fa fa-align-justify'></i> </span> Generar Multa a Contribuyente</h4></div>",
        buttons: [
            {
                id:"btnsave",
                html: '<span class="btn-label"><i class="glyphicon glyphicon-new-window"></i></span>Grabar Multa',
                "class": "btn btn-labeled bg-color-green txt-color-white",
                click: function () {fn_confirmar_multa();}
            },
            {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-primary bg-color-red",
                click: function () {$(this).dialog("close");}
            }]
        }).dialog('open');
   
}
iniciar=0;
function limpiar_multa_registrada()
{
     $('#table_multa_sel tbody tr').each(function() {$(this).remove();});
    $("#sel_multa_registrada_hidden,#dlg_contri_multa_registrada_hidden").val(0);
    $("#sel_multa_registrada,#costo_multa_registrada, #dlg_glosa_multa_registrada_dom, #dlg_contri_multa_registrada_dom, #dlg_contri_multa_registrada, #dlg_contri_multa_registrada_doc").val("");
    if(iniciar==0)
    {
        iniciar=1;
        CKEDITOR.replace('ckeditor');
    }
    CKEDITOR.instances['ckeditor'].setData('');
}
function buscar_multa()
{
    jQuery("#table_multas_criterios").jqGrid('setGridParam', {url: 'obtiene_multas/1'}).trigger('reloadGrid');
    $("#buscar_multa").dialog({
        autoOpen: false, modal: true, width: 800, 
        resizable: false,
        title: "<div class='widget-header'><h4><span class='widget-icon'> <i class='fa fa-align-justify'></i> </span> Seleccion de Multa</h4></div>",
        buttons: [
            {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-primary bg-color-red",
                click: function () {$(this).dialog("close");}
            }]
        }).dialog('open');
   
}

function fn_confirmar_multa()
{
   
    if($("#dlg_contri_multa_registrada_hidden").val()==0)
    {
       mostraralertasconfoco("Seleccione Contribuyente","#dlg_contri_multa_registrada"); 
        return false;
    }
     if($('#table_multa_sel > tbody tr').length==0)
    {
        mostraralertasconfoco("Agregar Multas","#sel_multa_registrada");
        return false;
    }
    $.SmartMessageBox({
            title : "<i class='glyphicon glyphicon-alert' style='color: yellow; margin-right: 20px; font-size: 1.5em;'></i> Confirmación Final!",
            content : "Está por generar Una Multa Para Este Contribuyente, desea Grabar la información?",
            buttons : '[Cancelar][Aceptar]'
    }, function(ButtonPressed) {
            if (ButtonPressed === "Aceptar") {
                        fn_save_multa_registrada();
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
function fn_save_multa_registrada()
{
    
    var contenido = CKEDITOR.instances['ckeditor'].getData();
    MensajeDialogLoadAjax('dlg_new_multa_registada', '.:: CARGANDO ...');
        $.ajax({url: 'fisca_multa/create',
        type: 'GET',
        data:{tip:2,contrib:$("#dlg_contri_multa_registrada_hidden").val(),glosa:$("#dlg_glosa_multa_registrada_dom").val(),fundamentos:contenido},
        success: function(r) 
        {
            if(r>0)
            {
                $('#table_multa_sel tbody tr').each(function() {
                    multa_sel=$(this).attr("id");
                    $.ajax({url: 'fisca_multa/create',
                    type: 'GET',
                    data:{tip:3,mul_reg:r,id_an: multa_sel},
                    success: function(data) 
                    {
                        MensajeExito("Multa Insertada","Su Registro Fue Insertado con Éxito...",4000);
                    },
                    error: function(data) {
                        mostraralertas("no inserto Multa, Comunicar al Administrador");
                        console.log('error');
                        console.log(data);
                    }
                    });
                });
            }
            jQuery("#table_multas").jqGrid('setGridParam', {url: 'trae_multas/'+$("#selantra").val()+'/0/0/0/0'}).trigger('reloadGrid');
            MensajeExito("Insertó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            MensajeDialogLoadAjaxFinish('dlg_new_multa_registada');
            $("#dlg_new_multa_registada").dialog("close");
            vermulta(r);
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dlg_new_multa_registada');
            console.log('error');
            console.log(data);
        }
        });
}

function vermulta(id)
{
    if($("#per_imp").val()==0)
    {
        sin_permiso();
        return false;
    }
    window.open('multa_rep/'+id);
}
function ponerfechanoti(num)
{
    $("#input_num_multa_fn").val(num);
    $("#input_fec_notifica").val('');
    $("#dlg_fec_notificacion").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  Fecha de Notificación Multas :.</h4></div>",
        buttons: [
            {
                id:"btnsave",
                html: '<span class="btn-label"><i class="glyphicon glyphicon-new-window"></i></span>Grabar Fecha',
                "class": "btn btn-labeled bg-color-green txt-color-white",
                click: function () {save_multa_fec_noti();}
            },
            {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-primary bg-color-red",
                click: function () {$(this).dialog("close");}
            }]
        }).dialog('open');
}

function save_multa_fec_noti()
{
    if($("#input_fec_notifica").val()=="")
    {
        mostraralertasconfoco("Ingresar Fecha de Notificación","#input_fec_notifica");
        return false;
    }
    if($("#per_new").val()==1||$("#per_edit").val()==1)
    {
        Id=$('#table_multas').jqGrid ('getGridParam', 'selrow');
        MensajeDialogLoadAjax('dlg_fec_notificacion', '.:: CARGANDO ...');
       $.ajax({url: 'mod_noti_multa',
       type: 'GET',
       data:{id:Id,fec:$("#input_fec_notifica").val()},
       success: function(r) 
       {
           $('#table_multas').trigger( 'reloadGrid' );
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

function poner_multa()
{
    if($("#sel_multa_registrada_hidden").val()==0)
    {
        mostraralertasconfoco("Seleccione Multa","#sel_multa_registrada");
        return false;
    }
    
    if ( $("#table_multa_sel tr#"+$("#sel_multa_registrada_hidden").val()+"-"+$("#selantra_multa").val()).length==0 ) {
//        if($("#hidden_id_carta").val()==0)
//        {
            $('#table_multa_sel > tbody').append('\
                    <tr id="'+$("#sel_multa_registrada_hidden").val()+"-"+$("#selantra_multa").val()+'"><td style="border: 1px solid #bbb">'+$("#sel_multa_registrada_hidden").val()+'</td>\n\
                    <td style="border: 1px solid #bbb">'+$("#selantra_multa").val()+'</td>\n\
                    <td style="border: 1px solid #bbb">'+$("#sel_multa_registrada").val()+'</td>\n\
                    <td style="border: 1px solid #bbb">'+$("#costo_multa_registrada").val()+'</td>\n\
                    <td class="text-center" style="border: 1px solid #bbb"><i class="fa fa-close" style="color:red; cursor:pointer" onclick="del_multa_sel('+"'"+$("#sel_multa_registrada_hidden").val()+"-"+$("#selantra_multa").val()+"'"+')"></i></td></tr>');
//        }
//        else
//        {
//            poner_fisca_bd($("#hidden_id_carta").val(),$("#selfisca").val());
//        }
    }
}
function del_multa_sel(multa)
{
    $("#table_multa_sel tr#"+multa+"").remove();
}