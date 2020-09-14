@extends('layouts.app_side')

@section('content')

@include('layouts/float_buttons')

<div ng-controller="sequencesSearchCtrl" ng-init="init(1)">
   <div ng-show="errorMessageFilter"
      id="errorMessageFilter" 
      class="fade-message d-none-result d-none alert alert-danger p-1 pl-2 row">
      <span class="col">@{{ errorMessageFilter }}</span>
      <span class="col-auto"><a ng-click="errorMessageFilter = null"><i class="far fa-times-circle"></a></i></span>
   </div>
   <div class="mb-3 card">
      <div class="card-body">
         <div class="no-gutters row">
            <div class="mb-3 col-12">
               <div class="justify-content-center justify-content-sm-between row">
                  <div class="text-center col-sm-auto boder-header p-2 ml-3">
                     <h5 class="d-inline-block">Guías de aprendizaje</h5>
                  </div>
               </div>
            </div>
            <div class="pr-lg-2 col-lg-12 text-justify">
            
               <p>Todas las <strong>guías de aprendizaje</strong> que hacen parte de <strong>Conexiones</strong>, usan como metáfora para estructurar sus contenidos la idea de viaje, como una invitación para explorar, conocer y comprender a través de la indagación el mundo natural del que hacemos parte. Así, cada <strong>guía de aprendizaje</strong>&nbsp; está compuesta por una <strong>situación generadora</strong> o punto de partida, un <strong>mapa de ruta</strong> flexible, un entramado <strong>saberes </strong>a desarrollar y un <strong>punto de encuentro</strong> o propósito, que integra un conjunto amplio de recursos didácticos orientados al desarrollo de pensamiento científico. </p>
               <p> Las <strong>guías de aprendizaje</strong> proponen una ruta de <strong>ocho momentos </strong>&nbsp;o estaciones que se componen &nbsp;a su vez de preguntas, experiencias científicas, explicaciones en contexto y enlaces sugeridos para más conexiones. <strong>(Ver + haciendo clic en cada ícono)</strong></p>
               <ul class="nav row fs--1 text-align mt-1">
                  <li class="ml-auto" >
                    <img icon-pedagogy src="{{asset('images/icons/situacionGeneradora.png')}}" width="74px" height="auto" ng-click="onIconPedagogy('pedagogy1')"  class="cursor-pointer">
                    <span class="d-flex mt-1 ml-auto mr-auto w-75 font-weight-bold"> Situación generadora </span>
                    <div class="panel-icon-pedagogy-purple d-none-result d-none fs--3 position-absolute" ng-show="icon_pedagogy==='pedagogy1'" style="background-color: white; z-index:102;">
                        <div style="margin-left: -1vw;position: absolute;margin-top: -38px;">
                          <div style="border-width: 0px 17px 17px; border-style: solid;border-image: initial;border-color: #b92f7f transparent;content: '';display: block;font-size: 0px;height: 0px;line-height: 0;position: absolute;top: 1px;width: 0px;left: 43px;">
                          </div>
                        </div>  
                        <div class="header">
                          <span>Situación Generadora</span>
                          
                        </div>            
                        <div class="body">
                            <p>Todo viaje inicia con un impulso, un deseo o una motivación.</p>
                            <ul>
                            <li>Plantean situaciones y problemas contextualizados para ser analizados desde el contexto local y global.</li>
                            <li>Estimulan la curiosidad para indagar</li>
                            <li>Incluye preguntas abiertas que motiven el interés de aprender</li>
                            <li>Constituye el eje articulador de todos los momentos y contenidos de la guías de aprendizaje.</li>
                            </ul>
                        </div>
                    </div>
                  </li>
                  <li class="ml-auto" >
                    <img icon-pedagogy src="{{asset('images/icons/rutaViaje.png')}}" width="74px" height="auto"  ng-click="onIconPedagogy('pedagogy2')"  class="cursor-pointer">
                    <span class="d-flex mt-1 ml-auto mr-auto w-75 font-weight-bold"> Ruta de viaje </span>

                    <div class="panel-icon-pedagogy-yellow d-none-result d-none fs--3 position-absolute" ng-show="icon_pedagogy==='pedagogy2'" style="background-color: white; z-index:102;">
                        <div style="margin-left: 5vw;position: absolute;margin-top: -38px;">
                          <div style="border-width: 0px 17px 17px; border-style: solid;border-image: initial;border-color: #eaa224 transparent;content: '';display: block;font-size: 0px;height: 0px;line-height: 0;position: absolute;top: 1px;width: 0px;left: -23px;">
                          </div>
                        </div>                
                        <div class="header">
                          <span>Ruta de viaje</span>
                        </div>
                        <div class="body">
                            <p>Saber a dónde ir requiere pensar en una ruta o itinerario para trazar caminos hacia varios destinos</p>
                            <p> Aunque hay una ruta trazada, tanto los y las estudiantes como el profesorado pueden elegir hacer el recorrido en el orden propuesto o de manera aleatoria, pues aunque los momentos de cada guía de aprendizaje se relacionan entre sí, cada uno puede abordarse independientemente.</p>
                            <p> De esta manera, quienes hacen uso de Conexiones tienen la posibilidad de aprovechar el material de acuerdo con los intereses personales o el plan de estudios.</p>
                        </div>
                    </div>
                    </li>
                  <li class="ml-auto" >
                    <img icon-pedagogy src="{{asset('images/icons/puntoEncuentro.png')}}" width="74px" height="auto"  ng-click="onIconPedagogy('pedagogy3')"  class="cursor-pointer">
                    <span class="d-flex mt-1 ml-auto mr-auto w-75 font-weight-bold"> Punto de encuentro </span>
                    <div class="panel-icon-pedagogy-guava d-none-result d-none fs--3 position-absolute" ng-show="icon_pedagogy==='pedagogy3'" style="background-color: white; z-index:102;">
                        <div style="margin-left: 5vw;position: absolute;margin-top: -38px;">
                          <div style="border-width: 0px 17px 17px; border-style: solid;border-image: initial;border-color: #f05e5b transparent;content: '';display: block;font-size: 0px;height: 0px;line-height: 0;position: absolute;top: 1px;width: 0px;left: -23px;">
                          </div>
                        </div>                
                        <div class="header">
                          <span>Punto de encuentro</span>
                        </div>
                        <div class="body">
                            <p>Los viajes tienen un propósito u objetivo, pero en el recorrido se pueden vivir otras experiencias de las cuales emergen otras enseñanzas y aprendizajes. </p>
                            <p>Es el propósito articulador de todos los contenidos y momentos que constituyen la guía de aprendizaje. Se caracteriza por ser amplio y por integrar  en su desarrollo diferentes saberes (Saber qué, saber cómo, Saber por qué, saber ser).</p>
                        </div>
                    </div>
                  </li>
                  <li class="ml-auto" >
                    <img icon-pedagogy src="{{asset('images/icons/GuiaSaberes.png')}}" width="74px" height="auto"  ng-click="onIconPedagogy('pedagogy4')"  class="cursor-pointer">
                    <span class="d-flex mt-1 ml-auto mr-auto w-75 font-weight-bold"> Guía de saberes </span>
                    <div class="panel-icon-pedagogy-orange d-none-result d-none fs--3 position-absolute" ng-show="icon_pedagogy==='pedagogy4'" style="background-color: white; z-index:102;">
                        <div style="margin-left: 5vw;position: absolute;margin-top: -38px;">
                          <div style="border-width: 0px 17px 17px; border-style: solid;border-image: initial;border-color: #0ccbea transparent;content: '';display: block;font-size: 0px;height: 0px;line-height: 0;position: absolute;top: 1px;width: 0px;left: -23px;">
                          </div>
                        </div>                
                        <div class="header">
                          <span>Guía de saber</span>
                        </div>
                        <div class="body">
                            <p>Algunos viajes se planean con el propósito de lograr algún objetivo pero en el recorrido se pueden vivir experiencias no planeadas de las cuales emergen otras enseñanzas»</p>
                            <p>Cada guía de aprendizaje propone el despliegue e integración de un conjunto de acciones de pensamiento y producción que hacen parte de los tres ejes articuladores que las estructuran:</p>
                            <ul>
                            <li>Aproximación al conocimiento como científicos</li>
                            <li>Manejo de conocimientos propios de las ciencias, y</li>
                            <li>Desarrollo de compromisos personales y sociales.</li>
                            </ul>
                            <p>Así, en las primeras páginas de la guía, tanto el profesorado como los y las estudiantes pueden identificar fácilmente lo que se espera que aprendan durante el desarrollo de estas. 
                            </p>
                        </div>
                    </div>
                  </li>
                  <li class="ml-auto" >
                    <img icon-pedagogy src="{{asset('images/icons/preguntaCentral.png')}}" width="74px" height="auto"  ng-click="onIconPedagogy('pedagogy5')"  class="cursor-pointer">
                    <span class="d-flex mt-1 ml-auto mr-auto w-75 font-weight-bold"> Pregunta Central </span>
                    <div class="panel-icon-pedagogy d-none-result d-none fs--3 position-absolute" ng-show="icon_pedagogy==='pedagogy5'" style="background-color: white; z-index:102;">
                        <div style="margin-left: 5vw;position: absolute;margin-top: -38px;">
                          <div style="border-width: 0px 17px 17px; border-style: solid;border-image: initial;border-color: #ec6625 transparent;content: '';display: block;font-size: 0px;height: 0px;line-height: 0;position: absolute;top: 1px;width: 0px;left: -23px;">
                          </div>
                        </div>                
                        <div class="header">
                          <span>Pregunta central</span>
                        </div>
                        <div class="body">
                            <p>Una pregunta puede motivarnos a viajar…  para responderla podemos recorrer diferentes caminos</p>
                            <ul>
                            <li>Permite movilizar y reconocer diferentes saberes previos para construir progresivamente explicaciones más complejas a partir de estos.</li>
                            <li>Tiene el propósito de promover la indagación y curiosidad científica.</li>
                            <li>Es formulada de manera abierta, sencilla y contextualizada.</li>
                            <li>Constituye el eje sobre el que se despliegan los contenidos de cada momento.</li>
                            </ul>
                        </div>
                    </div>
                  </li>
                  <li class="ml-auto" >
                    <img icon-pedagogy src="{{asset('images/icons/iconoExperiencia.png')}}" width="74px" height="auto"  ng-click="onIconPedagogy('pedagogy6')"  class="cursor-pointer">
                    <span class="d-flex mt-1 ml-auto mr-auto w-75 font-weight-bold"> Experiencia Científica </span>
                    <div class="panel-icon-pedagogy-green d-none-result d-none fs--3 position-absolute" ng-show="icon_pedagogy==='pedagogy6'" style="background-color: white; z-index:102;">
                        <div style="margin-left: 5vw;position: absolute;margin-top: -38px;">
                          <div style="border-width: 0px 17px 17px; border-style: solid;border-image: initial;border-color: #95c11f transparent;content: '';display: block;font-size: 0px;height: 0px;line-height: 0;position: absolute;top: 1px;width: 0px;left: -23px;">
                          </div>
                        </div>                
                        <div class="header">
                          <span>Experiencia científica</span>
                        </div>
                        <div class="body">
                            <p>Un viaje es un conjunto de experiencias, pues más allá de los destinos y lugares que se visitan es lo que se vive en ellos lo que permanece.</p>
                            <ul>
                                <li>Están diseñadas para que las y los estudiantes tengan un rol activo, protagónico y propositivo.</li>
                                <li>Crean condiciones de posibilidad para el diálogo.</li>
                                <li>Proponen la vivencia del trabajo colaborativo y su valoración.</li>
                                <li>Integran diferentes áreas de conocimiento para la comprensión de los fenómenos naturales.</li>
                            </ul>
                        </div>
                    </div>
                  </li>
                  <li class="ml-auto" >
                    <img icon-pedagogy id="pedagogy7" src="{{asset('images/icons/cienciaCotidiana.png')}}" width="74px" height="auto"  ng-click="onIconPedagogy('pedagogy7')" class="cursor-pointer">
                    <span class="d-flex mt-1 ml-auto mr-auto w-75 font-weight-bold"> Ciencia cotidiana </span>
                    <div class="panel-icon-pedagogy-blue d-none-result d-none fs--3 position-absolute" ng-show="icon_pedagogy==='pedagogy7'" style="background-color: white; z-index:102;">
                        <div style="margin-left: 11vw;position: absolute;margin-top: -38px;">
                          <div style="border-width: 0px 17px 17px; border-style: solid;border-image: initial;border-color: #00a4d6 transparent;content: '';display: block;font-size: 0px;height: 0px;line-height: 0;position: absolute;top: 1px;width: 0px;left: -23px;">
                          </div>
                        </div>                
                        <div class="header">
                          <span>Ciencia cotidiana</span>
                        </div>
                        <div class="body">
                            <p>Uno de los hechos más valiosos de un viaje, es vivir por si mismos aquello que se ha oído, imaginado o escuchado </p>
                            <ul>
                                <li>Presenta conocimientos propios de las ciencias de manera contextualizada, gradual y sencilla. </li>
                                <li>La dosificación de contenidos permite a los estudiantes construir explicaciones cada vez más elaboradas sobre de los fenómenos estudiados;</li>
                                <li>Integra conocimientos de diferentes áreas para la comprensión amplia de los fenómenos naturales.</li>
                            </ul>
                        </div>
                    </div>
                  </li>
                  <li class="ml-auto mr-auto" >
                    <img icon-pedagogy id="pedagogy8" src="{{asset('images/icons/masConexiones.png')}}" width="74px" height="auto"  ng-click="onIconPedagogy('pedagogy8')" class="cursor-pointer">
                    <span class="d-flex mt-1 ml-auto mr-auto w-100 font-weight-bold"> + Conexiones </span>
                    <div class="panel-icon-pedagogy-beige d-none-result d-none fs--3 position-absolute" ng-show="icon_pedagogy==='pedagogy8'" style="background-color: white; z-index:102;">
                        <div style="margin-left: 19vw;position: absolute;margin-top: -38px;">
                          <div style="border-width: 0px 17px 17px; border-style: solid;border-image: initial;border-color: #702283 transparent;content: '';display: block;font-size: 0px;height: 0px;line-height: 0;position: absolute;top: 1px;width: 0px;left: -23px;">
                          </div>
                        </div>                
                        <div class="header">
                          <span>+ Conexiones</span>
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
                  </li>
               </ul>
            </div>
            <div class="mb-1 mt-3 col-12 mt-2">
                Es importante tener en cuenta que si bien hay <strong>una ruta de viaje</strong> trazada,
                en <strong>Conexiones</strong> se puede elegir hacer el recorrido en el <strong>orden</strong> propuesto o de
                manera aleatoria, pues aunque los <strong>momentos</strong> de cada <strong>guía de aprendizaje</strong> se
                relacionan entre sí, cada uno puede abordarse independientemente.
                <p>
                De esta manera, <strong>Conexiones</strong> tiene una <strong>estructura flexible</strong> que se adapta a
                diversos <strong>intereses de aprendizaje</strong>,<strong> planes de estudio </strong>y disponibilidad de <strong>tiempo</strong>, dando
                la opción de elegir entre las siguientes <strong>opciones</strong>:
                </p>
                <ul>
                    <li><strong>Guía de aprendizaje completa</strong> con sus <strong>ocho momentos</strong>, cada uno con
                    preguntas exploradoras de saberes, experiencias científicas, explicaciones de
                    ciencias en contexto e ideas para + conexiones.
                    </li>
                    <li><strong>Uno</strong> o <strong>varios momentos</strong> o etapas de una misma guía de aprendizaje o de varias.
                    </li>
                    <li> <strong>Una</strong> o <strong>varias experiencias</strong> científicas, con los videos orientadores para su
                    realización.
                    </li>
                </ul>
                <p>
                    Haciendo clic en la guía de interés, puede consultar el detalle de los contenidos y las <strong>opciones de compra</strong> disponibles.
                </p>
                <p>
                    Si tiene dudas o sugerencias, <a target="_blank" href="{{route('contactus')}}"> <strong class="text-underline">contáctenos</strong></a> y con gusto le llamaremos para darle más detalles y ofrecerle la mejor opción de acuerdo sus expectativas.
                </p>
            </div>
            <div class="mb-1 mt-3 col-12 mt-2">
               <div class="justify-content-center justify-content-sm-between row">
                  <div class="ml-auto col-sm-auto p-2 ml-3">
                     Buscar por
                  </div>
                  <div class="d-none-result d-none d-flex flex-center mt-sm-0 col-sm-auto" id="divSearch">
                     <input ng-click="setPositionScroll()" ng-change="onSeachChange()" placeholder="Palabra clave..." aria-label="Search" 
                        type="search" ng-model="searchText" name="searchText" id="searchText"
                        ng-keyup="complete($event, searchText)"
                        ng-blur = "fillTextbox($event,searchText)"
                        class="fs-lg--1 fs-md--1 mr-2 rounded-pill search-input form-control">
                     <div ng-show="wordList.length > 0" tabindex="-1" role="menu" aria-hidden="false" 
                        class="d-none-result d-none dropdown-menu-card dropdown-menu dropdown-menu-left show" style="left: 28px;">
                        <div class="modal-menu shadow-none card py-2" style="max-width: 20rem;">
                           <a href="#!" tabindex="0" role="menuitem" 
                              id="keywordlist"
                              class="dropdown-item fs-lg--1 fs-md--1" 
                              ng-if = "$index < 10"
                              ng-repeat="keyword in wordList">@{{keyword}}</a>
                        </div>
                     </div>
                     <select ng-click="setPositionScroll()" ng-change="onThemeChange()" ng-model="themeName" class="custom-select custom-select-sm fs-lg--1 fs-md--1">
                        <option value="">Todos los temas</option>
                        <option value="@{{theme}}" ng-repeat="theme in themesList">@{{theme}}</option>
                     </select>
                     <select ng-click="setPositionScroll()" ng-change="onAreaChange()" ng-model="areaName" class="ml-2 custom-select custom-select-sm fs-lg--1 fs-md--1">
                        <option value="">Todas las áreas</option>
                        <option class=" " value="@{{area}}" ng-repeat="area in areas">@{{area}}</option>
                     </select>
                  </div>
               </div>
            </div>
            <div class="d-none-result d-none row w-100 p-3">
               <div class="col-lg-6 ml-2 pr-2 border-white-extent card card-body bg-dark row d-flex" ng-repeat="sequence in sequences | filter: searchText">
                  <div class="view" id="sequence-description-@{{sequence.id}}">
                     <div class="media">
                        <img ng-src="{{asset('/')}}@{{sequence.url_image}}" width="142px" height="auto" style="width:142px"/> 
                        <div class="media-body pl-2 pr-3">
                           <h5 class="pl-2 fs-0 boder-header text-align-left">@{{sequence.name}}</h5>
                           <div class="mt-3 pr-2 pl-2 fs--1" style="min-height: 110px;">@{{sequence.description}}</div>
                           <div class="mt-2">
                              <a class="btn btn-sm btn-outline-primary" ng-href="/guia_de_aprendizaje/@{{sequence.id}}/@{{sequence.name_url_value}}">
                              <span class="fs--1">Detalle</span></a>
                              <a ng-click="onSequenceBuy(sequence)" class="ml-2  btn-sm btn btn-outline-primary" href="#" class="col-auto">
                              <span class="fs--1">
                              <i class="fas fa-shopping-cart"></i> Comprar</span></a>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="p-3 border-lg-y col-lg-2 w-100" style="min-height: 23vw; border: 0.4px solid grey; min-width: 100%" ng-hide="sequences.length > 0">
               cargando...
            </div>
            <div class="d-none-result d-none p-3 border-lg-y col-lg-2 w-100" style="min-height: 23vw; border: 0.4px solid grey; min-width: 100%" ng-show="searchText.length > 0 && sequences.length === 0">
               No se encontraron guías que coincidan con la búsqueda @{{searchText}}
            </div>
         </div>
      </div>
   </div>
</div>
<script src="{{ asset('/../angular/controller/sequencesSearchCtrl.js') }}" defer></script>
<style type="text/css">
   .list-group-item:hover{
   color: #337ab7;
   text-shadow:  0 0 1em #337ab7;
   cursor: pointer;
   }
</style>
@endsection