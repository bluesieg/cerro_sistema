<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Acta de Apersonamiento</title>
        <style>        
            @font-face {
                font-family: SourceSansPro;
                src: url(SourceSansPro-Regular.ttf);
            }
            @page {                
                margin: 170px 75px 5px 75px;
            }
            #header { position: fixed; left: 0px; top: -180px; right: 0px; height: 100px; text-align: center; }
/*            #footer { position: fixed; left: 0px; bottom: -180px; right: 0px; height: 100px; background-color: lightblue; }
            #footer .page:after { content: counter(page, upper-roman); }*/
            .t1, .t2 { border-collapse: collapse; }
            .t1 > tbody > tr > td { border: 1px solid #D5D5D5; font-size: 13px}
            .t1 > thead > tr > th { border:1px solid #D5D5D5; background: #01A858;color: white; }
        </style>

    </head>    
    <body>
        <div id="header" style="padding-top:20px">            
            <img src="img/escudo.png" style="position:absolute;margin-top: 25px;margin-left: -10px; width: 55px;height: 65px;" >
            <center>
                <h3 style="color:#018F4B;margin-bottom:0px;font-size: 20px;">MUNICIPALIDAD DISTRITAL DE CERRO COLORADO</h3>
                <p style="margin-top:7px;font-size: 12px;"><b>Dirección:</b> Mariano Melgar N° 500, Urb. La Libertad, Cerro Colorado - Arequipa</p>
                <H3 style="margin-top:0px;font-size: 13px;"><b>OFICINA DE EJECUCIÓN COACTIVA / TELF: 54-382590 ANEXO: 733</b> </H3>                
                <div style="background:#01A858; margin-top: 0px;height: 1px"></div>           
            </center> 
        </div>
        
        <div style="text-align:center;margin-top: -40px;"><b>ACTA DE APERSONAMIENTO</b></div>
        
        <div style="text-align: justify;font-size:14px;margin-top: -5px;">           
            <center><u><b>EXPEDIENTE COACTIVO: </b>{{ $resol->nro_exped.'-'.$resol->anio_resol }}<b> / OEC-MDCC</b></u></center>
            <p>
                Que, con fecha {{$resol->fch_larga}}, siendo las {{date('H:i A')}} horas, se apersono a la Oficina de Ejecución Coactiva, el 
                <b>Sr. {{$resol->contribuyente}} con DNI: {{$resol->nro_doc}},</b> en calidad de representante legal de la empresa 
                <b>CURTIEMBRE GLOBAL SAC,</b> respecto de la deuda establecida en la {{$resol->doc_ini}} N° {{$resol->nro_rd}}-{{$resol->anio_rd}}-GAT-MDCC/IP, 
                por concepto de Impuesto Predial, y exigida con la Resolución de Ejecución Coactiva N° {{$resol->nro_resol.'-'.$resol->anio_resol}}, de la Oficina de Ejecución 
                Coactiva, teniendo en consideración los problemas económicos que viene atravesando la empresa, va a pagar {{count($cuotas)}} partes de 
                S/. {{$resol->monto}} ({{$resol->monto_letra}}). <b>Comprometiéndose a pagar de acuerdo al siguiente detalle:</b>
                <ul>                   
                    @foreach ($cuotas as $cuotas)
                        <li>{{$cuotas['nro']}}.- El {{$cuotas['fch_larga']}}</li>                       
                    @endforeach
                </ul>
            </p>
        </div>
        <div style="margin-top: 100px;width: 50%;">
            <div style="margin-top: 150px;width: 100%;border-top: 1px solid black;font-size: 13px">
                <center>{{$resol->contribuyente}}<br>DNI:&nbsp;{{$resol->nro_doc}}</center>
            </div>
        </div>
    </body>
</html>
