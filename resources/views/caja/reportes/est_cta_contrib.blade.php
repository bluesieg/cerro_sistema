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
        <footer class="footer" style="font-size:0.8em; text-align: left; padding-top: 5px; padding-left: 10px;"><b>Impreso Por:&nbsp; </b>{{$usuario[0]->usuario}}</footer>
    <body>
        <div class="datehead" style="font-size:0.7em;">{{ $fecha }}</div>
        
       <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0px;">
        <tr>
            <td style="width: 10%; border: 0px;" >
                <img src="img/escudo.png" height="60px"/>
            </td>
            <td style="width: 80%; padding-top: 0px; border:0px;">
                <div id="details" class="sub2">
                    <div id="invoice" style="font-size:0.7em" >
                        <h1>{{$institucion[0]->nom1}}&nbsp;{{$institucion[0]->nom2}}</h1>
                        <div class="sub2">Creado por Ley 12075 el día 26 de Febrero de 1954</div>
                    </div>
                    <div  style="width: 95%; border-top:1px solid #999; margin-top: 5px; margin-left: 25px"></div>
                </div>
            </td>
            <td style="width: 10%;border: 0px;"></td>
        </tr>

    </table>
        
        <center><div Class="asunto" style="margin-top: 0px;font-size:0.8em;"><b>Reporte: Saldos del Contribuyente
                </b></div></center>
    
           
                @if ($foto_estado == 1)
                     @if (substr($foto[0]->pers_foto,0,4)=='http')
                     <div style="padding-right: 70px;"><img style="float: right"  src="{{$foto[0]->pers_foto}}" height="80px" width="75px"/></div>
                     @else
                     <div style="padding-right: 70px;"><img style="float: right"  src="data:image/png;base64,{{$foto[0]->pers_foto}}" height="100px" width="95px"/></div>
                    @endif
                @else
           
                @endif
                
            
        <br>
         
        <div>
            <br><div>
                <div class="sub2" style="font-size:0.7em"><b>PERIODO:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>{{ $desde." al ".$hasta}}</div>
                <div class="sub2" style="font-size:0.7em"><b>CODIGO:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </b>{{ $contrib1[0]->id_persona}}</div>
                <div class="sub2" style="font-size:0.7em"><b>CONTRIBUYENTE:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </b>{{ $contrib1[0]->contribuyente}}</div>
                <div class="sub2" style="font-size:0.7em"><b>DNI:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </b>{{ $contrib1[0]->nro_doc}}</div>
                <div class="sub2" style="font-size:0.7em"><b>DOMICILIO FISCAL:&nbsp;&nbsp;&nbsp; </b>
                    {{ strtoUpper($contrib1[0]->dom_fis)}}
                    @if($contrib1[0]->nro_mun!="")
                        N° {{$contrib1[0]->nro_mun}}
                    @endif
                    @if($contrib1[0]->manz!="")
                        Manz. {{$contrib1[0]->manz}}
                    @endif
                    @if($contrib1[0]->lote!="")
                        Lt. {{$contrib1[0]->lote}}
                    @endif
                    @if($contrib1[0]->distrit!="")
                        Dist. {{strtoUpper($contrib1[0]->distrit)}}
                    @endif
                    @if($contrib1[0]->ref_dom_fis!="")
                        {{strtoUpper($contrib1[0]->ref_dom_fis)}}
                    @endif
                </div>
             <br>
             
        </div>

        <div > 
            <div style="font-size:0.8em"> <center> ESTADO DE CUENTA</center></div><br>
            <table  class="t1">
                <thead>
                    <tr>
                        
                        <th align="center" width="5%" style="font-size:0.6em">Año</th>
                        <th align="center" width="15%" style="font-size:0.6em">Base Imponible</th>
                        <th align="center" width="5%" style="font-size:0.6em">Form</th>
                        <th align="center" width="10%" style="font-size:0.6em">Impuesto Predial</th>
                        <th align="center" width="5%" style="font-size:0.6em">Reajuste</th>
                        <th align="center" width="5%" style="font-size:0.6em">TIM</th>
                        <th align="center" width="10%" style="font-size:0.6em">Multa DJ</th>
                        <th align="center" width="10%" style="font-size:0.6em">Interes Multa</th>
                        <th align="center" width="10%" style="font-size:0.6em">RD_OP</th>
                        <th align="center" width="10%" style="font-size:0.6em">Arbitrios Municipales</th>
                        <th align="center" width="10%" style="font-size:0.6em">RD_ARB</th>
                        <th align="center" width="10%" style="font-size:0.6em">Descuento Arbitrios</th>
                        <th align="center" width="10%" style="font-size:0.6em">Interes Arbitrios</th>
                        <th align="center" width="10%" style="font-size:0.6em">Total</th>
                    </tr>                                        
                </thead>
                <tbody>
                    @foreach($contrib as $pred)
                    <tr>                        
                        <td style="text-align: center;font-size:0.6em">{{ $pred->ano_cta }}</td>
                        <td style="text-align: right;font-size:0.6em">{{ number_format($pred->base_imponible,2,'.',',') }}</td>
                        <td style="text-align: right;font-size:0.6em">{{ number_format($pred->formularios,2,'.',',') }}</td>
                        <td style="text-align: right;font-size:0.6em">{{ number_format($pred->predial,2,'.',',') }}</td>
                        <td style="text-align: right;font-size:0.6em">{{ number_format($pred->reajuste,2,'.',',') }}</td>
                        <td style="text-align: right;font-size:0.6em">{{ number_format($pred->interes_impuesto,2,'.',',') }}</td>
                        <td style="text-align: right;font-size:0.6em">{{ number_format($pred->multa_dj,2,'.',',') }}</td>
                        <td style="text-align: right;font-size:0.6em">{{ number_format($pred->interes_multa,2,'.',',') }}</td>
                        <td style="text-align: right;font-size:0.6em">{{ substr($pred->nro_rd,-4)."-".$pred->anio}}</td>
                        <td style="text-align: right;font-size:0.6em">{{ number_format($pred->tot_arbitrios,2,'.',',') }}</td>
                        <td style="text-align: right;font-size:0.6em"></td>
                        <td style="text-align: right;font-size:0.6em">{{ number_format($pred->descuento_arbit,2,'.',',') }}</td>
                        <td style="text-align: right;font-size:0.6em">{{ number_format($pred->interes_arbit,2,'.',',')  }}</td>
                        <td style="text-align: right;font-size:0.6em">{{ number_format($pred->total,2,'.',',')  }}</td>
                    </tr>
                    @endforeach                                     
                </tbody>
                <tbody>
                    <tr>                        
                        <td colspan="2" style="text-align: right"><b>TOTAL</b></td>
                        <td style="text-align: right;font-size:0.6em"><b>{{ number_format($contrib->sum('formularios'),2,'.',',') }}</b></td>
                        <td style="text-align: right;font-size:0.6em"><b>{{ number_format($contrib->sum('predial'),2,'.',',') }}</b></td>
                        <td style="text-align: right;font-size:0.6em"><b>{{ number_format($contrib->sum('reajuste'),2,'.',',') }}</b></td>
                        <td style="text-align: right;font-size:0.6em"><b>{{ number_format($contrib->sum('interes_impuesto'),2,'.',',') }}</b></td>
                        <td style="text-align: right;font-size:0.6em"><b>{{ number_format($contrib->sum('multa_dj'),2,'.',',') }}</b></td>
                        <td style="text-align: right;font-size:0.6em"><b>{{ number_format($contrib->sum('interes_multa'),2,'.',',') }}</b></td>
                        <td style="text-align: right;font-size:0.6em"><b>{{ number_format($contrib->sum('nro_rd'),2,'.',',') }}</b></td>
                        <td style="text-align: right;font-size:0.6em"><b>{{ number_format($contrib->sum('tot_arbitrios'),2,'.',',') }}</b></td>
                        <td style="text-align: right;font-size:0.6em"></td>
                        <td style="text-align: right;font-size:0.6em"><b>{{ number_format($contrib->sum('descuento_arbit'),2,'.',',') }}</b></td>
                        <td style="text-align: right;font-size:0.6em"><b>{{ number_format($contrib->sum('interes_arbit'),2,'.',',')  }}</b></td>
                        <td style="text-align: right;font-size:0.6em"><b>{{ number_format($contrib->sum('total'),2,'.',',')  }}</b></td>
                    </tr>                                   
                </tbody>
            </table>
            
            <div class="sub2" style="font-size:0.8em; text-align: right;"><b>TOTAL:&nbsp;&nbsp;&nbsp; </b>{{ number_format($contrib->sum('total'),2,'.',',')  }}</div>
            
            @if(isset($convenio[0]))
                @if($convenio[0]->estado==1)
                    <div class="contenedor" style="left: 0px;top: 280px;height: 43px;"><center><h2 style="margin-top:6px">En Fraccionamiento</h2></center></div>
                @else
                    <div class="contenedor" style="left: 0px;top: 280px;height: 43px;"><center><h2 style="margin-top:6px"></h2></center></div>
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
