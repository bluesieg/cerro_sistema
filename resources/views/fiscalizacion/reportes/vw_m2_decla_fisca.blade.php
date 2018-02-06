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
    <footer class="footer" style="font-size:0.8em; text-align: left; padding-top: 5px; padding-left: 10px;"><b>Impreso Por:&nbsp; </b>{{$name}}</footer>
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

    <center><div Class="asunto" style="margin-top: 1px;font-size:0.8em;"><b>REPORTE M2 DECLARADOS VS FISCALIADOS {{ $anio }}</b></div></center>
    <div class="subasunto" style=" margin-bottom:1px; text-align: left; padding-left: 30px;font-size:0.7em;">
        <h5 class="subasunto" style="font-size:0.8em;  text-align: right; padding-left: 30px;">{{ date("d/m/Y") }}</h5>  
    </div>
    <div class="lado3" style=" margin-top: 5px;">
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom:20px; margin-top: 0px;  font-size: 1.0em;">
            <thead>
            <tr >
                <th style="width: 6%; ">N° Ficha</th>
                <th style="width: 8%">DNI/RUC</th>
                <th style="width: 16%;">CONTRIBUYENTE</th>
                <th style="width: 5%;">TIPO</th>
                <th style="width: 51%;">PREDIO FISCALIZADO</th>
                <th style="width: 7%;">AT Dif</th>
                <th style="width: 7%;">AC Dif</th>
            </tr>
            </thead>
            <tbody>

            @foreach ($sql as $cont)
                <tr>
                    <td style="text-align: center;padding: 0px">{{ trim($cont->nro_fic) }}</td>
                    <td style="text-align: center; padding: 0px">{{trim($cont->nro_doc)}}</td>
                    <td style="text-align: left;">{{ trim($cont->contribuyente) }}</td>
                    <td style="text-align: left;">{{trim($cont->tp)}}</td>
                    <td style="text-align: left;">
                        @if(trim($cont->tp)=='URB')
                            {{trim($cont->habilitacion)." ".trim($cont->nom_via)." ".trim($cont->nom_via)}}
                            @if($cont->nro_mun!='')
                                {{$cont->nro_mun}}
                            @endif
                            @if($cont->mzna_dist!='')
                                Mzna {{$cont->mzna_dist}}
                            @endif
                            @if($cont->lote_dist!='')
                                Lte {{$cont->lote_dist}}
                            @endif
                            @if(trim($cont->referencia)!=''&&trim($cont->referencia)!='-')
                                <br>Referencia: {{$cont->referencia}}
                            @endif
                        @else
                            {{trim($cont->lugar_pr_rust)." ".trim($cont->ubicac_pr_rus)}}
                            @if(trim($cont->klm)!='')
                                KLM: {{$cont->klm}}
                            @endif
                            @if(trim($cont->nom_pre_pr_rus)!='')
                                NOMBRE: {{$cont->nom_pre_pr_rus}}
                            @endif
                            
                        @endif
                    </td>
                    <td style="text-align: center;padding: 0px">
                        @if(trim($cont->tp)=='URB')
                        {{$cont->are_terr - $cont->are_terr_declarado}} m2
                        @else
                         {{$cont->hectareas-$cont->are_terr_declarado }}  hec 
                        @endif
                    </td>
                    <td style="text-align: center;padding: 0px">
                        {{$cont->are_const_fis - $cont->are_const_declar}} m2
                    </td>
                </tr>
                
            @endforeach

            </tbody>
        </table>
    </div>
</body>

</html>