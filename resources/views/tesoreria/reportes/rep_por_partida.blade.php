<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ingresos por Partida</title>
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
        
        <center><div Class="asunto" style="margin-top: 0px;"><b>Reporte Por Partida Presupuestal
                </b></div></center>
        <br>
        
        <center><div class="sub2">Desde {{$fechainicio}} Hasta {{$fechafin}}</div>
                  </div></center>
         
        <input type="hidden" value=" {{$num=1}}">
        
       
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 10px; margin-top: 10px" >
        <thead>
          <tr>
              <th style="width: 2%">N°</th>
              <th style="width: 10%">Cod. Presupuestal</th>
              <th style="width: 40%">Especifica Detalle</th>
              <th style="width: 10%">TOTAL s/.</th>
              
          </tr>
        </thead>
        <tbody>
          
          @foreach ($sql as $arc)
          <tr>
              <td style="text-align: center;font-size: 0.7em; padding: 0px;">{{$num++}}</td>
              <td style="text-align: left;font-size: 0.7em; padding: 0px;">{{$arc->codigo}}</td>
              <td style="text-align: left;font-size: 0.7em; padding-left: 5px;">{{$arc->desc_espec_detalle}}</td>
              <td style="text-align: center;font-size: 0.7em; padding-left: 5px;">{{$arc->total}}</td>
              
          </tr>
          @endforeach
        </tbody>
      </table>
  </body>
</html>
