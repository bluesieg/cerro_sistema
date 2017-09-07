@extends('layouts.app')
@section('content')

    <section id="widget-grid" class="">
        <div class='cr_content col-xs-12'>
            <div class="col-xs-12">
                <div class="col-lg-9">
                    <h1 class="txt-color-green"><b>Mantenimiento de Contribuyentes...</b></h1>
                </div>
                <div class="col-lg-3 col-md-6 col-xs-12">
                </div>

            </div>
            <div class="col-lg-3 col-md-6 col-xs-12">
            </div>

            <div class="col-lg-3 col-md-6 col-xs-12">
            </div>
            <div class="col-lg-6 col-md-12 col-xs-12">
                <ul class="text-right" style="margin-top: 22px !important; margin-bottom: 0px !important">
                    <button onclick="open_dialog_new_edit_Contribuyente('NUEVO');" id="btn_vw_contribuyentes_Nuevo" type="button" class="btn btn-labeled bg-color-greenLight txt-color-white">
                        <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                    </button>
                    <button onclick="modificar_contrib();" id="btn_vw_contribuyentes_Editar" type="button" class="btn btn-labeled bg-color-blue txt-color-white">
                        <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                    </button>
                    <button id="btn_vw_contribuyentes_Eliminar" type="button" class="btn btn-labeled btn-danger">
                        <span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span>Eliminar
                    </button>
                    <button onclick="dlg_new_reporte();" type="button" class="btn btn-labeled bg-color-magenta txt-color-white">
                        <span class="btn-label"><i class="glyphicon glyphicon-print"></i></span>Imprimir
                    </button>
                    <button onclick="reniec();" type="button" class="btn btn-labeled bg-color-magenta txt-color-white">
                        <span class="btn-label"><i class="glyphicon glyphicon-print"></i></span>Reniec
                    </button>
                </ul>
            </div>
        </div>
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; padding: 0px !important">
                <table id="table_Contribuyentes"></table>
                <div id="pager_table_Contribuyentes"></div>
            </article>
    </section>
@section('page-js-script')

