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
            <td style="width: 10%;border: 0px; text-align: center"><b></b></td>
            </tr>
            
        </table>
        
        <center><div Class="asunto" style="margin-top: 10px; font-size: 0.9em"><b>CARTA DE PRESENTACION Y REQUERIMIENTO DE FISCALIZACION N° {{$sql->nro_car}}-{{$sql->anio}}-SGFT-GAT-MDCC</b></div></center>
        <div class="subasunto" style="text-align: right;  margin-top: 10px;">Cerro Colorado, {{$sql->fec_reg}}</div>

        <table CELLPADDING ='0' CELLSPACING ='0' style="margin-top: 10px; margin-bottom: 5px !important font-weight: bold">
            <tr>
                <td style="border:0px; width: 22%">
                    NOMBRE/ RAZÓN SOCIAL
                </td>
                <td style="border:0px; width: 78%">
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
            <tr>
                <td style="border:0px">
                    PERIODO FISCALIZADO
               </td>
                <td style="border:0px;">
                    : {{$sql->anio_fis}} - {{date("Y")}}
                </td>
            </tr>
        </table>
        </b>
        <div style="width: 100%; text-align: justify; font-size: 0.9em; margin-top: 0px;">
            <b>Presente. -</b><br>
            Es grato dirigirme a Usted, con el objeto de saludarlo cordialmente a nombre de la Sub Gerencia de Fiscalización
            Tributaria de la Municipalidad Distrital de Cerro Colorado y poner de su conocimiento que:<br>
            <br>
            Los artículos 61°, 62° y 62°-A del TUO del Código Tributario a esta Administración , confiere a esta administración 
            las facultades de fiscalización y determinación; pudiendo solicitar la comparecencia de los deudores tributarios o 
            terceros para que proporcionen la información que se estime necesaria, otorgando un plazo no menor de cinco (5) días 
            hábiles, más el término de la distancia de ser el caso. <br>
            <br>
            @if(count($adjuntas)>0)
            En ejercicio de dichas facultades, es que a través de la Carta de Presentación y Requerimiento de Fiscalización 
            @foreach ($adjuntas as $adj)
                    {{'N°'.$adj->nro_car_adj.'-'.$adj->anio_adj.'-SGFT-GAT-MDCC Notificada '.$adj->fecha_notificacion_adj}},  
            @endforeach
            es que se inicia el proceso de fiscalización para verificar el correcto cumplimiento de las obligaciones 
            formales y materiales con respecto del impuesto predial. 
            @else
                En ejercicio de dichas facultades, es que a través de la Presente Carta de Presentación y Requerimiento de Fiscalización 
                se inicia el proceso de fiscalización para verificar el correcto cumplimiento de las obligaciones 
                formales y materiales con respecto del impuesto predial.   
            @endif
            <br>
            @php echo $sql->texto_1 @endphp
            A tenor de lo anterior, se requiere conforme a las atribuciones de esta área, dar las facilidades del caso
            a el/los agentes fiscalizadores: 
            @foreach ($fiscalizadores as $fis)
                    {{$fis->fiscalizador}} / DNI: {{$fis->pers_nro_doc}}, 
            @endforeach
            para que realicen el proceso de fiscalización en fecha {{$sql->fec_fis}} a Horas {{$sql->hora_fis}} del/los siguiente/s predio/s:
        </div>
        <table border="0" cellspacing="0" cellpadding="0" style="margin-top: 10px;margin-bottom: 5px;">
            <thead>
              <tr>
                  <th style="width: 80%">Ubicación del predio</th>
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
                            {{$pre->nom_via."-".$pre->habilitacion." ".trim($pre->referencia)}}
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

            En caso que Ud., 
            no pueda estar presente podrá nombrar un representante legal mediante Carta Poder simple, quien podrá acompañar y verificar el 
            respectivo proceso de inspección.
            <br>
            <br>
            
            De llevarse a cabo la verificación a su predio se ejecutará las labores que a continuación se detallan:<br>
            <table style="margin-top: 5px; margin-bottom: 5px;">
                <tr>
                    <td style="width: 50%; font-size: 1.0em; border:0px;">
                        <span style="padding-left: 15px;">-Toma de medidas de los perímetros del terreno, construcciones y obras complementarias.</span><br>
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%; font-size: 1.0em; border:0px;">
                        <span style="padding-left: 15px;">-Verificación de las categorías respecto de la construcción de los predios.</span><br>
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%; font-size: 1.0em; border:0px;">
                        <span style="padding-left: 15px;">-Toma de fotografías a las características de los predios.</span><br>
                    </td>
                </tr>
            </table>
        </div>
        <div style="width: 100%; text-align: justify; font-size: 0.9em; margin-top: 0px;">
            Para los fines del proceso de <b>Fiscalización Tributaria</b> se requiere al momento de la inspección presente la siguiente Informacion:<br>
            <table style="margin-top: 5px;">
                @if ($sql->soli_contra == "1")
                <tr>
                    <td style="width: 50%; font-size: 1.0em; border:0px;">
                        
                        <span style="padding-left: 15px;">-Contrato de Compra Venta y Título de Propiedad</span><br>
                        
                    </td>
                </tr>
                @endif
                @if ($sql->soli_licen == "1")
                <tr>
                    <td style="width: 50%; font-size: 1.0em; border:0px;">
                        <span style="padding-left: 15px;">-Licencia de Construcción</span><br>
                        
                    </td>
                </tr>
                @endif
                @if ($sql->soli_dercl == "1")
                <tr>
                    <td style="width: 50%; font-size: 1.0em; border:0px;">
                        <span style="padding-left: 15px;">-Última Declaración Jurada</span><br>
                    </td>
                </tr>
                @endif
                @if ($sql->soli_otro == "1")
                <tr>
                    <td style="width: 50%; font-size: 1.0em; border:0px;">
                        
                        <span style="padding-left: 15px;">-{{$sql->otro_text}}</span><br>
                        
                    </td>
                </tr>
                @endif
            </table>
            
            Agradeciendo anticipadamente por la atención que dispense al presente de ser necesario podrá comunicarse al teléfono 054-382890 anexo 762 o ubicarnos en las oficinas cito en la calle Francisco Bolognesi Nº 227 con Calle Mariano Melgar 3° Piso, Plaza las Américas Cerro Colorado.
            <br>
            Sin otro particular quedo de usted.
            <br>
            <br>
            Atentamente. -

            
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
