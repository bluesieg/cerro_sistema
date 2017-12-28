

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

function dlg_reporte_contribuyentes(tipo)
{
    if (tipo===0) {
        crear_dialogo_contribuyentes();
    } 
}

function abrir_reporte_contribuyente()
{
    window.open('reporte_contribuyentes/'+ $('#selantra_r0').val()+ '/'+ $('#min').val()+ '/' + $('#max').val() + '/'  +$('#num_reg').val());
}


/********************************REPORTE_LISTADO_DATOS_CONTRIBUYENTES**************************************************/

function crear_dialogo_listado_datos_contribuyentes()
{
    $("#dialog_listado_datos_contribuyente").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Listado de Datos de los Contribuyentes :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_listado_contribuyente(); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
}

function dlg_listado_datos_contribuyentes(tipo)
{
    if (tipo===0) {
        crear_dialogo_listado_datos_contribuyentes();
    } 
}


function abrir_reporte_listado_contribuyente()
{
    window.open('listado_datos_contribuyentes/'+$('#select_sup_anio_dc').val()+'/'+$('#select_sector_dc').val());
}


/********************************REPORTE_LISTADO_DATOS_CONTRIBUYENTES_PREDIOS**************************************************/

function crear_dialogo_listado_datos_contribuyentes_predios()
{
    $("#dialog_listado_datos_contribuyente_predios").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Listado de Datos de los Contribuyentes y Predios :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_listado_contribuyente_predio(); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
}

function dlg_listado_datos_contribuyentes_predios(tipo)
{
    if (tipo===0) {
        crear_dialogo_listado_datos_contribuyentes_predios();
    } 
}


function abrir_reporte_listado_contribuyente_predio()
{
    window.open('listado_contribuyentes_predios/'+$('#select_sup_anio_dcp').val()+'/'+$('#select_sect_dcp').val()+'');
}

/********************************REPORTE_CANTIDAD_CONTRIBUYENTES_EXONERADOS**************************************************/

function crear_dialogo_contribuyentes_exonerados()
{
    $("#dialog_reporte_contribuyentes_exonerados").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Listado de Datos de los Contribuyentes y Predios :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_contribuyentes_exonerados(); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
}

function dlg_reporte_contribuyentes_exonerados(tipo)
{
    if (tipo===0) {
        crear_dialogo_contribuyentes_exonerados();
    } 
}

function abrir_reporte_contribuyentes_exonerados()
{
    window.open('reporte_contribuyentes_exonerados/'+$('#selantra_5').val()+'/'+$('#selsec_5').val()+'/'+$('#selcond_5').val()+'');
}

/********************************REPORTE_CANTIDAD_CONTRIBUYENTES_DEDUCCION_50UIT**************************************************/


function crear_dialogo_reporte_cantidad_contribuyentes()
{
    $("#dialog_reporte_cantidad_contribuyentes").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Listado de Datos de los Contribuyentes y Predios :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_cantidad_contribuyentes(); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
}

function dlg_reporte_cantidad_contribuyentes(tipo)
{
    if (tipo===0) {
        crear_dialogo_reporte_cantidad_contribuyentes();
    } 
}

function abrir_reporte_cantidad_contribuyentes()
{
    window.open('reporte_cantidad_contribuyentes/'+$('#selantra_7').val()+'/'+$('#selsec_7').val()+'');
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


/******* NUEVOS
/********************************REPORTE_DE_CONTRIBUYENTES Y PREDIOS************************************************************/

function crear_dialogo_reporte_contribuyentes_predios()
{
    $("#dialog_reporte_contribuyente_predio").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Reporte de cantidad de contribuyentes y predios por zonas :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_contribuyente_predio(); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
}

function dlg_reporte_contribuyentes_predios(tipo)
{
    if (tipo===0) {
        crear_dialogo_reporte_contribuyentes_predios();
    } 
}

function abrir_reporte_contribuyente_predio()
{
    window.open('reporte_contribuyentes_predios_zonas/'+$('#select_sup_anio_rcp').val()+'/'+$('#select_sector_rcp').val()+'');
}


/********************************REPORTE_EMISION_PREDIAL_POR_USO************************************************************/

function crear_dialogo_reporte_emision_predial()
{
    $("#dialog_emision_predial").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Reporte Predios Por Uso :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_emision_predial(); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
}


function dlg_reporte_emision_predial_uso(tipo)
{
    if (tipo===0) {
        crear_dialogo_reporte_emision_predial();
    } 
}

function abrir_reporte_emision_predial()
{
    window.open('reporte_emision_predial/'+$('#select_anio_ep').val()+'/'+$('#select_sec_ep').val()+'/'+$('#select_uso_ep').val()+'');
}

/********************************REPORTE_CANTIDAD_CONTRIBUYENTES_POR_CONDICION Y MONTO DE LA BASE IMPONIBLE************************************************************/

function crear_dialogo_cant_cont_ded_mont_bas_imp()
{
    $("#dialog_cant_cont_ded_mont_bas_imp").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Reporte Predios Por Uso :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_cant_cont_ded_mont_bas_imp(); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
}
var aux1=0;

function crear_dialogo_por_zona()
{
    $("#dialog_por_zona").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Reporte impuesto Predial por Hab. Urbana - Zona :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_por_zona(); }
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
    if(aux1==0)
    {
        autocompletar_haburb('hab_urb');
        aux1=1;
    }
}

function dlg_reporte_cant_cont_ded_mont_bas_imp(tipo)
{
    if (tipo===0) {
        crear_dialogo_cant_cont_ded_mont_bas_imp();
    } 
}
function dlg_reporte_por_zona(tipo)
{
    if (tipo===0) {
        crear_dialogo_por_zona();
    } 
    if (tipo===1) {
        crear_dialogo_corriente();
    } 
}


function abrir_reporte_cant_cont_ded_mont_bas_imp()
{
    window.open('reporte_cant_cont_ded_mont_bas_imp/'+$('#select_anio_ccdmbi').val()+'/'+$('#select_sec_ccdmbi').val()+'/'+$('#select_condicion_ccdmbi').val()+'');
}
function abrir_reporte_por_zona()
{
    window.open('reporte_cant_cont_ded_mont_bas_imp/'+$('#select_anio_ccdmbi').val()+'/'+$('#select_sec_ccdmbi').val()+'/'+$('#select_condicion_ccdmbi').val()+'');
}
function abrir_reporte_corriente()
{
    window.open('reporte_cant_cont_ded_mont_bas_imp/'+$('#select_anio_ccdmbi').val()+'/'+$('#select_sec_ccdmbi').val()+'/'+$('#select_condicion_ccdmbi').val()+'');
}

/********************************REPORTEs andrea************************************************************/
