<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Example 2</title>
        <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
        <style>
            @page { margin-bottom: 10px !important; margin-left: 80px;margin-right: 80px;};
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
        
        <center><div Class="asunto" style="margin-top: 10px;"><b>ORDEN DE PAGO PREDIAL N° {{$sql->nro_fis}}-{{$sql->anio_reg}}-GAT-MDCC</b></div></center>
        <div class="subasunto" style="text-align: left; padding-left: 30px; margin-top: 20px;">Cerro Colorado, {{$sql->fec_reg}}</div>

        <div class="lado3" style="margin-top: 30px; font-weight: bold">
            INDENTIFICACION DEL DEUDOR TRIBUTARIO
        </div>
        <b>
        <table>
            <tr>
                <td style="border:0px; width: 20%">
                    Nombre/ Razon Social
                </td>
                <td style="border:0px;">
                    : {{$sql->contribuyente}}
                </td>
            </tr>
            <tr>
                <td style="border:0px; width: 20%">
                    N° Documento
                </td>
                <td style="border:0px;">
                    : {{$sql->nro_doc}}
                </td>
            </tr>
            <tr>
                <td style="border:0px; width: 20%">
                    Domicilio Fiscal
                </td>
                <td style="border:0px;">
                    : {{$sql->dom_fiscal}}
                </td>
            </tr>
            
        </table>
        </b>
        <div style="width: 100%; text-align: justify">
            Se requiere la cancelacion de la deuda contenida en el presente documento, en el plazo de 20 días hábiles contados a partir del día siguiente de la notificación, bajo apercibimiento de iniciar procedimiento de ejecución coactiva.<br>
            La presente de emite por los tributos y periodos que se indican, cuyo monto se ha actualizaco a la FECHA DE EMISIÓN, luego de esta fecha se actualizara con la tasa diaria de 0.04% conforme a la tasa de interes fijada.<br>
            <b>Monto Determinante:</b><br>
            Se ha verificado la existencia de una deuda tributaria no cancelada dentro de los plazos establecidos.<br>
            
        </div>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 10px; margin-top: 20px" >
        <thead>
          <tr>
              <th style="width: 7%">Tributo</th>
              <th style="width: 7%">Nro. OP</th>
              <th style="width: 7%">Periodo</th>
              <th style="width: 9%">Base Imponible</th>
              <th style="width: 11%">Tramo</th>
              <th style="width: 7%">Alicuota</th>
              <th style="width: 9%">Insoluto Tramo</th>
              <th style="width: 8%">Trimestre</th>
              <th style="width: 6%">Factor</th>
              <th style="width: 7%">Reajuste</th>
              <th style="width: 7%">Interes</th>
              <th style="width: 12%">Sub-total(S/.)</th>
          </tr>
          
        </thead>
        <tbody>
          @if($sql->trimestre1>0)
          <tr>
              <td style="text-align: center;font-size: 0.7em; padding: 0px;">Imp.<br>Predial</td>
              <td style="text-align: center;font-size: 0.7em; padding: 0px;">{{$sql->nro_fis}}</td>
              <td style="text-align: center;font-size: 0.7em; padding: 0px;">{{$sql->periodo1}}</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->ivpp_afecto,2)}}</td>
              <td style="text-align: right; font-size: 0.6em;padding-right: 2px;">Hasta 15 UIT<br>De 15 a 60 UIT<br>Más de 60 UIT</td>
              <td style="text-align: right; font-size: 0.6em; padding-right: 2px;">0.2%<br>0.6%<br>1.0%</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->ivpp/4,2)}}</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->trimestre1,2)}}</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->factor1,2)}}</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->reajuste1,2)}}</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->interes1,2)}}</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->sub_total1,2)}}</td>
          </tr>
          @endif
          @if($sql->trimestre2>0)
          <tr>
              <td style="text-align: center;font-size: 0.7em; padding: 0px;">Imp.<br>Predial</td>
              <td style="text-align: center;font-size: 0.7em; padding: 0px;">{{$sql->nro_fis}}</td>
              <td style="text-align: center;font-size: 0.7em; padding: 0px;">{{$sql->periodo2}}</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->ivpp_afecto,2)}}</td>
              <td style="text-align: right; font-size: 0.6em;padding-right: 2px;">Hasta 15 UIT<br>De 15 a 60 UIT<br>Más de 60 UIT</td>
              <td style="text-align: right; font-size: 0.6em; padding-right: 2px;">0.2%<br>0.6%<br>1.0%</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->ivpp/4,2)}}</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->trimestre2,2)}}</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->factor2,2)}}</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->reajuste2,2)}}</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->interes2,2)}}</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->sub_total2,2)}}</td>
          </tr>
          @endif
          @if($sql->trimestre3>0)
          <tr>
              <td style="text-align: center;font-size: 0.7em; padding: 0px;">Imp.<br>Predial</td>
              <td style="text-align: center;font-size: 0.7em; padding: 0px;">{{$sql->nro_fis}}</td>
              <td style="text-align: center;font-size: 0.7em; padding: 0px;">{{$sql->periodo3}}</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->ivpp_afecto,2)}}</td>
              <td style="text-align: right; font-size: 0.6em;padding-right: 2px;">Hasta 15 UIT<br>De 15 a 60 UIT<br>Más de 60 UIT</td>
              <td style="text-align: right; font-size: 0.6em; padding-right: 2px;">0.2%<br>0.6%<br>1.0%</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->ivpp/4,2)}}</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->trimestre3,2)}}</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->factor3,2)}}</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->reajuste3,2)}}</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->interes3,2)}}</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->sub_total3,2)}}</td>
          </tr>
          @endif
          @if($sql->trimestre4>0)
          <tr>
              <td style="text-align: center;font-size: 0.7em; padding: 0px;">Imp.<br>Predial</td>
              <td style="text-align: center;font-size: 0.7em; padding: 0px;">{{$sql->nro_fis}}</td>
              <td style="text-align: center;font-size: 0.7em; padding: 0px;">{{$sql->periodo4}}</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->ivpp_afecto,2)}}</td>
              <td style="text-align: right; font-size: 0.6em;padding-right: 2px;">Hasta 15 UIT<br>De 15 a 60 UIT<br>Más de 60 UIT</td>
              <td style="text-align: right; font-size: 0.6em; padding-right: 2px;">0.2%<br>0.6%<br>1.0%</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->ivpp/4,2)}}</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->trimestre4,2)}}</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->factor4,2)}}</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->reajuste4,2)}}</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->interes4,2)}}</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->sub_total4,2)}}</td>
          </tr>
          @endif
          <tr>
              <td colspan="6">Total Insoluto</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format(($sql->total),2)}}</td>
              <td colspan="4" style="text-align: right; padding-right: 2px; ">Total</td>
              <td style="text-align: right; padding-right: 2px; font-size: 0.7em">{{number_format($sql->sub_total1+$sql->sub_total2+$sql->sub_total3+$sql->sub_total4,2)}}</td>
          </tr>
        </tbody>
      </table>
        
        <div style="width: 100%; text-align: justify">
            U.I.T. {{$UIT->anio}} = S/ {{$UIT->uit}}<br><br>
            <b>Base Legal:</b><br>
            Artículos 33°,77°,78° inc 1 y 194 del T.U.O. del Código Tributario, aprobado por D.S. N° 133-2013-EF y sus
            modificatorias.<br>
            Artículo 8° y siguientes del TUO de la ley de Tributación Municipal, aprobada por D.S.N° 133-2013-EF y sus modificatorias<br>
            Ordenanzas 297-2010 que aprueba TIM para la jurisdicción del distrito de Cerro Colorado.<br>
            <b>Avisos:</b><br>
            -Si a la recepción de esta. Ud. ya realizo el pago de tales conceptos, le rogamos no prestar atención a la presente<br>
            -Cualquier consulta adicional, lo esperamos en la Plaza Las Americas, La Libertad, Cerro Colorado; o comuníquese al 
            teléfono (054)382590 anexo 719, en el horario de lunes a viernes de 8.00am a 3.30pm.<br>
            -En el caso de no conformidad, podrá interponer recurso de reclamación debidamente sustentado, suscrito por letrado hábil
            (nombre, firma y número de registro), para lo cual deberá acreditar la cancelación de la totalidad de la deuda, salvo sea
            evidente la improcedencia de la cobranza, en cuyo caso podra presentar la reclamación en el plazo de 20 días hábiles
            de notificada la presente, y conforme a los dispositivos vigentes.<br>
            <br>
            <b>Nota:</b> Se ha aprobado los beneficios tributarios a traves de Ordenanza Municipal 459-MDCC.
        </div>
        
  </body>
  
</html>
