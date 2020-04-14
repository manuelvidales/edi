@component('mail::message')
# Tienes una nueva orden de DAIMLER

Identificacion de envio: **{{ $id}}**<br>
Fecha de carga: **{{ $fecha}}**


| Origen |  | Destino |
| ------ | ------ | ------ |
| {{ $origen }} | - | {{ $destino }} |

<br>
<br>
¡Favor de enviar su confirmacion!
@component('mail::button', ['url' => 'http://192.168.1.220:8012/edidaimler/'.$id, 'color' => 'success']) Ver Orden
@endcomponent


Gracias,<br>
{{ config('app.name') }}
@endcomponent