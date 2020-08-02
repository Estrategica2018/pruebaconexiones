@component('mail::message')
# Hola, se ha realizado una solicitud por parte del correo {{$data['email']}}.
<br>
<strong>Comentario:</strong>
<br>
{{$data['comment']}}
<br>
Gracias.
<br>
{{ config('app.name') }}
@endcomponent