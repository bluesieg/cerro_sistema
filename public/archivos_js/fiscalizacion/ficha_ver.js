var inputglobal="";
function fn_bus_contrib_carta(input)
{
    inputglobal=input;
    if($("#"+input).val()=="")
    {
        mostraralertasconfoco("Ingresar Información del Contribuyente para busqueda","#dlg_contri"); 
        return false;
    }
    if($("#"+input).val().length<4)
    {
        mostraralertasconfoco("Ingresar al menos 4 caracteres de busqueda","#dlg_contri"); 
        return false;
    }
    jQuery("#table_contrib").jqGrid('setGridParam', {url: 'obtiene_cotriname?dat='+$("#"+input).val()}).trigger('reloadGrid');

    $("#dlg_bus_contr").dialog({
        autoOpen: false, modal: true, width:770, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  Busqueda de Contribuyente :.</h4></div>"       
        }).dialog('open');
}
function fn_bus_contrib_list_ficha(per)
{
    $("#"+inputglobal+"_hidden").val(per);
    $("#"+inputglobal).val($('#table_contrib').jqGrid('getCell',per,'contribuyente'));
    $("#"+inputglobal+"_doc").val($('#table_contrib').jqGrid('getCell',per,'nro_doc'));
    $("#"+inputglobal+"_dom").val($('#table_contrib').jqGrid('getCell',per,'dom_fiscal'));
    if(inputglobal=="dlg_contri")
    {
        call_list_contrib_carta(1);
    }
    $("#dlg_bus_contr").dialog("close");
}

