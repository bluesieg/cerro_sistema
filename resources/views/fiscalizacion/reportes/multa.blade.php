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
            <td style="width: 10%;border: 0px; text-align: center"></td>
            </tr>
            
        </table>
        
        <center><div Class="asunto" style="margin-top: 10px; "><b>RESOLUCION DE MULTA N° {{$sql->nro_multa}}-{{$sql->anio_reg}} SG-RTF-GAT-MDCC</b></div></center>
        <div class="subasunto" style="text-align: left; padding-left: 30px; margin-top: 20px;">Cerro Colorado, {{$sql->fec_reg}}</div>

        <table style="margin-top: 10px; margin-bottom: 5px !important font-weight: bold">
            <tr>
                <td style="border:0px; width: 17%">
                    NOMBRE/ RAZÓN SOCIAL
                </td>
                <td style="border:0px; width: 50%">
                    : {{$sql->pers_nro_doc}} {{$sql->contribuyente}}
                </td>
            </tr>
            <tr>
                <td style="border:0px; ">
                    COD.CONTRIBUYENTE
                </td>
                <td style="border:0px;">
                    : {{$sql->id_persona}}
                </td>
            </tr>
            <tr>
                <td style="border:0px">
                    DOMICILIO FISCAL
                </td>
                <td style="border:0px;">
                    : {{$sql->ref_dom_fis}}
                </td>
            </tr>
        </table>
        </b>
        <div style="width: 100%; text-align: justify; font-size: 0.9em; margin-top: 0px;">
        
        @if($sql->glosa_multa!=null)
        <b>INFRACCION:</b><br>
            {{$sql->glosa_multa}}
        @endif
        </div>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-top: 10px;margin-bottom: 5px;">
            <thead>
              <tr>
                  <th style="width: 10%">PERIODO</th>
                  <th style="width: 70%">MULTA</th>
                  <th style="width: 20%">MONTO MULTA</th>
                  
              </tr>
            </thead>
            <tbody>
                @foreach ($sql_detalle as $det)
                    <tr>
                        <td>
                            {{$det->anio}}
                        </td> 
                        <td>
                            {{$det->des_multa}}
                        </td> 
                        <td style="text-align: right;padding-right: 5px">
                            {{$det->cos_multa}}
                        </td> 
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2" style="text-align: right;padding-right: 5px">
                        TOTAL MULTAS
                    </td> 
                    <td style="text-align: right;padding-right: 5px">
                        {{$sql->total_multa}}
                    </td>
                </tr>
            </tbody>
        </table>
        
        <div style="width: 100%; text-align: justify; font-size: 0.9em; margin-top: 0px;">
            @php echo $sql->fundamentos @endphp
        </div>
        
  </body>
  
</html>
