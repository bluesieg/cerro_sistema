<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ingresos por Tributo</title>
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
        
        <center><div Class="asunto" style="margin-top: 0px;"><b>Reporte Por Tributo
                </b></div></center>
        <br>
        
        <center><div class="sub2">Desde {{$fechainicio}} Hasta {{$fechafin}}</div>
                  </div></center>
         
        <input type="hidden" value=" {{$num=1}}">
        <br>
         <div class="sub2" style="font-size:0.8em"><b>CÓDIGO DE TRIBUTO: </b>{{$sql[0]->cod_tributo}}</div>
         <div class="sub2" style="font-size:0.8em"><b>DESCRIPCIÓN: </b></div>
         <div class="sub2" style="font-size:0.8em"> {{$sql[0]->descrip_tributo}}</div>
         
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 10px; margin-top: 10px" >
        <thead>
          <tr>
              <th style="width: 5%">N°</th>
              <th style="width: 30%">Fecha</th>
              <th style="width: 30%">Monto s/.</th>
              
          </tr>
        </thead>
        <tbody>
          
          @foreach ($sql as $arc)
          <tr>
              <td style="text-align: center;font-size: 0.7em; padding: 0px;">{{$num++}}</td>
              <td style="text-align: center;font-size: 0.7em; padding: 0px;">{{$arc->fecha}}</td>
              <td style="text-align: right;font-size: 0.7em; padding-right: 10px;">{{ number_format($arc->total,2,".",",")}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
  </body>
</html>
