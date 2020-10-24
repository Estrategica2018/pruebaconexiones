@extends('roles.student.sequences_layout')

@section('content')
    <div class="container"  ng-controller="contentSequencesStudentCtrl" 
            ng-init="init({{auth('afiliadoempresa')->user()->company_id()}},'{{auth('afiliadoempresa')->user()->company_name()}}',{{$sequence->id}},{{$account_service_id}})">
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                    @include('roles/student/timeline_sequences')
                </div>
                <div class="col-md-3 open" id="sidemenu-sequences"  >
                    <div class="mb-3 card fade show" id="sidemenu-sequences-content">
                        @if(isset($order_moment_id))
                            @include('roles/student/sidebar_moment')
                        @else 
                            @include('roles/student/sidebar_sequences')
                        @endif
                    </div>
                    <div class="h-75 mb-3 fade show d-none card w-10" id="sidemenu-sequences-empty">
                    </div>
                    <div class="d-none d-md-block text-sans-serif dropdown position-absolute cursor-pointer" style="top: 91px; right:7px;" ng-click="toggleSideMenu()">
                        <i class="far fa-caret-square-left" id="sidemenu-sequences-button"></i>
                    </div>
                </div>
                <div id="loading" class="modal-backdrop position-absolute h-100 w-100 background-white p-auto m-auto text-align card" style="padding: 25%; height: 0px;">
                    Cargando...
                </div>
                <div class="col-md-9" id="content-section-sequences">
                    
                   <div class="d-result d-none">
                        @if (isset($success))
                        <div class="fade-message alert alert-success" role="alert" id="alert1" >
                           @{{ $success }}
                           <button type="button" class="close" aria-label="Close" on-click="alert(document.getElementById('alert1'))">
                              <span aria-hidden="true">&times;</span>
                           </button>
                        </div>
                        @endif
                        @if (isset($errorMessage))
                        <div class="fade-message alert alert-danger" role="alert" id="alert2" >
                           @{{ $errorMessage }}
                           <button type="button" class="close" aria-label="Close" on-click="alert(document.getElementById('alert2'))">
                              <span aria-hidden="true">&times;</span>
                           </button>
                        </div>
                        @endif
                        <div ng-show="errorMessage" class="fade-message d-none-result d-none alert alert-danger p-1 pl-2 row">
                         <span class="col">@{{ errorMessage }}</span>
                         <span class="col-auto"><a ng-click="errorMessage = null"><i class="far fa-times-circle"></a></i></span>
                        </div>

                        

                        <div class="mb-3 card background-sequence-card" w="{{$container['w']}}" h="{{$container['h']}}">
                            
                            <div class="d-none-result d-none">
                                @if(isset($background_image))
                                <img src="{{asset($background_image)}}" class="background-sequence-image"/>
                                @endif
                                <div class="card-body pb-0">
                                  @if(isset($elements))
                                  @foreach($elements as $element)
                                    @if($element['type'] == 'text-element' || $element['type'] == 'text-area-element')
                                       <div ng-style="{@if(isset($element['color'])) 'color': '{{$element['color']}}', @endif @if(isset($element['background_color'])) 'background-color': '{{$element['background_color']}}', @endif}" 
                                            style="@if(isset($element['style'])) {{$element['style']}} @endif"
                                            class="@if(isset($element['class'])){{ $element['class']}} @endif p-0 font-text card-body" w="{{$element['w']}}" h="{{$element['h']}}" mt="{{$element['mt']}}" ml="{{$element['ml']}}" fs="{{$element['fs']}}">
                                        {!! $element['text'] !!}
                                       </div>
                                    @endif
                                    @if($element['type'] == 'image-element')
                                        <div class="z-index-1" mt="{{$element['mt']}}" ml="{{$element['ml']}}">
                                            <img class="@if(isset($element['class'])){{ $element['class']}} @endif"
                                            style="@if(isset($element['style'])) {{$element['style']}} @endif"
                                            src="{{asset($element['url_image'])}}" w="{{$element['w']}}" h="{{$element['h']}}"/>
                                        </div>    
                                    @endif
                                    @if($element['type'] == 'video-element' && isset($element['url_vimeo']))
                                       <div class="z-index-2" mt="{{$element['mt']}}" ml="{{$element['ml']}}">
                                            <iframe src="{{$element['url_vimeo']}}" w="{{$element['w']}}" h="{{$element['h']}}" frameborder="0" 
                                            style="@if(isset($element['style'])) {{$element['style']}} @endif"
                                            class="@if(isset($element['class'])){{ $element['class']}} @endif"
                                            webkitallowfullscreen="false" mozallowfullscreen="false" allowfullscreen="false">
                                            </iframe>
                                        </div>
                                    @endif
                                    @if($element['type'] == 'button-element')
                                        <button 
                                        @if(isset($element['action']))
                                          onclick="location='{{route('student.show_moment_section',
                                          [ 'empresa'=>auth('afiliadoempresa')->user()->company_name(), 
                                            'sequence_id'=>$sequence->id, 
                                            'moment_id'=>explode('|',$element['action'])[1],
                                            'order_moment_id'=>explode('|',$element['action'])[2],
                                            'account_service_id'=>$account_service_id,
                                          ])}}'"
                                          conx-action="{{$element['action']}}"
                                          disabled
                                          class="{{$element['class']}} cursor-pointer button-moment-validate cursor-not-allowed"
                                        @else
                                          class="{{$element['class']}} cursor-pointer"
                                        @endif
                                          style="
                                          @if(isset($element['background_color'])) 
                                            background-color: {{$element['background_color']}}  
                                          @endif
                                          @if(isset($element['color'])) 
                                            background-color: {{$element['color']}}  
                                          @endif
                                          @if(isset($element['style'])) {{$element['style']}} @endif"
                                          ml="{{$element['ml']}}" mt="{{$element['mt']}}" w="{{$element['w']}}" h="{{$element['h']}}">
                                                                                  
                                        </button>
                                        @if(isset($element['action']))
                                        <span ml="{{$element['ml']}}" mt="{{$element['mt']}}" class="not-allowed">
                                            No tiene permisos
                                        </span>
                                        @endif
                                    @endif
                                    @if($element['type'] == 'evidence-element')
                                    <div ml="{{$element['ml']}}" mt="{{$element['mt']}}" class="z-index-10">
                                        <div id="{{$element['id']}}" class="{{$element['class']}} evidence-head cursor-pointer" 
                                           ng-style="{@if(isset($element['color'])) 'color': '{{$element['color']}}', @endif @if(isset($element['background_color'])) 'background-color': '{{$element['background_color']}}', @endif}" 
                                           style="@if(isset($element['style'])) {{$element['style']}} @endif"
                                           w="{{$element['w']}}" h="{{$element['h']}}" fs="{{$element['fs']}}"
                                           ng-click="onClickEvidence('{{$sequence_id}}','{{$moment->id}}','{{$section_id}}','{{$element['experience_id']}}','@if(isset($element['icon'])){{$element['icon']}}@endif','@if(isset($element['subtitle'])){{$element['subtitle']}}@endif','{{$part_id}}')">
                                           @if(isset($element['icon']))
                                           <img src="{{asset($element['icon'])}}" width="auto" height="40px"/>
                                           @else 
                                           <img src="{{asset('images/icons/evidenciasAprendizajeIcono-01.png')}}" width="auto" height="40px"/>
                                           @endif
                                           <span class="d-none ml-3"><i style="width: 30px;height: auto;" class="fa fa-spinner fa-spin"></i></span>
                                           {{$element['text']}}
                                         </div>
                                    </div>
                                    @endif
                                  @endforeach
                                  @endif
                                </div>   
                            </div>

                            @if(isset($part_id) && isset($section_id) && isset($sections[$section_id-1]['part_'.($part_id - 1)]))
                            <a class="btn btn-sm btn-primary z-index-2" ml="9%" mt="{{$container['h'] - 50 }}"
                               href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($section_id),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id,'part_id'=>($part_id -  1)])}}"> Parte {{$part_id -1}}</a>
                            @endif
                            @if(isset($part_id) && isset($section_id) && isset($sections[$section_id-1]['part_'.($part_id + 1)]['elements']) && count($sections[$section_id-1]['part_'.($part_id + 1)]['elements']) > 0)
                            <a class="btn btn-sm btn-primary z-index-2" ml="83%" mt="{{$container['h'] - 50 }}" 
                               href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($section_id),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id,'part_id'=>($part_id +  1)])}}"> Parte {{$part_id  + 1}}</a>
                            @endif

                            @if(isset($part_id) && isset($sectionParts['part_'.($part_id - 1)]))
                            <a class="btn btn-sm btn-primary  z-index-2" ml="9%" mt="{{$container['h'] - 50 }}"
                               href="{{route('student.sequences_section_'.$section_part_id,['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id,  'account_service_id'=>$account_service_id, 'part_id'=>($part_id -  1)])}}"> Parte {{$part_id -1}}</a>
                            @endif
                            @if(isset($part_id) && isset($sectionParts['part_'.($part_id + 1)]['elements']) && count($sectionParts['part_'.($part_id + 1)]['elements']) > 0)
                            <a class="btn btn-sm btn-primary z-index-2" ml="83%" mt="{{$container['h'] - 50 }}"
                               href="{{route('student.sequences_section_'.$section_part_id,['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id,  'account_service_id'=>$account_service_id, 'part_id'=>($part_id +  1)])}}"> Parte {{$part_id  + 1}}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div ng-show="evidenceOpened" class="d-result d-none position-absolute col-xl-9 col-lg-8 col-10" style="top: 10%;left:12%;">
            <div class="d-result d-none modal-backdrop fade show w-100"></div>
            
            <div class="card ml-lg-6" style="z-index: 1040;min-width: 658px;">
                <div class="p-2">
                    <img class="ml-2" src="/@{{evidenceOpened.icon}}" width="auto" height="60px"/>
                    <span class="ml-4 mt-3 fs-1 font-weight-bold">@{{evidenceOpened.subtitle}}</span>
                    <span ng-show="disabledEvidence && evidenceOpened.type_answer === 2" class="ml-10 ml-md-7 ml-lg-10 mt-3 fs-1 font-weight-bold">Prueba concluida</span>
                    <span ng-hide="disabledEvidence || evidenceOpened.type_answer !== 2" class="ml-10 ml-md-7 ml-lg-10 mt-3 fs-1 font-weight-bold">Intentos @{{attempts}} de @{{maxAttempts}}</span>
                    <button class="close mt-2 mr-2" ng-click="closeEvidence()">
                        <span class="font-weight-light" >&times;</span>
                    </button>
                </div>
                <div class="card-body p-5" ng-show="evidenceOpened.type_answer===1">
                    <div ng-repeat="question in evidenceOpened.questions track by $index" class="ml-auto mr-auto row mb-3">
                        <div class="col-6"> 
                            <h6 ng-show="question.title" style="color:#E15433;">
                                <img style="margin-left: -26px;margin-top: -3px;" width="21px" height="auto" src="{{asset('images/icons/icon-options-questions.png')}}" >
                                <div class="mt-n3" ng-bind-html="question.title"></div>
                            </h6>
                        </div>
                        <div class="col-6"> 
                            <h6 ng-show="question.objective" style="color:#402F73;">
                                <img style="margin-left: -26px;margin-top: -3px;" width="21px" height="auto" src="{{asset('images/icons/icon-objectives-questions.png')}}" >
                                <div class="mt-n3" ng-bind-html="question.objective"></div>
                            </h6>
                        </div>
                        
                    </div>
                    <div class="d-flex mt-6 ml-6">
                        <button class="btn btn-sm btn-outline-success ml-2" style="right: 10%;" 
                            ng-disabled="" ng-show="true" ng-click="closeEvidence()">
                        <span ng-show="onFinishEvidenceLoad"><i class="fa fa-spinner fa-spin"></i> </span>Finalizar</button>
                    </div>
                </div>
                <div class="card-body pl-7 pr-6" ng-show="evidenceOpened.type_answer===2">
                    <div ng-repeat="question in evidenceOpened.questions track by $index" class="ml-auto mr-auto" ng-show="indexQuestion === $index">
                        <h5 style="color:#E15433;">
                            <img style="margin-left: -26px;margin-top: -3px;" width="21px" height="auto" src="{{asset('images/icons/icon-options-questions.png')}}" >
                            Pregunta @{{$index + 1}}. <div ng-bind-html="question.title"></div>
                        </h5>
                        <h6 ng-show="question.objective" class="mb-3" style="color:#402F73;"><img style="margin-left: -26px;margin-top: -3px;" width="21px" height="auto" src="{{asset('images/icons/icon-objectives-questions.png')}}" >@{{question.objective}}</h6>
                        <div class="line-separator mb-3"></div>
                        <div class="fs-0 ml-4" ng-repeat="option in question.options track by $index">
                            <input type="radio"
                                name="optionQuestion-@{{question.id}}"
                                ng-model="question.menu"
                                ng-checked="@{{question.menu === $index}}"
                                ng-change="onSelectOption(question,option)"
                                ng-disabled="disabledEvidence"
                                selected
                                value="@{{$index}}" class="mr-2 cursor-pointer">
                            <span style="position: absolute;left: 86px;">@{{letters[$index]+'). '}}</span>
                            <div class="ml-4" style="margin-top:-25px" ng-bind-html="option.option"></div>
                        </div>
                    </div>
                    <div class="d-flex mt-6 ml-6">
                        <button class="btn btn-sm btn-outline-primary" ng-disabled="indexQuestion === 0" ng-class="{'opacity-0': indexQuestion === 0}" ng-click="backQuestion()">Atrás</button>
                        <button class="btn btn-sm btn-outline-primary ml-2" ng-disable="indexQuestion >= evidenceOpened.questions.length - 1 " ng-disabled="indexQuestion >= evidenceOpened.questions.length - 1 " ng-click="nextQuestion()">Siguiente</button>
                        <button class="btn btn-sm btn-outline-success ml-2" style="right: 10%;" ng-class="{'disabled': disabledEvidence}" ng-show="indexQuestion === evidenceOpened.questions.length - 1" ng-click="onFinishEvidence()">
                        <span ng-show="onFinishEvidenceLoad" ><i class="fa fa-spinner fa-spin"></i> </span>Finalizar</button>
                    </div>
                </div>
            </div>
         </div>
    </div>
    <script src="{{ asset('angular/controller/contentSequencesStudentCtrl.js') }}" defer></script>
@endsection

