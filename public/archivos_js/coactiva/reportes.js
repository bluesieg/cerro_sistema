function dlg_reporte_coactivo(tip)
{
    if(tip==1)
    {
         window.open('reporte_ingresos_pdf?fini='+$('#dlg_bus_fini').val()+'&ffin='+$('#dlg_bus_ffin').val());
    }

}

function rango_fecha_rep(){
    desde = $("#vw_rep_coa_fdesde").val();
    hasta = $("#vw_rep_coa_fhasta").val();
    estado = $("#vw_rep_coa_estado").val();
    materia = $("#vw_rep_coa_materia").val();
    valor = $("#vw_rep_coa_valor").val();
    fn_actualizar_grilla('all_tabla_expedientes','rep_exped?desde='+desde+'&hasta='+hasta+'&mat='+materia+'&estado='+estado+'&valor='+valor);
}
function fil_materia(materia){
    $('#vw_rep_coa_valor').prop('options').length = 1;
    desde = $("#vw_rep_coa_fdesde").val();
    hasta = $("#vw_rep_coa_fhasta").val();
    estado = $("#vw_rep_coa_estado").val();
    valor = $("#vw_rep_coa_valor").val();
    fn_actualizar_grilla('all_tabla_expedientes','rep_exped?desde='+desde+'&hasta='+hasta+'&mat='+materia+'&estado='+estado+'&valor='+valor);
    if(materia){
        $("#vw_rep_coa_valor").attr('disabled',false);
        $.ajax({
            url: 'cbo_valores?materia='+materia,
            type: 'GET',
            success: function (data) {
                for (i = 0; i <= data.length - 1; i++) {
                    $('#vw_rep_coa_valor').append('<option value=' + data[i].id_val + '>' + data[i].desc_val + '</option>');
                }
    //            $("#"+input_1).prop("selectedIndex", 2);
            },
            error: function (data) {
                alert(' Error al traer Tipo de Documentos');
            }
        });
    }else{
        $("#vw_rep_coa_valor").attr('disabled',true);
    }
    
}
function fil_estado(estado){
    desde = $("#vw_rep_coa_fdesde").val();
    hasta = $("#vw_rep_coa_fhasta").val();
    materia = $("#vw_rep_coa_materia").val();
    valor = $("#vw_rep_coa_valor").val();
    fn_actualizar_grilla('all_tabla_expedientes','rep_exped?desde='+desde+'&hasta='+hasta+'&mat='+materia+'&estado='+estado+'&valor='+valor);
}
function fil_valor(valor){
    desde = $("#vw_rep_coa_fdesde").val();
    hasta = $("#vw_rep_coa_fhasta").val();
    estado = $("#vw_rep_coa_estado").val();
    materia = $("#vw_rep_coa_materia").val();
    fn_actualizar_grilla('all_tabla_expedientes','rep_exped?desde='+desde+'&hasta='+hasta+'&mat='+materia+'&estado='+estado+'&valor='+valor);
}

function print_report(){
    desde = $("#vw_rep_coa_fdesde").val();
    hasta = $("#vw_rep_coa_fhasta").val();
    estado = $("#vw_rep_coa_estado").val();
    materia = $("#vw_rep_coa_materia").val();
    valor = $("#vw_rep_coa_valor").val();
    estado2 = $("#vw_rep_coa_estado option:selected").text();
    materia2 = $("#vw_rep_coa_materia option:selected").text();
    valor2 = $("#vw_rep_coa_valor option:selected").text();
    window.open('report_exped_coa?desde='+desde+'&hasta='+hasta+'&materia='+materia+'&estado='+estado+'&valor='+valor+'&materia2='+materia2+'&estado2='+estado2+'&valor2='+valor2);
}

