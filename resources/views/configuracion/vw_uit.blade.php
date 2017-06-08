@extends('layouts.app')

@section('content')


<section id="widget-grid" class="">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -15px">
            <div class="well well-sm well-light">
                <h1 class="txt-color-green"><b>Mantenimiento de UITs...</b></h1>			
               
                <!--<button onclick="open_dialog_new_edit_Usuario('NUEVO', false);" id="btn_vw_usuarios_Nuevo" type="button" class="btn btn-labeled bg-color-greenLight txt-color-white">-->
                <button onclick="open_dialog_nuevo_uit('NUEVO', false);" id="btn_vw_uit_Nuevo" type="button" class="btn btn-labeled bg-color-greenLight txt-color-white">
                    <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                </button>                        
                <button id="btn_vw_uit_Editar" type="button" class="btn btn-labeled bg-color-blue txt-color-white">
                    <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Editar
                </button>
                <button id ="btn_vw_uit_Eliminar" type="button" class="btn btn-labeled btn-danger">
                    <span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span>Eliminar
                </button>
            </div>                   
        </div>
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <table id="table_vw_uit"></table>
            <div id="pager_table_vw_uit"></div>
        </article>
    </div>
</section> 
        
        


<div id="dialog_open_list_uit" style="display: none">
    <table align="center" >
        <tr>
            <td> AÑO : </td>
            <td>
                <input id="txt_anio" type="number" min="1999" max="3000" step="1"  required="required"><p>
            </td>
        </tr>
          <tr>
            <td> UIT : </td>
            <td>
                <input type="number" id="txt_uit" min="0" max="10000" step="0.1"  required="required"><p>
            </td>
        </tr>
        <tr>
            <td> Uit Alcabala : </td>
            <td>
                <input type="number" id="txt_uit_alc" min="0" max="10000" step="0.1"  required="required"><p>
            </td>
        </tr>
         <tr>
            <td> Tasa Alcabala : </td>
            <td>
                <input type="number" id="txt_tas_alc" min="0" max="10000" step="0.1"  required="required"><p>
            </td>
        </tr>
         <tr>
            <td> Formatos : </td>
            <td>
                <input type="number" id="txt_formatos" min="0" max="10000" step="0.1"  required="required"><p>
            </td>
            <td> &nbsp &nbsp Hasta 15 UIT : </td>
             <td>
                <input type="number" id="txt_15uit" min="0" max="10000" step="0.1"  required="required"><p>
            </td>
        </tr>
         <tr>
            <td> % Min Ivpp : </td>
            <td>
                <input type="number" id="txt_min_ivpp" min="0" max="10000" step="0.1"  required="required"><p>
            </td>
            <td> &nbsp &nbsp Hasta 60 UIT : </td>
             <td>
                <input type="number" id="txt_60uit" min="0" max="10000" step="0.1"  required="required"><p>
            </td>
        </tr>
          <tr>
            <td> % Otras Inst : </td>
            <td>
                <input type="number" id="txt_ot_ins" min="0" max="10000" step="0.1"  required="required"><p>
            </td>
            <td> &nbsp &nbsp mas de 60 UIT : </td>
             <td>
                <input type="number" id="txt_60mas" min="0" max="10000" step="0.1"  required="required"><p>
            </td>
        </tr>
        
      
       
   
   </table>
    
    
</div>
    
  <div id="dialog_open_msg_eliminar" style="display: none">
      <h5>Desea eliminar la UIT?</h5>  
        </div>

  @section('page-js-script')
<script type="text/javascript">
    global=0;
    $(document).ready(function() {
        jQuery("#table_vw_uit").jqGrid({        
            url: 'list_uit',
            datatype: 'json', mtype: 'GET',        
            autowidth: true, height: 'auto',
            colNames:['pk_uit','Año','UIT','Uit Alcab %','Tasa Alcab','Formatos','% Min Ivpp','% Min O Inst','deoa15','de15a60','mas60'], 
            //rowNum: 11, sortname: 'pk_uit', sortorder: 'desc', viewrecords: true,
            rowNum: 10, sortname: 'pk_uit', sortorder: 'desc', viewrecords: true, caption: 'LISTADO DE UITs',  align: "center",
            colModel:[ 
                {name:'pk_uit',index:'pk_uit', hidden:true}, 
                {name:'anio',index:'anio', align:'center'}, 
                {name:'uit',index:'uit', align:'center'},
                {name:'uit_alc',index:'uit_alc', align:'center'},
                {name:'tas_alc',index:'uit_alc', align:'center'},
                {name:'formatos',index:'formatos', align:'center'},
                {name:'porc_min_ivpp',index:'porc_min_ivpp', align:'center'},
                {name:'porc_ot_ins',index:'porc_ot_ins',  align:'center'},
                {name:'deoa15',index:'deoa15',  align:'center', hidden:true},
                {name:'de15a60',index:'de15a60',  align:'center', hidden:true},
                {name:'mas60',index:'mas60',  align:'center', hidden:true},
            ],        
            pager: '#pager_table_vw_uit',
            rowList: [11, 22],
            onSelectRow: function(Id){
                $('#btn_vw_uit_Editar').attr('onClick', 'open_dialog_nuevo_uit("'+'EDITAR'+'",'+Id+')');
               $('#btn_vw_uit_Eliminar').attr('onClick', 'open_dialog_quitar_uit('+Id+')');
            },
            ondblClickRow: function(Id){
                //ape=$("#table_vw_uit").getCell(Id,"anio");
                //alert(ape);
                //global=Id;
//                alert(global);
                
        //open_dialog_nuevo_uit();
                
                
            }
        });
        
        $(window).on('resize.jqGrid', function() {
                $("#table_vw_uit").jqGrid('setGridWidth', $("#content").width());
        });
    });
</script>
@stop  
    
    


<script src="{{ asset('archivos_js/configuracion/uit.js') }}"></script>




@endsection
