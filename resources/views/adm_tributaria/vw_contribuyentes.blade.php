@extends('layouts.app')
@section('content')
<input type="hidden" id="per_del" value="{{$permisos[0]->btn_del}}"/>
<section id="widget-grid" class="">    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -12px">
            <div class="well well-sm well-light">
                <h1 class="txt-color-green"><b>Contribuyentes...</b></h1>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="text-right">
                            <section class="col-lg-7" style="padding-left:2px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Buscar Contribuyente o Nro Documento<i class="icon-append fa fa-male" style="margin-left: 5px;"></i></span>
                                    <input type="text" id="vw_contrib_buscar" class="form-control text-uppercase">
                                    <span class="input-group-btn">
                                        <button class="btn btn-success" type="button" onclick="buscar_contrib();" title="BUSCAR">
                                            <i class="glyphicon glyphicon-search"></i>&nbsp;Buscar
                                        </button>
                                    </span>
                                </div>                                            
                            </section>
                            @if( $permisos[0]->btn_new ==1 )
                                <button onclick="open_dialog_new_edit_Contribuyente('NUEVO');" id="btn_vw_contribuyentes_Nuevo" type="button" class="btn btn-labeled bg-color-greenLight txt-color-white">
                                    <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                                </button>
                            @else
                                <button onclick="sin_permiso();" id="btn_vw_contribuyentes_Nuevo" type="button" class="btn btn-labeled bg-color-greenLight txt-color-white">
                                    <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>Nuevo
                                </button>
                            @endif
                            @if( $permisos[0]->btn_edit ==1 )
                                <button onclick="modificar_contrib();" id="btn_vw_contribuyentes_Editar" type="button" class="btn btn-labeled bg-color-blue txt-color-white">
                                    <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                </button>
                            @else
                                <button onclick="sin_permiso();" id="btn_vw_contribuyentes_Editar" type="button" class="btn btn-labeled bg-color-blue txt-color-white">
                                    <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>Modificar
                                </button>
                            @endif
                            @if( $permisos[0]->btn_del ==1 )
                                <button onclick="eliminar_contrib();" data-token="{{ csrf_token() }}" id="btn_vw_contribuyentes_eliminar" type="button" class="btn btn-labeled bg-color-red txt-color-white">
                                    <span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span>Eliminar
                                </button>
                            @else
                                <button onclick="sin_permiso();" id="btn_vw_contribuyentes_eliminar" type="button" class="btn btn-labeled bg-color-red txt-color-white">
                                    <span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span>Eliminar
                                </button>
                            @endif
                            @if( $permisos[0]->btn_imp ==1 )
                                <button onclick="dlg_new_reporte();" type="button" class="btn btn-labeled bg-color-magenta txt-color-white">
                                    <span class="btn-label"><i class="glyphicon glyphicon-print"></i></span>Imprimir
                                </button>
                            @else
                                <button onclick="sin_permiso();" type="button" class="btn btn-labeled bg-color-magenta txt-color-white">
                                    <span class="btn-label"><i class="glyphicon glyphicon-print"></i></span>Imprimir
                                </button>
                            @endif
                        </div>                        
                    </div>
                </div> 
            </div>
            <div class="well well-sm well-light" style="margin-top:-20px;">                
                <div class="row">
                    <div class="col-xs-12">                        
                        <div class="row">
                            <section id="content_2" class="col-lg-12">
                                <table id="table_Contribuyentes"></table>
                                <div id="pager_table_Contribuyentes"></div>
                            </section>                            
                        </div>                                                
                    </div>
                </div> 
            </div>
        </div>       
    </div>
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
            colNames: ['Codigo','Tipo Persona', ' Tip. Doc', 'N°. Documento', 'Contribuyente o Razon Social', 'Cod. Via', 'Calle / Via', 'Fono. Fijo', 'Celular',
                'tipo_persona','id_cond_exonerac','est_civil','email','id_dpto','id_prov','id_dist','id_via','nro_mun','dpto','manz','lote',
                'dom_fis','tip_doc_conv','nro_doc_conv','con_pat','con_mat','con_nombres','id_pers','id_conv','tip_doc','nro_expediente'],
            rowNum: 20, sortname: 'id_contrib', sortorder: 'desc', viewrecords: true, caption: 'LISTA DE CONTRIBUYENTES REGISTRADOS', align: "center",
            colModel: [                
                {name: 'id_persona', index: 'id_persona', align: 'left', width: 80},
                {name: 'persona', index: 'persona', align: 'left', width: 100},
                {name: 'tip_doc_desc', index: 'tip_doc_desc', align: 'center', width: 60},
                {name: 'nro_doc', index: 'nro_doc', align: 'center', width: 90},
                {name: 'contribuyente', index: 'contribuyente', align: 'left', width: 250},
                {name: 'cod_via', index: 'cod_via', hidden:true},
                {name: 'nom_via', index: 'nom_via', width: 130},
                {name: 'tlfno_fijo', index: 'tlfno_fijo', hidden:true},
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
                {name: 'ref_dom_fis', index: 'tlfono_celular', hidden:true},
                {name: 'tip_doc_conv', index: 'tlfono_celular', hidden:true},
                {name: 'nro_doc_conv', index: 'tlfono_celular', hidden:true},
                {name: 'conv_pat', index: 'conv_pat', hidden:true},
                {name: 'conv_mat', index: 'conv_mat', hidden:true},
                {name: 'conv_nombres', index: 'conv_nombres', hidden:true},
                {name: 'num_expediente', index: 'num_expediente', hidden:true},
                {name: 'id_pers', index: 'tlfono_celular', hidden:true},
                {name: 'id_conv', index: 'tlfono_celular', hidden:true},
                {name: 'tip_doc', index: 'tip_doc', hidden:true}
            ],
            pager: '#pager_table_Contribuyentes',
            rowList: [20, 30, 50],
            onSelectRow: function (Id) { },
            ondblClickRow: function (Id) {
                perms = {!! json_encode($permisos[0]->btn_edit) !!};
                if(perms==1){
                    modificar_contrib();
                }else sin_permiso();
            },
            gridComplete: function () {
                var rows = $("#table_Contribuyentes").getDataIDs();
                for (var i = 0; i < rows.length; i++) {
                    var tipo_doc = $("#table_Contribuyentes").getCell(rows[i], "tipo_doc");
                }
                if (rows.length > 0) {
                    var firstid = jQuery('#table_Contribuyentes').jqGrid('getDataIDs')[0];
                    $("#table_Contribuyentes").setSelection(firstid);    
                }
            }
        });
        $(window).on('resize.jqGrid', function () {
            $("#table_Contribuyentes").jqGrid('setGridWidth', $("#content_2").width());
        });
        $("#vw_contrib_buscar").keypress(function (e) {
            if (e.which == 13) {
                buscar_contrib();
            }
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
                    get_datos_dni(); 
                }else if (tipo=='00'){
                    get_datos_ruc();
                }
            }
        });  
