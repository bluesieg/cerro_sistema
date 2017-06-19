var rutaglobal = 0;
function getServidorUrl()
{
    if (rutaglobal == 0) {
        rutaglobal = new Image;
        rutaglobal.src = '$';
    }
    return rutaglobal.src.slice(0, -1);
}
function MensajeDialogLoadAjax(Dialogo, Mensaje) {

    $('#' + Dialogo).parent().block({
        message: "<p class='ClassMsgBlock'><img src='" + getServidorUrl() + "img/cargando.gif' style='width: 18px;position: relative;top: -1px;'/>" + Mensaje + "</p>",
        css: {border: '2px solid #006000', background: 'white', width: '62%'}
    });
}
function MensajeDialogLoadAjaxFinish(Dialogo) {
    $('#' + Dialogo).parent().unblock();
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
    cod_dpto = cod_dpto || "04";
//    document.getElementById(input).options.length = 1;  
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
                }, 1000);
            }
        },
        error: function (data) {
            mostraralertas('* Error al traer  Provincias...!');
        }
    });
}
global_dist = 0;
function llenar_combo_dist(input, cod_prov) {// 0 form contribuyentes
    cod_prov = cod_prov || "0401";
    $('#' + input).prop('options').length = 1;
//    document.getElementById('contrib_dist').options.length = 1;
    $.ajax({
        url: 'get_all_dist?cod_prov=' + cod_prov,
        type: 'GET',
        success: function (data) {
            for (i = 0; i <= data.length - 1; i++) {
                $('#' + input).append('<option value=' + data[i].cod_dist + '>' + data[i].distrit + '</option>');
            }
            if (global_dist == 0) {
                global_dist = 1;
                $('#' + input).val('040101');
            } else {
//                if(tipo!='EDITAR'){
                setTimeout(function () {
                    $('#' + input).val('select');
                }, 1000);
//                }                
            }
        },
        error: function (data) {
            mostraralertas('* Error al traer Distritos...!');
        }
    });
}

function fn_actualizar_grilla(grilla, url) {
    jQuery("#" + grilla).jqGrid('setGridParam', {
        url: url
    }).trigger('reloadGrid');

}

var global_captcha_reniec = 0;

