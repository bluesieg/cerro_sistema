map.on('singleclick', function(evt) {

            mostrar=0;
            var fl = map.forEachFeatureAtPixel(evt.pixel, function (feature, layer) {
                
               
                
                if(layer.get('title')=='predios'&&mostrar==0)
                {
                    mostrar=1;
                    verlote(feature.get('id_lote'));
                    $("#dlg_sec").val(feature.get('codi_lote'));
                    $("#dlg_mzna").val(feature.get('codi_mzna'));
                    $("#dlg_lot").val(feature.get('sector'));
                    return false;
                    
                }
                if(layer.get('title')=='lotes'&&mostrar==0)
                {
                    mostrar=1;
                    $("#dlg_lot").val(feature.get('codi_lote'));
                    $("#dlg_mzna").val(feature.get('codi_mzna'));
                    $("#dlg_sec").val(feature.get('sector'));
                    viewlong_lote(feature.get('id_lote'));
                    return false;
                }
             
                
            });
    
});
function verlote(id)
{
    crear_dlg("dlg_predio_lote",900,"Informacion de Lote");
    traerpredionuevo(id);
    traerfoto(id);
}
function crear_dlg(dlg,ancho,titulo)
{
    $("#"+dlg).dialog({
    autoOpen: false, modal: true, width: ancho, show: {effect: "fade", duration: 300}, resizable: false,
    title: "<div class='widget-header'><h4>.: "+titulo+" :.</h4></div>",
    buttons: [
            {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-primary bg-color-red",
                click: function () {$(this).dialog("close");}
            }]
    }).dialog('open');
}
function traerpredionuevo(id)
{
    MensajeDialogLoadAjax('dlg_predio_lote', '.:: Cargando ...');
    $.ajax({url: 'traerlote/'+id+'/'+$("#anio_pred").val(),
    type: 'GET',
    success: function(r) 
    {
        if(r.length>0)
        {
            $("#input_pred_cod_cat").text(r[0].cod_cat);
            $("#input_pred_habilitacion").text(r[0].habilitacion);
            $("#input_pred_propietario").text(r[0].contribuyente);
        }
        MensajeDialogLoadAjaxFinish('dlg_predio_lote');
    },
    error: function(data) {
        mostraralertas("hubo un error, Comunicar al Administrador");
        console.log('error');
        console.log(data);
        MensajeDialogLoadAjaxFinish('dlg_predio_lote');
    }
    }); 
}
function traerfoto(id)
{
    MensajeDialogLoadAjax('dlg_img_view', '.:: Cargando ...');
    $.ajax({url: 'traefoto_lote_id/'+id,
    type: 'GET',
    success: function(r) 
    {
        if(r!=0)
        {
            $("#dlg_img_view").html('<center><img src="data:image/png;base64,'+r+'" width="85%"/></center>');
        }
        else
        {
            $("#dlg_img_view").html('<center><img src="img/recursos/Home-icon.png" width="85%"/></center>');
        }
        MensajeDialogLoadAjaxFinish('dlg_img_view');
    },
    error: function(data) {
        mostraralertas("hubo un error, Comunicar al Administrador");
        console.log('error');
        console.log(data);
        MensajeDialogLoadAjaxFinish('dlg_img_view');
    }
    }); 
}
function viewlong(id)
{
    $("#dlg_img_view_big").html("");
    $("#dlg_view_foto").dialog({
    autoOpen: false, modal: true, width: 1000, show: {effect: "fade", duration: 300}, resizable: false,
    title: "<div class='widget-header'><h4>.:  Foto del Predio :.</h4></div>",
    }).dialog('open');
  
        $("#dlg_img_view_big").html("");
        $("#dlg_view_foto").dialog({
        autoOpen: false, modal: true, width: 1000, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  Foto del Predio :.</h4></div>",
        }).dialog('open');
        $("#dlg_img_view_big").html($("#dlg_img_view").html());
    
}
function viewlong_lote(id)
{
    
    $("#dlg_img_view_big").html("");
    $("#dlg_view_foto").dialog({
    autoOpen: false, modal: true, width: 1000, show: {effect: "fade", duration: 300}, resizable: false,
    title: "<div class='widget-header'><h4>.:  Foto del Predio :.</h4></div>",
    }).dialog('open');
   MensajeDialogLoadAjax('dlg_view_foto', '.:: Cargando ...');
    $.ajax({url: 'traefoto_lote_id/'+id,
    type: 'GET',
    success: function(r) 
    {
        $("#dlg_img_view_big").html("");
        if(r!=0)
        {
            $("#dlg_img_view_big").html('<center><img src="data:image/png;base64,'+r+'" width="85%"/></center>');
        }
        else
        {
            $("#dlg_img_view_big").html('<center><img src="img/recursos/Home-icon.png" width="85%"/></center>');
        }
        MensajeDialogLoadAjaxFinish('dlg_view_foto');
        
        
    },
    error: function(data) {
        mostraralertas("hubo un error, Comunicar al Administrador");
        console.log('error');
        console.log(data);
        MensajeDialogLoadAjaxFinish('dlg_img_view');
    }
    }); 
        
    
}

