@component('mail::message')
@if ($code == 0)
# Tienes una nueva orden de DAIMLER

Identificacion de envio: **{{ $id}}**<br>
Fecha de carga: **{{ $fecha}}**<br>
Hora de carga: **{{ $hora}}**


| Origen |  | Destino |
| ------ | ------ | ------ |
| {{ $origen }} | - | {{ $destino }} |

<br>
<br>
¡Favor de enviar su confirmacion!
@component('mail::button', ['url' => 'http://192.168.1.220:8012/edidaimler/'.$id, 'color' => 'success']) Ver Orden
@endcomponent

@elseif($code == 5)
# DAIMLER Actualizo la orden siguiente:

Identificacion de envio: **{{ $id}}**<br>
Fecha de carga: **{{ $fecha}}**<br>
Hora de carga: **{{ $hora}}**


| Origen |  | Destino |
| ------ | ------ | ------ |
| {{ $origen }} | - | {{ $destino }} |

<br>
@else
# Cancelacion de orden por DAIMLER:

Identificacion de envio: **{{ $id}}**<br>
Fecha de carga: **{{ $fecha}}**<br>
Hora de carga: **{{ $hora}}**


| Origen |  | Destino |
| ------ | ------ | ------ |
| {{ $origen }} | - | {{ $destino }} |

<br>
@endif

Gracias,<br>
{{ config('app.name') }}
@endcomponent