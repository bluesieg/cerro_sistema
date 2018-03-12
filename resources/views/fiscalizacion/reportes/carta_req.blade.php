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
            <td style="width: 10%;border: 0px; text-align: center"><b>Año a Fiscalizar<br>{{$sql->anio_fis}}</b></td>
            </tr>
            
        </table>
        
        <center><div Class="asunto" style="margin-top: 10px;"><b>CARTA DE PRESENTACION Y REQUERIMIENTO DE FISCALIZACION N° {{$sql->nro_car}}-{{$sql->anio}}<br>SG-RTF-GAT-MDCC</b></div></center>
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
            Previo saludo, vecino cotribuyente, de conformidad con el Capítulo II Art. 60° 61° y 62° del Texto Único Ordenado del Código
            Tributario aprobado por el D.S. 133-2013-EF y en concordancia con la Ley N° 27972 y el D.S. N° 156-2004-EF, TUO de la Ley de Tributación
            Municipal, se le comunica que el día {{$sql->fec_fis}} a Horas {{$sql->hora_fis}}, se constituirá un
            equipo de fiscalizadores integrados por: 
            @foreach ($fiscalizadores as $fis)
                    {{$fis->fiscalizador}} / DNI: {{$fis->pers_nro_doc}}, 
            @endforeach
            respectivamente, para verificar el cabal cumplimiento de sus obligaciones tributarias relacionadas con el
            Impuesto Predial y otros tributos que administra y recauda esta Municipalidad, por lo que le exhortamos se sirva brindar las facilidades
            necesarias según lo establecido en el Art. 87° del D.S. 135-99-EF, para la inspección del/los siguiente/s predio/s.
        </div>
        
        <table border="0" cellspacing="0" cellpadding="0" style="margin-top: 10px;margin-bottom: 5px;">
            <thead>
              <tr>
                  <th style="width: 10%">Cod. Catastral</th>
                  <th style="width: 70%">Ubicación del predio</th>
                  <th style="width: 4%">N°</th>
                  <th style="width: 5%">Zona</th>
                  <th style="width: 6%">Manzana</th>
                  <th style="width: 5%">Lote</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($predios as $pre)
                    <tr>
                        <td>
                            {{$pre->cod_cat}}
                        </td> 
                        <td>
                            {{$pre->nom_via."-".$pre->habilitacion}}
                        </td> 
                        <td style="text-align: center">
                            {{$pre->nro_mun}}
                        </td> 
                        <td style="text-align: center">
                            {{$pre->zona}}
                        </td> 
                        <td style="text-align: center">
                            {{$pre->mzna_dist}}
                        </td> 
                        <td style="text-align: center">
                            {{$pre->lote_dist}}
                        </td> 
                    </tr>
                @endforeach
                
            </tbody>
        </table>
        <div style="width: 100%; text-align: justify; font-size: 0.9em; margin-top: 0px;">
            Para los fines del proceso de <b>Fiscalización Tributaria</b> se requiere al momento de la inspección presente la siguiente Informacion:<br>
            <table style="margin-top: 5px; margin-bottom: 5px;">
                <tr>
                    <td style="width: 50%; font-size: 1.0em; border:0px;">
                        @if ($sql->soli_contra == "1")
                        <span style="padding-left: 15px;">-Contrato de Compra Venta y Título de Propiedad</span><br>
                        @endif
                        @if ($sql->soli_licen == "1")
                        <span style="padding-left: 15px;">-Licencia de Construcción</span><br>
                        @endif
                    </td>
                    <td style="width: 50%; font-size: 1.0em; border:0px;">
                        @if ($sql->soli_dercl == "1")
                        <span style="padding-left: 15px;">-Última Declaración Jurada</span><br>
                        @endif
                        @if ($sql->soli_otro == "1")
                        <span style="padding-left: 15px;">-{{$sql->otro_text}}</span><br>
                        @endif
                    </td>
                </tr>
            </table>
            
            
            Los documentos requeridos y <b>No</b> presentados o exhibidos en la etapa de fiscalización no serán admitidos como medios probatorios en
            un eventual reclamo por parte del contribuyente de conformidad con el Art. 141° del Código Tributario. Este pedido se realizara en uso
            de las facultades discrecionales de fiscalización que posee la Administración Tributaria y que se encuentran reguladas en el Art. 62° del 
            TUO del Código Tributario aprobado por el D.S. 133-2013-EF y modificatorias. Asimismo le informamos que en caso que usted no pueda
            estar presente, podrá nombrar a un representante que acompañe y verifique el proceso de inspección.<br>
            También la Administración Tributaria de la Municipalidad Distrital de Cerro Colorado, se encuentra facultada a exigir al contribuyente
            citado, la exhibición y/o presentación de informes y análisis relacionados con hechos susceptibles de generar obligaciones tributarias
            en la forma y condiciones requeridas.<br>
            <br>
            Atentamente.-
            
        </div>
        <div style="page-break-before:always;"></div>
        <div style="width: 100%; text-align: justify; font-size: 1.0em; margin-top:10px; padding-top: 10px;border-top: 2px solid black;">
            <center><span><b>ACTA DE NOTIFICACION</b></span></center>
                    <br>
                    En Cerro Colorado, siendo las _ _ _ _ _ horas del día_ _ _ _ _ _ _ _del mes de_ _ _ _ _ _ _ _ _del año _ _ _ _, &nbsp;&nbsp;constituí
            en _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _, domicilio fiscal del obligado, requeriendo su presencia y respondió
            un ciudadano quien dijo llamarse:_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _identificado con DNI N°_ _ _ _ _ _ _ _ _
            quien tiene vinculación de parentesco( ) afinidad ( ) de: _ _ _ _ _ _ _ _ _ con el titular de la obligación tributaria: {{$sql->contribuyente}}
            a quien procedí a entregarle la copia de la presente Carta de Presentación y Requerimiento de Fiscalización N° {{$sql->nro_car}}-{{$sql->anio}} SG-RTF-GAT-MDCC
            y enterado del contenido:_ _ _ firmó. 
        </div>
        <div style="width: 100%; text-align: right; font-size: 1.1em; margin-top:40px;">
            ...................................................<br>
            <span style="padding-right: 70px;">FIRMA</span>
        </div>
        <table class="tablepegada">
            <tr>
                <td colspan="2" style=" border: 0px;">DATOS DEL NOTIFICADOR</td>
                <td style="border: 0px;">REFERENCIA DEL PREDIO</td>
            </tr>
            <tr>
                <td style="width: 8%;border: 0px;">Nombre:</td>
                <td style="width: 42%;border: 0px;">..............................................................................</td>
                <td style="width: 50%;border: 0px;">Color de Pared: ...........................................................................................</td>
            </tr>
            <tr>
                <td style="border: 0px;">DNI:</td>
                <td style="border: 0px;">..............................................................................</td>
                <td style="border: 0px;">Puerta: .........................................................................................................</td>
            </tr>
            <tr>
                <td style="border: 0px;">Firma:</td>
                <td style="border: 0px;">..............................................................................</td>
                <td style="border: 0px;">N° Pisos: .....................................................................................................</td>
            </tr>
            <tr>
                <td style="border: 0px;"></td>
                <td style="border: 0px;"></td>
                <td style="border: 0px;">N°suministro de Luz y Agua: .....................................................................</td>
            </tr>
            <tr>
                <td style="border: 0px;"></td>
                <td style="border: 0px;"></td>
                <td style="border: 0px;">Lectura del día del acta de notificacion: .....................................................</td>
            </tr>
        </table>
  </body>
  
</html>