function clicknewmznamasivo()
{
    $("#id_sector_masivo").val(0);
    $("#inicio").val('');
    $("#fin").val('');

    $("#dlg_manzana_masivo").dialog({
        autoOpen: false, modal: true, width: 400, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  CREAR MANZANAS MASIVO :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Guardar",
            "class": "btn btn-success bg-color-green",
            click: function () {
                create_mznas_masivo();
            }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {
                $(this).dialog("close");
            }
        }],
    });
    $("#dlg_manzana_masivo").dialog('open');
}

function create_mznas_masivo() {

    id_sect = $("#id_sector_masivo").val();
    inicio = $("#inicio").val();
    fin = $("#fin").val();
    xsector = $("#id_sector_masivo option:selected").html();

    // alert(id_sect);
    if (id_sect == "" || codi_mzna == "" || mzna_dist == "") {
        mostraralertasconfoco('* Los campos son obligatorios...', 'id_sector_nuevo_editar');
        return false;
    }

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'create_mzna_masivo',
            type: 'POST',
            data: {
                xsector: xsector,
                id_sect: id_sect,
                inicio: inicio,
                fin:fin
            },
            success: function (data) {
                $("#id_sector_masivo").val(id_sect);
                dialog_close('dlg_manzana_masivo');
                fn_actualizar_grilla('tabla_manzanas', 'list_mzns_sector?id_sec=' + id_sect );
                MensajeExito('Nueva Manzana', 'La Manzana se a creado correctamente.');
            },
            error: function (data) {
                mostraralertas('* Contactese con el Administrador...');
            }
        });
}


function new_dlg_map(obj)
{
    $("#dlg_map").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  NUEVA MANZANA :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Guardar",
            "class": "btn btn-success bg-color-green",
            click: function () {
                save_edit_manzana(1);
            }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {
                $(this).dialog("close");
            }
        }],
    });
    $("#dlg_map").dialog('open');

    $("#id").val(obj.get('gid'));
    $("#codigo").val(obj.get('nombre'));
    $("#sector").val(obj.get('mz_cat'));

}

function clickmodmzna()
{

    $("#dlg_manzana").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  EDITAR SECTOR :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Guardar",
            "class": "btn btn-success bg-color-green",
            click: function () {
                save_edit_manzana(2);
            }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {
                $(this).dialog("close");
            }
        }],
    });
    $("#dlg_manzana").dialog('open');


    MensajeDialogLoadAjax('dlg_manzana', '.:: Cargando ...');

    id = $("#current_id").val();
    $.ajax({url: 'catastro_mzns/'+id,
        type: 'GET',
        success: function(r)
        {
            $("#id_mzna").val(r[0].id_mzna);
            $("#id_sector_nuevo_editar").val(r[0].id_sect);
            $("#codi_mzna").val(r[0].codi_mzna);
            $("#mzna_dist").val(r[0].mzna_dist);
            MensajeDialogLoadAjaxFinish('dlg_manzana');

        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            MensajeDialogLoadAjaxFinish('dlg_manzana');
        }
    });
}


