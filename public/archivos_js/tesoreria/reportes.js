

function dlg_teso_reportes(tipo)
{
    if (tipo==1){
        
       
         crear_dialogo_usu();
    } 
        
}
function crear_dialogo_usu()
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

function abrir_reporte(tipo)
{
    if(tipo==1)
    {
        if($("#fec_ini").val()==""||$("#fec_fin").val()=="")
        {
            mostraralertasconfoco("Ingresar fechas correctamente","#fecini");
            return false;
        }
       window.open('ver_rep_tesoreria?ini='+$("#fec_ini").val()+'&fin='+$("#fec_fin").val());
       return false;
    }
   
   
    
}




