<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Importe Insoluto total de la deuda por impuesto predial transferida a Ejecución Coactiva</title>
    <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
    <style>
        .move-ahead { counter-increment: page 2; position: absolute; visibility: hidden; }
        .pagenum:after { content:' ' counter(page); }
        .footer {position: fixed }
    </style>
</head>
    <footer class="footer" style="font-size:0.8em; text-align: left; padding-top: 5px; padding-left: 10px;"><b>Impreso Por:&nbsp; </b>{{$usuario[0]->ape_nom}}</footer>

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
                       <h1>{{$institucion[0]->nom1}}&nbsp;{{$institucion[0]->nom2}}</h1>
                        <div class="sub2">Creado por Ley 12075 el día 26 de Febrero de 1954</div>
                    </div>
                    <div  style="width: 95%; border-top:1px solid #999; margin-top: 5px; margin-left: 25px"></div>
                </div>
            </td>
            <td style="width: 10%;border: 0px;"></td>
        </tr>

    </table>

    <center><div Class="asunto" style="margin-top: 10px; font-size:0.8em;"><b>Reporte de Importe Insoluto total de la deuda por impuesto predial transferida a Ejecución Coactiva</b></div></center>
    <div class="subasunto" style=" margin-bottom:1px; text-align: left; padding-left: 30px;font-size:0.7em;">
        <h5 class="subasunto" style="font-size:0.8em;  text-align: right; padding-left: 30px;">{{ $fecha }}</h5> 
        <h4 class="subasunto" style="font-size:1em;  text-align: left; ">Año : {{ $anio }}</h5>  
    </div>
   
    <input type="hidden" value=" {{$num= 1}}">

    <div class="lado3" style="font-size:0.8em; height: 435px; border-bottom: 0px solid #333 ">

        <br>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0px; font-size: 0.9em;">
            <thead>
            <tr >
                 <th style="width: 5%; text-align: center;">Nº</th>
                <th style="width: 50%; text-align: center;">CONTRIBUYENTE</th>
                <th style="width: 8%; text-align: center;">DOCUMENTO</th>
                <th style="width: 7%; text-align: center;">MONTO TOTAL S/.</th>
                <th style="width: 7%; text-align: center;">MONTO PAGADO S/. </th>
            
            </tr>
            </thead>
            <tbody>

            @foreach ($sql as $cont)
                <tr>
                    <td style="text-align: center;">{{ $num++ }}</td>
                    <td style="text-align: center;">{{ $cont->contribuyente }}</td>
                    <td style="text-align: left;">RD - {{$cont->nro_rd}}</td>
                    <td style="text-align: center;">{{ $cont->ivpp_verif }}</td>
                    <td style="text-align: center;"> {{ $cont->pagado }}</td>
          
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>