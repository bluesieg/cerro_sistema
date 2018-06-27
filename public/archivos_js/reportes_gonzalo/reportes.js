/********************************REPORTE_SUPERVISORES**************************************************/

function crear_dialogo()
{
    $("#dialog_supervisores").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Predios Ingresados Por Usuario :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte(); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
}

function abrir_reporte()
{
    window.open('reporte_supervisores/'+$('#select_sup_anio').val()+'/'+$('#select_sup_sec').val()+'/'+$('#select_sup_mz').val()+'');
}

function dlg_supervisor_reportes(tipo)
{
    if (tipo===0) {
        crear_dialogo();
        cargar_manzana('select_sup_mz');
    } 
}

function cargar_manzana(input)
{
    $("#"+input).html('');
    MensajeDialogLoadAjax(input, '.:: CARGANDO ...');
    $.ajax({url: 'selmzna?sec='+$("#select_sup_sec").val(),
        type: 'GET',
        success: function(r)
        {
            $(r).each(function(i, v){ 
                $("#"+input).append('<option value="' + v.id_mzna + '">' + v.codi_mzna + '</option>');
            })
            MensajeDialogLoadAjaxFinish(input);
        },
        error: function(data) {
            console.log('error');
            console.log(data);
        }
    });
}

/********************************REPORTE_CONTRIBUYENTES**************************************************/

function crear_dialogo_contribuyentes(){

    $("#dialog_reporte_contribuyentes").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Listado de Contribuyentes(Pricos,Mecos,Pecos) :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_contribuyente(); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');

}

function cambiar_estado(){
    if( $('#mostrar_todo').is(':checked') ) {
        $("#max").attr('disabled', 'disabled');  
        $("#max").attr('value', '1000000000'); 
    }else{
        $("#max").removeAttr('disabled');
        $("#max").attr('value', '50000');
    }
}

function dlg_reporte_contribuyentes(tipo)
{
    if (tipo===0) {
        crear_dialogo_contribuyentes();
    } 
}

function abrir_reporte_contribuyente()
{
    if( $('#mostrar_todo').is(':checked') ) {  
    window.open('reporte_contribuyentes/'+ $('#selantra_r0').val()+ '/'+ $('#min').val()+ '/' + $('#max').val() + '/'  +$('#num_reg').val());   
    }else{
    window.open('reporte_contribuyentes/'+ $('#selantra_r0').val()+ '/'+ $('#min').val()+ '/' + $('#max').val() + '/'  +$('#num_reg').val());
    }
}

/********************************REPORTE_DE_USUARIOS************************************************************/

function crear_dialogo_usuario()
{
    $("#dialog_busqueda_usuarios").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Listado de Datos de los Predios Ingresados por Usuario :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_usuarios(); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
}

function dlg_busqueda_usuarios(tipo)
{
    if (tipo===0) {
        crear_dialogo_usuario(); 
        $('#dlg_id').val("");
        $('#dlg_usuario').val("");
    } 
}

function fn_bus_contrib_rus()
{
    if($("#dlg_usuario").val()=="")
    {
        mostraralertasconfoco("Ingresar Información de busqueda","#dlg_usuario"); 
        return false;
    }
    if($("#dlg_usuario").val().length<4)
    {
        mostraralertasconfoco("Ingresar al menos 4 caracteres de busqueda","#dlg_usuario"); 
        return false;
    }
    jQuery("#table_usuario").jqGrid('setGridParam', {url: 'obtener_usuarios?dat='+$("#dlg_usuario").val()}).trigger('reloadGrid');

    $("#dlg_bus_usuario").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  Busqueda de Usuarios :.</h4></div>"       
        }).dialog('open');
       
}

function fn_bus_contrib_list_rus(per)
{
    $("#dlg_id").val($('#table_usuario').jqGrid('getCell',per,'id'));
    $("#dlg_usuario").val($('#table_usuario').jqGrid('getCell',per,'ape_nom'));
    $("#dlg_bus_usuario").dialog("close");  
}

