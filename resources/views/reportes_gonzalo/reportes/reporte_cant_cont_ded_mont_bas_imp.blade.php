<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Predios Ingresados Por Usuario</title>
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
                        <h1>MUNICIPALIDAD DISTRITAL DE CERRO COLORADO</h1>
                        <div class="sub2">Creado por Ley 12075 el día 26 de Febrero de 1954</div>
                    </div>
                    <div  style="width: 95%; border-top:1px solid #999; margin-top: 5px; margin-left: 25px"></div>
                </div>
            </td>
            <td style="width: 10%;border: 0px;"></td>
        </tr>

    </table>

    <center><div Class="asunto" style="margin-top: 10px; font-size:0.8em;"><b>Reporte de cantidad de contribuyentes con deduccion de 50 UIT - Pensionista y Adulto mayor</b></div></center>
    <div class="subasunto" style=" margin-bottom:1px; text-align: left; padding-left: 30px;font-size:0.7em;">
        <h5 class="subasunto" style="font-size:0.8em;  text-align: right; padding-left: 30px;">{{ $fecha }}</h5>  
    </div>
    <div class="subasunto" style="text-align: left; padding-left: 30px; margin-top: 20px; font-size:0.8em;">
        <table>
                <thead>
                    <tr>
                        <th style="width: 5%; text-align: center;">AÑO</th>
                        <th style="width: 5%; text-align: center;">HAB. URBANA</th>
                        <th style="width: 5%; text-align: center;">TIPO CONDICION</th>
                        <th style="width: 5%; text-align: center;">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                            <td style="text-align: center;">{{ $anio }}</td>
                            <td style="text-align: center;">{{ $sql[0]->nomb_hab_urba }}</td>
                            <td style="text-align: center;">{{ $sql[0]->desc_exon }} </td>
                            <td style="text-align: center;">{{ $total[0]->condiciones }}</td>
                    </tr>
                </tbody>
            </table>
    </div>
   
    <input type="hidden" value=" {{$num= 1}}">

    <div class="lado3" style="font-size:0.8em; height: 435px; border-bottom: 0px solid #333 ">

        <br>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0px; font-size: 0.8em;">
            <thead>
            <tr >
                 <th style="width: 5%; text-align: center;">Nº</th>
                <th style="width: 8%; text-align: center;">DNI</th>
                <th style="width: 25%; text-align: center;">NOMBRE</th>
                <th style="width: 25%; text-align: center;">DOMICILIO FISCAL</th>
                <th style="width: 7%; text-align: center;">MZNA</th>
                <th style="width: 7%; text-align: center;">LOTE</th>
                <th style="width: 10%; text-align: center;">DEDUCCION</th>
                <th style="width: 10%; text-align: center;">BASE IMPONIBLE</th>
            </tr>
            </thead>
            <tbody>

            @foreach ($sql as $cont)
                <tr>
                    <td style="text-align: center;">{{ $num++ }}</td>
                    <td style="text-align: center;">{{ $cont->pers_nro_doc }}</td>
                    <td style="text-align: center;">{{$cont->contribuyente}}</td>
                    <td style="text-align: center;">{{ $cont->dom_fis }}</td>
                    <td style="text-align: center;">{{ $cont->mzna }}</td>
                    <td style="text-align: center;">{{ $cont->lote }}</td>
                    <td style="text-align: center;">{{ $cont->porctje }}%</td>
                    <td style="text-align: center;">{{ number_format($cont->base_impon,2,'.',',')}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>