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
        
        <center><div Class="asunto" style="margin-top: 10px;">
                @if($sql->flg_anu==0)
                <b>Hoja de Liquidación de Deuda Tributaria (Reparo) N° {{$sql->nro_hoja}}-{{$sql->anio}}-SGFT-GAT-MDCC</b>
                @else
                <b>ANULADO</b>
                @endif
            </div></center>
        <div class="subasunto" style="text-align: left; padding-left: 30px; margin-top: 20px;">Cerro Colorado, {{$sql->fec_reg}}</div>

        <table style="margin-top: 10px; margin-bottom: 10px !important; border-bottom: 1px solid black">
            <tr>
                <td style="border:0px; width: 17%">
                    <b>Señor/es:</b>
                </td>
            </tr>
            <tr>
                <td style="border:0px; width: 50%">
                    <b> {{$sql->contribuyente}}</b>
                </td>
            </tr>
            <tr>
                <td style="border:0px; ">
                    <b>Dirección:</b> {{$sql->ref_dom_fis}}
                </td>
            </tr>
            <tr>
                <td style="border:0px;">
                    <b>Presente.-</b>
                </td>
            </tr>
            <tr>
                <td style="border:0px">
                    <b>Código:</b> {{$sql->id_persona}}
                </td>
            </tr>
            <tr>
                <td style="border:0px;">
                    <b>N° Doc:</b> {{$sql->pers_nro_doc}}
                </td>
            </tr>
        </table>
        </b>
        <div style="width: 100%; text-align: justify; font-size: 1.0em; margin-top: 0px;">
            Por la presente hacemos de su conocimiento que la Municipalidad Distrital de Cerro Colorado a través del
            personal de la Sub Gerencia de Fiscalización Tributaria, ha notificado la carta de presentación y
            Requerimiento de fiscalización N° {{$sql->nro_car."-".$sql->anio_carta}}-SGTF-GAT-MDCC de fecha {{$sql->fec_carta}}, habiéndose
            realizado la fiscalización in situ con fecha {{$sql->dias_fisca}} según Fichas Únicas de Verificación 
            del Impuesto Predial N° 
            @foreach($fichas as $fic)
                {{$fic->nro_fic}}, 
            @endforeach
            emitidas en fechas {{$sql->dias_fisca}} en los predios de su propiedad; se ha procedido a emitir la siguiente liquidación
            previa a la emisión de la Resolución de Determinación, <B>CONCEDIÉNDOLE UN PLAZO DE {{$sql->dia_plazo}} DÍAS HÁBILES</B>
            contados a partir de recepcionada la presente para que Uds. puedan Formular cualquier observación
            y/o inquietud, debiendo según el caso adjuntar los documentos sustentatorios; de no encontrarse ninguna
            observación deberá efectuar el pago dentro del plazo ya señalado.<br>
            La presente se emite facultativamente conforme al párrafo segundo del Art. 75° parte final del Código
            Tributario(DS 133-2013-EF).<br>
            Vencido el plazo establecido,se procederá a la emisión de los Títulos de Ejecución correspondientes, tales como
            Resoluciones de Determinación y Resolución de Multa. La presente se emite por los Tributos y 
            periodos que se indican cuyo monto se ha actualizado a la fecha de la presente liquidación<br>
            Conforme a la inspección ocular realizada al predio se ha determinado el valúo que le corresponde según
            cuadros anexos a la presente.
        </div>
        
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
            <b>Base Legal:</b><br>
            D. Leg.776(DS 156-2004-EF TUO) y modificada Ley de Tributación Municipal.<br>
            D. Sup. 133-2013-EF Texto Único ordenado en Código Tributario.<br>
            Resoluciones Directorales N° 296-2009- VIVIENDA (30-OCT 2009), 175-OCT 2010 VIVIENDA (29-OCT 2010),
            220-2001 VIVIENDA(30-OCT 2011).<BR>
            Los Intereses se calculan hasta el día de su cancelación; la tasa vigente de interes es del 1.2% mensual
            (Art. 33 TUO CT) y Ordenaza Municipal N° 297-2010 del 30 de Abr. del 2010.<br>
            La Multa Tributaria está contemplada en la Ordenanza Municipal N° 338-MDCC de fecha 29-Mar del 2012.
            <br> 
            Atentamente.
        </div>
        
        
  </body>
  
</html>
