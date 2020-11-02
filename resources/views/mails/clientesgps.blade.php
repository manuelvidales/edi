<style>
    table {
      border-collapse: collapse;
      width: 100%;
      font-size: 13px;
      text-align: center;
    }
    th, td {
      padding: 5px;
      border-bottom: 2px solid #ddd;
      color: #000;
    }
    p {
        font-size: 13px;
    }
    hr {
    	border-bottom: 5px solid #ddd;
    }
</style>
<table>
    <tr style="background-color: #cdefdc"><th style="border-bottom: 1px solid #ddd;">Carrier Status Update Notification</th></tr>
    <tr style="background-color: #cdefdc"><th style="border-bottom: 1px solid #ddd;">Customer: ALPS LOGISTICS (USA) INC.</th></tr>
    <tr><td style="border-bottom: 1px solid #fff;"><span style="font-weight: bold;">Carrier:</span> AUTOFLETES INTERNACIONALES HALCON SC</td></tr>
    <tr><td style="border-bottom: 1px solid #fff;"><img src="http://201.174.6.122:88/img/logonew.png"></td></tr>
    <tr>
        <td style="border-bottom: 1px solid #fff;">Traffic Control can be reached by emailing <a href="mailto:ace@autofleteshalcon.com">ace@autofleteshalcon.com</a> or directly at (956) 42932 88 from US or (899) 9252030 from Mexico.</td>
    </tr>
</table>
<p ><span style="font-weight: bold;">Load #: </span> {{$viaje}}</p>
<table >
    <tr style="background-color: #bce8d0">
        <th >Operador:</th>
        <th >Unidad:</th>
        <th >Placas:</th>
        <th >Remolque:</th>
        <th >Ruta:</th>
        <th >Origen:</th>
        <th >Destino:</th>
    </tr>
    <tr>
        <td>{{$operador}}</td>
        <td>{{$unidad}}</td>
        <td>{{$placas}}</td>
        <td>{{$remolque}}</td>
        <td>{{$ruta}}</td>
        <td>{{$origen}}</td>
        <td>{{$destino}}</td>
    </tr>
</table>
<br>
<table style="width:60%;">
    <tr style="background-color: #ddd">
        <th colspan="4">"BITACORA DE STATUS"</th>
    </tr>
    <tr style="background-color: #cdefdc">
        <th>Status:</th>
        <th>Ubicacion:</th>
        <th>Fecha:</th>
        <th>Mapa:</th>
    </tr>
    <tr>
        <td style="text-align: left;">Transitando</td>
        <td>[Torreón], Coahuila de Zaragoza 27275, México</td>
        <td>17/06/2019 01:25:00 p.m.</td>
        <td><a href="https://maps.google.com/?q=25.53073,-103.31506" target="_blank"><img src="http://201.174.6.122:88/img/location.png"></a></td>
    </tr>
</table>
<br>
<hr>
<table style="font-size: 11px;">
    <tr>
        <td style="border-bottom: 1px solid #fff;">Date Issuded: {{ date('d/m/Y h:i:s a ', strtotime(now())) }}</td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid #fff;">copyright©{{ now()->year }} Power By: <a href="https://www.autofleteshalcon.com">autofleteshalcon.com</a></td>
    </tr>
</table>
<br>