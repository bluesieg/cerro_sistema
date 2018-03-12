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
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0px;">
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
        
        <center><div Class="asunto" style="margin-top: 0px;"><b>RESOLUCIÓN DE DETERMINACIÓN N° {{$sql->nro_rd}}-{{$sql->anio}}-SGFT-GAT-MDCC</b></div></center>
        <div class="subasunto" style="text-align: left; padding-left: 30px; margin-top: 20px;">Cerro Colorado, {{$sql->fec_reg}}</div>

        <table style="margin-top: 10px; margin-bottom: 5px !important; border-bottom: 1px solid black">
            <tr>
                <td colspan="2" style="border:0px;">
                    <b>I . <span style=" text-decoration: underline">IDENTIFICACIÓN DEL DEUDOR TRIBUTARIO</span></b>
                </td>
            </tr>
            <tr>
                <td style="border:0px; padding-left:18px; width: 35%">
                    <b> NOMBRE DE CONTRIBUYENTE</b>
                </td>
                <td style="border:0px;">
                    : {{$sql->contribuyente}}
                </td>
            </tr>
            <tr>
                <td style="border:0px; padding-left:18px;">
                    <b> N° DOCUMENTO</b>
                </td>
                <td style="border:0px; ">
                    : {{$sql->pers_nro_doc}}
                </td>
            </tr>
            <tr>
                <td style="border:0px; padding-left:18px;">
                    <b> DOMICILIO FISCAL</b>
                </td>
                <td style="border:0px;">
                    : {{$sql->ref_dom_fis}}
                </td>
            </tr>
            <tr>
                <td style="border:0px; padding-left:18px;">
                    <b> CODIGO DEL CONTRIBUYENTE</b>
                </td>
                <td style="border:0px">
                    : {{$sql->id_persona}}
                </td>
            </tr>
     
        </table>
        </b>
        <div style="width: 100%; text-align: justify; font-size: 0.8em; margin-top: 0px; padding-left:18px;">
            Se requiere la cancelación de la deuda contenida en el presente documento, en el plazo de 20 días hábiles contados a partir del día
            siguiente de su notificación, bajo apercibimiento de iniciar el procedimiento de Ejecución Coactiva.<br>
            La presente se emite por los tributos y periodos que se indican cuyo monto se ha actualizado al {{$sql->fec_reg}}, luego de esa fecha se
            actualizara con la Tasa de Interes Moratorio de 1.2% conforme lo fijado mediante ordenanza municipal N° 297-2010-MDCC.<br>
        </div>
        <table style="margin-top: 5px; margin-bottom: 5px !important;">
            <tr>
                <td colspan="2" style="border:0px;">
                    <b>II. <span style=" text-decoration: underline">MOTIVO DETERMINANTE</span></b>
                </td>
            </tr>
         </table>
        <div style="width: 100%; text-align: justify; font-size: 0.8em; margin-top: 0px; padding-left:18px;">
            {{$sql->txt_motivacion}};
        </div>
        <table style="margin-top: 5px; margin-bottom: 5px !important;">
            <tr>
                <td colspan="2" style="border:0px;">
                    <b>III. <span style=" text-decoration: underline">DECLARACION JURADA ACTUALIZADA AL:</span> </b> {{strtoupper ($sql->fec_reg)}}
                </td>
            </tr>
           
         </table>
        <table style="margin-top: 5px; margin-bottom: 5px !important;">
           
            <tr>
                <td colspan="2" style="border:0px;">
                    <b>IV. <span style=" text-decoration: underline">UBICACION DE PREDIOS:</span> </b>
                </td>
            </tr>
         </table>
        
        <table border="0" cellspacing="0" cellpadding="0" style="margin-top: 0px;margin-bottom: 0px;">
            <thead>
              <tr>
                  <th style="width: 10%">Cod. Catastral</th>
                  <th style="width: 70%">Ubicación del predio</th>
                  <th style="width: 4%">N°</th>
                  <th style="width: 5%">Zona</th>
                  <th style="width: 6%">Manzana</th>
                  <th style="width: 5%">Lote</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($predios as $pre)
                    <tr>
                        <td>
                            {{$pre->cod_cat}}
                        </td> 
                        <td>
                            {{$pre->nom_via."-".$pre->habilitacion}}
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
        
        
        <table style="margin-top: 5px; margin-bottom: 5px !important;">
            <tr>
                <td colspan="2" style="border:0px;">
                    <b>V . <span style=" text-decoration: underline">CUANTIA DE IMPUESTO-REAJUSTE-INTERES:</span> </b>
            </tr>
         
         </table>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-top: 0px;margin-bottom: 0px;">
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
                    <td colspan="4" ><b>Nota: Deuda se actualizatará a la fecha de pago</b></td>
                    <td colspan="5" style="text-align: center"><b>Total</b></td>
                    <td style="font-size: 0.7em; text-align: right; padding-right: 5px"><b>S/.{{number_format($sql->ivpp_verif-$sql->pagado+4.64,3,".",",")}}</b></td>
                </tr>
            </tbody>
        </table>
        
        <table style="margin-top: 5px; margin-bottom: 5px !important;">
            <tr>
                <td colspan="2" style="border:0px;">
                    <b>VI . <span style=" text-decoration: underline">BASE LEGAL:</span> </b>
            </tr>
         
         </table>
        <div style="width: 100%; text-align: justify; font-size: 0.8em; margin-top: 0px; padding-left:18px;">
            1. Artículos 33°, 76°, 77° y 104° del TUO del Código Tributario aprobado por el D.S.133-2013-EF y.<br>
            2. Artículos 8°, 9°, 10°, 11°, 12°, 13°, 14° y 15° del TUO de la ley fr Tributación Municipal aprobado por el D.S.156-2004-EF y sus modificaciones,<br>
        </div>
        <table style="margin-top: 5px; margin-bottom: 5px !important;">
            <tr>
                <td colspan="2" style="border:0px;">
                    <b>VII . <span style=" text-decoration: underline">SE RESUELVE:</span> </b>
            </tr>
         
         </table>
        <div style="width: 100%; text-align: justify; font-size: 0.8em; margin-top: 0px; padding-left:18px;">
            <b>ARTÍCULO PRIMERO:</b> Determinar la deuda por concepto de impuesto predial al contribuyente {{$sql->contribuyente}}
            por el predio {{$sql->anio_fis}} en la suma de {{$sql->letras}}(S/.{{number_format($sql->ivpp_verif-$sql->pagado+4.64,3,".",",")}})
            , detalladas en el numeral V de la presente resolución.<br>
            <br>
            <b>ARTÍCULO SEGUNDO:</b> Encárguese, a la oficina de Ejecución Coactiva la prosecución de las acciones de cobranza que corresponde de
            conformidad con el TUO del D.S.N° 018-2008-JUS Y D.S.069-2003-EF y sus modificatorias una vez constituya cosa decidida.<br>
            <br>
            <b>REGÍSTRESE, COMUNÍQUESE Y CUMPLASE</b>
            
        </div>
        
        
  </body>
  
</html>
