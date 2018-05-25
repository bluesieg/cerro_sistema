
function env_coactiva(tip,id){
    MensajeDialogLoadAjax('tabla_Doc_OP', '.:: CARGANDO ...');
    MensajeDialogLoadAjax('tabla_Doc_OP_2', '.:: CARGANDO ...');
    $.ajax({
        url:'updat_env_doc',
        type:'GET',
        data:{id_gen_fis:id,env_op:tip},
        success:function(data)
        {
            MensajeExito("Se envió Correctamente","Su Registro Fue Eviado con Éxito...",4000);
            
            fn_actualizar_grilla('tabla_Doc_OP','recaudacion_get_op/'+$("#selantra").val()+"/0");
            fn_actualizar_grilla('tabla_Doc_OP_2','recaudacion_get_op/'+$("#selantra").val()+"/1");
            MensajeDialogLoadAjaxFinish('tabla_Doc_OP');
            MensajeDialogLoadAjaxFinish('tabla_Doc_OP_2');
        },
        error: function(){}
    }); 
}


function all_right(){    
    var rows = $("#tabla_Doc_OP").getDataIDs();
    if (rows.length > 0) {
        MensajeDialogLoadAjax('content','Cargando');
        
        var i = 0;
        var id = window.setInterval(function(){
            if(i >= (rows.length)) {
                clearInterval(id);
                return;
            }            
            all_update_env_op(rows[i],2);
            console.log(i);
            i++;
        }, 1000);

      
    }
}
function all_left(){    
    var rows = $("#tabla_Doc_OP_2").getDataIDs();
    if (rows.length > 0) {
        MensajeDialogLoadAjax('content','Cargando');
//        MensajeDialogLoadAjax('tabla_Doc_OP','Recibiendo...');
//        MensajeDialogLoadAjax('tabla_Doc_OP_2','Enviando...');
        var i=0;
        for(i=0;i<=(rows.length)-1;i++){
            all_update_env_op(rows[i],1);
        }
        setTimeout(function () {
            if($("input:radio[name='myradio']:checked").val()==1){
                fn_actualizar_grilla('tabla_Doc_OP','recaudacion_get_op?env_op=1&tip_bus='+$("input:radio[name='myradio']:checked").val()+
                    '&desde='+$("#vw_env_doc_fdesde").val()+'&hasta='+$("#vw_env_doc_fhasta").val());
                fn_actualizar_grilla('tabla_Doc_OP_2','recaudacion_get_op?env_op=2&tip_bus='+$("input:radio[name='myradio']:checked").val()+
                    '&desde='+$("#vw_env_doc_fdesde").val()+'&hasta='+$("#vw_env_doc_fhasta").val()+'&grid=2');
            }else if($("input:radio[name='myradio']:checked").val()==2){
                fn_actualizar_grilla('tabla_Doc_OP','recaudacion_get_op?env_op=1&tip_bus='+$("input:radio[name='myradio']:checked").val()+
                    '&del='+$("#vw_env_doc_nrode").val()+'&al='+$("#vw_env_doc_nroa").val());
                fn_actualizar_grilla('tabla_Doc_OP_2','recaudacion_get_op?env_op=2&tip_bus='+$("input:radio[name='myradio']:checked").val()+
                    '&del='+$("#vw_env_doc_nrode").val()+'&al='+$("#vw_env_doc_nroa").val()+'&grid=2');
            }
//            MensajeDialogLoadAjaxFinish('tabla_Doc_OP');
//            MensajeDialogLoadAjaxFinish('tabla_Doc_OP_2');
            MensajeDialogLoadAjaxFinish('content');
        }, 1100);
    }
}
function all_update_env_op(id_gen_fis,env_op){     
    $.ajax({
        url:'updat_env_doc',
        type:'GET',
        data:{id_gen_fis:id_gen_fis,env_op:env_op,doc_ini:2},
        success:function(data){},
        error: function(){}
    });    
}
function update_env_op(id_gen_fis,env_op){
    $.ajax({
        url:'updat_env_doc',
        type:'GET',
        data:{id_gen_fis:id_gen_fis,env_op:env_op,doc_ini:2},
        success:function(data){
            if($("input:radio[name='myradio']:checked").val()==1){
                fn_actualizar_grilla('tabla_Doc_OP','recaudacion_get_op?env_op=1&tip_bus='+$("input:radio[name='myradio']:checked").val()+
                    '&desde='+$("#vw_env_doc_fdesde").val()+'&hasta='+$("#vw_env_doc_fhasta").val());
                fn_actualizar_grilla('tabla_Doc_OP_2','recaudacion_get_op?env_op=2&tip_bus='+$("input:radio[name='myradio']:checked").val()+
                    '&desde='+$("#vw_env_doc_fdesde").val()+'&hasta='+$("#vw_env_doc_fhasta").val()+'&grid=2');
            }else if($("input:radio[name='myradio']:checked").val()==2){
                fn_actualizar_grilla('tabla_Doc_OP','recaudacion_get_op?env_op=1&tip_bus='+$("input:radio[name='myradio']:checked").val()+
                    '&del='+$("#vw_env_doc_nrode").val()+'&al='+$("#vw_env_doc_nroa").val());
                fn_actualizar_grilla('tabla_Doc_OP_2','recaudacion_get_op?env_op=2&tip_bus='+$("input:radio[name='myradio']:checked").val()+
                    '&del='+$("#vw_env_doc_nrode").val()+'&al='+$("#vw_env_doc_nroa").val()+'&grid=2');
            }
        },
        error: function(){}
    });     
}
function radio_click(val){
    if(val==1){
        $("#vw_env_doc_fdesde,#vw_env_doc_fhasta,#vw_env_doc_btn1").attr('disabled',false);
        $("#vw_env_doc_nrode,#vw_env_doc_nroa,#vw_env_doc_btn2").attr('disabled',true);
        $("#vw_env_doc_nrode,#vw_env_doc_nroa,#vw_env_doc_btn2").val('');
    }else if (val==2){
        $("#vw_env_doc_fdesde,#vw_env_doc_fhasta,#vw_env_doc_btn1").attr('disabled',true);        
        $("#vw_env_doc_nrode,#vw_env_doc_nroa,#vw_env_doc_btn2").attr('disabled',false);
    }
}
function print_op(){
    rows = $("#tabla_Doc_OP_2").getRowData().length;
    if(rows!=0){
        window.open("listado_op");
    }    
}
function fn_up_grid(){
    if($("input:radio[name='myradio']:checked").val()==1){
        fn_actualizar_grilla('tabla_Doc_OP','recaudacion_get_op?env_op=1&tip_bus='+$("input:radio[name='myradio']:checked").val()+
            '&desde='+$("#vw_env_doc_fdesde").val()+'&hasta='+$("#vw_env_doc_fhasta").val());
        fn_actualizar_grilla('tabla_Doc_OP_2','recaudacion_get_op?env_op=2&tip_bus='+$("input:radio[name='myradio']:checked").val()+
            '&desde='+$("#vw_env_doc_fdesde").val()+'&hasta='+$("#vw_env_doc_fhasta").val()+'&grid=2');
    }else if($("input:radio[name='myradio']:checked").val()==2){
        if($("#vw_env_doc_nrode").val()=='' || $("#vw_env_doc_nroa").val()==''){mostraralertas('Ingrese Valores...<br>Ejemplo: Del 1 - Al 10');return false;}
        fn_actualizar_grilla('tabla_Doc_OP','recaudacion_get_op?env_op=1&tip_bus='+$("input:radio[name='myradio']:checked").val()+
            '&del='+$("#vw_env_doc_nrode").val()+'&al='+$("#vw_env_doc_nroa").val());
        fn_actualizar_grilla('tabla_Doc_OP_2','recaudacion_get_op?env_op=2&tip_bus='+$("input:radio[name='myradio']:checked").val()+
            '&del='+$("#vw_env_doc_nrode").val()+'&al='+$("#vw_env_doc_nroa").val()+'&grid=2');
    }
    
}
function fn_bus_contrib_env_doc(){  
    if($("#vw_env_doc_contrib").val()==""){
        mostraralertasconfoco("Ingrese un Contribuyente para Buscar","#vw_env_doc_contrib"); 
        return false;
    }
    if($("#vw_env_doc_contrib").val().length<4){
        mostraralertasconfoco("Ingresar al menos 4 caracteres de busqueda","#vw_env_doc_contrib"); 
        return false;
    }

    fn_actualizar_grilla('table_contrib','obtiene_cotriname?dat='+$("#vw_env_doc_contrib").val());
    jQuery('#table_contrib').jqGrid('bindKeys', {"onEnter":function( rowid ){fn_bus_contrib_list_env_doc(rowid);} } ); 
    $("#dlg_bus_contr").dialog({
        autoOpen: false, modal: true, width: 500, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  Busqueda de Contribuyente :.</h4></div>"       
        }).dialog('open');       
}

