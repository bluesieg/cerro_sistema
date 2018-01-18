<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Example 2</title>
    <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
    <style>
        .move-ahead { counter-increment: page 2; position: absolute; visibility: hidden; }
        .pagenum:after { content:' ' counter(page); }
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

    <center><div Class="asunto" style="margin-top: 10px;"><b>REPORTE GENERAL DE CAJA - TODAS LAS AGENCIAS</b></div></center>
    <div class="subasunto" style="text-align: left; padding-left: 30px; margin-top: 20px;">

    </div>

    <input type="hidden" value=" {{$num= 1}}">

    <div class="lado3" style="height: 435px; border-bottom: 1px solid #333">

        <br>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0px; font-size: 1.3em;">
            <thead>
            <tr >
                <th style="width: 3%;">N°</th>
                <th style="width: 30%;">AGENCIA</th>
                <th style="width: 10%;">TOTAL</th>
            </thead>
            <tbody>

            @foreach ($sql as $caj)
                <tr>
                    <td style="text-align: center;">{{ $num++ }}</td>
                    <td style="text-align: center;">{{ $caj->descrip_caja }}</td>
                    <td style="text-align: right; padding-right: 3px;">{{ number_format($caj->total,2,".",",") }}</td>
                </tr>
            @endforeach
            </tbody>
            <br>
            
            <div class="sub2" style="font-size:0.8em; text-align: right; margin-top: 10;"><b>TOTAL:&nbsp;&nbsp;&nbsp; </b>{{number_format($sql->sum('total'),2,".",",")}}</div>
            
        </table>
    </div>
</body>

</html>