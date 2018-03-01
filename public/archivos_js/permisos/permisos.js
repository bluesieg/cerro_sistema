
function llamar_sub_modulo()
{
    
    modulo= $('#table_modulos').jqGrid('getGridParam', 'selrow');
    id_user = $('#table_Usuarios').jqGrid('getGridParam', 'selrow');
    if(id_user==null){
        return false;
    }
    jQuery("#table_sub_modulos").jqGrid('setGridParam', {url: 'sub_modulos?identifi='+modulo+'&usu='+id_user}).trigger('reloadGrid');
            
}
function fn_crea_mod()
{
    $("#hidden_id_mod").val(0);
    $("#dlg_des_mod,#dlg_title_mod,#dlg_idsis_mod").val("");
    $("#dlg_modulos").dialog({
        autoOpen: false, modal: true, width: 900, 
        show:{ effect: "explode", duration: 500},
        hide:{ effect: "explode", duration: 800}, resizable: false,
        title: "<div class='widget-header'><h4><span class='widget-icon'> <i class='fa fa-align-justify'></i> </span>Generar Modulos Sistema</h4></div>",
        buttons: [{
                html: "<i class='fa fa-sign-save'></i>&nbsp; Guardar",
                "class": "btn btn-success",
                id:'btn_save_mod',
                click: function () {
                    fn_save_mod();
                }
            },
            {
                html: "<i class='fa fa-sign-save'></i>&nbsp; Modificar",
                "class": "btn btn-primary",
                id:'btn_edit_mod',
                click: function () {
                    fn_save_mod();
                }
            },
            {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {
                    $(this).dialog("close");
                }
            }],
        }).dialog('open');
}
function fn_crea_submod()
{
    $("#hidden_id_submod").val(0);
    $("#dlg_des_submod,#dlg_title_submod,#dlg_idsis_submod,#dlg_ruta_submod").val("");
    $("#dlg_submodulos").dialog({
        autoOpen: false, modal: true, width: 900, 
        show:{ effect: "explode", duration: 500},
        hide:{ effect: "explode", duration: 800}, resizable: false,
        title: "<div class='widget-header'><h4><span class='widget-icon'> <i class='fa fa-align-justify'></i> </span>Generar Modulos Sistema</h4></div>",
        buttons: [{
                html: "<i class='fa fa-sign-save'></i>&nbsp; Guardar",
                "class": "btn btn-success",
                id:'btn_save_submod',
                click: function () {
                    fn_save_submod();
                }
            },
            {
                html: "<i class='fa fa-sign-save'></i>&nbsp; Modificar",
                "class": "btn btn-primary",
                id:'btn_edit_submod',
                click: function () {
                    fn_save_submod();
                }
            },
            {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {
                    $(this).dialog("close");
                }
            }],
        }).dialog('open');
}

function fn_new_mod()
{
    
    fn_crea_mod();
    $("#btn_edit_mod").hide();
    $("#btn_save_mod").show();

}
function fn_new_submod()
{
    
    fn_crea_submod();
    $("#btn_edit_submod").hide();
    $("#btn_save_submod").show();

}
function fn_edit_mod()
{
    fn_crea_mod();
    $("#btn_edit_mod").show();
    $("#btn_save_mod").hide();
    modulo= $('#table_modulos').jqGrid('getGridParam', 'selrow');
    if(modulo==null)
    {
        mostraralertasconfoco("No hay Modulo seleccionado","#table_modulos");
        return false;
    }
    MensajeDialogLoadAjax('dlg_modulos', '.:: Cargando ...');
    $.ajax({
        url: 'modulos/'+modulo,
        type: 'GET',
        success: function(r) 
        {
            $("#hidden_id_mod").val(modulo);
            $("#dlg_des_mod").val(r[0].descripcion);
            $("#dlg_title_mod").val(r[0].titulo);
            $("#dlg_idsis_mod").val(r[0].id_sistema);
            MensajeDialogLoadAjaxFinish('dlg_modulos');
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dlg_modulos');
            console.log('error');
            console.log(data);
        }
    });

}
function fn_edit_submod()
{
    fn_crea_submod();
    $("#btn_edit_submod").show();
    $("#btn_save_submod").hide();
    submodulo= $('#table_sub_modulos').jqGrid('getGridParam', 'selrow');
    if(submodulo==null)
    {
        mostraralertasconfoco("No hay Sub Modulo seleccionado","#table_sub_modulos");
        return false;
    }
    MensajeDialogLoadAjax('dlg_submodulos', '.:: Cargando ...');
    $.ajax({
        url: 'sub_modulos/'+submodulo,
        type: 'GET',
        success: function(r) 
        {
            $("#hidden_id_submod").val(submodulo);
            $("#dlg_des_submod").val(r[0].des_sub_mod);
            $("#dlg_title_submod").val(r[0].titulo);
            $("#dlg_idsis_submod").val(r[0].id_sistema);
            $("#dlg_ruta_submod").val(r[0].ruta_sis);
            $("#dlg_orden_submod").val(r[0].orden);
            MensajeDialogLoadAjaxFinish('dlg_submodulos');
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dlg_submodulos');
            console.log('error');
            console.log(data);
        }
    });

}