function call_list_contrib_carta(tip)
{
    
    $("#table_cartas").jqGrid("clearGridData", true);
    if(tip==0)
    {
        jQuery("#table_cartas").jqGrid('setGridParam', {url: 'trae_cartas/'+$("#selantra").val()+'/0/0/0/0'}).trigger('reloadGrid');
    }
    if(tip==1)
    {
        jQuery("#table_cartas").jqGrid('setGridParam', {url: 'trae_cartas/'+$("#selantra").val()+'/'+$("#dlg_contri_hidden").val()+'/0/0/0'}).trigger('reloadGrid');
    }
    if(tip==2)
    {
        if($("#dlg_bus_fini").val()==""||$("#dlg_bus_ffin").val()=="")
        {
            mostraralertasconfoco("Ingresar Fechas de busqueda","dlg_bus_fini");
            return false;
        }
        ini=$("#dlg_bus_fini").val().replace(/\//g,"-");
        fin=$("#dlg_bus_ffin").val().replace(/\//g,"-");
        jQuery("#table_cartas").jqGrid('setGridParam', {url: 'trae_cartas/0/0/'+ini+'/'+fin+'/0'}).trigger('reloadGrid');
    }
    if(tip==3)
    {
        if($("#dlg_bus_num").val()=="")
        {
            mostraralertasconfoco("Ingresar Numero","#dlg_bus_num"); 
            return false;
        }
        ajustar(6,'dlg_bus_num')
        num=$("#dlg_bus_num").val();
        jQuery("#table_cartas").jqGrid('setGridParam', {url: 'trae_cartas/'+$("#selantra").val()+'/0/0/0/'+num}).trigger('reloadGrid');
    }
    
}

creardlgcarta=0;
function datos_carta($id)
{
    limpiar_carta();
    if(creardlgcarta==0)
    {
        creardlgcarta=1;
        $("#dlg_vista_carta").dialog({
        autoOpen: false, modal: true, width: 1300, 
        show:{ effect: "explode", duration: 500},
        resizable: false,
        title: "<div class='widget-header'><h4><span class='widget-icon'> <i class='fa fa-align-justify'></i> </span> Datos Generales de Carta</h4></div>"
        }).dialog('open');
    }
    else
    {
        $("#dlg_vista_carta").dialog('open');
    }
    traer_carta($id);
}
function limpiar_carta()
{
    $("#dlg_nro_car,#dlg_contri_carta,#dlg_contri_carta_dom,#dlg_fec_fis,#dlg_hor_fis").val("");
}
function traer_carta($id)
{
    MensajeDialogLoadAjax('dlg_vista_carta', '.:: CARGANDO ...');
    $.ajax({url: 'carta_reque/'+$id,
        type: 'GET',
        success: function(r) 
        {
            $("#dlg_nro_car").val(r[0].nro_car);
            $("#dlg_contri_carta").val(r[0].contribuyente);
            $("#dlg_contri_carta_dom").val(r[0].ref_dom_fis);
            $("#dlg_fec_fis").val(r[0].fec_fis);
            $("#dlg_hor_fis").val(r[0].hora_fis);
            jQuery("#table_predios_contri").jqGrid('setGridParam', {url: 'trae_pred_carta/'+$id}).trigger('reloadGrid');
            MensajeDialogLoadAjaxFinish('dlg_vista_carta');

        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
            MensajeDialogLoadAjaxFinish('dlg_vista_carta');
            $("#dlg_vista_carta").dialog('close');
        }
        });
}

function vercarta(id)
{
    if($("#per_imp").val()==0)
    {
        sin_permiso();
        return false;
    }
    window.open('car_req_rep/'+id);
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
autocompletar=0;
creadialog=0;
function limpiarpred(tp)
{
    if(tp=="URB")
    {
        $("#dlg_img_view").html('<center><img src="img/recursos/Home-icon.png" height="100%" width="65%"/></center>');
    }
    if(tp=="RUS")
    {
        $("#dlg_img_view").html('<center><img src="img/recursos/rustico.jpg" height="100%" width="65%"/></center>');
    }
    autocompletar=1;
    if(creadialog==0)
    {
        creadialog=1;
        $("#dlg_reg_dj").dialog({
        autoOpen: false, modal: true, width: 1300, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.: Ficha Unica de Verificación Del Impuesto Predial :.</h4></div>",
        });
    }
    $("#dlg_sel_condpre,#dlg_nro_ficha,#dlg_observa").val("");
    $( "#dlg_dni, #dlg_lot" ).prop( "disabled", true );
    $('#dlg_inp_condos').val("");
    $("#dlg_inp_areter,#dlg_inp_arecomter").val("");
}
function clickmodgrid(Id)
{
   $("#dlg_idpre").val(Id);
   tp=$('#table_predios_contri').jqGrid('getCell',Id,'tp');
   if(tp=="URB")
   {
       $("#div_rus,#div_rus2").hide();
       $("#div_urb,#div_urb2").show();
   }
   else
   {
       $("#div_rus,#div_rus2").show();
       $("#div_urb,#div_urb2").hide();
   }
   limpiarpred(tp);
   $("#dlg_reg_dj").dialog('open');
   MensajeDialogLoadAjax('dlg_reg_dj', '.:: Cargando ...');
   id_fic=$('#table_predios_contri').jqGrid('getCell',Id,'id_fic');
   if(id_fic>0)
   {
       $("#dlg_idfic").val(id_fic);
       url='ficha_veri/'+id_fic;
       $("#btnsaveficha").hide();
        $("#btnmodficha").show();
   }
   else
   {
       id_fic=0;
        $("#dlg_idfic").val(0);
        if(tp=="URB")
        {
            url='predios_urbanos/'+Id;
        }
        else
        {
            url='predios_rural/'+Id;
        }
        $("#btnsaveficha").show();
        $("#btnmodficha").hide();
   }
   $.ajax({url: url,
    type: 'GET',
    success: function(r) 
    {
        if(id_fic>0)
        {
            $("#dlg_nro_ficha").val(r[0].nro_fic);
            $("#dlg_observa").val(r[0].observaciones);
        }
        if(r[0].foto!=0)
        {
            $("#dlg_img_view").html('<center><img src="data:image/png;base64,'+r[0].foto+'" height="100%" width="90%"/></center>');
        }
        if(tp=="URB")
        {
            $("#dlg_sec").val(r[0].sec);
            $("#dlg_mzna").val(r[0].mzna);
            $("#dlg_lot").append($('<option>',{value:0,text: r[0].lote}));
            dir=r[0].nom_via;
            if(r[0].nro_mun!=""){
                dir=dir+" N° "+r[0].nro_mun;
            }
            if(r[0].mzna_dist!=""){
                dir=dir+" Mzna "+r[0].mzna_dist;
            }
            if(r[0].lote_dist!=""){
                dir=dir+" Lt "+r[0].lote_dist;
            }
            if(r[0].zona!=""){
                dir=dir+" Zn "+r[0].zona;
            }
            $("#dlg_inp_direcc").val(dir+" "+r[0].habilitacion);
            $("#dlg_inp_direcc").attr('title',dir+" "+r[0].habilitacion);
            $("#dlg_inp_areter").val(r[0].are_terr);
            $("#dlg_inp_arecomter").val(r[0].are_com_terr);
        }
        else
        {
            $("#dlg_inp_direcc").val(r[0].lugar_pr_rust+" "+r[0].ubicac_pr_rus+" "+r[0].klm+" "+r[0].nom_pre_pr_rus);
            if(id_fic>0)
            {
                $("#dlg_inp_hectarea").val(r[0].hectareas);
            }
            else
            {
                $("#dlg_inp_hectarea").val(r[0].are_terr);
            }
            $("#dlg_sel_gpoterr").val(r[0].id_gpo_tierra);
            auto_select("dlg_sel_gpocatterr","sel_cat_gruterr",r[0].id_gpo_tierra,r[0].id_cat_gpo_tierra);
        }
            $("#dlg_dni_pred").val(r[0].nro_doc);
            $("#dlg_contri_pred").val(r[0].contribuyente);
            $("#dlg_sel_condpre").val(r[0].id_cond_prop);
            $("#dlg_inp_condos").val(r[0].nro_condominios);
            validacond();
            $("#dlg_sel_estcon").val(r[0].id_est_const);
            $("#dlg_sel_tippre").val(r[0].id_tip_pred);
            $("#dlg_inp_aranc").val(r[0].arancel);
            $("#dlg_inp_valterr").val(formato_numero(r[0].val_ter,3,".",","));
            
            
            MensajeDialogLoadAjaxFinish('dlg_reg_dj');
            jQuery("#table_pisos").jqGrid('setGridParam', {url: 'traepisos_fic/'+Id+'/'+id_fic}).trigger('reloadGrid');
            jQuery("#table_instal").jqGrid('setGridParam', {url: 'traeinsta_fic/'+Id+'/'+id_fic}).trigger('reloadGrid');


    },
    error: function(data) {
        mostraralertas("hubo un error, Comunicar al Administrador");
        console.log('error');
        console.log(data);
        MensajeDialogLoadAjaxFinish('dlg_reg_dj');
    }
    }); 
}
function dlgsave()
{
    if($("#dlg_nro_ficha").val()==""){mostraralertasconfoco("Ingresar Número de ficha de Verificación","dlg_nro_ficha");return false}
    Id=$("#dlg_idpre").val();
    tp_pre=$('#table_predios_contri').jqGrid('getCell',Id,'tp');
    if(tp_pre=="URB")
    {
        if($("#dlg_inp_areter").val()==""){mostraralertasconfoco("Ingresar Area del Terreno","dlg_inp_areter");return false}
    }
    if(tp_pre=="RUS")
    {
        if($("#dlg_inp_hectarea").val()==""){mostraralertasconfoco("Ingresar Hectareas","dlg_inp_hectarea");return false}
    }
    if($("#dlg_inp_arecomter").val()==""){$("#dlg_inp_arecomter").val(0)};
    puente=$('#table_predios_contri').jqGrid('getCell',Id,'id_puente');
    ajustar(6, 'dlg_nro_ficha');
    carta=$('#table_cartas').jqGrid ('getGridParam', 'selrow');
    MensajeDialogLoadAjax('dlg_reg_dj', '.:: CARGANDO ...');
        $.ajax({url: 'ficha_veri/create',
        type: 'GET',
        data:{puente:puente,
            nro:$("#dlg_nro_ficha").val(),
            obs:$("#dlg_observa").val(),
            cprop:$("#dlg_sel_condpre").val(),
                condos:$("#dlg_inp_condos").val(),
                ecc:$("#dlg_sel_estcon").val(),
                tp:$("#dlg_sel_tippre").val(),
                arcancel:$("#dlg_inp_aranc").val(),
                ater:$("#dlg_inp_areter").val(),
                acomun:$("#dlg_inp_arecomter").val(),
                hectareas:$("#dlg_inp_hectarea").val(),
                gr_tierra:$("#dlg_sel_gpoterr").val(),
                cat_tierra:$("#dlg_sel_gpocatterr").val(),
                carta:carta,
                tip_pre_u_r:tp_pre
            },
        success: function(r) 
        {
            if(r==0)
            {
                MensajeAlerta("No se Puede Crear","ya existe una hoja de Liquidación relacionada...",4000);
            }
            if(r==-1)
            {
                MensajeAlerta("No se Puede Crear","La Carta Relacionada está Anulada...",4000);
            }
            if(r>0)
            {
                $("#dlg_idfic").val(r);
                $("#btnsaveficha").hide();
                $("#btnmodficha").show();
                MensajeExito("Se Insertó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            }
            jQuery("#table_predios_contri").jqGrid('setGridParam', {url: 'trae_pred_carta/'+carta}).trigger('reloadGrid');
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
function dlgupdate()
{
    if($("#dlg_nro_ficha").val()==""){mostraralertasconfoco("Ingresar Número de ficha de Verificación","dlg_nro_ficha");return false}
    Idpre=$("#dlg_idpre").val();
    tp_pre=$('#table_predios_contri').jqGrid('getCell',Idpre,'tp');
    if(tp_pre=="URB")
    {
        if($("#dlg_inp_areter").val()==""){mostraralertasconfoco("Ingresar Area del Terreno","dlg_inp_areter");return false}
    }
    if(tp_pre=="RUS")
    {
        if($("#dlg_inp_hectarea").val()==""){mostraralertasconfoco("Ingresar Hectareas","dlg_inp_hectarea");return false}
    }
    if($("#dlg_inp_arecomter").val()==""){$("#dlg_inp_arecomter").val(0)};
    id=$("#dlg_idfic").val();
    ajustar(6, 'dlg_nro_ficha');
    carta=$('#table_cartas').jqGrid ('getGridParam', 'selrow');
    MensajeDialogLoadAjax('dlg_reg_dj', '.:: CARGANDO ...');
        $.ajax({url: 'ficha_veri/'+id+'/edit',
        type: 'GET',
        data:{
            nro:$("#dlg_nro_ficha").val(),
            obs:$("#dlg_observa").val(),
            cprop:$("#dlg_sel_condpre").val(),
            condos:$("#dlg_inp_condos").val(),
            ecc:$("#dlg_sel_estcon").val(),
            tp:$("#dlg_sel_tippre").val(),
            arcancel:$("#dlg_inp_aranc").val(),
            ater:$("#dlg_inp_areter").val(),
            acomun:$("#dlg_inp_arecomter").val(),
            hectareas:$("#dlg_inp_hectarea").val(),
            gr_tierra:$("#dlg_sel_gpoterr").val(),
            cat_tierra:$("#dlg_sel_gpocatterr").val(),
            carta:carta,
            tip_pre_u_r:tp_pre
        },
        success: function(r) 
        {
            if(r==0)
            {
                MensajeAlerta("No se Puede Modificar","ya existe una hoja de Liquidación relacionada...",4000);
            }
            if(r==-1)
            {
                MensajeAlerta("No se Puede Modificar","La Carta Relacionada está Anulada...",4000);
            }
            if(r>0)
            {
                MensajeExito("Se Modificó Correctamente","Su Registro Fue Modificado con Éxito...",4000);
            }
            jQuery("#table_predios_contri").jqGrid('setGridParam', {url: 'trae_pred_carta/'+carta}).trigger('reloadGrid');
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

function validarvalter(tp)
{
    if(tp==1)
    {
     if($("#dlg_inp_aranc").val()==""||$("#dlg_inp_areter").val()==""){$("#dlg_inp_valterr").val(0);return false;}
        if($("#dlg_inp_arecomter").val()==""){
            comun=0;}
        else{
            comun=$("#dlg_inp_arecomter").val();}
        $("#dlg_inp_valterr").val(formato_numero($("#dlg_inp_aranc").val()*(parseFloat($("#dlg_inp_areter").val())+parseFloat(comun)),3,".",","));
    }
    if(tp==2)
    {
        if($("#dlg_inp_aranc").val()==""||$("#dlg_inp_hectarea").val()==""){$("#dlg_inp_valterr").val(0);return false;}
        $("#dlg_inp_valterr").val( formato_numero($("#dlg_inp_aranc").val()*$("#dlg_inp_hectarea").val(),2,".",","));
    
    }
   
}


function creardlgpiso()
{
 $("#dlg_reg_piso").dialog({
    autoOpen: false, modal: true, width: 1200, show: {effect: "fade", duration: 300}, resizable: false,
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
    if($("#dlg_idfic").val()==0)
    {
        mostraralertas("Para Crear Piso, Primero Guardar Ficha de Fiscalización...");
        return false;
    }
    if($('#dlg_sel_estcon').val()==1)
    {
        mostraralertas("No se puede crear piso en un terreno sin construir..");
        return false;
    }
    limpiapisodeclaracion();
    limpiarpisofic();
    creardlgpiso();
    $("#btnpissave").show();
    $("#btnpismod").hide();
}
function limpiapisodeclaracion()
{
    $("#rpiso_inp_nro,#rpiso_inp_fech,#rpiso_inp_econstr,#rpiso_inp_estruc,#rpiso_inp_aconst,#rpiso_inp_acomun").val("")
    $("#rpiso_inp_clasi").val($("#rpiso_inp_clasi option:first").val());
    $("#rpiso_inp_mat").val($("#rpiso_inp_mat option:first").val());
    $("#rpiso_inp_econserv").val($("#rpiso_inp_econserv option:first").val());
    $("#rpiso_inp_econstr").val($("#rpiso_inp_econstr option:first").val());
    
    callchangeoption("rpiso_inp_clasi",0);
    callchangeoption("rpiso_inp_mat",0);
    callchangeoption("rpiso_inp_econserv",0);
    callchangeoption("rpiso_inp_econstr",0);
}
function limpiarpisofic()
{
    $("#rpiso_inp_nro_fis,#rpiso_inp_fech_fis,#rpiso_inp_econstr_fis,#rpiso_inp_estruc_fis,#rpiso_inp_aconst_fis,#rpiso_inp_acomun_fis").val("")
    $("#rpiso_inp_clasi_fis").val($("#rpiso_inp_clasi option:first").val());
    $("#rpiso_inp_mat_fis").val($("#rpiso_inp_mat option:first").val());
    $("#rpiso_inp_econserv_fis").val($("#rpiso_inp_econserv option:first").val());
    $("#rpiso_inp_econstr_fis").val($("#rpiso_inp_econstr option:first").val());
    callchangeoption("rpiso_inp_clasi_fis",0);
    callchangeoption("rpiso_inp_mat_fis",0);
    callchangeoption("rpiso_inp_econserv_fis",0);
    callchangeoption("rpiso_inp_econstr_fis",0);
}
function clickmodpiso()
{
    limpiapisodeclaracion();
    limpiarpisofic();
    if($("#dlg_idfic").val()==0)
    {
        mostraralertas("Para Crear Piso, Primero Guardar Ficha de Fiscalización...");
        return false;
    }
    Id=$('#table_pisos').jqGrid ('getGridParam', 'selrow');
    $('#dlg_idpiso').val(Id);
    creardlgpiso();
    id_pisos_fic=$('#table_pisos').jqGrid('getCell',Id,'id_pisos_fic');
    if(id_pisos_fic>0)
    {
        $("#btnpissave").hide();
        $("#btnpismod").show();
        $("#dlg_idpiso_fis").val(id_pisos_fic);
        traerpisofic(id_pisos_fic);
    }
    else
     {
        $("#btnpissave").show();
        $("#btnpismod").hide();
        $("#dlg_idpiso_fis").val(0);
    } 
    MensajeDialogLoadAjax('dlg_reg_piso', '.:: Cargando ...');
    if(Id.slice(0,1)>0)
    {
        $.ajax({url: 'pisos_predios/'+Id,
        type: 'GET',
        success: function(r) 
        {
            $("#rpiso_inp_nro,#rpiso_inp_nro_fis").val(r[0].cod_piso);
            $("#rpiso_inp_fech,#rpiso_inp_fech_fis").val(r[0].ani_const);
            $("#rpiso_inp_clasi,#rpiso_inp_clasi_fis").val(parseInt(r[0].clas));
            $("#rpiso_inp_mat, #rpiso_inp_mat_fis").val(r[0].mep);
            $("#rpiso_inp_econserv, #rpiso_inp_econserv_fis").val(r[0].esc);
            $("#rpiso_inp_econstr, #rpiso_inp_econstr_fis").val(parseInt(r[0].ecc));
            $("#rpiso_inp_estruc, #rpiso_inp_estruc_fis").val(r[0].est_mur+r[0].est_tch+r[0].aca_pis+r[0].aca_pta+r[0].aca_rev+r[0].aca_ban+r[0].ins_ele);
            $("#rpiso_inp_aconst, #rpiso_inp_aconst_fis").val(r[0].area_const);
            $("#rpiso_inp_acomun, #rpiso_inp_acomun_fis").val(r[0].val_areas_com);
            MensajeDialogLoadAjaxFinish('dlg_reg_piso');
            callchangeoption("rpiso_inp_clasi",0);
            callchangeoption("rpiso_inp_mat",0);
            callchangeoption("rpiso_inp_econserv",0);
            callchangeoption("rpiso_inp_econstr",0);
            
            callchangeoption("rpiso_inp_clasi_fis",0);
            callchangeoption("rpiso_inp_mat_fis",0);
            callchangeoption("rpiso_inp_econserv_fis",0);
            callchangeoption("rpiso_inp_econstr_fis",0);

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
}
function traerpisofic(Id)
{
   $.ajax({url: 'piso_fisca/'+Id,
    type: 'GET',
    success: function(r) 
    {
        $("#rpiso_inp_nro_fis").val(r[0].cod_piso);
        $("#rpiso_inp_fech_fis").val(r[0].ani_const);
        $("#rpiso_inp_clasi_fis").val(parseInt(r[0].clas));
        $("#rpiso_inp_mat_fis").val(r[0].mep);
        $("#rpiso_inp_econserv_fis").val(r[0].esc);
        $("#rpiso_inp_econstr_fis").val(parseInt(r[0].ecc));
        $("#rpiso_inp_estruc_fis").val(r[0].est_mur+r[0].est_tch+r[0].aca_pis+r[0].aca_pta+r[0].aca_rev+r[0].aca_ban+r[0].ins_ele);
        $("#rpiso_inp_aconst_fis").val(r[0].area_const);
        $("#rpiso_inp_acomun_fis").val(r[0].val_areas_com);
        MensajeDialogLoadAjaxFinish('dlg_reg_piso');
        callchangeoption("rpiso_inp_clasi_fis",0);
        callchangeoption("rpiso_inp_mat_fis",0);
        callchangeoption("rpiso_inp_econserv_fis",0);
        callchangeoption("rpiso_inp_econstr_fis",0);

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
function callchangeoption(input,tip)
{
    $("#"+input+"_des").val($("#"+input+" option:selected").attr("descri"));
    if(tip==1)
    {
        $("#dlg_inp_aranc").val($("#"+input+" option:selected").attr("aran"));
        validarvalter(2);
    }
}
    
function pisoSave()
{
    if($("#rpiso_inp_nro_fis").val()==""){mostraralertasconfoco("Ingresar Nro Piso","#rpiso_inp_nro"); return false}
    if($("#rpiso_inp_fech_fis").val()==""){mostraralertasconfoco("Ingresar Año de Construccion del Piso","#rpiso_inp_fech"); return false}
    if($("#rpiso_inp_fech_fis").val()<1800){mostraralertasconfoco("Ingresar Año de Construccion Valido de 4 cifras","#rpiso_inp_fech"); return false}
    if($("#rpiso_inp_clasi_fis").val()==""){mostraralertasconfoco("Ingresar Estado de Conservación","#rpiso_inp_econserv"); return false}
    if($("#rpiso_inp_mat_fis").val()==""){mostraralertasconfoco("Ingresar Estado de Conservación","#rpiso_inp_econserv"); return false}
    if($("#rpiso_inp_econserv_fis").val()==""){mostraralertasconfoco("Ingresar Estado de Conservación","#rpiso_inp_econserv"); return false}
    if($("#rpiso_inp_econstr_fis").val()==""){mostraralertasconfoco("Ingresar Estado de Construcción","#rpiso_inp_econstr"); return false}
    if($("#rpiso_inp_estruc_fis").val()==""){mostraralertasconfoco("Ingresar Estructuras","#rpiso_inp_estruc"); return false}
    if($("#rpiso_inp_estruc_fis").val().length<7){mostraralertasconfoco("Cadena de Estructura incompleta, Ingrese 7 caracteres","#rpiso_inp_estruc"); return false}
    if($("#rpiso_inp_aconst_fis").val()==""){mostraralertasconfoco("Ingresar Area Construida","#rpiso_inp_aconst"); return false}
    if($("#rpiso_inp_acomun_fis").val()==""){$("#rpiso_inp_acomun_fis").val(0);}
    MensajeDialogLoadAjax('dlg_reg_piso', '.:: Guardando ...');
    Id_fic=$("#dlg_idfic").val();
    id_pis=$("#dlg_idpiso").val();
    $("#rpiso_inp_estruc_fis").val().toUpperCase();
    carta=$('#table_cartas').jqGrid ('getGridParam', 'selrow');
    $.ajax({url: 'piso_fisca/create',
    type: 'GET',
    data:{nro:$("#rpiso_inp_nro_fis").val(),fech:$("#rpiso_inp_fech_fis").val(),clasi:$("#rpiso_inp_clasi_fis").val(),
    mep:$("#rpiso_inp_mat_fis").val(),estconserv:$("#rpiso_inp_econserv_fis").val(),estconst:$("#rpiso_inp_econstr_fis").val(),
    estru:$("#rpiso_inp_estruc_fis").val().toUpperCase(),aconst:$("#rpiso_inp_aconst_fis").val(),acomun:$("#rpiso_inp_acomun_fis").val(),id_fic:Id_fic,id_pis:id_pis,carta:carta},
    success: function(r) 
    {
        if(r==0)
        {
            MensajeAlerta("No se Puede Modificar","ya existe una hoja de Liquidación relacionada...",4000);
        }
        if(r==-1)
        {
            MensajeAlerta("No se Puede Modificar","La Carta Relacionada está Anulada...",4000);
        }
        if(r>0)
        {
            MensajeExito("Insertó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            Id_pre=$("#dlg_idpre").val();
            Id_fic=$("#dlg_idfic").val();
            jQuery("#table_pisos").jqGrid('setGridParam', {url: 'traepisos_fic/'+Id_pre+'/'+Id_fic}).trigger('reloadGrid');
        }
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
        if($("#rpiso_inp_nro_fis").val()==""){mostraralertasconfoco("Ingresar Nro Piso","#rpiso_inp_nro"); return false}
        if($("#rpiso_inp_fech_fis").val()==""){mostraralertasconfoco("Ingresar Año de Construccion del Piso","#rpiso_inp_fech"); return false}
        if($("#rpiso_inp_fech_fis").val()<1800){mostraralertasconfoco("Ingresar Año de Construccion Valido de 4 cifras","#rpiso_inp_fech"); return false}
        if($("#rpiso_inp_estruc_fis").val()==""){mostraralertasconfoco("Ingresar Estructuras","#rpiso_inp_estruc"); return false}
        if($("#rpiso_inp_estruc_fis").val().length<7){mostraralertasconfoco("Cadena de Estructura incompleta, Ingrese 7 caracteres","#rpiso_inp_estruc"); return false}
        if($("#rpiso_inp_aconst_fis").val()==""){mostraralertasconfoco("Ingresar Area Construida","#rpiso_inp_aconst"); return false}
        if($("#rpiso_inp_acomun_fis").val()==""){mostraralertasconfoco("Ingresar Area Común","#rpiso_inp_acomun"); return false}
        MensajeDialogLoadAjax('dlg_reg_piso', '.:: Modificando ...');
        carta=$('#table_cartas').jqGrid ('getGridParam', 'selrow');
        $.ajax({url: 'piso_fisca/'+$("#dlg_idpiso_fis").val()+'/edit',
        type: 'GET',
        data:{nro:$("#rpiso_inp_nro_fis").val(),fech:$("#rpiso_inp_fech_fis").val(),clasi:$("#rpiso_inp_clasi_fis").val(),
        mep:$("#rpiso_inp_mat_fis").val(),estconserv:$("#rpiso_inp_econserv_fis").val(),estconst:$("#rpiso_inp_econstr_fis").val(),
        estru:$("#rpiso_inp_estruc_fis").val().toUpperCase(),aconst:$("#rpiso_inp_aconst_fis").val(),acomun:$("#rpiso_inp_acomun_fis").val(),carta:carta},
        success: function(r) 
        {
            if(r==0)
            {
                MensajeAlerta("No se Puede Modificar","ya existe una hoja de Liquidación relacionada...",4000);
            }
            if(r==-1)
            {
                MensajeAlerta("No se Puede Modificar","La Carta Relacionada está Anulada...",4000);
            }
            if(r>0)
            {
                MensajeExito("Modificó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
                Id_pre=$("#dlg_idpre").val();
                Id_fic=$("#dlg_idfic").val();
                jQuery("#table_pisos").jqGrid('setGridParam', {url: 'traepisos_fic/'+Id_pre+'/'+Id_fic}).trigger('reloadGrid');
            }
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
                                     if(extra==2)
                                     {
                                        $( "#rinst_inp_largo_fis,#rinst_inp_ancho_fis,#rinst_inp_alto_fis,#rinst_inp_canti_fis" ).val(0);
                                        $("#rinst_inp_undmed_fis").val(ui.item.und);
                                        if(ui.item.und=="UND")
                                        {
                                            $( "#rinst_inp_largo_fis,#rinst_inp_ancho_fis,#rinst_inp_alto_fis" ).prop( "disabled", true );
                                            $( "#rinst_inp_canti_fis" ).prop( "disabled", false );
                                        }
                                        if(ui.item.und=="M2" || ui.item.und=="ML")
                                        {
                                            $( "#rinst_inp_largo_fis,#rinst_inp_ancho_fis" ).prop( "disabled", false );
                                            $( "#rinst_inp_canti_fis,#rinst_inp_alto_fis" ).prop( "disabled", true );
                                        }
                                        $("#"+textbox+"_cod").val(ui.item.codi);
                                     }
                                      return false;
                              }   
                          });             
                    }
            });
        }
}
function creardlginst()
{
    autocompletar=0;
    auto_input("rinst_inp_des_fis","autocompletar_insta",2);
    autocompletar=1;
 $("#dlg_reg_inst").dialog({
    autoOpen: false, modal: true, width: 1200, show: {effect: "fade", duration: 300}, resizable: false,
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
   if($('#dlg_idfic').val()==0)
    {
        mostraralertas("Primero Guardar Predio...");
        return false;
    }
    limpiaintsdeclara();
    limpiarinstfic();
    creardlginst();
    $("#btninstsave").show();
    $("#btninstmod").hide();
    
}
function limpiaintsdeclara()
{
    $("#hidden_rinst_inp_des").val(0)
    
    $("#rinst_inp_des,#rinst_inp_des_cod,#rinst_inp_undmed,#rinst_inp_anio,#rinst_inp_largo,#rinst_inp_ancho,#rinst_inp_alto,#rinst_inp_canti").val("")
    $("#rinst_inp_mat").val($("#rinst_inp_mat option:first").val());
    $("#rinst_inp_econserv").val($("#rinst_inp_econserv option:first").val());
    $("#rinst_inp_econstr").val($("#rinst_inp_econstr option:first").val());
    $("#rinst_inp_clasi").val($("#rinst_inp_clasi option:first").val());
    callchangeoption("rinst_inp_mat",0);
    callchangeoption("rinst_inp_econserv",0);
    callchangeoption("rinst_inp_econstr",0);
    callchangeoption("rinst_inp_clasi",0);

}
function limpiarinstfic()
{
    $( "#rinst_inp_largo_fis,#rinst_inp_ancho_fis,#rinst_inp_alto_fis,#rinst_inp_canti_fis" ).prop( "disabled", true );
    $("#rinst_inp_des_fis,#rinst_inp_des_fis_cod,#rinst_inp_undmed_fis,#rinst_inp_anio_fis,#rinst_inp_largo_fis,#rinst_inp_ancho_fis,#rinst_inp_alto_fis,#rinst_inp_canti_fis").val("")
    $("#rinst_inp_mat_fis").val($("#rinst_inp_mat_fis option:first").val());
    $("#rinst_inp_econserv_fis").val($("#rinst_inp_econserv_fis option:first").val());
    $("#rinst_inp_econstr_fis").val($("#rinst_inp_econstr_fis option:first").val());
    $("#rinst_inp_clasi_fis").val($("#rinst_inp_clasi_fis option:first").val());
    callchangeoption("rinst_inp_mat_fis",0);
    callchangeoption("rinst_inp_econserv_fis",0);
    callchangeoption("rinst_inp_econstr_fis",0);
    callchangeoption("rinst_inp_clasi_fis",0); 
}
function clickmodinst()
{
    limpiaintsdeclara();
    limpiarinstfic();
    if($('#dlg_idfic').val()==0)
    {
        mostraralertas("Primero Guardar Predio...");
        return false;
    }
    Id=$('#table_instal').jqGrid ('getGridParam', 'selrow');
    $('#dlg_idinst').val(Id);
    creardlginst();
    id_inst_fic=$('#table_instal').jqGrid('getCell',Id,'id_inst_fic');
    if(id_inst_fic>0)
    {
        $("#btninstsave").hide();
        $("#btninstmod").show();
        $("#dlg_idinst_fis").val(id_inst_fic);
        traerinstfic(id_inst_fic);
    }
    else
     {
        $("#btninstsave").show();
        $("#btninstmod").hide();
        $("#dlg_idinst_fis").val(0);
    } 

    MensajeDialogLoadAjax('dlg_reg_inst', '.:: Cargando ...');
    if(Id.slice(0,1)>0)
    {
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
            $( "#rinst_inp_canti" ).val(r[0].pro_tot);

        }
        if(r[0].unid_medida=="M2" || r[0].unid_medida=="ML")
        {
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
}
function traerinstfic(Id)
{
   $.ajax({url: 'insta_fisca/'+Id,
    type: 'GET',
    success: function(r) 
    {
        $("#rinst_inp_des_fis_cod").val(r[0].cod_instal);
        $("#rinst_inp_des_fis").val(r[0].descrip_instal);
        $("#hidden_rinst_inp_des_fis").val(r[0].id_instal);
        $("#rinst_inp_undmed_fis").val(r[0].unid_medida);
        if(r[0].unid_medida=="UND")
        {
            $( "#rinst_inp_largo_fis,#rinst_inp_ancho_fis,#rinst_inp_alto_fis" ).prop( "disabled", true );
            $( "#rinst_inp_canti_fis" ).prop( "disabled", false );
            $( "#rinst_inp_canti_fis" ).val(r[0].pro_tot);

        }
        if(r[0].unid_medida=="M2" || r[0].unid_medida=="ML")
        {
            $( "#rinst_inp_largo_fis,#rinst_inp_ancho_fis" ).prop( "disabled", false );
            $( "#rinst_inp_canti_fis,#rinst_inp_alto_fis" ).prop( "disabled", true );
            $( "#rinst_inp_canti_fis" ).val(0);
        }
        $("#rinst_inp_anio_fis").val(r[0].anio);
        $("#rinst_inp_mat_fis").val(r[0].mep);
        $("#rinst_inp_econserv_fis").val(r[0].ecs);
        $("#rinst_inp_econstr_fis").val(parseInt(r[0].ecc));
        $("#rinst_inp_largo_fis").val(r[0].dim_lar);
        $("#rinst_inp_ancho_fis").val(r[0].dim_anch);
        $("#rinst_inp_alto_fis").val(r[0].dim_alt);
        $("#rinst_inp_clasi_fis").val(r[0].id_cla)
        callchangeoption("rinst_inp_mat_fis",0);
        callchangeoption("rinst_inp_econserv_fis",0);
        callchangeoption("rinst_inp_econstr_fis",0);
        callchangeoption("rinst_inp_clasi_fis",0);
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
    if($("#hidden_rinst_inp_des_fis").val()==0){mostraralertasconfoco("seleccionar Instalación","#rinst_inp_des_fis"); return false}
    if($("#rinst_inp_anio_fis").val()==""){mostraralertasconfoco("Ingresar Año de Construcción","#rinst_inp_anio_fis"); return false}
    if($("#rinst_inp_largo_fis").val()==""){mostraralertasconfoco("Ingresar largo","#rinst_inp_largo_fis"); return false}
    if($("#rinst_inp_ancho_fis").val()==""){mostraralertasconfoco("Ingresar Ancho","#rinst_inp_ancho_fis"); return false}
    if($("#rinst_inp_alto_fis").val()==""){mostraralertasconfoco("Ingresar Alto","#rinst_inp_alto_fis"); return false}
    MensajeDialogLoadAjax('dlg_reg_inst', '.:: Guardando ...');
    Id_fic=$("#dlg_idfic").val();
    id_ins=$("#dlg_idinst").val();
    carta=$('#table_cartas').jqGrid ('getGridParam', 'selrow');
    $.ajax({url: 'insta_fisca/create',
    type: 'GET',
    data:{inst:$("#hidden_rinst_inp_des_fis").val(),anio:$("#rinst_inp_anio_fis").val(),largo:$("#rinst_inp_largo_fis").val(),
        ancho:$("#rinst_inp_ancho_fis").val(),alto:$("#rinst_inp_alto_fis").val(),mep:$("#rinst_inp_mat_fis").val(),
        ecs:$("#rinst_inp_econserv_fis").val(),ecc:$("#rinst_inp_econstr_fis").val(),id_fic:Id_fic,cla:$("#rinst_inp_clasi_fis").val(),
        cant:$("#rinst_inp_canti_fis").val(),ins:id_ins,carta:carta},
    success: function(r) 
    {
        if(r==0)
        {
            MensajeAlerta("No se Puede Modificar","ya existe una hoja de Liquidación relacionada...",4000);
        }
        if(r==-1)
        {
            MensajeAlerta("No se Puede Modificar","La Carta Relacionada está Anulada...",4000);
        }
        if(r>0)
        {
            MensajeExito("Insertó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            Id_pre=$("#dlg_idpre").val();
            jQuery("#table_instal").jqGrid('setGridParam', {url: 'traeinsta_fic/'+Id_pre+'/'+Id_fic}).trigger('reloadGrid');
        }
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
    if($("#hidden_rinst_inp_des_fis").val()==0){mostraralertasconfoco("seleccionar Instalación","#rinst_inp_des_fis"); return false}
    if($("#rinst_inp_anio_fis").val()==""){mostraralertasconfoco("Ingresar Año de Construcción","#rinst_inp_anio_fis"); return false}
    if($("#rinst_inp_largo_fis").val()==""){mostraralertasconfoco("Ingresar largo","#rinst_inp_largo_fis"); return false}
    if($("#rinst_inp_ancho_fis").val()==""){mostraralertasconfoco("Ingresar Ancho","#rinst_inp_ancho_fis"); return false}
    if($("#rinst_inp_alto_fis").val()==""){mostraralertasconfoco("Ingresar Alto","#rinst_inp_alto_fis"); return false}
    MensajeDialogLoadAjax('dlg_reg_inst', '.:: Modificando ...');
    carta=$('#table_cartas').jqGrid ('getGridParam', 'selrow');
    $.ajax({url: 'insta_fisca/'+$('#dlg_idinst_fis').val()+'/edit',
    type: 'GET',
    data:{inst:$("#hidden_rinst_inp_des_fis").val(),anio:$("#rinst_inp_anio_fis").val(),largo:$("#rinst_inp_largo_fis").val(),
        ancho:$("#rinst_inp_ancho_fis").val(),alto:$("#rinst_inp_alto_fis").val(),mep:$("#rinst_inp_mat_fis").val(),
        ecs:$("#rinst_inp_econserv_fis").val(),ecc:$("#rinst_inp_econstr_fis").val(),cla:$("#rinst_inp_clasi_fis").val(),
        cant:$("#rinst_inp_canti_fis").val(),carta:carta},
    success: function(r) 
    {
        if(r==0)
        {
            MensajeAlerta("No se Puede Modificar","ya existe una hoja de Liquidación relacionada...",4000);
        }
        if(r==-1)
        {
            MensajeAlerta("No se Puede Modificar","La Carta Relacionada está Anulada...",4000);
        }
        if(r>0)
        {
            MensajeExito("Se Modificó Correctamente","Su Registro Fue Insertado con Éxito...",4000);
            Id_pre=$("#dlg_idpre").val();
            Id_fic=$("#dlg_idfic").val();
            jQuery("#table_instal").jqGrid('setGridParam', {url: 'traeinsta_fic/'+Id_pre+'/'+Id_fic}).trigger('reloadGrid');
        }
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
function viewlong()
{
    $("#dlg_img_view_big").html("");
    $("#dlg_view_foto").dialog({
    autoOpen: false, modal: true, width: 1000, show: {effect: "fade", duration: 300}, resizable: false,
    title: "<div class='widget-header'><h4>.:  Foto del Predio :.</h4></div>",
    }).dialog('open');
    $("#dlg_img_view_big").html($("#dlg_img_view").html());
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