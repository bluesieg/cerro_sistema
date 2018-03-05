@extends('layouts.app')

@section('content')
    <style>
        html, body {
            background-color: #ffffff;
        }
    </style>
    <style>
        html, body, #map {
            width: 100%;
            height: 100%;
            padding: 0;
            margin: 0;
        }
        .ol-touch .rotate-north {
            top: 80px;
        }
        .ol-mycontrol {
            background-color: rgba(255, 255, 255, 0.4);
            border-radius: 4px;
            padding: 2px;
            position: absolute;
            width:300px;
            top: 5px;
            left:40px;
        }
    </style>

    <form class="smart-form">


    <div id="map" style="background: white; height: 100%">
        <div id="popup" class="ol-popup">
            <a href="#" id="popup-closer" class="ol-popup-closer"></a>
            <div id="popup-content"></div>
        </div>
    </div>
        </form>

@section('page-js-script')
    <script type="text/javascript">

        $(document).ready(function () {
            $("#menu_cart_base").show();
            $("#li_ver_cart").addClass('cr-active');

        });
</script>
    <script>


        window.app = {};
        var app = window.app;
        var layersList = [];
        var vectorSource = new ol.source.Vector({});
        var lyr_sectores_cat1;
        var lyr_manzanas2;
        var lyr_limites_distritales0;
        var lyr_lotes3;
        var lyr_predios4;
        var LayersList2= [lyr_sectores_cat1,lyr_manzanas2,lyr_limites_distritales0,lyr_lotes3,lyr_predios4];

        var defaultCerroColorado = new ol.style.Style({
            stroke: new ol.style.Stroke({
                color: '#0000ff',
                width: 2
            })
        });

        var manzanas_Style = new ol.style.Style({
            stroke: new ol.style.Stroke({
                color: '#ff0000',
                width: 2
            })
        });

        var selectEuropa = new ol.style.Style({
            stroke: new ol.style.Stroke({
                color: '#ff0000',
                width: 2
            })
        });

        app.CustomToolbarControl = function(opt_options) {

            var options = opt_options || {};

            var button = document.createElement('button');
            button.innerHTML = 'N';

            var button1 = document.createElement('button');
            button1.innerHTML = 'some button';

            var selectList = document.createElement("input");
            selectList.id = "inp_habilitacion";
            selectList.className = "input-sm col-xs-6";
            selectList.type = "text";
            selectList.style = "height:18px";
            selectList.placeholder = "Seleccione Habilitación";
            

            var selectList_anio = document.createElement("select");
            selectList_anio.id = "anio_pred";
            selectList_anio.className = "input-sm col-xs-2";


            

            var anio = {!! json_encode($anio_tra) !!};
            // alert(global_cod_alm[0].codigo);
            for (var i = 0; i < anio.length; i++) {
                var option_anio = document.createElement("option");
                option_anio.value = anio[i].anio;
                option_anio.text = anio[i].anio;
                selectList_anio.appendChild(option_anio);
            }


            var checkbox = document.createElement('input');
            checkbox.type = "checkbox";
            checkbox.name = "name"
            checkbox.value = "value";
            checkbox.id = "draw_predios";
            document.body.appendChild(checkbox);
            var div2 = document.createElement('div');
            div2.className = "col-xs-1";

            var label = document.createElement('label');
            label.className = 'toggle col-xs-2';
            label.innerHTML = '<input type="checkbox" id="draw_predios" name="checkbox-toggle" onclick="get_predios();"> <i data-swchon-text="ON" data-swchoff-text="OFF"></i>Predios';

            var this_ = this;
            var handleRotateNorth = function(e) {
                this_.getMap().getView().setRotation(0);
            };


            button.addEventListener('click', handleRotateNorth, false);
            button.addEventListener('touchstart', handleRotateNorth, false);

            var element = document.createElement('div');
            element.className = 'ol-unselectable ol-mycontrol';
            element.style='width:700px !important'

            element.appendChild(selectList);
            element.appendChild(div2);
            element.appendChild(selectList_anio);
            element.appendChild(div2);
            element.appendChild(label);

            ol.control.Control.call(this, {
                element: element,
                target: options.target
            });

        };
        ol.inherits(app.CustomToolbarControl, ol.control.Control);



        function styleFunction() {
            return [
                new ol.style.Style({
                    fill: new ol.style.Fill({
                        color: 'rgba(255,255,255,0.4)'
                    }),
                    stroke: new ol.style.Stroke({
                        color: '#3399CC',
                        width: 1.25
                    }),
                    text: new ol.style.Text({
                        font: '12px Calibri,sans-serif',
                        fill: new ol.style.Fill({ color: '#000' }),
                        stroke: new ol.style.Stroke({
                            color: '#fff', width: 2
                        }),
                        // get the text from the feature - `this` is ol.Feature
                        // and show only under certain resolution
                        text: map.getView().getZoom() > 12 ? this.get('description') : ''
                    })
                })
            ];
        }


        var map = new ol.Map({
            controls: ol.control.defaults({
                attributionOptions: /** @type {olx.control.AttributionOptions} */ ({
                    collapsible: false
                })
            }).extend([
                new app.CustomToolbarControl()
            ]),
            layers: [
                new ol.layer.Group({
                    'title': 'Base maps',
                    layers: [
                        new ol.layer.Tile({
                            title: 'Water color',
                            type: 'base',
                            visible: false,
                            source: new ol.source.Stamen({
                                layer: 'watercolor'
                            })
                        }),
                        new ol.layer.Tile({
                            title: 'OSM',
                            type: 'base',
                            visible: true,
                            source: new ol.source.OSM()
                        }),
                        new ol.layer.Tile({
                            title: 'BLANK',
                            type: 'base',
                            visible: false
                        })
                    ]
                })
            ],
            target: 'map',
            view: new ol.View({
                center: [-11000000, 4600000],
                zoom: 4
            })
        });



        $.ajax({url: 'getlimites',
            type: 'GET',
            async: false,
            success: function(r)
            {
                geojson_limites_distritales0 = JSON.parse(r[0].json_build_object);
                var format_limites_distritales0 = new ol.format.GeoJSON();
                var features_limites_distritales0 = format_limites_distritales0.readFeatures(geojson_limites_distritales0,
                        {dataProjection: 'EPSG:4326', featureProjection: 'EPSG:3857'});
                var jsonSource_limites_distritales0 = new ol.source.Vector({
                    attributions: [new ol.Attribution({html: '<a href=""></a>'})],
                });
                jsonSource_limites_distritales0.addFeatures(features_limites_distritales0);

                lyr_limites_distritales0 = new ol.layer.Vector({
                    source:jsonSource_limites_distritales0,
                    style: defaultCerroColorado,
                    title: "Límites Distritales"
                });

                map.addLayer(lyr_limites_distritales0);

            }
        });

        $.ajax({url: 'gethab_urb/0',
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
               
            }
        });

       


        map.getView().fit([-7986511.592568, -1853075.694599, -7949722.367052, -1825746.555644], map.getSize());
        var fullscreen = new ol.control.FullScreen();
        map.addControl(fullscreen);
       

        function polygonStyleFunction(feature, resolution) {
            return new ol.style.Style({
                stroke: new ol.style.Stroke({
                    color: 'blue',
                    width: 2
                }),
                fill: new ol.style.Fill({
                    color: 'rgba(0, 0, 255, 0.1)'
                }),
                text: new ol.style.Text({
                    font: '12px Calibri,sans-serif',
                    fill: new ol.style.Fill({ color: '#fff' }),
                    stroke: new ol.style.Stroke({
                        color: '#000', width: 2
                    }),
                    text:map.getView().getZoom() > 14 ? feature.get('nomb_hab_urba') : ""
                })
            });
        }



        function label_manzanas_full(feature) {
            return new ol.style.Style({
                stroke: new ol.style.Stroke({
                    color: 'red',
                    width: 2
                }),
                fill: new ol.style.Fill({
                    color: 'rgba(255, 0, 0, 0.1)'
                })
            });
        }


    </script>

    <script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/map/map.js') }}"></script>
