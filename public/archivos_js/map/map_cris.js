
map.on('singleclick', function(evt) {
//    map.getLayers().forEach(function(el) {
//        if(el.get('title')=='lotes')
//        { }});
            //alert(el.target.getFeatures().getLength());
            mostrar=0;
            var fl = map.forEachFeatureAtPixel(evt.pixel, function (feature, layer) {
                if(layer.get('title')=='Agencias Juridiccion'&&mostrar==0)
                {   
                    mostrar=1;
                    $("#input_agencia").val(feature.get('text'));
                    crear_dlg("dlg_agencias",600,"Angencias");
                    return false;
                }
                if(layer.get('title')=='Limites'&&mostrar==0)
                {
                    mostrar=1;
                    $("#input_limit_area").text(feature.get('area_km2')+" Km2");
                    $("#input_limit_poblacion").text(feature.get('poblacion'));
                    $("#input_limit_poblacion").text(feature.get('poblacion'));
                    $("#input_limit_norte").text(feature.get('lim_norte'));
                    $("#input_limit_sur").text(feature.get('lim_sur'));
                    $("#input_limit_este").text(feature.get('lim_este'));
                    $("#input_limit_oeste").text(feature.get('lim_oeste'));
                    $("#input_limit_creacion").text(feature.get('creacion'));
                    $("#input_limit_perimetro").text(feature.get('perimetro'));
                    crear_dlg("dlg_limites",1100,"Cerro Colorado");
                    return false;
                }
                if(layer.get('title')=='lotes'&&mostrar==0)
                {
                    mostrar=1;
//                    alert(feature.get('id_lote'));
//                    alert(layer.get('title'));
                    viewlong(feature.get('id_lote'));
                }
                if(layer.get('title')=='Zona Urbana'||layer.get('title')=='Zona Agricola'||layer.get('title')=='Zona Eriaza'&&mostrar==0)
                {
                    mostrar=1;
                    $("#input_zona").val(feature.get('zona'));
                    crear_dlg("dlg_zonas_distritales",600,"Zonas Distritales");
                    return false;
                }
                
            });
    
});
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

var layerSwitcher = new ol.control.LayerSwitcher({
    tipLabel: 'Légende' // Optional label for button
});
map.addControl(layerSwitcher);

function get_mzns_por_sector(id_sec){
    if(id_sec != '0')
    {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'get_centro_sector',
            type: 'POST',
            data: {codigo: id_sec+""},
            success: function (data) {
                
                map.getView().setCenter(ol.proj.transform([parseFloat(data[0].lat),parseFloat(data[0].lon)], 'EPSG:4326', 'EPSG:3857'));
                map.getView().setZoom(16);
            },
            error: function (data) {
                MensajeAlerta('Cartografía', 'Error.');
            }
        });

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'mznas_x_sector',
            type: 'POST',
            data: {codigo: id_sec+""},
            success: function (data) 
            {
                $('#select_manzanas').html(data);
            },
            error: function (data) {
                MensajeAlerta('Predios','No se encontró ningún predio.');
            }
        });

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'geogetmznas_x_sector',
            type: 'POST',
            data: {codigo: id_sec+""},
            success: function (data) {
                //alert(data[0].json_build_object);
                //alert(geojson_manzanas2);
                map.removeLayer(lyr_manzanas2);
                var format_manzanas2 = new ol.format.GeoJSON();
                var features_manzanas2 = format_manzanas2.readFeatures(JSON.parse(data[0].json_build_object),
                    {dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'});
                var jsonSource_manzanas2 = new ol.source.Vector({
                    attributions: [new ol.Attribution({html: '<a href=""></a>'})],
                });
                //vectorSource.addFeatures(features_manzanas2);
                jsonSource_manzanas2.addFeatures(features_manzanas2);
                lyr_manzanas2 = new ol.layer.Vector({
                    source:jsonSource_manzanas2,
                    style: label_manzanas,
                    title: "manzanas"
                });

                map.addLayer(lyr_manzanas2);
                layersList[2] = lyr_manzanas2;
                //alert(layersList.length);
                MensajeDialogLoadAjaxFinish('map', '.:: CARGANDO ...');

            },
            error: function (data) {
                MensajeAlerta('Predios','No se encontró ningún predio.');
//                        mostraralertas('* Error al Eliminar Contribuyente...');
            }
        });

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'get_lotes_x_sector',
            type: 'POST',
            data: {codigo: id_sec+""},
            success: function (data) {
                map.removeLayer(lyr_lotes3);
                var format_lotes3 = new ol.format.GeoJSON();
                var features_lotes3 = format_lotes3.readFeatures(JSON.parse(data[0].json_build_object),
                    {dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'});
                var jsonSource_lotes3 = new ol.source.Vector({
                    attributions: [new ol.Attribution({html: '<a href=""></a>'})],
                });
                jsonSource_lotes3.addFeatures(features_lotes3);
                lyr_lotes3 = new ol.layer.Vector({
                    source:jsonSource_lotes3,
                    style: label_lotes,
                    title: "lotes"
                });

                map.addLayer(lyr_lotes3);
                layersList[3] = lyr_lotes3;
                //alert(layersList.length);
                MensajeDialogLoadAjaxFinish('map', '.:: CARGANDO ...');

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
            text: map.getView().getZoom() > 16 ? feature.get('codi_lote') : ''
        })
         /*
        text: new ol.style.Text({
            text: feature.get('nom_lote')
        })
       text: map.getView().getZoom() > 12 ? feature.get('nom_lote') : ''*/
    });
}

