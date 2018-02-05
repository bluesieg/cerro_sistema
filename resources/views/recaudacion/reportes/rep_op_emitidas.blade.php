<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Example 2</title>
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
        
        <center><div Class="asunto" style="margin-top: 0px;"><b>ORDENES DE PAGO EMITIDAS {{$sql[0]->anio}}</b></div></center>

        <table border="0" cellspacing="0" cellpadding="0" style="width: 50%; margin-top: 20px" >
        <thead>
          <tr>
              <th style="width: 40%">Tipo</th>
              <th style="width: 20%">Año</th>
              <th style="width: 40%">Cantidad de OP Emitidasr</th>
          </tr>
        </thead>
        <tbody>
          <tr>
              <td style="text-align: center;font-size: 0.8em; padding: 0px;">Orden de Pago</td>
              <td style="text-align: center;font-size: 0.8em; padding: 0px;">{{$sql[0]->anio}}</td>
              <td style="text-align: center;font-size: 0.8em; padding: 0px;">{{count($sql)}}</td>
          </tr>
        </tbody>
      </table>
        <input type="hidden" value=" {{$num=1}}">
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 10px; margin-top: 10px" >
        <thead>
          <tr>
              <th style="width: 4%">N°</th>
              <th style="width: 7%">Nro. OP</th>
              <th style="width: 10%">Nro. Doc</th>
              <th style="width: 20%">Contribuyente</th>
              <th style="width: 10%">Tipo</th>
              <th style="width: 30%">Domicilio Fiscal</th>
              <th style="width: 10%">Monto</th>
              <th style="width: 9%">Fecha Notificación</th>
          </tr>
        </thead>
        <tbody>
          
          @foreach ($sql as $op)
          <tr>
              <td style="text-align: center;font-size: 0.7em; padding: 0px;">{{$num++}}</td>
              <td style="text-align: center;font-size: 0.7em; padding: 0px;">{{$op->nro_fis}}</td>
              <td style="text-align: center;font-size: 0.7em; padding: 0px;">{{$op->nro_doc}}</td>
              <td style="text-align: left;font-size: 0.7em; padding-left: 5px;">{{$op->contribuyente}}</td>
              <td style="text-align: center;font-size: 0.7em; padding: 0px;">{{$op->tipo_persona}}</td>
              <td style="text-align: left;font-size: 0.7em; padding-left: 5px;">{{$op->dom_fiscal}}</td>
              <td style="text-align: right;font-size: 0.7em; padding-right: 5px;">{{$op->monto}}</td>
              <td style="text-align: center;font-size: 0.7em; padding: 0px;">{{$op->fec_notifica}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
  </body>
</html>
