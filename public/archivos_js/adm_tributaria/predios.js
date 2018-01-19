function fn_bus_contrib_pred()
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
function fn_bus_contrib_list_pred(per)
{
    $("#dlg_contri_hidden").val(per);
    $("#dlg_dni").val($('#table_contrib').jqGrid('getCell',per,'nro_doc'));
    $("#dlg_contri").val($('#table_contrib').jqGrid('getCell',per,'contribuyente'));
    $("#dlg_bus_contr").dialog("close");
    
}
function callpredtab()
    {
        $("#selmnza").html('');
        MensajeDialogLoadAjax('dvselmnza', '.:: CARGANDO ...');
        $.ajax({url: 'selmzna?sec='+$("#selsec").val(),
        type: 'GET',
        success: function(r) 
        {
            $(r).each(function(i, v){ // indice, valor
                $("#selmnza").append('<option value="' + v.id_mzna + '">' + v.codi_mzna + '</option>');
            })
            callfilltab();        
            MensajeDialogLoadAjaxFinish('dvselmnza');
            

        },
        error: function(data) {
            console.log('error');
            console.log(data);
        }
        });
//
    }
    function callfilltab()
    {
        callfilltab_arbitrios();
        jQuery("#table_predios").jqGrid('setGridParam', {url: 'gridpredio?tpre=1&mnza='+$("#selmnza").val()+'&ctr=0&an='+$("#selantra").val()}).trigger('reloadGrid');
          
    }
    function llenarlote()
    {
        $("#dlg_lot").html('');
            MensajeDialogLoadAjax('dlg_lot', '.:: CARGANDO ...');
            $.ajax({url: 'sellot?man='+$("#selmnza").val(),
            type: 'GET',
            success: function(r) 
            {
                $("#dlg_lot").append('<option value="0">--Seleccione--</option>');
                $(r).each(function(i, v){ // indice, valor
                    $("#dlg_lot").append('<option value="' + v.id_lote + '">' + v.codi_lote + '</option>');
                })
                callfilltab();        
                MensajeDialogLoadAjaxFinish('dlg_lot');
            },
            error: function(data) {
                console.log('error');
                console.log(data);
            }
            });
    }
    function limpiarpred(tip)
    {
        
        auto_input("dlg_inp_usopre","autocompletar_tipo_uso",1);
        $("#dlg_img_view").html('<center><img src="img/recursos/Home-icon.png" height="100%" width="65%"/></center>');
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
            $( "#dlg_dni, #dlg_lot,#dlg_contri" ).prop( "disabled", false );
            $('#dlg_lot,#dlg_inp_luz,#dlg_inp_agua').val("");
            $("#btnsavepre").show();
            $("#btnmodpre").hide();
            $("#dlg_sel_condpre,#hidden_dlg_inp_usopre,#dlg_sel_estcon,#dlg_sel_tippre,#dlg_sel_foradq").val(1);
            $("#dlg_inp_usopre_cod").val("010101");
            $("#dlg_inp_usopre").val("CASA HABITACIÓN");
            $("input[name=dlg_rd_lcons][value='0']").prop("checked",true);
            $("input[name=dlg_rd_confobr][value='0']").prop("checked",true);
            $("input[name=dlg_rd_defra][value='0']").prop("checked",true);
            $('#dlg_inp_condos').val(100);
            $("#dlg_inp_condos").prop('disabled', true);
            
        }
        if(tip==2)
        {
            $("#hidden_dlg_inp_usopre").val(0);
            $("#dlg_inp_usopre_cod,#dlg_inp_usopre,#dlg_sel_condpre").val("");
            $( "#dlg_dni, #dlg_lot,#dlg_contri" ).prop( "disabled", true );
            $("#btnsavepre").hide();
            $("#btnmodpre").show();
            $('#dlg_inp_condos').val("");
        }
        
        $("#dlg_sec").val($("#selsec option:selected").text());
        $("#dlg_mzna").val($("#selmnza option:selected").text());
        $('#dlg_dni,#dlg_contri').val("");
        $("#dlg_inp_areter,#dlg_inp_arecomter").val("");
        $("#tipeado").text("");
        $('#dlg_inp_nvia_des,#dlg_inp_nvia,#dlg_inp_n,#dlg_inp_mz,#dlg_inp_lt,#dlg_inp_zn').val("");
        $('#dlg_inp_supMzna,#dlg_inp_gpoZonal').val("");
        $('#dlg_inp_secc,#dlg_inp_piso,#dlg_inp_dpto,#dlg_inp_tdastand,#dlg_inp_refe, #dlg_inp_fech').val("");
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
    function traerfoto()
    {
        MensajeDialogLoadAjax('dlg_img_view', '.:: Cargando ...');
        $.ajax({url: 'traefoto_lote/'+$("#dlg_sec").val()+'/'+$("#dlg_mzna").val()+'/'+$("#dlg_lot option:selected").text(),
        type: 'GET',
        success: function(r) 
        {
            if(r!=0)
            {
                $("#dlg_img_view").html('<center><img src="data:image/png;base64,'+r+'" height="100%" width="90%"/></center>');
            }
            else
            {
                $("#dlg_img_view").html('<center><img src="img/recursos/Home-icon.png" height="100%" width="65%"/></center>');
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
    function viewlong()
    {
        $("#dlg_img_view_big").html("");
        $("#dlg_view_foto").dialog({
        autoOpen: false, modal: true, width: 1000, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  Foto del Predio :.</h4></div>",
        }).dialog('open');
        $("#dlg_img_view_big").html($("#dlg_img_view").html());
    }
    function validarcampos()
    {
        if($('#dlg_inp_condos').val()==""){$('#dlg_inp_condos').val('0');}
        if($('#dlg_inp_piso').val()==""){$('#dlg_inp_piso').val('0');}
        if($('#dlg_inp_zn').val()==""){$('#dlg_inp_zn').val('-');}
        if($('#dlg_inp_secc').val()==""){$('#dlg_inp_secc').val('-');}
        if($('#dlg_inp_refe').val()==""){$('#dlg_inp_refe').val('-');}
        if($('#dlg_inp_n').val()==""){$('#dlg_inp_n').val('-');}
        if($('#dlg_inp_tdastand').val()==""){$('#dlg_inp_tdastand').val('-');}
        if($('#dlg_inp_dpto').val()==""){$('#dlg_inp_dpto').val('-');}
        if($('#dlg_inp_luz').val()==""){$('#dlg_inp_luz').val('-');}
        if($('#dlg_inp_agua').val()==""){$('#dlg_inp_agua').val('-');}
        if($('#dlg_inp_areter').val()==""){$('#dlg_inp_areter').val(0);}
        if($('#dlg_inp_arecomter').val()==""){$('#dlg_inp_arecomter').val(0);}
        if($('#dlg_inp_aranc').val()==""){$('#dlg_inp_aranc').val(0);}
    }
    function clicknewgrid()
    {
        llenarlote();
        jQuery("#table_pisos").jqGrid('setGridParam', {url: 'gridpisos/0'}).trigger('reloadGrid');
        jQuery("#table_condos").jqGrid('setGridParam', {url: 'gridcondos/0'}).trigger('reloadGrid');
        jQuery("#table_instal").jqGrid('setGridParam', {url: 'gridinsta/0'}).trigger('reloadGrid');
        limpiarpred(1);
        $("#dlg_contri_hidden").val(0);
        auto_select("dlg_inp_nvia","sel_viaby_sec",0);
        $("#dlg_reg_dj").dialog('open');
    }
    function clickmodgrid()
    {
       limpiarpred(2);
       
       Id=$('#table_predios').jqGrid ('getGridParam', 'selrow');
       if(Id)
       {
        $("#dlg_reg_dj").dialog('open');
        MensajeDialogLoadAjax('dlg_reg_dj', '.:: Cargando ...');
        $("#dlg_idpre").val(Id);
        $.ajax({url: 'predios_urbanos/'+Id,
        type: 'GET',
        success: function(r) 
        {
            if(r[0].foto!=0)
            {
                $("#dlg_img_view").html('<center><img src="data:image/png;base64,'+r[0].foto+'" height="100%" width="90%"/></center>');
            }
            $("#dlg_lot").html("");
            $("#dlg_lot").append('<option value="' + r[0].id_lote + '">' + r[0].lote+ '</option>');
                $("#dlg_contri_hidden").val(r[0].id_contrib);
                $("#dlg_dni").val(r[0].nro_doc);
                $("#dlg_contri").val(r[0].contribuyente);
                $("#dlg_sel_condpre").val(r[0].id_cond_prop);
                $("#dlg_inp_dpto").val(r[0].dpto);
                $("#dlg_inp_mz").val(r[0].mzna_dist);
                $("#dlg_inp_lt").val(r[0].lote_dist);
                $("#dlg_inp_n").val(r[0].nro_mun);
                $("#dlg_inp_zn").val(r[0].zona);
                $("#dlg_inp_secc").val(r[0].secc);
                $("#dlg_inp_piso").val(r[0].piso);
                $("#dlg_inp_tdastand").val(r[0].nro_int);
                $("#dlg_inp_condos").val(r[0].nro_condominios);
                validacond();
                $("#dlg_inp_refe").val(r[0].referencia);
                $("#dlg_sel_estcon").val(r[0].id_est_const);
                $("#dlg_sel_tippre").val(r[0].id_tip_pred);
                $("#hidden_dlg_inp_usopre").val(r[0].id_uso_predio);
                $("#dlg_inp_usopre_cod").val(r[0].codi_uso);
                $("#dlg_inp_usopre").val(r[0].desc_uso);
                $("#dlg_sel_foradq").val(r[0].id_form_adq);
                $("#dlg_inp_fech").val(r[0].fech_adquis);
                $("#dlg_inp_luz").val(r[0].luz_nro_sum);
                $("#dlg_inp_agua").val(r[0].agua_nro_sum);
                $("input[name=dlg_rd_lcons][value='"+r[0].licen_const+"']").prop("checked",true);
                $("input[name=dlg_rd_confobr][value='"+r[0].conform_obra+"']").prop("checked",true);
                $("input[name=dlg_rd_defra][value='"+r[0].declar_fabrica+"']").prop("checked",true);
                auto_select("dlg_inp_nvia","sel_viaby_sec",r[0].id_via);
                $("#dlg_inp_aranc").val(r[0].arancel);
                $("#dlg_inp_valterr").val(formato_numero(r[0].val_ter,3,".",","));
                $("#dlg_inp_areter").val(r[0].are_terr);
                $("#dlg_inp_arecomter").val(r[0].are_com_terr);
                $("#dlg_inp_supMzna").val(r[0].sup_mzna);
                $("#dlg_inp_gpoZonal").val(r[0].gpo_zonal);
                $("#tipeado").text(" PREDIO TIPEADO POR : "+r[0].ape_nom);
                MensajeDialogLoadAjaxFinish('dlg_reg_dj');
                jQuery("#table_pisos").jqGrid('setGridParam', {url: 'gridpisos/'+Id}).trigger('reloadGrid');
                jQuery("#table_condos").jqGrid('setGridParam', {url: 'gridcondos/'+Id}).trigger('reloadGrid');
                jQuery("#table_instal").jqGrid('setGridParam', {url: 'gridinsta/'+Id}).trigger('reloadGrid');
                llenararbitrios();
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            MensajeDialogLoadAjaxFinish('dlg_reg_dj');
        }
        }); 
       }
       else
       {
           mostraralertasconfoco("No Hay Predio Seleccionado","#selsec");
       }

    }
    
    
    
function fn_confirmar_predio()
{
    if(parseInt($('#dlg_lot').val())<1||$('#dlg_lot').val()==""){mostraralertasconfoco('Ingresar un número de lote...',"#dlg_lot");return false}
    if($('#dlg_inp_aranc').val()==""||$('#dlg_inp_aranc').val()==0){mostraralertasconfoco('No existe Arancel, comunicar al administrador...',"#dlg_inp_aranc");return false}
    if($('#dlg_contri_hidden').val()==0){mostraralertasconfoco('Ingresar contribuyente...',"#dlg_dni");return false}
    if($("#dlg_inp_nvia").val()==null){mostraralertasconfoco('La Vía es incorrecta, vuelva a ingresar una vía válida...',"#dlg_inp_nvia");return false}
    if($('#dlg_sel_condpre').val()==null){mostraralertasconfoco('Ingresar condicion predio...',"#dlg_sel_condpre");return false}
    if($('#dlg_inp_areter').val()==null||$('#dlg_inp_areter').val()==""){mostraralertasconfoco('Ingresar area del terreno...',"#dlg_inp_areter");return false}
    if($('#dlg_inp_condos').val()>100){mostraralertasconfoco('Porcentaje de Posesión no puede ser Mayor al 100%...',"#dlg_inp_condos");return false}
    MensajeDialogLoadAjax('dlg_reg_dj', '.:: Guardando ...');
    $.ajax({url: 'validar_predio',
    type: 'GET',
    data:{lote:$('#dlg_lot').val(),an:$("#selantra").val()},
    success: function(r) 
    {
        MensajeDialogLoadAjaxFinish('dlg_reg_dj');
        if(r==0)
        {
            texto="Está por generar Predio, Esta seguro que desea continuar?"
        }
        else
        {
            texto="El Predio, ya fue registrado por el/los Contribuyente/s <div style='height=200px;overflow-y: scroll;height: 150px !important;'>"+r+"</div><br>Desea Continuar?"
        }
        $.SmartMessageBox({
            title : "<i class='glyphicon glyphicon-alert' style='color: yellow; margin-right: 20px; font-size: 1.5em;'></i> Confirmación Final!",
            content : texto,
            buttons : '[Cancelar][Aceptar]'
    }, function(ButtonPressed) {
            if (ButtonPressed === "Aceptar") {

                    dlgSave();
            }
            if (ButtonPressed === "Cancelar") {
                    $.smallBox({
                            title : "No se Guardo",
                            content : "<i class='fa fa-clock-o'></i> <i>Puede Corregir...</i>",
                            color : "#C46A69",
                            iconSmall : "fa fa-times fa-2x fadeInRight animated",
                            timeout : 3000
                    });
            }

    });
    },
    error: function(data) {
        mostraralertas("hubo un error, Comunicar al Administrador");
        MensajeDialogLoadAjaxFinish('dlg_reg_dj');
        console.log('error');
        console.log(data);
    }
    });    
    
}
    function dlgSave()
    {
        validarcampos();
        save_otros();
        MensajeDialogLoadAjax('dlg_reg_dj', '.:: Guardando ...');
        $.ajax({url: 'predios_urbanos/create',
        type: 'GET',
        data:{condpre:$('#dlg_sel_condpre').val(),condos:$('#dlg_inp_condos').val(),cvia:$("#dlg_inp_nvia").val(),
        n:$("#dlg_inp_n").val(),mz:$("#dlg_inp_mz").val(),lt:$('#dlg_inp_lt').val(),zn:$('#dlg_inp_zn').val(),
        secc:$('#dlg_inp_secc').val(),piso:$('#dlg_inp_piso').val(),dpto:$("#dlg_inp_dpto").val(),int:$("#dlg_inp_tdastand").val(),
        ref:$('#dlg_inp_refe').val(),contrib:$('#dlg_contri_hidden').val(),lote:$('#dlg_lot').val(),
        ecc:$("#dlg_sel_estcon").val(),tpre:$("#dlg_sel_tippre").val(),tipuso:$("#hidden_dlg_inp_usopre").val(),
        ifor:$("#dlg_sel_foradq").val(),ffor:$("#dlg_inp_fech").val(),
        luz:$("#dlg_inp_luz").val(),agua:$("#dlg_inp_agua").val(),liccon:$('input:radio[name=dlg_rd_lcons]:checked').val(),
        confobr:$('input:radio[name=dlg_rd_confobr]:checked').val(),defra:$('input:radio[name=dlg_rd_defra]:checked').val(),
        areterr:$("#dlg_inp_areter").val(),arecomter:$("#dlg_inp_arecomter").val(),aranc:$("#dlg_inp_aranc").val(),
        an:$("#selantra").val(),id_mzna:$("#selmnza").val(),
        supmzna:$("#dlg_inp_supMzna").val(),gpozonal:$("#dlg_inp_gpoZonal").val(),
        otros:nuevo_otros},//oooootrotoro
        
        success: function(r) 
        {
            $('#dlg_idpre').val(r);
            MensajeExito("Insertó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            jQuery("#table_predios").jqGrid('setGridParam', {url: 'gridpredio?tpre=1&mnza='+$("#selmnza").val()+'&ctr=0&an='+$("#selantra").val()}).trigger('reloadGrid');
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
        if($('#dlg_inp_aranc').val()==""||$('#dlg_inp_aranc').val()==0){mostraralertasconfoco('No existe Arancel, comunicar al administrador...',"#dlg_inp_aranc");return false}
        if($("#dlg_inp_nvia").val()==null){mostraralertasconfoco('La Vía es incorrecta, vuelva a ingresar una vía válida...',"#dlg_inp_nvia_des");return false}
        if($('#dlg_inp_condos').val()>100){mostraralertasconfoco('Porcentaje de Posesión no puede ser Mayor al 100%...',"#dlg_inp_condos");return false}
        validarcampos();
        save_otros();
        
        MensajeDialogLoadAjax('dlg_reg_dj', '.:: CARGANDO ...');
        $.ajax({url: 'predios_urbanos/'+$('#dlg_idpre').val()+'/edit',
        type: 'GET',
        data:{condpre:$('#dlg_sel_condpre').val(),condos:$('#dlg_inp_condos').val(),cvia:$("#dlg_inp_nvia").val(),
        n:$("#dlg_inp_n").val(),mz:$("#dlg_inp_mz").val(),lt:$('#dlg_inp_lt').val(),zn:$('#dlg_inp_zn').val(),
        secc:$('#dlg_inp_secc').val(),piso:$('#dlg_inp_piso').val(),dpto:$("#dlg_inp_dpto").val(),int:$("#dlg_inp_tdastand").val(),
        ref:$('#dlg_inp_refe').val(),
        ecc:$("#dlg_sel_estcon").val(),tpre:$("#dlg_sel_tippre").val(),tipuso:$("#hidden_dlg_inp_usopre").val(),
        ifor:$("#dlg_sel_foradq").val(),ffor:$("#dlg_inp_fech").val(),
        luz:$("#dlg_inp_luz").val(),agua:$("#dlg_inp_agua").val(),liccon:$('input:radio[name=dlg_rd_lcons]:checked').val(),
        confobr:$('input:radio[name=dlg_rd_confobr]:checked').val(),defra:$('input:radio[name=dlg_rd_defra]:checked').val(),
        areterr:$("#dlg_inp_areter").val(),arecomter:$("#dlg_inp_arecomter").val(),aranc:$("#dlg_inp_aranc").val(),
        supmzna:$("#dlg_inp_supMzna").val(),gpozonal:$("#dlg_inp_gpoZonal").val(),otros:nuevo_otros},
        success: function(r) 
        {
            MensajeExito("Modificó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            jQuery("#table_predios").jqGrid('setGridParam', {url: 'gridpredio?tpre=1&mnza='+$("#selmnza").val()+'&ctr=0&an='+$("#selantra").val()}).trigger('reloadGrid');
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
function fn_confirmar_borrar_predio()
{
    Id=$('#table_predios').jqGrid ('getGridParam', 'selrow');
    if(Id)
    {
        $.SmartMessageBox({
            title : "<i class='glyphicon glyphicon-alert' style='color: yellow; margin-right: 20px; font-size: 1.5em;'></i> Confirmación Final!",
            content : 'Esta Por ELiminar El Predio.<br>Si Elimina EL predio no se Podra Recuperar la información, Esta Seguro?',
            buttons : '[Cancelar][Aceptar]'
        }, function(ButtonPressed) {
                if (ButtonPressed === "Aceptar") {

                        dlgDelete();
                }
                if (ButtonPressed === "Cancelar") {
                        $.smallBox({
                                title : "No se Eliminó",
                                content : "<i class='fa fa-clock-o'></i> <i>Puede Corregir...</i>",
                                color : "#C46A69",
                                iconSmall : "fa fa-times fa-2x fadeInRight animated",
                                timeout : 3000
                        });
                }

        });
    }
    else
    {
        mostraralertasconfoco("No Hay Predio Seleccionado","#selsec");
    }
}
    function dlgDelete()
    {
        if($("#per_del").val()==0)
        {
            sin_permiso();
            return false;
        }
        Id=$('#table_predios').jqGrid ('getGridParam', 'selrow');
        MensajeDialogLoadAjax('table_predios', '.:: Eliminando ...');
        $.ajax({
            url: 'predios_urbanos/destroy',
            type: 'post',
            data: {_method: 'delete', _token:$("#btn_s1_delpiso").data('token'),id:Id},
            success: function(r) 
            {
                jQuery("#table_predios").jqGrid('setGridParam', {url: 'gridpredio?tpre=1&mnza='+$("#selmnza").val()+'&ctr=0&an='+$("#selantra").val()}).trigger('reloadGrid');
                MensajeAlerta("Se Eliminó Correctamente","Su Registro Fue eliminado Correctamente...",4000)
                MensajeDialogLoadAjaxFinish('table_predios');
            },
            error: function(data) {
                mostraralertas("hubo un error, Comunicar al Administrador");
                MensajeDialogLoadAjaxFinish('table_predios');
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
        if($("#rpiso_inp_fech").val()==""){mostraralertasconfoco("Ingresar Año de Construccion del Piso","#rpiso_inp_fech"); return false}
        if($("#rpiso_inp_fech").val()<1800){mostraralertasconfoco("Ingresar Año de Construccion Valido de 4 cifras","#rpiso_inp_fech"); return false}
        if($("#rpiso_inp_clasi").val()==""){mostraralertasconfoco("Ingresar Estado de Conservación","#rpiso_inp_econserv"); return false}
        if($("#rpiso_inp_mat").val()==""){mostraralertasconfoco("Ingresar Estado de Conservación","#rpiso_inp_econserv"); return false}
        if($("#rpiso_inp_econserv").val()==""){mostraralertasconfoco("Ingresar Estado de Conservación","#rpiso_inp_econserv"); return false}
        if($("#rpiso_inp_econstr").val()==""){mostraralertasconfoco("Ingresar Estado de Construcción","#rpiso_inp_econstr"); return false}
        if($("#rpiso_inp_estruc").val()==""){mostraralertasconfoco("Ingresar Estructuras","#rpiso_inp_estruc"); return false}
        if($("#rpiso_inp_estruc").val().length<7){mostraralertasconfoco("Cadena de Estructura incompleta, Ingrese 7 caracteres","#rpiso_inp_estruc"); return false}
        if($("#rpiso_inp_aconst").val()==""){mostraralertasconfoco("Ingresar Area Construida","#rpiso_inp_aconst"); return false}
        if($("#rpiso_inp_acomun").val()==""){$("#rpiso_inp_acomun").val(0);}
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
            jQuery("#table_predios").jqGrid('setGridParam', {url: 'gridpredio?tpre=1&mnza='+$("#selmnza").val()+'&ctr=0&an='+$("#selantra").val()}).trigger('reloadGrid');
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
        if($("#rpiso_inp_fech").val()==""){mostraralertasconfoco("Ingresar Año de Construccion del Piso","#rpiso_inp_fech"); return false}
        if($("#rpiso_inp_fech").val()<1800){mostraralertasconfoco("Ingresar Año de Construccion Valido de 4 cifras","#rpiso_inp_fech"); return false}
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
            jQuery("#table_predios").jqGrid('setGridParam', {url: 'gridpredio?tpre=1&mnza='+$("#selmnza").val()+'&ctr=0&an='+$("#selantra").val()}).trigger('reloadGrid');
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
                jQuery("#table_predios").jqGrid('setGridParam', {url: 'gridpredio?tpre=1&mnza='+$("#selmnza").val()+'&ctr=0&an='+$("#selantra").val()}).trigger('reloadGrid');
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
            $("#rcondo_inp_rsoc").val(r[0].ape_pat+" "+r[0].ape_mat+" "+r[0].nombres);
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
            jQuery("#table_predios").jqGrid('setGridParam', {url: 'gridpredio?tpre=1&mnza='+$("#selmnza").val()+'&ctr=0&an='+$("#selantra").val()}).trigger('reloadGrid');
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
            jQuery("#table_predios").jqGrid('setGridParam', {url: 'gridpredio?tpre=1&mnza='+$("#selmnza").val()+'&ctr=0&an='+$("#selantra").val()}).trigger('reloadGrid');
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
        $("#rinst_inp_des,#rinst_inp_des_cod,#rinst_inp_undmed,#rinst_inp_anio,#rinst_inp_largo,#rinst_inp_ancho,#rinst_inp_alto,#rinst_inp_canti,#glosa").val("")
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
            $("#rinst_inp_clasi").val(r[0].id_cla);
            $("#glosa").val(r[0].glosa);
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
            cant:$("#rinst_inp_canti").val(),glosa:$("#glosa").val()},
        success: function(r) 
        {
            
            MensajeExito("Insertó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            jQuery("#table_instal").jqGrid('setGridParam', {url: 'gridinsta/'+Id_pre}).trigger('reloadGrid');
            jQuery("#table_predios").jqGrid('setGridParam', {url: 'gridpredio?tpre=1&mnza='+$("#selmnza").val()+'&ctr=0&an='+$("#selantra").val()}).trigger('reloadGrid');
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
            cant:$("#rinst_inp_canti").val(),glosa:$("#glosa").val()},
        success: function(r) 
        {
            MensajeExito("Se Modificó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            jQuery("#table_instal").jqGrid('setGridParam', {url: 'gridinsta/'+$('#dlg_idpre').val()}).trigger('reloadGrid');
            jQuery("#table_predios").jqGrid('setGridParam', {url: 'gridpredio?tpre=1&mnza='+$("#selmnza").val()+'&ctr=0&an='+$("#selantra").val()}).trigger('reloadGrid');
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
                jQuery("#table_predios").jqGrid('setGridParam', {url: 'gridpredio?tpre=1&mnza='+$("#selmnza").val()+'&ctr=0&an='+$("#selantra").val()}).trigger('reloadGrid');
                MensajeAlerta("Se Eliminó Correctamente","Su Registro Fue eliminado Correctamente...",4000)
                MensajeDialogLoadAjaxFinish('s2');
            },
            error: function(data) {
                mostraralertas("hubo un error, Comunicar al Administrador");
                MensajeDialogLoadAjaxFinish('s2');
                console.log('error');
                console.log(data);
            }
        });
    }
   
    function callchangeoption(input,tip)
    {
        $("#"+input+"_des").val($("#"+input+" option:selected").attr("descri"));
        if(tip==1)
        {
            $("#dlg_inp_aranc").val($("#"+input+" option:selected").attr("aran"));
            validarvalter();
            
        }
    }
    
    function validarvalter()
    {
        if($("#dlg_inp_aranc").val()==""||$("#dlg_inp_areter").val()==""){$("#dlg_inp_valterr").val(0);return false;}
        if($("#dlg_inp_arecomter").val()==""){
            comun=0;}
        else{
            comun=$("#dlg_inp_arecomter").val();}
        $("#dlg_inp_valterr").val(formato_numero($("#dlg_inp_aranc").val()*(parseFloat($("#dlg_inp_areter").val())+parseFloat(comun)),3,".",","));
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
    function auto_select(sel_input,url,valor)
    {
        MensajeDialogLoadAjax(sel_input, '.:: Cargando ...');
        $("#"+sel_input).html("");
        $.ajax({
            type: 'GET',
            url: url,
            data:{sec:$("#dlg_sec").val(),mzna:$("#dlg_mzna").val(),an:$("#selantra").val()},
            success: function(data){               
                for (var i=0; i < data.length; i++)
                {
                    $("#"+sel_input).append($('<option>',{value:data[i].id_via,text: data[i].nom_via,descri:data[i].cod_via, aran:data[i].val_ara}));
                } 
                MensajeDialogLoadAjaxFinish(sel_input);
                $("#"+sel_input).val(valor);
                if(valor==0)
                {
                    callchangeoption(sel_input,1);
                }
                else
                {
                    $("#"+sel_input+"_des").val($("#"+sel_input+" option:selected").attr("descri"));
                }
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
    window.open('pre_rep/PU/'+Id+'/0/0');
}
///////////////Arbitrios
function limpiararb()
{
    $("#sel_mes_ini").val(1);
    $("#sel_bar_frecu,#sel_ressol_frecu,#sel_seren_cat,#sel_parq_cat,#sel_ressol_tp").val(0);
    $("#inp_bar_frent,#sel_bar_frecu_cos,#inp_bar_costot,#inp_bar_costri,#inp_bar_cosmes").val("");
    $("#inp_ressol_area,#sel_ressol_frecu_cos,#inp_ressol_costot,#inp_ressol_costri,#inp_ressol_cosmes").val("");
    $("#inp_seren_costot,#inp_seren_costri,#inp_seren_cosmes").val("");
    $("#inp_parq_costot,#inp_parq_costri,#inp_parq_mes").val("");
}
function create_arb()
{
    $("#tit_anio").html("<b>Arbitrios para el Año "+$("#selantra").val()+"...</b>")
    limpiararb();
    $("#dlg_new_arbi").dialog({
        autoOpen: false, modal: true, width: 1000, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.: Arbítrios :.</h4></div>" ,
        buttons: [
            {
                id:"btnsavearb",
                html: '<span class="btn-label"><i class="glyphicon glyphicon-new-window"></i></span>Grabar Arbítrios',
                "class": "btn btn-labeled bg-color-green txt-color-white",
                click: function () {savearb();}
            },
//            {
//                id:"btnupdatearb",
//                html: '<span class="btn-label"><i class="glyphicon glyphicon-new-window"></i></span>Modificar Arbítrios',
//                "class": "btn btn-labeled bg-color-blue txt-color-white",
//                click: function () {updatearb();}
//            },
            {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-primary bg-color-red",
                click: function () {$(this).dialog("close");}
            }]
    }).dialog('open');
}
function new_arb()
{
    limpiararb();
    Id=$('#dlg_idpre').val();
    if(Id==0)
    {
        mostraralertas("Primero Guardar Predio...");
        return false;
    }
    create_arb();
    MensajeDialogLoadAjax('dlg_new_arbi', '.:: Cargando ...');
        $.ajax({url: 'arbitrios_municipales/'+Id,
        type: 'GET',
        data:{an:$("#selantra").val(),new:1},
        success: function(r) 
        {
            getpisos(0);
            $("#inp_hidd_arb").val(0);
            $("#btnupdatearb").hide();
            $("#btnsavearb").show();
            if(r==0)
            {
                $("#inp_ressol_area").val(0);
            }
            else
            {
                $("#inp_ressol_area").val(r[0].area_const);
            }
            $("#sel_mes_ini, #sel_pis_uso").prop( "disabled", false );
            MensajeDialogLoadAjaxFinish('dlg_new_arbi');
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            MensajeDialogLoadAjaxFinish('dlg_new_arbi');
            $("#dlg_new_arbi").dialog('close');
        }
        });
}
function change_select(input,tip)
{
    if(tip<3)
    {
        $("#"+input+"_cos").val($("#"+input+" option:selected").attr("costo"));
    }
    calculos(tip);
}
function calculos(tip)
{
    switch(tip)
    {
        case 1:
            if($("#inp_bar_frent").val()==""||$("#sel_bar_frecu").val()=="")
            {
                $("#inp_bar_costot,#inp_bar_cosmes,#inp_bar_costri").val(0);
            }
            else
            {
                $("#inp_bar_costot").val(($("#inp_bar_frent").val()*$("#sel_bar_frecu_cos").val()).toFixed(4));
                $("#inp_bar_cosmes").val(($("#inp_bar_costot").val()/12).toFixed(4));
                $("#inp_bar_costri").val(($("#inp_bar_costot").val()/4).toFixed(4));
            }
        case 2:
            if($("#inp_ressol_area").val()==""||$("#sel_ressol_frecu").val()=="")
            {
                $("#inp_ressol_costot,#inp_ressol_costri,#inp_ressol_cosmes").val(0);
            }
            else
            {
                $("#inp_ressol_costot").val(($("#inp_ressol_area").val()*$("#sel_ressol_frecu_cos").val()).toFixed(4));
                $("#inp_ressol_costri").val(($("#inp_ressol_costot").val()/4).toFixed(4));
                $("#inp_ressol_cosmes").val(($("#inp_ressol_costot").val()/12).toFixed(4));
            }
        case 3:
            $("#inp_seren_costot").val($("#sel_seren_cat option:selected").attr("costo"));
            $("#inp_seren_costri").val(($("#inp_seren_costot").val()/4).toFixed(4));
            $("#inp_seren_cosmes").val(($("#inp_seren_costot").val()/12).toFixed(4));
        case 4:
            $("#inp_parq_costot").val($("#sel_parq_cat option:selected").attr("costo"));
            $("#inp_parq_costri").val(($("#inp_parq_costot").val()/4).toFixed(4));
            $("#inp_parq_mes").val(($("#inp_parq_costot").val()/12).toFixed(4));
    }
}
function cambiarpis()
{
     $("#inp_ressol_area").val($("#sel_pis_uso option:selected").attr("costo"));
}
function call_frec_rrs(valu)
{
    MensajeDialogLoadAjax("sel_ressol_frecu", '.:: Cargando ...');
    id=$("#sel_ressol_tp").val();
    $.ajax({url: 'getfrecrrs',
        type: 'GET',
        data:{id:id,an:$("#selantra").val()},
        success: function(data) 
        {
            $("#sel_ressol_frecu").html("");
            $("#sel_ressol_frecu").append($('<option>',{value:0,text: "--Seleccione--", costo:0}));
            for (var i=0; i < data.length; i++)
            {
                $("#sel_ressol_frecu").append($('<option>',{value:data[i].id_rrs,text: data[i].frecuencia, costo:data[i].costo}));
            } 
            $("#sel_ressol_frecu").val(valu);
            change_select('sel_ressol_frecu',2);
            MensajeDialogLoadAjaxFinish('sel_ressol_frecu');
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            
        }
        });
}

function getpisos(valor)
{
    $("#sel_pis_uso").html("");
    MensajeDialogLoadAjax('sel_pis_uso', '.:: Cargando ...');
    Id=$('#dlg_idpre').val();
    $.ajax({url: 'gridpisos/'+Id,
        type: 'GET',
        data:{page:1,limit:1,sidx:'cod_piso',sord:'asc',rows:1},
        success: function(r) 
        {
            total=0;
            $("#sel_pis_uso").append($('<option>',{value:0,text: "Todos",costo:0}));
            if(r['rows'])
            {
                for (var i=0; i < r['rows'].length; i++)
                {
                    total=parseFloat(total)+parseFloat(r['rows'][i].cell[13]);
                    $("#sel_pis_uso").append($('<option>',{value:r['rows'][i].cell[0],text: r['rows'][i].cell[1], costo:r['rows'][i].cell[13]}));
                }
            }
            $("#sel_pis_uso").find('option[value="0"]').attr('costo',total);
            MensajeDialogLoadAjaxFinish('sel_pis_uso');
            $("#sel_pis_uso").val(valor);
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            MensajeDialogLoadAjaxFinish('sel_pis_uso');
        }
        });
}

function callfilltab_arbitrios()
{
    obtener_barrido_fec();
    obtener_serenazgo_fec();
    getfrecparques();
    
}
function obtener_barrido_fec()
{
     MensajeDialogLoadAjax("sel_bar_frecu", '.:: Cargando ...');
    $.ajax({url: 'getfrecbarrido/'+$("#selantra").val(),
        type: 'GET',
        success: function(data) 
        {
            $("#sel_bar_frecu").html("");
            $("#sel_bar_frecu").append($('<option>',{value:0,text: "--Seleccione--", costo:0}));
            for (var i=0; i < data.length; i++)
            {
                $("#sel_bar_frecu").append($('<option>',{value:data[i].id_bar_cal,text: data[i].frecuencia+"-Veces por Semana", costo:data[i].costo}));
            } 
            MensajeDialogLoadAjaxFinish("sel_bar_frecu");
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            }
        });
}
function obtener_serenazgo_fec()
{
     MensajeDialogLoadAjax("sel_seren_cat", '.:: Cargando ...');
    $.ajax({url: 'getfrecserenazgo/'+$("#selantra").val(),
        type: 'GET',
        success: function(data) 
        {
            $("#sel_seren_cat").html("");
            $("#sel_seren_cat").append($('<option>',{value:0,text: "--Seleccione--", costo:0}));
            for (var i=0; i < data.length; i++)
            {
                $("#sel_seren_cat").append($('<option>',{value:data[i].id_seren,text: data[i].categoria, costo:data[i].costo}));
            } 
            MensajeDialogLoadAjaxFinish("sel_seren_cat");
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            }
        });
}
function getfrecparques()
{
     MensajeDialogLoadAjax("sel_parq_cat", '.:: Cargando ...');
    $.ajax({url: 'getfrecparques/'+$("#selantra").val(),
        type: 'GET',
        success: function(data) 
        {
            $("#sel_parq_cat").html("");
            $("#sel_parq_cat").append($('<option>',{value:0,text: "--Seleccione--", costo:0}));
            for (var i=0; i < data.length; i++)
            {
                $("#sel_parq_cat").append($('<option>',{value:data[i].id_par_jar,text: data[i].categoria, costo:data[i].costo}));
            } 
            MensajeDialogLoadAjaxFinish("sel_parq_cat");
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            }
        });
}

function llenararbitrios()
{
    pred=$('#dlg_idpre').val();
    jQuery("#table_arbitrios").jqGrid('setGridParam', {url: 'gridarbitrios?pre='+pred+'&an='+$("#selantra").val()}).trigger('reloadGrid');
}
function savearb()
{
    pred=$('#dlg_idpre').val();
    cod=$('#table_predios').jqGrid ('getCell', pred, 'cod_cat');
    if($("#inp_bar_cosmes").val()==""){$("#inp_bar_cosmes").val(0)};
    if($("#inp_ressol_cosmes").val()==""){$("#inp_ressol_cosmes").val(0)};
    if($("#inp_seren_cosmes").val()==""){$("#inp_seren_cosmes").val(0)};
    if($("#inp_parq_mes").val()==""){$("#inp_parq_mes").val(0)};
    if($("#inp_ressol_area").val()==""){$("#inp_ressol_area").val(0)};
    MensajeDialogLoadAjax('dlg_new_arbi', '.:: Guardando ...');
    $.ajax({url: 'arbitrios_municipales/create',
    type: 'GET',
    data:{barfrec:$("#sel_bar_frecu").val(),barfrent:$("#inp_bar_frent").val(),pred:pred,cod:cod,
            an:$("#selantra").val(),usorrs:$("#sel_ressol_tp").val(),rrsfrec:$("#sel_ressol_frecu").val(),seren:$("#sel_seren_cat").val(),
            parjar:$("#sel_parq_cat").val(),contrib:$("#dlg_contri_hidden").val(),
            mesbar:$("#inp_bar_cosmes").val(),mesrrs:$("#inp_ressol_cosmes").val(),
            messeren:$("#inp_seren_cosmes").val(),mesparjar:$("#inp_parq_mes").val(),inimes:$("#sel_mes_ini").val(),
            pis_uso:$("#sel_pis_uso").val(),area:$("#inp_ressol_area").val()},
    success: function(r) 
    {
        MensajeExito("Insertó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
        llenararbitrios();
        MensajeDialogLoadAjaxFinish('dlg_new_arbi');
        $("#dlg_new_arbi").dialog('close');
        
    },
    error: function(data) {
        mostraralertas("hubo un error, Comunicar al Administrador");
        MensajeDialogLoadAjaxFinish('dlg_new_arbi');
        console.log('error');
        console.log(data);
    }
    });
}
//otrooo
function llamar_otro(){
     if($("#dlg_sel_foradq").val()=="9")
    {
       $("#dialog_otros").dialog({
        autoOpen: false, modal: true, width: 600, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: Especificar OTROS :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-save'></i>&nbsp; Confirmar"  ,
            "class": "btn btn-success bg-color-green",
            click: function () { save_otros(),$(this).dialog("close"); }
        }, {
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () { $(this).dialog("close"); }
        }]
    }).dialog('open');
    }
       
}
function save_otros(){
   nuevo_otros = $("#dlg_inp_otros").val();
}
