@component('mail::message')
# Hola, se ha realizado una solicitud por parte del correo {{$data['email']}}.
<br>
<strong>Asunto:</strong>
<br>
{{$data['affair']}}
<br>
<strong>Mensaje:</strong>
<br>
{{$data['message']}}
<br>
<strong>Nº radicado:</strong>
{{$data['contacus_id']}}
<br>
<br>
Gracias.
<br>
<strong>{{ config('app.name') }}</strong>
@endcomponent