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
                        <h1>{{$institucion[0]->nom1}}&nbsp;{{$institucion[0]->nom2}}</h1>
                        <div class="sub2">Creado por Ley 12075 el día 26 de Febrero de 1954</div>
                    </div>
                    <div style="width: 90%; border-top:1px solid #999; margin-top: 10px; margin-left: 25px;"></div>
                </div>
            </td>
            <td style="width: 10%;border: 0px;"></td>
        </tr>

    </table>

    <center><div Class="asunto" style="margin-top: 10px;"><b>RECIBO DE INGRESOS</b></div></center>
    <div class="subasunto" style="text-align: left; padding-left: 30px; margin-top: 20px;">
       
        
         
    </div>
    <div class="sub2">Recibo de Ingresos del día : {{$fecha}}</div>
  
     <div class="sub2">Caja : {{$descrip_caja}}</div>              
    <input type="hidden" value=" {{$num= 1}}">

    <div class="lado3" style="height: 435px; border-bottom: 0px solid #333">

        <br>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0px; font-size: 1.3em;">
            <thead>
            
            <tr>
                <th style="width: 10%;">Cta patrimonial</th>
                <th style="width: 10%">Debe</th>
                <th style="width: 10%">Haber</th>
                
            </tr>
            </thead>
            <tbody>
            @foreach ($sqldebe as $debe)
          <tr>
              <td style="text-align: center;font-size: 0.7em; padding: 0px;">{{$debe->cta_pat}}</td>
              <td style="text-align: right;font-size: 0.7em; padding-right: 10px;">{{ number_format($debe->debe,2,".",",")}}</td>
              <td style="text-align: right;font-size: 0.7em; padding-right: 10px;"></td>

          </tr>
          @endforeach
          @foreach ($sqlhaber as $haber)
          <tr>
              <td style="text-align: center;font-size: 0.7em; padding: 0px;">{{$haber->cta_pat}}</td>
              <td style="text-align: right;font-size: 0.7em; padding-right: 10px;"></td>
              <td style="text-align: right;font-size: 0.7em; padding-right: 10px;">{{ number_format($haber->haber,2,".",",")}}</td>

          </tr>
          @endforeach
           <tr>
              <td style="border-bottom: hidden;border-left:hidden; text-align: right;font-size: 0.7em; padding: 0px;"><b>TOTAL:&nbsp;&nbsp;&nbsp; </b></td>
              <td style="text-align: right;font-size: 0.7em; padding-right: 10px;">{{ number_format($sqldebe->sum('debe'),2,'.',',')  }}</td>
              <td style="text-align: right;font-size: 0.7em; padding-right: 10px;">{{ number_format($sqlhaber->sum('haber'),2,'.',',')  }}</td>

          </tr>
            </tbody>
        </table>
        <br>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0px; font-size: 1.3em;">
            <thead>
            
            <tr>
                <th style="width: 10%;">Cta presupuestal</th>
                <th style="width: 10%">Debe</th>
                <th style="width: 10%">Haber</th>
                
            </tr>
            </thead>
            <tbody>
            @foreach ($sqlpresdebe as $presdebe)
          <tr>
              <td style="text-align: center;font-size: 0.7em; padding: 0px;">{{$presdebe->cta_presup_debe}}</td>
              <td style="text-align: right;font-size: 0.7em; padding-right: 10px;">{{ number_format($presdebe->debe,2,".",",")}}</td>
              <td style="text-align: right;font-size: 0.7em; padding-right: 10px;"></td>

          </tr>
          @endforeach
          
          @foreach ($sqlpreshaber as $preshaber)
          <tr>
              <td style="text-align: center;font-size: 0.7em; padding: 0px;">{{$preshaber->cta_presup_haber}}</td>
              <td style="text-align: right;font-size: 0.7em; padding-right: 10px;"></td>
              <td style="text-align: right;font-size: 0.7em; padding-right: 10px;">{{ number_format($preshaber->haber,2,".",",")}}</td>

          </tr>
          @endforeach
            
          <tr>
              <td style="border-bottom: hidden;border-left:hidden; text-align: right;font-size: 0.7em; padding: 0px;"><b>TOTAL:&nbsp;&nbsp;&nbsp; </b></td>
              <td style="text-align: right;font-size: 0.7em; padding-right: 10px;">{{ number_format($sqlpresdebe->sum('debe'),2,'.',',')  }}</td>
              <td style="text-align: right;font-size: 0.7em; padding-right: 10px;">{{ number_format($sqlpreshaber->sum('haber'),2,'.',',')  }}</td>

          </tr>
            </tbody>
          
        </table>

        
        
        
    </div>
  
</body>

</html>