function callfilltab()
{
    fn_actualizar_grilla('tabla_Doc_multa','get_multa/'+$("#selantra").val()+"/0");
    fn_actualizar_grilla('tabla_Doc_multa_2','get_multa/'+$("#selantra").val()+"/1");
}

function env_coactiva(tip,id){
    MensajeDialogLoadAjax('tabla_Doc_multa_2', '.:: CARGANDO ...');
    MensajeDialogLoadAjax('tabla_Doc_multa', '.:: CARGANDO ...');
    $.ajax({
        url:'update_env_multa',
        type:'GET',
        data:{id_multa_reg:id,env_rd:tip},
        success:function(data)
        {
            MensajeExito("Se envió Correctamente","Su Registro Fue Eviado con Éxito...",4000);
            
            callfilltab();
            MensajeDialogLoadAjaxFinish('tabla_Doc_multa');
            MensajeDialogLoadAjaxFinish('tabla_Doc_multa_2');
        },
        error: function(){}
    }); 
}