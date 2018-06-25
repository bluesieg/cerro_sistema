
$(document).ready(function () {
    foto_global = '';
    $(function () {
        $('#vw_usuario_cargar_foto,#vw_usuario_cambiar_cargar_foto').change(function (e) {
            if (validarImagen(e)) {
                addImage(e);
            } else {
                $("#vw_usuario_foto_img,#vw_usuario_cambiar_foto_img").attr("src", "img/avatars/male.png");
                $("#vw_usuario_cargar_txt_foto,#vw_usuario_cambiar_cargar_foto").val('');
            }
        });

        function addImage(e) {
            var file = e.target.files[0],
                    imageType = /image.*/;

            if (!file.type.match(imageType)) {
                $("#vw_usuario_cargar_foto,#vw_usuario_cambiar_cargar_foto").val("");
                return mostraralertas('*Solo puede Seleccionar Imagenes JPG,JPEG, PNG');
            }

            var reader = new FileReader();
            reader.onload = fileOnload;
            reader.readAsDataURL(file);
        }
        function fileOnload(e) {
            var result = e.target.result;
            foto_global = result;
            $('#vw_usuario_foto_img,#vw_usuario_cambiar_foto_img').attr("src", foto_global);
        }

    });

    function validarImagen(e) {
        var fileSize = e.target.files[0].size;
        var siezekiloByte = parseInt(fileSize / 2000);
        if (siezekiloByte > $('#vw_usuario_foto_img,#vw_usuario_cambiar_foto_img').attr('size')) {
            mostraralertas("* La Imagen es muy grande, Tamaño Maximo 2MB");
            return false;
        } else {
            return true;
        }
    }
});
function buscar_user(){
    user = $("#vw_user_txt_buscar").val();
    fn_actualizar_grilla('table_Usuarios','list_usuarios?user='+user);
}

