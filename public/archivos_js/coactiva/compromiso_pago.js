
function editar_compromiso(id_aper,estado){
    if(estado==0){
        estado=1;
    }else{
        estado=0;
    }
    id_coa_mtr = $('#tabla_expedientes').jqGrid ('getGridParam', 'selrow');
    $.confirm({
        title:'COACTIVA',
        content: '* Actualizar Estado.',
        buttons: {
            Aceptar: function () {
                 $.ajax({
                    url:'edit_estado',
                    type:'GET',
                    data:{id_aper:id_aper,estado:estado},
                    success: function(data){
                        if(data.msg=='si'){
                            MensajeExito('Cambiar Estado de Expediente','Operacion Completada Correctamente...');
                            fn_actualizar_grilla('t_compromisos_pago','get_compromisopago?id_coa_mtr='+id_coa_mtr);
                        }
                    }                           
                });   
            },
            Cancelar: function () {}
        }
    });
}

function dlg_edit_compromiso(id_aper){
    $("#dlg_edit_compromiso").dialog({
        autoOpen: false, modal: true, width: 600,height: 'auto', show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.: COACTIVA :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-primary",
                click: function () { 
                    editar_compromiso(id_aper);
                }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {$(this).dialog("close");}
            }],
        open: function(){ },
        close:function(){ }
    }).dialog('open');
}

function ver_compromisos_pago(id_coa_mtr){
    id_coa_mtr = id_coa_mtr || $('#tabla_expedientes').jqGrid ('getGridParam', 'selrow');    
    fn_actualizar_grilla('t_compromisos_pago','get_compromisopago?id_coa_mtr='+id_coa_mtr);
}

function bus_contrib_compromiso(){
    if($("#vw_compromiso_contrib").val()==""){
        mostraralertasconfoco("Ingrese un Contribuyente para Buscar","#vw_compromiso_contrib"); 
        return false;
    }
    if($("#vw_compromiso_contrib").val().length<4){
        mostraralertasconfoco("Ingresar al menos 4 caracteres de busqueda","#vw_compromiso_contrib"); 
        return false;
    }

    fn_actualizar_grilla('table_contrib','obtiene_cotriname?dat='+$("#vw_compromiso_contrib").val());
    jQuery('#table_contrib').jqGrid('bindKeys', {"onEnter":function( rowid ){fn_bus_contrib_select_compromiso(rowid);} } ); 
    $("#dlg_bus_contr").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  Busqueda de Contribuyente :.</h4></div>"       
        }).dialog('open');
}

function fn_bus_contrib_select_compromiso(per){    
    $("#vw_compromiso_idcontrib").val(per);
       
    $("#vw_compromiso_contrib").val($('#table_contrib').jqGrid('getCell',per,'contribuyente'));
    tam=($('#table_contrib').jqGrid('getCell',per,'contribuyente')).length;

    
    $("#vw_compromiso_contrib").attr('maxlength',tam);

    fn_actualizar_grilla('tabla_expedientes','get_exped?id_contrib='+$("#vw_compromiso_idcontrib").val());
    $('#t_compromisos_pago').jqGrid('clearGridData');
    $("#dlg_bus_contr").dialog("close");    
}