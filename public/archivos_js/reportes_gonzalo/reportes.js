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



/*********************************************REPORTES GERENCIALES********************************************/

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
        },  {
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
auxcon=0;
function crear_dialogo_por_condicion()
{
    $("#dialog_por_condicion").dialog({
        autoOpen: false, modal: true, width: 630, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Reporte Predios Por Condicion :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte_cant_cont_ded_mont_bas_imp(0); }
        },{
            html: "<i class='fa fa-file-excel-o'></i>&nbsp; Ver Todas",
            "class": "btn btn-success bg-color-blue",
            click: function () { abrir_reporte_cant_cont_ded_mont_bas_imp(1); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
    if(auxcon==0)
    {
        autocompletar_haburb('habilitacion_urbana4');
        auxcon=1;
    }
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
auxded=0;
function crear_dialogo_cantidad_exonerados()
{
    $("#dialog_por_exonerados").dialog({
        autoOpen: false, modal: true, width: 630, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.:Reporte de Cantidad de Contribuyentes exonerados. :.</h4></div>",
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
    if(auxded==0)
    {
        autocompletar_haburb('habilitacion_urbana10');
        auxded=1;
    }
}
auxep=0;
function crear_dialogo_ep_afecto_exonerado()
{
    $("#dialog_por_ep_afecto_exonerado").dialog({
        autoOpen: false, modal: true, width: 630, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.:  Reporte Número de  Contribuyentes de la emision predial Afecto y Exonerado :.</h4></div>",
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
    if(auxep==0)
    {
        autocompletar_haburb('habilitacion_urbana10');
        auxep=1;
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
         $('#habilitacion_urbana3').val("");
         $('#hidden_habilitacion_urbana3').val("");
    }
    if (tipo===8) {
        crear_dialogo_ep_afecto_exonerado();
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
    if (tipo===7) {
        crear_dialogo_por_condicion();
         $('#habilitacion_urbana4').val("");
         $('#hidden_habilitacion_urbana4').val("");
         
    }
    if (tipo===13) {
        crear_dialogo_corriente();
    } 
    
    if (tipo===18) {
        crear_dialogo_fraccionamiento();
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
}


function abrir_reporte_por_zona()
{
    if ($("#hiddenhab").val() == 0){
            mostraralertasconfoco("Debes Ingresar una Habilitacion Urbana","#hab_urb");
            return false;
    }
    window.open('listado_datos_contribuyentes/'+$('#select_sup_anio_dc1').val()+'/'+$('#hiddenhab').val()+'');
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

function abrir_reporte_cant_cont_ded_mont_bas_imp(tipo)
{
    if(tipo==0)
    {
        if ($("#hidden_habilitacion_urbana4").val() == 0){
            mostraralertasconfoco("Debes Ingresar una Habilitacion Urbana","#hidden_habilitacion_urbana4");
            return false;
        }
        window.open('reporte_cant_cont_ded_mont_bas_imp/0'+'/'+$('#select_sup_anio_con').val()+'/'+$('#hidden_habilitacion_urbana4').val()+'/'+$('#select_condicion_ccdmbi').val()+'');
    }
    if(tipo==1)
    {
         $('#habilitacion_urbana4').val("");$('#hidden_habilitacion_urbana4').val("");
         window.open('reporte_cant_cont_ded_mont_bas_imp/1'+'/'+$('#select_sup_anio_con').val()+'/'+'0'+'/'+$('#select_condicion_ccdmbi').val()+'');
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
function abrir_reporte_exonerados(tipo)
{
    if(tipo==0)
    {
        if ($("#hidden_habilitacion_urbana11").val() == 0){
            mostraralertasconfoco("Debes Ingresar una Habilitacion Urbana","#hidden_habilitacion_urbana11");
            return false;
        }
        window.open('reporte_exonerados/0'+'/'+$('#select_sup_anio_exo').val()+'/'+$('#hidden_habilitacion_urbana11').val()+'/'+''+'');
    }
    if(tipo==1)
    {
         $('#habilitacion_urbana11').val("");$('#hidden_habilitacion_urbana11').val("");
         window.open('reporte_exonerados/1'+'/'+$('#select_sup_anio_exo').val()+'/'+'0'+'/'+$('#select_condicion_ccdmbi').val()+'');
    }
    
}
function abrir_reporte_ep_afecto_exonerado(tipo)
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
         $('#habilitacion_urbana4').val("");$('#hidden_habilitacion_urbana10').val("");
         window.open('reporte_deduccion_50UIT/1'+'/'+$('#select_sup_anio_ded').val()+'/'+'0'+'/'+$('#select_condicion_ded').val()+'');
    }
    
}