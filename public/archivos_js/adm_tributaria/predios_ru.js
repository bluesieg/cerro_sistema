function fn_bus_contrib_rus()
{
    if($("#dlg_contri").val()=="")
    {
        mostraralertasconfoco("Ingresar Información de busqueda","#dlg_contri"); 
        return false;
    }
    if($("#dlg_contri").val().length<4)
    {
        mostraralertasconfoco("Ingresar al menos 4 caracteres de busqueda","#dlg_contri"); 
        return false;
    }
    jQuery("#table_contrib").jqGrid('setGridParam', {url: 'obtiene_cotriname?dat='+$("#dlg_contri").val()}).trigger('reloadGrid');

    $("#dlg_bus_contr").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  Busqueda de Contribuyente :.</h4></div>"       
        }).dialog('open');
       
}
function fn_bus_contrib_list_rus(per)
{
    $("#dlg_contri_hidden").val(per);
    $("#dlg_dni").val($('#table_contrib').jqGrid('getCell',per,'nro_doc'));
    $("#dlg_contri").val($('#table_contrib').jqGrid('getCell',per,'contribuyente'));
    $("#dlg_bus_contr").dialog("close");
    
}
function llamarcambio()
{
    auto_select("dlg_sel_gpocatterr","sel_cat_gruterr",$("#dlg_sel_gpoterr").val(),0);
    $("#dlg_inp_aranc").val(0);
    validarvalter();
}
    
    function limpiarpred(tip)
    {
        
        auto_input("dlg_inp_usopre","autocompletar_tipo_uso",1);
        $("#dlg_inp_valle,#dlg_inp_carre,#dlg_inp_km,#dlg_inp_nompre,#dlg_inp_norte,#dlg_inp_sur,#dlg_inp_este,#dlg_inp_oeste").val("");
        $("#dlg_sel_tterr,#dlg_sel_uterr,#dlg_sel_estcon,#dlg_sel_tippre").val(1);
        $("#dlg_inp_aranc,#dlg_inp_areter,#dlg_inp_valterr").val("")
        autocompletar=1;
        $("#dlg_reg_dj").dialog({
        autoOpen: false, modal: true, width: 1300, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  REGISTRO DE DECLARACIONES JURADAS :.</h4></div>",
        buttons: [
            {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-primary bg-color-red",
                click: function () {$(this).dialog("close");}
            }]
        });
        if(tip==1)
        {
            $("#dlg_idpre").val(0);
            $( "#dlg_dni,#dlg_contri" ).prop( "disabled", false );
            $("#btnsavepre").show();
            $("#btnmodpre").hide();
            $("#dlg_sel_condpre,#dlg_sel_estcon,#dlg_sel_tippre,#dlg_sel_foradq").val(1);
            $('#dlg_inp_condos').val(100);
            $("#dlg_inp_condos").prop('disabled', true);
           
            
        }
        if(tip==2)
        {
            $("#hidden_dlg_inp_usopre").val(0);
            $("#dlg_inp_usopre_cod,#dlg_inp_usopre,#dlg_sel_condpre").val("");
            $( "#dlg_dni, #dlg_lot,#dlg_contri" ).prop( "disabled", true );
            $('#dlg_inp_condos').val("");
            $("#btnsavepre").hide();
            $("#btnmodpre").show();
        }
        $("#dlg_sel_gpoterr").val(2);
        $('#dlg_dni,#dlg_contri,#dlg_inp_fech').val("");
        $("#dlg_inp_areter").val("");
        $("#s5_sel_condi,#s5_inp_basleg,#s5_inp_exp,#s5_inp_reso,#s5_inp_fechres,#s5_inp_anini,#s5_inp_anfin").val("");
    }
    function validacond()
    {
        if($("#dlg_sel_condpre").val()==5||$("#dlg_sel_condpre option:selected").text()=="Condominio")
        {
            $("#dlg_inp_condos").prop('disabled', false);
        }
        else
        {
            $("#dlg_inp_condos").val(100);
            $("#dlg_inp_condos").prop('disabled', true);
        }
    }
    function validarcampos()
    {
        if($('#dlg_inp_condos').val()==""){$('#dlg_inp_condos').val('0');}
        if($('#dlg_inp_areter').val()==""){$('#dlg_inp_areter').val(0);}
        if($('#dlg_inp_aranc').val()==""){$('#dlg_inp_aranc').val(0);}
        if($('#dlg_inp_norte').val()==""){$('#dlg_inp_norte').val("-");}
        if($('#dlg_inp_sur').val()==""){$('#dlg_inp_sur').val("-");}
        if($('#dlg_inp_este').val()==""){$('#dlg_inp_este').val("-");}
        if($('#dlg_inp_oeste').val()==""){$('#dlg_inp_oeste').val("-");}
        if($('#dlg_inp_valle').val()==""){$('#dlg_inp_valle').val("-");}
        if($('#dlg_inp_carre').val()==""){$('#dlg_inp_carre').val("-");}
    }
    
    function clicknewgrid()
    {
        
        jQuery("#table_pisos").jqGrid('setGridParam', {url: 'gridpisos/0'}).trigger('reloadGrid');
        jQuery("#table_condos").jqGrid('setGridParam', {url: 'gridcondos/0'}).trigger('reloadGrid');
        jQuery("#table_instal").jqGrid('setGridParam', {url: 'gridinsta/0'}).trigger('reloadGrid');
        limpiarpred(1);
        auto_select("dlg_sel_gpocatterr","sel_cat_gruterr",2,"");
        $("#dlg_reg_dj").dialog('open');
    }
    function clickmodgrid()
    {
        limpiarpred(2);
       $("#dlg_reg_dj").dialog('open');
       MensajeDialogLoadAjax('dlg_reg_dj', '.:: Cargando ...');
       Id=$('#table_predios').jqGrid ('getGridParam', 'selrow');
       
       $("#dlg_idpre").val(Id);
       $.ajax({url: 'predios_rural/'+Id,
        type: 'GET',
        success: function(r) 
        {
                $("#dlg_dni").val(r[0].nro_doc);
                $("#dlg_contri").val(r[0].contribuyente);
                $("#dlg_sel_condpre").val(r[0].id_cond_prop);
                $("#dlg_inp_condos").val(r[0].nro_condominios);
                validacond();
                $("#dlg_sel_estcon").val(r[0].id_est_const);
                $("#dlg_sel_tippre").val(r[0].id_tip_pred);
                $("#dlg_sel_foradq").val(r[0].id_form_adq);
                $("#dlg_inp_fech").val(r[0].fech_adquis);
                $("#dlg_inp_aranc").val(r[0].arancel);
                $("#dlg_inp_valterr").val(formato_numero(r[0].val_ter,2,".",","));
                $("#dlg_inp_areter").val(r[0].are_terr);
                $("#dlg_inp_valle").val(r[0].lugar_pr_rust);
                $("#dlg_inp_carre").val(r[0].ubicac_pr_rus);
                $("#dlg_inp_km").val(r[0].klm);
                $("#dlg_inp_nompre").val(r[0].nom_pre_pr_rus);
                $("#dlg_inp_norte").val(r[0].norte);
                $("#dlg_inp_sur").val(r[0].sur);
                $("#dlg_inp_este").val(r[0].este);
                $("#dlg_inp_oeste").val(r[0].oeste);
                $("#dlg_sel_tterr").val(r[0].id_tip_pre_rus);
                $("#dlg_sel_uterr").val(r[0].id_uso_pre_rust);
                $("#dlg_sel_gpoterr").val(r[0].id_gpo_tierra);
                auto_select("dlg_sel_gpocatterr","sel_cat_gruterr",r[0].id_gpo_tierra,r[0].id_cat_gpo_tierra);
                MensajeDialogLoadAjaxFinish('dlg_reg_dj');
                jQuery("#table_pisos").jqGrid('setGridParam', {url: 'gridpisos/'+Id}).trigger('reloadGrid');
                jQuery("#table_condos").jqGrid('setGridParam', {url: 'gridcondos/'+Id}).trigger('reloadGrid');
                jQuery("#table_instal").jqGrid('setGridParam', {url: 'gridinsta/'+Id}).trigger('reloadGrid');

        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            MensajeDialogLoadAjaxFinish('dlg_reg_dj');
        }
        }); 
        
        

    }
    function dlgSave()
    {
        
        if($('#dlg_contri_hidden').val()==0){mostraralertasconfoco('Ingresar contribuyente...',"#dlg_dni");return false}
        if($('#dlg_sel_condpre').val()==null){mostraralertasconfoco('Ingresar condicion predio...',"#dlg_sel_condpre");return false}
        if($('#dlg_sel_gpocatterr').val()==null){mostraralertasconfoco('Seleccion categoria de Tierras...',"#dlg_sel_gpocatterr");return false}
        if($('#dlg_inp_condos').val()>100){mostraralertasconfoco('Porcentaje de Posesión no puede ser Mayor al 100%...',"#dlg_inp_condos");return false}
        validarcampos();
        MensajeDialogLoadAjax('dlg_reg_dj', '.:: Guardando ...');
        $.ajax({url: 'predios_rural/create',
        type: 'GET',
        data:{condpre:$('#dlg_sel_condpre').val(),condos:$('#dlg_inp_condos').val(),
        contrib:$('#dlg_contri_hidden').val(),ecc:$("#dlg_sel_estcon").val(),tpre:$("#dlg_sel_tippre").val(),
        ifor:$("#dlg_sel_foradq").val(),ffor:$("#dlg_inp_fech").val(),
        areterr:$("#dlg_inp_areter").val(),aranc:$("#dlg_inp_aranc").val(),an:$("#selantra").val(),
        valle:$("#dlg_inp_valle").val(),carretera:$("#dlg_inp_carre").val(),km:$("#dlg_inp_km").val(),
        nompre:$("#dlg_inp_nompre").val(),norte:$("#dlg_inp_norte").val(),sur:$("#dlg_inp_sur").val(),
        este:$("#dlg_inp_este").val(),oeste:$("#dlg_inp_oeste").val(),tterr:$("#dlg_sel_tterr").val(),uterr:$("#dlg_sel_uterr").val(),
        gpt:$("#dlg_sel_gpoterr").val(),cgpt:$("#dlg_sel_gpocatterr").val()},
        success: function(r) 
        {
            $('#dlg_idpre').val(r);
            MensajeExito("Insertó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            jQuery("#table_predios").jqGrid('setGridParam', {url: 'gridpredio?tpre=2&mnza=0&ctr=0&an='+$("#selantra").val()}).trigger('reloadGrid');
            MensajeDialogLoadAjaxFinish('dlg_reg_dj');
            $( "#dlg_dni, #dlg_lot,#dlg_contri" ).prop( "disabled", true );
            $("#btnsavepre").hide();
            $("#btnmodpre").show();
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dlg_reg_dj');
            console.log('error');
            console.log(data);
        }
        });
    }
    function dlgUpdate()
    {
        if($('#dlg_sel_condpre').val()==null){mostraralertasconfoco('Ingresar condicion predio...',"#dlg_sel_condpre");return false}
        if($('#dlg_inp_condos').val()>100){mostraralertasconfoco('Porcentaje de Posesión no puede ser Mayor al 100%...',"#dlg_inp_condos");return false}
        validarcampos();
        MensajeDialogLoadAjax('dlg_reg_dj', '.:: CARGANDO ...');
        $.ajax({url: 'predios_rural/'+$('#dlg_idpre').val()+'/edit',
        type: 'GET',
        data:{condpre:$('#dlg_sel_condpre').val(),condos:$('#dlg_inp_condos').val(),
        ecc:$("#dlg_sel_estcon").val(),tpre:$("#dlg_sel_tippre").val(),
        ifor:$("#dlg_sel_foradq").val(),ffor:$("#dlg_inp_fech").val(),
        areterr:$("#dlg_inp_areter").val(),aranc:$("#dlg_inp_aranc").val(),an:$("#selantra").val(),
        valle:$("#dlg_inp_valle").val(),carretera:$("#dlg_inp_carre").val(),km:$("#dlg_inp_km").val(),
        nompre:$("#dlg_inp_nompre").val(),norte:$("#dlg_inp_norte").val(),sur:$("#dlg_inp_sur").val(),
        este:$("#dlg_inp_este").val(),oeste:$("#dlg_inp_oeste").val(),tterr:$("#dlg_sel_tterr").val(),uterr:$("#dlg_sel_uterr").val(),
        gpt:$("#dlg_sel_gpoterr").val(),cgpt:$("#dlg_sel_gpocatterr").val()},
        success: function(r) 
        {
            MensajeExito("Modificó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            jQuery("#table_predios").jqGrid('setGridParam', {url: 'gridpredio?tpre=2&mnza=0&ctr=0&an='+$("#selantra").val()}).trigger('reloadGrid');
            MensajeDialogLoadAjaxFinish('dlg_reg_dj');

        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dlg_reg_dj');
            console.log('error');
            console.log(data);
        }
        });
       
    }
    function creardlgpiso()
    {
     $("#dlg_reg_piso").dialog({
        autoOpen: false, modal: true, width: 700, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  REGISTRO DE CONSTRUCCIONES :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                id:"btnpissave",
                "class": "btn btn-primary bg-color-green",
                click: function () {pisoSave();}
            },
            {
                html: "<i class='fa fa-save'></i>&nbsp; Modificar",
                "class": "btn btn-primary bg-color-blue",
                id:"btnpismod",
                click: function () {pisoUpdate();}
            },
            {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-primary bg-color-red",
                click: function () {$(this).dialog("close");}
            }],
        }).dialog('open');
    }
    function clicknewpiso()
    {
        $('#dlg_idpiso').val(0);
        if($('#dlg_idpre').val()==0)
        {
            mostraralertas("Primero Guardar Predio...");
            return false;
        }
       if($('#dlg_sel_estcon').val()==1)
        {
            mostraralertas("No se puede crear piso en un terreno sin construir..");
            return false;
        }
        $("#rpiso_inp_nro,#rpiso_inp_fech,#rpiso_inp_econstr,#rpiso_inp_estruc,#rpiso_inp_aconst,#rpiso_inp_acomun").val("")
        $("#rpiso_inp_clasi").val($("#rpiso_inp_clasi option:first").val());
        $("#rpiso_inp_mat").val($("#rpiso_inp_mat option:first").val());
        $("#rpiso_inp_econserv").val($("#rpiso_inp_econserv option:first").val());
        $("#rpiso_inp_econstr").val($("#rpiso_inp_econstr option:first").val());
        callchangeoption("rpiso_inp_clasi",0);
        callchangeoption("rpiso_inp_mat",0);
        callchangeoption("rpiso_inp_econserv",0);
        callchangeoption("rpiso_inp_econstr",0);
        creardlgpiso();
        $("#btnpissave").show();
        $("#btnpismod").hide();
    }
    function clickmodpiso()
    {
        if($('#dlg_idpre').val()==0)
        {
            mostraralertas("Primero Guardar Predio...");
            return false;
        }
        Id=$('#table_pisos').jqGrid ('getGridParam', 'selrow');
        $('#dlg_idpiso').val(Id);
        creardlgpiso();
        $("#btnpissave").hide();
        $("#btnpismod").show();
        MensajeDialogLoadAjax('dlg_reg_piso', '.:: Cargando ...');
        $.ajax({url: 'pisos_predios/'+Id,
        type: 'GET',
        success: function(r) 
        {
            $("#rpiso_inp_nro").val(r[0].cod_piso);
            $("#rpiso_inp_fech").val(r[0].ani_const);
            $("#rpiso_inp_clasi").val(parseInt(r[0].clas));
            $("#rpiso_inp_mat").val(r[0].mep);
            $("#rpiso_inp_econserv").val(r[0].esc);
            $("#rpiso_inp_econstr").val(parseInt(r[0].ecc));
            $("#rpiso_inp_estruc").val(r[0].est_mur+r[0].est_tch+r[0].aca_pis+r[0].aca_pta+r[0].aca_rev+r[0].aca_ban+r[0].ins_ele);
            $("#rpiso_inp_aconst").val(r[0].area_const);
            $("#rpiso_inp_acomun").val(r[0].val_areas_com);
            MensajeDialogLoadAjaxFinish('dlg_reg_piso');
            callchangeoption("rpiso_inp_clasi",0);
            callchangeoption("rpiso_inp_mat",0);
            callchangeoption("rpiso_inp_econserv",0);
            callchangeoption("rpiso_inp_econstr",0);

        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            MensajeDialogLoadAjaxFinish('dlg_reg_piso');
            $("#dlg_reg_piso").dialog('close');
        }
        });
    }
    function pisoSave()
    {
        if($("#rpiso_inp_nro").val()==""){mostraralertasconfoco("Ingresar Nro Piso","#rpiso_inp_nro"); return false}
        if($("#rpiso_inp_fech").val()==""){mostraralertasconfoco("Ingresar Fecha del Piso","#rpiso_inp_fech"); return false}
        if($("#rpiso_inp_clasi").val()==""){mostraralertasconfoco("Ingresar Estado de Conservación","#rpiso_inp_econserv"); return false}
        if($("#rpiso_inp_mat").val()==""){mostraralertasconfoco("Ingresar Estado de Conservación","#rpiso_inp_econserv"); return false}
        if($("#rpiso_inp_econserv").val()==""){mostraralertasconfoco("Ingresar Estado de Conservación","#rpiso_inp_econserv"); return false}
        if($("#rpiso_inp_econstr").val()==""){mostraralertasconfoco("Ingresar Estado de Construcción","#rpiso_inp_econstr"); return false}
        if($("#rpiso_inp_estruc").val()==""){mostraralertasconfoco("Ingresar Estructuras","#rpiso_inp_estruc"); return false}
        if($("#rpiso_inp_estruc").val().length<7){mostraralertasconfoco("Cadena de Estructura incompleta, Ingrese 7 caracteres","#rpiso_inp_estruc"); return false}
        if($("#rpiso_inp_aconst").val()==""){mostraralertasconfoco("Ingresar Area Construida","#rpiso_inp_aconst"); return false}
        if($("#rpiso_inp_acomun").val()==""){$("#rpiso_inp_acomun").val(0)}
        MensajeDialogLoadAjax('dlg_reg_piso', '.:: Guardando ...');
        Id_pre=$('#dlg_idpre').val();
        $("#rpiso_inp_estruc").val().toUpperCase();
        $.ajax({url: 'pisos_predios/create',
        type: 'GET',
        data:{nro:$("#rpiso_inp_nro").val(),fech:$("#rpiso_inp_fech").val(),clasi:$("#rpiso_inp_clasi").val(),
        mep:$("#rpiso_inp_mat").val(),estconserv:$("#rpiso_inp_econserv").val(),estconst:$("#rpiso_inp_econstr").val(),
        estru:$("#rpiso_inp_estruc").val().toUpperCase(),aconst:$("#rpiso_inp_aconst").val(),acomun:$("#rpiso_inp_acomun").val(),id_pre:Id_pre},
        success: function(r) 
        {
            MensajeExito("Insertó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            jQuery("#table_pisos").jqGrid('setGridParam', {url: 'gridpisos/'+Id_pre}).trigger('reloadGrid');
            jQuery("#table_predios").jqGrid('setGridParam', {url: 'gridpredio?tpre=2&mnza=0&ctr=0&an='+$("#selantra").val()}).trigger('reloadGrid');
            MensajeDialogLoadAjaxFinish('dlg_reg_piso');
            $("#dlg_reg_piso").dialog('close');
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dlg_reg_piso');
            console.log('error');
            console.log(data);
        }
        });
    }
    function pisoUpdate()
    {
        if($("#per_edit").val()==0)
        {
            sin_permiso();
            return false;
        }
        if($("#rpiso_inp_nro").val()==""){mostraralertasconfoco("Ingresar Nro Piso","#rpiso_inp_nro"); return false}
        if($("#rpiso_inp_fech").val()==""){mostraralertasconfoco("Ingresar Fecha del Piso","#rpiso_inp_fech"); return false}
        if($("#rpiso_inp_estruc").val()==""){mostraralertasconfoco("Ingresar Estructuras","#rpiso_inp_estruc"); return false}
        if($("#rpiso_inp_estruc").val().length<7){mostraralertasconfoco("Cadena de Estructura incompleta, Ingrese 7 caracteres","#rpiso_inp_estruc"); return false}
        if($("#rpiso_inp_aconst").val()==""){mostraralertasconfoco("Ingresar Area Construida","#rpiso_inp_aconst"); return false}
        if($("#rpiso_inp_acomun").val()==""){mostraralertasconfoco("Ingresar Area Común","#rpiso_inp_acomun"); return false}
        MensajeDialogLoadAjax('dlg_reg_piso', '.:: Modificando ...');
        
        $.ajax({url: 'pisos_predios/'+$('#dlg_idpiso').val()+'/edit',
        type: 'GET',
        data:{nro:$("#rpiso_inp_nro").val(),fech:$("#rpiso_inp_fech").val(),clasi:$("#rpiso_inp_clasi").val(),
        mep:$("#rpiso_inp_mat").val(),estconserv:$("#rpiso_inp_econserv").val(),estconst:$("#rpiso_inp_econstr").val(),
        estru:$("#rpiso_inp_estruc").val().toUpperCase(),aconst:$("#rpiso_inp_aconst").val(),acomun:$("#rpiso_inp_acomun").val()},
        success: function(r) 
        {
            MensajeExito("Modificó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            jQuery("#table_pisos").jqGrid('setGridParam', {url: 'gridpisos/'+$('#dlg_idpre').val()}).trigger('reloadGrid');
            jQuery("#table_predios").jqGrid('setGridParam', {url: 'gridpredio?tpre=2&mnza=0&ctr=0&an='+$("#selantra").val()}).trigger('reloadGrid');
            MensajeDialogLoadAjaxFinish('dlg_reg_piso');
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dlg_reg_piso');
            console.log('error');
            console.log(data);
        }
        });
    }
    function pisoDelete()
    {
        if($("#per_del").val()==0)
        {
            sin_permiso();
            return false;
        }
        if($('#dlg_idpre').val()==0)
        {
            mostraralertas("Primero Guardar Predio...");
            return false;
        }
        Id=$('#table_pisos').jqGrid ('getGridParam', 'selrow');
        MensajeDialogLoadAjax('s1', '.:: Eliminando ...');
        $.ajax({
            url: 'pisos_predios/destroy',
            type: 'post',
            data: {_method: 'delete', _token:$("#btn_s1_delpiso").data('token'),id:Id},
            success: function(r) 
            {
                jQuery("#table_pisos").jqGrid('setGridParam', {url: 'gridpisos/'+$('#dlg_idpre').val()}).trigger('reloadGrid');
                jQuery("#table_predios").jqGrid('setGridParam', {url: 'gridpredio?tpre=2&mnza=0&ctr=0&an='+$("#selantra").val()}).trigger('reloadGrid');
                MensajeAlerta("Se Eliminó Correctamente","Su Registro Fue eliminado Correctamente...",4000)
                MensajeDialogLoadAjaxFinish('s1');
            },
            error: function(data) {
                mostraralertas("hubo un error, Comunicar al Administrador");
                MensajeDialogLoadAjaxFinish('s1');
                console.log('error');
                console.log(data);
            }
        });
    }
    function creardlgcondo()
    {
     $("#dlg_reg_condo").dialog({
        autoOpen: false, modal: true, width: 700, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  REGISTRO DE CONDOMINIOS :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                id:"btncondsave",
                "class": "btn btn-primary bg-color-green",
                click: function () {condoSave();}
            },
            {
                html: "<i class='fa fa-save'></i>&nbsp; Modificar",
                "class": "btn btn-primary bg-color-blue",
                id:"btncondmod",
                click: function () {condoUpdate();}
            },
            {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-primary bg-color-red",
                click: function () {$(this).dialog("close");}
            }],
        }).dialog('open');
    }
    function clicknewcondo()
    {
        $('#dlg_idcondo').val(0);
        if($('#dlg_idpre').val()==0)
        {
            mostraralertas("Primero Guardar Predio...");
            return false;
        }
        $('#rcondo_inp_dni,#rcondo_inp_rsoc,#rcondo_inp_dir,#rcondo_inp_porcent').val("");
        $('#rcondo_inp_dni_hidden').val(0);
        creardlgcondo();
        $("#btncondsave").show();
        $("#btncondmod").hide();
    }
    function clickmodcondo()
    {
        if($('#dlg_idpre').val()==0)
        {
            mostraralertas("Primero Guardar Predio...");
            return false;
        }
        Id=$('#table_condos').jqGrid ('getGridParam', 'selrow');
        $('#dlg_idcondo').val(Id);
        creardlgcondo();
        $("#btncondsave").hide();
        $("#btncondmod").show();
        MensajeDialogLoadAjax('dlg_reg_condo', '.:: Cargando ...');
        $.ajax({url: 'condominios_predios/'+Id,
        type: 'GET',
        success: function(r) 
        {
            $("#rcondo_inp_dni").val(r[0].nro_doc);
            $("#rcondo_inp_rsoc_hidden").val(r[0].id_contrib);
            $("#rcondo_inp_rsoc").val(r[0].ape_pat+" "+r[0].ape_pat+" "+r[0].nombres);
            $("#rcondo_inp_dir").val(r[0].direccion);
            $("#rcondo_inp_porcent").val(r[0].porcent);
            MensajeDialogLoadAjaxFinish('dlg_reg_condo');
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            MensajeDialogLoadAjaxFinish('dlg_reg_condo');
            $("#dlg_reg_condo").dialog('close');
        }
        });
        
        
    }
    function condoSave()
    {
        if($("#rcondo_inp_dni").val()==""){mostraralertasconfoco("Ingresar Nro DNI o RUC","#rcondo_inp_dni"); return false}
        if($("#rcondo_inp_rsoc_hidden").val()=="0"){mostraralertasconfoco("Ingresar Nro DNI o RUC y confirmar con tecla enter","#rcondo_inp_dni"); return false}
        if($("#rcondo_inp_dir").val()==""){mostraralertasconfoco("Ingresar Dirección","#rcondo_inp_dir"); return false}
        if($("#rcondo_inp_porcent").val()==""){mostraralertasconfoco("Ingresar porcentaje","#rcondo_inp_porcent"); return false}
        MensajeDialogLoadAjax('dlg_reg_condo', '.:: Guardando ...');
        Id_pre=$('#dlg_idpre').val();
        $.ajax({url: 'condominios_predios/create',
        type: 'GET',
        data:{contrib:$("#rcondo_inp_rsoc_hidden").val(),dir:$("#rcondo_inp_dir").val().toUpperCase(),porc:$("#rcondo_inp_porcent").val(),id_pre:Id_pre},
        success: function(r) 
        {
            MensajeExito("Insertó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            jQuery("#table_condos").jqGrid('setGridParam', {url: 'gridcondos/'+Id_pre}).trigger('reloadGrid');
            jQuery("#table_predios").jqGrid('setGridParam', {url: 'gridpredio?tpre=2&mnza=0&ctr=0&an='+$("#selantra").val()}).trigger('reloadGrid');
            MensajeDialogLoadAjaxFinish('dlg_reg_condo');
            $("#dlg_reg_condo").dialog('close');
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dlg_reg_condo');
            console.log('error');
            console.log(data);
        }
        });
    }
    function condoUpdate()
    {
        if($("#per_edit").val()==0)
        {
            sin_permiso();
            return false;
        }
        if($("#rcondo_inp_dni").val()==""){mostraralertasconfoco("Ingresar Nro DNI o RUC","#rcondo_inp_dni"); return false}
        if($("#rcondo_inp_rsoc_hidden").val()=="0"){mostraralertasconfoco("Ingresar Nro DNI o RUC y confirmar con tecla enter","#rcondo_inp_dni"); return false}
        if($("#rcondo_inp_dir").val()==""){mostraralertasconfoco("Ingresar Dirección","#rcondo_inp_dir"); return false}
        if($("#rcondo_inp_porcent").val()==""){mostraralertasconfoco("Ingresar porcentaje","#rcondo_inp_porcent"); return false}
        MensajeDialogLoadAjax('dlg_reg_condo', '.:: Modificando ...');
        $.ajax({url: 'condominios_predios/'+$('#dlg_idcondo').val()+'/edit',
        type: 'GET',
        data:{contrib:$("#rcondo_inp_rsoc_hidden").val(),dir:$("#rcondo_inp_dir").val().toUpperCase(),porc:$("#rcondo_inp_porcent").val()},
        success: function(r) 
        {
            MensajeExito("Modificó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            jQuery("#table_condos").jqGrid('setGridParam', {url: 'gridcondos/'+$('#dlg_idpre').val()}).trigger('reloadGrid');
            jQuery("#table_predios").jqGrid('setGridParam', {url: 'gridpredio?tpre=2&mnza=0&ctr=0&an='+$("#selantra").val()}).trigger('reloadGrid');
            
            MensajeDialogLoadAjaxFinish('dlg_reg_condo');
            $("#dlg_reg_condo").dialog('close');
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dlg_reg_condo');
            console.log('error');
            console.log(data);
        }
        });
    }
    function condoDelete()
    {
        if($("#per_del").val()==0)
        {
            sin_permiso();
            return false;
        }
        if($('#dlg_idpre').val()==0)
        {
            mostraralertas("Primero Guardar Predio...");
            return false;
        }
        Id=$('#table_condos').jqGrid ('getGridParam', 'selrow');
        MensajeDialogLoadAjax('s3', '.:: Eliminando ...');
        $.ajax({
            url: 'condominios_predios/destroy',
            type: 'post',
            data: {_method: 'delete', _token:$("#btn_s3_delcondos").data('token'),id:Id},
            success: function(r) 
            {   
                jQuery("#table_condos").jqGrid('setGridParam', {url: 'gridcondos/'+$('#dlg_idpre').val()}).trigger('reloadGrid');
                jQuery("#table_predios").jqGrid('setGridParam', {url: 'gridpredio?tpre=1&mnza='+$("#selmnza").val()+'&ctr=0&an='+$("#selantra").val()}).trigger('reloadGrid');
                MensajeAlerta("Se Eliminó Correctamente","Su Registro Fue eliminado Correctamente...",4000)
                MensajeDialogLoadAjaxFinish('s3');
            },
            error: function(data) {
                mostraralertas("hubo un error, Comunicar al Administrador");
                MensajeDialogLoadAjaxFinish('s3');
                console.log('error');
                console.log(data);
            }
        });
    }
    
    function creardlginst()
    {
     $("#dlg_reg_inst").dialog({
        autoOpen: false, modal: true, width: 700, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  REGISTRO DE INSTALACIONES :.</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                id:"btninstsave",
                "class": "btn btn-primary bg-color-green",
                click: function () {instSave();}
            },
            {
                html: "<i class='fa fa-save'></i>&nbsp; Modificar",
                "class": "btn btn-primary bg-color-blue",
                id:"btninstmod",
                click: function () {instUpdate();}
            },
            {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-primary bg-color-red",
                click: function () {$(this).dialog("close");}
            }],
        }).dialog('open');
    }
    function clicknewinst()
    {
        $("#dlg_idinst").val(0);
       if($('#dlg_idpre').val()==0)
        {
            mostraralertas("Primero Guardar Predio...");
            return false;
        }
        $("#hidden_rinst_inp_des").val(0)
        autocompletar=0;
        auto_input("rinst_inp_des","autocompletar_insta",2);
        $( "#rinst_inp_largo,#rinst_inp_ancho,#rinst_inp_alto,#rinst_inp_canti" ).prop( "disabled", true );
        autocompletar=1;
        $("#rinst_inp_des,#rinst_inp_des_cod,#rinst_inp_undmed,#rinst_inp_anio,#rinst_inp_largo,#rinst_inp_ancho,#rinst_inp_alto,#rinst_inp_canti").val("")
        $("#rinst_inp_mat").val($("#rinst_inp_mat option:first").val());
        $("#rinst_inp_econserv").val($("#rinst_inp_econserv option:first").val());
        $("#rinst_inp_econstr").val($("#rinst_inp_econstr option:first").val());
        $("#rinst_inp_clasi").val($("#rinst_inp_clasi option:first").val());
        creardlginst();
        $("#btninstsave").show();
        $("#btninstmod").hide();
        callchangeoption("rinst_inp_mat",0);
        callchangeoption("rinst_inp_econserv",0);
        callchangeoption("rinst_inp_econstr",0);
        callchangeoption("rinst_inp_clasi",0);
        
    }
    function clickmodinst()
    {
        if($('#dlg_idpre').val()==0)
        {
            mostraralertas("Primero Guardar Predio...");
            return false;
        }
        Id=$('#table_instal').jqGrid ('getGridParam', 'selrow');
        $('#dlg_idinst').val(Id);
        creardlginst();
        $("#btninstsave").hide();
        $("#btninstmod").show();
        MensajeDialogLoadAjax('dlg_reg_inst', '.:: Cargando ...');
        $.ajax({url: 'instalaciones_predios/'+Id,
        type: 'GET',
        success: function(r) 
        {
            $("#rinst_inp_des_cod").val(r[0].cod_instal);
            $("#rinst_inp_des").val(r[0].descrip_instal);
            $("#hidden_rinst_inp_des").val(r[0].id_instal);
            $("#rinst_inp_undmed").val(r[0].unid_medida);
            if(r[0].unid_medida=="UND")
            {
                $( "#rinst_inp_largo,#rinst_inp_ancho,#rinst_inp_alto" ).prop( "disabled", true );
                $( "#rinst_inp_canti" ).prop( "disabled", false );
                $( "#rinst_inp_canti" ).val(r[0].pro_tot);
            }
            if(r[0].unid_medida=="M2" || r[0].unid_medida=="ML")
            {
                $( "#rinst_inp_largo,#rinst_inp_ancho" ).prop( "disabled", false );
                $( "#rinst_inp_canti,#rinst_inp_alto" ).prop( "disabled", true );
                $( "#rinst_inp_canti" ).val(0);
            }
            $("#rinst_inp_anio").val(r[0].anio);
            $("#rinst_inp_mat").val(r[0].mep);
            $("#rinst_inp_econserv").val(r[0].ecs);
            $("#rinst_inp_econstr").val(parseInt(r[0].ecc));
            $("#rinst_inp_largo").val(r[0].dim_lar);
            $("#rinst_inp_ancho").val(r[0].dim_anch);
            $("#rinst_inp_alto").val(r[0].dim_alt);
            $("#rinst_inp_clasi").val(r[0].id_cla)
            callchangeoption("rinst_inp_mat",0);
            callchangeoption("rinst_inp_econserv",0);
            callchangeoption("rinst_inp_econstr",0);
            callchangeoption("rinst_inp_clasi",0);
            MensajeDialogLoadAjaxFinish('dlg_reg_inst');
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            MensajeDialogLoadAjaxFinish('dlg_reg_inst');
            $("#dlg_reg_inst").dialog('close');
        }
        });
        
        
    }
    function instSave()
    {
        if($("#hidden_rinst_inp_des").val()==0){mostraralertasconfoco("seleccionar Instalación","#rinst_inp_des"); return false}
        if($("#rinst_inp_anio").val()==""){mostraralertasconfoco("Ingresar Año de Construcción","#rinst_inp_anio"); return false}
        if($("#rinst_inp_largo").val()==""){mostraralertasconfoco("Ingresar largo","#rinst_inp_largo"); return false}
        if($("#rinst_inp_ancho").val()==""){mostraralertasconfoco("Ingresar Ancho","#rinst_inp_ancho"); return false}
        if($("#rinst_inp_alto").val()==""){mostraralertasconfoco("Ingresar Alto","#rinst_inp_alto"); return false}
        MensajeDialogLoadAjax('dlg_reg_inst', '.:: Guardando ...');
        Id_pre=$('#dlg_idpre').val();
        $.ajax({url: 'instalaciones_predios/create',
        type: 'GET',
        data:{inst:$("#hidden_rinst_inp_des").val(),anio:$("#rinst_inp_anio").val(),largo:$("#rinst_inp_largo").val(),
            ancho:$("#rinst_inp_ancho").val(),alto:$("#rinst_inp_alto").val(),mep:$("#rinst_inp_mat").val(),
            ecs:$("#rinst_inp_econserv").val(),ecc:$("#rinst_inp_econstr").val(),id_pre:Id_pre,cla:$("#rinst_inp_clasi").val(),
            cant:$("#rinst_inp_canti").val()},
        success: function(r) 
        {
            
            MensajeExito("Insertó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            jQuery("#table_instal").jqGrid('setGridParam', {url: 'gridinsta/'+Id_pre}).trigger('reloadGrid');
            jQuery("#table_predios").jqGrid('setGridParam', {url: 'gridpredio?tpre=2&mnza=0&ctr=0&an='+$("#selantra").val()}).trigger('reloadGrid');
            MensajeDialogLoadAjaxFinish('dlg_reg_inst');
            $("#dlg_reg_inst").dialog('close');
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dlg_reg_inst');
            console.log('error');
            console.log(data);
        }
        });
    }
    function instUpdate()
    {
        if($("#per_edit").val()==0)
        {
            sin_permiso();
            return false;
        }
        if($("#hidden_rinst_inp_des").val()==0){mostraralertasconfoco("seleccionar Instalación","#rinst_inp_des"); return false}
        if($("#rinst_inp_anio").val()==""){mostraralertasconfoco("Ingresar Año de Construcción","#rinst_inp_anio"); return false}
        if($("#rinst_inp_largo").val()==""){mostraralertasconfoco("Ingresar largo","#rinst_inp_largo"); return false}
        if($("#rinst_inp_ancho").val()==""){mostraralertasconfoco("Ingresar Ancho","#rinst_inp_ancho"); return false}
        if($("#rinst_inp_alto").val()==""){mostraralertasconfoco("Ingresar Alto","#rinst_inp_alto"); return false}
        MensajeDialogLoadAjax('dlg_reg_inst', '.:: Modificando ...');
        $.ajax({url: 'instalaciones_predios/'+$('#dlg_idinst').val()+'/edit',
        type: 'GET',
        data:{inst:$("#hidden_rinst_inp_des").val(),anio:$("#rinst_inp_anio").val(),largo:$("#rinst_inp_largo").val(),
            ancho:$("#rinst_inp_ancho").val(),alto:$("#rinst_inp_alto").val(),mep:$("#rinst_inp_mat").val(),
            ecs:$("#rinst_inp_econserv").val(),ecc:$("#rinst_inp_econstr").val(),cla:$("#rinst_inp_clasi").val(),
            cant:$("#rinst_inp_canti").val()},
        success: function(r) 
        {
            MensajeExito("Se Modificó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            jQuery("#table_instal").jqGrid('setGridParam', {url: 'gridinsta/'+$('#dlg_idpre').val()}).trigger('reloadGrid');
            jQuery("#table_predios").jqGrid('setGridParam', {url: 'gridpredio?tpre=2&mnza=0&ctr=0&an='+$("#selantra").val()}).trigger('reloadGrid');
            MensajeDialogLoadAjaxFinish('dlg_reg_inst');
            $("#dlg_reg_condo").dialog('close');
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dlg_reg_inst');
            console.log('error');
            console.log(data);
        }
        });
    }
    function instDelete()
    {
        if($("#per_del").val()==0)
        {
            sin_permiso();
            return false;
        }
        if($('#dlg_idpre').val()==0)
        {
            mostraralertas("Primero Guardar Predio...");
            return false;
        }
        Id=$('#table_instal').jqGrid ('getGridParam', 'selrow');
        MensajeDialogLoadAjax('s2', '.:: Eliminando ...');
        $.ajax({
            url: 'instalaciones_predios/destroy',
            type: 'post',
            data: {_method: 'delete', _token:$("#btn_s2_delinst").data('token'),id:Id},
            success: function(r) 
            {
                jQuery("#table_instal").jqGrid('setGridParam', {url: 'gridinsta/'+$('#dlg_idpre').val()}).trigger('reloadGrid');
                jQuery("#table_predios").jqGrid('setGridParam', {url: 'gridpredio?tpre=2&mnza=0&ctr=0&an='+$("#selantra").val()}).trigger('reloadGrid');
                MensajeAlerta("Se Eliminó Correctamente","Su Registro Fue eliminado Correctamente...",4000)
                MensajeDialogLoadAjaxFinish('s1');
            },
            error: function(data) {
                mostraralertas("hubo un error, Comunicar al Administrador");
                MensajeDialogLoadAjaxFinish('s1');
                console.log('error');
                console.log(data);
            }
        });
    }
    
    function callchangeoption(input,tip)
    {
        
        if(tip==1)
        {
            $("#dlg_inp_aranc").val($("#"+input+" option:selected").attr("aran"))
            validarvalter();
            
        }
        else
        {
            $("#"+input+"_des").val($("#"+input+" option:selected").attr("descri"));
        }
    }
    
    function validarvalter()
    {
        if($("#dlg_inp_aranc").val()==""||$("#dlg_inp_areter").val()==""){$("#dlg_inp_valterr").val(0);return false;}
        $("#dlg_inp_valterr").val( formato_numero($("#dlg_inp_aranc").val()*$("#dlg_inp_areter").val(),2,".",","));
    }
    autocompletar=0;
    function auto_input(textbox,url,extra){
        if(autocompletar==0)
        {
            $.ajax({
                   type: 'GET',
                   url: url,
                   data:{an:$("#selantra").val()},
                   success: function(data){               
                        var $local_todo=data;          
                         $("#"+textbox).autocomplete({
                              source: $local_todo,
                              focus: function(event, ui) {
                                     $("#"+textbox).val(ui.item.label);                             
                                     return false;
                              },
                              select: function(event, ui) {
                                     $("#"+textbox).val(ui.item.label);
                                     $("#hidden_"+textbox).val(ui.item.value);
                                     if(extra!=0)
                                     {
                                        $("#"+textbox+"_cod").val(ui.item.codi);
                                     }
                                     if(extra==2)
                                     {
                                        $( "#rinst_inp_largo,#rinst_inp_ancho,#rinst_inp_alto,#rinst_inp_canti" ).val(0);
                                        $("#rinst_inp_undmed").val(ui.item.und);
                                        if(ui.item.und=="UND")
                                        {
                                            $( "#rinst_inp_largo,#rinst_inp_ancho,#rinst_inp_alto" ).prop( "disabled", true );
                                            $( "#rinst_inp_canti" ).prop( "disabled", false );
                                        }
                                        if(ui.item.und=="M2" || ui.item.und=="ML")
                                        {
                                            $( "#rinst_inp_largo,#rinst_inp_ancho" ).prop( "disabled", false );
                                            $( "#rinst_inp_canti,#rinst_inp_alto" ).prop( "disabled", true );
                                        }
                                         if(ui.item.und=="M3")
                                        {
                                            $( "#rinst_inp_largo,#rinst_inp_ancho,#rinst_inp_alto" ).prop( "disabled", false );
                                            $( "#rinst_inp_canti" ).prop( "disabled", true );
                                        }
                                     }
                                      return false;
                              }   
                          });             
                    }
            });
        }
}
    function auto_select(sel_input,url,consulta,valor)
    {
        MensajeDialogLoadAjax(sel_input, '.:: Cargando ...');
        $("#"+sel_input).html("");
        $.ajax({
            type: 'GET',
            url: url,
            data:{an:$("#selantra").val(),val:consulta},
            success: function(data){               
                for (var i=0; i < data.length; i++)
                {
                    $("#"+sel_input).append($('<option>',{value:data[i].id_gpo_cat,text: data[i].categoria,aran:data[i].arancel_rustico}));
                } 
                MensajeDialogLoadAjaxFinish(sel_input);
                $("#"+sel_input).val(valor);

             }
     });
}

function imppu()
{
    Id=$('#table_predios').jqGrid ('getGridParam', 'selrow');
    if(Id==null)
    {
        mostraralertasconfoco("seleccione Predio","")
        return false;
    }
    window.open('pre_rep/PR/'+Id+'/0/0');
}