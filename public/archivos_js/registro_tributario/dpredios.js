function limpiar_dl_dpredios(tip)
{
    if(tip==1)
    {
        $('#documento').val("");
        $('#valor').val("");
        $('#anio').val("");
    }
}

function nuevo_dpredios()
{
    limpiar_dl_dpredios(1);
    $("#dlg_nuevo_dpredios").dialog({
        autoOpen: false, modal: true, width: 1000, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  DESCARGAR PREDIOS :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Guardar",
            "class": "btn btn-success bg-color-green",
            click: function () {

                guardar_editar_tim(1);
            }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {
                $(this).dialog("close");
            }
        }],
    });
    $("#dlg_nuevo_dpredios").dialog('open');
    tabla()
}

function actualizar_tim()
{
    var idarray = jQuery('#tabla_tim').jqGrid('getDataIDs');
        if (idarray.length == '') {
        mostraralertasconfoco('* No Existen Registros en la Tabla...', 'dlg_anio');
        }else{
            
        
    $("#dlg_anio").val($("#select_anio option:selected").html());
    $("#id_anio").val($("#select_anio").val());
    limpiar_dl_tim(1);
    $("#dlg_nuevo_tim").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  EDITAR TASA DE INTERES MORATORIO  :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Guardar",
            "class": "btn btn-success bg-color-green",
            click: function () {
                guardar_editar_tim(2);
            }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {
                $(this).dialog("close");
            }
        }],
    });
    $("#dlg_nuevo_tim").dialog('open');


    MensajeDialogLoadAjax('dlg_nuevo_tim', '.:: Cargando ...');

    id = $("#current_id").val();
    $.ajax({url: 'tim/'+id,
        type: 'GET',
        success: function(r)
        {
            $("#id_tim").val(r[0].id_tim);
            $("#documento").val(r[0].documento_aprob);
            $("#valor").val(r[0].tim);
            $("#anio").val(r[0].anio);
            MensajeDialogLoadAjaxFinish('dlg_nuevo_tim');

        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            MensajeDialogLoadAjaxFinish('dlg_nuevo_tim');
        }
    });
    
    }
}

function guardar_editar_tim(tipo) {

    documento = $("#documento").val();
    valor = $("#valor").val();
    anio = $("#id_anio").val();

    if (documento == '') {
        mostraralertasconfoco('* El campo Documento es obligatorio...', 'documento');
        return false;
    }
    if (valor == '') {
        mostraralertasconfoco('* El campo Valor obligatorio...', 'valor');
        return false;
    }
    if (anio == '') {
        mostraralertasconfoco('* El campo Año obligatorio...', 'anio');
        return false;
    }

    if (tipo == 1) {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'insertar_nuevo_tim',
            type: 'POST',
            data: {
                documento_aprob: documento,
                tim: valor,
                anio: anio
            },
            success: function (data) {
                dialog_close('dlg_nuevo_tim');
                fn_actualizar_grilla('tabla_tim');
                MensajeExito('Nuevo TIM', 'La TIM se a creado correctamente.');
            },
            error: function (data) {
                mostraralertas('* Contactese con el Administrador...');
            }
        });
    }
    else if (tipo == 2) {
        id = $("#current_id").val();
        MensajeDialogLoadAjax('dlg_nuevo_tim', '.:: CARGANDO ...');
        $.confirm({
            title: '.:Cuidado... !',
            content: 'Los Cambios no se podran revertir...',
            buttons: {
                Confirmar: function () {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: 'modificar_tim',
                        type: 'POST',
                        data: {
                            id_tim:id,
                            documento_aprob: documento,
                            tim: valor,
                            anio: anio
                        },
                        success: function (data) {
                            MensajeExito('Editar TIM', 'TIM: '+ id + '  -  Ha sido Modificado.');
                            fn_actualizar_grilla('tabla_tim');
                            dialog_close('dlg_nuevo_tim');
                            MensajeDialogLoadAjaxFinish('dlg_nuevo_tim', '.:: CARGANDO ...');
                        },
                        error: function (data) {
                            mostraralertas('* Contactese con el Administrador...');
                            MensajeAlerta('Editar TIM','Ocurrio un Error en la Operacion.');
                            dialog_close('dlg_nuevo_tim');
                            MensajeDialogLoadAjaxFinish('dlg_nuevo_tim', '.:: CARGANDO ...');
                        }
                    });
                },
                Cancelar: function () {
                    MensajeAlerta('Editar TIM','Operacion Cancelada.');
                    MensajeDialogLoadAjaxFinish('dlg_nuevo_tim', '.:: CARGANDO ...');
                    
                }
            }
        });

    }
}