function fn_borrar_Modulo()
{
    modulo= $('#table_modulos').jqGrid('getGridParam', 'selrow');
    if(modulo==null)
    {
        mostraralertasconfoco("No hay Modulo seleccionado","#table_modulos");
        return false;
    }
    $.SmartMessageBox({
            title : "<i class='glyphicon glyphicon-alert' style='color: yellow; margin-right: 20px; font-size: 1.5em;'></i> Confirmación Final!",
            content : "Está Seguro que desea Eliminar Modulo?, Se Eliminará Submodulos y todos los permisos",
            buttons : '[Cancelar][Aceptar]'
    }, function(ButtonPressed) {
            if (ButtonPressed === "Aceptar") {
                        fn_delete_mod();
            }
            if (ButtonPressed === "Cancelar") {
                    $.smallBox({
                            title : "No se Elimino",
                            content : "<i class='fa fa-clock-o'></i> <i>Puede Corregir...</i>",
                            color : "#C46A69",
                            iconSmall : "fa fa-times fa-2x fadeInRight animated",
                            timeout : 3000
                    });
            }
    });
}

function fn_borrar_subModulo()
{
    submodulo= $('#table_sub_modulos').jqGrid('getGridParam', 'selrow');
    if(submodulo==null)
    {
        mostraralertasconfoco("No hay Sub Modulo seleccionado","#table_sub_modulos");
        return false;
    }
    $.SmartMessageBox({
            title : "<i class='glyphicon glyphicon-alert' style='color: yellow; margin-right: 20px; font-size: 1.5em;'></i> Confirmación Final!",
            content : "Está Seguro que desea Eliminar Sub Modulo?, Se Eliminará todos los permisos",
            buttons : '[Cancelar][Aceptar]'
    }, function(ButtonPressed) {
            if (ButtonPressed === "Aceptar") {
                        fn_delete_submod();
            }
            if (ButtonPressed === "Cancelar") {
                    $.smallBox({
                            title : "No se Elimino",
                            content : "<i class='fa fa-clock-o'></i> <i>Puede Corregir...</i>",
                            color : "#C46A69",
                            iconSmall : "fa fa-times fa-2x fadeInRight animated",
                            timeout : 3000
                    });
            }
    });
}
function fn_save_mod()
{
    if($("#dlg_des_mod").val()==0||$("#dlg_des_mod").val()=="")
    {
        mostraralertasconfoco("Ingresar Nombre de Módulo","#dlg_des_mod");
        return false;
    }
    if($("#dlg_title_mod").val()==0||$("#dlg_title_mod").val()=="")
    {
        mostraralertasconfoco("Ingresar Título de Módulo","#dlg_title_mod");
        return false;
    }
    if($("#dlg_idsis_mod").val()==0||$("#dlg_idsis_mod").val()=="")
    {
        mostraralertasconfoco("Ingresar id_sistema","#dlg_idsis_mod");
        return false;
    }
    if($("#hidden_id_mod").val()==0)
    {
        url='modulos/create'; titulo="Insertó";
    }
    else
    {
        url='modulos/'+$("#hidden_id_mod").val()+'/edit'; titulo="Modificó";
    }
    MensajeDialogLoadAjax('dlg_modulos', '.:: Guardando ...');
    $.ajax({
        url: url,
        type: 'GET',
        data: {des:$("#dlg_des_mod").val(),tit:$("#dlg_title_mod").val(),sis:$("#dlg_idsis_mod").val()},
        success: function(r) 
        {
            jQuery("#table_modulos").jqGrid('setGridParam', {url: 'modulos'}).trigger('reloadGrid');
            MensajeExito("Se "+titulo+" Correctamente","Su Registro Fue Insertado Correctamente...",4000)
            MensajeDialogLoadAjaxFinish('dlg_modulos');
            $("#dlg_modulos").dialog("close");
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dlg_modulos');
            console.log('error');
            console.log(data);
        }
    });
}
function fn_save_submod()
{
    if($("#dlg_des_submod").val()==0||$("#dlg_des_submod").val()=="")
    {
        mostraralertasconfoco("Ingresar Nombre de sub Módulo","#dlg_des_mod");
        return false;
    }
    if($("#dlg_title_submod").val()==0||$("#dlg_title_submod").val()=="")
    {
        mostraralertasconfoco("Ingresar Título de Módulo","#dlg_title_mod");
        return false;
    }
    if($("#dlg_idsis_submod").val()==0||$("#dlg_idsis_submod").val()=="")
    {
        mostraralertasconfoco("Ingresar id_sistema","#dlg_idsis_mod");
        return false;
    }
    if($("#dlg_ruta_submod").val()==0||$("#dlg_ruta_submod").val()=="")
    {
        mostraralertasconfoco("Ingresar Ruta","#dlg_ruta_submod");
        return false;
    }
    if($("#dlg_orden_submod").val()=='')
    {
       $("#dlg_orden_submod").val(0);
    }
    if($("#hidden_id_submod").val()==0)
    {
        url='sub_modulos/create'; titulo="Insertó";
    }
    else
    {
        url='sub_modulos/'+$("#hidden_id_submod").val()+'/edit'; titulo="Modificó";
    }
    modulo= $('#table_modulos').jqGrid('getGridParam', 'selrow');
    MensajeDialogLoadAjax('dlg_submodulos', '.:: Guardando ...');
    $.ajax({
        url: url,
        type: 'GET',
        data: {des:$("#dlg_des_submod").val(),tit:$("#dlg_title_submod").val(),sis:$("#dlg_idsis_submod").val(),ruta:$("#dlg_ruta_submod").val(),mod:modulo,orden:$("#dlg_orden_submod").val()},
        success: function(r) 
        {
            llamar_sub_modulo() 
            MensajeExito("Se "+titulo+" Correctamente","Su Registro Fue Insertado Correctamente...",4000)
            MensajeDialogLoadAjaxFinish('dlg_submodulos');
            $("#dlg_submodulos").dialog("close");
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dlg_submodulos');
            console.log('error');
            console.log(data);
        }
    });
}
function fn_delete_mod()
{
 
    Id= $('#table_modulos').jqGrid('getGridParam', 'selrow');
    MensajeDialogLoadAjax('dialog_Editar_Usuario', '.:: Eliminando ...');
    $.ajax({
        url: 'modulos/destroy',
        type: 'post',
        data: {_method: 'delete', _token:$("#btn_delmod").data('token'),id:Id},
        success: function(r) 
        {
            jQuery("#table_modulos").jqGrid('setGridParam', {url: 'modulos'}).trigger('reloadGrid');
            MensajeAlerta("Se Eliminó Correctamente","Su Registro Fue eliminado Correctamente...",4000)
            MensajeDialogLoadAjaxFinish('dialog_Editar_Usuario');
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dialog_Editar_Usuario');
            console.log('error');
            console.log(data);
        }
    });
}
function fn_delete_submod()
{
    Id=$('#table_sub_modulos').jqGrid('getGridParam', 'selrow');
    MensajeDialogLoadAjax('dialog_Editar_Usuario', '.:: Eliminando ...');
    $.ajax({
        url: 'sub_modulos/destroy',
        type: 'post',
        data: {_method: 'delete', _token:$("#btn_delmod").data('token'),id:Id},
        success: function(r) 
        {
            llamar_sub_modulo();
            MensajeAlerta("Se Eliminó Correctamente","Su Registro Fue eliminado Correctamente...",4000)
            MensajeDialogLoadAjaxFinish('dialog_Editar_Usuario');
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dialog_Editar_Usuario');
            console.log('error');
            console.log(data);
        }
    });
}
function actbtn(id,tip)
{
    if( $('#ck'+tip+'_'+id).is(':checked') ) {
        nu=1;
    }
    else
    {
        nu=0;
    }
    id_user = $('#table_Usuarios').jqGrid('getGridParam', 'selrow');
    MensajeDialogLoadAjax('dialog_Editar_Usuario', '.:: Guardando ...');
    $.ajax({
        url: 'permisos/create',
        type: 'GET',
        data: {submod:id,tipo:tip,val:nu,usu:id_user},
        success: function(r) 
        {
            MensajeExito("Se Creo Correctamente","Su Permiso Fue Insertado Correctamente...",4000)
            MensajeDialogLoadAjaxFinish('dialog_Editar_Usuario');
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dialog_Editar_Usuario');
            console.log('error');
            console.log(data);
        }
    });
    
}