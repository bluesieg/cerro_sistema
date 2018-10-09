<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
   
    <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
    <style>
        .move-ahead { counter-increment: page 2; position: absolute; visibility: hidden; }
        .pagenum:after { content:' ' counter(page); }
       .footer {position: fixed }

    </style>
</head>
    

<body>
    
    <input type="hidden" value=" {{$num= 1}}">

    <div class="lado3" style="height: 435px; margin-bottom: 20px;">
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom:20px; margin-top: 0px;  font-size: 1.0em;">
            
            <thead>
            <tr>
                <th style="width: 10%;">N°</th>
                <th style="width: 25%">CODIGO</th>
                <th style="width: 20%">DNI/RUC</th>
                <th style="width: 50%">NOMBRE</th>
                <th colspan="2" style="width: 150%">DIRECCION</th>
                <th style="width: 25%">ESTADO</th>
                <th style="width: 15%">TIPO</th>
                <th style="width: 25%">USO</th>
                <th style="width: 30%">AREA COMUN</th>
                <th style="width: 30%">AREA TERRENO</th>
                <th style="width: 30%">AUTOVALUO</th>
            </tr>
            </thead>
            <thead>
            <tr>
                <th style="width: 10%;"></th>
                <th style="width: 25%;">NUMERO PISO</th>
                <th colspan="2" style="width: 70%">CLASIFICACION</th>
                <th style="width: 75%">MATERIAL</th>
                <th style="width: 75%">ESTADO CONSERVACION</th>
                <th colspan="4" style="width: 95%">CATEGORIAS</th>
                <th style="width: 30%">AREA CONSTRUCCION</th>
                <th style="width: 30%">AREA COMUN</th>
            </tr>
            </thead>
            <tbody>

            @foreach ($predios as $predio)
                <?php $pisos = DB::table('adm_tri.pisos')->where('id_pred_anio',$predio->id_pred_anio)->get(); ?>
                <tr>
                    <td style="text-align: center;">{{ $num++ }}</td>
                    <td style="text-align: center;">{{$predio->id_persona}}</td>
                    <td style="text-align: center;">{{$predio->nro_doc}}</td>
                    <td style="text-align: center;">{{$predio->contribuyente}}</td>
                    <td colspan="2" style="text-align: center;">{{$predio->domicilio}}</td>
                    <td style="text-align: center;">{{$predio->descripcion}}</td>
                    <td style="text-align: center;">{{$predio->tp}}</td>
                    <td style="text-align: center;">{{$predio->desc_uso}}</td>
                    <td style="text-align: center;">{{$predio->are_com_terr}}</td>
                    <td style="text-align: center;">{{$predio->are_terr}}</td>
                    <td style="text-align: center;">{{$predio->autovaluo}}</td>
                </tr>
                
                
                @foreach ($pisos as $piso)
                <tr>
                    <td style="text-align: center;"></td>
                    <td style="text-align: center;">{{$piso->num_pis}}</td>
                    <td colspan="2" style="text-align: center;">{{$piso->clas}}</td>
                    <td style="text-align: center;">{{$piso->mep}}</td>
                    <td style="text-align: center;">{{$piso->esc}}</td>
                    <td colspan="4" style="text-align: center;">{{$piso->est_mur}}</td>
                    <td style="text-align: center;">{{$piso->area_const}}</td>
                    <td style="text-align: center;">{{$piso->val_areas_com}}</td>
                </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>