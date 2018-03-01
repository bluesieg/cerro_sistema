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
       
        <div class="subasunto" style="text-align: left; padding-left: 30px; margin-top: 20px;">Cerro Colorado</div>

        <table style="margin-top: 10px; margin-bottom: 5px !important; border-bottom: 1px solid black">
            <tr>
                <td colspan="2" style="border:0px;">
                    <b>I . <span style=" text-decoration: underline">IDENTIFICACIÓN DEL DEUDOR TRIBUTARIO</span></b>
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
     
        </table>
        </b>
        <div style="width: 100%; text-align: justify; font-size: 0.8em; margin-top: 0px; padding-left:18px;">
            Se requiere la cancelación de la deuda contenida en el presente documento, en el plazo de 20 días hábiles contados a partir del día
            siguiente de su notificación, bajo apercibimiento de iniciar el procedimiento de Ejecución Coactiva.<br>
            La presente se emite por los tributos y periodos que se indican cuyo monto se ha actualizado al ggggg, luego de esa fecha se
            actualizara con la Tasa de Interes Moratorio de 1.2% conforme lo fijado mediante ordenanza municipal N° 297-2010-MDCC.<br>
        </div>
        <table style="margin-top: 5px; margin-bottom: 5px !important;">
            <tr>
                <td colspan="2" style="border:0px;">
                    <b>II. <span style=" text-decoration: underline">MOTIVO DETERMINANTE</span></b>
                </td>
            </tr>
         </table>
        <div style="width: 100%; text-align: justify; font-size: 0.8em; margin-top: 0px; padding-left:18px;">
            Que, habiendose realizado el respectivo proceso de fiscalización iniciado con la Carta de Requerimiendo N° gggggg-SGFT-GAT-MDCC,
            la misma que fue notificada el ggggg; la verificación realizada in situ en fechas
            realizando acciones de medición al área construida, categorización de la edificación, su clasificación, estado de conservación y medición
            y valorización de obras complementarias fijas y permanentes, toma de fotografías, todo ello contenido en Fichas de Inspección N°
            
            ggggg
            
             Culminando el proceso de Fiscalización se ha detectado que no ha cumplido on sus obligaciones
             formales y sustanciales motivo por el cual se emite la presente Resolución de Determinación.
        </div>
        <table style="margin-top: 5px; margin-bottom: 5px !important;">
            <tr>
                <td colspan="2" style="border:0px;">
                    <b>III. <span style=" text-decoration: underline">DECLARACION JURADA ACTUALIZADA AL:</span> </b> 
                </td>
            </tr>
           
         </table>
        <table style="margin-top: 5px; margin-bottom: 5px !important;">
           
            <tr>
                <td colspan="2" style="border:0px;">
                    <b>IV. <span style=" text-decoration: underline">UBICACION DE PREDIOS:</span> </b>
                </td>
            </tr>
         </table>
        
        
          
        
  </body>
  
</html>
