<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Cronograma de Pagos</title>
        <style>        
            @font-face {
                font-family: SourceSansPro;
                src: url(SourceSansPro-Regular.ttf);
            }
            footer { position: fixed; bottom: -20px; left: 0px; right: 0px; height: 70px; }
            .t1, .t2 { border-collapse: collapse; }
            .t1 > tbody > tr > td { border: 1px solid #D5D5D5; font-size: 13px}
            .t1 > thead > tr > th { border:1px solid #D5D5D5; background: #01A858;color: white; }            
        </style>

    </head>    
    <body>
        <img src="img/escudo.png" style="position:absolute;margin-top: 0px;margin-left: 12px; width: 55px;height: 60px;" >
        <center>
            <h3 style="color:#018F4B;margin-bottom:0px;font-size: 20px;">MUNICIPALIDAD DISTRITAL DE CERRO COLORADO</h3>
            <div style="background:#01A858; margin-top: 20px;height: 1px"></div>
            <div>
                <h2 style="margin-bottom:10px;font-size: 20px;"><u>CRONOGRAMA DE PAGOS</u></h2>                    
            </div>
        </center>
        
        <div style="margin-top: 20px;">
            <center><b>DATOS DEL CONTRIBUYENTE</b></center>
            <table style="font-size:14px;margin-top: 10px;">
                <tr>
                    <td><b>CODIGO:</b></td>
                    <td>{{ $contrib[0]->id_persona}}<br/></td>
                </tr>
                <tr>
                    <td><b>CONTRIBUYENTE:</b></td>
                    <td>{{ $contrib[0]->contribuyente}}<br/></td>
                </tr>
                <tr>
                    <td><b>DNI:</b></td>
                    <td>{{ $contrib[0]->nro_doc}}<br/></td>
                </tr>
                <tr>
                    <td><b>DOMICILIO FISCAL:</b></td>
                    <td>{{ $contrib[0]->dom_fis}}<br/></td>
                </tr>
                <tr>
                    <td><b>FECHA:</b></td>
                    <td>{{ strtoupper($fecha_larga) }}<br/></td>
                </tr>
            </table>
        </div>
        <div style="margin-top: 20px; ">
            <center><b>DATOS DEL FRACCIONAMIENTO</b></center>
            <table class="table table-sm" style="font-size:14px;margin-top: 10px;">
                <tr>
                    <td><b>PERIODO FRACCIONADO:</b></td>
                    <td>&nbsp;{{ $conv[0]->periodo}}<br/></td>
                </tr>
                <tr>
                    <td><b>TIPO FRACCIONAMIENTO:</b></td>
                    <td>&nbsp;{{ $conv[0]->tip_fracc}}<br/></td>
                </tr>
                <tr>
                    <td><b>NRO. CONVENIO:</b></td>
                    <td>&nbsp;{{ $conv[0]->nro_conve2}}<br/></td>
                </tr>
                <tr>
                    <td><b>TOTAL:</b></td>
                    <td>&nbsp;S/.{{ number_format($conv[0]->total_convenio,2,'.',',')}}<br/></td>
                </tr>
                <tr>
                    <td><b>INICIAL:</b></td>
                    <td>&nbsp;S/.{{ $conv[0]->cuota_inicial}} &nbsp;({{ $conv[0]->porc_cuo_inic}}%)<br/></td>
                </tr>
                <tr>
                    <td><b>TIF:</b></td>
                    <td>&nbsp;{{ $conv[0]->interes}}%<br/></td>
                </tr>
                <tr>
                    <td><b>N° CUOTAS APROBADAS:</b></td>
                    <td>&nbsp;{{ $conv[0]->nro_cuotas}}<br/></td>
                </tr>
                <tr>
                    <td><b>CUOTA CONSTANTE:</b></td>
                    <td>&nbsp;S/.{{ number_format($todo[0]->total,2,'.',',') }}<br/></td>
                </tr>
                <tr>
                    <td><b>GLOSA:</b></td>
                    <td>&nbsp;{{ $conv[0]->detalle_fracci }}<br/></td>
                </tr>
            </table>
        </div>
        <div style="margin-top:10px;">         
            <table style="width: 100%;" id="t_dina_conve_fracc" class="t1">
                <thead>
                    <tr style="font-size:0.9em">
                        <th width="10%" align="center">N°</th>
                        <th width="40%" align="center">Saldo</th>
                        <th width="40%" align="center">Amortización</th>
                        <th width="40%" align="center">Interes</th>
                        <th width="40%" align="center">Cuota Mensual</th>
                        <th width="40%" align="left">Fecha Vencimiento</th>
                    </tr>
                </thead>
                <tbody>                
                    @foreach($todo as $value)
                    <tr>
                        <td style="text-align: center">{{ number_format($value->nro_cuot,2,'.',',') }}</td>
                        <td style="text-align: center">{{ number_format($value->saldo,2,'.',',') }}</td>
                        <td style="text-align: center">{{ number_format($value->amor,2,'.',',') }}</td>
                        <td style="text-align: center">{{ number_format($value->inter,2,'.',',') }}</td>
                        <td style="text-align: center">{{ number_format($value->total,2,'.',',') }}</td>
                        <td style="text-align: left">{{ $value->fec_pago }}</td>
                    </tr>
                    @endforeach            
                </tbody>
            </table>
            <div style="border-top: 1px solid #01A858">
                <table style="width: 100%;" class="t1">
                    <tr>
                        <td width="10%" align="center"></td>
                        <td width="40%" align="center">TOTALES</td>               
                        <td width="40%" align="center">{{ $totales['tot_amo'] }}</td>
                        <td width="40%" align="center">{{ $totales['tot_inter'] }}</td>
                        <td width="40%" align="center">{{ $totales['tot_cc'] }}</td>                       
                        <td width="40%" align="center"></td>
                    </tr>                   
                </table> 
            </div>
        </div>
    
    <footer>
        <center>
        <div style="float: left;width: 45%; text-align: center;border-top: 1px solid #01A858">
            <p>MUNICIPALIDAD</p>
        </div>
        <div style="float: right;width: 45%; text-align: center;border-top: 1px solid #01A858">
            <p>{{ str_replace('-','',$contrib[0]->contribuyente)}}<br>DNI: {{ $contrib[0]->nro_doc}}</p>
        </div>
        </center>
    </footer>
    </body>
</html>
