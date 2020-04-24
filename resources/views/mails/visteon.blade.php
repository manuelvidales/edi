@component('mail::message')
# Notificacion nueva orden de facturacion

Identificacion: **{{ $id}}**<br>
Fecha de Emision: **{{ $fecha}}**



Gracias,<br>
{{ config('app.name') }}
@endcomponent