function open_dialog_new_edit_Usuario() {
    $("#dialog_new_edit_Usuario").dialog({
        autoOpen: false, modal: true, width: 550, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: NUEVO USUARIO :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-success",
                click: function () {
                    save_nuevo_usuario();
                }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {
                    $(this).dialog("close");
                }
            }],
        close: function (event, ui) {
            limpiar_form_usuario();
            foto_global = '';
        },
        open: function () {
            limpiar_form_usuario();
            foto_global = '';
        }
    }).dialog('open');
}
function fn_consultar_dni(num) {
    if (num == '' || num.length <= 7) {
        mostraralertas('* Ingrese Numero de Documento... <br>* ');
        return false;
    }
    $.ajax({
        url: 'consultar_persona?nro_doc=' + num,
        type: 'GET',
        success: function (data) {
            if (data) {
                $("#vw_usuario_txt_ape_nom").val(data.contrib);
                $("#vw_usuario_foto_img").attr("src", 'data:image/png;base64,' + data.pers_foto);
                $("#vw_usuario_txt_id_pers").val(data.id_pers);
                usuario = data.nombres.substr(0, 1) + data.ape_pat;
                $("#vw_usuario_txt_usuario").val(usuario.trim());
            } else {
                dlg_new_persona_user(num);
            }
        },
        error: function (data) {
            MensajeAlerta('* Error de Red...<br>* Contactese con el Administrador...');
        }
    });
}
function dlg_new_persona_user(nro_doc) {
    $("#dialog_Personas").dialog({
        autoOpen: false, modal: true, width: 750, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: PERSONAS :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-success bg-color-green",
                click: function () {
                    new_persona_user();
                }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {
                    $(this).dialog("close");
                }
            }],
        close: function (event, ui) {
            limpiar_personas();
        },
        open: function () {
            limpiar_personas();
        }
    }).dialog('open');
    $("#cb_tip_doc_3").val('02');
    $("#pers_nro_doc").val(nro_doc);
    tipo = '02';
    if (tipo == '02') {
        get_datos_dni();
        $("#pers_pat,#pers_mat,#pers_nombres").removeAttr('disabled');
        $("#pers_raz_soc").removeAttr('disabled');
        $("#pers_raz_soc").attr('disabled', true);
        $("#pers_nro_doc").removeAttr('maxlength');
        $("#pers_nro_doc").attr('maxlength', 8);
    }
}
function new_persona_user() {
    if ($("#cb_tip_doc_3").val() == '02') {
        if ($("#pers_sexo").val() == '-') {
            mostraralertasconfoco('Ingrese Sexo', '#pers_sexo');
            return false;
        }
        if ($("#pers_nombres").val() == '-'||$("#pers_nombres").val() == '') {
            mostraralertasconfoco('Ingrese Nombre', '#pers_pat');
            return false;
        }
        if ($("#pers_nro_doc").val() == '-'||$("#pers_mat").val() == '') {
            mostraralertasconfoco('Ingrese DNI', '#pers_nro_doc');
            return false;
        }
        if ($("#pers_pat").val()+$("#pers_mat").val() == '') {
            mostraralertasconfoco('Ingrese al menos un apellido', '#pers_pat');
            return false;
        }
    }
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'insert_personas_user',
        type: 'POST',
        data: {
            pers_ape_pat: $("#pers_pat").val().toUpperCase() || '-',
            pers_ape_mat: $("#pers_mat").val().toUpperCase() || '-',
            pers_nombres: $("#pers_nombres").val().toUpperCase() || '-',
            pers_raz_soc: $("#pers_raz_soc").val().toUpperCase() || '-',
            pers_tip_doc: $("#cb_tip_doc_3").val() || '-',
            pers_nro_doc: $("#pers_nro_doc").val() || '-',
            pers_sexo: $("#pers_sexo").val() || '-',
            pers_fnac: $("#pers_fnac").val() || '1900-01-01',
            pers_foto: $("#pers_foto").attr("src")
        },
        success: function (data) {
            if (data) {
                fn_consultar_dni($("#pers_nro_doc").val());
                $("#vw_usuario_txt_dni").val($("#pers_nro_doc").val());
                dialog_close('dialog_Personas');
            }
        },
        error: function (data) {
            MensajeAlerta('* Error de Red...<br>* Contactese con el Administrador...');
        }
    });
}
function btn_bus_getdatos() {
    get_datos_dni();
}
function limpiar_personas() {
    $("#pers_nro_doc,#pers_pat,#pers_mat,#pers_nombres,#pers_raz_soc,#pers_fnac").val('');
    $("#pers_foto").attr("src", "img/avatars/male.png");
}
function dlg_Editar_Usuario() {
    $("#dialog_Editar_Usuario").dialog({
        autoOpen: false, modal: true, width: 1300, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: EDITAR USUARIO :.</h4></div>",
        buttons: [ {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {
                    $(this).dialog("close");
                }
            }],
        close: function (event, ui) {
            limpiar_form_usuario();
        }
    }).dialog('open');

    id_user = $('#table_Usuarios').jqGrid('getGridParam', 'selrow');
    $.ajax({
        type: 'GET',
        url: 'get_datos_usuario?id=' + id_user,
        success: function (data) {
            llamar_sub_modulo();
            $("#vw_usuario_txt_ape_nom_2").val(data.ape_nom);
            $("#vw_usuario_txt_dni_2").val(data.dni);
            $("#vw_usuario_txt_usuario_2").val(data.usuario);
            $("#vw_usuario_chk_jefe").val(data.jefe);
            if(data.jefe=='1'){
                $("#vw_usuario_chk_jefe").attr('checked',true);
            }else if(data.jefe=='0'){
                $("#vw_usuario_chk_jefe").attr('checked',false);
            }
        }, error: function (data) {
            mostraralertas('* Error base de datos... <br> * Contactese con el administrador..');
            dialog_close('dialog_Editar_Usuario');
        }
    });
}

