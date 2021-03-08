@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
<br>
<p>
    Estás recibiendo este mensaje porque acabas de registrarte en <strong>Conexiones</strong>, experiencias científicas para comprender
    el mundo natural. Nuestra <strong>propuesta educativa</strong> y <strong>plataforma on line</strong>, han sido especialmente diseñadas para que las niñas,
    los niños y jóvenes,<strong> integren diferentes saberes en su proceso de aprendizaje</strong>, con la intención de promover el desarrollo
    de <strong>pensamiento científico.</strong>
</p>
<br>
<p>
    Te invitamos a conocer más sobre los recursos didácticos dispuestos para nuestros
    estudiantes y demás características de nuestra propuesta educativa en el siguiente
    vínculo <a href="{{route('/')}}">https://educonexiones.com/</a>

</p>
<br>
<p>
    Te damos la bienvenida a <strong>Conexiones</strong>, nuestro equipo pedagógico y técnico está disponible para facilitar
    la apropiación de los contenidos y el uso de la plataforma. De acuerdo con los datos suministrados,
    en este momento tienes acceso a la <strong>muestra gratuita</strong>, esta opción ofrece <strong>15 días</strong> para aprovechar al
    máximo los recursos didácticos que estamos compartiendo.
</p>
<br>
<p>
    Al acceder a la plataforma con esta opción, tendrás a disposición: un video introductorio a la <strong>situación generadora</strong>
    y contenidos del <strong>Momento 1</strong> de la ruta propuesta en la guía de aprendizaje: <strong>Energía súper poderosa.</strong>
</p>
<br>
<p>
    Es importante tener presente que con este plan podrán acceder a preguntas para <strong>explorar saberes previos,
        prácticas experimentales, explicaciones científicas contextualizadas y recursos en la web sugeridos</strong> para
    ampliar sus comprensiones.
</p>
<p>
    Durante todo el proceso de exploración de los contenidos, encontrarán <strong>preguntas abiertas</strong> que tienen la
    intención de estimular habilidades científicas y que no tienen respuesta única,
    pero al finalizar cada sesión, habrá un <strong>test de preguntas</strong> cerradas que permite identificar cómo
    avanza en el proceso de aprendizaje, las fortalezas y aspectos susceptibles de mejora.
</p>
<p>
    Si tienes preguntas o sugerencias, puedes contactarnos dando click <a href="{{route('contactus')}}">aquí</a>
</p>
<hr>
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Hello!')
@endif
@endif
{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach
@foreach($data as $user)
<h2>{!!'Usuario:' .$user->user_name !!}</h2>
<h2>{!!'Contraseña:' .$user->user_name !!}</h2>
@foreach($user->affiliated_company as $company_rol)
{{'Empresa: '.$company_rol->company->name.' rol: '.$company_rol->rol->description}}
<br>
@endforeach
@endforeach
{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Regards'),<br>
{{ config('app.name') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
@lang(
    "If you’re having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
    'into your web browser: [:actionURL](:actionURL)',
    [
        'actionText' => $actionText,
        'actionURL' => $actionUrl,
    ]
)
@endslot
@endisset
@endcomponent
