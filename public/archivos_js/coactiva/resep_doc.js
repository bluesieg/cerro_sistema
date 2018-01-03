
function up_resep_doc(){
    tip_bus=$("input:radio[name='myradio_resep_doc']:checked").val();
    if(tip_bus==1){
        fn_actualizar_grilla('t_recep_doc','coactiva_recep_doc?tip_bus='+tip_bus+'&tip_doc='+$("#vw_recep_doc_tip_doc").val()+'&desde='+$("#vw_resep_doc_fdesde").val()+'&hasta='+$("#vw_resep_doc_fhasta").val());
    }else if(tip_bus==2){
        if($("#vw_resep_doc_nrode").val()=='' || $("#vw_resep_doc_nroa").val()==''){mostraralertas('Ingrese Valores...<br>Ejemplo: Del 1 - Al 10');return false;}
        fn_actualizar_grilla('t_recep_doc','coactiva_recep_doc?tip_bus='+tip_bus+'&tip_doc='+$("#vw_recep_doc_tip_doc").val()
                +'&del='+$("#vw_resep_doc_nrode").val()+'&al='+$("#vw_resep_doc_nroa").val());
    }    
}
function recibir_doc(){
    cont_rows=jQuery("#t_recep_doc").jqGrid('getGridParam', 'records');
    var Seleccionados = new Array();
    $('input[type=checkbox][name=chk_recib_doc]:checked').each(function() {
        Seleccionados.push($(this).val());
    });
    cant=Seleccionados.length;
    recib_doc = Seleccionados.join('-');
    
    if(cont_rows==0 || cant==0){ return false; }    
    
    $.confirm({
        title: 'Coactiva',
        content: 'Recibir Documentos Seleccionados',
        buttons: {
            Confirmar: function () {
                if($("#vw_recep_doc_tip_doc").val()==2){
                    $.ajax({
                        url:'recib_doc_coactiva',
                        type:'GET',
                        data:{id_gen_fis:recib_doc},
                        success:function(data){
                            if(data.msg=='si'){
                                MensajeExito('COACTIVA','Documentos Recibidos.');
                                up_resep_doc();
                            }
                        },
                        error: function(){}
                    });
                }else if($("#vw_recep_doc_tip_doc").val()==1){
                    $.ajax({
                        url:'recib_doc_coactiva_rd',
                        type:'GET',
                        data:{id_rd:recib_doc},
                        success:function(data){
                            if(data.msg=='si'){
                                MensajeExito('COACTIVA','Documentos Recibidos.');
                                up_resep_doc();
                            }
                        },
                        error: function(){}
                    });
                }
                
            },
            Cancelar: function () {} 
        }
    });    
}
function check_all_resep_doc(){
    $("#chk_sel_todo_doc").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });
}
function radio_click_resep_doc(val){
    if(val==1){
        $("#vw_resep_doc_fdesde,#vw_resep_doc_fhasta,#vw_resep_doc_btn1").attr('disabled',false);
        $("#vw_resep_doc_nrode,#vw_resep_doc_nroa,#vw_resep_doc_btn2").attr('disabled',true);
        $("#vw_resep_doc_nrode,#vw_resep_doc_nroa,#vw_resep_doc_btn2").val('');
    }else if (val==2){
        $("#vw_resep_doc_fdesde,#vw_resep_doc_fhasta,#vw_resep_doc_btn1").attr('disabled',true);
        $("#vw_resep_doc_nrode,#vw_resep_doc_nroa,#vw_resep_doc_btn2").attr('disabled',false);        
    }
}
function fn_bus_contrib_recep_doc(){  
    if($("#vw_recep_doc_contrib").val()==""){
        mostraralertasconfoco("Ingrese un Contribuyente para Buscar","#vw_recep_doc_contrib"); 
        return false;
    }
    if($("#vw_recep_doc_contrib").val().length<4){
        mostraralertasconfoco("Ingresar al menos 4 caracteres de busqueda","#vw_recep_doc_contrib"); 
        return false;
    }

    fn_actualizar_grilla('table_contrib','obtiene_cotriname?dat='+$("#vw_recep_doc_contrib").val());
    jQuery('#table_contrib').jqGrid('bindKeys', {"onEnter":function( rowid ){fn_bus_contrib_list_resep_doc(rowid);} } ); 
    $("#dlg_bus_contr").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  Busqueda de Contribuyente :.</h4></div>"       
        }).dialog('open');
}

function fn_bus_contrib_list_resep_doc(per){
    $("#hidden_vw_recep_doc_codigo").val(per);
    
    $("#vw_recep_doc_codigo").val($('#table_contrib').jqGrid('getCell',per,'id_per'));    
    $("#vw_recep_doc_contrib").val($('#table_contrib').jqGrid('getCell',per,'contribuyente'));
    tam=($('#table_contrib').jqGrid('getCell',per,'contribuyente')).length;
    
    $("#vw_recep_doc_contrib").attr('maxlength',tam);

    fn_actualizar_grilla('tabla_Doc_OP','recaudacion_get_op?id_contrib='+$("#hidden_vw_recep_doc_codigo").val()+'&env_op=2');
    $("#dlg_bus_contr").dialog("close");    
}
