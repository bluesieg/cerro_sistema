<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Example 2</title>
        <link href="{{ asset('css/pdf.css') }}" rel="stylesheet">
        <style>
            @page { margin-bottom: 10px !important; margin-left: 70px;margin-right: 70px;};
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
            <td style="width: 10%;border: 0px; padding: 0px">
                <div class="cabdiv text-center" style="width: 100%;font-size: 0.9em !important" ><center>COD OPERACIÓN</center></div>
                <div class="cuerdiv text-center" style="width: 100%;font-size: 0.9em !important"><center>{{$sql->id_alcab}}</center></div>
            </td>
                
            </tr>
            
        </table>
        
        <center><div Class="asunto" style="margin-bottom: 10px;"><b>IMPUESTO DE ALCABALA N° {{$sql->nro_alcab}}-{{$sql->anio}}</b></div></center>

        <div class="lado3" >
            I. DATOS DEL CONTRIBUYENTE    
        </div>
        <table border="0" cellspacing="0" cellpadding="0">
            <thead>
              <tr>
                  <td class="nro">1</td>
                  <th style="width: 10%">Tip.doc.</th>
                  <td class="nro">2</td>
                <th style="width: 22%">NÚMERO DE DOCUMENTO</th>
                <td class="nro" >3</td>
                <th style="width: 60%">APELLIDOS y NOMBRES / RAZÓN SOCIAL</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                  <td colspan="2" style="text-align: center">{{$sql->tip_doc_adqui}}</td>
                  <td colspan="2" style="text-align: center">{{ $sql->doc_adqui }}</td>
                  <td colspan="2" style="text-align: center">{{ $sql->nom_adqui }}</td>
              </tr>
              <tr>
                  <td class="nro">4</td>
                  <th style="width: 10%" colspan="5">Domicilio Fiscal</th>
              </tr>
              <tr>
                  <td colspan="6" style="text-align: center">{{$sql->dom_fiscal_adqui}}</td>
              </tr>
            </tbody>

          </table>
        <div class="lado3" >
            II. DATOS DEL VENDEDOR   
        </div>
        <table border="0" cellspacing="0" cellpadding="0">
            <thead>
              <tr>
                  <td class="nro">5</td>
                  <th style="width: 10%">Tip.doc.</th>
                  <td class="nro">6</td>
                <th style="width: 22%">NÚMERO DE DOCUMENTO</th>
                <td class="nro" >7</td>
                <th style="width: 60%">APELLIDOS y NOMBRES / RAZÓN SOCIAL</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                  <td colspan="2" style="text-align: center">{{$sql->tip_doc_trans}}</td>
                  <td colspan="2" style="text-align: center">{{ $sql->doc_trans }}</td>
                  <td colspan="2" style="text-align: center">{{ $sql->nom_trans }}</td>
              </tr>
              <tr>
                  <td class="nro">8</td>
                  <th style="width: 10%" colspan="5">Domicilio Fiscal</th>
              </tr>
              <tr>
                  <td colspan="6" style="text-align: center">{{$sql->dom_fiscal_trans}}</td>
              </tr>
            </tbody>

          </table>
        <div class="lado3" >
            III. UBICACIÓN DEL PREDIOS   
        </div>
        <table border="0" cellspacing="0" cellpadding="0">
            <thead>
              <tr>
                  <td class="nro">9</td>
                  <th style="width: 60%" >Sector-Manzana-Lote:Habilitación  Vía - Número - Interior -Letra/Manzana - Lote - Bloque</th>
                  <td class="nro">10</td>
                  <th style="width: 40%" >DISTRITO</th>
              
              </tr>
            </thead>
            <tbody>
              <tr>
                  <td colspan="2" style="text-align: center">{{$dir}}, {{$sql->referencia}}</td>
                  <td colspan="2" style="text-align: center">CERRO COLORADO</td>
              </tr>
            </tbody>

          </table>
        
        <div class="lado3" >
            IV. DATOS RELATIVOS AL DOCUMENTO DE TRANSFERENCIAS   
        </div>
        <table border="0" cellspacing="0" cellpadding="0">
            <thead>
              <tr>
                  <td class="nro">11</td>
                  <th style="width: 30%">Tipo Documento Trans.</th>
                  <td style="width: 30%" >{{$sql->descrip_doc_transf}}</td>
                  <td class="nro">12</td>
                  <th style="width: 15%">Porcentaje Adquirido</th>
                  <td class="nro">13</td>
                  <th style="width: 15%">Fecha Transferencia</th>
              </tr>
              <tr>
                  <td class="nro">14</td>
                  <th >Naturaleza del Contrato</th>
                  <td>{{$sql->descrip_cto}}</td>
                  <td colspan="2" style="text-align: center">{{$sql->porcen_adqui}} %</td>
                  <td colspan="2" style="text-align: center;">{{$sql->fec_doc_tranf}}</td>
              </tr>
              <tr>
                  <td class="nro">15</td>
                  <th >Notaria</th>
                  <td  colspan="5" >{{$sql->nom_notaria}}</td>
                  
              </tr>
            </thead>
          </table>
        
        <div class="lado3" >
            V. LIQUIDACION DEL IMPUESTO   
        </div>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 5px;">
            <thead>
              <tr>
                  <td class="nro">16</td>
                  <th style="width: 30%">MOTIVO INAFECTACIÓN</th>
                  <td style="width: 70%" >{{$sql->descrip_trans_inaf}}</td>
              </tr>
            </thead>
        </table>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 5px;">
            <thead>
              <tr>
                  <td class="nro">17</td>
                  <th style="width: 40%">AREA DEL TERRENO</th>
                  <td class="nro">18</td>
                  <th style="width: 40%">AREA CONSTRUIDA</th>
                  
              </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" style="text-align: right; padding-right: 10px;">{{$sql->are_terr}}</td> 
                    <td colspan="2" style="text-align: right; padding-right: 10px;">{{$sql->area_const}} M2</td> 
                </tr>
            </tbody>
        </table>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 5px;">
            <thead>
              <tr>
                  <td class="nro">19</td>
                  <th style="width: 30%">VALOR DEL AUTOVALUO DEL PREDIO</th>
                  <td class="nro">20</td>
                  <th style="width: 30%">PORCENTAJE ADQUIRIDO</th>
                  <td class="nro">21</td>
                  <th style="width: 30%">VALOR DE LO ADQUIRIDO POR EL CONTRIBUYENTE</th>
              </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" style="text-align: right; padding-right: 10px;">{{number_format($sql->base_impon_autoavaluo,2)}}</td> 
                    <td colspan="2" style="text-align: right; padding-right: 10px;">{{$sql->porcen_adqui}} %</td> 
                    <td colspan="2" style="text-align: right; padding-right: 10px;">{{number_format($sql->base_impon_autoavaluo*$sql->porcen_adqui/100,2)}}</td> 
                </tr>
            </tbody>
        </table>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 5px;">
            <thead>
              <tr>
                  <td class="nro">22</td>
                  <th style="width: 30%">PRECIO DE TRANFERENCIA</th>
                  <td class="nro">23</td>
                  <th style="width: 15%">MONEDA</th>
                  <td class="nro">24</td>
                  <th style="width: 15%">CAMBIO APLICADO</th>
                  <td class="nro">25</td>
                  <th style="width: 30%">VALOR DE TRANSFERENCIA EN SOLES</th>
              </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" style="text-align: right; padding-right: 10px;">{{number_format($sql->valor_transferencia,2)}}</td> 
                    <td colspan="2" style="text-align: center">{{$moneda}}</td> 
                    <td colspan="2" style="text-align: right; padding-right: 10px;">{{number_format($sql->tip_camb,2)}}</td> 
                    <td colspan="2" style="text-align: right; padding-right: 10px;">{{number_format($sql->valor_transferencia*$sql->tip_camb,2)}}</td> 
                </tr>
            </tbody>
        </table>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 5px;">
            <thead>
              <tr>
                  <td class="nro">26</td>
                  <th style="width: 15%">Base Imponible<br>se consigna el mas alto<br>de (1) y (2)</th>
                  <td class="nro">27</td>
                  <th style="width: 10%">UIT</th>
                  <td class="nro">28</td>
                  <th style="width: 15%">N° UIT DEDUCCION</th>
                  <td class="nro">29</td>
                  <th style="width: 15%">IMPORTE DEDUCIBLE</th>
                  <td class="nro">30</td>
                  <th style="width: 15%">BASE IMPONIBLE AFECTA</th>
                  <td class="nro">31</td>
                  <th style="width: 15%">TASA DEL IMPUESTO</th>
                  <td class="nro">32</td>
                  <th style="width: 15%">IMPUESTO A PAGAR</th>
              </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" style="text-align: right; padding-right: 10px;">{{number_format($afecto,2)}}</td> 
                    <td colspan="2" style="text-align: right; padding-right: 10px;">{{number_format($sql->uit,2)}}</td> 
                    <td colspan="2" style="text-align: right; padding-right: 10px;">{{$sql->nro_uit}}</td> 
                    <td colspan="2" style="text-align: right; padding-right: 10px;">{{number_format($sql->uit*$sql->nro_uit,2)}}</td> 
                    <td colspan="2" style="text-align: right; padding-right: 10px;">{{number_format($sql->base_impon_afecta,2)}}</td> 
                    <td colspan="2" style="text-align: right; padding-right: 10px;">{{$sql->por_tas}} %</td> 
                    <td colspan="2" style="text-align: right; padding-right: 10px;">{{number_format($sql->impuesto_tot,2)}}</td> 
                </tr>
            </tbody>
        </table>
        
        
        <div class="lado3" >
            VI. FIRMA Y SELLO  
        </div>
        <table border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <td colspan="2" style="height: 80px"></td>
                    <td colspan="2"></td>
                    <td colspan="2"></td>
                </tr>
              <tr>
                  <td class="nro">33</td>
                  <th style="width: 40%">Firma del Contribuyente o Representante Legal</th>
                  <td class="nro">34</td>
                  <th style="width: 20%">Huella Digital</th>
                  <td class="nro">35</td>
                  <th style="width: 40%">Sello Y Firma de ADM. Tributaria</th>
              </tr>
            </thead>
          </table>
        <div style="width: 100%; text-align: justify; font-size:0.6em">
            <b>LA PRESENTE TIENE CARACTER DE DECLARACIÓN JURADA</b><br>
            <b>NOTA IMPORTANTE:</b> El impuesto de Alcabala es de realización inmediata y grava las transferencias de propiedad de bienes 
            inmuebles urbanos o rústicos a título oneroso o gratuito, cualquiera sea su forma o modalidad, inclusive las ventas
            con reserva de dominio(Art. 21 del TUO de la ley de Tributación Municipal aprobado por D.S. 156-2004 EF y sus modificatorias).<br>
            Concordancia con el art. 1373 del Código Civil: "El contrato de compra venta perfeccionado en el momento y lugar en
            que la aceptación es conocida por el oferente*<br>
            Jurisprudencia:<br>
            "Desde el momento en que la aceptación recoge la declaración contenida en la oferta, haciendola suya,
            y es conocida por el oferente, el contrato queda concluida, produciendose sus efectos".
        </div>
  </body>
  
</html>
