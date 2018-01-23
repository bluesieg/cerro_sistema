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

        <div id="details" class="clearfix">
            <div id="invoice" >
                <h2 style="margin-top: 10px; text-align: center;">MUNICIPALIDAD DISTRITAL DE CERRO COLORADO</h2>
                <div style="margin-top: 10px; text-align: center;" class="sub2">Creado por Ley 12075 el día 26 de Febrero de 1954</div>
            </div>
            <div style="width: 90%; border-top:1px solid #999; margin-top: 10px; margin-left: 25px;"></div>
        </div>
        <td style="width: 10%;border: 0px;"></td>

    <center><div style="margin-top: 10px; text-align: center;"><b>REPORTE DE ESTADO DE CUENTA</b></div></center>
    <div class="subasunto" style="text-align: center;">
            <h2 style="text-align: center;">{{$persona}}</h2>
    </div>

</body>

</html>