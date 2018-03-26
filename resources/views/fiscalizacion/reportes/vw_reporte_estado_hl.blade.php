<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Listado de Datos de los Contribuyentes</title>
    <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
    <style>
        .move-ahead { counter-increment: page 2; position: absolute; visibility: hidden; }
        .pagenum:after { content:' ' counter(page); }
       .footer {position: fixed }

    </style>
</head>
    <footer class="footer" style="font-size:0.8em; text-align: left; padding-top: 5px; padding-left: 10px;"><b>Impreso Por:&nbsp; </b>{{$name}}</footer>
<body>
<main>

    <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0px;">
        <tr>
            <td style="width: 10%; border: 0px;" >
                <img src="img/escudo.png" height="60px"/>
            </td>
            <td style="width: 80%; padding-top: 0px; border:0px;">
                <div id="details" class="sub2">
                    <div id="invoice" style="font-size:0.7em" >
                        <h1>MUNICIPALIDAD DISTRITAL DE CERRO COLORADO</h1>
                        <div class="sub2">Creado por Ley 12075 el día 26 de Febrero de 1954</div>
                    </div>
                    <div  style="width: 95%; border-top:1px solid #999; margin-top: 5px; margin-left: 25px"></div>
                </div>
            </td>
            <td style="width: 10%;border: 0px;"></td>
        </tr>

    </table>

    <center>
        <div Class="asunto" style="margin-top: 1px;font-size:0.8em;">
            <b>
                @if ($estado == 4) 
                    IMPUESTO PREDIAL GENERADO POR FISCALIZACIÓN {{ $anio }}
                @else
                    REPORTE DE ESTADO DE HOJA DE LIQUIDACIÓN {{ $anio }}
                @endif
            </b>
        </div>
    </center>
    <div class="subasunto" style=" margin-bottom:1px; text-align: left; padding-left: 30px;font-size:0.7em;">
        <h5 class="subasunto" style="font-size:0.8em;  text-align: right; padding-left: 30px;">{{ date("d/m/Y") }}</h5>
    </div>
    <div>
          @if ($estado == 0)                   
                    <h5 class="subasunto" style="font-size:0.8em; ">ESTADO : NO NOTIFICADO</h5>             
                @endif
          @if ($estado == 1)                   
                    <h5 class="subasunto" style="font-size:0.8em;">ESTADO : NOTIFICADO</h5>             
                 @endif
          @if ($estado == 2)                   
                    <h5 class="subasunto" style="font-size:0.8em;">ESTADO : NO PAGADO</h5>             
                 @endif
          @if ($estado == 3)                   
                    <h5 class="subasunto" style="font-size:0.8em;">ESTADO : PAGADO</h5>             
                 @endif
          @if ($estado == 4)                   
                    <h5 class="subasunto" style="font-size:0.8em;">ESTADO : IMPUESTO GENERADO</h5>             
                 @endif
    </div>
    <div class="lado3" style=" margin-top: 5px;">
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom:20px; margin-top: 0px;  font-size: 1.0em;">
            <thead>
            <tr >
                <th style="width: 5%;">N° Hoja</th>
                <th style="width: 8%">DNI/RUC</th>
                <th style="width: 20%;">CONTRIBUYENTE</th>
                <th style="width: 40%;">DOMICILIO FISCAL</th>
                <th style="width: 7%;">ANULADO</th>
                @if ($estado == 4)
                <th style="width: 20%;">IMPUESTO VERIFICADO</th>
                @else
                <th style="width: 10%;">DEUDA</th>
                <th style="width: 10%;">PAGADO</th>
                @endif
                
            </tr>
            </thead>
            <tbody>

            @foreach ($sql as $cont)
                <tr>
                    <td style="text-align: center;">{{ trim($cont->nro_hoja) }}</td>
                    <td style="text-align: center;">{{trim($cont->pers_nro_doc)}}</td>
                    <td style="text-align: left;">{{ trim($cont->contribuyente) }}</td>
                    <td style="text-align: left;">{{trim($cont->ref_dom_fis)}}</td>
                    <td style="text-align: center;">
                        @if($cont->flg_anu==1)
                         ANULADO
                         @endif
                    </td>
                    @if ($estado == 4)
                        <td style="text-align: right;">{{number_format($cont->ivpp_verif,"2",".",",")}}</td>
                    @else
                        <td style="text-align: right;">{{number_format($cont->saldo,"2",".",",")}}</td>
                        <td style="text-align: right;">{{number_format($cont->total_pagado,"2",".",",")}}</td>
                    @endif
                    
                </tr>
                
            @endforeach
            <tr>
                <td colspan="5" style="text-align: right;">TOTAL:</td>
                @if ($estado == 4)
                    <td style="text-align: right;">{{number_format($sql->sum('ivpp_verif'),"2",".",",")}}</td>
                @else
                    <td style="text-align: right;">{{number_format($sql->sum('saldo'),"2",".",",")}}</td>
                    <td style="text-align: right;">{{number_format($sql->sum('total_pagado'),"2",".",",")}}</td>
                @endif
            </tr>
            </tbody>
        </table>
    </div>
</body>

</html>