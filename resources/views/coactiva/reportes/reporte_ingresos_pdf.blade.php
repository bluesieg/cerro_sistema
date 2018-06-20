<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de ingresos coactivo</title>
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

    <center><div Class="asunto" style="margin-top: 10px;"><b>Reporte de Ingresos Coactivo</b></div></center>
    <div class="lado3" >
        <table style="width: 500px; font-size: 1.3em; margin-top: 10px">
            <thead>
                <tr> 
                    <th colspan="1" style="text-align: center; width: 10%;">FECHA INICIO</th>
                    <th colspan="1" style="text-align: center; width: 10%;">FECHA FIN</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="1" style="text-align: center;">{{ $fechainicio }}</td>
                    <td colspan="1" style="text-align: center;">{{ $fechafin }}</td>
                </tr>
            </tbody>
        </table>
    </div>
   
    <input type="hidden" value=" {{$num= 1}}">

    <div class="lado3" >

        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0px; font-size: 1.3em;">
            <thead>
            <tr >
                <th style="width: 3%; text-align: center;">Nº</th>
                <th style="width: 40%; text-align: center;">CONTRIBUYENTE</th>
                <th style="width: 12%; text-align: center;">DOCUMETO</th>
                <th style="width: 10%; text-align: center;">N°<BR>APERSO-<br>NAMIENTO</th>
                <th style="width: 7%; text-align: center;">N°<BR>CUOTA</th>
                <th style="width: 6%; text-align: center;">AÑO</th>
                <th style="width: 10%; text-align: center;">FECHA PAGO</th>
                <th style="width: 12%; text-align: center;">MONTO</th>
            </tr>
            </thead>
            <tbody>

            @foreach ($sql as $cont)
                <tr>
                    <td style="text-align: center;">{{ $num++ }}</td>
                    <td style="text-align: left;">{{ $cont->contribuyente }}</td>
                    <td style="text-align: center;">{{$cont->pers_nro_doc}}</td>
                    <td style="text-align: center;">{{ $cont->nro_resol }}</td>
                    <td style="text-align: center;">{{ $cont->nro_cuo }}</td>
                    <td style="text-align: center;">{{ $cont->anio }}</td>
                    <td style="text-align: center;">{{ $cont->fecha }}</td>
                    <td style="text-align: right; padding-right: 5px">{{ number_format($cont->monto,2,".",",")}}</td>
                </tr>
            @endforeach
            <tr>
                 <td colspan="7" style="text-align: right; padding-right: 5px">TOTAL:</td>
                 <td style="text-align: right; padding-right: 5px">{{ number_format($sql->sum('monto'),2,".",",") }}</td>
             </tr>
             
            </tbody>
            
        </table>
    </div>
</body>

</html>