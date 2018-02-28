
function rd_right(){
    cont_rows=jQuery("#tabla_Doc_RD").jqGrid('getGridParam', 'records');
    id_rd=$('#tabla_Doc_RD').jqGrid ('getGridParam', 'selrow');
    if(cont_rows==0 || id_rd==null){return false;}
    update_env_rd(id_rd,2);
}
function rd_left(){
    cont_rows=jQuery("#tabla_Doc_RD_2").jqGrid('getGridParam', 'records');
    id_rd=$('#tabla_Doc_RD_2').jqGrid ('getGridParam', 'selrow');
    if(cont_rows==0 || id_rd==null){return false;}
    update_env_rd(id_rd,1);
}
function rd_all_right(){    
    var rows = $("#tabla_Doc_RD").getDataIDs();
    if (rows.length > 0) {
        MensajeDialogLoadAjax('content','Cargando');
        
        var i = 0;
        var id = window.setInterval(function(){
            if(i >= (rows.length)) {
                clearInterval(id);
                return;
            }            
            all_update_env_rd(rows[i],2);
            console.log(i);
            i++;
        }, 1000);

        setTimeout(function () {
            if($("input:radio[name='myradio_rd']:checked").val()==1){
                fn_actualizar_grilla('tabla_Doc_RD','fisca_get_rd?env_rd=1&tip_bus='+$("input:radio[name='myradio_rd']:checked").val()+
                    '&desde='+$("#vw_env_rd_fdesde").val()+'&hasta='+$("#vw_env_rd_fhasta").val());
                fn_actualizar_grilla('tabla_Doc_RD_2','fisca_get_rd?env_rd=2&tip_bus='+$("input:radio[name='myradio_rd']:checked").val()+
                    '&desde='+$("#vw_env_rd_fdesde").val()+'&hasta='+$("#vw_env_rd_fhasta").val()+'&grid=2');
            }else if($("input:radio[name='myradio_rd']:checked").val()==2){
                fn_actualizar_grilla('tabla_Doc_RD','fisca_get_rd?env_rd=1&tip_bus='+$("input:radio[name='myradio_rd']:checked").val()+
                    '&del='+$("#vw_env_rd_nrode").val()+'&al='+$("#vw_env_rd_nroa").val());
                fn_actualizar_grilla('tabla_Doc_RD_2','fisca_get_rd?env_rd=2&tip_bus='+$("input:radio[name='myradio_rd']:checked").val()+
                    '&del='+$("#vw_env_rd_nrode").val()+'&al='+$("#vw_env_rd_nroa").val()+'&grid=2');
            }
            MensajeDialogLoadAjaxFinish('content');
        }, (rows.length+1)*1000);
    }
}
function rd_all_left(){    
    var rows = $("#tabla_Doc_RD_2").getDataIDs();
    if (rows.length > 0) {
        MensajeDialogLoadAjax('content','Cargando');
        var i=0;
        for(i=0;i<=(rows.length)-1;i++){
            all_update_env_rd(rows[i],1);
        }
        setTimeout(function () {
            if($("input:radio[name='myradio_rd']:checked").val()==1){
                fn_actualizar_grilla('tabla_Doc_RD','fisca_get_rd?env_rd=1&tip_bus='+$("input:radio[name='myradio_rd']:checked").val()+
                    '&desde='+$("#vw_env_rd_fdesde").val()+'&hasta='+$("#vw_env_rd_fhasta").val());
                fn_actualizar_grilla('tabla_Doc_RD_2','fisca_get_rd?env_rd=2&tip_bus='+$("input:radio[name='myradio_rd']:checked").val()+
                    '&desde='+$("#vw_env_rd_fdesde").val()+'&hasta='+$("#vw_env_rd_fhasta").val()+'&grid=2');
            }else if($("input:radio[name='myradio_rd']:checked").val()==2){
                fn_actualizar_grilla('tabla_Doc_RD','fisca_get_rd?env_rd=1&tip_bus='+$("input:radio[name='myradio_rd']:checked").val()+
                    '&del='+$("#vw_env_rd_nrode").val()+'&al='+$("#vw_env_rd_nroa").val());
                fn_actualizar_grilla('tabla_Doc_RD_2','fisca_get_rd?env_rd=2&tip_bus='+$("input:radio[name='myradio_rd']:checked").val()+
                    '&del='+$("#vw_env_rd_nrode").val()+'&al='+$("#vw_env_rd_nroa").val()+'&grid=2');
            }
            MensajeDialogLoadAjaxFinish('content');
        }, 1100);
    }
}
function all_update_env_rd(id_rd,env_rd){     
    $.ajax({
        url:'update_env_rd',
        type:'GET',
        data:{id_rd:id_rd,env_rd:env_rd},
        success:function(data){},
        error: function(){}
    });    
}
function update_env_rd(id_rd,env_rd){
   
    $.ajax({
        url:'update_env_rd',
        type:'GET',
        data:{id_rd:id_rd,env_rd:env_rd},
        success:function(data){
            if($("input:radio[name='myradio_rd']:checked").val()==1){
                fn_actualizar_grilla('tabla_Doc_RD','fisca_get_rd?env_rd=1&tip_bus='+$("input:radio[name='myradio_rd']:checked").val()+
                    '&desde='+$("#vw_env_rd_fdesde").val()+'&hasta='+$("#vw_env_rd_fhasta").val());
                fn_actualizar_grilla('tabla_Doc_RD_2','fisca_get_rd?env_rd=2&tip_bus='+$("input:radio[name='myradio_rd']:checked").val()+
                    '&desde='+$("#vw_env_rd_fdesde").val()+'&hasta='+$("#vw_env_rd_fhasta").val()+'&grid=2');
            }else if($("input:radio[name='myradio_rd']:checked").val()==2){
                fn_actualizar_grilla('tabla_Doc_RD','fisca_get_rd?env_rd=1&tip_bus='+$("input:radio[name='myradio_rd']:checked").val()+
                    '&del='+$("#vw_env_rd_nrode").val()+'&al='+$("#vw_env_rd_nroa").val());
                fn_actualizar_grilla('tabla_Doc_RD_2','fisca_get_rd?env_rd=2&tip_bus='+$("input:radio[name='myradio_rd']:checked").val()+
                    '&del='+$("#vw_env_rd_nrode").val()+'&al='+$("#vw_env_rd_nroa").val()+'&grid=2');
            }
        },
        error: function(){}
    });     
}
function radio_click_rd(val){
    if(val==1){
        $("#vw_env_rd_fdesde,#vw_env_rd_fhasta,#vw_env_rd_btn1").attr('disabled',false);
        $("#vw_env_rd_nrode,#vw_env_rd_nroa,#vw_env_rd_btn2").attr('disabled',true);
        $("#vw_env_rd_nrode,#vw_env_rd_nroa,#vw_env_rd_btn2").val('');
    }else if (val==2){
        $("#vw_env_rd_fdesde,#vw_env_rd_fhasta,#vw_env_rd_btn1").attr('disabled',true);        
        $("#vw_env_rd_nrode,#vw_env_rd_nroa,#vw_env_rd_btn2").attr('disabled',false);
    }
}

