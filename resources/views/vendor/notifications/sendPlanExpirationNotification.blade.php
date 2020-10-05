@component('mail::message')
#Hola (nombre del adulto registrado)
<br>
<p>En Conexiones queremos que aprovechen al máximo los recursos educativos que hemos creado para fortalecer los saberes y el desarrollo de pensamiento científico, por eso queremos avisarte que el (fecha) termina el permiso de acceso a (nombre del contenido / plan)</p>
<br>
Hasta pronto,
<br>
<strong>{{ config('app.name') }}</strong>
@endcomponent