function save_nuevo_usuario() {
    MensajeDialogLoadAjax('table_Usuarios', 'Cargando...');
    dni = $.trim($("#vw_usuario_txt_dni").val());
    usuario = $.trim($("#vw_usuario_txt_usuario").val());

    if (dni == '' || dni.length <= 7) {
        MensajeDialogLoadAjaxFinish('table_Usuarios');
        mostraralertasconfoco('* Ingrese el Dni 8 digitos ...', 'vw_usuario_txt_dni');
        return false;
    }

    if (usuario == '' || usuario.length <= 2) {
        MensajeDialogLoadAjaxFinish('table_Usuarios');
        mostraralertasconfoco('* Ingrese un Usuario de 3 a mas caracteres...', 'vw_usuario_txt_usuario');
        return false;
    }
    pass = ($("#vw_usuario_txt_password").val()).trim();
    if (pass == '') {
        MensajeDialogLoadAjaxFinish('table_Usuarios');
        mostraralertasconfoco('* Ingrese su Contraseña', 'vw_usuario_txt_password');
        return false
    }
    confir_pass = ($("#vw_usuario_txt_conf_pass").val()).trim();
    if (pass != confir_pass) {
        mostraralertasconfoco('* Las Contraseñas no Coinciden', 'vw_usuario_txt_password');
        return false;
    }

    var promise = validar_usuario(usuario);
    promise.done(function (result) {
        if (result == false) {
            var promise_2 = validar_dni(dni);
            promise_2.done(function (result2) {
                if (result2 == false) {
                    if ($("#form_user").submit()) {
                        dialog_close('dialog_new_edit_Usuario');
                    }
                }
                console.log("Bool: " + result + " Bool2:" + result2);
                setTimeout(function () {
                    fn_actualizar_grilla('table_Usuarios', 'list_usuarios');
                    MensajeDialogLoadAjaxFinish('table_Usuarios');
                }, 2000);
            });
        }
    });

}
function on_jefe(){
    if($("#vw_usuario_chk_jefe").is(':checked')){
        $("#vw_usuario_chk_jefe").val('1');
    } else {
        $("#vw_usuario_chk_jefe").val('0');
    }
}
function update_user() {
    jefe = $("#vw_usuario_chk_jefe").val();
    dni = $.trim($("#vw_usuario_txt_dni_2").val());
    usuario = $.trim($("#vw_usuario_txt_usuario_2").val());
    ape_nom = $.trim($("#vw_usuario_txt_ape_nom_2").val());

    if (dni == '' || dni.length <= 7) {
        mostraralertasconfoco('* Ingrese el Dni 8 digitos ...', 'vw_usuario_txt_dni');
        return false;
    }

    if (usuario == '' || usuario.length <= 2) {
        mostraralertasconfoco('* Ingrese un Usuario de 3 a mas caracteres...', 'vw_usuario_txt_usuario');
        return false;
    }
    id_user = $('#table_Usuarios').jqGrid('getGridParam', 'selrow');
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'POST',
        url: 'usuario_update',
        data: {id: id_user, dni: dni, usuario: usuario.toUpperCase(), ape_nom: ape_nom.toUpperCase(),jefe:jefe},
        success: function (data) {
            if (data.msg == 'si') {
                fn_actualizar_grilla('table_Usuarios', 'list_usuarios');
                dialog_close('dialog_Editar_Usuario');
            } else {
                mostraralertas('* Error al Modificar Usuario...!');
            }
        }, error: function (data) {
            mostraralertas('* Error al guardar Usuario <br> * Contactese con el administrador..');
            dialog_close('dialog_Editar_Usuario');
        }
    });
}
function cambiar_foto_usuario() {
    $("#dialog_Cambiar_Foto_Usuario").dialog({
        autoOpen: false, modal: true, width: 350, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: CAMBIAR FOTO :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-success",
                click: function () {
                    update_foto();
                }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {
                    $(this).dialog("close");
                }
            }],
        close: function (event, ui) {
            $("#vw_usuario_cambiar_foto_img").attr("src", "img/avatars/male.png");
            $("#vw_usuario_cambiar_cargar_foto").val('');
            foto_global = '';
        }
    }).dialog('open');
}

function cambiar_password() {
    $("#dialog_Cambiar_password").dialog({
        autoOpen: false, modal: true, width: 350, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: CONFIGURACION DE USUARIO :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-success",
                click: function () {
                    if ($("#vw_usuario_cam_pass_1").val() == $("#vw_usuario_cam_pass_2").val()) {
                        $.ajax({
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            url: 'cambiar_pass_user',
                            method: "POST",
                            data: {pass1: $("#vw_usuario_cam_pass_1").val()},
                            success: function (data) {
                                if (data.msg == 'si') {
                                    dialog_close('dialog_Cambiar_password');
                                } else {
                                    mostraralertas('* Error al cambiar la Contraseña.');
                                }
                            },
                            error: function (er) {
                                mostraralertas('* Error en Base de Datos.<br>* Comuniquese con el Administrador.');
                            }
                        });
                    } else {
                        mostraralertas('* Las Contraseñas no son Iguales');
                    }
                }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {
                    $(this).dialog("close");
                }
            }],
        close: function (event, ui) {}
    }).dialog('open');
}

