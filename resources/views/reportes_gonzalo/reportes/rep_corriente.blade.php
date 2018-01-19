<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Impuesto Predial Corriente/No Corriente</title>
        <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
        <style>
            @page { margin-bottom: 10px !important; margin-left: 50px;margin-right: 50px;};
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
        
        <center><div Class="asunto" style="margin-top: 0px;"><b>Reporte Impuesto Predial Corriente y No Corriente
                </b></div></center>
        <br>
        <h5 class="subasunto" style="font-size:0.8em;  text-align: right; padding-left: 30px;">{{$usuario[0]->ape_nom}} - {{ $fecha }}</h5>
        <div class="sub2" style="font-size:0.8em"><b>Año Seleccionado: </b>{{$sql[0]->periodo}}</div>
         
        <input type="hidden" value=" {{$num=1}}">
        <br>
                  
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 10px; margin-top: 10px" >
        <thead>
          <tr>

              <th style="width: 30%">Corriente</th>
              <th style="width: 30%">No Corriente</th>
              
          </tr>
        </thead>
        <tbody>
          
          @foreach ($sql as $arc)
          <tr>
              <td style="text-align: center;font-size: 0.7em; padding: 0px;">{{number_format($arc->sum,2,".",",")}}</td>
              

          @endforeach
          @foreach ($sql1 as $arc1)
            @if($arc1->sum == '')
           
            @endif

              <td style="text-align: center;font-size: 0.7em; padding: 0px;">{{number_format($arc1->sum,2,".",",")}}</td>
              
          </tr>
          @endforeach
        </tbody>
      </table>
  </body>
</html>
