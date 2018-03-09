@extends('layouts.app')
@section('content')
<section id="widget-grid" class=""> 
    <div class='cr_content col-xs-12 '>

        <div class="col-xs-12">
        <div class="col-lg-9">
            <h1 class="txt-color-green"><b>Impresión de Formatos...</b></h1>
        </div>
        <div class="col-lg-3 col-md-6 col-xs-12">
            <div class="input-group input-group-md">
                <span class="input-group-addon">Año de Trabajo <i class="fa fa-cogs"></i></span>
                <div class="icon-addon addon-md">
                    <select id='selantra' class="form-control col-lg-8" style="height: 32px;" onchange="call_list_contrib_carta(0)">
                    @foreach ($anio_tra as $anio)
                    <option value='{{$anio->anio}}' >{{$anio->anio}}</option>
                    @endforeach
                    </select>
                </div>
            </div>
        </div>
        </div>
        
        <div class="col-xs-12 cr-body" >
            
            <div class="col-xs-3" style="padding: 0px; margin-top: 5px">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 0px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-align-justify"></i> </span>
                                <h2>Busqueda por Contribuyente</h2>
                        </header>
                    </div>
                </section>
            </div>
        
        <div class="col-xs-9" style="padding: 0px; margin-top: 5px; margin-bottom: 15px">
            <div class="col-xs-3" style="padding: 0px;">
                <div class="input-group input-group-md">
                    <span class="input-group-addon">Cod. &nbsp;<i class="fa fa-hashtag"></i></span>
                    <div class=""  >
                        <input id="dlg_contri_hidden" type="hidden" value="0">
                    <input id="dlg_dni" type="text"  class="form-control" style="height: 32px; " >
                    </div>
                </div>
            </div>
            <div class="col-xs-9" style="padding: 0px;">
                <div class="input-group input-group-md">
                    <span class="input-group-addon">Contribuyente &nbsp;<i class="fa fa-male"></i></span>
                    <div   >
                        <input id="dlg_contri" type="text"  class="form-control" style="height: 32px;font-size: 0.9em;width: 102% !important" autofocus="focus" >
                    </div>
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" onclick="fn_bus_contrib()">
                            <i class="glyphicon glyphicon-search"></i>
                        </button>
                    </span>
                </div>
            </div>
            
        </div>
        </div>
        
       
    </div>
    <div class='cr_content col-xs-12'>
        <div class="col-xs-12" style="padding: 0px; margin-top: 5px;">
            <ul style="padding: 0px;">                                        
                        <button type="button" class="btn btn-labeled bg-color-green txt-color-white" onclick="abrir_rep('HR');">
                            <span class="btn-label"><i class="glyphicon glyphicon-print"></i></span>Imprimir HR
                        </button>
                        <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="abrir_rep('PU');">
                            <span class="btn-label"><i class="glyphicon glyphicon-print"></i></span>Imprimir PU
                        </button>
                        <button type="button" class="btn btn-labeled bg-color-magenta txt-color-white" onclick="abrir_rep('PR');">
                            <span class="btn-label"><i class="glyphicon glyphicon-print"></i></span>Imprimir PR
                        </button>
            </ul>
        </div>
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; padding: 0px !important">
            <table id="table_predios"></table>
            <div id="pager_table_predios"></div>
        </article>
        
    </div>
    
</section>

