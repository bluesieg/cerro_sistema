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

    <center><div Class="asunto" style="margin-top: 10px;"><b>Reporte de Cantidad de Contribuyentes y Predios por Zonas</b></div></center>
    <div class="subasunto" style="text-align: left; padding-left: 30px; margin-top: 20px;">
            AÑO: {{ $anio }}
    </div>
   
    <input type="hidden" value=" {{$num= 1}}">

    <div class="lado3" style="height: 435px; border-bottom: 1px solid #333">

        <br>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0px; font-size: 1em;">
            <thead>
            <tr>
                <th colspan="2" style="text-align: center;">NOMBRE</th>
                <th colspan="2" style="text-align: center;">DNI</th>
                <th colspan="2" style="text-align: center;">USUARIO</th>    

            </tr>
            <tr>
                <th style="width: 5%">SECTOR</th>
                <th style="width: 5%;">MZ</th>
                <th style="width: 5%;">LOTE</th>
                <th style="width: 5%;">AÑO</th>
                <th style="width: 5%;">CODIGO VIA</th>
                <th style="width: 5%;">NOMBRE VIA</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                
                <td colspan="2" style="text-align: center;">{{$sql[0]->nom_usu }}</td>
                <td colspan="2" style="text-align: center;">{{$sql[0]->dni_usu}}</td>
                <td colspan="2" style="text-align: center;">{{$sql[0]->usuario}}</td>

            </tr>

            <tr>
                <td style="border-right:0px; border-bottom: 0px; text-align: center;">{{$sql[0]->sec}}</td>
                <td style="border:0px; text-align: center;">{{$sql[0]->mzna}}</td>
                <td style="border:0px; text-align: center;">{{$sql[0]->lote}}</td>
                <td style="border:0px; text-align: center;">{{$sql[0]->anio}}</td>
                <td style="border:0px; text-align: center;">{{$sql[0]->cod_via}}</td>
                <td style="border-left:0px; border-bottom: 0px;text-align: center;">{{$sql[0]->nom_via}}</td>
            </tr>
            
             @for ($i = 1; $i < count($sql); $i++)
                @if($sql[$i]->id_usu == $sql[$i-1]->id_usu)
                    <tr>
                        <td style="border-right:0px; border-bottom: 0px;border-top: 0px; text-align: center;">{{$sql[$i]->sec}}</td>
                        <td style="border:0px; text-align: center;">{{$sql[$i]->mzna}}</td>
                        <td style="border:0px; text-align: center;">{{$sql[$i]->lote}}</td>
                        <td style="border:0px; text-align: center;">{{$sql[$i]->anio}}</td>
                        <td style="border:0px; text-align: center;">{{$sql[$i]->cod_via}}</td>
                        <td style="border-left:0px; border-bottom: 0px;border-top: 0px; text-align: center;">{{$sql[$i]->nom_via}}</td>
                    </tr>
                @else

                    <tr>
                        <td colspan="2" style="text-align: center;">{{$sql[$i]->nom_usu }}</td>
                        <td colspan="2" style="text-align: center;">{{$sql[$i]->dni_usu}}</td>
                        <td colspan="2" style="text-align: center;">{{$sql[$i]->usuario}}</td>

                    </tr>

                    <tr>
                        <td style="border-right:0px; border-bottom: 0px;border-top: 0px; text-align: center;">{{$sql[$i]->sec}}</td>
                        <td style="border:0px; text-align: center;">{{$sql[$i]->mzna}}</td>
                        <td style="border:0px; text-align: center;">{{$sql[$i]->lote}}</td>
                        <td style="border:0px; text-align: center;">{{$sql[$i]->anio}}</td>
                        <td style="border:0px; text-align: center;">{{$sql[$i]->cod_via}}</td>
                        <td style="border-left:0px; border-bottom: 0px;border-top: 0px;text-align: center;">{{$sql[$i]->nom_via}}</td>
                    </tr>
                @endif
            @endfor
            <tr>
                <td colspan="6" style="border-left:0px; border-bottom: 0px;border-right: 0px"></td>
            </tr>
            </tbody>
        </table>
    </div>
   
</body>

</html>