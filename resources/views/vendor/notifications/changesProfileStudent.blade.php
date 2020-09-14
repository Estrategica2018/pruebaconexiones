@component('mail::message')
# Hola {{$family->name}} {{$family->last_name}}
<br>
Te informamos que el estudiante {{$student['name'].' '.$student['last_name']}}, ha realizado los siguientes cambios en su perfil de estudiante:
<br>
@foreach($data as $dataRow)
<span><strong>{{$dataRow['key']}}:</strong> {{$dataRow['value']}}</span>
<br>
@endforeach
<br>
<br>
Gracias.
<br>
<strong>{{ config('app.name') }}</strong>
@endcomponent