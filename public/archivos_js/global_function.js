var rutaglobal = 0;
function getServidorUrl()
{
    if(rutaglobal==0){rutaglobal = new Image; rutaglobal.src = '$';}
    return rutaglobal.src.slice(0,-1);
    }
function MensajeDialogLoadAjax(Dialogo,Mensaje){

    $('#'+Dialogo).parent().block({
        message: "<p class='ClassMsgBlock'><img src='"+getServidorUrl()+"img/cargando.gif' style='width: 18px;position: relative;top: -1px;'/>"+Mensaje+"</p>",
        css: { border: '2px solid #006000',background:'white',width: '62%'}
    });
}
function MensajeDialogLoadAjaxFinish(Dialogo){
     $('#'+Dialogo).parent().unblock();
}

function limpiar_ctrl(div) {
    $(':input', '#' + div).each(function () {
        if (this.type === 'text') {
            if ($(this).attr('disabled')) {
                //no hase nada
            } else {
                this.value = "";
            }
        } else if ($(this).is('select')) {
//                if ($(this).is(':hidden')) {            
            this.value = 'select';
//                }
        } else if (this.type === 'radio') {
            this.checked = false;
        } else if (this.type === 'textarea') {
            this.value = '';
        } else if (this.type === 'password') {
            this.value = '';
        }
    });
}
function valores_defaul_form(tip) {

    switch (tip) {
        case 0://0 form contribuyentes 
            $("input[name=radio_tip_per][value='1']").prop('checked', true);
            $("#contrib_ape_mat").val('-');
            $("#contrib_sexo").val('1');
            $("#contrib_tlfno_fijo").val('0');
            $("#contrib_tlfono_celular").val('0');
            $("#contrib_email").val('@');
            $("#contrib_raz_soc").val('-');
            $("#hiddentxt_av_jr_calle_psje").val('1');//id_via
            $("#contrib_nro_mun").val('0');
            $("#contrib_dpto_depa").val('0');
            $("#contrib_manz").val('0');
            $("#contrib_lote").val('0');
            $("#contrib_dom_fiscal").val('-');
            $("#contrib_nro_doc_conv").val('0');
            $("#contrib_conviviente").val('-');
            $("#contrib_id_cond_exonerac").val('1');
            break
        case 1:///dialog insert update casas             
            $("#reg_casa_cas_des").css({border: "1px solid #83CBFF"});
            $("#reg_casa_cas_dir").css({border: "1px solid #83CBFF"});
            $("#reg_casa_cas_fono").css({border: "1px solid #83CBFF"});
            break;
        default:
    }
}

function traer_contrib_cod(input, doc) {    
    MensajeDialogLoadAjax(input, '.:: Cargando ...');    
    $.ajax({
        url: 'autocomplete_contrib?doc=0&cod=' + doc,
        type: 'GET',
        success: function (data) {            
            if (data.msg == 'si') {
                $("#" + input + "_hidden").val(data.id_pers);
                $("#" + input).val((data.contribuyente).replace('-',''));
                $("#vw_emi_rec_fracc_contrib").attr('maxlength',(((data.contribuyente).replace('-','')).length)-1);                
            } else {
                $("#" + input + "_hidden").val(0);
                $("#" + input).val("");                
                mostraralertas('* El Documento Ingresado no Existe.<br>* Registre al contribuyente o intente con otro número ... !');                
            }
            MensajeDialogLoadAjaxFinish(input);
        },
        error: function (data) {
            mostraralertas('* Error Interno !  Comuniquese con el Administrador...');
            MensajeDialogLoadAjaxFinish(input);
        }
    });
}

