<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Caja-Recibo</title>        
    </head>
    <style>        
        @page {
            margin:0;
            padding: 0;
           
        }
    </style>
    <body style="font-family: sans-serif;">

        <img src="img/recibo_caja.jpg" style="width: 100%;position: absolute;">              
        <div style="position: absolute;margin-top: 70px;margin-left: 590px; font-size: 19px;">
            N°.{{$recibo[0]->serie}}
        </div>
        <div style="position: absolute;margin-top: 85px;margin-left: 80px; font-size: 12px;">
           Nombre&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp; {{$recibo[0]->contribuyente}}
        </div>
        <div style="position: absolute;margin-top: 100px;margin-left: 80px; font-size: 12px;">
           Dirección&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp; {{$recibo[0]->direccion}}
        </div>
        <div style="position: absolute;margin-top: 115px;margin-left: 80px; font-size: 12px;">
           Cajero(a)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp; {{$recibo[0]->usuario}}
        </div>
        <div style="position: absolute;margin-top: 130px;margin-left: 80px; font-size: 12px;">
          Recibo N.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp; {{$recibo[0]->recib_sistema}}
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ {{date('M d',strtotime($recibo[0]->fecha))}} ]
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ref:{{$recibo[0]->id_rec_mtr}} 
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{$recibo[0]->serie}}
        </div>
        <div style="position: absolute;margin-top: 145px;margin-left: 80px; font-size: 12px;">
          Emitido&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;  {{$fecha_larga}}
        </div>
        <div style="position: absolute;margin-top: 160px;margin-left: 80px; font-size: 12px;">
          Glosa&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;  {{$recibo[0]->glosa}}
        </div>
        
        <div style="width: 700px;position: absolute;margin-top: 181px;margin-left: 50px; font-size: 10px;">
            <table class="table table-sm" style="font-size:10px">
                <thead style="border-bottom:  1px solid black;">
                    <tr>
                        <th style="width: 20px">Cant.</th>
                        <th style="width: 70px">Concepto</th>
                        <th style="width: 450px">Descripción</th>
                        <th style="width: 60px" align="right">Prec.Unit</th>
                        <th style="width: 60px" align="right">Total</th>
                    </tr>
                </thead>
               
                <tbody style="border-bottom:  1px solid black;">
                    @foreach($detalle as $det)
                    <tr>
                        <td align="center">{{number_format($det->cant,0)}}</td>
                        <td align="center">{{$det->concepto}}</td>
                        <td>{{$det->descrip_tributo}}&nbsp;&nbsp;
                            @if(isset($det->detalle_trimestres))
                                [{{$det->detalle_trimestres}}]
                                
                            @else
                                [-]
                            @endif
                        </td>        
                        <td align="right">{{$det->p_unit}}</td>        
                        <td align="right">{{number_format($det->monto,2)}}</td> 
                    </tr>
                    @endforeach 
                    
                </tbody>
                <tfoot>
                <td colspan="5" style="text-align: right;">{{number_format($soles_numeros,2,".",",")}}</td>
                </tfoot>              
            </table>
           
            <div style=" margin-top: 5px; font-size: 12px;">
            Son: &nbsp;{{$soles}}
        </div>
        </div>
         
    </body>
     
</html>

<!--sd
{{$recibo[0]->id_rec_mtr}}<br>
                {{$recibo[0]->serie}}<br>
                {{$recibo[0]->hora_pago}}<br>
-->

