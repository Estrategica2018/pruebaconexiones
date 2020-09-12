@extends('layouts.app_side')
@section('content')
<div class="container" style="min-width:661px;"  ng-controller="pedagogyCtrl" ng-init="init()">
   <div class="p-3 border-lg-y w-100 h-100"
      style="min-height: 23vw; border: 0.4px solid grey; min-width: 100%" ng-hide="loadFinished">
      cargando...
   </div>
   <div class="background-sequence-card mt-3 ml-3 row" w="1519" h="4424" ng-show="loadFinished">
      <div class="d-none-result d-none">
         <div mt="0" ml="40" fs="30" class="boder-header p-2" >
            <h5 class="mb-0">Enfoque pedagógico</h5>
         </div>
         <div class="z-index-1" mt="80" ml="40">
            <img src="{{ asset('images/acercaConexiones/tituloMomento.jpg') }}"  w="562" h="auto"/>
         </div>
         <div class=""  mt="80" ml="660" w="762"  fs="18">
            <div class="font-weight-bold"><strong>Aprendizaje por indagación y desarrollo de pensamiento científicos</strong></div>
            <div class="text-justify mt-3" >
               <p>La propuesta educativa de <strong>Conexiones</strong> se sustenta en un enfoque pedagógico de aprendizaje por indagación,
                  que tiene la intención de promover en los niños, las niñas y los jóvenes, el desarrollo de pensamiento
                  científico. En coherencia con esto, las guías de aprendizaje se han diseñado bajo las siguientes
                  características comunes:
               </p>
               <ul>
                  <li class="mt-2">Parten de situaciones cotidianas que despiertan interés en las y los estudiantes.</li>
                  <li class="mt-2">Promueven la identificación de saberes de los y las estudiantes, para construir
                     progresivamente explicaciones más complejas a partir de estos.
                  </li>
                  <li class="mt-2">Plantean situaciones y problemas situados, para ser analizados desde el contexto local y
                     global.
                  </li>
                  <li class="mt-2">Formulan preguntas abiertas, sencillas y contextualizadas, con la intención de motivar
                     el interés de aprender y la curiosidad por indagar.
                  </li>
                  <li class="mt-2">Propone experiencias de aprendizaje que invitan al estudiante a desempeñar un rol activo, protagónico y creativo.
                  </li>
                  <li class="mt-2">involucran a las y los estudiantes en el diseño y desarrollo de prácticas experimentales.
                  </li>
                  <li class="mt-2">Proponen la vivencia del trabajo colaborativo y la valoración de este.</li>
                  <li class="mt-2">Promueven la integración de diferentes áreas de conocimiento para la comprensión amplia
                     de los fenómenos naturales.
                  </li>
                  <li class="mt-2">Presentan conocimientos propios de las ciencias de manera contextualizada, gradual y
                     sencilla.
                  </li>
                  <li class="mt-2">Fortalecen actitudes cuidadosas (de sí, del medio, de otros y otras) responsables y propositivas.</li>
                  <li class="mt-2">Estimulan la socialización de acciones y resultados.</li>
                  <li class="mt-2">Integran la evaluación como un proceso permanente, en el que los aciertos y errores se conciben como oportunidades para el aprendizaje.</li>
               </ul>
            </div>
         </div>
         <div mt="950" ml="40" fs="30" class="boder-header p-2">
            <h5 class="mb-0">Para saber más</h5>
         </div>
         <div mt="1020" ml="40" w="600" fs="18">
            <p class="text-justify">Si les interesa conocer más respecto a la indagación científica en la etapa escolar,
               perspectiva de enseñanza de las ciencias en la que se fundamenta pedagógicamente <strong>Conexiones</strong>, les
               recomendamos ver la siguiente charla de TED, en la que Melina Furman, expertaen didáctcia de las ciencias
               nos cuenta sobre las potencialidades que esta tiene.
            </p>
         </div>
         <div mt="990" ml="740" >
            <iframe w="700" h="441" src="https://www.youtube.com/embed/LFB9WJeBCdA" frameborder="0"
               allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
               allowfullscreen=""></iframe>
         </div>
         <div mt="1480" ml="40" fs="30" class="boder-header p-2 " >
            <h5>Estructura de las guías de aprendizaje</h5>
         </div>
         <div class="z-index-1" mt="1580" ml="60">
            <img src="{{ asset('images/acercaConexiones/estructura_guias_aprendizaje.png') }}"  w="562" h="auto"/>
         </div>
         <div class="text-justify"  mt="1580" ml="660" w="762"  fs="18">
            <p>Las guías de aprendizaje que hacen parte de <strong>Conexiones</strong>, usan la metáfora de recorrido o
               camino para organizar y presentar los contenidos que los y las estudiantes son invitados a
               explorar. A diferencia de los recursos educativos convencionales, nuestras guías de aprendizaje
               no se diseñan a partir de una temática, ni restringen los contenidos a un único campo de saber
               (biología, física, química o tecnología…); por el contrario, el abordaje de los fenómenos
               naturales se hace desde una perspectiva interdisciplinar, que permite establecer múltiples
               relaciones entre estos para así ampliar las fronteras del conocimiento.
            </p>
         </div>
         <div mt="2340" ml="40" fs="30" class="boder-header p-2 " >
            <h5>Situación generadora</h5>
         </div>
         <div class="text-justify"  mt="2420" ml="60" w="600"  fs="18">
            <p>En lugar de proponer objetivos anclados a conceptos, cada guía de aprendizaje inicia con la descripción
               de una situación generadora
               o de interés, que tiene la intención pedagógica de movilizar la curiosidad de los estudiantes y las
               estudiantes para indagar y
               aprender.
            </p>
            <p>A continuación podrán apreciar como ejemplo, la situación generadora de la guía de aprendizaje: Las
               Astroaventuras de Yotopó y la
               cápsula del tiempo, en la que se invita a los niños, niñas y jóvenes a observar los movimientos del Sol,
               la Luna y las estrellas, para
               deducir la relación que estos fenómenos naturales tienen con la medida del tiempo.
            </p>
         </div>
         <div mt="2340" ml="740" >
            <iframe w="700" h="441" src="https://www.youtube.com/embed/u1iBFJIsIhw" frameborder="0"
               allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
               allowfullscreen=""></iframe>
         </div>
         <div mt="2800" ml="40" class="boder-header p-2 " >
            <h5>Ruta de viaje</h5>
         </div>
         <div class="z-index-1" mt="2870" ml="40">
            <img src="{{ asset('images/acercaConexiones/Ruta_de_viaje.png') }}"  w="762" h="auto"/>
         </div>
         <div mt="3370" ml="40" w="762">
            <span class="text-justify"  fs="14" style="font-size: 9px;display: inline-flex;"> Este es el ejemplo de la ruta de viaje de la
            guía de aprendizaje: Nuestro cuerpo: vida y movimiento, que está orientada a reconocer cómo funciona el
            cuerpo, partes y procesos vitales que desempeñan, valorarlo y tomar decisiones para su cuidado
            </span>
         </div>
         <div class="text-justify"  mt="2870" ml="830" w="600"  fs="18">
            <p>Cada guía de aprendizaje propone seguir una ruta de viaje organizada en ocho momentos o etapas
               secuenciales que, en conjunto,
               permiten ampliar la comprensión de los fenómenos naturales tratados y la conexión de estos con el mundo
               de la vida. Los momentos
               de la ruta están articulados a un punto de encuentro, que constituye el propósito común articulador de
               todos los contenidos.
            </p>
         </div>
         <div mt="3470" ml="40" fs="30" class="boder-header p-2 " >
            <h5>Guía de saberes</h5>
         </div>
         <div mt="3550" ml="60" w="600" fs="18" class="text-justify">
            <p>Algo a tener en cuenta es que durante el desarrollo de cada guía de aprendizaje, las y los estudiantes
               despliegan un conjunto de
               acciones de pensamiento y producción que constituyen las evidencias de aprendizaje, y se reconocen por la
               presencia de los
               siguientes íconos:
            </p>
            <p><strong>Saber qué:</strong> manejo de conocimientos propios de las ciencias</p>
            <p><strong>Saber cómo:</strong> aproximación al conocimiento como lo hacen quienes se
               dedican a las ciencias (Saber cómo)
            </p>
            <p><strong  >Saber ser:</strong> desarrollo de compromisos personales y sociales.</p>
         </div>
         <div class="z-index-1" mt="3440" ml="740">
            <img src="{{ asset('images/acercaConexiones/Guias_de_saberes.png') }}"  w="762" h="auto"/>
         </div>
         <div mt="3940" ml="750" w="700" class="text-justify">
            <span class="" fs="14"  style="font-size: 9px;display: inline-flex;"> Este es el ejemplo de algunos de los saberes
            que implican el desarrollo de la guía de aprendizaje: Agua para todos, a través de la cual
            se puede comprender función que tiene el agua en el sostenimiento de todos los seres vivos y promover
            acciones individuales y
            colectivas para su preservación.</span>
         </div>
         <div mt="4000" ml="40" w="1470" class="col-12 mt-3" fs="18">
            <p class="mt-3 text-justify">Cada momento de la ruta de viaje está conformado por: una pregunta central, de la que se derivan experiencias
               científicas, explicaciones de ciencia cotidiana y recursos recomendados para <strong>+conexiones</strong>, que movilizan la activación de
               diferentes acciones de pensamiento y producción esenciales para que las niñas, niños y jóvenes desarrollen pensamiento científico.
            </p>
            <p>Los siguientes iconos permiten identificar la ubicación y relación de cada momento de aprendizaje <strong>(Ver + haciendo clic en cada ícono)</strong></p>
            <div class="row mt-4" fs="18">
               <div class="col-3  text-align">
                  <img class="mb-3 cursor-pointer" ng-click="setIconPedagogy('central_question')" w="74" h="auto" src="{{ asset('images/icons/preguntaCentral.png') }}">
                  <span class="font-14px justify-content-center font-weight-bold d-flex mt-1 ml-auto mr-automl-1 w-75" ml="80"  mt="75" fs="18">  Pregunta Central </span>
                  <div class="d-none-result d-none panel-icon-pedagogy fs--3 mt-3" mt="120" ng-show="icon_pedagogy==='central_question'">
                     <div style="margin-left: 9vw;position: absolute;margin-top: -38px;">
                        <div style="border-width: 0px 17px 17px; border-style: solid;border-image: initial;border-color: #ec6625 transparent;content: '';display: block;font-size: 0px;height: 0px;line-height: 0;position: absolute;top: 1px;width: 0px;left: -6px;">
                        </div>
                     </div>
                     <div class="header">
                        <span class="central_question_color">Pregunta central</span>
                     </div>
                     <div class="body">
                        <p>Una pregunta puede motivarnos a viajar… para responderla podemos recorrer diferentes caminos </p>
                        <ul>
                           <li>Permite movilizar y reconocer diferentes saberes previos para construir progresivamente
                              explicaciones más complejas a partir de estos. 
                           </li>
                           <li>Tiene el propósito de promover la indagación y curiosidad científica. </li>
                           <li>Es formulada de manera abierta, sencilla y contextualizada. </li>
                           <li>Constituye el eje sobre el que se despliegan los contenidos de cada momento. </li>
                        </ul>
                     </div>
                  </div>
               </div>
               <div class="col-3  text-align">
                  <img class="mb-3 cursor-pointer" ng-click="setIconPedagogy('scientific_experience')" w="74" h="auto" src="{{ asset('images/icons/iconoExperiencia.png') }}">
                  <span class="font-14px justify-content-center font-weight-bold d-flex mt-1 ml-auto mr-auto ml-1 w-75" ml="80"  mt="75" fs="18">  Experiencia Científica </span>
                  <div class="d-none-result d-none panel-icon-pedagogy-green fs--3 mt-3" mt="120" ng-show="icon_pedagogy==='scientific_experience'">
                     <div style="margin-left: 9vw;position: absolute;margin-top: -38px;">
                        <div style="border-width: 0px 17px 17px; border-style: solid;border-image: initial;border-color: #95c11f transparent;content: '';display: block;font-size: 0px;height: 0px;line-height: 0;position: absolute;top: 1px;width: 0px;left: -6px;">
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
               <div class="col-3  text-align">
                  <img class="mb-3 cursor-pointer" ng-click="setIconPedagogy('everyday_science')" w="74" h="auto" src="{{ asset('images/icons/cienciaCotidiana.png') }}">
                  <span class="font-14px justify-content-center font-weight-bold d-flex mt-1 ml-auto mr-auto ml-1 w-75" ml="80"  mt="75" fs="18">  Ciencia Cotidiana </span>
                  <div class="d-none-result d-none panel-icon-pedagogy-blue fs--3 mt-3 everyday_science_color" mt="120" ng-show="icon_pedagogy==='everyday_science'">
                     <div style="margin-left: 9vw;position: absolute;margin-top: -38px;">
                        <div style="border-width: 0px 17px 17px; border-style: solid;border-image: initial;border-color: #00a4d6 transparent;content: '';display: block;font-size: 0px;height: 0px;line-height: 0;position: absolute;top: 1px;width: 0px;left: -6px;">
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
               <div class="col-3  text-align">
                  <img class="mb-3 cursor-pointer" ng-click="setIconPedagogy('more_conextion')" w="74" h="auto" src="{{ asset('images/icons/masConexiones.png') }}">
                  <span class="font-14px justify-content-center font-weight-bold d-flex mt-1 mr-auto ml-1 w-75" ml="80"  mt="75" fs="18">  + Conexiones </span>
                  <div class="d-none-result d-none panel-icon-pedagogy-beige fs--3" mt="140" ng-show="icon_pedagogy==='more_conextion'">
                     <div style="margin-left: 9vw;position: absolute;margin-top: -38px;">
                        <div style="border-width: 0px 17px 17px; border-style: solid;border-image: initial;border-color: #702283 transparent;content: '';display: block;font-size: 0px;height: 0px;line-height: 0;position: absolute;top: 1px;width: 0px;left: -6px;">
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
            </div>
         </div>
      </div>
   </div>
</div>
<script src="{{ asset('angular/controller/pedagogyCtrl.js') }}" defer></script>
@endsection