function eliminar_tim() {
    
    var idarray = jQuery('#tabla_tim').jqGrid('getDataIDs');
        if (idarray.length == '') {
        mostraralertasconfoco('* No Existen Registros en la Tabla...', 'dlg_anio');
        }else{
    
    id = $("#current_id").val();

    $.confirm({
        title: '.:Cuidado... !',
        content: 'Los Cambios no se podran revertir...',
        buttons: {
            Confirmar: function () {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: 'eliminar_tim',
                    type: 'POST',
                    data: {id_tim: id},
                    success: function (data) {
                        fn_actualizar_grilla('tabla_tim');
                        MensajeExito('Eliminar TIM', id + ' - Ha sido Eliminado');
                    },
                    error: function (data) {
                        MensajeAlerta('Eliminar TIM', id + ' - No se pudo Eliminar.');
                    }
                });
            },
            Cancelar: function () {
                MensajeAlerta('Eliminar TIM','Operacion Cancelada.');
            }

        }
    });
    
    }
}

function selecciona_anio(){
    
    aniox = $("#select_anio").val();

    jQuery("#tabla_tim").jqGrid('setGridParam', {
         url: 'listar_tim?anio=' + aniox 
    }).trigger('reloadGrid');

}

function tabla(){
jQuery("#tabla1").jqGrid({
            url: 'listar_tim?anio=' + anio,
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
            toolbarfilter: true,
            colNames: ['ID','FECHA','MOTIVO','AÑO'],
            rowNum: 20,sortname: 'id_tim', viewrecords: true, caption: 'DESCARGA DE PREDIOS', align: "center",
            colModel: [
                {name: 'id_tim', index: 'id_tim', align: 'center',width:30},
                {name: 'documento_aprob', index: 'documento_aprob', align: 'center', width:30}, 
                {name: 'tim', index: 'tim', align: 'center', width:50},
                {name: 'anio', index: 'anio', align: 'center', width:40},

            ],
            pager: '#pager_tabla1',
            rowList: [10, 20],
            gridComplete: function () {
                    var idarray = jQuery('#tabla1').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tabla1').jqGrid('getDataIDs')[0];
                            $("#tabla1").setSelection(firstid);
                        }
                },
            onSelectRow: function (Id){
                $('#current_id').val($("#tabla1").getCell(Id, "id_tim"));

            },
            ondblClickRow: function (Id){
                $('#current_id').val($("#tabla1").getCell(Id, "id_tim"));
                actualizar_tim();}
        });
        
        $(window).on('resize.jqGrid', function () {
            $("#tabla1").jqGrid('setGridWidth', $("#content").width());
        });
        
    jQuery("#tabla").jqGrid({
        url: 'grid_detalle_fracc?id_conv=0',
        datatype: 'json', mtype: 'GET',
        height: 200, width: 880,
        toolbarfilter: true,
        colNames: ['N°', 'Cuota Mensual', 'Estado', 'cod_est', 'Fecha Pago', 'Selec.'],
        rowNum: 12, sortname: 'nro_cuota', sortorder: 'asc', viewrecords: true, caption: 'DESCARGA DE PREDIOS', align: "center",
        colModel: [
            {name: 'nro_cuota', index: 'nro_cuota',width: 20,align:'center'},
            {name: 'total', index: 'total',width: 60,align:'center'},
            {name: 'estado', index: 'estado',width: 50, align:'center'},
            {name: 'cod_est', index: 'cod_est', hidden: true},
            {name: 'fec_pago', index: 'fec_pago', width: 60, align:'center'},
            {name: 'seleccione', index: 'seleccione', align: 'center', width: 50}
        ],
        pager: '#pager_tabla',
        rowList: [10, 20],
        gridComplete: function () {
            var rows = $("#tabla").getDataIDs();
            if (rows.length > 0) {
                var firstid = jQuery('#tabla').jqGrid('getDataIDs')[0];
                $("#tabla").setSelection(firstid);
            }            
            $("#tabla").val('0.00');
            var verif = jQuery("#tabla").getGridParam('userData').verif_cancela;
            if(verif==rows.length){
                $("#tabla").closest(".ui-jqgrid").block({
                    message:"<div style='font-size:1.5em;text-align:center;font-weight: bold'>Fraccionamiento Cancelado</div>",
                    theme: true,
                    themedCSS:{
                        width: "40%",
                        left: "30%",
                        border: "3px solid #a00"
                    }
                });
            }else{ $("#tabla").closest(".ui-jqgrid").unblock(); }
            
        },            
        ondblClickRow: function (Id) {}
    });  
        
}