//        $.ajax({
//                   type: 'GET',
//                   url: url,
//                   data:{an:$("#selantra").val()},
//                   success: function(data){               
//                        var $local_todo=data;          
//                         $("#inp_dep_prov_dis").autocomplete({
//                              source: $local_todo,
//                              focus: function(event, ui) {
//                                     $("#"+textbox).val(ui.item.label);                             
//                                     return false;
//                              },
//                              select: function(event, ui) {
//                                     $("#"+textbox).val(ui.item.label);
//                                     $("#hidden_"+textbox).val(ui.item.value);
//                                     if(extra!=0)
//                                     {
//                                        $("#"+textbox+"_cod").val(ui.item.codi);
//                                     }
//                                     if(extra==2)
//                                     {
//                                        $( "#rinst_inp_largo,#rinst_inp_ancho,#rinst_inp_alto,#rinst_inp_canti" ).val(0);
//                                        $("#rinst_inp_undmed").val(ui.item.und);
//                                        if(ui.item.und=="UND")
//                                        {
//                                            $( "#rinst_inp_largo,#rinst_inp_ancho,#rinst_inp_alto" ).prop( "disabled", true );
//                                            $( "#rinst_inp_canti" ).prop( "disabled", false );
//                                        }
//                                        if(ui.item.und=="M2" || ui.item.und=="ML")
//                                        {
//                                            $( "#rinst_inp_largo,#rinst_inp_ancho" ).prop( "disabled", false );
//                                            $( "#rinst_inp_canti,#rinst_inp_alto" ).prop( "disabled", true );
//                                        }
//                                        if(ui.item.und=="M3")
//                                        {
//                                            $( "#rinst_inp_largo,#rinst_inp_ancho,#rinst_inp_alto" ).prop( "disabled", false );
//                                            $( "#rinst_inp_canti" ).prop( "disabled", true );
//                                        }
//                                     }
//                                      return false;
//                              }   
//                          });             
//                    }
//            });
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
                                        <input id="txt_nro_doc" type="text" onkeypress="return soloDNI(event);" onkeyup="limpiarcontri();" minlength="8" maxlength="8" placeholder="00000000" class="input-sm">
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
                                        <select onchange="active_conyugue(this.value);" id="vw_contrib_sel_tip_contrib" class="input-sm">
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
                                <section class="col col-4"  style="padding-left:5px;">
                                    <label class="label">Correo Electronico:</label>
                                    <label class="input">
                                        <input id="contrib_email" type="text" placeholder="Correo Electronico" class="input-sm">
                                    </label>   
                                </section>  
                                <section class="col col-2"  style="padding-left:2px; padding-right: 15px;">
                                    <label class="label">Nº Expediente</label>
                                    <label class="input">
                                        <input id="contrib_num_expediente" type="text" placeholder="Numero Expediente" class="input-sm" maxlength="30">
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
                                        <select id="contrib_dist" class="input-sm text-uppercase" onchange="selec_dist(this.value);">
                                            <option value="select" selected="" disabled="">Distrito</option>                                            
                                        </select><i></i> </label>   
                                </section>
                            </div>
                            <div class="col-xs-12" style="padding-left: 0px; display: none">
                                <section class="col-xs-12" style="padding-left:0px;">
                                    <label class="label">Departamento,Provincia,Distrito:</label>
                                    <label class="input">
                                        <input  id="inp_dep_prov_dis" type="text" class="input-sm text-uppercase" placeholder="Departamento,Provincia,Distrito">
                                    </label>   
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
                                        <select id="cb_tip_doc_2" class="input-sm" disabled="">
                                            @foreach ($tip_doc as $tip_doc2)
                                            <option value='{{$tip_doc2->tip_doc}}' >{{trim($tip_doc2->tipo_documento)}}</option>
                                            @endforeach                                           
                                        </select>
                                </section>                                
                                <section class="col col-2" style="padding-left:5px;padding-right:5px;">
                                    <label class="label">Nro.Documento:</label>
                                    <label class="input">
                                        <input id="contrib_nro_doc_conv" type="text" placeholder="00000000" maxlength="8" class="input-sm" disabled="">
                                    </label>
                                </section>
                                <section class="col col-2" style="padding-left:5px;">
                                    <label class="label">Ape. Paterno:</label>
                                    <label class="input">
                                        <input type="hidden" id="vw_contrib_id_conv">
                                        <input id="contrib_conviviente_pat" type="text" class="input-sm" disabled="">
                                    </label>
                                </section>
                                <section class="col col-2" style="padding-left:5px;">
                                    <label class="label">Ape. Materno:</label>
                                    <label class="input">
                                        <input type="hidden" id="vw_contrib_id_conv">
                                        <input id="contrib_conviviente_mat" type="text" class="input-sm" disabled="">
                                    </label>
                                </section>
                                <section class="col col-3" style="padding-left:5px;">
                                    <label class="label">Nombres:</label>
                                    <label class="input">
                                        <input type="hidden" id="vw_contrib_id_conv">
                                        <input id="contrib_conviviente_nom" type="text" class="input-sm" disabled="">
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
                        <fieldset class="col col-lg-9">
                            <div class="row">
                                <section class="col col-6" style="padding-right:5px;">
                                    <label class="label">Tipo Documento:</label>                                   
                                    <label class="select">
                                        <select id="cb_tip_doc_3" name="cb_tip_doc_3" onchange="filtro_tipo_doc_pers(this.value);" class="input-sm">
                                        @foreach ($tip_doc as $tip_doc3)
                                        <option value='{{$tip_doc3->tip_doc}}' >{{trim($tip_doc3->tipo_documento)}}</option>
                                        @endforeach                                          
                                        </select><i></i> </label>                                                       
                                </section>
                                <section class="col col-4" style="padding-left:5px;padding-right: 5px;">
                                    <label class="label">Nro. Documento:</label>
                                    <label class="input">
                                        <input id="pers_nro_doc" name="pers_nro_doc" type="text" onkeypress="return soloDNI(event);" maxlength="8" placeholder="00000000" class="input-sm">
                                    </label>                                    
                                </section>
                                <section class="col col-2" style="padding-left:5px;">
                                    <label class="label">&nbsp;</label>
                                    <button onclick="btn_bus_getdatos();" type="button" class="btn btn-labeled btn-primary">
                                        <span class="btn-label" style="left: 0px;">
                                            <i class="fa fa-search"></i>
                                        </>Buscar
                                   </button>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-3" style="padding-right:5px;">
                                    <label class="label">Ape.Paterno:</label>
                                    <label class="input">
                                        <input id="pers_pat" name="pers_pat" type="text" maxlength="50" class="input-sm text-uppercase">
                                    </label>                                    
                                </section>
                                <section class="col col-3" style="padding-left:5px;padding-right:5px;">
                                    <label class="label">Ape.Materno:</label>
                                    <label class="input">
                                        <input id="pers_mat" name="pers_mat" type="text" maxlength="50" class="input-sm text-uppercase">
                                    </label>                                                                     
                                </section>
                                <section class="col col-6" style="padding-left:5px;">
                                    <label class="label">Nombres:</label>
                                    <label class="input">
                                        <input id="pers_nombres" name="pers_nombres" type="text" maxlength="100" class="input-sm text-uppercase">
                                    </label>                                                                     
                                </section> 
                            </div>
                            <div class="row">                            
                                <section class="col col-9" style="padding-left:12px;">
                                    <label class="label">Razon Social:</label>
                                    <label class="input">
                                        <input id="pers_raz_soc" name="pers_raz_soc" type="text" class="input-sm text-uppercase">
                                    </label>
                                </section>
                                <section class="col col-3" style="padding-left:5px;">
                                <label class="label">Entidad Reciproca:</label>
                                <label><input type="checkbox" name="entidad_reciproca" id="entidad_reciproca" style="width:22px;height:22px" disabled=""></label>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-6" style="padding-right:5px;">
                                    <label class="label">Sexo:</label>                                   
                                    <label class="select">
                                        <select id="pers_sexo" name="pers_sexo" class="input-sm text-uppercase">
                                            <option value="-">Seleccionar</option>
                                            <option value="1">Masculino</option>
                                            <option value="0">Femenino</option>        
                                        </select><i></i> </label>                                     
                                </section>
                                <section class="col col-6" style="padding-left:5px;">
                                    <label class="label">Fecha Nac.:</label>
                                    <label class="input">
                                        <input id="pers_fnac" name="pers_fnac" type="text" data-mask="99/99/9999" data-mask-placeholder="-" placeholder="dia/mes/año" class="input-sm">
                                    </label>                                                                                                          
                                </section>                                
                            </div>
                        </fieldset>
                        <fieldset class="col col-lg-3 text-align-center">
                            <section>
                             <img id="pers_foto" src="{{asset('img/avatars/male.png')}}" name="pers_foto" style="width: 160px;height: 220px;border: 1px solid #fff; outline: 1px solid #bfbfbf;">   
                            </section>
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
                            <li>
                                <a href="#s3" onclick="current_tab(3);" data-toggle="tab">Otro</a>
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
                                        <input type="hidden" id="hiddentxt_hab_urb">
                                        <input id="hab_urb" type="text" placeholder="Avenida, Jiron, Calle o Pasaje." class="input-sm">
                                       <!-- <input type="text" list="list" id="hab_urb" name="hab_urb" placeholder="Habilitación Urbana">
                                        <datalist id="list">
                                            @foreach ($hab_urb as $hab)
                                                <option data-xyz ="{{$hab->id_hab_urb}}" value="{{$hab->nomb_hab_urba}}">{{$hab->id_hab_urb}}</option>
                                            @endforeach
                                        </datalist> -->
                                        </label>
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
                            <div class="tab-pane fade" id="s3">


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
                                            <input type="hidden" id="hiddentxt_hab_urb">
                                            <input id="hab_urb" type="text" placeholder="Avenida, Jiron, Calle o Pasaje." class="input-sm">
                                            <!-- <input type="text" list="list" id="hab_urb" name="hab_urb" placeholder="Habilitación Urbana">
                                        <datalist id="list">
                                            @foreach ($hab_urb as $hab)
                                                    <option data-xyz ="{{$hab->id_hab_urb}}" value="{{$hab->nomb_hab_urba}}">{{$hab->id_hab_urb}}</option>
                                            @endforeach
                                                    </datalist> -->
                                        </label>
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