function update_foto() {

    var filesSelected = $("#vw_usuario_cambiar_cargar_foto").val();
    if (filesSelected == '') {
        mostraralertasconfoco('* Seleccione una Foto', 'vw_usuario_cambiar_cargar_foto');
        return false;
    }
    var form = new FormData($("#form_cambiar_foto")[0]);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'cambiar_foto_user',
        method: "POST",
        dataType: 'json',
        data: form,
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.msg == 'si') {
                dialog_close('dialog_Cambiar_Foto_Usuario');
                location.reload();
            } else {
                mostraralertas('* Error al cambiar la foto.');
            }
        },
        error: function (er) {
            mostraralertas('* Error en Base de Datos.<br>* Comuniquese con el Administrador.');
        }
    });

}
function validar_usuario(usuario) {
    var deferred = $.Deferred();
    var bool = false;
    MensajeDialogLoadAjax('vw_usuario_txt_usuario', '.: validando ...');
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'usuarios_validar_user?usuario=' + usuario.toUpperCase(),
        type: 'GET',
        success: function (data) {
            MensajeDialogLoadAjaxFinish('vw_usuario_txt_usuario');
            if (data.msg == 'si') {
                $("#vw_usuario_txt_usuario").css({border: "1px solid #FF4040"});
                mostraralertasconfoco('* El Usuario ' + ($('#vw_usuario_txt_usuario').val()).toUpperCase() + ' Ya Existe...', 'vw_usuario_txt_usuario');
                memory_glob_usuario = $('#vw_usuario_txt_usuario').val();
                bool = true;
            } else if (data.msg == 'no') {
                $("#vw_usuario_txt_usuario").css({border: "1px solid #BDBDBD"});
            }
        },
        error: function (data) {
            mostraralertas('* Error de Red... <br>* Contactese con el Administrador ');
            MensajeDialogLoadAjaxFinish('vw_usuario_txt_usuario');
        },
        complete: function () {
            deferred.resolve(trueOrFalse(bool));
        }
    });
    return deferred.promise();
}
function trueOrFalse(bool) {
    return bool;
}
function validar_dni(dni) {
    var deferred = $.Deferred();
    var bool = false;
    MensajeDialogLoadAjax('vw_usuario_txt_dni', '.: validando ...');
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'usuarios_validar_dni?dni=' + dni,
        type: 'GET',
        success: function (data) {
            MensajeDialogLoadAjaxFinish('vw_usuario_txt_dni');
            if (data.msg == 'si') {
                $("#vw_usuario_txt_dni").css({border: "1px solid #FF4040"});
                mostraralertasconfoco('* Un Usuario con Dni ' + $('#vw_usuario_txt_dni').val() + ' Ya Existe...', 'vw_usuario_txt_dni');
                memory_glob_dni = $('#vw_usuario_txt_dni').val();
                bool = true;
            } else if (data.msg == 'no') {
                $("#vw_usuario_txt_dni").css({border: "1px solid #BDBDBD"});
            }
        },
        error: function (data) {
            mostraralertas('* Error al Valida Dni...!');
            MensajeDialogLoadAjaxFinish('vw_usuario_txt_dni');
        },
        complete: function () {
            deferred.resolve(trueOrFalse(bool));
        }
    });
    return deferred.promise();
}

function limpiar_form_usuario() {
    $("#vw_usuario_txt_ape_nom").val('');
    $("#vw_usuario_foto_img").attr("src", "img/avatars/male.png");
    $("#vw_usuario_cargar_foto").val("");
    $("#vw_usuario_txt_dni").val('');
    $("#vw_usuario_txt_fch_nac").val('');
    $("#vw_usuario_txt_usuario").val('');
    $("#vw_usuario_txt_password").val('');
    $("#vw_usuario_txt_conf_pass").val('');
    $("#vw_usuario_txt_dni").css({border: "1px solid #BDBDBD"});
    $("#vw_usuario_txt_usuario").css({border: "1px solid #BDBDBD"});
}

function eliminar_usuario() {
    id = $('#table_Usuarios').jqGrid('getGridParam', 'selrow');
    if(id)
    {
        $.confirm({
            title: '.:Cuidado... !',
            content: 'Los Cambios no se podran revertir...',
            buttons: {
                Confirmar: function () {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        type: 'POST',
                        url: 'usuario_delete',
                        data: {id: id},
                        success: function (data) {
                            fn_actualizar_grilla('table_Usuarios', 'list_usuarios');
                            MensajeExito('Usuario','El Usuario fue eliminado...');
                        }, error: function (data) {
                            dialog_close('dialog_new_edit_Usuario');
                            MensajeAlerta('* Error.', 'Contactese con el Administrador.');
                        }
                    });
                },
                Cancelar: function () {
                    MensajeAlerta('Eliminar Usuario..', 'Operacion Cancelada.');
                }
            }
        });
    }else{
        mostraralertasconfoco("No Hay Usuarios Seleccionados","#table_Usuarios");
    }
}


function imprimir_usuario(num_rep){
    $("#dialog_usuarios_imprimir").dialog({
        autoOpen: false, modal: true, width: 350, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: REPORTE DE USUARIOS :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Ver Reporte"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { mostrar_usuarios(num_rep); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
}

function mostrar_usuarios(){
    window.open('get_usuarios/'+ $('#select_usuarios').val());
}