function soloDNI(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if ((charCode > 45 && charCode < 58) || (charCode > 36 && charCode < 41) || charCode == 9 || charCode == 8) {
        if (charCode == 190 || charCode == 191 || charCode == 84 || charCode == 78 || charCode == 40 || charCode == 37 || charCode == 46 || charCode == 110) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}
function soloNumeroTab(evt) {// con guin y slash ( - / )

    var charCode = (evt.which) ? evt.which : evt.keyCode
    if ((charCode > 44 && charCode < 58) || (charCode > 36 && charCode < 41) || charCode == 9 || charCode == 8 || charCode == 110) {
        if (charCode == 78 || charCode == 40 || charCode == 37 || charCode == 110) {
            return false;
        } else {
            return true;
        }

    } else {
        return false;
    }
}

function dialog_close(div) {
    $('#' + div).dialog("close");
}

function get_fecha_actual(input) {
    var f = new Date();
    $("#" + input).val(("0" + f.getDate()).slice(-2) + "/" + ("0" + (f.getMonth() + 1)).slice(-2) + "/" + f.getFullYear());
}


function llenar_combo_tipo_documento(input_1, input_2) {
    $.ajax({
        url: 'get_all_tipo_documento',
        type: 'GET',
        success: function (data) {
            for (i = 0; i <= data.length - 1; i++) {//carga el combo para seleccionar el seguro desde la BD

                $('#' + input_1).append('<option value=' + data[i].tip_doc + '>' + data[i].tipo_documento + '</option>');

                if (input_2 != undefined) {
                    $('#' + input_2).append('<option value=' + data[i].tip_doc + '>' + data[i].tipo_documento + '</option>');
                }
            }
//            $("#"+input_1).val($("#"+input_1+" option:second").val());
            $("#"+input_1).prop("selectedIndex", 2);
            if (input_2 != undefined) {
                $("#"+input_2).prop("selectedIndex", 2);
//                $("#"+input_2).val($("#"+input_2+" option:second").val());
            }
        },
        error: function (data) {
            alert(' Error al traer Tipo de Documentos');
        }
    });
}
function llenar_combo_cond_exonerac(tip) {// 0 form contribuyentes 
    $.ajax({
        url: 'get_all_cond_exonerac',
        type: 'GET',
        success: function (data) {
            for (i = 0; i <= data.length - 1; i++) {//carga el combo para seleccionar el seguro desde la BD
                if (tip == 0) {
                    $('#contrib_id_cond_exonerac').append('<option value=' + data[i].id_cond_exo + '>' + data[i].desc_cond_exon + '</option>');
                }
            }
        },
        error: function (data) {
            alert(' Error al traer condicion exonerac');
            $(this).dialog("close");
        }
    });
}

function autocompletar_av_jr_calle(textbox, nom_via) {
    $.ajax({
        type: 'GET',
        url: 'autocompletar_direccion?nom_via=' + nom_via.toUpperCase(),
        success: function (data) {
            var $local_sourcedoctotodo = data;
            $("#" + textbox).autocomplete({
                source: $local_sourcedoctotodo,
                focus: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hidden" + textbox).val(ui.item.value);
                    $("#" + textbox).attr('maxlength', ui.item.label.length);
                    return false;
                },
                select: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hidden" + textbox).val(ui.item.value);
                    return false;
                }
            });
        }
    });
}
function autocompletar_av_jr_call(textbox) {
    $.ajax({
        type: 'GET',
        url: 'autocompletar_direccion',
        success: function (data) {
            var $local_sourcedoctotodo = data;
            $("#" + textbox).autocomplete({
                source: $local_sourcedoctotodo,
                focus: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hidden" + textbox).val(ui.item.value);
//                    $("#" + textbox).attr('maxlength', ui.item.label.length);
                    return false;
                },
                select: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hidden" + textbox).val(ui.item.value);
                    return false;
                }
            });
        }
    });
}