function abrir_reporte_usuarios()
{
    window.open('reporte_usuarios/'+$('#dlg_id').val()+'?ini='+$('#fdesde').val()+'&fin='+$('#fhasta').val()+'');
}



/*********************************************REPORTES GERENCIALES********************************************/

auxdc=0;
function crear_dialogo_datos_contribuyentes()
{
    $("#dialog_datos_contribuyentes").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Listado de Datos de los Contribuyentes :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Hab. Urbana Seleccionada"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_datos_contribuyentes(0); }
        }, {
            html: "<i class='fa fa-file-excel-o'></i>&nbsp; Ver Todas",
            "class": "btn btn-success bg-color-blue",
            click: function () { abrir_reporte_datos_contribuyentes(1); }
        },{
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
    if(auxdc==0)
    {
        autocompletar_haburb('habilitacion_urbana');
        auxdc=1;
    }
}

auxdcp=0;
function crear_dialogo_datos_contribuyentes_p()
{
    $("#dialog_datos_contribuyentes_p").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Listado de Datos de los Contribuyentes y Predios :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Hab. Urbana Seleccionada"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_datos_contribuyentes_predios(0); }
        },{
            html: "<i class='fa fa-file-excel-o'></i>&nbsp; Ver Todas",
            "class": "btn btn-success bg-color-blue",
            click: function () { abrir_reporte_datos_contribuyentes_predios(1); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
    if(auxdcp==0)
    {
        autocompletar_haburb('habilitacion_urbana1');
        auxdcp=1;
    }
}
auxdet=0;
function crear_dialogo_contribuyentes_p_detallado()
{
    $("#dialog_datos_contribuyentes_p_detallado").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Listado de Datos de los Contribuyentes y Predios Detallado :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Hab. Urbana Seleccionada"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_datos_contribuyentes_predios_detallado(0); }
        },{
            html: "<i class='fa fa-file-excel-o'></i>&nbsp; Ver Todas",
            "class": "btn btn-success bg-color-blue",
            click: function () { abrir_reporte_datos_contribuyentes_predios_detallado(1); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
    if(auxdet==0)
    {
        autocompletar_haburb('habilitacion_urbana_det');
        auxdet=1;
    }
}
auxcpz=0;
function crear_dialogo_cantidad_por_zona()
{
    $("#dialog_cantidad_por_zona").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Reporte de cantidad de contribuyentes y predios por zonas :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_cantidad_por_zona(0); }
        },{
            html: "<i class='fa fa-file-excel-o'></i>&nbsp; Ver Todas",
            "class": "btn btn-success bg-color-blue",
            click: function () { abrir_reporte_cantidad_por_zona(1); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
    if(auxcpz==0)
    {
        autocompletar_haburb('habilitacion_urbana2');
        auxcpz=1;
    }
}
auxuso=0;
function crear_dialogo_por_uso()
{
    $("#dialog_por_uso").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Reporte Predios Por Uso :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_por_uso(0); }
        },{
            html: "<i class='fa fa-file-excel-o'></i>&nbsp; Ver Todas",
            "class": "btn btn-success bg-color-blue",
            click: function () { abrir_reporte_por_uso(1); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
    if(auxuso==0)
    {
        autocompletar_haburb('habilitacion_urbana3');
        auxuso=1;
    }
}

function crear_dialogo_monto_ep()
{
    $("#dialog_monto_ep_afecto_exonerado").dialog({
        autoOpen: false, modal: true, width: 630, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.:  Reporte monto de la emision predial Afecto y Exonerado :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_ep_afecto_exonerado(2); }
        },{
            html: "<i class='fa fa-file-excel-o'></i>&nbsp; Ver Resumen",
            "class": "btn btn-success bg-color-blue",
            click: function () { abrir_reporte_ep_afecto_exonerado(3); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
}
function crear_dialogo_ep_afecto_exonerado()
{
    $("#dialog_por_ep_afecto_exonerado").dialog({
        autoOpen: false, modal: true, width: 630, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.:  Reporte Número de  Contribuyentes de la emision predial Afecto y Exonerado :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_ep_afecto_exonerado(1); }
        },{
            html: "<i class='fa fa-file-excel-o'></i>&nbsp; Ver Resumen",
            "class": "btn btn-success bg-color-blue",
            click: function () { abrir_reporte_ep_afecto_exonerado(0); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
}
function crear_dialogo_autovaluo()
{
    $("#dialog_rep_autovaluo").dialog({
        autoOpen: false, modal: true, width: 630, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.:  Reporte Monto de Autovaluo Inafecto :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_autovaluo(1); }
        },{
            html: "<i class='fa fa-file-excel-o'></i>&nbsp; Ver Resumen",
            "class": "btn btn-success bg-color-blue",
            click: function () { abrir_reporte_autovaluo(0); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
}

auxded=0;

function crear_dialogo_deduccion_50UIT()
{
    $("#dialog_por_deduccion_50UIT").dialog({
        autoOpen: false, modal: true, width: 630, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Reporte de cantidad de contribuyentes con deduccion de 50 UIT :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_deduccion_50UIT(0); }
        },{
            html: "<i class='fa fa-file-excel-o'></i>&nbsp; Ver Todas",
            "class": "btn btn-success bg-color-blue",
            click: function () { abrir_reporte_deduccion_50UIT(1); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
    if(auxded==0)
    {
        autocompletar_haburb('habilitacion_urbana10');
        auxded=1;
    }
}
function crear_dialogo_bi_afecto_exonerado()
{
    $("#dialog_bi_afecto_exonerado").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp.:Reporte del Monto de la Base Imponible Afecto y Exonerado. :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_bi_afecto_exonerado(1); }
        },{
            html: "<i class='fa fa-file-excel-o'></i>&nbsp; Ver Resumen",
            "class": "btn btn-success bg-color-blue",
            click: function () { abrir_reporte_bi_afecto_exonerado(0); }
        },{
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
   
}
auxexo=0;
function crear_dialogo_cantidad_exonerados()
{
    $("#dialog_por_exonerados").dialog({
        autoOpen: false, modal: true, width: 630, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Reporte de cantidad de contribuyentes exonerados :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_exonerados(0); }
        },{
            html: "<i class='fa fa-file-excel-o'></i>&nbsp; Ver Todas",
            "class": "btn btn-success bg-color-blue",
            click: function () { abrir_reporte_exonerados(1); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
    if(auxexo==0)
    {
        autocompletar_haburb('habilitacion_urbana11');
        auxexo=1;
    }
}

function crear_dialogo_corriente()
{
    $("#dialog_corriente").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp.:Reporte impuesto Predial Corriente y No Corriente :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_corriente(); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
  
}
function crear_dialogo_fraccionamiento()
{
    $("#dialog_fraccionamiento").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp.:Reporte Fraccionamientos Realizados y Cancelados :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_fraccionamiento(); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
    if(aux1==0)
    {
        autocompletar_haburb('hab_urb');
        aux1=1;
    }
}

function crear_dialogo_caja()
{
    $("#dialog_caja").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp.:Reporte General de Caja :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_cajas(); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
   
}
auxma=0;

function crear_dialogo_morosidad_arbitrios()
{
    $("#dialog_por_morosidad_arbitrios").dialog({
        autoOpen: false, modal: true, width: 630, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Reporte de % de Morosidad de Arbitrios Municipales :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_morosidad_arbititros(0); }
        },{
            html: "<i class='fa fa-file-excel-o'></i>&nbsp; Ver Todas",
            "class": "btn btn-success bg-color-blue",
            click: function () { abrir_reporte_morosidad_arbititros(1); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
    if(auxma==0)
    {
        autocompletar_haburb('habilitacion_urbana15');
        auxma=1;
    }
}
auxra=0;
function crear_dialogo_recaudacion_arbitrios()
{    
    $("#dialog_por_recaudacion_arbitrios").dialog({
        autoOpen: false, modal: true, width: 630, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.:Reporte de la Recaudación de Abitrios Municipales por Zona :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_recaudacion_arbititros(0); }
        },{
            html: "<i class='fa fa-file-excel-o'></i>&nbsp; Ver Todas",
            "class": "btn btn-success bg-color-blue",
            click: function () { abrir_reporte_recaudacion_arbititros(1); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
    if(auxra==0)
    {
        autocompletar_haburb('habilitacion_urbana17');
        auxra=1;
    }
}


function crear_dialogo_importe_insoluto()
{
    $("#dialog_importe_insoluto").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header' ><h5>&nbsp&nbsp.: Monto de deuda transferida a Coactiva:.</h5></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_monto_trans_a_coactivo(); }
        },{
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
}
auxci=0;
function crear_dialogo_cuentas_imp()
{
    $("#dialog_cuentas_imp").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header' ><h5>&nbsp&nbsp.:Reporte de cuentas de Impuesto Predial:.</h5></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_cuentas_imp(); }
        },{
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
    if(auxci==0)
    {
        autocompletar_haburb('habilitacion_urbana25');
        auxci=1;
    }
}
auxca=0;
function crear_dialogo_cuentas_arbi()
{
    $("#dialog_cuentas_arbi").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header' ><h5>&nbsp&nbsp.:Reporte de cuentas de Arbitrios Municpales:.</h5></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_cuentas_arbi(); }
        },{
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
    if(auxca==0)
    {
        autocompletar_haburb('habilitacion_urbana26');
        auxca=1;
    }
}
function dlg_reportes_andrea(tipo)
{
    if (tipo===1)  {
        crear_dialogo_datos_contribuyentes();
         $('#habilitacion_urbana').val("");
         $('#hidden_habilitacion_urbana').val("");
    } 
    if (tipo===2) {
        crear_dialogo_datos_contribuyentes_p();
         $('#habilitacion_urbana1').val("");
         $('#hidden_habilitacion_urbana1').val("");
    }
    if (tipo===3) {
        crear_dialogo_contribuyentes_p_detallado();
         $('#habilitacion_urbana_det').val("");
         $('#hidden_habilitacion_urbana_det').val("");
    }
    if (tipo===4) {
        crear_dialogo_cantidad_por_zona();
         $('#habilitacion_urbana2').val("");
         $('#hidden_habilitacion_urbana2').val("");
    }
    if (tipo===5) {
        crear_dialogo_por_uso();
         $('#habilitacion_urbana3').val("");
         $('#hidden_habilitacion_urbana3').val("");
    }
    if (tipo===6) {
        crear_dialogo_bi_afecto_exonerado();
    }
    if (tipo===7) {
        crear_dialogo_monto_ep();
    }
    if (tipo===8) {
         crear_dialogo_ep_afecto_exonerado();
    }
     if (tipo===9) {
         crear_dialogo_autovaluo();
    }
    if (tipo===10) {
        crear_dialogo_deduccion_50UIT();
         $('#habilitacion_urbana10').val("");
         $('#hidden_habilitacion_urbana10').val("");
    }
    if (tipo===11) {
        crear_dialogo_cantidad_exonerados();
         $('#habilitacion_urbana11').val("");
         $('#hidden_habilitacion_urbana11').val("");
         
    }
    if (tipo===13) {
        crear_dialogo_corriente();
    } 
    if (tipo===15) {
        crear_dialogo_morosidad_arbitrios();
         $('#habilitacion_urbana15').val("");
         $('#hidden_habilitacion_urbana15').val("");
         
    }
    if (tipo===17) {
        crear_dialogo_recaudacion_arbitrios();
         $('#habilitacion_urbana17').val("");
         $('#hidden_habilitacion_urbana17').val("");
         
    }
    if (tipo===23) {
        crear_dialogo_importe_insoluto();        
    }
    if (tipo===25) {
         crear_dialogo_cuentas_imp();
         $('#habilitacion_urbana25').val("");
         $('#hidden_habilitacion_urbana25').val("");     
    }
    if (tipo===26) {
         crear_dialogo_cuentas_arbi();
         $('#habilitacion_urbana26').val("");
         $('#hidden_habilitacion_urbana26').val("");     
    }
    if (tipo===18) {
        crear_dialogo_cuentas_arbi();
    }
    if (tipo===101) {
        crear_dialogo_forma_adq();
    }
    if (tipo===102) {
        crear_dialogo_deudores();
    }
    if (tipo===103) {
        crear_dialogo_caja();
    }
    if (tipo===123) {
        abrir_rep();
    }
}

function abrir_rep()
{    window.open('reporte_constancia'+'');
}

function abrir_reporte_corriente()
{
    window.open('reporte_corriente/'+$('#anio_corriente').val()+'');
}
function abrir_reporte_fraccionamiento()
{
    window.open('reporte_fraccionamiento/'+$('#anio_fraccionamiento').val()+'/'+$('#select_estado').val());
}
function abrir_reporte_cajas()
{    window.open('reporte_cajas?ini='+$('#fec_ini_cajas').val()+'&fin='+$('#fec_fin_cajas').val()+'&id_agen='+$('#select_agencia').val()+'');
}
function abrir_reporte_datos_contribuyentes(tipo)
{
    if(tipo==0)
    {
        if ($("#hidden_habilitacion_urbana").val() == 0){
            mostraralertasconfoco("Debes Ingresar una Habilitacion Urbana","#hidden_habilitacion_urbana");
            return false;
        }
        window.open('listado_datos_contribuyentes/0'+'/'+$('#select_sup_anio_dc1').val()+'/'+$('#hidden_habilitacion_urbana').val()+''); 
    }
    if(tipo==1)
    {
         $('#habilitacion_urbana').val("");
         $('#hidden_habilitacion_urbana').val("");
         window.open('listado_datos_contribuyentes/1'+'/'+$('#select_sup_anio_dc1').val()+'/'+'0');
    }
        
}
function abrir_reporte_datos_contribuyentes_predios(tipo)
{   if(tipo==0)
    {
        if ($("#hidden_habilitacion_urbana1").val() == 0){
            mostraralertasconfoco("Debes Ingresar una Habilitacion Urbana","#hidden_habilitacion_urbana1");
            return false;
        }
        window.open('listado_contribuyentes_predios/0'+'/'+$('#select_sup_anio_pred').val()+'/'+$('#hidden_habilitacion_urbana1').val()+'');
    }
    if(tipo==1)
    {
         $('#habilitacion_urbana1').val("");$('#hidden_habilitacion_urbana1').val("");
         window.open('listado_contribuyentes_predios/1'+'/'+$('#select_sup_anio_pred').val()+'/'+'0');
    }
}
function abrir_reporte_datos_contribuyentes_predios_detallado(tipo)
{   if(tipo==0)
    {
        if ($("#hidden_habilitacion_urbana_det").val() == 0){
            mostraralertasconfoco("Debes Ingresar una Habilitacion Urbana","#hidden_habilitacion_urbana_det");
            return false;
        }
        window.open('listado_contribuyentes_predios_det/0'+'/'+$('#select_sup_anio_pred_det').val()+'/'+$('#hidden_habilitacion_urbana_det').val()+'');
    }
    if(tipo==1)
    {
         $('#habilitacion_urbana_det').val("");$('#hidden_habilitacion_urbana_det').val("");
         window.open('listado_contribuyentes_predios_det/1'+'/'+$('#select_sup_anio_pred_det').val()+'/'+'0');
    }
}
function abrir_reporte_cantidad_por_zona(tipo)
{   if(tipo==0)
    {
        if ($("#hidden_habilitacion_urbana2").val() == 0){
            mostraralertasconfoco("Debes Ingresar una Habilitacion Urbana","#hidden_habilitacion_urbana2");
            return false;
        }
        window.open('reporte_contribuyentes_predios_zonas/0'+'/'+$('#select_sup_anio_cpz').val()+'/'+$('#hidden_habilitacion_urbana2').val()+'');
    }
    if(tipo==1)
    {
         $('#habilitacion_urbana2').val("");$('#hidden_habilitacion_urbana2').val("");
         window.open('reporte_contribuyentes_predios_zonas/1'+'/'+$('#select_sup_anio_cpz').val()+'/'+'0');
    }   
}
function abrir_reporte_por_uso(tipo)
{
    if(tipo==0)
    {
        if ($("#hidden_habilitacion_urbana3").val() == 0){
            mostraralertasconfoco("Debes Ingresar una Habilitacion Urbana","#hidden_habilitacion_urbana3");
            return false;
        }
        window.open('reporte_emision_predial/0'+'/'+$('#select_sup_anio_uso').val()+'/'+$('#hidden_habilitacion_urbana3').val()+'/'+$('#select_uso_ep').val()+'');
    }
    if(tipo==1)
    {
         $('#habilitacion_urbana3').val("");$('#hidden_habilitacion_urbana3').val("");
         window.open('reporte_emision_predial/1'+'/'+$('#select_sup_anio_uso').val()+'/'+'0'+'/'+$('#select_uso_ep').val()+'');
    } 
    
}
function abrir_reporte_deduccion_50UIT(tipo)
{
    if(tipo==0)
    {
        if ($("#hidden_habilitacion_urbana10").val() == 0){
            mostraralertasconfoco("Debes Ingresar una Habilitacion Urbana","#hidden_habilitacion_urbana10");
            return false;
        }
        window.open('reporte_deduccion_50UIT/0'+'/'+$('#select_sup_anio_ded').val()+'/'+$('#hidden_habilitacion_urbana10').val()+'/'+$('#select_condicion_ded').val()+'');
    }
    if(tipo==1)
    {
         $('#habilitacion_urbana10').val("");$('#hidden_habilitacion_urbana10').val("");
         window.open('reporte_deduccion_50UIT/1'+'/'+$('#select_sup_anio_ded').val()+'/'+'0'+'/'+$('#select_condicion_ded').val()+'');
    }
    
}
function abrir_reporte_bi_afecto_exonerado(tipo)
{
    if(tipo==0)
    {
        window.open('reporte_bi_afecto_exonerado/0'+'/'+$('#select_bi_afec_exon').val()+'/'+$('#select_condicion_bi_afec_exon').val()+'');
    }
    if(tipo==1)
    {
        window.open('reporte_bi_afecto_exonerado/1'+'/'+$('#select_bi_afec_exon').val()+'/'+$('#select_condicion_bi_afec_exon').val()+'');
    }
    
}

function abrir_reporte_ep_afecto_exonerado(tipo)
{
    if(tipo==0)
    {
       window.open('reporte_ep_afecto_exonerado/0'+'/'+$('#select_ep_afec_exon').val()+'/'+$('#select_condicion_ep_afec_exon').val()+'');
    }
    if(tipo==1)
    {
        window.open('reporte_ep_afecto_exonerado/1'+'/'+$('#select_ep_afec_exon').val()+'/'+$('#select_condicion_ep_afec_exon').val()+'');
    } 
     if(tipo==2)
    {
       window.open('reporte_ep_afecto_exonerado/2'+'/'+$('#select_anio_monto_ep').val()+'/'+$('#select_condicion_monto_ep_afec_exon').val()+'');
    }
    if(tipo==3)
    {
        window.open('reporte_ep_afecto_exonerado/3'+'/'+$('#select_anio_monto_ep').val()+'/'+$('#select_condicion_monto_ep_afec_exon').val()+'');
    } 
}

function abrir_reporte_exonerados(tipo)
{
    if(tipo==0)
    {
        if ($("#hidden_habilitacion_urbana11").val() == 0){
            mostraralertasconfoco("Debes Ingresar una Habilitacion Urbana","#hidden_habilitacion_urbana11");
            return false;
        }
        window.open('reporte_exonerados/0'+'/'+$('#select_sup_anio_exo').val()+'/'+$('#hidden_habilitacion_urbana11').val()+'/'+$('#select_condicion_exo').val()+'');
    }
    if(tipo==1)
    {
         $('#habilitacion_urbana11').val("");$('#hidden_habilitacion_urbana11').val("");
         window.open('reporte_exonerados/1'+'/'+$('#select_sup_anio_exo').val()+'/'+'0'+'/'+$('#select_condicion_exo').val()+'');
    }
    
}
function abrir_reporte_morosidad_arbititros(tipo)
{
    if(tipo==0)
    {
        if ($("#hidden_habilitacion_urbana15").val() == 0){
            mostraralertasconfoco("Debes Ingresar una Habilitacion Urbana","#hidden_habilitacion_urbana15");
            return false;
        }
        window.open('reporte_morosidad_arbitrios/0'+'/'+$('#select_sup_anio_ma').val()+'/'+$('#hidden_habilitacion_urbana15').val()+'');
    }
    if(tipo==1)
    {
         $('#habilitacion_urbana15').val("");$('#hidden_habilitacion_urbana15').val("");
         window.open('reporte_morosidad_arbitrios/1'+'/'+$('#select_sup_anio_ma').val()+'/'+'0'+'');
    }
      
}
function abrir_reporte_recaudacion_arbititros(tipo)
{
    if(tipo==0)
    {
        if ($("#hidden_habilitacion_urbana17").val() == 0){
            mostraralertasconfoco("Debes Ingresar una Habilitacion Urbana","#hidden_habilitacion_urbana17");
            return false;
        }
        window.open('reporte_recaudacion_arbitrios/0'+'/'+$('#select_sup_anio_ra').val()+'/'+$('#hidden_habilitacion_urbana17').val()+'');   
    }
    if(tipo==1)
        {
             $('#habilitacion_urbana17').val("");$('#hidden_habilitacion_urbana17').val("");
             window.open('reporte_recaudacion_arbitrios/1'+'/'+$('#select_sup_anio_ra').val()+'/'+'0'+'');
        }
      
}
function abrir_reporte_monto_trans_a_coactivo()
{   
        window.open('reporte_monto_trans_a_coactivo/'+$('#select_sup_anio_imp_insol').val()+'/'+$('#select_doc').val()+'');   
}
function abrir_reporte_autovaluo(tipo)
{
    if(tipo==0)
    {
       window.open('reporte_ep_afecto_exonerado/0'+'/'+$('#select_ep_autovaluo').val()+'/'+$('#select_condicion_ep_autovaluo').val()+'');
    }
    if(tipo==1)
    {
        window.open('reporte_ep_afecto_exonerado/1'+'/'+$('#select_ep_autovaluo').val()+'/'+$('#select_condicion_ep_autovaluo').val()+'');
    } 
}
function abrir_reporte_cuentas_imp()
{   
        if ($("#hidden_habilitacion_urbana25").val() == 0){
            mostraralertasconfoco("Debes Ingresar una Habilitacion Urbana","#hidden_habilitacion_urbana25");
            return false;
        }
        window.open('reporte_monto_cuentas_imp'+'/'+$('#hidden_habilitacion_urbana25').val()+'/'+$('#select_anio25').val());   
}
function abrir_reporte_cuentas_arbi()
{   
        if ($("#hidden_habilitacion_urbana26").val() == 0){
            mostraralertasconfoco("Debes Ingresar una Habilitacion Urbana","#hidden_habilitacion_urbana26");
            return false;
        }
        window.open('reporte_monto_cuentas_arb'+'/'+$('#hidden_habilitacion_urbana26').val()+'/'+$('#select_anio26').val());   
}