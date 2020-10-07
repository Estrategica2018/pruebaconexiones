@component('mail::message')
#Hola {{$data->company_affiliated->retrive_afiliado_empresa->name}} {{$data->company_affiliated->retrive_afiliado_empresa->last_name}}
<br>
<p>En Conexiones queremos que aprovechen al máximo los recursos educativos que hemos creado para fortalecer los saberes y
    el desarrollo de pensamiento científico, por eso queremos avisarte que el
    {{$data->end_date}}
    termina el permiso de acceso a {{$data->rating_plan->name}}</p>
<br>
Hasta pronto,
<br>
<strong>{{ config('app.name') }}</strong>
@endcomponent