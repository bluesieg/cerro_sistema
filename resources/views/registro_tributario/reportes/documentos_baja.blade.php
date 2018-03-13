<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Documentacion Baja de Predios</title>
        <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
        <style>
            @page { margin-bottom: 10px !important; margin-left: 70px;margin-right: 70px;};
        </style>
  </head>
  <body>
    <main>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 0px;">
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
       
        <div class="subasunto" style="text-align: left; padding-left: 30px; margin-top: 20px;">CERRO COLORADO - {{$fecha_hora}}</div>

        <table style="margin-top: 10px; margin-bottom: 5px !important; border-bottom: 1px solid black">
            <tr>
                <td colspan="2" style="border:0px;">
                    <b>I . <span style=" text-decoration: underline">IDENTIFICACIÓN DEL CONTRIBUYENTE</span></b>
                </td>
            </tr>
            <tr>
                <td style="border:0px; padding-left:18px; width: 35%">
                    <b> NOMBRE DE CONTRIBUYENTE</b>
                </td>
                <td style="border:0px;">
                    : {{$sql[0]->contribuyente}}
                </td>
            </tr>
            <tr>
                <td style="border:0px; padding-left:18px;">
                    <b> N° DOCUMENTO</b>
                </td>
                <td style="border:0px; ">
                    : {{$sql[0]->nro_doc_contri}}
                </td>
            </tr>
            <tr>
                <td style="border:0px; padding-left:18px;">
                    <b> DOMICILIO FISCAL</b>
                </td>
                <td style="border:0px;">
                    : {{$sql[0]->ref_dom_fis}}
                </td>
            </tr>
            <tr>
                <td style="border:0px; padding-left:18px;">
                    <b> COMPRADOR</b>
                </td>
                <td style="border:0px;">
                    : {{$comprador}}
                </td>
            </tr>
        </table>
        </b>
        <div style="width: 100%; text-align: justify; font-size: 0.8em; margin-top: 0px; padding-left:18px;">
            Se deja constancia que se realizo la <b>BAJA</b> del predio por el siguiente Motivo:<b>{{$motivo}}</b> ,el dia <b>{{$fecha}}</b>.<br>
        </div>
        @if(isset($motivo1))
                @if($motivo1==2)
                <div style="width: 100%; text-align: justify; font-size: 0.8em; margin-top: 0px; padding-left:18px;"><b>Segun Decreto Supremo 156-2004-EF y modificatorias.</b></div>
                @endif
        @endif
        <table style="margin-top: 5px; margin-bottom: 5px !important;">
            <tr>
                <td colspan="2" style="border:0px;">
                    <b>II. <span style=" text-decoration: underline">INFORMACION DEL PREDIO DADO DE BAJA</span></b>
                </td>
            </tr>
         </table>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-top: 10px;margin-bottom: 5px;">
            <thead>
              <tr>
                  <th style="width: 10%">Cod. Catastral</th>
                  <th style="width: 60%">Ubicación del predio</th>
                  <th style="width: 5%">N°</th>
                  <th style="width: 15%">Referencia</th>
                  <th style="width: 5%">Manzana</th>
                  <th style="width: 5%">Lote</th>
              </tr>
            </thead>
            <tbody>
                    <tr>
                        <td>
                            {{$sql[0]->cod_cat}}
                        </td> 
                        <td>
                            {{$sql[0]->nom_via."-".$sql[0]->nomb_hab_urba}}
                        </td> 
                        <td style="text-align: center">
                            {{$sql[0]->nro_mun}}
                        </td> 
                        <td style="text-align: center">
                            {{$sql[0]->referencia}}
                        </td> 
                        <td style="text-align: center">
                            {{$sql[0]->mzna}}
                        </td> 
                        <td style="text-align: center">
                            {{$sql[0]->lote_cat}}
                        </td> 
                    </tr>
                
            </tbody>
        </table>
        
  </body>
  
</html>