function viewlong(id)
{
    $("#dlg_img_view_big").html("");
    $("#dlg_view_foto").dialog({
    autoOpen: false, modal: true, width: 1000, show: {effect: "fade", duration: 300}, resizable: false,
    title: "<div class='widget-header'><h4>.:  Foto del Predio :.</h4></div>",
    }).dialog('open');
    MensajeDialogLoadAjax('dlg_img_view_big', '.:: Cargando ...');
        $.ajax({url: 'traefoto_lote_id/'+id,
        type: 'GET',
        success: function(r) 
        {
            if(r!=0)
            {
                $("#dlg_img_view_big").html('<center><img src="data:image/png;base64,'+r+'" height="500px" width="90%"/></center>');
            }
            else
            {
                $("#dlg_img_view_big").html('<center><img src="img/recursos/Home-icon.png" height="500px" width="65%"/></center>');
            }
            MensajeDialogLoadAjaxFinish('dlg_img_view_big');
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            MensajeDialogLoadAjaxFinish('dlg_img_view_big');
        }
        }); 
    
}
function dlg_sector(val)
{
    if(val==1)
    {
        texto='Traer Manzanas';
        $("#op_sel_sector").show();
    }
    if(val==2)
    {
        $("#op_sel_sector").hide();
        texto='Traer Lotes';
    }
   
    $("#dlg_selecciona_sector").dialog({
    autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
    title: "<div class='widget-header'><h4>.: Seleccione Sector :.</h4></div>",
    buttons: [
            {
                html: '<span class="btn-label"><i class="glyphicon glyphicon-new-window"></i></span>'+texto,
                "class": "btn btn-labeled bg-color-green txt-color-white",
                click: function () 
                {
                    if(val==1)
                    {
                        crearmanzana(1)
                    }
                    if(val==2)
                    {
                        crearlotes();
                    }
                }
            },

            {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-primary bg-color-red",
                click: function () {$(this).dialog("close");MensajeDialogLoadAjaxFinish('map');}
            }]
    }).dialog('open');
    
}

