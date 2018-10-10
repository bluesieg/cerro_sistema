    <!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Hoja Resumen</title>
        <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
        <style>
            @page { margin-top: 20px !important;};
        </style>
  </head>
  <body>
      <div class="datehead">{{ $fecha }}</div>
    <main>
        <table border="0" cellspacing="0" cellpadding="0" style="margin: 0px;">
            <tr>
            <td style="width: 10%; border: 0px;" >
                <img src="img/escudo.png" height="70px"/>
            </td>
            <td style="width: 80%; padding-top: 10px; border:0px;">
                <div id="details" class="clearfix">
                   <div id="invoice">
                       <h1 style="font-size: 1.8em">MUNICIPALIDAD DISTRITAL DE CERRO COLORADO</h1>
                    </div>
                  </div>
            </td>
            <td style="width: 10%;border: 0px;"></td>
            </tr>
            
        </table>
        <div id="details" class="clearfix">
          <div id="invoice" >
         
          
          
          <div class="lado" style="text-align: left !important; padding-top: 20px;">GERENCIA DE ADM. TRIBUTARIA</div>
          <div class="lado">
              <div class="sub">HOJA DE RESUMEN</div>
          </div>
          <div class="lado" style="text-align: right !important">PERIODO<BR><div class="resaltado" >{{ $an }}</div></div>
          <div Class="asunto" style="margin-top: 5px;">DECLARACION JURADA DE AUTOVALUO</div>
          <div class="subasunto">LEY TRIBUTARIA MUNICIPAL/DECRETO LEGISLATIVO 776</div>
          <table border="0" cellspacing="0" cellpadding="0" style="margin: 0px;">
              <tr>
                  <td style="width:75%;vertical-align: bottom; border: 0px; padding: 0px;">
                    IDENTIFICACION DEL CONTRIBUYENTE: Si es casado anotar datos del Conyugue
                  </td>
                  <td style="text-align: center;border: 0px">
                      <div class="cabdiv">COD. CONTRIBUYENTE</div>
                      <div class="cuerdiv">{{ $sql->id_persona }}</div>
                  </td>
              </tr>
          </div>
        
                
          </table>
        </div>
      </div>
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
              <td class="nro">1</td>
              <th style="width: 12%">TIPO PERSONA</th>
              <td class="nro">2</td>
            <th style="width: 20%">NUMERO DE DOCUMENTO</th>
            <td class="nro" >3</td>
            <th style="width: 60%">APELLIDOS y NOMBRES / RAZON SOCIAL</th>
            <th style="width: 10%; font-size: 0.7em;">Nº EXP.</th>
          </tr>
        </thead>
        <tbody>
          <tr>
              <td colspan="2" style="text-align: center">{{ $sql->persona }}</td>
              <td colspan="2" style="text-align: center">{{ $sql->nro_doc }}</td>
              <td colspan="2" style="text-align: center">{{ $sql->contribuyente }}</td>
              <td colspan="1" style="text-align: center">{{ $sql->num_expediente }}</td>
              
            </tr>
        </tbody>
