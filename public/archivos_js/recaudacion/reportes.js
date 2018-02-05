function crear_dialogo()
{
    $("#dialog_op").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4 id='h4_dlg'></h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            id:"ver_rep1",
            click: function () { abrir_reporte(1); }
        },{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            id:"ver_rep2",
            click: function () { abrir_reporte(2); }
        }, {
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            id:"ver_rep3",
            click: function () { abrir_reporte(3); }
        }, {
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            id:"ver_rep4",
            click: function () { abrir_reporte(4); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
}

function abrir_reporte(tipo)
{
    window.open('ver_rep_op/'+$('#selantra').val()+'/'+tipo);
}

function dlg_op_reportes(tipo)
{
    crear_dialogo();
    if (tipo==1) {
        $("#ver_rep2").hide();
        $("#ver_rep3").hide();
        $("#ver_rep4").hide();
        $("#ver_rep1").show();
        $("#h4_dlg").text(".: Ordenes de Pago Notificadas :.")
    } 
    if (tipo==2) {
        $("#ver_rep2").show();
        $("#ver_rep1").hide();
        $("#ver_rep3").hide();
        $("#ver_rep4").hide();
        $("#h4_dlg").text(".: Ordenes de Pago Pagadas :.")
    } 
    if (tipo==3) {
        $("#ver_rep3").show();
        $("#ver_rep2").hide();
        $("#ver_rep1").hide();
        $("#ver_rep4").hide();
        $("#h4_dlg").text(".: Ordenes de Pago Sin Notificar :.")
    } 
    if (tipo==4) {
        $("#ver_rep3").hide();
        $("#ver_rep2").hide();
        $("#ver_rep1").hide();
        $("#ver_rep4").show();
        $("#h4_dlg").text(".: Ordenes de Pago Emitidas :.")
    } 
}
