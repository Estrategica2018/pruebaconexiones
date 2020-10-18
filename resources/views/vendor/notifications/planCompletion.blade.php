@component('mail::message')
# Hola {{$nameFamiliar}}
<p>Felicitaciones <strong>{{$student->name}} {{$student->last_name}}</strong> acaba de terminar <strong>{{$sequence->name}}</strong> del <strong>{{$plan->name}}</strong>
</p>
<br>
<p>Gracias</p>
Coordinación pedagógica
<br>
<strong>Educonexiones</strong>

@endcomponent