@stop
<input type="hidden" id="hidden_inp_habilitacion" value="0"/> 
    <div id="dlg_map" style="display: none; margin-top: 5px;">
        
    </div>
<div id="dlg_predio_lote" style="display: none;">
    <div class='cr_content col-xs-12 ' style="margin-bottom: 0px;">
        <div class="col-xs-12 cr-body" >
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px; margin-top: 0px;">
                
                <div class="col-xs-9" style="padding: 0px;">
                    <div class="col-xs-12" style="padding: 0px;">
                        <div class="input-group input-group-md col-xs-12" style="padding: 0px">
                            <span class="input-group-addon" style="width: 30%">Codigo Catastral &nbsp;<i class="fa fa-power-off"></i></span>
                            <div >
                                <label id="input_pred_cod_cat"  class="form-control" style="height: 32px;"></label>
                            </div>

                        </div>
                    </div>
                    <div class="col-xs-12" style="padding: 0px; margin-top: 10px">
                        <div class="input-group input-group-md col-xs-12" style="padding: 0px">
                            <span class="input-group-addon" style="width: 30%">Habilitación &nbsp;<i class="fa fa-map"></i></span>
                            <div >
                                <label id="input_pred_habilitacion"  class="form-control" style="height: 32px;"></label>
                            </div>

                        </div>
                    </div>
                    <div class="col-xs-12" style="padding: 0px; margin-top: 10px">
                        <div class="input-group input-group-md col-xs-12" style="padding: 0px">
                            <span class="input-group-addon" style="width: 30%">Propietario &nbsp;<i class="fa fa-male"></i></span>
                            <div >
                                <label id="input_pred_propietario"  class="form-control" style="height: 32px;"></label>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xs-3">
                    <div class="panel panel-success cr-panel-sep" style="height: 180px;overflow-y: scroll;">
                        <div class="panel-heading bg-color-success" style="padding: 5px">.:: Foto Predio ::.</div>
                        <div class="panel-body cr-body" style="padding-top: 0px">
                            <div id="dlg_img_view" style="padding: 5px; " onclick="viewlong()"></div>
                        </div>
                    </div>
                </div>
            </div>
          
        </div>
    </div>