<script type="text/javascript">
    $(document).ready(function () {
        $("#menu_admtri").show();
        $("#li_config_contribuyentes").addClass('cr-active');
        jQuery("#table_Contribuyentes").jqGrid({
            url: 'grid_contribuyentes',
            datatype: 'json', mtype: 'GET',
            height: 'auto', autowidth: true,
            toolbarfilter: true,
            colNames: ['Codigo', ' Tip. Doc', 'N°. Documento', 'Contribuyente o Razon Social', 'Cod. Via', 'Calle / Via', 'Fono. Fijo', 'Celular',
                'tipo_persona','id_cond_exonerac','est_civil','email','id_dpto','id_prov','id_dist','id_via','nro_mun','dpto','manz','lote',
                'dom_fis','tip_doc_conv','nro_doc_conv','conviviente','id_pers','id_conv','tip_doc'],
            rowNum: 20, sortname: 'id_contrib', sortorder: 'desc', viewrecords: true, caption: 'LISTA DE CONTRIBUYENTES REGISTRADOS', align: "center",
            colModel: [                
                {name: 'id_persona', index: 'id_persona', align: 'left', width: 80},
                {name: 'tip_doc_desc', index: 'tip_doc_desc', align: 'center', width: 60},
                {name: 'nro_doc', index: 'nro_doc', align: 'center', width: 90},
                {name: 'contribuyente', index: 'contribuyente', align: 'left', width: 250},
                {name: 'cod_via', index: 'cod_via', align: 'center', width: 60},
                {name: 'nom_via', index: 'nom_via', width: 130},
                {name: 'tlfno_fijo', index: 'tlfno_fijo', width: 80},
                {name: 'tlfono_celular', index: 'tlfono_celular', width: 80},
                {name: 'tipo_persona', index: 'tipo_persona', hidden:true},
                {name: 'id_cond_exonerac', index: 'id_cond_exonerac', hidden:true},
                {name: 'est_civil', index: 'est_civil', hidden:true},
                {name: 'email', index: 'email', hidden:true},
                {name: 'id_dpto', index: 'tlfono_celular', hidden:true},
                {name: 'id_prov', index: 'tlfono_celular', hidden:true},
                {name: 'id_dist', index: 'tlfono_celular', hidden:true},
                {name: 'id_via', index: 'tlfono_celular', hidden:true},
                {name: 'nro_mun', index: 'tlfono_celular', hidden:true},
                {name: 'dpto', index: 'tlfono_celular', hidden:true},
                {name: 'manz', index: 'tlfono_celular', hidden:true},
                {name: 'lote', index: 'tlfono_celular', hidden:true},
                {name: 'dom_fis', index: 'tlfono_celular', hidden:true},
                {name: 'tip_doc_conv', index: 'tlfono_celular', hidden:true},
                {name: 'nro_doc_conv', index: 'tlfono_celular', hidden:true},
                {name: 'conviviente', index: 'tlfono_celular', hidden:true},
                {name: 'id_pers', index: 'tlfono_celular', hidden:true},
                {name: 'id_conv', index: 'tlfono_celular', hidden:true},
                {name: 'tip_doc', index: 'tip_doc', hidden:true}
            ],
            pager: '#pager_table_Contribuyentes',
            rowList: [20, 30, 50],
            onSelectRow: function (Id) { },
            ondblClickRow: function (Id) {
                modificar_contrib();
            },
            gridComplete: function () {
                var rows = $("#table_Contribuyentes").getDataIDs();
                for (var i = 0; i < rows.length; i++) {
                    var tipo_doc = $("#table_Contribuyentes").getCell(rows[i], "tipo_doc");
//                    if (tipo_doc == '02') {                        
//                        $("#table_Contribuyentes").jqGrid('setCell', rows[i],'tipo_doc', 'DNI');
//                    }
//                    if (tipo_doc == '00') {                        
//                        $("#table_Contribuyentes").jqGrid('setCell', rows[i],'tipo_doc', 'RUC');
//                    }
                }
                if (rows.length > 0) {
                    var firstid = jQuery('#table_Contribuyentes').jqGrid('getDataIDs')[0];
                    $("#table_Contribuyentes").setSelection(firstid);    
                }
            }
        });
        $(window).on('resize.jqGrid', function () {
            $("#table_Contribuyentes").jqGrid('setGridWidth', $("#content").width());
        });
        $("#txt_nro_doc").keypress(function (e) {
            if (e.which == 13) {
                fn_consultar_persona(1);
            }
        });
        $("#contrib_nro_doc_conv").keypress(function (e) {
            if (e.which == 13) {
                fn_consultar_persona(2);
            }
        });        
        $("#pers_nro_doc").keypress(function (e) {
            if (e.which == 13) { 
                tipo = $("#cb_tip_doc_3").val();
                if(tipo=='02'){
                    fn_consultar_dni(); 
                }else if (tipo=='00'){
                    fn_consultar_ruc();
                }
            }
        });        
    });
</script>
@stop
<script src="{{ asset('archivos_js/adm_tributaria.js') }}"></script>


