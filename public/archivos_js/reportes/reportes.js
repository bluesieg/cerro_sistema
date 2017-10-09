
/*
function abrir_rep()
{
    $sector = $('#selsec').val();
    $mzna = $('#selmnza').val();
    //Id=$('#table_Contribuyentes').jqGrid ('getGridParam', 'selrow');
    //alert(Id + "/" + $sector + "/" + $mzna);
    window.open('pre_rep_contr/'+$sector+'/'+$mzna);
}
*/
function limpiar_reporte(){
    $('#selsec').val('0');
    $('#selmnza').val('0');
    $('#selantra').val('0');
    $('#selec_hab_urb').val('0');
    $('#hab_urb').val('');
    $('#descripcion_subtitulo').val('');
}

function dlg_new_reporte_0(num_rep){
    //limpiar_reporte_3();
    //$titulo = $('#titulo_r'+num_rep).html();
    //alert($titulo);
    //$('#descripcion_subtitulo').html('Reporte de ' + $titulo + ':');
    $("#dialog_reporte_contr_0").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Listado de Contribuyentes(Pricos,Mecos,Pecos) :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_rep(num_rep); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');

}

function dlg_new_reporte(num_rep){
    limpiar_reporte();
    $titulo = $('#titulo_r'+num_rep).html();
    //alert($titulo);
    $('#descripcion_subtitulo').html('Reporte de ' + $titulo + ':');
    $("#dialog_reporte_contr").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Listado de datos contribuyentes y predios :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_rep(num_rep); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
    autocompletar_hab_urb('hab_urb');
}

