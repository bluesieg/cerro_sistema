<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Reporte Caja Diario</title>
        <style>        
            @font-face {
                font-family: SourceSansPro;
                src: url(SourceSansPro-Regular.ttf);
            }
            @page {
                size: 21cm 29.7cm;
                margin-top: 0.5cm;
                margin-bottom: 0.5cm;
                
            }
            footer { position: fixed; bottom: -20px; left: 0px; right: 0px; height: 70px; }
            .t1, .t2 { border-collapse: collapse; }
            .t1 > tbody > tr > td { border: 1px solid #D5D5D5; font-size: 12px}
            .t1 > thead > tr > th { border:1px solid #D5D5D5;font-size: 13px; background: #01A858;color: white; }            
        </style>

    </head>    
    <body>
        <img src="img/escudo.png" style="position:absolute;margin-top: 0px;margin-left: 12px; width: 55px;height: 65px;" >
        <center>
            <h3 style="color:#018F4B;margin-bottom:0px;font-size: 20px;">MUNICIPALIDAD DISTRITAL DE CERRO COLORADO</h3>
            <div style="background:#01A858; margin-top: 20px;height: 1px"></div>
            <div>
                <h2 style="margin-bottom:10px;font-size: 20px;"><u>REPORTE DIARIO CAJA</u></h2>                    
            </div>
        </center>
        
        <div style="margin-top: 20px;">            
            <table style="font-size:14px;margin-top: 10px;">                
                <tr>
                    <td><b>CAJA:</b></td>
                    <td>{{ $master[0]->descrip_caja}}<br/></td>
                </tr>
                <tr>
                    <td><b>USUARIO:</b></td>
                    <td>{{ $master[0]->usuarios}}<br/></td>
                </tr>
                <tr>
                    <td><b>FECHA:</b></td>
                    <td>{{ date('d-m-Y',strtotime($master[0]->fecha))}}<br/></td>
                </tr>                
            </table>
        </div>
        
        <div style="margin-top: 20px;"> <center> LISTA DE RECIBOS</center></div>
        <div style="margin-top: 20px;">            
            <table style="width: 100%;" class="t1">
                <thead>
                    <tr>
                        <th align="center" width="5%">N°</th>
                        <th align="center" width="10%">Nro Recibo</th>
                        <th align="center" width="20%">Persona</th>
                        <th align="center" width="50%">Descripción / Glosa</th>
                        <th align="center" width="10%">Total S/.</th>
                    </tr>                                        
                </thead>
                <tbody>                    
                    @foreach($master as $master)
                    <tr>                        
                        <td style="text-align: center">{{ $master->nro }}</td>
                        <td style="text-align: center">{{ $master->nro_recibo }}</td>
                        <td style="text-align: center">{{ $master->persona }}</td>
                        <td style="text-align: left">{{ $master->glosa }}</td>
                        <td style="text-align: right">{{ number_format($master->total,2,'.',',') }}</td>                        
                    </tr>
                    @endforeach
                    <tr>                        
                        <td colspan="4" style="text-align: right">TOTAL:&nbsp;</td>
<!--                        <td style="text-align: center"></td>
                        <td style="text-align: left"></td>
                        <td style="text-align: center">Total</td>-->
                        <td style="text-align: right; background: #01A858;color:white"><b>{{ number_format($total[0]->total,3,'.',',') }}</b></td>                        
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>