<div id="dialog_new_edit_Contribuyentes" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">                
                <div class="panel panel-success">
                    <div class="panel-heading bg-color-success">.:: Datos del Contribuyente ::.</div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="row">
                                <section class="col col-2" style="padding-right:5px;">
                                    <label class="label">Tipo Documento:</label>                                   
                                    <label class="select">
                                        <select id="cb_tip_doc_1" onchange="filtro_tipo_doc(this.value);" class="input-sm">
                                        @foreach ($tip_doc as $tip_doc1)
                                        <option value='{{$tip_doc1->tip_doc}}' >{{trim($tip_doc1->tipo_documento)}}</option>
                                        @endforeach                                         
                                        </select><i></i> </label>                        
                                </section>
                                <section class="col col-2" style="padding-left:5px;padding-right:5px;">
                                    <label class="label">Nro. Documento:</label>
                                    <label class="input">
                                        <input id="txt_nro_doc" type="text" onkeypress="return soloDNI(event);" maxlength="8" placeholder="00000000" class="input-sm">
                                    </label>                      
                                </section>
                                <section class="col col-2" style="padding-left:5px;padding-right:5px;margin-top: 16px"> 
                                    <button onclick="fn_consultar_persona(1);" type="button" class="btn btn-labeled btn-primary">
                                        <span class="btn-label" style="left: 0px;">
                                            <i class="fa fa-search"></i>
                                        </>Buscar
                                   </button>
                                </section>
                                <section class="col col-3" style="padding-left:5px;padding-right:5px;">
                                    <label class="label">Tipo de Contribuyente:</label>
                                    <label class="select">
                                        <select id="vw_contrib_sel_tip_contrib" class="input-sm">
                                            @foreach ($tip_contrib as $tip_contrib1)
                                            <option value='{{$tip_contrib1->id_tip_contrib}}' >{{trim($tip_contrib1->tipo_contrib)}}</option>
                                            @endforeach                                            
                                        </select><i></i> </label>
                                </section>
                                <section class="col col-3" style="padding-left:5px">
                                    <label class="label">Condicion del Contribuyente:</label>
                                    <label class="select">
                                    <select id="contrib_id_cond_exonerac" class="input-sm text-uppercase">
                                        @foreach ($condicion as $condicion)
                                        <option value='{{$condicion->id_exo}}' >{{trim($condicion->desc_exon)}}</option>
                                        @endforeach                                            
                                    </select><i></i> </label>
                                </section>
                            </div>
                            <section>
                                <label class="label">Contribuyente:</label>
                                <label class="input">
                                    <input type="hidden" id="vw_contrib_id_pers">
                                    <input id="vw_contrib_contribuyente" type="text" class="input-sm" disabled="">
                                </label>                        
                            </section>
                            <div class="row">
                                <section class="col col-2"  style="padding-right:5px;">
                                    <label class="label">Est.Civil:</label>
                                    <label class="select">
                                        <select id="contrib_est_civil" class="input-sm">
                                            <option value="select" selected="" disabled="">Est.Civil</option>
                                            <option value="0">Soltero</option>
                                            <option value="1">Casado</option>
                                            <option value="2">Viudo</option>
                                            <option value="3">Divorciado</option>
                                            <option value="4">Conviviente</option>
                                        </select><i></i> </label>
                                </section>
                                <section class="col col-2"  style="padding-left:5px;padding-right:5px;">
                                    <label class="label">Telefono Fijo:</label>
                                    <label class="input">
                                        <input id="contrib_tlfno_fijo" type="text" placeholder="Telefono Fijo" class="input-sm">
                                    </label>                      
                                </section>
                                <section class="col col-2"  style="padding-left:5px;padding-right:5px;">
                                    <label class="label">Telefono Celular:</label>
                                    <label class="input">
                                        <input id="contrib_tlfono_celular" type="text" placeholder="Telefono Celular" class="input-sm">
                                    </label>                      
                                </section>
                                <section class="col col-6"  style="padding-left:5px;">
                                    <label class="label">Correo Electronico:</label>
                                    <label class="input">
                                        <input id="contrib_email" type="text" placeholder="Correo Electronico" class="input-sm">
                                    </label>   
                                </section>
                            </div>                           
                        </fieldset>
                    </div>
                </div>
                <div class="panel panel-success">
                    <div class="panel-heading bg-color-success">.:: Domicilio Fiscal en la Provincia ::.</div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="row">
                                <section class="col col-4" style="padding-right:5px;">
                                    <label class="label">Departamento:</label>
                                    <label class="select">
                                        <select id="contrib_dpto" class="input-sm text-uppercase" onchange="llenar_combo_prov('contrib_prov', this.value);">
                                            @foreach ($dpto as $dpto)
                                            <option value='{{$dpto->cod}}' >{{trim($dpto->dpto)}}</option>
                                            @endforeach                                           
                                        </select><i></i> </label>                        
                                </section>
                                <section class="col col-4" style="padding-left:5px;padding-right:5px;">
                                    <label class="label">Provincia:</label>
                                    <label class="select">
                                        <select id="contrib_prov" class="input-sm text-uppercase" onchange="llenar_combo_dist('contrib_dist', this.value);">
                                            <option value="select" selected="" disabled="">Provincia</option>                                            
                                        </select><i></i> </label>                     
                                </section>
                                <section class="col col-4" style="padding-left:5px;">
                                    <label class="label">Distrito:</label>
                                    <label class="select">
                                        <select id="contrib_dist" class="input-sm text-uppercase">
                                            <option value="select" selected="" disabled="">Distrito</option>                                            
                                        </select><i></i> </label>   
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-4" style="padding-right:5px;">
                                    <label class="label">Avenida, Jiron, Calle o Pasaje.:</label>
                                    <label class="input">
                                        <input type="hidden" id="hiddentxt_av_jr_calle_psje">
                                        <input id="txt_av_jr_calle_psje" type="text" placeholder="Avenida, Jiron, Calle o Pasaje." class="input-sm">
                                    </label>
                                </section>
                                <section class="col col-2" style="padding-left:5px;padding-right:5px;">
                                    <label class="label">N°Muni:</label>
                                    <label class="input">
                                        <input id="contrib_nro_mun" type="text" placeholder="N°. Municipal" class="input-sm">
                                    </label>
                                </section>
                                <section class="col col-2" style="padding-left:5px;padding-right:5px;">
                                    <label class="label">Dpto:</label>
                                    <label class="input">
                                        <input id="contrib_dpto_depa" type="text" placeholder="Dpto." class="input-sm">
                                    </label>
                                </section>
                                <section class="col col-2" style="padding-left:5px;padding-right:5px;">
                                    <label class="label">Mzna:</label>
                                    <label class="input">
                                        <input id="contrib_manz" type="text" placeholder="Mzna." class="input-sm">
                                    </label>
                                </section>
                                <section class="col col-2" style="padding-left:5px;">
                                    <label class="label">Lote:</label>
                                    <label class="input">
                                        <input id="contrib_lote" type="text" placeholder="Lote" class="input-sm">
                                    </label>
                                </section>
                            </div>
                            <section>
                                <label class="label">Referencia del Domicilio Fiscal:</label>
                                <label class="input">
                                    <input id="contrib_dom_fiscal" type="text" placeholder="Referencia del Domicilio Fiscal" class="input-sm text-uppercase">
                                </label>                        
                            </section>
                        </fieldset>
                    </div>
                </div>
                <div class="panel panel-success">
                    <div class="panel-heading bg-color-success">.:: Datos del Conyugue ::.</div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="row">
                                <section class="col col-3" style="padding-right:5px;">
                                    <label class="label">Tipo Documento:</label>
                                    <label class="select">
                                        <select id="cb_tip_doc_2" class="input-sm" disabled="">
                                            @foreach ($tip_doc as $tip_doc2)
                                            <option value='{{$tip_doc2->tip_doc}}' >{{trim($tip_doc2->tipo_documento)}}</option>
                                            @endforeach                                           
                                        </select><i></i></label>                        
                                </section>                                
                                <section class="col col-3" style="padding-left:5px;padding-right:5px;">
                                    <label class="label">Nro.Documento:</label>
                                    <label class="input">
                                        <input id="contrib_nro_doc_conv" type="text" placeholder="00000000" maxlength="8" class="input-sm">
                                    </label>
                                </section>
                                <section class="col col-6" style="padding-left:5px;">
                                    <label class="label">Apellidos y Nombres del Conyugue:</label>
                                    <label class="input">
                                        <input type="hidden" id="vw_contrib_id_conv">
                                        <input id="contrib_conviviente" type="text" class="input-sm" disabled="">
                                    </label>
                                </section>
                            </div>    
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="dialog_Personas" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">                
                <div class="panel panel-success" style="border: 0px !important;">
