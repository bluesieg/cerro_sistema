<?php

Route::get('/', function () {
    return view("auth/login");
});

Route::get('home', 'map\MapController@index');

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->get('logout', 'Auth\LoginController@logout')->name('logout');

$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//$this->post('register', 'Auth\RegisterController@register');
//Route::post('registro','Usuarios@postRegistro')->name('registro_user');
Route::group(['middleware' => 'auth'], function() {//YOHAN MODULOS
    Route::get('uit', 'configuracion\Oficinas_Uit@get_alluit')->name('uit'); // tabla..
    Route::get('list_uit', 'configuracion\Oficinas_Uit@index'); // tabla grilla uit
    Route::post('uit_save', 'configuracion\Oficinas_Uit@insert'); // ruta para guardar
    Route::post('uit_mod', 'configuracion\Oficinas_Uit@modif');
    Route::post('uit_quitar', 'configuracion\Oficinas_Uit@eliminar');

    Route::get('oficinas', 'configuracion\Oficinas_Uit@get_alloficinas')->name('oficinas'); // tabla grilla Clientes
    Route::get('list_oficinas', 'configuracion\Oficinas_Uit@index1'); // tabla grilla uit
    Route::post('oficinas_mod', 'configuracion\Oficinas_Uit@modif_ofi');
    Route::post('oficinas_insert_new', 'configuracion\Oficinas_Uit@oficinas_insert_new');
    Route::post('oficinas_delete', 'configuracion\Oficinas_Uit@oficinas_delete');
});
//Route::get('/home', 'HomeController@index')->name('home');

//Route::get('/vw_general', 'General@index')->name('vw_general');
//
Route::get('fracc', 'General@fraccionamiento');
Route::get('dni',function(){
    $rq		= new \stdClass();
$rq->data	= new \stdClass();
$rq->auth	= new \stdClass();


$rq->auth->dni	= '80673320';		// DNI del usuario
$rq->auth->pas	= 'Pr0gr4m4';	// Contrasenia
$rq->auth->ruc	= '20159515240';	// RUC de la entida del usuario


$rq->data->ws	= 'getDatosDni';	// Web Service al que se va a llamar
$rq->data->dni	= '40524155';		// Dato que debe estar acorde al contrato del ws
$rq->data->cache= 'true';		// Retira informacion del Cache local (true mejora la velocidad de respuesta


$url = 'https://ehg.pe/delfos/';		// Endpoint del WS
$options = array(
    	'http' => array(
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($rq)
    )
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === FALSE) 
{  
  echo 'Error de conexion';
}

$rpta = json_decode($result);


if($rpta->resp->code == '0000')
{
    $Lista=new \stdClass();
    $Lista->ape_pat=$rpta->data->apPrimer;
    $Lista->ape_mat=$rpta->data->apSegundo;
    $Lista->nombres=$rpta->data->prenombres;
    $Lista->est_civil=$rpta->data->estadoCivil;
    $Lista->dir=$rpta->data->direccion;
    $Lista->ubigeo=$rpta->data->ubigeo;
//            $Lista->foto='http://ws.ehg.pe'.$rpta->data->foto;
    $Lista->foto='https://ehg.pe/delfos/'.$rpta->data->foto;
    return response()->json($Lista);
//    echo $rpta->data->apPrimer."\n";
//    echo $rpta->data->apSegundo."\n";   
//    echo $rpta->data->prenombres."\n";
//    echo $rpta->data->estadoCivil."\n";
//    echo $rpta->data->direccion."\n";
//    echo $rpta->data->ubigeo."\n" ;
//    echo 'https://ws.ehg.pe'.$rpta->data->foto."\n"; 
//    echo $rpta->data->cache."\n";

 var_dump($rpta);
}
else
{
 echo $rpta->resp->code.'-'.$rpta->resp->text;
}

});

