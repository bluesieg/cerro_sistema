<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Listado de Datos de los Contribuyentes y Predios</title>
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

    <center><div Class="asunto" style="margin-top: 10px;"><b>LISTADO DE CONTRIBUYENTES Y PREDIOS</b></div></center>
    <h5 class="subasunto" style="font-size:0.8em;  text-align: right; padding-left: 30px;">{{$usuario[0]->ape_nom}} - {{ $fecha }}</h5>
    <input type="hidden" value=" {{$num= 1}}">

    <div class="lado3" style="height: 435px; border-bottom: 1px solid #333">

        <br>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0px; font-size: 1.3em;">
            <thead>
            <tr>
                <th style="width: 10%;">Código</th>
                <th style="width: 10%">DNI/RUC</th>
                <th style="width: 30%;">Nombre o Razon Social</th>
                <th style="width: 30%;">Listado de Predios</th>
                <th style="width: 10%">Area de Terreno Construida</th>
                <th style="width: 10%">Area de Terreno </th>
            </tr>
            </thead>
            <tbody>

            <tr>
                <td style="text-align: center;">{{$sql[0]->id_persona}}</td>
                <td style="text-align: center;">{{$sql[0]->nro_doc_contri}}</td>
                <td style="border-right:0px; text-align: center;">{{$sql[0]->contribuyente}}</td>
                <td colspan="3" style="text-align: center;"></td>

            </tr>

            <tr>
                <td style="border-right:0px; border-bottom: 0px; text-align: center;"></td>
                <td style="border-right:0px; border-bottom: 0px; text-align: center;"></td>
                <td style="border-right:0px; border-bottom: 0px; text-align: center;"></td>
                <td style="text-align: center;">{{$sql[0]->cod_via}} - {{$sql[0]->nom_via}} - {{$sql[0]->nro_mun}} - {{$sql[0]->referencia}}</td>
                <td style="text-align: center;">{{number_format($sql[0]->are_terr,2,'.',',')}}</td>
                <td style="text-align: center;">{{number_format($sql[0]->area_const,2,'.',',')}}</td>
            </tr>
            
            @for ($i = 1; $i < count($sql); $i++)
                @if($sql[$i]->id_contrib == $sql[$i-1]->id_contrib)
                    <tr>
                        <td style="border-right:0px; border-bottom: 0px;border-top: 0px; text-align: center;"></td>
                        <td style="border-right:0px; border-bottom: 0px;border-top: 0px; text-align: center;"></td>
                        <td style="border-right:0px; border-bottom: 0px;border-top: 0px; text-align: center;"></td>
                        <td style="text-align: center;">{{$sql[$i]->cod_via}} - {{$sql[$i]->nom_via}} - {{$sql[$i]->nro_mun}} - {{$sql[$i]->referencia}}</td>
                        <td style="text-align: center;">{{number_format($sql[$i]->are_terr,2,'.',',')}}</td>
                        <td style="text-align: center;">{{number_format($sql[$i]->area_const,2,'.',',')}}</td>
                    </tr>
                @else

                    <tr>
                        <td style="text-align: center;">{{$sql[$i]->id_persona}}</td>
                        <td style="text-align: center;">{{$sql[$i]->nro_doc_contri}}</td>
                        <td style="border-right:0px; text-align: center;">{{$sql[$i]->contribuyente}}</td>
                        <td colspan="3" style="text-align: center;"></td>
   
                    </tr>

                    <tr>
                        <td style="border-right:0px; border-bottom: 0px;border-top: 0px; text-align: center;"></td>
                        <td style="border-right:0px; border-bottom: 0px;border-top: 0px; text-align: center;"></td>
                        <td style="border-right:0px; border-bottom: 0px;border-top: 0px; text-align: center;"></td>
                        <td style="text-align: center;">{{$sql[$i]->cod_via}} - {{$sql[$i]->nom_via}} - {{$sql[$i]->nro_mun}} - {{$sql[$i]->referencia}}</td>
                        <td style="text-align: center;">{{number_format($sql[$i]->are_terr,2,'.',',')}}</td>
                        <td style="text-align: center;">{{number_format($sql[$i]->area_const,2,'.',',')}}</td>

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