function fn_bus_contrib_list_env_doc(per){
    $("#hidden_vw_env_doc_codigo").val(per);
    
    $("#vw_env_doc_codigo").val($('#table_contrib').jqGrid('getCell',per,'id_per'));    
    $("#vw_env_doc_contrib").val($('#table_contrib').jqGrid('getCell',per,'contribuyente'));
    tam=($('#table_contrib').jqGrid('getCell',per,'contribuyente')).length;
    
    $("#vw_env_doc_contrib").attr('maxlength',tam);

    if($("input:radio[name='myradio']:checked").val()==1){
        fn_actualizar_grilla('tabla_Doc_OP','recaudacion_get_op?env_op=1&tip_bus='+$("input:radio[name='myradio']:checked").val()+
            '&desde='+$("#vw_env_doc_fdesde").val()+'&hasta='+$("#vw_env_doc_fhasta").val());
        fn_actualizar_grilla('tabla_Doc_OP_2','recaudacion_get_op?env_op=2&tip_bus='+$("input:radio[name='myradio']:checked").val()+
            '&desde='+$("#vw_env_doc_fdesde").val()+'&hasta='+$("#vw_env_doc_fhasta").val()+'&grid=2');
    }else if($("input:radio[name='myradio']:checked").val()==2){
        fn_actualizar_grilla('tabla_Doc_OP','recaudacion_get_op?env_op=1&tip_bus='+$("input:radio[name='myradio']:checked").val()+
            '&del='+$("#vw_env_doc_nrode").val()+'&al='+$("#vw_env_doc_nroa").val());
        fn_actualizar_grilla('tabla_Doc_OP_2','recaudacion_get_op?env_op=2&tip_bus='+$("input:radio[name='myradio']:checked").val()+
            '&del='+$("#vw_env_doc_nrode").val()+'&al='+$("#vw_env_doc_nroa").val()+'&grid=2');
    }
    $("#dlg_bus_contr").dialog("close");    
}

function verop(idop)
{
    Id=$('#tabla_Doc_Fisca').jqGrid ('getGridParam', 'selrow');
    if(Id==null){
        mostraralertas("No hay Contribuyente seleccionado para impresión");
        return false;
    }    

    $("#dlg_iframe_op").dialog({
        autoOpen: false, modal: true, width: 910,heigth: 580, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  OP :.</h4></div>",
        open: function(ev, ui){
            $('#myIframe_op').attr('src','fis_rep/1/'+idop+'/0/0');
        }
    }).dialog('open');
    MensajeDialogLoadAjax('dlg_iframe_op','Cargando...');
    
    setTimeout(function () {
        MensajeDialogLoadAjaxFinish('dlg_iframe_op');
    }, 1100);
    
//    window.open('fis_rep/1/'+idop+'/0/0');
}

