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
<footer class="footer" style="font-size:0.8em; text-align: left; padding-top: 5px; padding-left: 10px;"><b>Impreso Por:&nbsp; </b>{{$usuario[0]->usuario}}</footer>
<body>
    <div class="datehead">{{ $fecha }}</div>
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
            <td style="width: 10%;border: 0px;"></td>REPORTE DE EMISION PREDIAL POR USO
        </tr>

    </table>
    <center><div Class="asunto" style="margin-top: 1px;margin-bottom: 5px;font-size:0.8em;"><b>REPORTE DE CONTRIBUYENTES</b></div></center>
    <div class="subasunto" style=" margin-bottom:1px; text-align: left; padding-left: 30px;font-size:0.7em;"> 
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%; text-align: center;">AÑO</th>
                        <th style="width: 25%; text-align: center;">HAB. URBANA</th>
                        <th style="width: 5%; text-align: center;">TIPO DE USO</th>
                        <th style="width: 3%; text-align: center;">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                            <td style="text-align: center;">{{ $anio }}</td>
                            <td style="text-align: center;">{{ $sql[0]->nomb_hab_urba }}</td>
                             @if ($uso == 0)
                            <td style="text-align: center;">TODOS</td>
                               @else
                            <td style="text-align: center;">{{ $sql[0]->uso_arbitrio }}</td>
                            @endif  
                            <td style="text-align: center;">{{ $total[0]->usos }}</td>
                    </tr>
                </tbody>
            </table>
    </div>
    
    <input type="hidden" value=" {{$num= 1}}">

    <div class="lado3" style="height: 435px; border-bottom: 0px solid #333">

        <br>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0px; font-size: 1.0em;">
            <thead>
            <tr >
                <th style="width: 5%; text-align: center;">N°</th>
                <th style="width: 10%; text-align: center;">DNI/RUC</th>
                <th style="width: 30%; text-align: center;">CONTRIBUYENTE</th>
                <th style="width: 55%; text-align: center;">DOMICILIO</th>
            </tr>
            </thead>
            <tbody>

            @foreach ($sql as $cont)
                <tr>
                    <td style="text-align: center;">{{ $num++ }}</td>
                    <td style="text-align: center;">{{$cont->pers_nro_doc}}</td>
                    <td style="text-align: center;">{{ $cont->contribuyente }}</td>
                    <td style="text-align: center;">{{$cont->dir_pred}} - {{$cont->referencia}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
   
</body>

</html>