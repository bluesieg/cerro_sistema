<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte del Monto de la Base Imponible Afecto y Exonerado</title>
    <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
    <style>
        .move-ahead { counter-increment: page 2; position: absolute; visibility: hidden; }
        .pagenum:after { content:' ' counter(page); }
    </style>
</head>
 <footer class="footer" style="font-size:0.8em; text-align: left; padding-top: 5px; padding-left: 10px;"><b>Impreso Por:&nbsp; </b>{{$usuario[0]->usuario}}</footer>
<body>
    <div class="datehead">{{ $fecha }}</div>
<main>

    <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 5px;">
        <tr>
            <td style="width: 10%; border: 0px;" >
                <img src="img/escudo.png" height="70px"/>
            </td>
            <td style="width: 80%; padding-top: 10px; border:0px;">
                <div id="details" class="clearfix">
                    <div id="invoice" >
                      <h1>{{$institucion[0]->nom1}}&nbsp;{{$institucion[0]->nom2}}</h1>
                        <div class="sub2">Creado por Ley 12075 el día 26 de Febrero de 1954</div>
                    </div>
                    <div style="width: 90%; border-top:1px solid #999; margin-top: 10px; margin-left: 25px;"></div>
                </div>
            </td>
            <td style="width: 10%;border: 0px;"></td>
        </tr>

    </table>

    <center><div Class="asunto" style="margin-top: 10px;"><b>Reporte del Monto de la Base Imponible Afecto y Exonerado</b></div></center>
    <div class="subasunto" style="text-align: left; padding-left: 30px; margin-top: 20px;">
    </div>

    <input type="hidden" value=" {{$num= 1}}">

    <div class="lado3" style="height: 435px; border-bottom: 0px solid #333">

        <br>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0px; font-size: 1.3em;">
            <thead>
                    <tr>
                        <th style="width: 5%; text-align: center;">AÑO</th>
                        <th style="width: 5%; text-align: center;">CONDICION</th>
                        <th style="width: 5%; text-align: center;">BASE IMPONIBLE</th>
                        <th style="width: 5%; text-align: center;">IMPUESTO</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                            <td style="text-align: center;">{{ $anio }}</td>
                            @if($nombre_condicion)
                            <td style="text-align: center;">{{ $nombre_condicion[0]->desc_exon }}</td>
                            @else
                            <td style="text-align: center;">{{ $nombre_condicion1 }}</td>
                            @endif
                            <td style="text-align: center;">{{ number_format($base_imponible[0]->base_imponible,2,'.',',') }} </td>
                            <td style="text-align: center;">{{ number_format($impuesto[0]->impuesto,2,'.',',') }}</td>
                    </tr>
                </tbody>
        </table>
    </div>
</body>

</html>