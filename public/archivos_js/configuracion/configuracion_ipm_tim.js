
function guardar_datos() {
    
    id_institucion = $("#id_institucion").val();
    
    if($("#chkbox_ipm").is(':checked')){
       var ipm = 1;
    }else{
        ipm = 0;
    }
    
    if($("#chkbox_tim").is(':checked')){
       var tim = 1;
    }else{
        tim = 0;
    }
    
    MensajeDialogLoadAjax('configuracion', '.:: Cargando ...');
    
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'confg_ipm_tim/'+id_institucion+'/edit',
        type: 'GET',
        data: {
            ipm:ipm,
            tim:tim          
        },
        success: function(data) 
        {
            MensajeExito('OPERACION EXITOSA', 'El registro fue guardado Correctamente');
            MensajeDialogLoadAjaxFinish('configuracion');
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
 
}

function llamar_datos()
{
    id_institucion = $("#id_institucion").val();
        
        MensajeDialogLoadAjax('configuracion', '.:: CARGANDO ::...');
        
        $.ajax({url: 'confg_ipm_tim/'+id_institucion,
            type: 'GET',
            success: function(data)
            {   
                if (data.cobrar_ipm == 1) {
                    $("#chkbox_ipm").prop('checked', true);
                }else{
                    $("#chkbox_ipm").prop('checked', false);
                }
                
                if (data.cobrar_tim == 1) {
                    $("#chkbox_tim").prop('checked', true);
                }else{
                    $("#chkbox_tim").prop('checked', false);
                }
                MensajeDialogLoadAjaxFinish('configuracion');
            },
            error: function(data) {
                mostraralertas("Hubo un Error, Comunicar al Administrador");
                console.log('error');
                console.log(data);
            }
        });
}



