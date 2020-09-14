@component('mail::message')
# Hola, se ha realizado una solicitud por parte del correo {{$data['email']}}.
<br>
<br>
<strong>Comentario:</strong>
<br>
<br>
{{$data['comment']}}
<br>
<br>
Gracias.
<br>
<strong>{{ config('app.name') }}</strong>
@endcomponent