<!--        <tfoot>
          <tr>
           
            
          </tr>
        </tfoot>-->
      </table>
      <div class="lado3">
                IDENTIFICACION DEL CONYUGUE  /  REPRESENTANTE LEGAL : (Llenar de acuerdo a tabla adjunta y completando datos personales)
      </div>
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
              <td class="nro">4</td>
              <th style="width: 12%">TD</th>
              <td class="nro">5</td>
            <th style="width: 20%">NUMERO DE DOCUMENTO</th>
            <td class="nro">6</td>
            <th style="width: 60%">CONYUGE / REPRESENTANTE LEGAL</th>
          </tr>
        </thead>
        <tbody>
            <tr>
              <td colspan="2" style="text-align: center">{{$sql->tip_doc_conv}}</td>
              <td colspan="2" style="text-align: center">{{$sql->nro_doc_conv}}</td>
              <td colspan="2" style="text-align: center">{{$sql->conviviente}}</td>
            </tr>
        </tbody>

      </table>
        
        <div class="lado3">
            DOMICILIO FISCAL DEL CONTRIBUYENTE / REPRESENTANTE LEGAL.      
        </div>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0px;">
        <thead>
          <tr>
              <td class="nro">7</td>
              <th style="width: 15.16%">DEPARTAMENTO</th>
              <td style="width: 15.16%; text-align: center">{{$sql->dpto}}</td>
              <td class="nro">8</td>
              <th style="width: 15.16%">PROVINCIA</th>
              <td style="width: 15.16%; text-align: center">{{$sql->provinc}}</td>
              <td class="nro">9</td>
              <th style="width: 15.16%">DISTRITO</th>
              <td colspan="2" style="text-align: center">{{substr ($sql->distrit,0,20)}}</td>
          </tr>
          <tr>
              <td class="nro">10</td>
              <th>MANZANA URBANA</th>
              <td style="text-align: center">{{$sql->manz}}</td>
              <td class="nro">11</td>
              <th>LOTE URBANO</th>
              <td style="text-align: center">{{$sql->lote}}</td>
              <td class="nro">12</td>
              <th>SUB LOTE URBANO</th>
              <td colspan="2" ></td>
          </tr>
          <tr>
              <td class="nro">13</td>
              <th colspan="5">HABILITACION URBANA / ZONA / VIA /CALLE</th>
              <td class="nro">14</td>
              <th>NUMERO MUNICIPAL</th>
              <td class="nro">15</td>
              <th>NUMERO INTERIOR/DPTO</th>
          </tr>
          <tr>
              <td colspan="6">{{$sql->dom_fiscal}}, {{$sql->ref_dom_fis}}</td>
              <td colspan="2" style="text-align: center;">{{$sql->nro_mun}}</td>
              <td colspan="2" style="text-align: center;">{{$sql->contri_dpto}}</td>
          </tr>
          <tr>
              <td class="nro">16</td>
              <th>CORREO ELECTRONICO</th>
              <td colspan="4">{{$sql->email}}</td>
              <td class="nro">17</td>
              <th>N° DE TELEFONO</th>
              <td colspan="2">{{$sql->tlfono_celular}}</td>
          </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="10" class="fintd"></td>
            </tr>
        </tbody>
        
      </table>
      @if(count($sql_pre)<=10)
        <div class="lado3" style="height: 420px; border-bottom: 1px solid #333">
            <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0px; font-size: 1.3em;">
      @else
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 5px;">
      @endif
            VALORIZACION DE PREDIOS Y DETERMINACION DE IMPUESTO
        
        <thead>
            <tr >
              <th style="width: 5%;font-size: 0.7em">Anexo</th>
              <th style="width: 5%;font-size: 0.7em">Tip Pred</th>
              <th style="width: 70%;font-size: 0.7em">DIRECCION DEL PREDIO</th>
              <th style="width: 8%;font-size: 0.7em">% Titularidad</th>
              <th style="width: 12%;font-size: 0.7em">Valor Afecto</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($sql_pre as $pre)
            <tr >
              <td style="height: 35px">{{ $pre->anexo }}</td>
              <td style="font-size: 0.7em">{{$pre->tp}}</td>
              <td style="font-size: 0.6em">{{$pre->nom_via." ".$pre->nro_mun." ".($pre->mzna_dist!=null?"MZN ".$pre->mzna_dist:"")." ".($pre->lote_dist!=null?"LT ".$pre->lote_dist:"")." ".($pre->zona!="-"?"ZONA ".$pre->zona:"")." ".($pre->secc!="-"?"SECC ".$pre->secc:"")." ".($pre->dpto!="-"?"DPTO ".$pre->dpto:"")." ".($pre->referencia!=null?$pre->referencia:"")." ".$pre->nomb_hab_urba}}</td>
              <td style="text-align: center;font-size: 0.7em">{{$pre->nro_condominios}}</td>
              <td style="text-align: right; padding-right: 5px;font-size: 0.7em">{{number_format($pre->base_impon_afecto,2,".",",")}}</td>
            </tr>
            @endforeach
        </tbody>
      </table>
      @if(count($sql_pre)<=10)
        </div>
      @else
        
      @endif
        
      @if(count($sql_pre)>=11&&count($sql_pre)<=15)
        <div style="page-break-before:always;"></div>
      @endif
      @if(count($sql_pre)>=40&&count($sql_pre)<=44)
        <div style="page-break-before:always;"></div>
      @endif
      @if(count($sql_pre)>=69&&count($sql_pre)<=73)
        <div style="page-break-before:always;"></div>
      @endif
     
      @if(count($sql_pre)>=98&&count($sql_pre)<=102)
        <div style="page-break-before:always;"></div>
      @endif
      @if(count($sql_pre)>=127&&count($sql_pre)<=131)
        <div style="page-break-before:always;"></div>
      @endif
      @if(count($sql_pre)>=156&&count($sql_pre)<=160)
        <div style="page-break-before:always;"></div>
      @endif
      @if(count($sql_pre)>=185&&count($sql_pre)<=189)
        <div style="page-break-before:always;"></div>
      @endif
      @if(count($sql_pre)>=214&&count($sql_pre)<=218)
        <div style="page-break-before:always;"></div>
      @endif
      @if(count($sql_pre)>=243&&count($sql_pre)<=247)
        <div style="page-break-before:always;"></div>
      @endif
        <table border="0" cellspacing="0" cellpadding="0" >
            <thead>
              <tr>
                  <td style="width: 50%; border:0px;" rowspan="3">
                      @if($sql->id_cond_exonerac==4||$sql->id_cond_exonerac==5)
                        {{$sql->desc_exon}} beneficiario de la deducción de la base imponible del impuesto predial equivalente al 50% UIT D.L. 776 art 19.
                      @endif
                  </td>
                  <td class="nro">18</td>
                  <th style="width: 22.5%">BASE IMPONIBLE</th>
                  <td style="text-align: right; padding-right: 5px;">{{number_format($sql_pre->sum('base_impon_afecto'),2,".",",") }}</td>
              </tr>
              <tr>
                  <td class="nro">19</td>
                  <th>IMPUESTO ANUAL</th>
                  <td style="text-align: right; padding-right: 5px;">{{number_format($sql->ivpp,2,".",",")}}</td>
              </tr>
              <tr>
                  <td class="nro">20</td>
                  <th>IMPUESTO TRIMESTRAL</th>
                  <td style="text-align: right; padding-right: 5px;">{{number_format(($sql->ivpp/4),2,".",",")}}</td>
              </tr>
            </thead>
        </table>
        
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0px;">
            <thead>
              <tr>
                  <td class="nro">21</td>
                  <th rowspan="2" style="width: 15%; border-left: 0px;">total predios declarados</th>
                  <td rowspan="2" style="width: 10%; font-size: 1.2em; text-align: center">{{$sql_pre->count()}}</td>
                  <td rowspan="3" style="border-top:0px; border-bottom:0px; border-left: 0px;"></td>
                  <th rowspan="3" style="width: 20%; border-left: 0px;">DECLARO BAJO JURAMENTO QUE LOS DATOS CONSIGNADOS EN ESTA DECLARACION SON VERDADEROS</th>
                  <td rowspan="2" style="width: 20%; border-bottom: 0px;"></td>
                  <td rowspan="2" style="width: 30%; border-bottom: 0px;"></td>

              </tr>
              <tr>
                  <th style="border-right: 0px;"></th>
              </tr>
              <tr>
                  <td colspan="3" style="border:0px;"></td>
                  <td  class="firma" style="width: 20%;"><div class="firma2">fecha</div></td>
                  <td  class="firma" style="width: 30%;"><div class="firma2">firma</div></td>
              </tr>
              
            </thead>
            <div style="padding-top: 1px; font-size: 0.6em; text-align:right ;">IMPRESO POR : {{$usuario[0]->usuario}}</div>
        </table>
  </body>
</html>