var global_id_via;
function get_global_cod_via(input, cod_via) {
    MensajeDialogLoadAjax(input, '.:: Cargando ...');
    $.ajax({
        url: 'autocomplete_nom_via?cod_via=' + cod_via,
        type: 'GET',
        success: function (data) {
            if (data.msg == 'si') {
                global_id_via = data.id_via;
                $("#" + input).val(data.via_compl);
                $("#" + input).attr('maxlength', data.via_compl.length);
            } else {
                global_id_via = 0;
                $("#" + input).val("");
                mostraralertas('* El Codigo Ingresado no Existe ... !');
            }
            MensajeDialogLoadAjaxFinish(input);
        },
        error: function (data) {
            mostraralertas('*Error Interno !  Comuniquese con el Administrador...');
            MensajeDialogLoadAjaxFinish(input);
        }
    });

}
function get_global_contri(input, doc) {
    MensajeDialogLoadAjax(input, '.:: Cargando ...');
    $.ajax({
        url: 'autocomplete_contrib?doc=' + doc,
        type: 'GET',
        success: function (data) {
            if (data.msg == 'si') {
                $("#" + input + "_hidden").val(data.id_pers);
                $("#" + input).val(data.contribuyente);
            } else {
                $("#" + input + "_hidden").val(0);
                $("#" + input).val("");
                mostraralertas('* El Documento Ingresado no Existe, registre al contribuyente o intente con otro número ... !');
            }
            MensajeDialogLoadAjaxFinish(input);

        },
        error: function (data) {
            mostraralertas('* Error Interno !  Comuniquese con el Administrador...');
            MensajeDialogLoadAjaxFinish(input);
        }
    });
}

function get_global_anio_uit(input) {

    var d = new Date();
    $.ajax({
        url: 'get_anio_val_arancel',
        type: 'GET',
        success: function (data) {
            for (i = 0; i <= data.length - 1; i++) {
                $('#' + input).append('<option value=' + data[i].anio + '>' + data[i].anio + '</option>');
            }
            $('#' + input).val(d.getFullYear());
        },
        error: function (data) {
            alert(' Error al llenar combo Año...');
            MensajeDialogLoadAjaxFinish('content', '.:: CARGANDO ...');
        }
    });
}

/*COMBOS DE DEPARTAMENTOS PROVINCIA Y DISTRITO*/

function llenar_combo_dpto(input) {// 0 form contribuyentes
    $.ajax({
        url: 'get_all_dpto',
        type: 'GET',
        success: function (data) {
            for (i = 0; i <= data.length - 1; i++) {
                $('#' + input).append('<option value=' + data[i].cod + '>' + data[i].dpto + '</option>');
            }
            $('#' + input).val('04');
        },
        error: function (data) {
            mostraralertas('* Error al traer Departamentos...!');
        }
    });
}

global_prov = 0;
function llenar_combo_prov(input, cod_dpto) {// 0 form contribuyentes
    MensajeDialogLoadAjax(input,'Cargando');
    
    cod_dpto = cod_dpto || "04";
    $('#' + input).prop('options').length = 1;
    $.ajax({
        url: 'get_all_prov?cod_dpto=' + cod_dpto,
        type: 'GET',
        success: function (data) {
            for (i = 0; i <= data.length - 1; i++) {
                $('#' + input).append('<option value=' + data[i].cod_prov + '>' + data[i].provinc + '</option>');
            }
            if (global_prov == 0) {
                global_prov = 1;
                $('#' + input).val('0401');
            } else {
                setTimeout(function () {
                    $('#contrib_setprov').val('select');
                }, 500);
            }
        },
        error: function (data) {
            mostraralertas('* Error al traer  Provincias...!');
        }
    });
    setTimeout(function () { 
        llenar_combo_dist('contrib_dist',$("#contrib_prov option:selected").val());        
        MensajeDialogLoadAjaxFinish(input);        
    }, 500);
}
global_dist = 0;
function llenar_combo_dist(input, cod_prov) {// 0 form contribuyentes
    MensajeDialogLoadAjax('contrib_dist','Cargando');
    cod_prov = cod_prov || "0401";
    $('#' + input).prop('options').length = 1;
    $.ajax({
        url: 'get_all_dist?cod_prov=' + cod_prov,
        type: 'GET',
        success: function (data) {
            for (i = 0; i <= data.length - 1; i++) {
                $('#' + input).append('<option value=' + data[i].cod_dist + '>' + data[i].distrit + '</option>');
            }
        },
        error: function (data) {
            mostraralertas('* Error al traer Distritos...!');
        }
    });
    setTimeout(function () { 
        MensajeDialogLoadAjaxFinish('contrib_dist');
    }, 300);
}