function valida_capa(check)
{
    if($("#"+check).prop('checked'))
    {
        MensajeDialogLoadAjax('map', '.:: Cargando ...');
        if(check=='chk_limite')
        {
            crearlimites();
        }
        if(check=='chk_sector')
        {
            crearsector(0);
        }
        if(check=='chk_mzna')
        {
            dlg_sector(1);
        }
        if(check=='chk_lote')
        {
            dlg_sector(2);
        }
        if(check=='chk_hab_urb')
        {
            crearhaburb();
        }
        if(check=='chk_agencias')
        {
            crearagencia();
        }
        if(check=='chk_camaras')
        {
            crearcamaras();
        }
        if(check=='chk_vias')
        {
            crearvias();
        }
        if(check=='chk_z_urbana')
        {
             crear_z_urbana();
        }
        if(check=='chk_z_agricola')
        {
             crear_z_agricola();
        }
        if(check=='chk_z_eriaza')
        {
             crear_z_eriaza();
        }
        if(check=='chk_aportes')
        {
             crear_aportes();
        }
    }
    else
    {
        if(check=='chk_limite')
        {
            map.removeLayer(lyr_limites_distritales0);
        }
        if(check=='chk_sector')
        {
            map.removeLayer(lyr_sectores);
        }
        if(check=='chk_mzna')
        {
            map.removeLayer(lyr_manzanas);
        }
        if(check=='chk_lote')
        {
            map.removeLayer(lyr_lotes);
        }
        if(check=='chk_hab_urb')
        {
            map.removeLayer(lyr_hab_urb);
        }
        if(check=='chk_agencias')
        {
            map.removeLayer(lyr_agencias);
            map.removeLayer(lyr_agencias_fondos);
        }
        if(check=='chk_camaras')
        {
            map.removeLayer(lyr_camaras);
        }
        if(check=='chk_vias')
        {
            map.removeLayer(lyr_vias);
        }
        if(check=='chk_z_urbana')
        {
            map.removeLayer(lyr_z_urbana);
        }
        if(check=='chk_z_agricola')
        {
            map.removeLayer(lyr_z_agricola);
        }
        if(check=='chk_z_eriaza')
        {
            map.removeLayer(lyr_z_eriaza);
        }
        if(check=='chk_aportes')
        {
            map.removeLayer(lyr_aportes);
        }
    }
}
function crearlimites()
{
    $.ajax({url: 'mapa_cris_getlimites',
            type: 'GET',
//            async: false,
            success: function(r)
            {
                mapa_bd = JSON.parse(r[0].json_build_object);
                var format_limites_distritales0 = new ol.format.GeoJSON();
                var features_limites_distritales0 = format_limites_distritales0.readFeatures(mapa_bd,
                        {dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'});
                var jsonSource_limites_distritales0 = new ol.source.Vector({
                    attributions: [new ol.Attribution({html: '<a href=""></a>'})],
                });
                jsonSource_limites_distritales0.addFeatures(features_limites_distritales0);

                lyr_limites_distritales0 = new ol.layer.Vector({
                    source:jsonSource_limites_distritales0,
                    style: polygonStyleFunction,
                    title: "Limites",
                   
                });
                
                map.addLayer(lyr_limites_distritales0);
                var scale = new ol.control.ScaleLine();
                map.addControl(scale);
                var extent = lyr_limites_distritales0.getSource().getExtent();
                map.getView().fit(extent, map.getSize());
                var fullscreen = new ol.control.FullScreen();
                map.addControl(fullscreen);
                MensajeDialogLoadAjaxFinish('map');
            }
        });
}
        
function crearsector(tip)
{
    $.ajax({url: 'getsectores',
            type: 'GET',
//            async: false,
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
                lyr_sectores = new ol.layer.Vector({
                    source:jsonSource_sectores_cat1,
                    style: stylesector,
                    title: "Sectores Catastrales"
                });
                map.addLayer(lyr_sectores);
                //var extent = lyr_sectores.getSource().getExtent();
                //map.getView().fit(extent, map.getSize());
                if(tip==0)
                {
                    MensajeDialogLoadAjaxFinish('map');
                }
                
            }
        });
}
function stylesector(feature, resolution) {
    return new ol.style.Style({
        stroke: new ol.style.Stroke({
            color: 'blue',
            width: 2,
            lineCap: 'butt',
        }),
        fill: new ol.style.Fill({
            color: 'rgba(102, 102, 255, 0.3)',
        }),
        text: new ol.style.Text({
        //font: '12px Roboto',
        text: feature.get('sector')
        })
    });
}
function crearmanzana(val)
{
    $("#dlg_selecciona_sector").dialog('close');
    map.removeLayer(lyr_sectores);
    if(val>0)
    {
        crearsector(1);
    }
    $("#chk_sector").prop("checked", true);
    $.ajax({url: 'getmznas',
            type: 'GET',
//            async: false,
            data:{sector:$("#selsec").val()},
            success: function(r)
            {
                geojson_mzn_cat1 = JSON.parse(r[0].json_build_object);
                var format_sectores_cat1 = new ol.format.GeoJSON();
                var features_sectores_cat1 = format_sectores_cat1.readFeatures(geojson_mzn_cat1,
                        {dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'});
                var jsonSource_sectores_cat1 = new ol.source.Vector({
                    attributions: [new ol.Attribution({html: '<a href=""></a>'})],
                });
                jsonSource_sectores_cat1.addFeatures(features_sectores_cat1);
                lyr_manzanas = new ol.layer.Vector({
                    source:jsonSource_sectores_cat1,
                    style: stylemanzana,
                    title: "Manzanas Catastrales"
                });
                map.addLayer(lyr_manzanas);
                if(val==1)
                {
                    var extent = lyr_manzanas.getSource().getExtent();
                    map.getView().fit(extent, map.getSize());
                }
                MensajeDialogLoadAjaxFinish('map');
            }
        });
}
function stylemanzana(feature, resolution) {
    return new ol.style.Style({
        stroke: new ol.style.Stroke({
            color: 'green',
            width: 2,
            lineCap: 'butt',
        }),
        fill: new ol.style.Fill({
            color: 'rgba(0, 153, 0, 0.3)',
        }),
        text: new ol.style.Text({
        //font: '12px Roboto',
        text: feature.get('codi_mzna')
        })
    });
}
        
function crearlotes()
{
    $("#dlg_selecciona_sector").dialog('close');
     $("#chk_sector").prop("checked", true);
     $("#chk_mzna").prop("checked", true);
    map.removeLayer(lyr_sectores);
    map.removeLayer(lyr_manzanas);
    crearsector(1);
    crearmanzana(0);
    
    $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'get_lotes_x_sector_mapa',
            type: 'POST',
            data: {codigo: $("#selsec").val()+""},
            success: function (data) {
                var format_lotes3 = new ol.format.GeoJSON();
                var features_lotes3 = format_lotes3.readFeatures(JSON.parse(data[0].json_build_object),
                    {dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'});
                var jsonSource_lotes3 = new ol.source.Vector({
                    attributions: [new ol.Attribution({html: '<a href=""></a>'})],
                });
                jsonSource_lotes3.addFeatures(features_lotes3);
                lyr_lotes = new ol.layer.Vector({
                    source:jsonSource_lotes3,
                    style: stylelotes,
                    title: "lotes"
                });

                map.addLayer(lyr_lotes);
                var extent = lyr_lotes.getSource().getExtent();
                map.getView().fit(extent, map.getSize());
                MensajeDialogLoadAjaxFinish('map');
            },
            error: function (data) {
                MensajeAlerta('Predios','No se encontró ningún predio.');
//                        mostraralertas('* Error al Eliminar Contribuyente...');
            }
        });
}

function stylelotes(feature, resolution) {
    return new ol.style.Style({
        stroke: new ol.style.Stroke({
            color: 'red',
            width: 2,
            lineCap: 'butt',
        }),
        fill: new ol.style.Fill({
            color: 'rgba(180, 4, 17, 0.3)',
        }),
        text: new ol.style.Text({
        //font: '12px Roboto',
        text: feature.get('codi_lote')
        })
    });
}

function crearhaburb()
{
    $.ajax({url: 'gethab_urb_by_id/'+$("#selsec").val(),
            type: 'GET',
//            async: false,
            data:{sector:$("#selsec").val()},
            success: function(r)
            {
                geojson_hab_urb = JSON.parse(r[0].json_build_object);
                var format_sectores_cat1 = new ol.format.GeoJSON();
                var features_sectores_cat1 = format_sectores_cat1.readFeatures(geojson_hab_urb,
                        {dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'});
                var jsonSource_sectores_cat1 = new ol.source.Vector({
                    attributions: [new ol.Attribution({html: '<a href=""></a>'})],
                });
                jsonSource_sectores_cat1.addFeatures(features_sectores_cat1);
                lyr_hab_urb = new ol.layer.Vector({
                    source:jsonSource_sectores_cat1,
                    style: stylehaburb,
                    title: "Habilitacion Urbana"
                });
                map.addLayer(lyr_hab_urb);
                var extent = lyr_hab_urb.getSource().getExtent();
                map.getView().fit(extent, map.getSize());
                MensajeDialogLoadAjaxFinish('map');
            }
        });
}

function stylehaburb(feature, resolution) {
    return new ol.style.Style({
        stroke: new ol.style.Stroke({
            color: '#B40477',
            width: 2,
            lineCap: 'butt',
        }),
        fill: new ol.style.Fill({
            color: 'rgba(180, 4, 119 , 0.3)',
        }),
        text: new ol.style.Text({
        //font: '12px Roboto',
            text: map.getView().getZoom() > 14 ? feature.get('nomb_hab_urba') : "", 
            fill: new ol.style.Fill({
                color: 'white',
            }),
            stroke: new ol.style.Stroke({
                color: 'black',
                width: 2,
                lineCap: 'butt',
            }),
        })
    });
}

function crearagencia()
{
    $.ajax({url: 'getagencias_polygono',
            type: 'GET',
//            async: false,
            success: function(r)
            {
                geojson_agencias = JSON.parse(r[0].json_build_object);
                var format_sectores_cat1 = new ol.format.GeoJSON();
                var features_sectores_cat1 = format_sectores_cat1.readFeatures(geojson_agencias,
                        {dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'});
                var jsonSource_sectores_cat1 = new ol.source.Vector({
                    attributions: [new ol.Attribution({html: '<a href=""></a>'})],
                });
                jsonSource_sectores_cat1.addFeatures(features_sectores_cat1);
                lyr_agencias_fondos = new ol.layer.Vector({
                    source:jsonSource_sectores_cat1,
                    style: styleagencias_polygono,
                    title: "Agencias Juridiccion"
                });
                map.addLayer(lyr_agencias_fondos);
                var extent = lyr_agencias_fondos.getSource().getExtent();
                map.getView().fit(extent, map.getSize());
                
                llamar_agenciaspoint();
            }
        });
}
function llamar_agenciaspoint()
{
    $.ajax({url: 'getagencias',
            type: 'GET',
//            async: false,
            success: function(r)
            {
                geojson_agencias = JSON.parse(r[0].json_build_object);
                var format_sectores_cat1 = new ol.format.GeoJSON();
                var features_sectores_cat1 = format_sectores_cat1.readFeatures(geojson_agencias,
                        {dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'});
                var jsonSource_sectores_cat1 = new ol.source.Vector({
                    attributions: [new ol.Attribution({html: '<a href=""></a>'})],
                });
                jsonSource_sectores_cat1.addFeatures(features_sectores_cat1);
                lyr_agencias = new ol.layer.Vector({
                    source:jsonSource_sectores_cat1,
                    style: styleagencias,
                    title: "Agencias Punto"
                });
                map.addLayer(lyr_agencias);
                var extent = lyr_agencias.getSource().getExtent();
                map.getView().fit(extent, map.getSize());
                MensajeDialogLoadAjaxFinish('map');
            }
        });
}
function styleagencias(feature, resolution){
    return new ol.style.Style({
        image: new ol.style.Circle({
            fill: new ol.style.Fill({
                color: 'rgba(4, 164, 180  , 0.3)',
            }),
            stroke: new ol.style.Stroke({
                color: 'black',
                width: 2,
                lineCap: 'butt',
            }),
            radius: 5
            
        }),
        text: new ol.style.Text({
            text: feature.get('agencia'),
            offsetY: -25,
            fill: new ol.style.Fill({
                color: 'white',
            }),
            stroke: new ol.style.Stroke({
                color: 'black',
                width: 2,
                lineCap: 'butt',
            }),
        })
    });
}
function styleagencias_polygono(feature, resolution) {
    return new ol.style.Style({
        stroke: new ol.style.Stroke({
            color: '#04A4B4',
            width: 2,
            lineCap: 'butt',
        }),
        fill: new ol.style.Fill({
            color: 'rgba(4, 164, 180, 0.3)',
        }),
        text: new ol.style.Text({
        //font: '12px Roboto',
        })
    });
}


function crearcamaras()
{
    $.ajax({url: 'getcamaras',
            type: 'GET',
//            async: false,
            success: function(r)
            {
                geojson_camaras = JSON.parse(r[0].json_build_object);
                var format_camaras= new ol.format.GeoJSON();
                var features_camaras = format_camaras.readFeatures(geojson_camaras,
                        {dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'});
                var jsonSource_camaras = new ol.source.Vector({
                    attributions: [new ol.Attribution({html: '<a href=""></a>'})],
                });
                jsonSource_camaras.addFeatures(features_camaras);
                lyr_camaras = new ol.layer.Vector({
                    source:jsonSource_camaras,
                    style: stylecamaras,
                    title: "Camaras"
                });
                map.addLayer(lyr_camaras);
                var extent = lyr_camaras.getSource().getExtent();
                map.getView().fit(extent, map.getSize());
                MensajeDialogLoadAjaxFinish('map');
            }
        });
}
function stylecamaras(feature, resolution){
    return  new ol.style.Style({
        image: new ol.style.Icon({
          scale: 0.2,
          src: 'img/recursos/camara-md.png',
        })
      });
}

function crearvias()
{
    $.ajax({url: 'getvias_lineas',
            type: 'GET',
//            async: false,
            success: function(r)
            {
                geojson_vias = JSON.parse(r[0].json_build_object);
                var format_vias= new ol.format.GeoJSON();
                var features_vias = format_vias.readFeatures(geojson_vias,
                        {dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'});
                var jsonSource_vias = new ol.source.Vector({
                    attributions: [new ol.Attribution({html: '<a href=""></a>'})],
                });
                jsonSource_vias.addFeatures(features_vias);
                lyr_vias = new ol.layer.Vector({
                    source:jsonSource_vias,
                    style: stylevias,
                    title: "Vias"
                });
                map.addLayer(lyr_vias);
                var extent = lyr_vias.getSource().getExtent();
                map.getView().fit(extent, map.getSize());
              
                MensajeDialogLoadAjaxFinish('map');
            }
        });
}

function stylevias(feature, resolution){
    return new ol.style.Style({
       stroke: new ol.style.Stroke({
        color: '#B40477',
        width: 3
      }),
        text: new ol.style.Text({
            Placement: 'line',
            textAlign: "center",
            text: feature.get('result'),
            Baseline:'middle',
            maxAngle: 6.283185307179586,
            rotation: 0,
            fill: new ol.style.Fill({
                color: 'white',
            }),
            stroke: new ol.style.Stroke({
                color: 'black',
                width: 2,
                lineCap: 'butt',
            }),
        })
    });
}

function crear_z_urbana()
{
    $.ajax({url: 'get_z_urbana',
            type: 'GET',
//            async: false,
            success: function(r)
            {
                z_urbana = JSON.parse(r[0].json_build_object);
                var format_z_urbana = new ol.format.GeoJSON();
                var features_limites_distritales0 = format_z_urbana.readFeatures(z_urbana,
                        {dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'});
                var jsonSource_z_urbana = new ol.source.Vector({
                    attributions: [new ol.Attribution({html: '<a href=""></a>'})],
                });
                jsonSource_z_urbana.addFeatures(features_limites_distritales0);

                lyr_z_urbana = new ol.layer.Vector({
                    source:jsonSource_z_urbana,
                    style: stylez_urbana,
                    title: "Zona Urbana",
                   
                });
                
                map.addLayer(lyr_z_urbana);
                var scale = new ol.control.ScaleLine();
                map.addControl(scale);
                var extent = lyr_z_urbana.getSource().getExtent();
                map.getView().fit(extent, map.getSize());
                var fullscreen = new ol.control.FullScreen();
                map.addControl(fullscreen);
                MensajeDialogLoadAjaxFinish('map');
            }
        });
}

function stylez_urbana(feature, resolution) {
    return new ol.style.Style({
        stroke: new ol.style.Stroke({
            color: '#000000',
            width: 2,
            lineCap: 'butt',
        }),
        fill: new ol.style.Fill({
            color: 'rgba(255, 195, 0 , 0.3)',
        }),
        text: new ol.style.Text({
        text: feature.get('zona')
        })
    });
}
function crear_z_agricola()
{
    $.ajax({url: 'get_z_agricola',
            type: 'GET',
//            async: false,
            success: function(r)
            {
                z_agricola = JSON.parse(r[0].json_build_object);
                var format_z_agricola = new ol.format.GeoJSON();
                var features_z_agricola = format_z_agricola.readFeatures(z_agricola,
                        {dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'});
                var jsonSource_z_agricola = new ol.source.Vector({
                    attributions: [new ol.Attribution({html: '<a href=""></a>'})],
                });
                jsonSource_z_agricola.addFeatures(features_z_agricola);

                lyr_z_agricola = new ol.layer.Vector({
                    source:jsonSource_z_agricola,
                    style: stylez_agircola,
                    title: "Zona Agricola",
                   
                });
                
                map.addLayer(lyr_z_agricola);
                var scale = new ol.control.ScaleLine();
                map.addControl(scale);
                var extent = lyr_z_agricola.getSource().getExtent();
                map.getView().fit(extent, map.getSize());
                var fullscreen = new ol.control.FullScreen();
                map.addControl(fullscreen);
                MensajeDialogLoadAjaxFinish('map');
            }
        });
}

function stylez_agircola(feature, resolution) {
    return new ol.style.Style({
        stroke: new ol.style.Stroke({
            color: '#6666ff',
            width: 2,
            lineCap: 'butt',
        }),
        fill: new ol.style.Fill({
            color: 'rgba(12, 17, 178 , 0.3)',
        }),
        text: new ol.style.Text({
        text: feature.get('zona')
        })
    });
}
function crear_z_eriaza()
{
    $.ajax({url: 'get_z_eriaza',
            type: 'GET',
//            async: false,
            success: function(r)
            {
                z_eriaza = JSON.parse(r[0].json_build_object);
                var format_z_eriaza = new ol.format.GeoJSON();
                var features_z_eriaza = format_z_eriaza.readFeatures(z_eriaza,
                        {dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'});
                var jsonSource_z_agricola = new ol.source.Vector({
                    attributions: [new ol.Attribution({html: '<a href=""></a>'})],
                });
                jsonSource_z_agricola.addFeatures(features_z_eriaza);

                lyr_z_eriaza = new ol.layer.Vector({
                    source:jsonSource_z_agricola,
                    style: stylez_eriaza,
                    title: "Zona Eriaza",
                   
                });
                
                map.addLayer(lyr_z_eriaza);
                var scale = new ol.control.ScaleLine();
                map.addControl(scale);
                var extent = lyr_z_eriaza.getSource().getExtent();
                map.getView().fit(extent, map.getSize());
                var fullscreen = new ol.control.FullScreen();
                map.addControl(fullscreen);
                MensajeDialogLoadAjaxFinish('map');
            }
        });
}

function stylez_eriaza(feature, resolution) {
    return new ol.style.Style({
        stroke: new ol.style.Stroke({
            color: '#009900',
            width: 2,
            lineCap: 'butt',
        }),
        fill: new ol.style.Fill({
            color: 'rgba(17, 178, 12   , 0.3)',
        }),
        text: new ol.style.Text({
        text: feature.get('zona')
        })
    });
}
function crear_aportes()
{
    $.ajax({url: 'get_aportes',
            type: 'GET',
//            async: false,
            success: function(r)
            {
                z_aportes = JSON.parse(r[0].json_build_object);
                var format_z_aportes = new ol.format.GeoJSON();
                var features_z_aportes = format_z_aportes.readFeatures(z_aportes,
                        {dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'});
                var jsonSource_aportes = new ol.source.Vector({
                    attributions: [new ol.Attribution({html: '<a href=""></a>'})],
                });
                jsonSource_aportes.addFeatures(features_z_aportes);

                lyr_aportes = new ol.layer.Vector({
                    source:jsonSource_aportes,
                    style: stylez_aportes,
                    title: "Aportes",
                   
                });
                
                map.addLayer(lyr_aportes);
                var scale = new ol.control.ScaleLine();
                map.addControl(scale);
                var extent = lyr_aportes.getSource().getExtent();
                map.getView().fit(extent, map.getSize());
                var fullscreen = new ol.control.FullScreen();
                map.addControl(fullscreen);
                MensajeDialogLoadAjaxFinish('map');
            }
        });
}

function stylez_aportes(feature, resolution) {
    return new ol.style.Style({
        stroke: new ol.style.Stroke({
            color: '#EA7D09',
            width: 2,
            lineCap: 'butt',
        }),
        fill: new ol.style.Fill({
            color: 'rgba(234, 125, 9 , 0.5)'
        }),
        text: new ol.style.Text({
            text: feature.get('layer')
        })
    });
}