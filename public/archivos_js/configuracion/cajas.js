
function guardar_editar_datos(tipo) {
    descripcion = $("#dlg_descripcion").val();
    direcion = $("#dlg_direccion").val();
    serie = $("#dlg_serie").val();

    if(descripcion == "")
    {
        mostraralertasconfoco("* El Campo DIRECCION es Obligatorio","#dlg_descripcion");
        return false;
    }
    if(direcion == "")
    {
        mostraralertasconfoco("* El Campo DIRECCION es Obligatorio","#dlg_direccion");
        return false;
    }
    if(serie == "")
    {
        mostraralertasconfoco("* El Campo SERIE es Obligatorio","#dlg_serie");
        return false;
    }
    
    if (tipo == 1) {

        MensajeDialogLoadAjax('dlg_nueva_caja', '.:: Cargando ...');
      
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'cajas/create',
            type: 'GET',
            data: {
                descripcion:descripcion,
                direcion:direcion,
                serie:serie            
            },
            success: function(data) 
            {
                MensajeExito('OPERACION EXITOSA', 'El registro fue guardado Correctamente');
                MensajeDialogLoadAjaxFinish('dlg_nueva_caja');
                fn_actualizar_grilla('table_cajas');
                $("#dlg_nueva_caja").dialog("close");
            },
            error: function(data) {
                mostraralertas("hubo un error, Comunicar al Administrador");
                MensajeDialogLoadAjaxFinish('table_cajas');
                console.log('error');
                console.log(data);
            }
        });
    }
    else if (tipo == 2) {

        id_caja = $('#table_cajas').jqGrid ('getGridParam', 'selrow');

        MensajeDialogLoadAjax('dlg_nueva_caja', '.:: Cargando ...');
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'cajas/'+id_caja+'/edit',
            type: 'GET',
            data: {
        	descripcion:descripcion,
                direcion:direcion,
                serie:serie 
            },
            success: function(data) 
            {
                MensajeExito('Se Modifico Correctamente', 'Su Registro Fue Modificado Correctamente...');
                MensajeDialogLoadAjaxFinish('dlg_nueva_caja');
                fn_actualizar_grilla('table_cajas');
                $("#dlg_nueva_caja").dialog("close");
            },
            error: function(data) {
                mostraralertas("hubo un error, Comunicar al Administrador");
                MensajeDialogLoadAjaxFinish('table_cajas');
                console.log('error');
                console.log(data);
            }
        });
    }
 
}

function limpiar_datos()
{
    $("#dlg_descripcion").val('');
    $("#dlg_direccion").val('');
    $("#dlg_serie").val('');
}

function nueva_caja()
{
    limpiar_datos();
    $("#dlg_nueva_caja").dialog({
        autoOpen: false, modal: true, width: 800, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  NUEVO REGISTRO DE CAJA  :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Guardar",
            "class": "btn btn-success bg-color-green",
            click: function () {
                    guardar_editar_datos(1);
            }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {
                $(this).dialog("close");
            }
        }],
    });
    $("#dlg_nueva_caja").dialog('open');
}

function modificar_caja()
{
    id_caja = $('#table_cajas').jqGrid ('getGridParam', 'selrow');
    
    if (id_caja) {
        
        $("#dlg_nueva_caja").dialog({
            autoOpen: false, modal: true, width: 800, show: {effect: "fade", duration: 300}, resizable: false,
            title: "<div class='widget-header'><h4>.:  EDITAR INFORMACION CAJA :.</h4></div>",
            buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-success bg-color-green",
                click: function () {
                    guardar_editar_datos(2);
                }
            },{
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {
                    $(this).dialog("close");
                }
            }],
        });
        $("#dlg_nueva_caja").dialog('open');


        MensajeDialogLoadAjax('dlg_nueva_caja', '.:: Cargando ...');

        $.ajax({url: 'cajas/'+id_caja+'?show=cajas',
            type: 'GET',
            success: function(data)
            {          
                $("#dlg_descripcion").val(data[0].descrip_caja);
                $("#dlg_direccion").val(data[0].direc_caja);
                $("#dlg_serie").val(data[0].serie);
                
                MensajeDialogLoadAjaxFinish('dlg_nueva_caja');
            },
            error: function(data) {
                mostraralertas("Hubo un Error, Comunicar al Administrador");
                console.log('error');
                console.log(data);
                MensajeDialogLoadAjaxFinish('dlg_nueva_caja');
            }
        });
    }else{
        mostraralertasconfoco("No Hay Registros Seleccionados","#table_cajas");
    }
}