</div> 
<div id="dlg_view_foto" style="display: none;">
    <div class="col-xs-12">
       <div class=" col-xs-4">
            <div class="input-group input-group-md">
                <input type="hidden" id="dlg_idpre" value="0">
                <span class="input-group-addon">Sector &nbsp;&nbsp;<i class="fa fa-cogs"></i></span>
                <div class="icon-addon addon-md">
                    <input class="text-center col-xs-12 form-control"  style="height: 32px;" id="dlg_sec" type="text" name="dlg_sec" disabled="" >
                </div>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="input-group input-group-md">
                <span class="input-group-addon">Manzana &nbsp;&nbsp;<i class="fa fa-apple"></i></span>
                <div class="icon-addon addon-md">
                    <input class="text-center form-control" style="height: 32px;" id="dlg_mzna" type="text" name="dlg_mzna" disabled="" >
                </div>
            </div>
        </div>
        <div class="col-xs-4">
            <div class="input-group input-group-md">
                <span class="input-group-addon">Lotes &nbsp;<i class="fa fa-home"></i></span>
                <div class="icon-addon addon-md">
                     <input class="text-center form-control" style="height: 32px;" id="dlg_lot" type="text" name="dlg_mzna" disabled="" >

                </div>
            </div>
        </div>
</div>
    <div class="panel panel-success cr-panel-sep" style="border:0px;">
        <div class="panel-body cr-body">
            <div id="dlg_img_view_big" style="padding-top: 0px"></div>
        </div>
    </div>
</div> 
    @include('configuracion/vw_general')

@endsection
