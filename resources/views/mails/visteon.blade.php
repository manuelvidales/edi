@component('mail::message')
# Envio de Factura a cliente

Identificacion: **{{ $id}}**<br>
Fecha de Emision: **{{ $fecha}}**



Gracias,<br>
{{ config('app.name') }}
@endcomponent