function fn_actualizar_grilla(grilla, url) {
    jQuery("#" + grilla).jqGrid('setGridParam', {
        url: url
    }).trigger('reloadGrid');

}

var global_captcha_reniec = 0;
function get_datos_dni() {    
    nro_doc = ($("#pers_nro_doc").val()).trim();    
    MensajeDialogLoadAjax('dialog_Personas', 'Realizando Busqueda en Reniec...');
    $.ajax({
        type: 'GET',
        url: 'get_datos_dni?nro_doc='+nro_doc,        
        success: function (data) {
            $("#pers_pat").val(data.ape_pat);
            $("#pers_mat").val(data.ape_mat);
            $("#pers_nombres").val(data.nombres);
            $("#pers_foto").attr("src",data.foto);
            if ( $("#vw_usuario_foto_img").length ) {
                $("#vw_usuario_foto_img").attr("src",data.foto);
            }
        },
        error: function (data){            
            mostraralertas('* No se Encontró el DNI<br>* Porfavor Ingrese los Datos Manualmente...');            
        }
    });
    setTimeout(function(){ MensajeDialogLoadAjaxFinish('dialog_Personas'); }, 3000);
}
function get_datos_ruc(){
    nro_doc = ($("#pers_nro_doc").val()).trim(); 
    MensajeDialogLoadAjax('dialog_Personas', 'Realizando Busqueda en Sunat...');
    $.ajax({
        type: 'GET',
        url: 'get_datos_ruc?nro_doc='+nro_doc,
        datatype: 'json',
        success: function (data) {
            $("#pers_raz_soc").val(data.raz_soc);            
        },
        error: function (data) {
            mostraralertas('* No se Encontró el RUC<br>Porfavor Ingrese los Datos Manualmente...');            
        }
    });
    setTimeout(function(){ MensajeDialogLoadAjaxFinish('dialog_Personas'); }, 2000);
}
function formato_numero(numero, decimales, separador_decimal, separador_miles) { // v2007-08-06
    numero = parseFloat(numero);
    if (isNaN(numero)) {
        return "";
    }

    if (decimales !== undefined) {
        // Redondeamos
        numero = numero.toFixed(decimales);
    }
    // Convertimos el punto en separador_decimal
    numero = numero.toString().replace(".", separador_decimal !== undefined ? separador_decimal : ",");

    if (separador_miles) {
        // Añadimos los separadores de miles
        var miles = new RegExp("(-?[0-9]+)([0-9]{3})");
        while (miles.test(numero)) {
            numero = numero.replace(miles, "$1" + separador_miles + "$2");
        }
    }
    return numero;
}
function redondeo(numero, decimales)
{
var flotante = parseFloat(numero);
var resultado = Math. round(flotante*Math. pow(10,decimales))/Math. pow(10,decimales);
return resultado;
}

/**********MENSAJES DEL SISTEMA*****************************************/

