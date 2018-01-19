<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Estado de Cuenta</title>
        <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
        <style>
            @page { margin-bottom: 10px !important; margin-left: 50px;margin-right: 50px;};
        </style>
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
     <br>
        <br>
                <div class="sub2"  style="text-align: right;"style="font-size:0.8em"><b>FECHA: </b>{{ strtoupper($fecha_larga) }}</div>

        <div class="sub2" style="font-size:0.8em"><b>PERIODO: </b>{{ $desde." al ".$hasta}}</div>
        <div class="sub2" style="font-size:0.8em"><b>CODIGO: </b>{{ $desde." al ".$hasta}}</div>
        <div class="sub2" style="font-size:0.8em"><b>CONTRIBUYENTE: </b>{{ $contrib[0]->id_persona}}</div>
        <div class="sub2" style="font-size:0.8em"><b>DNI: </b>{{ $contrib[0]->contribuyente}}</div>
        <div class="sub2" style="font-size:0.8em"><b>DOMICILIO FISCAL: </b>{{ strtoupper($fecha_larga) }}</div>

        <br>
       
        
        <div style="margin-top: 10px;"> 
            
            <center><div Class="asunto" style="margin-top: 0px;"><b>Estado de Cuenta
                </b></div></center>
            <br>
            <table style="width: 100%;" class="t1">
                <thead>
                    <tr>
                        <th align="center" width="5%">Año</th>
                        <th align="center" width="10%">Base Imponible</th>
                        <th align="center" width="10%">Formularios</th>
                        <th align="center" width="10%">Impuesto Prediak</th>
                        <th align="center" width="10%">Trim II</th>
                        <th align="center" width="10%">Reajusto</th>
                        <th align="center" width="10%">Interes Impuesto</th>
                        <th align="center" width="10%">Multa DJ</th>
                        <th align="center" width="10%">Interes Multa</th>
                        <th align="center" width="10%">Arbitrios Municipales</th>
                        <th align="center" width="10%">Descuento Arbitrios</th>
                        <th align="center" width="10%">Interes Arbitrios.</th>
                        <th align="center" width="10%">Total</th>
                    </tr>                                        
                </thead>
                <tbody>
                    @foreach($pred as $pred)
                    <tr>                        
                        <td style="text-align: center">{{ $pred->ano_cta }}</td>
                        <td style="text-align: left">{{ number_format($pred->car1_cta,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->car1_cta,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->abo1_cta,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->car2_cta,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->abo2_cta,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->car3_cta,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->abo3_cta,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->car4_cta,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->abo4_cta,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->ivpp,3,'.',',')  }}</td>
                        <td style="text-align: right">{{ number_format($pred->ivpp,3,'.',',')  }}</td>
                        <td style="text-align: right">{{ number_format($pred->saldo,3,'.',',')  }}</td>
                    </tr>
                    @endforeach                                     
                </tbody>
                <tbody>
                    
                    <tr>                        
                        <td colspan="2" style="text-align: right">TOTAL</td>
                        <td style="text-align: right">{{ number_format($pred->car1_cta,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->abo1_cta,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->car2_cta,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->abo2_cta,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->car3_cta,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->abo3_cta,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->car4_cta,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->abo4_cta,3,'.',',') }}</td>
                        <td style="text-align: right">{{ number_format($pred->ivpp,3,'.',',')  }}</td>
                        <td style="text-align: right">{{ number_format($pred->ivpp,3,'.',',')  }}</td>
                        <td style="text-align: right">{{ number_format($pred->saldo,3,'.',',')  }}</td>
                    </tr>
                                                    
                </tbody>
                            
            </table>
            <div class="sub2" style="text-align: right;" style="font-size:0.8em"><b>TOTAL: </b>{{ number_format($pred->saldo,3,'.',',')  }}</div>

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