function fn_consultar_dni() {
    tipo = $("#cb_tip_doc_1").val();
    nro_doc = ($("#txt_nro_doc").val()).trim();
    if (tipo == '02' && nro_doc != ''){
        MensajeDialogLoadAjax('dialog_new_edit_Contribuyentes', 'Realizando Busqueda en Reniec...');
        $.ajax({
            type: 'GET',
            url: 'http://py-devs.com/api/dni/' + nro_doc + '/?format=json',
            datatype: 'json',
            success: function (data) {
                $("#contrib_ape_pat").val(data.ape_paterno);
                $("#contrib_ape_mat").val(data.ape_materno);
                $("#contrib_nombres").val(data.nombres);
                MensajeDialogLoadAjaxFinish('dialog_new_edit_Contribuyentes');
            },
            error: function (data) {
                mostraralertas('* No se Encontró el DNI<br>Porfavor Ingrese los Datos Manualmente...');
                MensajeDialogLoadAjaxFinish('dialog_new_edit_Contribuyentes');
            }
        });
    } else {
        mostraralertasconfoco('* Ingrese Numero de Documento.<br>* Seleccione tipo de Documento.', '#txt_nro_doc');
        return false;
    }
}
function fn_consultar_ruc() {
    tipo = $("#cb_tip_doc_1").val();
    nro_doc = ($("#txt_nro_doc").val()).trim();
    if (nro_doc != '' && tipo == '00') {
        MensajeDialogLoadAjax('dialog_new_edit_Contribuyentes', 'Realizando Busqueda en Sunat...');
        $.ajax({
            type: 'GET',
            url: 'http://py-devs.com/api/ruc/' + nro_doc + '/?format=json',
            datatype: 'json',
            success: function (data) {
                $("#contrib_raz_soc").val(data.nombre);
                MensajeDialogLoadAjaxFinish('dialog_new_edit_Contribuyentes');
            },
            error: function (data) {
                mostraralertas('* No se Encontró el RUC<br>Porfavor Ingrese los Datos Manualmente...');
                MensajeDialogLoadAjaxFinish('dialog_new_edit_Contribuyentes');
            }
        });
    } else {
        mostraralertasconfoco('* Ingrese Numero de Documento.<br>* Seleccione tipo de Documento.', '#txt_nro_doc');
        return false;
    }
}
function fn_buscar_reniec() {
//    tipo = $("#cb_tip_doc_1").val();
//    dni = ($("#txt_nro_doc").val()).trim();


//    if(dni!='' && tipo==02){
//        var tecactusApi = new TecactusApi("eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjExZTVmMzAxOGNjYWYzZjA1OGZkYjQ3NjNmYmQ3NDQ5NTYxZjJhNTE4YzVlMTdiZjllNzdhOGNmYmIxZTZlMmEyN2IyZmI2MDA1OTI4YTYxIn0.eyJhdWQiOiIxIiwianRpIjoiMTFlNWYzMDE4Y2NhZjNmMDU4ZmRiNDc2M2ZiZDc0NDk1NjFmMmE1MThjNWUxN2JmOWU3N2E4Y2ZiYjFlNmUyYTI3YjJmYjYwMDU5MjhhNjEiLCJpYXQiOjE0OTc0MDgzODIsIm5iZiI6MTQ5NzQwODM4MiwiZXhwIjoxODEyOTQxMTgyLCJzdWIiOiIzNTIiLCJzY29wZXMiOlsidXNlLXJlbmllYyJdfQ.GUNrMCwwV1K6G8HNeTs1YtpLbPBUZSKIGpm0xH2RUKvwrHa8vhWzqLB1GCtVOCXdA9UlAabnQwz7kv2smTjfMD9wwgncbhUSpavEXlr6Wl0Sk0OR-FNaI2-4FhQTqrOycyezRZvhmIPQjaUfl98wympjmBbs03ylWHFacjVUkHSFx9DsmEnlZFb133lrWgsbKOES8zko-xt2z-czqQjMA57nWE6rG5_4ehkmb2a6nPHzLeoJuduCRzxtFAXohngNl47_SVGGlz__u3z2oBbvqamJZCWN8eotMK65WTLHyr5yXROXziZ3zpn8Pv-mURpYV-z5tdvcBxOhONqUCpU5JR3qr47gkvI73Nq140jA5VePEk0gwVdid_azS6dmfjsM6hbMwlivP5Lt0FBsyUhhD6kWLOmgPua9Y9O9qoG4VUozymBItSmNZaQm8XduT8BUg0tR9Mt1yvdzJqYxFbNkI0PIApT4ftgVIvbQD6793E02axQ38py3lc-AwnlkEf_pZ56Ziw6zX7rW-fR2JVLUsZg_ZKdDi3sFz2j8axwqMxrp5D7PPY_ySL-WzUPLIQiqp2rZ31cSfCVCHZMT_DyfVS_0ylrHdjLytDOkYD7GNwjES9QnWBIBjqOXCAfhwwZqBjBs3NjF1escydE40g8TlbpwTXa0lUu_ZKane6v5Txw")
//
//        tecactusApi.Reniec.getDni(dni)
//                .then(function (response) {
//                    console.log("consulta correcta!")
//                    console.log(response.data)
//                    $("#contrib_ape_pat").val(response.data.apellido_paterno);
//                    $("#contrib_ape_mat").val(response.data.apellido_materno);
//                    $("#contrib_nombres").val(response.data.nombres);
//                })
//                .catch(function (response) {
//                    console.log("algo ocurrió")
//                    console.log("código de error: " + response.code)
//                    console.log("mensaje de respuesta: " + response.status)
//                    console.log(response.data)
//
//                })
//        
//    }else{
//        mostraralertasconfoco('DNI requerido','#txt_nro_doc');
//        return false
//    }

    MensajeDialogLoadAjax('captcha_reniec', 'Reniec...');
    if (global_captcha_reniec == 0) {
//        global_captcha_reniec = 1;
        $("#dialog_captcha_reniec").dialog({
            autoOpen: false, modal: true, height: 220, width: 300, show: {effect: "fade", duration: 300}, resizable: false,
            title: "<div class='widget-header'><h4>&nbsp&nbsp.: CAPTCHA RENIEC :.</h4></div>",
            buttons: [{
                    html: " <span class='btn-label'><i class='fa fa-save'></i></span>Guardar",
                    "class": "btn btn-labeled bg-color-blue txt-color-white",
                    click: function () {
                        fn_traer_datos_reniec();
                        $(this).dialog("close");
                    }
                }, {
                    html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                    "class": "btn btn-primary bg-color-blue",
                    click: function () {
                        $(this).dialog("close");
                    }
                }],
            close: function (event, ui) {

            }
        }).dialog('open');

        $('#captcha_reniec').attr('src', 'https://cel.reniec.gob.pe/valreg/codigo.do');
    }
    MensajeDialogLoadAjaxFinish('captcha_reniec');
}
//function fn_traer_datos_reniec(){
//    dni=$("#txt_nro_doc").val();
//    captcha = $("#txt_captcha_reniec").val();
//}


/**********MENSAJES DEL SISTEMA*****************************************/

function foco(div)
{
    $(div).focus();
}
function mostraralertas(texto)
{
    $("#alertdialog").html('<p>' + texto + '</p>');
    $("#alertdialog").dialog('open');
}
var focoglobal = "";
function mostraralertasconfoco(texto, foco)
{
    $("#alertdialog").html('<p>' + texto + '</p>');
    $("#alertdialog").dialog('open');
    focoglobal = foco;
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
