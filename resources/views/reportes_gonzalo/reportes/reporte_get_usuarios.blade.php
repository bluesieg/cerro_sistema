<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Predios Ingresados Por Usuario</title>
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

    <center><div Class="asunto" style="margin-top: 10px;"><b>Reporte de Predios Ingresados Por Usuario</b></div></center>
    <div class="subasunto" style="text-align: center; padding-left: 30px; margin-top: 20px;">
        <table>
            <thead>
                <tr> 
                    <th colspan="1" style="text-align: center; width: 10%;">FECHA INICIO</th>
                    <th colspan="1" style="text-align: center; width: 10%;">FECHA FIN</th>
                    <th colspan="1" style="text-align: center; width: 10%;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="1" style="text-align: center;">{{ $fechainicio }}</td>
                    <td colspan="1" style="text-align: center;">{{ $fechafin }}</td>
                    <td colspan="1" style="text-align: center;">{{ $total[0]->usuario }}</td>
                </tr>
            </tbody>
        </table>
    </div>
   
    <input type="hidden" value=" {{$num= 1}}">

    <div class="lado3" style="height: 435px; border-bottom: 1px solid #333">

        <br>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0px; font-size: 1.3em;">
            <thead>
            <tr >
                 <th style="width: 5%; text-align: center;">Nº</th>
                <th style="width: 28%; text-align: center;">NOMBRE</th>
                <th style="width: 7%; text-align: center;">DNI</th>
                <th style="width: 5%; text-align: center;">SECTOR</th>
                <th style="width: 5%; text-align: center;">MZ</th>
                <th style="width: 5%; text-align: center;">LOTE</th>
                <th style="width: 30%; text-align: center;">CONTRIBUYENTE</th>
                <th style="width: 10%; text-align: center;">FECHA REGISTRO</th>
                <th style="width: 5%; text-align: center;">HORA REGISTRO</th>
            </tr>
            </thead>
            <tbody>

            @foreach ($sql as $cont)
                <tr>
                    <td style="text-align: center;">{{ $num++ }}</td>
                    <td style="text-align: center;">{{ $cont->nom_usu }}</td>
                    <td style="text-align: center;">{{$cont->dni_usu}}</td>
                    <td style="text-align: center;">{{ $cont->sec }}</td>
                    <td style="text-align: center;">{{ $cont->mzna }}</td>
                    <td style="text-align: center;">{{ $cont->lote }}</td>
                    <td style="text-align: center;">{{ $cont->contribuyente }}</td>
                    <td style="text-align: center;">{{ $cont->fec_reg }}</td>
                    <td style="text-align: center;">{{ $cont->hora_reg }}</td> 
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>