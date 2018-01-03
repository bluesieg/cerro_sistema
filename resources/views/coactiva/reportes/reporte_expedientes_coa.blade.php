<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>REPORTES EXPEDIENTES COACTIVA</title>
        <style>        
            @font-face {
                font-family: SourceSansPro;
                src: url(SourceSansPro-Regular.ttf);
            }
            @page {
                margin-top: 180px;
                margin-left: 35px;
                margin-right: 35px;
            }
            #header { position: fixed; left: 0px; top: -180px; right: 0px; height: 190px; text-align: center; }
/*            #footer { position: fixed; left: 0px; bottom: -180px; right: 0px; height: 100px; background-color: lightblue; }
            #footer .page:after { content: counter(page, upper-roman); }*/
            .t1, .t2 { border-collapse: collapse; }
            .t1 > tbody > tr > td { border: 1px solid #D5D5D5; font-size: 11px}
            .t1 > thead > tr > th { border:1px solid #D5D5D5; background: #01A858;color: white; }
        </style>

    </head>    
    <body>
        <div id="header" style="padding-top:0px">            
            <img src="img/escudo.png" style="position:absolute;margin-top: 25px;margin-left: -10px; width: 55px;height: 65px;" >
            <center>
                <h3 style="color:#018F4B;margin-bottom:0px;font-size: 20px;">MUNICIPALIDAD DISTRITAL DE CERRO COLORADO</h3>
                <p style="margin-top:7px;font-size: 12px;"><b>Dirección:</b> Mariano Melgar N° 500, Urb. La Libertad, Cerro Colorado - Arequipa</p>
                <H3 style="margin-top:0px;font-size: 13px;"><b>OFICINA DE EJECUCIÓN COACTIVA / TELF: 54-382590 ANEXO: 733</b> </H3>                
                <div style="background:#01A858; margin-top: 0px;height: 1px"></div>           
            </center>
           
            <div style="text-align:center"><b>REPORTE DE EXPEDIENTES / OEC-MDCC</b></div>
            <div style="position:absolute; left: 910px; top: 145px;">{{date('d-m-Y H:i A')}}</div>
            <div style="text-align:center">{{$desde}} AL {{$hasta}}</div>
            
            <div style="width:100%;">
                <div style="width:22%;float: left;text-align: left;"><b>Materia: </b>{{$materia2}}</div>
                <div style="width:27%;float: left;text-align: left;"><b>Estado: </b>{{$estado2}}</div>
                <div style="width:53%;float: left;text-align: left;"><b>Valor: </b>{{$valor2}}</div>
            </div>
            <br>
        </div>
        
        
        <div style="text-align: justify;font-size:14px;overflow:auto;">           
            <table style="width: 100%;" id="t_dina_conve_fracc" class="t1">
                <thead>
                    <tr>
                        <th width="2%" align="center">N</th>
                        <th width="8%" align="center">Expediente</th>
                        <th width="30%" align="center">Contribuyente</th>
                        <th width="10%" align="center">Materia</th>
                        <th width="25%" align="center">Ultimo Documento Emitido</th>
                        <th width="10%" align="center">Monto</th>
                        <th width="10%" align="center">Estado</th>
                        <th width="18%" align="center">Valor</th>
                    </tr>
                </thead>
                <tbody>                
                    @foreach($todo as $todo)
                        <tr>
                        <td style="text-align: center">{{ $todo->cc }}</td>
                        <td style="text-align: left">{{ $todo->nro_exped }}-{{ $todo->anio }}</td>
                        <td style="text-align: left">{{ str_replace('-','',trim($todo->contribuyente)) }}</td>
                        <td style="text-align: left">{{ $todo->desc_mat }}</td>
                        <td style="text-align: left">{{ $todo->ult_gestion }}</td>
                        <td style="text-align: right">{{ $todo->monto }}</td>
                        <td style="text-align: center">{{ $todo->estado }}</td>
                        <td style="text-align: left">{{ $todo->doc_ini }}</td>
                        </tr>
                    @endforeach    
                </tbody>
            </table>
            <table style="width: 100%;" class="t1">
                <tr>
                
                <td style="text-align: right" width="58%">TOTAL</td>
                <td style="text-align: right" width="10%">{{ number_format($ttotal,3,'.',',') }}</td>
                <td style="text-align: center" width="22.4%"></td>
                
                </tr>
            </table>
        </div>     
    </body>
</html>