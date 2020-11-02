<!-- Demo styles -->
<style type="text/css">
    .help {
        position: absolute;
        width: 39px;
        height: 37px;
        right: 20px;
    }
    .help-icon {
        font-size: 35px;
    }    
    
    .help .leyend {
        position: absolute;
        width: 270px;
        min-height: 143px;
        right: 0px;
        top: 47px;
        z-index: 10;
        padding: 10px;
        display: none;
    }
    
    .help-arrow {
        position: absolute;
        z-index: -1;
        width: 1rem;
        height: 1rem;
        top: -.375rem;
        left: 1.8rem;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
        background-color: #f9fafd;
        border-radius: .125rem;
        border-width: 1px 0 0 1px;
        border-style: solid;
        border-color: #e9eaed;
        -webkit-box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
        right: .95rem;
        left: auto;
        top: 41px;
    }
    
    .dropdown-item {
        color: #5e6e82;
        white-space: normal;

    }

</style>

<div ng-controller="timelineSequencesStudentCtrl" ng-init=init(1,"{{$account_service_id}}","{{$sequence_id}}") class="row">
    <div ng-controller="navbarController" class="col-5 pr-0" style="height: 106px;">
        <a href="{{asset('/')}}"><img href="" class="mr-2 avatar-logo" src="{{ asset('images/icons/iconosoloConexiones-01.png') }}" alt="Logo" width="40"></a>
        
        @if(isset(auth('afiliadoempresa')->user()->url_image)) 
            <img class="avatar-student-timeline avatar-default rounded-circle" src="{{ asset(auth('afiliadoempresa')->user()->url_image) }}" width="70px" height="auto">
        @else 
            <img class="avatar-student-timeline" src="{{asset('images/icons/default-avatar.png')}}" width="70px" height="auto"/>
        @endif
        
        
        <span class="nameTimeLine fs--1">{{auth('afiliadoempresa')->user()->name}}</span>
        <div class="position-absolute d-flex" style="top: 12px;left: 220px;">
            <a class="ml-8 cursor-pointer image-tooltip" href="{{route('student','conexiones')}}">
                <img src="{{asset('images/icons/portal-estudiante/home_Mesa de trabajo 1.png')}}" width="32" height="auto"/>
                <span class="tooltiptext">inicio</span>
            </a>
            @if(isset($buttonBack) && $buttonBack != 'none')
            <a class="ml-1 cursor-pointer image-tooltip" href="{{$buttonBack}}">
                <img src="{{asset('images/icons/portal-estudiante/atras_Mesa de trabajo 1.png')}}" width="32" height="auto"/>
                <span class="tooltiptext">atr&aacute;s</span>
            </a>
            @else
            <a class="ml-1 cursor-not-allowed image-tooltip" disabled style="opacity: .5;">
                <img src="{{asset('images/icons/portal-estudiante/atras_Mesa de trabajo 1.png')}}" width="32" height="auto"/>
                <span class="tooltiptext">atr&aacute;s</span>
            </a>
            @endif
            @if($buttonNext != 'none')
            <a class="ml-1 cursor-pointer image-tooltip" href="@if(isset($buttonNext)) {{$buttonNext }} @endif">
                <img src="{{asset('images/icons/portal-estudiante/adelante_Mesa de trabajo 1.png')}}" width="32" height="auto"/>
                <span class="tooltiptext">adelante</span>
            </a>
            @else
                <a class="ml-1 cursor-not-allowed image-tooltip" disabled style="opacity: .5;">
                    <img src="{{asset('images/icons/portal-estudiante/adelante_Mesa de trabajo 1.png')}}" width="32" height="auto"/>
                    <span class="tooltiptext">adelante</span>
                </a>
            @endif
            <a class="ml-1 cursor-pointer image-tooltip" href="{{ route('student.available_sequences',auth('afiliadoempresa')->user()->company_name()) }}">
                <img src="{{asset('images/icons/portal-estudiante/guias_Mesa de trabajo 1.png')}}" width="32" height="auto"/>
                <span class="tooltiptext">gu&iacute;as de aprendizaje</span>
            </a>
            <a class="ml-1 cursor-pointer image-tooltip" href="{{ route('student.achievements',auth('afiliadoempresa')->user()->company_name()) }}">
                <img src="{{asset('images/icons/portal-estudiante/logros_Mesa de trabajo 1.png')}}" width="32" height="auto"/>
                <span class="tooltiptext">logros</span>
            </a>
            <a class="ml-1 cursor-pointer image-tooltip" href="{{route('student','conexiones')}}">
                <img src="{{asset('images/icons/portal-estudiante/perfil_Mesa de trabajo 1.png')}}" width="32" height="auto"/>
                <span class="tooltiptext">perf&iacute;l</span>
            </a>
            <a class="ml-1 cursor-pointer image-tooltip" href="#" ng-click="closeSession('{{ route('user.logout') }}')">
                <img src="{{asset('images/icons/portal-estudiante/salir_Mesa de trabajo 1.png')}}" width="32" height="auto"/>
                <span class="tooltiptext">salir</span>
            </a>
            <form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
             @csrf
            </form>
        </div>
    </div>
    <div class="help" ng-show="alertProgress.length > 0" ng-click="showLeyend=!showLeyend; showLeyendHover=false">
       <!--div ng-mouseenter="showLeyendHover=true" ng-mouseleave="showLeyendHover=false">
          <i class="far fa-bell help-icon cursor-pointer"></i>
       </div-->
       <i class="far fa-bell help-icon cursor-pointer"></i>
       <div class="help-arrow d-none"  ng-class="{'d-block':showLeyend || showLeyendHover}"></div>
       <div class="leyend p-0 dropdown-menu dropdown-menu-right py-0" aria-labelledby="navbarDropdownUser" ng-class="{'d-block':showLeyend || showLeyendHover}">
           <div class="bg-white rounded-soft p-0">
              <div class=" bg-light font-weight-bold text-warning text-align p-2">
                <span>Secciones por completar</span>
              </div>
              <div ng-repeat="alert in alertProgress">
              <a class="dropdown-item pl-4" href="#!" >@{{alert.text}}</a>
              <div class="dropdown-divider mt-0"></div>
              </div>
              <div class="dropdown-divider"></div>
           </div>
       </div>
    </div>
    <div class="col-auto d-none d-lg-block lineTimeLine">
        @if($rating_plan_type != 3)
        <div>
            @for($j = 1; $j < 9 ; $j++)
                @for($i = 1; $i < 5 ; $i++)
                    @if($i ===1)
                        <svg class="svgelem2" width="10px" height="20px" style="margin-right: -6px; margin-left: -12px;">
                            <rect width="35" fill="#494b9a" stroke="#494b9a" height="1" style="stroke-width:0.5;" />
                        </svg>
                    @else
                        <svg class="svgelem2" width="10px" height="20px" style="margin-right: -7px;padding-left: 0.3px;">
                            <rect width="12" fill="#494b9a" stroke="#494b9a" height="1" style="stroke-width:0.5;" />
                        </svg>
                    @endif
                    
                        <svg class="svgelem"  width="17px" height="13px" style="margin-right:-10px" xmlns="http://www.w3.org/2000/svg">
                            <g> 
                              <circle class="circle{{$j}}{{$i}}" cx="7" cy="7" r="5" fill="#FFFFFF" stroke="#494b9a" stroke-width="2%"/>

                              <a xlink:href="{{route('student.show_moment_section',[
                                'empresa' => $empresa,
                                'account_service_id'=>$account_service_id,
                                'sequence_id'=>$sequence_id,
                                'moment_id'=> App\Http\Controllers\MomentController::retriveMoment($sequence->moments,$j),
                                'order_moment_id'=>$j,
                                'section_id'=>$i
                            ])}}" target="_top">
                                <text x="40%" y="83%" style="text-anchor: middle; opacity: 0;">.</text>
                              </a>
                              
                            </g>
                        </svg>
                    
                @endfor
                <svg class="svgelem2" width="14px" height="20px" style="margin-right: -21px; padding-left: 0.4px;">
                    <rect width="35" height="1" fill="#494b9a" stroke="#494b9a" style="stroke-width:0.5;" />
                </svg>
                <svg width="44px" height="80px" xmlns="http://www.w3.org/2000/svg" style="margin-left: -2px;">
                        <path  class="star{{$j}}" fill="#FFFFFF"  style="transform:translate(12px, 22px) scale(.22,.22) rotate(-1deg);" stroke="#494b9a" stroke-width="6"
                              d="m135.78 50.46c0-2.01-1.52-3.259-4.564-3.748l-40.897-5.947-18.331-37.07c-1.031-2.227-2.363-3.34-3.992-3.34-1.629
                              0-2.96 1.113-3.992 3.34l-18.332 37.07-40.899 5.947c-3.041.489-4.562 1.738-4.562 3.748 0 1.141.679 2.445 2.037
                              3.911l29.656 28.841-7.01 40.736c-.109.761-.163 1.305-.163 1.63 0 1.141.285 2.104.855 2.893.57.788 1.425 1.181
                              2.566 1.181.978 0 2.064-.324 3.259-.977l36.58-19.229 36.583 19.229c1.142.652 2.228.977 3.258.977 1.089 0
                              1.916-.392 2.486-1.181.569-.788.854-1.752.854-2.893 0-.706-.027-1.249-.082-1.63l-7.01-40.736 29.574-28.841c1.414-1.412
                              2.119-2.716 2.119-3.911"/>
                        <text class="number{{$j}}" x="56%" y="50%" text-anchor="middle" stroke="#494b9a" stroke-width="1.5px" dy=".18em" dx=".15em" style="text-anchor: middle;">{{$j}}</text>
                </svg>
            @endfor
        </div>
        @endif
    </div>
    <div class="col-auto d-md-block d-lg-none lineTimeLine small">
        <div>
            @for($j = 1; $j < 9 ; $j++)
                @for($i = 1; $i < 5 ; $i++)
                    @if($i ===1)
                        <svg class="svgelem2" width="10px" height="20px" style="margin-right: -21px; margin-left: -18px;">
                            <rect width="25" height="1"  fill="#494b9a" stroke="#494b9a" style="stroke-width:0.5;" />
                        </svg>
                    @else
                        <svg class="svgelem2" width="10px" height="20px" style="margin-right: -24px;">
                            <rect width="10" height="1" fill="#494b9a" stroke="#494b9a" style="stroke-width:0.5;" />
                        </svg>
                    @endif
                    <svg class="svgelem"  width="33px" height="40px" style="margin-right:-12px" xmlns="http://www.w3.org/2000/svg">
                        <circle class="circle{{$j}}{{$i}}" cx="21" cy="25" r="4" fill="#FFFFFF" stroke="#494b9a"
                                stroke-width="2%"/>
                    </svg>
                @endfor
                <svg class="svgelem2" width="10px" height="20px" style="margin-right: -10px">
                    <rect width="16" height="1" fill="#494b9a" stroke="#494b9a" style="stroke-width:0.5;" />
                </svg>
                <svg width="50" height="80" xmlns="http://www.w3.org/2000/svg"  style="margin-left: -9px;">
                    <path  class="star{{$j}}" fill="#FFFFFF"  style="transform:translate(12px, 22px) scale(.22,.22) rotate(-1deg);" stroke="#494b9a" stroke-width="6"
                           d="m135.78 50.46c0-2.01-1.52-3.259-4.564-3.748l-40.897-5.947-18.331-37.07c-1.031-2.227-2.363-3.34-3.992-3.34-1.629
                              0-2.96 1.113-3.992 3.34l-18.332 37.07-40.899 5.947c-3.041.489-4.562 1.738-4.562 3.748 0 1.141.679 2.445 2.037
                              3.911l29.656 28.841-7.01 40.736c-.109.761-.163 1.305-.163 1.63 0 1.141.285 2.104.855 2.893.57.788 1.425 1.181
                              2.566 1.181.978 0 2.064-.324 3.259-.977l36.58-19.229 36.583 19.229c1.142.652 2.228.977 3.258.977 1.089 0
                              1.916-.392 2.486-1.181.569-.788.854-1.752.854-2.893 0-.706-.027-1.249-.082-1.63l-7.01-40.736 29.574-28.841c1.414-1.412
                              2.119-2.716 2.119-3.911"/>
                    <text class="number{{$j}}" x="50%" y="53%" text-anchor="middle" stroke="#494b9a" stroke-width="1px" dy=".08em" dx=".05em" style="text-anchor: middle;">{{$j}}</text>
                </svg>
            @endfor
        </div>
    </div>

</div>
@section('js')
    <script src="{{ asset('angular/controller/timelineSequencesStudentCtrl.js') }}" defer></script>
@endsection