<!--                    <div class="panel-heading bg-color-success">.:: Datos del Contribuyente ::.</div>-->
                    <div class="panel-body">
                        <fieldset>
                            <div class="row">
                                <section class="col col-6" style="padding-right:5px;">
                                    <label class="label">Tipo Documento:</label>                                   
                                    <label class="select">
                                        <select id="cb_tip_doc_3" onchange="filtro_tipo_doc_pers(this.value);" class="input-sm">
                                        @foreach ($tip_doc as $tip_doc3)
                                        <option value='{{$tip_doc3->tip_doc}}' >{{trim($tip_doc3->tipo_documento)}}</option>
                                        @endforeach                                          
                                        </select><i></i> </label>                                                       
                                </section>
                                <section class="col col-6" style="padding-left:5px;">
                                    <label class="label">Nro. Documento:</label>
                                    <label class="input">
                                        <input id="pers_nro_doc" type="text" onkeypress="return soloDNI(event);" maxlength="8" placeholder="00000000" class="input-sm">
                                    </label>                                                                                            
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-3" style="padding-right:5px;">
                                    <label class="label">Ape.Paterno:</label>
                                    <label class="input">
                                        <input id="pers_pat" type="text" maxlength="50" class="input-sm text-uppercase">
                                    </label>                                    
                                </section>
                                <section class="col col-3" style="padding-left:5px;padding-right:5px;">
                                    <label class="label">Ape.Materno:</label>
                                    <label class="input">
                                        <input id="pers_mat" type="text" maxlength="50" class="input-sm text-uppercase">
                                    </label>                                                                     
                                </section>
                                <section class="col col-6" style="padding-left:5px;">
                                    <label class="label">Nombres:</label>
                                    <label class="input">
                                        <input id="pers_nombres" type="text" maxlength="100" class="input-sm text-uppercase">
                                    </label>                                                                     
                                </section> 
                            </div>                            
                            <section>
                                <label class="label">Razon Social:</label>
                                <label class="input">
                                    <input id="pers_raz_soc" type="text" class="input-sm text-uppercase">
                                </label>                                                 
                            </section>
                            <div class="row">
                                <section class="col col-6" style="padding-right:5px;">
                                    <label class="label">Sexo:</label>                                   
                                    <label class="select">
                                        <select id="pers_sexo" class="input-sm text-uppercase">
                                            <option value="-">Seleccionar</option>
                                            <option value="1">Masculino</option>
                                            <option value="0">Femenino</option>        
                                        </select><i></i> </label>                                     
                                </section>
                                <section class="col col-6" style="padding-left:5px;">
                                    <label class="label">Fecha Nac.:</label>
                                    <label class="input">
                                        <input id="pers_fnac" type="text" data-mask="99/99/9999" data-mask-placeholder="-" placeholder="dia/mes/año" class="input-sm">
                                    </label>                                                                                                          
                                </section>                                
                            </div>