function get_mzns_por_sector(id_sec){
    //var map = new ol.Map("map");
    // add layers here"POINT(-71.546226195617 -16.3045550718574)"
   // map.setCenter(new ol.LonLat(-71.546226195617, -16.3045550718574), 5);

    if(id_sec != '0')
    {
        //alert(id_sec);
        MensajeDialogLoadAjax('map', '.:: CARGANDO ...');
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'get_centro_sector',
            type: 'POST',
            data: {codigo: id_sec+""},
            success: function (data) {
//                        $.alert(raz_soc + '- Ha sido Eliminado');
                //alert(data[0].lat + " / " + data[0].lon);
                map.getView().setCenter(ol.proj.transform([parseFloat(data[0].lat),parseFloat(data[0].lon)], 'EPSG:4326', 'EPSG:3857'));
                map.getView().setZoom(16);
            },
            error: function (data) {
                MensajeAlerta('Cartografía', 'Error.');
//                        mostraralertas('* Error al Eliminar Contribuyente...');
            }
        });

        //alert($("#departamento").val());

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'mznas_x_sector',
            type: 'POST',
            data: {codigo: id_sec+""},
            success: function (data) {
                //alert(data);
                $('#select_manzanas').html(data);
//                        $.alert(raz_soc + '- Ha sido Eliminado');
                //fn_actualizar_grilla('tabla_sectores', 'list_sectores');
                //dialog_close('dlg_nuevo_sector');
                //MensajeExito('Eliminar Sector', id + ' - Ha sido Eliminado');
            },
            error: function (data) {
                MensajeAlerta('Predios','No se encontró ningún predio.');
//                        mostraralertas('* Error al Eliminar Contribuyente...');
            }
        });

        

        
    }

    else{
        alert("Seleccione un sector");
    }

}

function label_manzanas(feature, resolution) {
    return new ol.style.Style({
        stroke: new ol.style.Stroke({
            color: 'red',
            width: 2
        }),
        fill: new ol.style.Fill({
            color: 'rgba(255, 0, 0, 0.1)'
        }),
        text: new ol.style.Text({
            text: feature.get('codi_mzna')
        })
    });
}

function label_lotes(feature) {
    return new ol.style.Style({
        stroke: new ol.style.Stroke({
            color: 'green',
            width: 1
        }),
        fill: new ol.style.Fill({
            color: 'rgba(0, 255, 0, 0.1)'
        }),
        text: new ol.style.Text({
            font: '12px Calibri,sans-serif',
            fill: new ol.style.Fill({ color: '#000' }),
            stroke: new ol.style.Stroke({
                color: '#fff', width: 2
            }),
            // get the text from the feature - `this` is ol.Feature
            // and show only under certain resolution
            text: map.getView().getZoom() > 17 ? feature.get('codi_lote') : ''
        })
         /*
        text: new ol.style.Text({
            text: feature.get('nom_lote')
        })
       text: map.getView().getZoom() > 12 ? feature.get('nom_lote') : ''*/
    });
}

function get_predios(){
    var habilitacion = $('#hidden_inp_habilitacion').val();
    var anio = $('#anio_pred').val();
    

    if($('#draw_predios').is(':checked')){
        if(habilitacion != '0')
        {
            MensajeDialogLoadAjax('dlg_map', '.:: Cargando ...');
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: 'get_predios_rentas',
                type: 'POST',
                data: {codigo: habilitacion,
                        anio:anio},
                success: function (data) {
                    map.removeLayer(lyr_predios4);
                    var format_predios4 = new ol.format.GeoJSON();
                    var features_predios4 = format_predios4.readFeatures(JSON.parse(data[0].json_build_object),
                        {dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'});
                    var jsonSource_predios4 = new ol.source.Vector({
                        attributions: [new ol.Attribution({html: '<a href=""></a>'})],
                    });
                    jsonSource_predios4.addFeatures(features_predios4);
                    lyr_predios4 = new ol.layer.Vector({
                        source:jsonSource_predios4,
                        style: label_predios,
                        title: "predios"
                    });

                    map.addLayer(lyr_predios4);
                    MensajeDialogLoadAjaxFinish('dlg_map', '.:: CARGANDO ...');

                },
                error: function (data) {
                    MensajeDialogLoadAjaxFinish('dlg_map', '.:: CARGANDO ...');
                    MensajeAlerta('Predios', 'No se encontraron predios en esta Habilitación');
//                        mostraralertas('* Error al Eliminar Contribuyente...');
                }
            });


        }
        else{
            $("#draw_predios").prop("checked",false);
            MensajeAlerta('Habilitacion', 'No se encontraron predios en esta Habilitación');
        }
    }
    else {
        map.removeLayer(lyr_predios4);
    }

}

