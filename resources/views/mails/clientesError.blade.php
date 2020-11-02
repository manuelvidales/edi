<style>
    table {
      border-collapse: collapse;
      width: 100%;
      font-size: 14px;
      text-align: center;
    }
    th, td {
      padding: 5px;
      border-bottom: 2px solid #ddd;
      color: #000;
    }
    p {
        font-size: 14px;
    }
    hr {
    	border-bottom: 10px solid #ddd;
    }
</style>

<table>
    <tr style="background-color: #ff000f">
      <th style="border-bottom: 1px solid #ffff;">ERROR DE CORREO</th>
    </tr>
    <tr style="background-color: #ffda90">
        <th style="border-bottom: 1px solid #ddd;"> Cliente: {{$cliente}}.</th>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid #fff;">
            <br><br>
        <p>Favor de capturar el correo electronico para el cliente: <span style="font-weight: bold;"> {{ $cliente }}.</span> </p>
        <p>O bien revisar el catalogo de correos de los clientes para verificar este capturado correctamente.</p>
        </td>
    </tr>
</table>
<br><br><br>
<p style="font-size: 11px;"><i>Este mensaje lo genera automáticamente el servicio de notificación por correo electrónico de autofleteshalcon, y no es necesario responder.</i></p>
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