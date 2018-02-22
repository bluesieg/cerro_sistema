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
            font-family: sans-serif;
        }

    </style>
    <body>

        
        <div style="z-index: 99; margin-top: 70px;">
        <div style="margin-top: 5px;margin-right: 70px; font-size: 25px; text-align: right;">
            N°.{{$recibo[0]->serie}}
        </div>
        <div style="margin-bottom: 15px;margin-left: 175px; font-size: 16px;">
            {{$recibo[0]->contribuyente}}
        </div>
        <div style="margin-top: 5px;margin-left: 175px; font-size: 16px;">
            {{$recibo[0]->usuario}}
        </div>
        <div style="margin-left: 175px; font-size: 16px;">
            <table>
                <thead>
                    <tr>
                        <th>{{$recibo[0]->id_rec_mtr}}</th>
                        <th style="width: 155px; text-align: right;">{{date('M d',strtotime($recibo[0]->fecha))}}</th>
                        <th style="width: 250px; text-align: right;">{{$recibo[0]->serie}}</th>
                    </tr>
                </thead>
            </table> 
        </div>
        <div style="margin-top: 0px;margin-left: 175px; font-size: 16px;">
            {{$fecha_larga}}
        </div>
        <div style="margin-top: 5px;margin-left: 115px; font-size: 16px;">
            GLOSA : {{$recibo[0]->glosa}}
        </div>
        <div style="width: 700px;margin-top: 5px;margin-left: 70px; font-size: 16px;">
            <table class="table table-sm" style="font-size:16px">
                <thead>
                    <tr>
                        <th style="width: 20px">Cant.</th>
                        <th style="width: 70px">Concepto</th>
                        <th style="width: 450px">Descripcion</th>
                        <th style="width: 60px" align="center">Prec.Unit</th>
                        <th style="width: 60px" align="center">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($detalle as $det)
                    <tr>
                        <td align="center">{{number_format($det->cant,0)}}</td>
                        <td align="center">{{$det->concepto}}</td>
                        <td>{{$det->descrip_tributo}}</td>        
                        <td align="right">{{$det->p_unit}}</td>        
                        <td align="right">{{number_format($det->monto,2)}}</td>        
                    </tr>
                    @endforeach                    
                </tbody>
            </table>
            <div style="border-bottom: 1px solid #333"></div>
            <div style="margin-top: 5px; font-size: 16px; text-align: right; padding-right: 8px;">
                {{number_format($soles_numeros,2,".",",")}}
            </div>
        </div>
        <div style="margin-top: 5px;margin-left: 110px; font-size: 18px;">
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

