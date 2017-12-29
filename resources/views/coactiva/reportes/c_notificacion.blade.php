<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Notificación</title>
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
        
        <div style="text-align:center;margin-top: -40px;"><b>CONSTANCIA DE NOTIFICACIÓN</b></div>
        
        <div style="text-align: justify;font-size:14px;margin-top: -5px; border: 1px solid black;padding: 5px">           
            <center><u><b>EXPEDIENTE COACTIVO: </b>{{ $documento[0]->nro_exped.'-'.$documento[0]->anio_resol }}<b> / OEC-MDCC</b></u></center>
            <ul> 
            <li><b>EJECUTOR COACTIVO: </b>ABOG. EDSON ALAN BERNAL DÍAZ</li> 
            <li><b>AUXILIAR COACTIVO: </b>SR. OSCAR RUBÉN MOGROVEJO PORTUGAL</li> 
            </ul>
            <table style="font-size:14px;">
                <tr>
                    <td style="width: 120px"><b>Obligado</b></td>
                    <td>:&nbsp;{{$documento[0]->contribuyente}}<br/></td>
                </tr>
                <tr>
                    <td style="width: 120px"><b>Dirección</b></td>
                    <td>:&nbsp;{{$documento[0]->dom_fis}}<br/></td>
                </tr>
                <tr>
                    <td style="width: 120px"><b>Domicilio Fiscal</b></td>
                    <td>:&nbsp;..........................................................................................................................................<br/></td>
                </tr>
                <tr>
                    <td style="width: 120px"><b>Domicilio Procesal</b></td>
                    <td>:&nbsp;..........................................................................................................................................<br/></td>
                </tr>
                <tr>
                    <td style="width: 120px"><b>Obligacion</b></td>
                    <td>:&nbsp;TRIBUTARIA(&nbsp;&nbsp;&nbsp;)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NO TRIBUTARIA(&nbsp;&nbsp;&nbsp;)<br/></td>
                </tr>
                <tr>
                    <td style="width: 120px"><b>Materia</b></td>
                    <td>:&nbsp;IMPUESTO PREDIAL<br/></td>
                </tr>
                <tr>
                    <td colspan="2">Lo Notifico a Usted con arreglo a Ley.<br>
                        {{$documento[0]->texto}}, de fecha ....../....../20..... CON RELACIÓN AL ESCRITO:................................................... de fecha, ....../....../20..... CON UN TOTAL DE:(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) FOLIO(S).
                    </td>                    
                </tr>
            </table>
        </div>
        <div style="text-align: justify;font-size:14px;margin-top: 0px;">
            La Municipalidad Distrital de Cerro Colorado, por intermedio de la Oficina de Ejecución Coactiva a los ......... días del mes de .......................... del año {{date('Y')}}, a horas ............., me constituí en el domicilio del obligado requiriendo su presencia y respondió una persona que dijo llamarse ......................................................... ......................................................................................................... que se identificó con el documento
            : N° ......................................., a quien procedí a entregarle original de la presente Resolución y enterado del contenido de la misma procedió a:
            <ul> 
                <li><b>Si: (&nbsp;&nbsp;&nbsp;&nbsp;)</b>firmo</li> 
                <li><b>No: (&nbsp;&nbsp;&nbsp;&nbsp;)</b>firmo</li> 
            </ul>
            (*) Al negarse a identificarse y firmar la persona que recepcionó la presente, se procedió conforme a lo establecido en el numeral 21.3, del articulo 21°, de la Ley 27444; siendo las características del domicilio: ........................................................................................................................................................................................ ........................................................................................................................................................................................
            
        </div>
        <p style="margin-top:0px;font-size: 14px;font-weight: bold">OBSERVACIONES:</p>
        <ul> 
            <li>Se dejó aviso (&nbsp;&nbsp;&nbsp;)</li> 
            <li>Se dejó la notificación por debajo de la puerta (&nbsp;&nbsp;&nbsp;)</li>
            <li>N° del suministro de luz (&nbsp;&nbsp;&nbsp;) agua (&nbsp;&nbsp;&nbsp;) N° ...................................</li>
            <li>No se encontró dirección (&nbsp;&nbsp;&nbsp;)</li>
            <li>Se trasladaron a: ...........................................................................</li>
        </ul>
        <p style="margin-top:0px;font-size: 14px;font-weight: bold"><U>PREAVISO DE NOTIFICACIÓN</U></p>
        <p style="margin-top:5px;font-size: 14px;">No habiendo persona alguna con quien entenderse la notificación, se deja el preaviso, señalandose como fecha próxima, el día ............... del mes de ............................. del año {{date('Y')}}.</p>
        <div style="font-size:14px;margin-top: 7px; width: 100%; height: 180px">
            <div style="width: 45%; float: left; height: 180px;border: 1px solid black;padding: 5px">
                <div style="border-top: 1px solid black;margin-top: 125px">
                    <b>FIRMA DEL NOTIFICADO</b><br>
                    Nombre: .................................................................<br>
                    DNI: N°............................................................
                </div>                
            </div>
            <div style="width: 45%; float: right;height: 180px;border: 1px solid black;padding: 5px">
                <div style="border-top: 1px solid black;margin-top: 125px">
                    <b>FIRMA DEL NOTIFICADOR</b><br>
                    Nombre: .................................................................<br>
                    DNI: N°............................................................
                </div>
            </div>
        </div>
    </body>
</html>
