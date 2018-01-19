<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Listado de Datos de los Contribuyentes</title>
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

    <center><div Class="asunto" style="margin-top: 10px;"><b>REPORTE DE CANTIDAD DE CONTRIBUYENTES Y PREDIOS POR ZONA</b></div></center>
    <h5 class="subasunto" style="font-size:0.8em;  text-align: right; padding-left: 30px;">{{$usuario[0]->ape_nom}} - {{ $fecha }}</h5>
    <div class="subasunto" style="text-align: left; padding-left: 30px; margin-top: 20px;">
            <table>
                <thead>
                    <tr>
                         <th style="width: 5%; text-align: center;">AÑO</th>
                        <th style="width: 5%; text-align: center;">SECTOR</th>
                        <th style="width: 5%; text-align: center;">NRO SECTORES</th>
                        <th style="width: 5%; text-align: center;">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="width: 5%; text-align: center;">{{$anio}}</td>
                        <td style="width: 5%; text-align: center;">{{$sector}}</td>
                        <td style="width: 5%; text-align: center;">{{$nro_sectores[0]->total}}</td>
                        <td style="width: 5%; text-align: center;">{{$total[0]->total}}</td>
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
                <th style="width: 5%; text-align: center;">N°</th>
                <th style="width: 7%; text-align: center;">DNI/RUC</th>
                <th style="width: 25%; text-align: center;">NOMBRE</th>
                <th style="width: 13%; text-align: center;">TIPO CONTRIBUYENTE</th>
                <th style="width: 25%; text-align: center;">DOMICILIO FISCAL</th>
                <th style="width: 25%; text-align: center;">LISTA DE PREDIOS</th>
              
            </tr>
            </thead>
            <tbody>

            @foreach ($sql as $cont)
                <tr>
                    <td style="text-align: center;">{{ $num++ }}</td>
                    <td style="text-align: center;">{{ $cont->nro_doc }}</td>
                    <td style="text-align: center;">{{$cont->contribuyente}}</td>
                    <td style="text-align: center;">{{$cont->cond_prop_descripc}}</td>
                    <td style="text-align: center;">{{$cont->dom_fis}}</td>
                    <td style="text-align: center;">{{$cont->cod_via}} - {{$cont->nom_via}} - {{$cont->nro_mun}} - {{$cont->referencia}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
   
</body>

</html>