function foco(div)
{    $(div).focus();}
function mostraralertas(texto)
{
    $("#alertdialog").html('<p>' + texto + '</p>');
    $("#alertdialog").dialog('open');
}
var focoglobal = "";
function mostraralertasconfoco(texto, foco)
{
    $.SmartMessageBox({
            title : "<i class='glyphicon glyphicon-alert' style='color: yellow; margin-right: 20px; font-size: 1.5em;'></i> Alerta del Sistema!",
            content : texto,
            buttons : '[Aceptar]'
    }, function(ButtonPressed) {
            if (ButtonPressed === "Aceptar") {
                    $(foco).focus();
            }
    });
    $("#bot1-Msg1").addClass('bg-color-green txt-color-white');
    $("#bot1-Msg1").focus();
    setTimeout(openPopup, 500);

}
function openPopup()
{
    $('body').keyup(function(e) {
    if(e.keyCode == 13) {
        $("#bot1-Msg1").trigger("click");
        $('body').off('keyup');

    }
});
}
function MensajeExito(tit,cont,dura)
{
    $.smallBox({
                    title : tit,
                    content : "<i class='fa fa-clock-o'></i> <i>"+cont+"</i>",
                    color : "#659265",
                    iconSmall : "fa fa-check fa-2x fadeInRight animated",
                    timeout : dura || 5000
            });
}
function MensajeAlerta(tit,cont,dura)
{
    $.smallBox({
                    title : tit,
                    content : "<i class='fa fa-clock-o'></i> <i>"+cont+"</i>",
                    color : "#C46A69",
                    iconSmall : "fa fa-check fa-2x fadeInRight animated",
                    timeout : dura || 5000
            });
}
function sin_permiso()
{
    $.smallBox({
                    title : 'No tiene Permiso de Usar este Boton',
                    content : "<i class='fa fa-clock-o'></i> <i>Comuniquese con el Admin</i>",
                    color : "#C46A69",
                    iconSmall : "fa fa-check fa-2x fadeInRight animated",
                    timeout : 5000
            });
}
function ajustar(tam, num)
{
    data = $("#" + num).val();
    if (data != "")
    {
        if (data.toString().length <= tam)
        {
            $("#" + num).val("0" + data);
            return ajustar(tam, num)
        } else
            return $("#" + num).val(data);
    }
}