<!--                            <section>
                                <label class="label">Domicilio Fiscal:</label>
                                <label class="input">
                                    <input id="pers_dom_fis" type="text" class="input-sm text-uppercase">
                                </label>                                
                            </section>-->
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="dialog_captcha_reniec" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <center><img id="captcha_reniec" src="" width="150"></center>
                <section>
                    <label class="label">Codigo:</label>
                    <label class="input">
                        <input id="txt_captcha_reniec" type="text" class="input-sm">
                    </label>                        
                </section> 

            </div>
        </div>
    </div>
</div>

<div id="reniec" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <center><img id="captcha_reniec" src="" width="150"></center>
                <section>
                    <label class="label">Codigo:</label>
                    <label class="input">
                        <input id="txt_captcha_reniec" type="text" class="input-sm">
                    </label>                        
                </section>
            </div>
        </div>
    </div>
</div>


<div id="dialog_reporte_contr" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group">
                <!-- widget div-->
                <div>

                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->

                    </div>
                    <!-- end widget edit box -->

                    <!-- widget content -->
                    <div class="widget-body">

                        <p>
                            Reporte por:
                        </p>
                        <hr class="simple">
                        <ul id="myTab1" class="nav nav-tabs bordered">
                            <li class="active">
                                <a href="#s1" onclick="current_tab(1);" data-toggle="tab">Año-Sector-Manzana</a>
                            </li>
                            <li>
                                <a href="#s2" onclick="current_tab(2);" data-toggle="tab">Habilitación Urbana</a>
                            </li>

                        </ul>

                        <div id="myTabContent1" class="tab-content padding-10">
                            <div class="tab-pane fade in active" id="s1">
                                <div class="row">
                                    <section class="col col-4" style="padding-right:5px;">
                                        <label class="label">AÑO:</label>
                                        <label class="select">
                                            <select id='selantra' class="form-control col-lg-8">
                                                @foreach ($anio_tra as $anio)
                                                    <option value='{{$anio->anio}}' >{{$anio->anio}}</option>
                                                @endforeach
                                            </select><i></i> </label>
                                    </section>
                                    <section class="col col-4" style="padding-left:5px;padding-right:5px;">
                                        <label class="label">SECTOR:</label>
                                        <label class="select">
                                            <select id='selsec' class="form-control col-lg-8">
                                                @foreach ($sectores as $sectores)
                                                    <option value='{{$sectores->sector}}' >{{$sectores->sector}}</option>
                                                @endforeach
                                            </select><i></i> </label>
                                    </section>
                                    <section class="col col-4" style="padding-left:5px;padding-right:5px">
                                        <label class="label">MANZANA:</label>
                                        <label class="select">
                                            <select id="selmnza" class="form-control">
                                                @foreach ($manzanas as $manzanas)
                                                    <option value='{{$manzanas->codi_mzna}}'>{{$manzanas->codi_mzna}}</option>
                                                @endforeach
                                            </select><i></i> </label>
                                    </section>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="s2">

                                <!--
                                <section class="col col-3" style="padding-right:5px;">
                                    <label class="label">Código:</label>
                                    <label class="input">
                                        <input id="pers_pat" type="text" maxlength="50">
                                    </label>
                                </section>-->
                                <div class="row">
                                <section class="col col-2" style="padding-right:5px;">
                                    <label class="label">AÑO:</label>
                                    <label class="select">
                                        <select id='selec_hab_urb' class="form-control col-lg-8">
                                            @foreach ($anio_tra as $anio)
                                                <option value='{{$anio->anio}}' >{{$anio->anio}}</option>
                                            @endforeach
                                        </select><i></i> </label>
                                </section>

                                <section class="col col-10">
                                    <label class="label">Habilitación Urbana:</label>
                                    <label class="input">
                                        <input type="text" list="list" id="hab_urb" name="hab_urb" placeholder="Habilitación Urbana">
                                        <datalist id="list">
                                            @foreach ($hab_urb as $hab)
                                                <option data-xyz ="{{$hab->id_hab_urb}}" value="{{$hab->nomb_hab_urba}}">{{$hab->id_hab_urb}}</option>
                                            @endforeach
                                        </datalist> </label>
                                </section>
                                    </div>

                                <!--
                                <section class="col col-9" style="padding-left:5px;padding-right:5px;">
                                    <label class="label">Habilitación Urbana:</label>
                                    <label class="input">
                                        <input id="pers_mat" type="text" maxlength="50" class="input-sm text-uppercase">
                                    </label>
                                </section>-->

                            </div>
                        </div>

                    </div>
                    <!-- end widget content -->

                </div>
                <!-- end widget div -->
            </div>
        </div>
    </div>
</div>

@endsection