Route::group(['middleware' => 'auth'], function() {
    /******************** ********    CONFIGURACION CATASTRAL   ****************************/
    Route::group(['namespace'=>'catastro'],function(){

        /******************** *****  SECTORES   ***************************/
        Route::resource('catastro_sectores','SectoresController');
        Route::get('list_sectores','SectoresController@getSectores');
        Route::post('insert_new_sector', 'SectoresController@insert_new_sector');
        Route::post('update_sector', 'SectoresController@update_sector');
        Route::post('delete_Sector', 'SectoresController@delete_sector');

        /******************** *****  MANZANAS   ***************************/
        Route::resource('catastro_mzns','ManzanaController');
        Route::get('list_mzns_sector','ManzanaController@getManzanaPorSector');
        Route::post('insert_new_mzna', 'ManzanaController@insert_new_mzna');
        Route::post('create_mzna_masivo', 'ManzanaController@create_mzna_masivo');
        Route::post('update_mzna', 'ManzanaController@update_mzna');
        Route::post('delete_mzna', 'ManzanaController@delete_mzna');

        /******************** *****  ARANCELES RUSTICOS   ***************************/
        Route::resource('catastro_aran_rust','ArancelesRusticosController');
        Route::get('list_aran_pred_rust','ArancelesRusticosController@getArancelRustPorAnio');
        Route::post('insert_new_pred_rust', 'ArancelesRusticosController@insert_new_pred_rust');
        Route::post('update_pred_rust', 'ArancelesRusticosController@update_pred_rust');
        Route::post('delete_pred_rust', 'ArancelesRusticosController@delete_aran_pred_rust');
    });
/******************** ********    MAP CONTROLLER   ******************  **********/
    Route::group(['namespace'=>'map'],function(){
        Route::get('cartografia', 'MapController@index')->name('home');
        Route::get('getlimites', 'MapController@get_limites')->name('get.limites');
        Route::get('getsectores', 'MapController@get_sectores')->name('get.sectores');
        Route::get('getmznas', 'MapController@get_manzanas')->name('get.manzanas');
        Route::post('geogetmznas_x_sector', 'MapController@geogetmznas_x_sector');
        Route::post('get_centro_sector', 'MapController@get_centro_sector');
        Route::post('mznas_x_sector', 'MapController@mznas_x_sector');
        Route::post('get_lotes_x_sector', 'MapController@get_lotes_x_sector');
        Route::post('get_predios_rentas','MapController@get_predios_rentas');
    });
    /******************************      MANTENIMIENTO   USUARIOS ********************************************************/
    Route::get('list_usuarios', 'Usuarios@index'); // tabla grilla Usuarios
    Route::get('/usuarios', 'Usuarios@vw_usuarios_show')->name('usuarios'); //vw_usuarios
    Route::post('usuario_save', 'Usuarios@insert_Usuario');
    Route::post('usuario_update', 'Usuarios@update_Usuario');
    Route::post('usuario_delete', 'Usuarios@eliminar_usuario'); //eliminar usuario
    Route::get('usuarios_validar_user','Usuarios@validar_user');
    Route::get('usuarios_validar_dni','Usuarios@validar_dni');
    Route::get('get_datos_usuario','Usuarios@get_datos_usuario');
    Route::post('cambiar_foto_user','Usuarios@cambiar_foto_usuario');
    Route::post('cambiar_pass_user','Usuarios@cambiar_pass_user');

    /*     * **************************AUTOLLENADO DE COMBOS********************************************************************* */
    Route::get('get_all_tipo_documento', 'General@get_tipo_doc'); //llena combo tipo documento
    Route::get('get_all_cond_exonerac', 'General@get_cond_exonerac'); //llena combo condicion exonerac
    Route::get('autocompletar_direccion', 'General@autocompletar_direccion'); //autocompleta el input text avenida,jiro, calle de contribuyentes
    Route::get('autocompletar_tipo_uso', 'General@autocompletar_tipo_uso'); //autocompleta tipos de uso
    Route::get('autocompletar_insta', 'General@autocompletar_instalaciones'); //autocompleta tipos de uso
    Route::get('sel_viaby_sec', 'General@sel_viaby_sec'); //seleccionas vias por mazana y sector
    Route::get('sel_cat_gruterr', 'General@sel_cat_gruterr'); //seleccionas vias por mazana y sector
    Route::get('autocomplete_nom_via', 'configuracion\Valores_Arancelarios@get_autocomplete_nom_via'); //autocompletar arancel cod_via->nom_completo de via
    Route::get('autocomplete_hab_urb', 'General@autocomplete_hab_urb'); //autocomplentar habilitaciones urbanas

    /*     * *******************DEPARTAMENTO  PROVINCIA DISTRITO  **************************************************************** */
    Route::get('get_all_dpto', 'General@get_dpto'); //llena combo departamentos
    Route::get('get_all_prov', 'General@get_prov'); //llena combo provincias
    Route::get('get_all_dist', 'General@get_dist'); //llena combo Distritos
    /*     * ********************************************************************************************************************* */
    /*     * **************************************CONTRIBUYENTES******************************************************************** */
//    Route::get('contribuyentes', 'adm_tributaria\Contribuyentes@vw_contribuyentes'); // VW_CONTRIUYENTES
    
    Route::post('insert_personas_user','Usuarios@insert_persona_user');
    Route::group(['namespace' => 'adm_tributaria'], function() {
        Route::resource('contribuyentes','ContribuyentesController');
        Route::get('consultar_persona','ContribuyentesController@consultar_persona');
        Route::post('insert_personas','ContribuyentesController@insert_persona');
        
        Route::get('grid_contribuyentes', 'ContribuyentesController@grid_contrib'); // tabla grilla Contribuyentes 
        Route::get('obtiene_cotriname', 'ContribuyentesController@get_cotrib_byname'); //
        Route::get('pre_rep_contr/{sect}/{mzna}/{anio}','ContribuyentesController@reporte_contribuyentes');
        Route::get('pre_rep_contr_hab_urb/{cod_hab_urb}/{anio}','ContribuyentesController@reporte_contribuyentes_hab_urb');
        Route::get('pre_rep_contr_otro','ContribuyentesController@reporte_contribuyentes_otro');
        Route::get('get_datos_dni','ContribuyentesController@get_datos_dni');
        Route::get('get_datos_ruc','ContribuyentesController@get_datos_ruc');
//        Route::post('insert_new_contribuyente', 'adm_tributaria\Contribuyentes@insert_new_contribuyente');
        /*ENVIO DE DOCUEMNTOS EJECUCION COACTIVA*/
        Route::resource('envio_doc_coactiva','EnvDocCoactivaController');
        Route::get('recaudacion_get_op', 'EnvDocCoactivaController@fis_getOP');        
        Route::get('updat_env_doc','EnvDocCoactivaController@up_env_doc');
        Route::get('listado_op','EnvDocCoactivaController@imp_op');
    });
    Route::group(['namespace' => 'presupuesto'], function() {
        Route::resource('generica', 'GenericaController');
        Route::get('get_generica','GenericaController@get_generica');
        Route::resource('sub_generica', 'SubGenericaController');
        Route::get('get_subgenerica','SubGenericaController@get_subgenerica');
        Route::resource('sub_gen_detalle', 'SubGenDetalleController');
        Route::get('get_subgenerica_detalle','SubGenDetalleController@get_subgen_detalle');
        Route::resource('especifica', 'EspecificaController');
        Route::get('get_especifica','EspecificaController@get_espec');
        Route::resource('especifica_detalle', 'Esp_DetalleController');
        Route::get('get_esp_detalle', 'Esp_DetalleController@get_esp_detalle');
        Route::resource('procedimientos', 'ProcedimientoController');
        Route::get('get_procedimientos','ProcedimientoController@get_procedimientos');
        Route::get('autocompletar_oficinas','ProcedimientoController@autocompletar_oficinas');
        Route::get('auto_esp_detalle','ProcedimientoController@autocompletar_esp_detalle');
        Route::resource('tributos', 'TributosController');
        Route::get('get_tributos','TributosController@get_tributos');
    });
    
    Route::get('llenar_form_contribuyentes', 'adm_tributaria\Contribuyentes@llenar_form_contribuyentes'); //llena form contribuyentes
    Route::post('contribuyente_update', 'adm_tributaria\Contribuyentes@modificar_contribuyente'); //update contribuyente
    
    Route::get('autocomplete_contrib', 'adm_tributaria\Contribuyentes@get_autocomplete_contrib'); //eliminar contribuyente
    
    Route::get('obtiene_cotriop', 'adm_tributaria\Contribuyentes@get_cotrib_op'); //
    Route::get('obtener_pred_ctb/{id}', 'adm_tributaria\Contribuyentes@get_predios_contrib'); //
    /*     * ******************************************VALORES ARANCELARIOS******************************************************************** */
    Route::group(['namespace' => 'configuracion'], function() {
        Route::get('val_aran', 'Valores_Arancelarios@vw_val_arancel'); // VW_ARANCELES
        Route::get('grid_val_arancel', 'Valores_Arancelarios@grid_valores_arancelarios'); // tabla grilla Valores Arancelarios
        Route::get('get_anio_val_arancel', 'Valores_Arancelarios@get_anio'); //llena combo AÑO vw_val_arancel
        Route::get('get_sector_val_arancel', 'Valores_Arancelarios@get_sector'); //llena combo SECTOR vw_val_arancel
        Route::get('get_mzna_val_arancel', 'Valores_Arancelarios@get_mzna'); //llena combo MANZANAvw_val_arancel        
        Route::post('insert_valor_arancel', 'Valores_Arancelarios@insert_valor_arancel');
        Route::post('update_valor_arancel', 'Valores_Arancelarios@update_valor_arancel');
        Route::post('delete_valor_arancel', 'Valores_Arancelarios@delete_valor_arancel');
    });
    /*     * ****************************************   VALORES UNITARIOS    ************************************************************** */
    Route::group(['namespace' => 'configuracion'], function() {
        Route::get('valores_unitarios', 'Valores_Unitarios@show_vw_val_unit'); // VW_VALORES_UNITARIOS
        Route::get('grid_val_unitarios', 'Valores_Unitarios@grid_val_unitarios'); // tabla grilla VALORES UNITARIOS
        Route::get('create_magic_grid_val_unit', 'Valores_Unitarios@magic_grid_valores_unit'); // EXECUTE FUNCTION POSTGRES... VALORES UNITARIOS
        Route::post('update_valor_unitario', 'Valores_Unitarios@update_valor_unitario');
    });
    /******************** ********    TESORERIA     ****   EMISION DE RECIBOS DE PAGO            ************************************/
    Route::group(['namespace' => 'tesoreria'], function() {
        Route::resource('emi_recibo_master', 'Recibos_MasterController');
        Route::resource('emi_recibo_detalle', 'Recibos_DetalleController');
        Route::get('grid_Resumen_recibos','Recibos_MasterController@tabla_Resumen_recibos');
        Route::get('autocompletar_tributo','Recibos_MasterController@completar_tributo');// recibos varios
        Route::get('emi_recib_buscar_persona','Recibos_MasterController@buscar_persona');
        Route::post('insert_new_persona','Recibos_MasterController@insert_new_persona');
        Route::get('get_grid_cta_cte2','Recibos_MasterController@tabla_cta_cte_2');
        Route::get('grid_pred_arbitrios','Recibos_MasterController@tabla_cta_arbitrios');
        Route::get('grid_cta_pago_arbitrios','Recibos_MasterController@cta_pago_arbitrios');
        Route::get('verif_est_cta_coactiva','Recibos_MasterController@verif_est_cta');
    });
    Route::group(['namespace' => 'caja'], function() {
        Route::resource('caja_movimient','Caja_MovimientosController');
        Route::get('imp_pago_rec','Caja_MovimientosController@reportes_caja_mov');
        Route::get('grid_Caja_Movimientos','Caja_MovimientosController@get_grid_Caja_Mov');
        Route::get('verif_apertura_caja','Caja_MovimientosController@verif_aper_caja');
        Route::get('apertura_caja','Caja_MovimientosController@apertura_caja');
        Route::get('cierre_caja','Caja_MovimientosController@cierre_caja');
        Route::get('reporte_diario_caja/{id_caja}','Caja_MovimientosController@rep_dia_caja');
    });
    Route::group(['namespace'=>'caja'],function(){///ESTADO DE CUENTAS
        Route::resource('estado_de_cta','Caja_Est_CuentasController');
        Route::get('caja_est_cta_contrib','Caja_Est_CuentasController@caja_est_cuentas');
        Route::get('caja_imp_est_cta/{id_contrib}/{desde}/{hasta}','Caja_Est_CuentasController@print_est_cta_contrib');
        Route::get('est_cta_fracc','Caja_Est_CuentasController@vw_fracc_est_cta');
        Route::get('get_conv_fracc_estcta','Caja_Est_CuentasController@conv_fracc_estcta');
        Route::get('get_det_fracc','Caja_Est_CuentasController@get_det_fracc');
        Route::get('imp_est_cta_fracc/{id_contrib}/{id_conv}','Caja_Est_CuentasController@print_estcta_fracc');
    });
    Route::group(['namespace' => 'fraccionamiento'], function() {//FRACCIONAMIENTO DE PAGOS PREDIAL
        Route::resource('config_fraccionamiento','configuracion\Fraccionamiento');        
        Route::resource('conve_fraccionamiento','ConvenioController');
        Route::resource('convenio_detalle','Convenio_DetalleController');
        Route::get('grid_deu_contrib_arbitrios','ConvenioController@list_deuda_contrib');
        Route::get('grid_Convenios','ConvenioController@grid_all_convenios');
        Route::get('imp_cronograma_Pago_Fracc','ConvenioController@crono_pago_fracc');
        Route::get('grid_fracc_de_contrib','ConvenioController@fracc_de_contrib');
        Route::get('grid_detalle_fracc','ConvenioController@detalle_fracc');
    });    
    Route::group(['namespace'=>'coactiva'],function(){///COACTIVA/////////////
        Route::resource('coactiva','CoactivaController');
        Route::get('gestion_expedientes','CoactivaController@gest_exped');
        Route::get('get_exped','CoactivaController@get_expedientes');
        Route::get('get_doc_exped','CoactivaController@get_docum_expediente');
        Route::get('coactiva_recep_doc','CoactivaController@get_doc');
        Route::get('recib_doc_coactiva','CoactivaMasterController@resep_documentos_op');
        Route::get('recib_doc_coactiva_rd','CoactivaMasterController@resep_documentos_rd');
        Route::get('add_documento_exped','CoactivaController@add_documento');
        Route::get('abrirdocumento/{id_doc}/{id_coa_mtr}','CoactivaController@open_document');
        Route::get('editar_resol','CoactivaController@editar_resol');
        Route::get('editar_acta_aper','CoactivaController@editar_acta_aper');
        Route::post('update_documento','CoactivaController@update_documento');
        Route::get('agreg_fch_recep_notif','CoactivaController@fch_recep_notif');
        Route::get('num_letra','CoactivaController@letra');
        
        Route::get('emision_rec','CoactivaController@emision_apertura_resolucion');
        Route::get('recepcion_doc','CoactivaController@recep_doc');
        Route::get('coactiva_gen_resolucion','CoactivaController@get_doc_recibidos');
        Route::get('all_resoluciones','CoactivaController@grid_all_resoluciones');
    });
    
    Route::group(['namespace' => 'adm_tributaria'], function() {
        Route::resource('predios_urbanos', 'PredioController');
        Route::resource('predios_rural', 'PredioRuralController');
        Route::resource('pisos_predios', 'PisosController');
        Route::resource('condominios_predios', 'CondominiosController');
        Route::resource('instalaciones_predios', 'InstalacionesController');
        Route::resource('pensionista_predios', 'PensionistaController');
        Route::resource('arbitrios_municipales', 'ArbitriosController');
        Route::get('getfrecbarrido/{an}','ArbitriosController@barrido_by_an');
        Route::get('getfrecserenazgo/{an}','ArbitriosController@serenazgo_by_an');
        Route::get('getfrecparques/{an}','ArbitriosController@paques_by_an');
        Route::get('getfrecrrs','ArbitriosController@frec_rrs');
        Route::get('gridpredio','PredioController@listpredio');
        Route::get('gridpisos/{id}','PisosController@listpisos');
        Route::get('gridcondos/{id}','CondominiosController@listcondos');
        Route::get('gridinsta/{id}','InstalacionesController@listinsta');
        Route::get('gridarbitrios','ArbitriosController@listarbitrios');
        Route::get('selmzna','PredioController@ListManz');
        Route::get('sellot','PredioController@ListLote');
        Route::get('adm_impform/','PredioController@imprimir_formatos');
        Route::get('pre_rep/{tip}/{id}/{an}/{per}','PredioController@reporte');
        Route::get('traefoto_lote/{sec}/{mzna}/{lote}','PredioController@getfoto');
        Route::get('validar_predio','PredioController@validar');
        
    });
    Route::group(['namespace' => 'recaudacion'], function() {//modulo de fiscalizacion
        Route::resource('ordenpago', 'OrdenPagoController');
        Route::get('fis_rep/{tip}/{id}/{sec}/{man}','OrdenPagoController@reporte');
        Route::get('obtiene_op/{dat}/{sec}/{manz}/{an}/{ini}/{fin}', 'OrdenPagoController@getOP'); //
        Route::get('obtiene_con_sec', 'OrdenPagoController@getcontrbsec'); //
        Route::get('notifica_op', 'OrdenPagoController@notifica_op_index'); //
        Route::get('mod_noti_op', 'OrdenPagoController@edit_op_fec'); //
        Route::get('reportes_op', 'OrdenPagoController@index_reportes_op'); //
        Route::get('ver_rep_op/{anio}/{tipo}', 'OrdenPagoController@ver_reporte_op'); 
    });  
    Route::group(['namespace' => 'alcabala'], function() {//modulo de alcabala
        Route::resource('alcabala', 'AlcabalaController');
        Route::get('trae_acabala/{an}/{id}/{tip}/{num}/{ini}/{fin}', 'AlcabalaController@get_alcabala'); //
        Route::get('alca_manten_doc', 'AlcabalaController@manten_docs'); //
        Route::get('alcabala_conf', 'AlcabalaController@mantenimiento'); //
        Route::get('deduccion_save', 'AlcabalaController@ded_create'); //
        Route::get('tasa_save', 'AlcabalaController@tas_create'); //
        Route::get('natcontra_save', 'AlcabalaController@contra_create'); //
        Route::get('doctrans_save', 'AlcabalaController@transfer_create'); //
        Route::get('transina_save', 'AlcabalaController@inafecto_create'); //
        Route::get('grid_deduc', 'AlcabalaController@get_deduc'); //
        Route::get('grid_tasas', 'AlcabalaController@get_tasas'); //
        Route::get('grid_nat_contra', 'AlcabalaController@get_contra'); //
        Route::get('grid_doc_trans', 'AlcabalaController@get_transfe'); //
        Route::get('grid_trans_ina', 'AlcabalaController@get_inafecto'); //
        Route::get('alcab_rep/{id}','AlcabalaController@reporte');
    });  
    Route::group(['namespace' => 'fiscalizacion'], function() {//modulo de fiscalizacion
        /////carta de requerimiendo
        Route::resource('carta_reque', 'Carta_RequerimientoController');
        Route::get('carta_set_fisca', 'Carta_RequerimientoController@fisca_enviados_create'); //
        Route::get('car_req_rep/{id}', 'Carta_RequerimientoController@carta_repo'); //
        Route::get('trae_cartas/{an}/{contr}/{ini}/{fin}/{num}', 'Carta_RequerimientoController@get_cartas_req'); //
        Route::get('trae_pred_carta/{car}', 'Carta_RequerimientoController@get_predios_carta'); //
        Route::get('trae_fisca_carta/{car}', 'Carta_RequerimientoController@get_fisca_enviados'); //
        Route::get('fis_env_del', 'Carta_RequerimientoController@fisca_enviado_destroy'); //
        Route::get('carta_anula', 'Carta_RequerimientoController@fisca_enviado_destroy'); //
        //// ficha de verificacion
        Route::resource('ficha_veri', 'Ficha_verificacionController');
        Route::resource('piso_fisca', 'Pisos_ficController');
        Route::get('traepisos_fic/{id}/{fic}', 'Pisos_FicController@listpisos_fic'); //
        Route::resource('insta_fisca', 'Instalaciones_ficController');
        Route::get('traeinsta_fic/{id}/{fic}', 'Instalaciones_FicController@listinsta_fic'); //
        ////// hoja de liquidación
        Route::resource('hoja_liquidacion', 'Hoja_liquidacionController');
        Route::get('hoja_liq_rep/{id}', 'Hoja_liquidacionController@hoja_repo'); //
        Route::get('trae_hojas_liq/{an}/{contr}/{ini}/{fin}/{num}', 'Hoja_liquidacionController@get_hojas_liq'); //
        /////// resolucion de determinación
        Route::resource('reso_deter', 'Res_DeterminacionController');
        Route::get('rd_rep/{id}', 'Res_DeterminacionController@rd_repo');
        Route::get('trae_rd/{an}/{contr}/{ini}/{fin}/{num}', 'Res_DeterminacionController@get_rd'); //
        /////// coactiva
        Route::get('env_rd_coactiva','EnvRD_CoactivaController@vw_env_rd_coa');
        Route::get('fisca_get_rd','EnvRD_CoactivaController@fis_get_RD');
        Route::get('update_env_rd','EnvRD_CoactivaController@fis_env_rd');
        
    });  
    Route::get('$',function(){ echo 0;});//url auxiliar
    /*************************************** - REPORTES - *************************************** */
    Route::group(['namespace' => 'reportes'], function() {
        Route::resource('reportes', 'ReportesController');
                    /******************** CONTRITUYENTES  ********************/
        Route::get('pre_rep_contr_r4/{anio_r4}','ReportesController@contribuyentes_r4');
        Route::get('pre_rep_contr/{sect}/{mzna}/{anio}','ReportesController@reporte_contribuyentes');
        Route::get('pre_rep_contr_otro/{sect}/{mzna}/{anio}','ReportesController@reporte_contribuyentes_otro');
        Route::get('pre_rep_contr_hab_urb/{cod_hab_urb}/{anio}','ReportesController@reporte_contribuyentes_hab_urb');
        Route::get('pre_rep_contr_pred_hu/{cod_hab_urb}/{anio}','ReportesController@reporte_contribuyentes_pred_hu');
        Route::get('pre_rep_prin_contr/{anio}/{min}/{max}/{num_reg}','ReportesController@reporte_prin_contribuyentes');
        Route::get('pre_rep_condic/{anio}/{sec}/{tip}','ReportesController@reporte_por_condicion');
        Route::get('pre_rep_num_pred_uso/{anio}/{sec}/{tip}','ReportesController@reporte_num_pred_uso');

    });
    /*************************************** - PERMISOS - *************************************** */
    Route::group(['namespace' => 'permisos'], function() {
        Route::resource('modulos', 'ModulosController');
        Route::resource('sub_modulos', 'Sub_ModulosController');
        Route::resource('permisos', 'Permisos_Modulo_UsuarioController');
    });
    /*************************************** - Archivo - *************************************** */
    Route::group(['namespace' => 'archivo'], function() {
        Route::resource('archi_contribuyentes', 'Arch_ContribuyenteController');
        Route::get('list_arch_contrib', 'Arch_ContribuyenteController@grid_contrib');
        Route::resource('archi_expe', 'DigitalizacionController');
        Route::get('list_arch_expe', 'DigitalizacionController@grid_expe');
        Route::post('callpdf', 'DigitalizacionController@get_pdf');
        Route::post('create_exp', 'DigitalizacionController@create');
        Route::post('modifica_exp', 'DigitalizacionController@edit');
        Route::get('ver_file/{id}', 'DigitalizacionController@verfile'); //
        Route::get('grid_contrib_arch', 'DigitalizacionController@get_cotrib_byname'); //
        Route::get('validar_expe_arch', 'Arch_ContribuyenteController@validar'); //
        Route::get('validar_dir', 'DigitalizacionController@validar'); //
        Route::resource('arch_busqueda', 'BusquedasController'); //
        Route::get('busque_contrib_arch', 'BusquedasController@get_cotrib_byname'); //
        Route::get('busque_archivo', 'BusquedasController@get_cotrib'); //
        Route::get('busque_arch_expe', 'BusquedasController@grid_expe_busqueda'); //
        Route::get('rep_archivo', 'DigitalizacionController@index_reportes_arch'); //
        Route::get('ver_rep_arch/{contri}/{tipo}', 'DigitalizacionController@ver_reporte_arc'); 
    });
     /*************************************** - GONZALO - *************************************** */
    Route::group(['namespace' => 'reportes_gonzalo'], function() {
        Route::resource('reportes_gonza', 'ReportesController');
        Route::get('reporte_contribuyentes/{anio}/{min}/{max}/{num_reg}','ReportesController@reportes_contribuyentes');
        Route::get('reporte_supervisores/{anio}/{sector}/{manzana}', 'ReportesController@reportes'); 
        Route::get('listado_datos_contribuyentes/{anio}/{sector}', 'ReportesController@listado_contribuyentes'); 
        Route::get('listado_contribuyentes_predios/{anio}/{sector}','ReportesController@listado_contribuyentes_predios');
        Route::get('reporte_contribuyentes_exonerados/{anio}/{sector}/{tipo}','ReportesController@reporte_contribuyentes_exonerados');
        Route::get('reporte_cantidad_contribuyentes/{anio}/{sector}','ReportesController@reporte_cantidad_contribuyentes');
        
        //TRAER USUARIOS
        Route::get('reporte_usuarios/{id}', 'ReportesController@reporte_usuarios');
        Route::get('obtener_usuarios', 'ReportesController@get_usuarios'); 
        
        //*NUEVOS
        Route::get('reporte_contribuyentes_predios_zonas/{anio}/{sector}','ReportesController@reporte_contribuyentes_predios_zonas');
        Route::get('reporte_emision_predial/{anio}/{sector}/{uso}','ReportesController@reporte_emision_predial');
        Route::get('reporte_cant_cont_ded_mont_bas_imp/{anio}/{sector}/{condicion}','ReportesController@reporte_cant_cont_ded_mont_bas_imp');
         
        //REPORTE SUPERVISORES
        Route::get('reporte_supervisores','ReportesController@index_supervisores');
          
    });
    Route::group(['namespace' => 'catastro_gonzalo'], function() {
       
        //CONFIGURACION CATASTRO_GONZALO CALLES - VIAS
        Route::resource('conf_vias_calles', 'ViasController');
        Route::post('insertar_nueva_via_calle', 'ViasController@insertar_nueva_vc');
        Route::get('listar_vias','ViasController@getVias');
        Route::post('modificar_via_calle', 'ViasController@modificar_vc');
        Route::post('eliminar_via_calle', 'ViasController@eliminar_vc');

    });
});