function FilterTableAllFields(Letras, IdTabla)
{
    var Palabras = Letras.value.toLowerCase().split(" ");
    var Tabla = document.getElementById(IdTabla);
    var Elemento;
    for (var r = 1; r < Tabla.rows.length; r++)
    {
	 Elemento = Tabla.rows[r].innerHTML.replace(/<[^>]+>/g, "");
	 var displayStyle = 'none';
	 for (var i = 0; i < Palabras.length; i++)
	 {
	     if (Elemento.toLowerCase().indexOf(Palabras[i]) >= 0)
	     {
		  displayStyle = '';
	     }
	     else
	     {
		  displayStyle = 'none';
		  break;
	     }
	 }
	 Tabla.rows[r].style.display = displayStyle;
    }
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
function iniciar_mapa()
{
        autocompletar_haburb('inp_habilitacion');
        window.app = {};
        var app = window.app;
        var layersList = [];
        var vectorSource = new ol.source.Vector({});
        var lyr_sectores;
        var lyr_manzanas;
        var lyr_limites_distritales0;
        var lyr_lotes3;
        var lyr_predios4;
        var lyr_sectores_cat1;
        var LayersList2= [lyr_sectores,lyr_manzanas,lyr_limites_distritales0,lyr_lotes3,lyr_predios4];
        
        app.CustomToolbarControl = function(opt_options) {

            var options = opt_options || {};

            var button = document.createElement('button');
            button.innerHTML = 'N';

            var button1 = document.createElement('button');
            button1.innerHTML = 'some button';

            var selectList = document.createElement("input");
            selectList.id = "inp_habilitacion";
            selectList.className = "input-sm col-xs-12";
            selectList.type = "text";
            selectList.style = "height:18px";
            selectList.placeholder = "Seleccione Habilitación";

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

            ol.control.Control.call(this, {
                element: element,
                target: options.target
            });
        };
        ol.inherits(app.CustomToolbarControl, ol.control.Control);
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
                            title: 'OSM',
                            type: 'base',
                            visible: true,
                            source: new ol.source.OSM()
                        }),
                        new ol.layer.Tile({
                            title: 'Water color',
                            type: 'base',
                            visible: false,
                            source: new ol.source.Stamen({
                                layer: 'watercolor'
                            })
                        }),
                        new ol.layer.Tile({
                            title: 'Blanco',
                            type: 'base',
                            visible: false
                        }),
                        new ol.layer.Tile({
                            title: 'Satelite',
                            visible: false,
                            source: new ol.source.BingMaps({
                              key: 'EqfF5l6dY2LLMQa8JHlI~voA5TXsAVOQgFOP74piAbg~Aqg-emVFCImabFdRRDvdjqh1rB6Bl9l8ZkcmL7nGveSeeNkV7iSRC7XTHi1XeUVu',
                              imagerySet: 'Aerial'
                            })
                        })
                    ]
                })
            ],
            target: 'id_map_reg_lote',
            
        });
        $.ajax({url: 'mapa_cris_getlimites',
                    type: 'GET',
                    async: false,
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

                    $.ajax({url: 'gethab_urb_by_id/0',
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
            }
        });
        
  
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
        $.ajax({url: 'gethab_urb_by_id/'+id,
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
                    url: 'get_lotes_x_hab_urb',
                    type: 'GET',
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
            font: '30px Calibri,sans-serif',
            fill: new ol.style.Fill({ color: '#000' }),
            stroke: new ol.style.Stroke({
                color: '#fff', width: 2
            }),
            // get the text from the feature - `this` is ol.Feature
            // and show only under certain resolution
            text: map.getView().getZoom() > 17 ? feature.get('codi_lote') : ''
        })
        
    });
}

        map.on('singleclick', function(evt) {

            
            mostrar=0;
            var fl = map.forEachFeatureAtPixel(evt.pixel, function (feature, layer) {
               
                if(layer.get('title')=='lotes'&&mostrar==0)
                {
                    mostrar=1;
                    $("#dlg_lot_foto").val(feature.get('codi_lote'));
                    $("#dlg_mzna_foto").val(feature.get('codi_mzna'));
                    $("#dlg_sec_foto").val(feature.get('sector'));
                    $("#hidden_dlg_lot_foto").val(feature.get('id_lote'));
                    viewlong_lote(feature.get('id_lote'));
                    return false;
                }
            });
    
        });
        
}
function viewlong_lote(id)
{
    
    $("#dlg_img_view_big").html("");
    crear_dlg("dlg_view_foto_desde_mapa",1000,"Foto del Predio");
   
   MensajeDialogLoadAjax('dlg_view_foto_desde_mapa', '.:: Cargando ...');
    $.ajax({url: 'traefoto_lote_id_mapa/'+id,
    type: 'GET',
    success: function(r) 
    {
        texto1='';
        texto2='';
        $("#dlg_img_view_big_mapa").html("");
        if(r!=0)
        {
            $("#dlg_img_view_big_mapa").html('<center><img src="data:image/png;base64,'+r[0].foto+'" width="85%"/></center>');

            for(i=0;i<r.length;i++)
            {
                if(i==0)
                {
                    texto1=texto1+'<li data-target="#myCarousel" data-slide-to="'+i+'" class="active"></li>';            
                    texto2=texto2+'<div class="item active"><center><img src="data:image/png;base64,'+r[i].foto+'" alt=""></center></div>';

                }
                else
                {
                    texto1=texto1+'<li data-target="#myCarousel" data-slide-to="'+i+'"></li>';            
                    texto2=texto2+'<div class="item"><center><img src="data:image/png;base64,'+r[i].foto+'" alt=""></center></div>\n\
                                  '
                }
            }
            final='<div id="myCarousel" class="carousel fade" style="margin-bottom: 20px;">\n\
                  <ol class="carousel-indicators">\n\
                  '+texto1+'\n\
                  </ol>\n\
                  <div class="carousel-inner">\n\
                    '+texto2+'\n\
                  </div>\n\
                <a class="left carousel-control" href="#myCarousel" data-slide="next"> <span class="glyphicon glyphicon-chevron-left"></span> </a>\n\
                <a class="right carousel-control" href="#myCarousel" data-slide="prev"> <span class="glyphicon glyphicon-chevron-right"></span> </a>\n\
                </div>';
            $("#dlg_img_view_big_mapa").html(final);
        }
        else
        {
            $("#dlg_img_view_big_mapa").html('<center><img src="img/recursos/Home-icon.png" width="85%"/></center>');
        }
        MensajeDialogLoadAjaxFinish('dlg_view_foto_desde_mapa');
        
        
    },
    error: function(data) {
        mostraralertas("hubo un error, Comunicar al Administrador");
        console.log('error');
        console.log(data);
        MensajeDialogLoadAjaxFinish('dlg_view_foto_desde_mapa');
    }
    }); 
}