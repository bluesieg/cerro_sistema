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

    <center><div Class="asunto" style="margin-top: 10px;"><b>REPORTE DE INGRESOS POR PARTIDA</b></div></center>
    <div class="subasunto" style="text-align: left; padding-left: 30px; margin-top: 20px;">
        <center><div class="sub2">Desde {{$fechainicio}} - Hasta {{$fechafin}}</div>
                  </div></center>
    </div>
    <input type="hidden" value=" {{$num= 1}}">

    <div class="lado3" style="height: 435px; border-bottom: 1px solid #333">

        <br>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0px; font-size: 1.3em;">
            <thead>
            <tr>
                <th style="width: 20%;">Partida</th>
                <th style="width: 50%">Detalle de Partida</th>
                <th style="width: 30%;">Sub Total</th>
            </tr>
            </thead>
            <tbody>

            <tr>
                <td style="text-align: left; border-left:0px; border-bottom: 0px;border-right: 0px; border-top: 0px;"><b>{{$sql[0]->codigo_2}}</b></td>
                <td style="text-align: left; border-left:0px; border-bottom: 0px;border-right: 0px; border-top: 0px;"><b>{{$sql[0]->det_especifica}}</b></td>
    

            </tr>

            <tr>
 
                <td style="text-align: left; border-left:0px; border-bottom: 0px;border-right: 0px; border-top: 0px;">{{$sql[0]->codigo_1}}</td>
                <td style="text-align: left; border-left:0px; border-bottom: 0px;border-right: 0px; border-top: 0px;">{{$sql[0]->desc_espec_detalle}}</td>
                <td style="text-align: center; border-left:0px; border-bottom: 0px;border-right: 0px; border-top: 0px;">{{$sql[0]->total}}</td>
            </tr>
            
            @for ($i = 1; $i < count($sql); $i++)
                @if($sql[$i]->codigo_1 == $sql[$i-1]->codigo_1)
                    <tr>

                        <td style="text-align: left; border-left:0px; border-bottom: 0px;border-right: 0px; border-top: 0px;">{{$sql[$i]->codigo_1}}</td>
                        <td style="text-align: left; border-left:0px; border-bottom: 0px;border-right: 0px; border-top: 0px;">{{$sql[$i]->desc_espec_detalle}}</td>
                        <td style="text-align: center; border-left:0px; border-bottom: 0px;border-right: 0px; border-top: 0px;">{{$sql[$i]->total}}</td>
                    </tr>
                @else

                    <tr>
                        <td style="text-align: left; border-left:0px; border-bottom: 0px;border-right: 0px; border-top: 0px;"><b>{{$sql[$i]->codigo_2}}</b></td>
                        <td style="text-align: left; border-left:0px; border-bottom: 0px;border-right: 0px; border-top: 0px;"><b>{{$sql[$i]->det_especifica}}</b></td>
                      
   
                    </tr>

                    <tr>
                        
                        <td style="text-align: left; border-left:0px; border-bottom: 0px;border-right: 0px; border-top: 0px;">{{$sql[$i]->codigo_1}}</td>
                        <td style="text-align: left; border-left:0px; border-bottom: 0px;border-right: 0px; border-top: 0px;">{{$sql[$i]->desc_espec_detalle}}</td>
                        <td style="text-align: center; border-left:0px; border-bottom: 0px;border-right: 0px; border-top: 0px;">{{$sql[$i]->total}}</td>

                    </tr>
                @endif
            @endfor
            <tr>
                <td colspan="4" style="border-left:0px; border-bottom: 0px;border-right: 0px"></td>
            </tr>
            </tbody>
        </table>
    </div>
  
</body>

</html>