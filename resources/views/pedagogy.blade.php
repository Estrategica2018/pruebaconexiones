@extends('layouts.app_side')

@section('plugins')
<link rel="stylesheet" href="{{ asset('css/pedagogy.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="card-header about-header boder-header p-2 ml-3">
        <h5 class="mb-0">Enfoque pedagógico</h5>
    </div>
    <div class="mt-3 ml-3 row">
        <img class="col-lg-4 col-6 mb-3" src="{{ asset('images/acercaConexiones/tituloMomento.jpg') }}" alt="">
        <div class="col-lg-8 col-12 pr-5">
            <div class="font-weight-bold fs-md-0 fs-sm--1"><strong>Aprendizaje por indagación y desarrollo de pensamiento científicos</strong></div>
            <div class="text-justify fs-0 mt-3">
                <p>La propuesta educativa de Conexiones se sustenta en un enfoque pedagógico de aprendizaje por indagación,
                    que tiene la intención de promover en los niños, las niñas y los jóvenes, el desarrollo de pensamiento
                    científico. En coherencia con esto, las guías de aprendizaje se han diseñado bajo las siguientes
                    características comunes:</p>
                <ul>
                    <li class="mt-2">Parten de situaciones cotidianas que despiertan interés en las y los estudiantes.</li>
                    <li class="mt-2">Promueven la identificación de saberes de los y las estudiantes, para construir
                        progresivamente explicaciones más complejas a partir de estos.</li>
                    <li class="mt-2">Plantean situaciones y problemas situados, para ser analizados desde el contexto local y
                        global.</li>
                    <li class="mt-2">Formulan preguntas abiertas, sencillas y contextualizadas, con la intención de motivar
                        el interés de aprender y la curiosidad por indagar.</li>
                    <li class="mt-2">Propone experiencias de aprendizaje en las que los y las estudiantes asuman un rol
                        activo, protagónico y propositivo. </li>
                    <li class="mt-2">Involucran al estudiante en el diseño y desarrollo de las prácticas experimentales.
                    </li>
                    <li class="mt-2">Proponen la vivencia del trabajo colaborativo y la valoración de este.</li>
                    <li class="mt-2">Promueven la integración de diferentes áreas de conocimiento para la comprensión amplia
                        de los fenómenos naturales.</li>
                    <li class="mt-2">Presentan conocimientos propios de las ciencias de manera contextualizada, gradual y
                        sencilla.</li>
                </ul>
            </div>
        </div>

    </div>

    <div class="pl-lg-2 ml-3 mb-3 col-12">
        <ul>
            <li class="mt-2">Fortalecen actitudes cuidadosas (de sí, del medio, de otros y otras) responsables y
                propositivas.</li>
            <li class="mt-2">Estimulan la socialización de acciones y resultados.</li>
            <li class="mt-2">Integran la evaluación como un proceso permanente, en el que los aciertos y errores se
                conciben como oportunidades para el aprendizaje.</li>
        </ul>
    </div>
    <div class="pr-lg-2 col-12">
        <div class="p-2 ml-3">
            <h5 class="mb-0">Para saber más</h5>
        </div>
        <div class="row ml-2">
            <div class="col-lg-6 col-12 mt-2">
                <p class="text-justify">Si les interesa conocer más respecto a la indagación científica en la etapa escolar,
                    perspectiva de enseñanza de las ciencias en la que se fundamenta pedagógicamente Conexiones, les
                    recomendamos ver la siguiente charla de TED, en la que Melina Furman, expertaen didáctcia de las ciencias
                    nos cuenta sobre las potencialidades que esta tiene.</p>
            </div>
            <div class="col-auto ml-3 mt-3 mt-lg-1">
                <iframe width="412" height="241" src="https://www.youtube.com/embed/LFB9WJeBCdA" frameborder="0"
                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen=""></iframe>
            </div>
        </div>
    </div>
    <div class="pr-lg-2 col-lg-12 mt-3">
        <div class="col-12 boder-header p-2 ml-3 mb-3">
            <h5>Estructura de las guías de aprendizaje</h5>
        </div>
        <div class="row p-3">
            <div class="col-lg-4 col-12">
                <img class="ml-6" width="350" height="400"
                    src="{{ asset('images/acercaConexiones/estructura_guias_aprendizaje.png') }}" alt="">
            </div>
            <div class="col-lg-6 col-12 ml-lg-6 mt-3 text-justify">
                <p>Las guías de aprendizaje que hacen parte de Conexiones, usan la metáfora de recorrido o
                    camino para organizar y presentar los contenidos que los y las estudiantes son invitados a
                    explorar. A diferencia de los recursos educativos convencionales, nuestras guías de aprendizaje
                    no se diseñan a partir de una temática, ni restringen los contenidos a un único campo de saber
                    (biología, física, química o tecnología…); por el contrario, el abordaje de los fenómenos
                    naturales se hace desde una perspectiva interdisciplinar, que permite establecer múltiples
                    relaciones entre estos para así ampliar las fronteras del conocimiento.</p>
            </div>
        </div>
    </div>
    <div class="pr-lg-2 col-lg-12 mt-3">
        <div class="col-12 boder-header p-2 ml-3 mb-2">
            <h5>Situación generadora</h5>
        </div>
        <div class="row p-2">
            <div class="col-md-5 ml-2 mt-1 text-justify">
                <p>En lugar de proponer objetivos anclados a conceptos, cada guía de aprendizaje inicia con la descripción
                    de una situación generadora
                    o de interés, que tiene la intención pedagógica de movilizar la curiosidad de los estudiantes y las
                    estudiantes para indagar y
                    aprender.</p>
                <p>A continuación podrán apreciar como ejemplo, la situación generadora de la guía de aprendizaje: Las
                    Astroaventuras de Yotopó y la
                    cápsula del tiempo, en la que se invita a los niños, niñas y jóvenes a observar los movimientos del Sol,
                    la Luna y las estrellas, para
                    deducir la relación que estos fenómenos naturales tienen con la medida del tiempo.</p>
            </div>
            <div class="col-md-6 col-12">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/u1iBFJIsIhw" frameborder="0"
                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </div>
    <div class="pr-lg-2 col-lg-12 mt-3">
        <div class="col-12 boder-header p-2 ml-3 mb-2">
            <h5>Ruta de viaje</h5>
        </div>
        <div class="row p-2">
            <div class="col-lg-6 col-12 ml-lg-4">
                <img class="" width="580" height="340" src="{{ asset('images/acercaConexiones/Ruta_de_viaje.png') }}"
                    alt="">
                <span class="" style="font-size: 9px;display: inline-flex;"> Este es el ejemplo de la ruta de viaje de la
                    guía de aprendizaje: Nuestro cuerpo: vida y movimiento, que está orientada a reconocer cómo funciona el
                    cuerpo, (partes y procesos vitales que desempeñan) valorarlo y tomar decisiones para su cuidado</span>
            </div>
            <div class="col-lg-6 col-12 ml-2 mt-1 text-justify">
                <p>Cada guía de aprendizaje propone seguir una ruta de viaje organizada en ocho momentos o etapas
                    secuenciales que, en conjunto,
                    permiten ampliar la comprensión de los fenómenos naturales tratados y la conexión de estos con el mundo
                    de la vida. Los momentos
                    de la ruta están articulados a un punto de encuentro, que constituye el propósito común articulador de
                    todos los contenidos.</p>
                <p>Este es el ejemplo de la ruta de viaje de la guía de aprendizaje: Nuestro cuerpo: vida y movimiento, que
                    está orientada a reconocer
                    cómo funciona el cuerpo, (partes y procesos vitales que desempeñan) valorarlo y tomar decisiones para su
                    cuidado.</p>
            </div>
        </div>
    </div>
    <div class="pr-lg-2 col-lg-12 mt-3">
        <div class="col-12 boder-header p-2 ml-3 mb-2">
            <h5>Guía de saberes</h5>
        </div>
        <div class="row p-2">
            <div class="col-md-5 ml-2 mt-1 text-justify">
                <p>Algo a tener en cuenta es que durante el desarrollo de cada guía de aprendizaje, las y los estudiantes
                    despliegan un conjunto de
                    acciones de pensamiento y producción que constituyen las evidencias de aprendizaje, y se reconocen por la
                    presencia de los
                    siguientes íconos:</p>
                <p><strong>Saber qué:</strong> Manejo de conocimientos propios de las ciencias</p>
                <p><strong>Saber cómo:</strong> Aproximación al conocimiento como lo hacen quienes se
                    dedican a las ciencias (Saber cómo)</p>
                <p><strong  >Saber ser:</strong> Desarrollo de compromisos personales y sociales.</p>
            </div>
            <div class="col-md-6 col-12 ml-lg-4">
                <img class="" width="580" height="340" src="{{ asset('images/acercaConexiones/Guias_de_saberes.png') }}"
                    alt="">
                <span class="" style="font-size: 9px;display: inline-flex;"> Este es el ejemplo de algunos de los saberes
                    que implican el desarrollo de la guía de aprendizaje: Agua para todos, a través de la cual
                    se puede comprender función que tiene el agua en el sostenimiento de todos los seres vivos y promover
                    acciones individuales y
                    colectivas para su preservación.</span>
            </div>
        </div>
        <p class="mt-3">Cada momento de la ruta de viaje está conformado por: una pregunta central, de la que se derivan experiencias
            científicas,
            explicaciones de ciencia cotidiana y recursos recomendados para + conexiones, que movilizan la activación de
            diferentes acciones
            de pensamiento y producción esenciales para que las niñas, niños y jóvenes desarrollen pensamiento científico.
        </p>
        <p>Los siguientes iconos permiten identificar la ubicación y relación de cada momento de aprendizaje <strong>(Ver + haciendo clic en cada ícono)</strong></p>
		
        <div class="row mt-4">
            <div class="col-1-7"></div>
            <div class="col-2-3  text-align">
                <img class="mb-3 cursor-pointer" ng-mouseover="icon_pedagogy = 'central_question'" ng-click="icon_pedagogy === 'central_question'" width="74px" height="auto" src="{{ asset('images/icons/preguntaCentral.png') }}">
				<span class="justify-content-center font-weight-bold d-flex mt-1 ml-auto mr-auto w-75"> Pregunta Central </span>
                <div class="d-result d-none panel-icon-pedagogy fs--3" ng-show="icon_pedagogy==='central_question'">
					<div style="margin-left: 5vw;position: absolute;margin-top: -38px;">
					  <div style="border-width: 0px 17px 17px; border-style: solid;border-image: initial;border-color: #77A3A3 transparent;content: '';display: block;font-size: 0px;height: 0px;line-height: 0;position: absolute;top: 1px;width: 0px;left: -6px;">
					  </div>
				    </div>				
                    <div class="header ">
                      <span class="central_question_color">Pregunta central</span>
                    </div>
                    <div class="body">
                        <p>Una pregunta puede motivarnos a viajar… para responderla podemos recorrer diferentes caminos </p>
						<ul>
                        <li>Permite movilizar y reconocer diferentes saberes previos para construir progresivamente
                            explicaciones más complejas a partir de estos. </li>
                        <li>Tiene el propósito de promover la indagación y curiosidad científica. </li>
                        <li>Es formulada de manera abierta, sencilla y contextualizada. </li>
                        <li>Constituye el eje sobre el que se despliegan los contenidos de cada momento. </li>
						</ul>
                    </div>
                </div>
            </div>
            <div class="col-2-3  text-align">
				<img class="mb-3 cursor-pointer" ng-mouseover="icon_pedagogy = 'scientific_experience'"  ng-click="icon_pedagogy === 'scientific_experience' " width="74px" height="auto" src="{{ asset('images/icons/iconoExperiencia.png') }}">
				<span class="justify-content-center font-weight-bold d-flex mt-1 ml-auto mr-auto w-75"> Experiencia Científica </span>
                <div class="d-result d-none panel-icon-pedagogy fs--3" ng-show="icon_pedagogy==='scientific_experience'">
					<div style="margin-left: 5vw;position: absolute;margin-top: -38px;">
					  <div style="border-width: 0px 17px 17px; border-style: solid;border-image: initial;border-color: #77A3A3 transparent;content: '';display: block;font-size: 0px;height: 0px;line-height: 0;position: absolute;top: 1px;width: 0px;left: -6px;">
					  </div>
				    </div>
                    <div class="header">
                      <span class="scientific_experience_color">Experiencia científica</span>
                    </div>
                    <div class="body">
                        <p>Un viaje es un conjunto de experiencias, pues más allá de los destinos y lugares que se visitan es lo que se vive en ellos lo que permanece.</p>
						<ul>
                        <li>Están diseñadas para que las y los estudiantes tengan un rol activo, protagónico y propositivo. </li>
                        <li>Crean condiciones de posibilidad para el diálogo.</li>
                        <li>Proponen la vivencia del trabajo colaborativo y su valoración.</li>
                        <li>Integran diferentes áreas de conocimiento para la comprensión de los fenómenos naturales.</li>
						</ul>                        
                    </div>
                </div>
            </div>
            <div class="col-2-3  text-align">
                <img class="mb-3 cursor-pointer" ng-mouseover="icon_pedagogy = 'everyday_science'" ng-click="icon_pedagogy === 'everyday_science'" width="74px" height="auto" src="{{ asset('images/icons/cienciaCotidiana.png') }}">
				<span class="justify-content-center font-weight-bold d-flex mt-1 ml-auto mr-auto w-75"> Ciencia Cotidiana </span>
				  <div class="d-result d-none panel-icon-pedagogy fs--3 everyday_science_color" ng-show="icon_pedagogy==='everyday_science'">
					<div style="margin-left: 5vw;position: absolute;margin-top: -38px;">
					  <div style="border-width: 0px 17px 17px; border-style: solid;border-image: initial;border-color: #77A3A3 transparent;content: '';display: block;font-size: 0px;height: 0px;line-height: 0;position: absolute;top: 1px;width: 0px;left: -6px;">
					  </div>
				    </div>				
                    <div class="header">
                      <span class="everyday_science_color"> Ciencia cotidiana</span>
                    </div>
                    <div class="body">
                        <p>Uno de los hechos más valiosos de un viaje, es vivir por si mismos aquello que se ha oído, imaginado o escuchado  </p>
						<ul>
							<li>Presenta conocimientos propios de las ciencias de manera contextualizada, gradual y sencilla. </li>
							<li>La dosificación de contenidos permite a los estudiantes construir explicaciones cada vez más elaboradas sobre de los fenómenos estudiados. </li>
							<li>Integra conocimientos de diferentes áreas para la comprensión amplia de los fenómenos naturales.</li>
						</ul>
                    </div>
                </div>
            </div>
            <div class="col-2-3 text-align">
                <img class="mb-3 cursor-pointer" ng-mouseover="icon_pedagogy = 'more_conextion'" ng-click="icon_pedagogy === 'more_conextion'" width="74px" height="auto" src="{{ asset('images/icons/masConexiones.png') }}">
                <span class="justify-content-center font-weight-bold d-flex mt-1 ml-auto mr-auto w-75"> + Conexiones </span>
                <div class="d-result d-none panel-icon-pedagogy fs--3" ng-show="icon_pedagogy==='more_conextion'">
					<div style="margin-left: 5vw;position: absolute;margin-top: -38px;">
					  <div style="border-width: 0px 17px 17px; border-style: solid;border-image: initial;border-color: #77A3A3 transparent;content: '';display: block;font-size: 0px;height: 0px;line-height: 0;position: absolute;top: 1px;width: 0px;left: -6px;">
					  </div>
				    </div>				
                    <div class="header">
                      <span class="more_conextion_color">+ Conexiones</span>
                    </div>
                    <div class="body">
                        <p>Durante un viaje o después de este, se conocen nuevas personas, olores, sabores y lugares, en otras palabras se abren puertas a nuevos conocimientos que puede incitar la realización de un nuevo viaje</p>
						<p>Los recursos seleccionados posibilitan:</p>
						<ul>
                        <li>Profundizar en el conocimiento científico. </li>
                        <li>Estimular el estudio de los fenómenos naturales desde diferentes campos de saber.</li>
                        <li>Motivar el planteamiento de nuevas preguntas y estimular la indagación.</li>
						</ul>
                    </div>
                </div>
            </div>
            <div class="col-1-7"></div>
        </div>
    </div>
</div>
<script>
  $( document ).ready(function() {
     $('.d-result').removeClass('d-none');
  });  
</script>
@endsection