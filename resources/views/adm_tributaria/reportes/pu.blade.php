<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Predio Urbano</title>
        <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
        <style>
            @page { margin-top: 20px !important;};
        </style>
  </head>
  <body>
       <div class="datehead">{{ $fecha }}</div>
    <main>
         <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 5px;">
            <tr>
            <td style="width: 10%; border: 0px;" >
                <img src="img/escudo.png" height="70px"/>
            </td>
            <td style="width: 80%; padding-top: 10px; border:0px;">
                <div id="details" class="clearfix">
                   <div id="invoice">
                       <h1 style="font-size: 1.8em">DECLARACION JURADA DE AUTOAVALUO</h1>
                      <div class="sub2" style="font-size: 1.1em">IMPUESTO PREDIAL  - DECRETO LEGISLATIVO Nº 776</div>
                    </div>
                  </div>
            </td>
            <td style="width: 10%;border: 0px;"></td>
            </tr>
            
        </table>
      
      
          
        <table border="0" cellspacing="0" cellpadding="0" style="margin-top: 10px; margin-bottom: 5px">
        <thead>
          <tr>
              <td style=" font-size: 0.7em; width: 20%; border: 0px; text-align: center;">
                  MUNICIPALIDAD DISTRITAL DE CERRO COLORADO
              </td>
              <td style="width: 40%;  border: 0px; padding: 10px 0px 0px 80px;"><div class="resaltado" style="font-size: 3em">PU - {{ $sql->anio }}</div>(Predio Urbano)</td>
                <td style="width: 15%; padding: 0px; border: 0px;text-align: center;font-size: 0.9em; " >
                <div class="cabdiv" style="width: 100%; font-size: 0.9em !important">código contribuyente</div>
                <div class="cuerdiv" style="width: 100%;font-size: 0.9em !important">{{ $sql->id_persona }}</div>
              </td>
              <td style="width: 15%; padding: 0px 0px 0px 2px; border: 0px;text-align: center;font-size: 0.9em; " >
                <div class="cabdiv" style="width: 100%;font-size: 0.9em !important">código del predio</div>
                <div class="cuerdiv" style="width: 100%;font-size: 0.9em !important">{{ $sql->cod_cat }}</div>
              </td>
              
              <td style="width: 10%; padding: 0px 0px 0px 2px; border: 0px;text-align: center;font-size: 0.9em; " >
                <div class="cabdiv" style="width: 100%;font-size: 0.9em !important">ANEXO</div>
                <div class="cuerdiv" style="width: 100%;font-size: 0.9em !important">{{substr("000".$sql->nro_anexo,-4,4)}}</div>
              </td>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      
      
        
        <div class="lado3" style="border-top: double 3px #000; padding-top: 5px;">               
        </div>
        
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 5px">
        <thead>
            <tr>
                <td colspan="2" style="border: 0px; padding: 0px;">
                    DATOS DEL CONTRIBUYENTE / CÓNYUGUE:. 
                </td
                <td rowspan="4" style="width: 20%; padding: 0px !important;">
                  @if (count($foto) >= 1)
        <center><img src="data:image/png;base64,{{$foto[0]->foto}}" height="95px" width="137px"/></center>
                   @endif
              </td>
            </tr>
          <tr>
              <th style="width: 10%">DNI/RUC</th>
              <th style="width: 70%">APELLIDOS Y NOMBRES / RAZON SOCIAL</th>
              
          </tr>
          
        </thead>
        <tbody>
          <tr>
              <td>{{$sql->nro_doc}}</td>
              <td>{{$sql->contribuyente}}</td>
          </tr>
          <tr>
              <td>{{$sql->nro_doc_conv}}</td>
              <td>{{$sql->conviviente}}</td>
          </tr>
        </tbody>

      </table>
        <div class="lado3" >
            UBICACION DEL PREDIO:    
        </div>
        
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0px;" >
        <thead>
          <tr>
              <th style="width: 43%">HABILITACION URBANA</th>
              <th style="width: 30%">CALLE / AV. /PSJE</th>
              <th style="width: 7%">N° MUN.</th>
              <th style="width: 6%">INTER</th>
              <th style="width: 7%">MZNA</th>
              <th style="width: 7%">LOTE</th>
          </tr>
          
        </thead>
        <tbody>
          <tr>
              <td>{{$sql->habilitacion}}</td>
              <td>{{$sql->cod_via."-".$sql->nom_via}}</td>
              <td>{{$sql->nro_mun}}</td>
              <td>{{$sql->nro_int}}</td>
              <td>{{$sql->mzna_dist}}</td>
              <td>{{$sql->lote_dist}}</td>
          </tr>
          <tr>
              <td colspan="6">
                  REFERENCIA: @if($sql->sup_mzna!=''){{'SUP. MNZA '.$sql->sup_mzna.', '}}@endif
                              @if($sql->gpo_zonal!=''){{'GPO. ZONAL '.$sql->gpo_zonal.', '}}@endif 
                              @if($sql->referencia!='-'){{$sql->referencia}}@endif 
                              
              </td>
          </tr>
          
        </tbody>

      </table>
        
        <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
              <th style="width: 25%">CONDICION DE LA PROPIEDAD</th>
              <th style="width: 25%">ESTADO DE LA CONSTRUCCION</th>
              <th style="width: 50%">USO DEL PREDIO</th>
              
          </tr>
          
        </thead>
        <tbody>
          <tr>
              <td>{{$sql->cond_prop_descripc}}</td>
              <td>{{$sql->descripcion}}</td>
              <td>{{$sql->codi_uso."-".$sql->desc_uso}}</td>
          </tr>
          
        </tbody>

      </table>
      <div style="height: 510px">
        <div class="lado3" >
            INFORMACIÓN DE PISOS:    
        </div>
        <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
              <th style="width: 4%">PISO</th>
              <th style="width: 4%">CLAS</th>
              <th style="width: 4%">MAT</th>
              <th style="width: 4%">EST</th>
              <th style="width: 4%">ANT.</th>
              <th style="width: 8%">CATEGORIAS</th>
              <th style="width: 10%">VALOR UNIT M2</th>
              <th style="width: 6%">INCRE.</th>
              <th style="width: 6%">DEPREC (%)</th>
              <th style="width: 10%">VALOR UNIT DEPREC.</th>
              <th style="width: 10%">AREA CONST.</th>
              <th style="width: 10%">AREA COMUN</th>
              <th style="width: 15%">VALOR TOTAL S/.</th>
              
          </tr>
        </thead>
        <tbody>
          @foreach ($sql_pis as $pis)
          <tr>
              <td>{{$pis->cod_piso}}</td>
              <td>{{$pis->clas}}</td>
              <td>{{$pis->mep}}</td>
              <td>{{$pis->esc}}</td>
              <td>{{$pis->ant_ano}}</td>
              <td style="padding-left: 2px;">{{$pis->est_mur.$pis->est_tch.$pis->aca_pis.$pis->aca_pta.$pis->aca_rev.$pis->aca_ban.$pis->ins_ele}}</td>
              <td style="text-align: right;padding-right: 5px;">{{number_format($pis->val_cons,2)}}</td>
              <td style="text-align: right;padding-right: 5px;">{{number_format($pis->inc_pis)}}</td>
              <td style="text-align: right;padding-right: 5px;">{{$pis->por_dep."%"}}</td>
              <td style="text-align: right;padding-right: 5px;">{{ number_format($pis->val_uni_dep,2)}}</td>
              <td style="text-align: right;padding-right: 5px;">{{number_format($pis->area_const,2)}}</td>
              <td style="text-align: right;padding-right: 5px;">{{number_format($pis->val_areas_com,2)}}</td>
              <td style="text-align: right;padding-right: 5px;">{{number_format($pis->val_const_tot,2)}}</td>
          </tr>
          @endforeach
        </tbody>

      </table>
        @if (count($sql_ist) >= 1)
  
          <div class="lado3" >
            INFORMACIÓN DE INSTALACIONES:    
        </div>
        <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
              <th style="width: 3%">COD</th>
              <th style="width: 22%">DESCRIPCION</th>
              <th style="width: 4%">ANTIG.</th>
              <th style="width: 4%">MEP</th>
              <th style="width: 4%">ECS</th>
              <th style="width: 4%">ECC</th>
              <th style="width: 4%">LARGO</th>
              <th style="width: 4%">ANCHO</th>
              <th style="width: 4%">ALTO</th>
              <th style="width: 4%">UND. MED</th>
              <th style="width: 10%">PROD.TOTAL</th>
              
          </tr>
          
        </thead>
        <tbody>
        @foreach ($sql_ist as $inst)
          <tr>
              <td>{{$inst->cod_instal}}</td>
              <td style="font-size:0.6em;">{{substr($inst->descrip_instal,0,30)}} ; {{$inst->glosa}}</td>
              <td style="text-align: center">{{$inst->anio}}</td>
              <td style="text-align: center">{{$inst->mep}}</td>
              <td style="text-align: center">{{$inst->ecs}}</td>
              <td style="text-align: center">{{$inst->ecc}}</td>
              <td style="text-align: right;padding-right: 5px;">{{$inst->dim_lar}}</td>
              <td style="text-align: right;padding-right: 5px;">{{$inst->dim_anch}}</td>
              <td style="text-align: right;padding-right: 5px;">{{$inst->dim_alt}}</td>
              <td style="text-align: center">{{$inst->unid_medida}}</td>
              <td style="text-align: right;padding-right: 5px;">{{$inst->tot_inst}}</td>
              
              
          </tr>
          @endforeach
        </tbody>

      </table>
        @endif
        
        @if (count($sql_cond) >= 1)
          <div class="lado3" >
            INFORMACIÓN DE CONDOMINIOS:    
        </div>
                <table border="0" cellspacing="0" cellpadding="0">
                <thead>
                  <tr>
                      <th style="width: 10%">DNI/RUC</th>
                      <th style="width: 40%">NOMBRE</th>
                      <th style="width: 40%">DIRECCIÓN</th>
                      <th style="width: 10%">% CONDO.</th>
                  </tr>

                </thead>
                <tbody>
                @foreach ($sql_cond as $condos)
                  <tr>
                      <td>{{$condos->nro_doc}}</td>
                      <td>{{ substr($condos->nombres." ".$condos->ape_pat." ".$condos->ape_mat,0,40)}}</td>
                      <td>{{substr($condos->direccion,0,40)}}</td>
                      <td style="text-align: center">{{$condos->porcent}}</td>
                  </tr>
                  @endforeach
                </tbody>

              </table>
         @endif
         
        </div>
        
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0px;">
            <thead>
              <tr>
                  <th style="width: 15%">AREA TERRENO</th>
                  <TD style="width: 2%; border: 0px;"></TD>
                  <th style="width: 15%">AREA T. COMUN</th>
                  <TD style="width: 2%; border: 0px;"></TD>
                  <th style="width: 15%">ARANCEL * M2</th>
                  <TD STYLE="width: 11%;border: 0px;"></TD>
                  <td rowspan="4" STYLE="border: 0px; vertical-align: top;">
                      <table>
                          <tr><td>VALOR DE LA CONSTRUCCION</td><td style="text-align: right;padding-right: 5px; font-size: 1.1em">{{number_format($sql->val_const,2)}}</td></tr>
                          <tr><td>VALOR DE OTRAS INSTALAC.</td><td style="text-align: right;padding-right: 5px;font-size: 1.1em">{{number_format($sql->val_obr_cmp,2)}}</td></tr>
                          <tr><td>VALOR TOTAL DEL TERRENO</td><td style="text-align: right;padding-right: 5px;font-size: 1.1em">{{number_format($sql->val_ter,2)}}</td></tr>
                          <tr><td>TOTAL AUTOVALUO</td><td style="text-align: right;padding-right: 5px;font-size: 1.1em">{{number_format($sql->base_impon,2)}}</td></tr>

                      </table>
                  </td>
              </tr>
            </thead>
            <tbody>
              <tr>
                  <td style="text-align: right;padding-right: 5px">{{number_format($sql->are_terr,2)}}</td>
                  <td STYLE="border: 0px;">+</td>
                  <td style="text-align: right;padding-right: 5px">{{number_format($sql->are_com_terr,2)}}</td>
                  <td STYLE="border: 0px;">*</td>
                  <td style="text-align: right;padding-right: 5px">{{number_format($sql->arancel,2)}}</td>
                  <td STYLE="border: 0px;"></td>
              </tr>
              <tr>
                  
                  <td colspan="5" style="border:0px">
                  </td>
                  
              </tr>

            </tbody>
          </table>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0px; page-break-after: auto;">
            <thead>
              <tr>
                 
                  
                  <td rowspan="2" style="border-top:0px; border-bottom:0px; border-left: 0px;"></td>
                  <th rowspan="2" style="width: 20%; border-left: 0px;">DECLARO BAJO JURAMENTO QUE LOS DATOS CONSIGNADOS EN ESTA DECLARACION SON VERDADEROS</th>
                  <td  style="width: 20%; border-bottom: 0px;"></td>
                  <td  style="width: 30%; border-bottom: 0px;"></td>

              </tr>
              
              <tr>
                  <td  class="firma" style="width: 20%;"><div class="firma2">fecha</div></td>
                  <td  class="firma" style="width: 30%;"><div class="firma2">firma</div></td>
              </tr>
              
            </thead>
            <div style="margin-bottom: 1px; font-size: 0.6em; text-align:right ;">IMPRESO POR : {{$usuario[0]->usuario}}</div>
        </table>
  </body>
  
</html>
