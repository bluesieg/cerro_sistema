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