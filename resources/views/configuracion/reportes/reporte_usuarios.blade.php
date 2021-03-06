<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Listado de Usuarios</title>
    <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
    <style>
        .move-ahead { counter-increment: page 2; position: absolute; visibility: hidden; }
        .pagenum:after { content:' ' counter(page); }
       .footer {position: fixed }

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

    <center><div Class="asunto" style="margin-top: 1px;font-size:0.8em;"><b>REPORTE DE USUARIOS</b></div></center>
    <div class="subasunto" style=" margin-bottom:5px; text-align: left; padding-left: 30px;font-size:0.7em;"> 
        <br>  
    </div>
    
    <input type="hidden" value=" {{$num= 1}}">

    <div class="lado3" style="height: 435px; margin-bottom: 20px;">
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom:20px; margin-top: 0px;  font-size: 1.0em;">
            <thead>
            <tr >
                <th style="width: 5%;">N°</th>
                <th style="width: 10%">DNI/RUC</th>
                <th style="width: 50%;">NOMBRE COMPLETO</th>
                <th style="width: 15%">USUARIO</th>
                <th style="width: 10%">FECHA NACIMIENTO</th>
            </tr>
            </thead>
            <tbody>

            @foreach ($sql as $cont)
                <tr>
                    <td style="text-align: center;">{{ $num++ }}</td>
                    <td style="text-align: center;">{{$cont->dni}}</td>
                    <td style="text-align: left;">{{ $cont->ape_nom }}</td>
                    <td style="text-align: left;">{{ $cont->usuario }}</td>
                    <td style="text-align: left;">{{$cont->fch_nac}}</td>
                </tr>
                
            @endforeach

            </tbody>
        </table>
    </div>
</body>

</html>