function dlg_new_reporte_4(num_rep){
    //limpiar_reporte_3();
    //$titulo = $('#titulo_r'+num_rep).html();
    //alert($titulo);
    //$('#descripcion_subtitulo').html('Reporte de ' + $titulo + ':');
    $("#dialog_reporte_contr_4").dialog({
        autoOpen: false, modal: true, width: 300, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Reporte de cantidad de contribuyentes y predios por zonas :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_rep(num_rep); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');

}

function dlg_new_reporte_5(num_rep){
    //limpiar_reporte_3();
    //$titulo = $('#titulo_r'+num_rep).html();
    //alert($titulo);
    //$('#descripcion_subtitulo').html('Reporte de ' + $titulo + ':');
    $("#dialog_reporte_contr_5").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Reporte de cantidad de contribuyentes exonerados :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_rep(num_rep); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');

}

function dlg_new_reporte_6(num_rep){
    //limpiar_reporte_3();
    //$titulo = $('#titulo_r'+num_rep).html();
    //alert($titulo);
    //$('#descripcion_subtitulo').html('Reporte de ' + $titulo + ':');
    $("#dialog_reporte_contr_6").dialog({
        autoOpen: false, modal: true, width: 400, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Reporte número de Predios de la emision predial por Usos :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_rep(num_rep); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');

}

function dlg_new_reporte_7(num_rep){
    //limpiar_reporte_3();
    //$titulo = $('#titulo_r'+num_rep).html();
    //alert($titulo);
    //$('#descripcion_subtitulo').html('Reporte de ' + $titulo + ':');
    $("#dialog_reporte_contr_7").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Reporte de cantidad de contribuyentes con deducción de 50 UIT :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { abrir_rep(num_rep); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');

}


$current_tab=1;
function abrir_rep(num_rep)
{
    if(num_rep == 0) {

        window.open('pre_rep_prin_contr/'+ $('#selantra_r0').val()+ '/'+ $('#min').val()+ '/' + $('#max').val() + '/'  +$('#num_reg').val());
    }

    if($current_tab == 1 && num_rep == 1) {
        //alert('1-1');
        $sector = $('#selsec').val();
        $mzna = $('#selmnza').val();
        $anio = $('#selantra').val();
        //Id=$('#table_Contribuyentes').jqGrid ('getGridParam', 'selrow');
        //alert(Id + "/" + $sector + "/" + $mzna);
        window.open('pre_rep_contr/'+$sector+'/'+$mzna+'/'+$anio);
    }
    if($current_tab == 2 && num_rep == 1){
        //alert('1-2');
        var anio_hab_urb = $('#selec_hab_urb').val();
        var id_hab_urba = $('#hiddentxt_hab_urb').val();
        if(id_hab_urba != '')
            window.open('pre_rep_contr_hab_urb/'+id_hab_urba+'/'+anio_hab_urb);
        else
            MensajeAlerta('Reporte Contrituyentes', ' Habilitación Urbana no Válida.');
    }

    if($current_tab == 1 && num_rep == 2) {
        //alert('2');
        $sector = $('#selsec').val();
        $mzna = $('#selmnza').val();
        $anio = $('#selantra').val();
        //Id=$('#table_Contribuyentes').jqGrid ('getGridParam', 'selrow');
        //alert(Id + "/" + $sector + "/" + $mzna);
        window.open('pre_rep_contr_otro/'+$sector+'/'+$mzna+'/'+$anio);
    }

    if($current_tab == 1 && num_rep == 2) {
        //alert('2');
        var anio_hab_urb = $('#selec_hab_urb').val();
        var id_hab_urba = $('#hiddentxt_hab_urb').val();
        window.open('pre_rep_contr_pred_hu/'+id_hab_urba+'/'+anio_hab_urb);
    }

    if(num_rep == 4) {
        //alert('2');
        $anio_r4 = $('#selan_r4').val();
        window.open('pre_rep_contr_r4/'+$anio_r4);
    }
    if(num_rep == 5){
        window.open('pre_rep_condic/'+ $('#selantra_5').val() +'/' + $('#selsec_5').val() + '/' + $('#selcond_5').val());
    }
    if(num_rep == 6){
        window.open('pre_rep_num_pred_uso/' +  $('#selantra_6').val() +'/' + $('#selsec_6').val() + '/' + $('#seluso_6').val());
    }
    if(num_rep == 7){
        window.open('pre_rep_adult_pensio/' +  $('#selantra_7').val() +'/' + $('#selsec_7').val());
    }
}

function autocompletar_hab_urb(textbox) {
    $.ajax({
        type: 'GET',
        url: 'autocomplete_hab_urb',
        success: function (data) {
            var $local_sourcedoctotodo = data;
            $("#" + textbox).autocomplete({
                source: $local_sourcedoctotodo,
                focus: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hiddentxt_" + textbox).val(ui.item.value);
                    $("#" + textbox).attr('maxlength', ui.item.label.length);
                    return false;
                },
                select: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hiddentxt_" + textbox).val(ui.item.value);
                    return false;
                }
            });
            /*.bind("autocompletesearchcomplete", function(event, contents) {
             if(contents.length<1){
             $("#results").html("No Entries Found");
             }
             else{
             $("#results").html("");
             }

             });*/
        }
    });
}

function current_tab(id_report){
    $current_tab=id_report;
}


function callpredtab()
{
    $("#selmnza").html('');
    MensajeDialogLoadAjax('selmnza', '.:: CARGANDO ...');
    $.ajax({url: 'selmzna?sec='+$("#selsec").val(),
        type: 'GET',
        success: function(r)
        {
            $("#selmnza").append('<option value="0"> -- TODOS -- </option>');
            $(r).each(function(i, v){ // indice, valor
                $("#selmnza").append('<option value="' + v.id_mzna + '">' + v.codi_mzna + '</option>');
            })
            MensajeDialogLoadAjaxFinish('selmnza');
        },
        error: function(data) {
            console.log('error');
            console.log(data);
        }
    });
//
}

function callpredtab_6()
{
    $("#selmnza_6").html('');
    MensajeDialogLoadAjax('selmnza_6', '.:: CARGANDO ...');
    $.ajax({url: 'selmzna?sec='+$("#selsec").val(),
        type: 'GET',
        success: function(r)
        {
            $("#selmnza_6").append('<option value="0"> -- TODOS -- </option>');
            $(r).each(function(i, v){ // indice, valor
                $("#selmnza_6").append('<option value="' + v.id_mzna + '">' + v.codi_mzna + '</option>');
            })
            MensajeDialogLoadAjaxFinish('selmnza_6');
        },
        error: function(data) {
            console.log('error');
            console.log(data);
        }
    });
//
}
