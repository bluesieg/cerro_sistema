<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Example 2</title>
        <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
        <style>
            @page { margin-bottom: 10px !important; margin-left: 70px;margin-right: 70px;};
        </style>
  </head>
  <body>
    <main>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 5px;">
            <tr>
            <td style="width: 10%; border: 0px;" >
                <img src="img/escudo.png" height="70px"/>
            </td>
            <td style="width: 80%; padding-top: 10px; border:0px;">
                <div id="details" class="clearfix">
                  <div id="invoice" >
                      <h1>MUNICIPALIDAD DISTRITAL DE CERRO COLORADO</h1>
                      <div class="sub2">Creado por Ley 12075 el día 26 de Febrero de 1954</div>
                  </div>
                    <div style="width: 90%; border-top:1px solid #999; margin-top: 10px; margin-left: 25px;"></div>
                </div>
            </td>
            <td style="width: 10%;border: 0px;"></td>
            </tr>
            
        </table>
        
        <center><div Class="asunto" style="margin-top: 5px;">
                @if($sql->flg_anu==0)
                <b>Hoja de Liquidación de Deuda Tributaria (Reparo) N° {{$sql->nro_hoja}}-{{$sql->anio}}-SGFT-GAT-MDCC</b>
                @else
                <b>ANULADO</b>
                @endif
            </div></center>
        <div class="subasunto" style="text-align: right; padding-left: 30px; margin-top: 10px;">Cerro Colorado, {{$sql->fec_reg}}</div>

        <table style="margin-top: 10px; margin-bottom: 5px !important;">
            <tr>
                <td style="border:0px;" colspan="2">
                    <b>I.- IDENTIFICACION DEL CONTRIBUYENTE, PERIODOS Y PREDIOS FISCALIZADOS.</b>
                </td>
            </tr>
            <tr>
                <td style="border:0px; width: 10%">
                    <b> Contribuyente:</b>
                </td>
                <td style="border:0px; width: 90%">
                    <b> {{$sql->pers_nro_doc."-".$sql->contribuyente}}</b>
                </td>
            </tr>
            <tr>
                <td style="border:0px; ">
                    <b> Código:</b>
                </td>
                <td style="border:0px; ">
                    {{$sql->id_persona}}
                </td>
            </tr>
            <tr>
                <td style="border:0px; ">
                    <b> Dirección:</b>
                </td>
                <td style="border:0px; ">
                    {{$sql->ref_dom_fis}}
                </td>
            </tr>
            <tr>
                <td style="border:0px; ">
                    <b> Periodo:</b>
                </td>
                <td style="border:0px; ">
                    {{$sql->anio_fis."-".date("Y")}}
                </td>
            </tr>
          
        </table>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-top: 0px;margin-bottom: 5px;">
            <thead>
              <tr>
                  <th style="width: 80%">Predios Fiscalizados</th>
                  <th style="width: 4%">N°</th>
                  <th style="width: 5%">Zona</th>
                  <th style="width: 6%">Manzana</th>
                  <th style="width: 5%">Lote</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($fichas as $pre)
                    <tr>
                         
                        <td style="font-size: 0.7em">
                            {{$pre->nom_via."-".$pre->habilitacion." ".trim($pre->referencia)}}
                        </td> 
                        <td style="text-align: center">
                            {{$pre->nro_mun}}
                        </td> 
                        <td style="text-align: center">
                            {{$pre->zona}}
                        </td> 
                        <td style="text-align: center">
                            {{$pre->mzna_dist}}
                        </td> 
                        <td style="text-align: center">
                            {{$pre->lote_dist}}
                        </td> 
                    </tr>
                @endforeach
                
            </tbody>
        </table>
        </b>
        <div style="width: 100%; text-align: justify; font-size: 1.0em; margin-top: 0px;">
            <b>II.-Motivo del Reparo.</b><br>
            
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Que, en ejercicio de las facultades de fiscalización y determinación que confiere el TUO del Código Tributario
            a esta Administración, es que a través de las Cartas de Presentación y Requerimiento de Fiscalización 
            N°.{{$sql->nro_car."-".$sql->anio_carta}}-SGTF-GAT-MDCC Notificada {{$sql->carta_fecha_notificacion}},
            
            @if(count($adjuntas)>0)
                @foreach ($adjuntas as $adj)
                        {{'N°'.$adj->nro_car_adj.'-'.$adj->anio_adj.'-SGFT-GAT-MDCC Notificada '.$adj->fecha_notificacion_adj}},  
                @endforeach
                 respectivamente,
                @else

                @endif
                se inicia el proceso de fiscalización. <br><br>
            
            
            Que habiéndose
            realizado la fiscalización in situ con fecha {{$sql->dias_fisca}},
            se realizó acciones de medición al área de terreno y área construida, categorización de la edificación, 
            estado de conservación, así como, la medición y valorización de obras complementarias fijas y permanentes,
            de acuerdo al Reglamento Nacional de Tasaciones, la  toma de fotografías,
            todo ello contenido en el expediente de fiscalización y en la Ficha Única de Verificación 
            
            
            @foreach($fichas as $fic)
                MDCC {{$fic->nro_fic}}-SGFT-GAT-MDCC, 
            @endforeach
            
            emitidas en fechas {{$sql->dias_fisca}} en los predios de su propiedad; por lo que se procedió a hacer el recalculo del impuesto resumido en el siguiente cuadro;
            
            
            
        </div>
        
        <table border="0" cellspacing="0" cellpadding="0" style="margin-top: 10px;margin-bottom: 5px;">
            <thead>
              <tr>
                  <th style="width: 10%" rowspan="2">Periodo</th>
                  <th style="width: 40%" colspan="2">Base Imponible</th>
                  <th style="width: 50%" colspan="3">Insoluto  Anual</th>
              </tr>
              <tr>
                  <th>Valor Declarado</th>
                  <th>Valor Verificado</th>
                  <th>Impuesto Cancelado</th>
                  <th>Impuesto Determinado</th>
                  <th>Diferencia a Reintregrar</th>
              </tr>
            </thead>
            <tbody>
                @foreach($valores as $val)
                <tr>
                    <td style="font-size: 0.7em; text-align: center">{{$val->anio}}</td>
                    <td style="font-size: 0.7em; text-align: right; padding-right: 5px">{{number_format($val->base_impon_declarado,2,".",",")}}</td>
                    <td style="font-size: 0.7em; text-align: right; padding-right: 5px">{{number_format($val->base_impon_verificado,2,".",",")}}</td>
                    <td style="font-size: 0.7em; text-align: right; padding-right: 5px">{{number_format($val->ivpp_cancelado,2,".",",")}}</td>
                    <td style="font-size: 0.7em; text-align: right; padding-right: 5px">{{number_format($val->ivpp_determiado,2,".",",")}}</td>
                    <td style="font-size: 0.7em; text-align: right; padding-right: 5px">{{number_format($val->ivpp_determiado-$val->ivpp_cancelado,2,".",",")}}</td>
                </tr>
               @endforeach
                <tr>
                    <td colspan="3" ><b>Nota: Deuda se actualizará a la fecha de pago</b></td>
                    <td colspan="2" style="text-align: right"><b>Total</b></td>
                    <td style="font-size: 0.7em; text-align: right; padding-right: 5px"><b>S/.{{number_format($valores->sum('$val->ivpp_determiado')-$valores->sum('$val->ivpp_cancelado'),3,".",",")}}</b></td>
                </tr>
            </tbody>
        </table>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-top: 10px;margin-bottom: 5px;">
            <thead>
              <tr>
                  <th style="width: 7%" rowspan="2">Tributo</th>
                  <th style="width: 13%" rowspan="2">Base<br>Imponible<br>Verificado</th>
                  <th style="width: 13%" rowspan="2">Tramo del<br>Autovaluo</th>
                  <th style="width: 7%" rowspan="2">Alicuota</th>
                  <th style="width: 33%" colspan="3">Insoluto Anual</th>
                  <th style="width: 10%" rowspan="2">Impuesto<br>Exigible</th>
                  <th style="width: 7%" rowspan="2">Reajuste</th>
                  <th style="width: 10%" rowspan="2">Total</th>
              </tr>
              <tr>
                  <th>Impuesto</th>
                  <th>Cancelado</th>
                  <th>Diferencia</th>
              </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-size: 0.7em; text-align: center">{{$sql->anio_fis}}</td>
                    <td style="font-size: 0.7em; text-align: right; padding-right: 5px">{{number_format($sql->base_verific,3,".",",")}}</td>
                    <td style="font-size: 0.7em; text-align: center">15 UIT <br>HASTA 60 UIT<br>MAS DE 60 UIT</td>
                    <td style="font-size: 0.7em; text-align: center">0.20% <br>0.60%<br>1.00%</td>
                    <td style="font-size: 0.7em; text-align: right; padding-right: 5px">{{number_format($sql->ivpp_verif,3,".",",")}}</td>
                    <td style="font-size: 0.7em; text-align: right; padding-right: 5px">{{number_format($sql->pagado,3,".",",")}}</td>
                    <td style="font-size: 0.7em; text-align: right; padding-right: 5px">{{number_format($sql->ivpp_verif-$sql->pagado,3,".",",")}}</td>
                    <td style="font-size: 0.7em; text-align: right; padding-right: 5px">{{number_format($sql->ivpp_verif-$sql->pagado,3,".",",")}}</td>
                    <td style="font-size: 0.7em; text-align: right; padding-right: 5px">{{$reajuste[0]->reajuste_actual}}</td>
                    <td style="font-size: 0.7em; text-align: right; padding-right: 5px">{{number_format($sql->ivpp_verif-$sql->pagado+$reajuste[0]->reajuste_actual,3,".",",")}}</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: center"><b>Sub Total</b></td>
                    <td style="font-size: 0.7em; text-align: right; padding-right: 5px"><b>{{number_format($sql->ivpp_verif,3,".",",")}}</b></td>
                    <td style="font-size: 0.7em; text-align: right; padding-right: 5px"><b>{{number_format($sql->pagado,3,".",",")}}</b></td>
                    <td style="font-size: 0.7em; text-align: right; padding-right: 5px"><b>{{number_format($sql->ivpp_verif-$sql->pagado,3,".",",")}}</b></td>
                    <td style="font-size: 0.7em; text-align: right; padding-right: 5px"><b>{{number_format($sql->ivpp_verif-$sql->pagado,3,".",",")}}</b></td>
                    <td style="font-size: 0.7em; text-align: right; padding-right: 5px"><b>{{$reajuste[0]->reajuste_actual}}</b></td>
                    <td style="font-size: 0.7em; text-align: right; padding-right: 5px"><b>{{number_format($sql->ivpp_verif-$sql->pagado+$reajuste[0]->reajuste_actual,3,".",",")}}</b></td>
                </tr>
                <tr>
                    <td colspan="4" ><b>Nota: Deuda se actualizará a la fecha de pago</b></td>
                    <td colspan="5" style="text-align: center"><b>Total</b></td>
                    <td style="font-size: 0.7em; text-align: right; padding-right: 5px"><b>S/.{{number_format($sql->ivpp_verif-$sql->pagado+4.64,3,".",",")}}</b></td>
                </tr>
            </tbody>
        </table>
        <div style="width: 100%; text-align: justify; font-size: 1.0em; margin-top: 10px;">
            Se procede a emitir la siguiente liquidación, previa a la emisión de la Resolución de Determinación,
            CONCEDIÉNDOLE UN PLAZO DE TRES {{$sql->dia_plazo}} DÍAS HABILES, contados a partir de recepcionada
            la presente, a efecto que pueda formular cualquier observación y/o inquietud, debiendo adjuntar los
            documentos sustentatorios; de no encontrar ninguna observación deberá efectuar el pago dentro del 
            plazo ya señalado. Vencido el plazo establecido, se procederá a la emisión de los títulos valores,
            tales como Resoluciones de Determinación y Resolución de Multa. 
            <br><br><b>III.-Base Legal</b><br>
            Ley Nro. 27972 Orgánica de Municipalidades, y Decreto Supremo Nro.133-13-EF TUO del Código Tributario
            y sus modificatorias, TUO de la Ley Tributación Municipal 776 (D.S. N°154-2004-EF), 
            Resolución Ministerial Nro. 172-2016-VIVIENDA Reglamento Nacional de Tasaciones y Ordenanza Municipal
            297-MDCC.
            
            <br><br>
            Agradeciendo anticipadamente por la atención que dispense al presente de ser necesario podrá 
            comunicarse al teléfono 054-382890 anexo 762 o ubicarnos en las oficinas cito en la calle 
            Francisco Bolognesi Nº 227 con Calle Mariano Melgar, Plaza las Américas Cerro Colorado.
            <br><br>
            @php echo $sql->texto_1 @endphp
        </div>
        
        
  </body>
  
</html>