@section('page-js-script')
<script type="text/javascript">
    
    $(document).ready(function (){
        $("#menu_admtri").show();
        $("#li_impform").addClass('cr-active')
        jQuery("#table_predios").jqGrid({
            url: 'gridpredio?mnza=0&ctr=0&tpre=0&an=0',
            datatype: 'json', mtype: 'GET',
            height: '260px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_pred','t_pred', 'Sector','Manzana','Lote Cat', 'Código Predial', 'Mz Dist', 'Lt Dist', 'N° Munic', 'Est. Construcción', 'Contribuyente o Razon Social', 'Calle/Vía','id_via','A.Terreno','S/.Terreno','S/.Construct'],
            rowNum: 20, sortname: 'id_pred', sortorder: 'desc', viewrecords: true, caption: 'Predios Urbanos/Rusticos', align: "center",
            colModel: [
                {name: 'id_pred', index: 'id_pred', hidden: true},
                {name: 'tp', index: 'tp', align: 'center', width: 40},
                {name: 'sec', index: 'sec', align: 'center', width: 30},
                {name: 'mnza', index: 'mnza', align: 'center', width: 30},
                {name: 'lote', index: 'lote', align: 'center', width: 30},
                {name: 'cod_cat', index: 'cod_cat', align: 'center', width: 80},
                {name: 'mzna_dist', index: 'mzna_dist', align: 'center', width: 40},
                {name: 'lote_dist', index: 'lote_dist', align: 'center', width: 40},
                {name: 'nro_mun', index: 'nro_mun', width: 40,align: "right"},
                {name: 'descripcion', index: 'descripcion', width: 100},
                {name: 'contribuyente', index: 'contribuyente', width: 150},
                {name: 'nom_via', index: 'nom_via', width: 100},
                {name: 'id_via', index: 'id_via', hidden: true},
                {name: 'are_terr', index: 'are_terr', width: 60,align: "right"},
                {name: 'val_ter', index: 'val_ter', width: 60,align: "right"},
                {name: 'val_const', index: 'val_const', width: 60, align: "right"},
               
            ],
            pager: '#pager_table_predios',
            rowList: [13, 20],
            rownumbers: true,
            gridComplete: function () {
                    var idarray = jQuery('#table_predios').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_predios').jqGrid('getDataIDs')[0];
                            $("#table_predios").setSelection(firstid);    
                        }
                     
                },
            onSelectRow: function (Id){
                
            },
            ondblClickRow: function (Id){}
        });
        contrib_global=0;
        jQuery("#table_contrib").jqGrid({
            url: 'obtiene_cotriname?dat=0',
            datatype: 'json', mtype: 'GET',
            height: '300px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id_pers','codigo','DNI/RUC','contribuyente'],
            rowNum: 20, sortname: 'contribuyente', sortorder: 'asc', viewrecords: true, caption: 'Contribuyentes', align: "center",
            colModel: [
                {name: 'id_pers', index: 'id_pers', hidden: true},
                {name: 'id_per', index: 'id_per', align: 'center',width: 100},
                {name: 'nro_doc', index: 'nro_doc', align: 'center',width: 100},
                {name: 'contribuyente', index: 'contribuyente', align: 'left',width: 260},
                
            ],
            pager: '#pager_table_contrib',
            rowList: [13, 20],
            gridComplete: function () {
                    var idarray = jQuery('#table_contrib').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_contrib').jqGrid('getDataIDs')[0];
                            $("#table_contrib").setSelection(firstid);    
                        }
                    if(contrib_global==0)
                    {   contrib_global=1;
                        jQuery('#table_contrib').jqGrid('bindKeys', {"onEnter":function( rowid ){fn_bus_contrib_list(rowid);} } ); 
                    }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){fn_bus_contrib_list(Id)}
        });
        $("#dlg_dni").keypress(function (e) {
            if (e.which == 13) {
                traer_contri_cod("dlg_contri",$("#dlg_dni").val());
            }
        });
        var globalvalidador=0;
        $("#dlg_contri").keypress(function (e) {
            if (e.which == 13) {
                if(globalvalidador==0)
                {
                    fn_bus_contrib();
                    globalvalidador=1;
                }
                else
                {
                    globalvalidador=0;
                }
                
            }
        });
       
        
    });
    
</script>
@stop
<script src="{{ asset('archivos_js/adm_tributaria/imp_rep.js') }}"></script>
<div id="dlg_bus_contr" style="display: none;">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 10px; padding: 0px !important">
        <table id="table_contrib"></table>
        <div id="pager_table_contrib"></div>
    </article>
</div> 



@endsection




