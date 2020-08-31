@component('mail::message')
# Hola {{$family->name}} {{$family->last_name}}
<p>Te escribimos para contarte que <strong>{{$student->name}}</strong> <strong>{{$student->last_name}}</strong> ha pasado por <strong>{{$place_advance_line}}</strong> y
    acaba de realiza el test de pregunta cerrada del momento <strong><i>{{$moment->name}}</i></strong>, en la guía de aprendizaje: <strong><i>{{$sequence->name}}</i></strong>.Su desempeño fue {{$performance}}%, y está en el {{$level}}</p>
<strong><span style="width: 10px;
    height: 15px;
    oz-border-radius: 50%;
    -webkit-border-radius: 50%;
    border-radius: 50%;
    background: {{$color_performance}};">&nbsp;&nbsp;&nbsp;&nbsp;</span> {{$performance_comment}}</strong>

<p>A continuación, presentamos el reporte detallado de desempeño en las preguntas:</p>
<hr>
<p>
@foreach($data as $questionAnswer)
<strong>Pregunta: </strong>
{{ Illuminate\Mail\Markdown::parse($questionAnswer['title']) }}
<br>
<strong>Repuesta: </strong>
{{ Illuminate\Mail\Markdown::parse($questionAnswer['answer_student']) }}
<br>
@if($questionAnswer['is_correct'])
<strong>Aprobado: </strong><span style="color:green;
        font-size: 23px;
        font-weight: bolder;margin-top: -16px;">✓</span>
@else
<strong>Aprobado: </strong><span style="color:red;
        font-size: 23px;
        font-weight: bolder;margin-bottom: 2px;margin-top: -16px;">x</span>
@endif
<br>
<strong>Respuesta esperada: </strong><span style="width: 10px;
        height: 15px;
        oz-border-radius: 50%;
        -webkit-border-radius: 50%;
        border-radius: 50%;
        border-style: solid;
    border-width: 1px;">&nbsp;{{strtoupper($questionAnswer['type_numeral'])}}&nbsp;</span>
<br>
{{ Illuminate\Mail\Markdown::parse($questionAnswer['answer_question']) }}
<br>
<strong>Concepto clave a tener en cuenta: </strong>{{$questionAnswer['concept']}}</p>
<hr>
@endforeach
<br>
Hasta pronto,
<br>
Coordinación pedagógica
<br>
<strong>Educonexiones</strong>

@endcomponent