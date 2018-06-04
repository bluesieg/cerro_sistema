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
                        <h1>{{$institucion[0]->nom1}}&nbsp;{{$institucion[0]->nom2}}</h1>
                        <div class="sub2">Creado por Ley 12075 el día 26 de Febrero de 1954</div>
                    </div>
                    <div style="width: 90%; border-top:1px solid #999; margin-top: 10px; margin-left: 25px;"></div>
                </div>
            </td>
            <td style="width: 10%;border: 0px;"></td>
        </tr>

    </table>

    <center><div Class="asunto" style="margin-top: 10px;"><b>Reporte de Cantidad de Contribuyentes y Predios por Zonas</b></div></center>
    <div class="subasunto" style="text-align: left; padding-left: 30px; margin-top: 20px;">
            AÑO: {{ $anio }} - SECTOR {{ $sector }} - MANZANA {{ $manzana }}
    </div>
   
    <input type="hidden" value=" {{$num= 1}}">

    <div class="lado3" style="height: 435px; border-bottom: 1px solid #333">

        <br>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0px; font-size: 1.3em;">
            <thead>
            <tr >
                <th style="width: 5%;">N°</th>
                <th style="width: 10%; text-align: center;">LOTE</th>
                <th style="width: 40%; text-align: center;">NOMBRE</th>
                <th style="width: 10%; text-align: center;">DNI</th>
                <th style="width: 10%; text-align: center;">USUARIO</th>
                <th style="width: 10%; text-align: center;">AÑO</th>
                <th style="width: 15%; text-align: center;">CODIGO VIA</th>
            </tr>
            </thead>
            <tbody>

            @foreach ($sql as $cont)
                <tr>
                    <td style="text-align: center;">{{ $num++ }}</td>
                    <td style="text-align: center;">{{ $cont->lote }}</td>
                    <td style="text-align: center;">{{ $cont->nom_usu }}</td>
                    <td style="text-align: center;">{{$cont->dni_usu}}</td>
                    <td style="text-align: center;">{{ $cont->usuario }}</td>
                    <td style="text-align: center;">{{ $cont->anio }}</td>
                    <td style="text-align: center;">{{ $cont->id_via }}</td>
                    
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <p class="pagenum" style="padding-top: 130px;text-align: center"> Gerencia de Administración Tributaria - Página</p>
</body>

</html>