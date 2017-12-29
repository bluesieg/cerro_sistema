<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Requerimiento Pago</title>
        <style>        
            @font-face {
                font-family: SourceSansPro;
                src: url(SourceSansPro-Regular.ttf);
            }
            @page {                
                margin-top: 120px;
                margin-left: 75px;
                margin-right: 75px;
                margin-bottom: 0px;
            }
            #header { position: fixed; left: 0px; top: -120px; right: 0px; height: 100px; text-align: center; }
            .t1, .t2 { border-collapse: collapse; }
            .t1 > tbody > tr > td { border: 1px solid #D5D5D5; font-size: 13px}
            .t1 > thead > tr > th { border:1px solid #D5D5D5; background: #01A858;color: white; }
        </style>

    </head>    
    <body>
        <div id="header" style="padding-top:10px;">            
            <img src="img/escudo.png" style="position:absolute;margin-top: 25px;margin-left: -10px; width: 55px;height: 65px;" >
            <center>
                <h3 style="color: #018F4B;margin-bottom:0px;font-size: 20px;">MUNICIPALIDAD DISTRITAL DE CERRO COLORADO</h3>
                <p style="margin-top: 7px;font-size: 12px;"><b>Dirección:</b> Mariano Melgar N° 500, Urb. La Libertad, Cerro Colorado - Arequipa</p>
                <p style="margin-top: 0px;font-size: 13px;"><b>OFICINA DE EJECUCIÓN COACTIVA / TELF: 54-382590 ANEXO: 733</b> </p>                
                <div style="background:#01A858; margin-top: 0px;height: 1px"></div>           
            </center> 
        </div>
        <div class="subcontainer" style="position: absolute;font-size: 130px;width: 550px; left: 30px; top: 330px; z-index: 100;opacity: .3;transform: rotate(-50deg);transform-origin: 50% 50%;text-align: center">
            <b>PREVIO EMBARGO</b>
        </div>
        <div style="text-align:center; margin-top: 10px;"><b>REQUERIMIENTO DE PAGO</b></div>
        <div style="margin-bottom: -15px;border-bottom: 1px solid black;margin-top: 10px;">
            <table style="font-size:13px;">
                <tr>
                    <td style="width:120px"><b>Obligado</b></td>
                    <td>:&nbsp;{{$resol->contribuyente}}</td>
                </tr>
                <tr>
                    <td><b>Domicilio Fiscal</b></td>
                    <td>:&nbsp;{{$resol->dom_fis}}</td>
                </tr>
                <tr>
                    <td><b>Ubicacion del Predio</b></td>
                    <td>:&nbsp;{{$resol->ubi_pred}}</td>
                </tr>
                <tr>
                    <td><b>Referencia</b></td>
                    <td>:&nbsp;EXPEDIENTE N° {{ $resol->nro_exped.'-'.$resol->anio_resol }}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;&nbsp;Resolución de Ejecución Coactiva N° {{ $resol->nro_resol.'-'.$resol->anio_resol }}/OEC-MDCC</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;&nbsp;{{$resol->doc_ini}} N° {{$resol->nro_rd.'-'.$resol->anio_rd}}-SGFT-MDCC/ARB</td>
                </tr>
                <tr>
                    <td><b>Fecha</b></td>
                    <td>:&nbsp;{{$resol->fch_larga}}</td>
                </tr>
            </table>
        </div>
        <br>
        <div style="text-align: justify;font-size:13px;overflow:auto"> 
            <p>
            Mediente el Presente escrito y en atención a las Resoluciones de referencia, le recordamos que la oficina de Ejecución Coactiva, mediante <b>Resolución de Ejecución Coactiva N° {{ $resol->nro_exped.'-'.$resol->anio_resol }}/OEC-MDCC</b>
            se ha dado inicio al procedimiento de ejecución coactiva, a fin de que usted cumpla con pagar la deuda establecida mediante <b> {{$resol->doc_ini}} N° {{ $resol->nro_rd.'-'.$resol->anio_rd }}-SGFT-MDCC/ARB,</b> 
            ascendiente a la suma de <b>S/. {{$resol->monto}} ({{$resol->monto_letra}})</b> por concepto de Arbitrios correspondiente a los años {{$resol->periodos}}, 
            <b>más los intereses actualizados a la fecha de pago,</b> así como tambien el pago de <u>costas y gastos</u> ocasionados a la entidad en la cobranza coactiva de dicha deuda tributaria, 
            respecto del predio ubicado en {{$resol->ubi_pred}}, Arequipa requiriéndose el cumplimiento de pago dentro de los plazos expresamente 
            establecidos en la ley, <u>bajo apercibimiento de ley en caso de incumplimiento;</u> la misma que fue <u>debidamente notificada</u> con copia del acto administrativo 
            que sirve de título ejecutivo, su correspondiente constancia de notificación, así como también, la constancia de cosa decidida administrativamente, sin embargo, dicha 
            Resolución de Ejecución Coactiva <b>NO HA SIDO CUMPLIDA</b> hasta la fecha.
            </p>
            <p>
            Por lo que, al amparo de lo establecido en el Art. 32, de la Ley de Procedimiento de Ejecución Coactiva Ley N° 26979, su reglamento y sus modificatorias que establece que, 
            <b>"vencido el plazo a que se refiere el Art. 29° (siete días hábiles) sin que el obligado haya cumplido con el mandato contenido en la Resolución de Ejecución Coactiva, 
            el Ejecutor podrá disponer se trabe cualquiera de la MEDIDAS cautelares establecidas en el Art. 33° de la presente Ley, o, en su caso <u>MANDARÁ A EJECUTAR FORZOSAMENTE</u> 
            la obligación de hacer o no hacer. <u>El obligado deberá asumir los gastos</u> en los que haya incurrido la entidad, para llevar a cabo el procedimiento",</b> por lo tanto:
            </p>
            <p>
            <b>SE REQUIERE</b> a usted a <b>CUMPLIR CON EL PAGO DE DICHA DEUDA TRIBUTARIA, CASO CONTRARIO</b> se hará <b><u>EFECTIVO EL APERCIBIMIENTO</u></b> ESTABLECIDO EN LA RESOLUCIÓN 
            DE INICIO DE PROCEDIMIENTO DE EJECUCIÓN COACTIVA, <b><u>EN CUALQUIER MOMENTO Y SIN PREVIO AVISO, EL EJECUTOR PROCEDERÁ A TRABAJAR MEDIDAS CAUTELARES DE EMBARGO U OTROS,</u> 
            CUYOS <u>GASTOS DE EJECUCIÓN CORRERAN A CUENTA DEL OBLIGADO.</u></b> DE CONFORMIDAD CON LA LEY N° 26979, CONSISTENTES EN:
            </p>
            <p>
                <ul> 
                    <li>En forma de intervención en <b>RECAUDACIÓN,</b> en forma de <b>INFORMACIÓN</b> o en <b>ADMINISTRACIÓN</b> sobre los bienes del <u>Obligado (Empresas o Negocios)</u>.</li> 
                    <li>En forma de DEPOSITO O SECUESTRO Conservativo sobre los bienes del Obligado.</li> 
                    <li>En forma de INSCRIPCIÓN EN LOS REGISTROS PÚBLICOS u otros registros.</li> 
                    <li>En forma de RETENCIÓN, cuyo caso recae sobre los bienes, valores y fondos de cuentas corrientes, así como tammbién sobre los derechos de crédito que tenga el Obligado.</li>
                </ul>
            </p>
            <p>
                ASIMISMO, LA ENTIDAD PRECEDERA A SOLICITAR LA INSCRIPCIÓN DE SU DEUDA EN LAS <u>CENTRALES DE RIESGOS.</u> DE CONFORMIDAD CON LO DISPUESTO POR EL TRIBUNAL FISCAL MEDIANTE RTF. 
                N° 09151-1-2008. TAMBIEN PONEMOS DE CONOCIMIENTO QUE PARA EFECTOS DE LEVANTAMIENTO DE LAS MEDIDAS CAUTELARES TRABADAS Y EL COSTO DE LA INSCRIPCIÓN EN LAS CENTRALES DE RIESGO, 
                CORRERAN A CUENTA DEL OBLIGADO. ASUMIENDO COMPETENCIA EL EJECUTOR Y AUXILIAR COACTIVO QUE SUSCRIBEN POR DISPOSICION DE LA ENTIDAD.
            </p>
            <P>Atentamente.</P>
            <br><br><br>
            <p style="font-size: 10px;">
                <b><u>NOTA IMPORTANTE: </u></b>Se pone de conocimiento que, para efectos de <u>CONCLUSION DEL PROCEDIMIENTO Y ARCHIVO DE SU EXPEDIENTE</u> deberá efectuar el pago 
                de su deuda en la <u>OFICINA DE EJECUCIÓN COACTIVA</u> ubicada en el <b>CUARTO PISO</b> de la MUNICIPALIDAD - Sede Administración Tributaria, sitio en la <u>CALLE 
                MELGAR ESQUINA PLAZA LAS AMÉRICAS CON CALLE BOLOGNESI, </u> Cerro Colorado, ello en razón a que su Expediente se encuentra en dicha Oficina, y así evitar procedimientos 
                de Ejecución Forzada, Medidas Cautelares y/o EMBARGOS por incumplimiento de sus obligaciones y/o multas.
            </p>
        </div>     
    </body>
</html>
