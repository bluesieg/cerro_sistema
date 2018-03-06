<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Listado Contribuyentes (Pricos,Mecos,Pecos)</title>
    <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
    <style>
        .move-ahead { counter-increment: page 2; position: absolute; visibility: hidden; }
        .pagenum:after { content:' ' counter(page); }
    </style>
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
                        <h1>MUNICIPALIDAD DISTRITAL DE CERRO COLORADO</h1>
                        <div class="sub2">Creado por Ley 12075 el día 26 de Febrero de 1954</div>
                    </div>
                    <div  style="width: 95%; border-top:1px solid #999; margin-top: 5px; margin-left: 25px"></div>
                </div>
            </td>
            <td style="width: 10%;border: 0px;"></td>
        </tr>

    </table>

    <center><div Class="asunto" style="margin-top: 1px;font-size:0.8em;"><b>REPORTE LISTADO DE CONTRIBUYENTES (Pricos,Mecos,Pecos)</b></div></center>
    <div class="subasunto" style=" margin-bottom:5px; text-align: left; padding-left: 30px;font-size:0.7em;"> 
        <br>
        Año: {{ $anio }} - Monto desde: {{$min}} hasta {{$max}}
        
    </div>
    <input type="hidden" value=" {{$num= 1}}">

    <div class="lado3" style="height: 435px; border-bottom: 1px solid #333">

        <br>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0px; font-size: 1.0em;">
            <thead>
            <tr >
                <th style="width: 3%;">N°</th>
                <th style="width: 5%">DNI/RUC</th>
                <th style="width: 20%;">CONTRIBUYENTE</th>
                <th style="width: 35%">DOMICILIO</th>
                <th style="width: 8%">IMPUESTO</th>
                <th style="width: 10%">SALDO</th>
            </tr>
            </thead>
            <tbody>

            @foreach ($sql as $cont)
                <tr>
                    <td style="text-align: center;">{{ $num++ }}</td>
                    <td style="text-align: center;">{{ $cont->nro_doc }}</td>
                    <td style="text-align: left;">{{$cont->contribuyente}}</td>
                    <td style="text-align: left;">{{ $cont->dom_fis }}</td>
                    <td style="text-align: center;">{{ number_format($cont->ivpp,2,'.',',')}}</td>
                    <td style="text-align: center;">{{ number_format($cont->saldo,2,'.',',') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