function fn_up_grid_rd(){
    if($("input:radio[name='myradio_rd']:checked").val()==1){
        fn_actualizar_grilla('tabla_Doc_RD','fisca_get_rd?env_rd=1&tip_bus='+$("input:radio[name='myradio_rd']:checked").val()+
            '&desde='+$("#vw_env_rd_fdesde").val()+'&hasta='+$("#vw_env_rd_fhasta").val());
        fn_actualizar_grilla('tabla_Doc_RD_2','fisca_get_rd?env_rd=2&tip_bus='+$("input:radio[name='myradio_rd']:checked").val()+
            '&desde='+$("#vw_env_rd_fdesde").val()+'&hasta='+$("#vw_env_rd_fhasta").val()+'&grid=2');
    }else if($("input:radio[name='myradio_rd']:checked").val()==2){
        if($("#vw_env_rd_nrode").val()=='' || $("#vw_env_rd_nroa").val()==''){mostraralertas('Ingrese Valores...<br>Ejemplo: Del 1 - Al 10');return false;}
        fn_actualizar_grilla('tabla_Doc_RD','fisca_get_rd?env_rd=1&tip_bus='+$("input:radio[name='myradio_rd']:checked").val()+
            '&del='+$("#vw_env_rd_nrode").val()+'&al='+$("#vw_env_rd_nroa").val());
        fn_actualizar_grilla('tabla_Doc_RD_2','fisca_get_rd?env_rd=2&tip_bus='+$("input:radio[name='myradio_rd']:checked").val()+
            '&del='+$("#vw_env_rd_nrode").val()+'&al='+$("#vw_env_rd_nroa").val()+'&grid=2');
    }
}