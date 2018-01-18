<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Estado de Cta</title>        
        <style>        
            @font-face {
                font-family: SourceSansPro;
                src: url(SourceSansPro-Regular.ttf);
            }
            img.alineadoTextoImagenCentro{
                        vertical-align: middle;
            }
            footer { position: fixed; bottom: -20px; left: 0px; right: 0px; height: 70px; }
            .t1, .t2 { border-collapse: collapse; }
            .t1 > tbody > tr > td { border: 1px solid #D5D5D5; font-size: 12px}
            .t1 > thead > tr > th { border:1px solid #D5D5D5;font-size: 13px; background: #01A858;color: white; }            
        </style>
        <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
    </head>    
    <body>
        
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
        
        <center><div Class="asunto" style="margin-top: 0px;"><b>Reporte: Saldos del Contribuyente
                </b></div></center>
    @if(substr($foto[0]->pers_foto, 0,4)=='http')
            <img style="float: right"  src="{{$foto[0]->pers_foto}}" height="100px" width="95px"/></div>
    @else
           <img style="float: right"  src="data:image/png;base64,{{$foto[0]->pers_foto}}" height="100px" width="95px"/></div>
           @endif
          {{$foto[0]->pers_foto}}  
        <br>
         
        <div>
            <br><div>
                <div class="sub2" style="font-size:0.8em"><b>FECHA:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>{{ $fecha_larga }}</div>                
                <div class="sub2" style="font-size:0.8em"><b>PERIODO:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>{{ $desde." al ".$hasta}}</div>
                <div class="sub2" style="font-size:0.8em"><b>CODIGO:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </b>{{ $contrib1[0]->id_persona}}</div>
                <div class="sub2" style="font-size:0.8em"><b>CONTRIBUYENTE:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </b>{{ $contrib1[0]->contribuyente}}</div>
                <div class="sub2" style="font-size:0.8em"><b>DNI:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </b>{{ $contrib1[0]->nro_doc}}</div>
                <div class="sub2" style="font-size:0.8em"><b>DOMICILIO FISCAL:&nbsp;&nbsp;&nbsp; </b>{{ strtoUpper($contrib1[0]->dom_fis)}}</div>
             <br>
             
             <br>
             <br>

        </div>

        <div > 
            <div> <center> ESTADO DE CUENTA</center></div><br>
            <table  class="t1">
                <thead>
                    <tr>
                        
                        <th align="center" width="5%" style="font-size:0.8em">Año</th>
                        <th align="center" width="10%" style="font-size:0.8em">Base Imponible</th>
                        <th align="center" width="10%" style="font-size:0.8em">Formularios</th>
                        <th align="center" width="10%" style="font-size:0.8em">Impuesto Predial</th>
                        <th align="center" width="10%" style="font-size:0.8em">Reajuste</th>
                        <th align="center" width="10%" style="font-size:0.8em">Interes Impuesto</th>
                        <th align="center" width="10%" style="font-size:0.8em">Multa DJ</th>
                        <th align="center" width="10%" style="font-size:0.8em">Interes Multa</th>
                        <th align="center" width="10%" style="font-size:0.8em">Arbitrios Municipales</th>
                        <th align="center" width="10%" style="font-size:0.8em">Descuento Arbitrios</th>
                        <th align="center" width="10%" style="font-size:0.8em">Interes Arbitrios</th>
                        <th align="center" width="10%" style="font-size:0.8em">Total</th>
                    </tr>                                        
                </thead>
                <tbody>
                    @foreach($contrib as $pred)
                    <tr>                        
                        <td style="text-align: center">{{ $pred->ano_cta }}</td>
                        <td style="text-align: right">{{ number_format($pred->base_imponible,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->formularios,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->predial,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->reajuste,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->interes_impuesto,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->multa_dj,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->interes_multa,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->tot_arbitrios,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->descuento_arbit,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->interes_arbit,3,'.',',')  }}</td>
                        <td style="text-align: right">{{ number_format($pred->total,3,'.',',')  }}</td>
                    </tr>
                    @endforeach                                     
                </tbody>
                <tbody>
                    @foreach($total as $suma_total)
                    <tr>                        
                        <td colspan="2" style="text-align: right"><b>TOTAL</b></td>
                        <td style="text-align: right"><b>{{ number_format($suma_total->formularios,3,'.',',') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($suma_total->impuesto_predial,3,'.',',') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($suma_total->reajuste,3,'.',',') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($suma_total->interes_impuesto,3,'.',',') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($suma_total->multa_dj,3,'.',',') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($suma_total->interes_multa,3,'.',',') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($suma_total->arbitrios_municipales,3,'.',',') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($suma_total->descuento_arbitrios,3,'.',',') }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($suma_total->interes_arbitrios,3,'.',',')  }}</b></td>
                        <td style="text-align: right"><b>{{ number_format($suma_total->total,3,'.',',')  }}</b></td>
                    </tr>
                    @endforeach                                     
                </tbody>
            </table>
            
            <div class="sub2" style="font-size:0.8em; text-align: right;"><b>TOTAL:&nbsp;&nbsp;&nbsp; </b>{{ number_format($suma_total->total,3,'.',',')  }}</div>
            
            @if(isset($convenio[0]))
                @if($convenio[0]->tipo==1 || $convenio[0]->tipo==3)
                    <div class="contenedor" style="left: 0px;top: 280px;height: 43px;"><center><h2 style="margin-top:6px">En Fraccionamiento</h2></center></div>
                @endif
            @endif
            
        </div>        
    
        <script src="{{ asset('archivos_js/reportes/est_cta.js') }}"></script>
        <script src="{{ asset('js/libs/jquery-2.1.1.min.js') }}"></script>
        <script type="text/javascript">            
            $(document).ready(function() {
                
            });
        </script>
    </body>
</html>