function label_predios(feature) {
    return new ol.style.Style({
        stroke: new ol.style.Stroke({
            color: 'green',
            width: 1
        }),
        fill: new ol.style.Fill({
            color: 'rgb(255, 255, 0)'
        }),
        text: new ol.style.Text({
            font: '12px Calibri,sans-serif',
            fill: new ol.style.Fill({ color: '#000' }),
            stroke: new ol.style.Stroke({
                color: '#fff', width: 2
            }),
            // get the text from the feature - `this` is ol.Feature
            // and show only under certain resolution
            text: map.getView().getZoom() > 16 ? feature.get('codi_lote') : ''
        })
      
    });
}



var doHover = false;
var onSingleClick2 = function(evt) {
    if (doHover) {
        return;
    }
    var pixel = map.getEventPixel(evt.originalEvent);
    var coord = evt.coordinate;
    var popupField;
    var popupText = '';
    var currentFeature;
    var currentFeatureKeys;
    map.forEachFeatureAtPixel(pixel, function(feature, layer) {
        currentFeature = feature;
        currentFeatureKeys = currentFeature.getKeys();

        //alert(currentFeatureKeys);
        if(lyr_manzanas2 == layer){
            //alert(1);
            new_dlg_map(currentFeature);
        }

    });
};

var layerSwitcher = new ol.control.LayerSwitcher({
    tipLabel: 'Légende' // Optional label for button
});
map.addControl(layerSwitcher);
autocompletar_haburb('inp_habilitacion');

 function autocompletar_haburb(textbox){
    $.ajax({
        type: 'GET',
        url: 'autocomplete_hab_urba',
        success: function (data) {
            
            var $datos = data;
            $("#"+ textbox).autocomplete({
                source: $datos,
                focus: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    return false;
                },
                select: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hidden_"+ textbox).val(ui.item.value);
                    traer_hab_by_id(ui.item.value);
                    return false;
                }
            });
        }
    });
}

function traer_hab_by_id(id)
{
    map.removeLayer(lyr_sectores_cat1);
    map.removeLayer(lyr_lotes3);
    MensajeDialogLoadAjax('dlg_map', '.:: Cargando ...');
    $.ajax({url: 'gethab_urb/'+id,
                type: 'GET',
                async: false,
                success: function(r)
                {
                    geojson_sectores_cat1 = JSON.parse(r[0].json_build_object);
                    var format_sectores_cat1 = new ol.format.GeoJSON();
                    var features_sectores_cat1 = format_sectores_cat1.readFeatures(geojson_sectores_cat1,
                            {dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'});
                    var jsonSource_sectores_cat1 = new ol.source.Vector({
                        attributions: [new ol.Attribution({html: '<a href=""></a>'})],
                    });
                    jsonSource_sectores_cat1.addFeatures(features_sectores_cat1);
                    lyr_sectores_cat1 = new ol.layer.Vector({
                        source:jsonSource_sectores_cat1,
                        style: polygonStyleFunction,
                        title: "Habilitaciones Urbanas"
                    });
                    map.addLayer(lyr_sectores_cat1);
                    var extent = lyr_sectores_cat1.getSource().getExtent();
                    map.getView().fit(extent, map.getSize());
                    traer_lote_by_hab(id);

                }
            });
}
function traer_lote_by_hab(id)
{
    $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'get_lotes_x_sector',
            type: 'POST',
            data: {codigo: id},
            success: function (data) {
                //alert(data[0].json_build_object);
                //alert(geojson_manzanas2);
                map.removeLayer(lyr_lotes3);
                var format_lotes3 = new ol.format.GeoJSON();
                var features_lotes3 = format_lotes3.readFeatures(JSON.parse(data[0].json_build_object),
                    {dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'});
                var jsonSource_lotes3 = new ol.source.Vector({
                    attributions: [new ol.Attribution({html: '<a href=""></a>'})],
                });
                //vectorSource.addFeatures(features_manzanas2);
                jsonSource_lotes3.addFeatures(features_lotes3);
                lyr_lotes3 = new ol.layer.Vector({
                    source:jsonSource_lotes3,
                    style: label_lotes,
                    title: "lotes"
                });
                map.addLayer(lyr_lotes3);
                MensajeDialogLoadAjaxFinish('dlg_map');

            },
            error: function (data) {
                MensajeAlerta('Predios','No se encontró ningún predio.');
            }
        });
}