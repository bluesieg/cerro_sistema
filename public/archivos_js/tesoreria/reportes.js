

function dlg_teso_reportes(tipo)
{
    if(tipo==1)
    {
        crear_dialogo_por_partida();
    }
    if(tipo == 2)
    {
        crear_dialogo_por_tributo()
    }
    if(tipo == 3)
    {
        crear_dialogo_por_zonas()
    }
            
}
function crear_dialogo_por_partida()
{
    $("#dialog_por_partida").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:Ingresos /Partida:</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte(1); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
}

var aux1=0;
function crear_dialogo_por_tributo()
{
    $("#dialog_por_tributo").dialog({
        autoOpen: false, modal: true, width: 850, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:Ingresos /tributo:</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_reporte(2); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
    if(aux1==0)
    {
        autocompletar_tributo('tributo');
        aux1=1;
    }
    
    
}

function abrir_reporte(tipo)
{
    if(tipo==1)
    {
        if($("#fec_ini").val()==""||$("#fec_fin").val()=="")
        {
            mostraralertasconfoco("Ingresar fechas correctamente","#fec_ini");
            return false;
        }
       window.open('ver_rep_tesoreria/1?ini='+$("#fec_ini").val()+'&fin='+$("#fec_fin").val());
       return false;
    }
    if(tipo==2)
    {
        if ($("#hiddentributo").val() == 0){
            mostraralertasconfoco("Debes Ingresar un Tributo","#tributo");
            return false;
        }
        if($("#fec_ini_tributo").val()==""||$("#fec_fin_tributo").val()=="")
        {
            mostraralertasconfoco("Ingresar fechas correctamente","#fec_ini_tributo");
            return false;
        }
       window.open('ver_rep_tesoreria/2?ini='+$("#fec_ini").val()+'&fin='+$("#fec_fin_tributo").val()+'&id_tributo='+$('#hiddentributo').val());
       return false;
    }
    if(tipo==3)
    {
     
       window.open('ver_rep_tesoreria/3?ini='+$("#fec_ini").val()+'&fin='+$("#fec_fin_tributo").val()+'&id_tributo='+$('#hiddentributo').val());
       return false;
    }
   
   
    
}


