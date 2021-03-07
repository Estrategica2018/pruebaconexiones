<div class="pr-2 bg-light d-flex justify-content-between card-header">
    @isset($moment)
    <h5 class="w-100 mb-0 inline-block">Momento {{$moment->order}}</h5>
    <p>
    <h6 class="w-100 mb-0">{{$moment->name}}</h6>
    @endif
</div>
<div class="card-body">
@if(isset($moment))
    <div class="mb-3 fs--1 text-justify">
        <span><strong>Los invitamos a:</strong></span>
        @if ($moment->objectives != "")
        <ul class="navbar-nav flex-column">
          @foreach(explode('|', $moment->objectives) as $obj) 
            <li class="nav-item list-style-inside">
                <span>{{$obj}}</span>
            </li>
          @endforeach
        </ul> 
        @endif
    </div>
    <nav class="pr-sm-6 pl-sm-6 pr-md-2 pl-md-2 pr-lg-3 pl-lg-3 fs--2 font-weight-semi-bold row navbar text-center">
        <a class="cursor-pointer" href="{{route('student.sequences_section_1',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id,'account_service_id'=>$account_service_id])}}">
            <img src="{{asset('/images/icons/situacionGeneradora.png')}}" height= "auto" width="45px">
            <span class="d-flex" style="margin-left: -5px;top: 69px;width: 45px;">Situación Generadora</span>
        </a>
        <a class="cursor-pointer" href="{{route('student.sequences_section_2',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id,'account_service_id'=>$account_service_id])}}">
            <img src="{{asset('/images/icons/rutaViaje.png')}}" height= "auto" width="45px">
            <span class="d-flex" style="top: 69px;width: 45px;">Ruta de viaje</span>
        </a>
        <a class="cursor-pointer mt-md-2" href="{{route('student.sequences_section_3',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id,'account_service_id'=>$account_service_id])}}">
            <img src="{{asset('/images/icons/GuiaSaberes.png')}}" height= "auto" width="45px">
            <span class="d-flex" style="top: 69px;width: 45px;">Guía de saberes</span>
        </a>
        <a class="cursor-pointer mt-md-2" href="{{route('student.sequences_section_4',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id,'account_service_id'=>$account_service_id])}}">
            <img src="{{asset('/images/icons/puntoEncuentro.png')}}" height= "auto" width="45px">
            <span class="d-flex" style="top: 69px;width: 45px;">Punto de encuentro</span>
        </a>
    <div class="fs--2 mt-3 font-weight-semi-bold">
        @if(isset($sections))
        @foreach( $sections as $index=> $section )
        @if($section['section']['type'] == 1)
            <a id="section_type_question" class="@if($section_id == 1) bottom-selected @endif  cursor-pointer d-flex color-gray-dark mt-2" href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($index+1),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id])}}">
                <img src="{{asset('/images/icons/preguntaCentral.png')}}" height= "45px;" width="45px">
                <h6 class="tooltiptext d-none">No tienes acceso al módulo</h6>
                <div class="fs--1 text-align-left ml-3 mb-auto mt-auto">Pregunta central:
                    <span class="fs--2 ml-2">@if(isset($section['title'])){{$section['title']}} @endif</span>
                </div>
            </a>
            <div class="text-align-left ml-5">
                @if(isset($section['part_2']) && count($section['part_2'])>0 && isset($section['part_2']['elements']) && count($section['part_2']['elements'])>0)
                <a style="@if($section_type == 1 && (!isset($part_id) || isset($part_id) && $part_id == 1) ) border-bottom: 2px solid #d8e2ef !important; @endif"
                class="mr-1" href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($index+1),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id,'part_id'=>1])}}"> Parte 1 </a>
                | <a style="@if($section_type == 1 && (!isset($part_id) || isset($part_id) && $part_id == 2) ) border-bottom: 2px solid #d8e2ef !important; @endif"
                     class="mr-1 ml-1" href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($index+1),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id,'part_id'=>2])}}"> Parte 2 </a>
                @if(isset($section['part_3']) && count($section['part_3'])>0 && isset($section['part_3']['elements']) && count($section['part_3']['elements'])>0)
                | <a style="@if($section_type == 1 && (!isset($part_id) || isset($part_id) && $part_id == 3) ) border-bottom: 2px solid #d8e2ef !important; @endif"
                     class="mr-1 ml-1" href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($index+1),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id,'part_id'=>3])}}"> Parte 3 </a>
                @endif
                @if(isset($section['part_4']) && count($section['part_4'])>0 && isset($section['part_4']['elements']) && count($section['part_4']['elements'])>0)
                | <a style="@if($section_type == 1 && (!isset($part_id) || isset($part_id) && $part_id == 4) ) border-bottom: 2px solid #d8e2ef !important; @endif"
                     class="mr-1 ml-1" href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($index+1),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id,'part_id'=>4])}}"> Parte 4 </a>
                @endif
                @if(isset($section['part_5']) && count($section['part_5'])>0 && isset($section['part_5']['elements']) && count($section['part_5']['elements'])>0)
                | <a style="@if($section_type == 1 && (!isset($part_id) || isset($part_id) && $part_id == 5) ) border-bottom: 2px solid #d8e2ef !important; @endif"
                     class="mr-1 ml-1" href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($index+1),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id,'part_id'=>5])}}"> Parte 5 </a>
                @endif
                @else
                    <div class="mt-2"></div>
                @endif
           </div>    
        @endif
        @if($section['section']['type'] == 2)
            <a id="section_type_science" class="@if($section_type == 2) bottom-selected @endif cursor-pointer d-flex color-gray-dark mt-2" href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($index+1),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id])}}">
                <img src="{{asset('/images/icons/cienciaCotidiana.png')}}" height= "45px" width="45px">
                <h6 class="tooltiptext d-none">No tienes acceso al módulo</h6>
                <div class="fs--1 text-align-left ml-3 mb-auto mt-auto">Ciencia cotidiana: 
                    <span class="fs--2 ml-2">@if(isset($section['title'])){{$section['title']}} @endif</span>
                </div>
            </a>
            <div class="text-align-left ml-5">    
                @if(isset($section['part_2']) && count($section['part_2'])>0 && isset($section['part_2']['elements']) && count($section['part_2']['elements'])>0)
                    <a style="@if($section_type == 2 && (!isset($part_id) || isset($part_id) && $part_id == 1) ) border-bottom: 2px solid #d8e2ef !important; @endif"
                       class="mr-1" href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($index+1),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id,'part_id'=>1])}}"> Parte 1 </a>
                    | <a style="@if($section_type == 2 && (!isset($part_id) || isset($part_id) && $part_id == 2) ) border-bottom: 2px solid #d8e2ef !important; @endif"
                         class="mr-1 ml-1" href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($index+1),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id,'part_id'=>2])}}"> Parte 2 </a>
                    @if(isset($section['part_3']) && count($section['part_3'])>0 && isset($section['part_3']['elements']) && count($section['part_3']['elements'])>0)
                    | <a style="@if($section_type == 2 && (!isset($part_id) || isset($part_id) && $part_id == 3) ) border-bottom: 2px solid #d8e2ef !important; @endif"
                         class="mr-1 ml-1" href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($index+1),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id,'part_id'=>3])}}"> Parte 3 </a>
                    @endif
                    @if(isset($section['part_4']) && count($section['part_4'])>0 && isset($section['part_4']['elements']) && count($section['part_4']['elements'])>0)
                    | <a style="@if($section_type == 2 && (!isset($part_id) || isset($part_id) && $part_id == 4) ) border-bottom: 2px solid #d8e2ef !important; @endif"
                         class="mr-1 ml-1" href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($index+1),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id,'part_id'=>4])}}"> Parte 4 </a>
                    @endif
                    @if(isset($section['part_5']) && count($section['part_5'])>0 && isset($section['part_5']['elements']) && count($section['part_5']['elements'])>0)
                    | <a style="@if($section_type == 2 && (!isset($part_id) || isset($part_id) && $part_id == 5) ) border-bottom: 2px solid #d8e2ef !important; @endif"
                         class="mr-1 ml-1" href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($index+1),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id,'part_id'=>5])}}"> Parte 5 </a>
                    @endif
                @else
                    <div class="mt-2"></div>
                @endif
            </div>    
        @endif
        @if($section['section']['type'] == 3 && $moment->exclude_experience == 0)
            <a class="@if($section_type == 3) bottom-selected @endif  cursor-pointer d-flex color-gray-dark mt-2" href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($index+1),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id])}}">
                <img src="{{asset('/images/icons/iconoExperiencia.png')}}" height= "45px" width="45px">
                <div class="fs--1 text-align-left ml-3 mb-auto mt-auto">Experiencia científica:<span class="fs--2 ml-2">@if(isset($section['title'])){{$section['title']}} @endif</span></div>
            </a>
            <div class="text-align-left ml-5">    
                @if(isset($section['part_2']) && count($section['part_2'])>0 && isset($section['part_2']['elements']) && count($section['part_2']['elements'])>0)
                    <a style="@if($section_type == 3 && (!isset($part_id) || isset($part_id) && $part_id == 1) ) border-bottom: 2px solid #d8e2ef !important; @endif"
                       class="mr-1" href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($index+1),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id,'part_id'=>1])}}"> Parte 1 </a>
                    | <a style="@if($section_type == 3 && (!isset($part_id) || isset($part_id) && $part_id == 2) ) border-bottom: 2px solid #d8e2ef !important; @endif"
                         class="mr-1 ml-1" href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($index+1),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id,'part_id'=>2])}}"> Parte 2 </a>
                    @if(isset($section['part_3']) && count($section['part_3'])>0 && isset($section['part_3']['elements']) && count($section['part_3']['elements'])>0)
                    | <a style="@if($section_type == 3 && (!isset($part_id) || isset($part_id) && $part_id == 3) ) border-bottom: 2px solid #d8e2ef !important; @endif"
                         class="mr-1 ml-1" href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($index+1),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id,'part_id'=>3])}}"> Parte 3 </a>
                    @endif
                    @if(isset($section['part_4']) && count($section['part_4'])>0 && isset($section['part_4']['elements']) && count($section['part_4']['elements'])>0)
                    | <a style="@if($section_type == 3 && (!isset($part_id) || isset($part_id) && $part_id == 4) ) border-bottom: 2px solid #d8e2ef !important; @endif"
                         class="mr-1 ml-1" href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($index+1),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id,'part_id'=>4])}}"> Parte 4 </a>
                    @endif
                    @if(isset($section['part_5']) && count($section['part_5'])>0 && isset($section['part_5']['elements']) && count($section['part_5']['elements'])>0)
                    | <a style="@if($section_type == 3 && (!isset($part_id) || isset($part_id) && $part_id == 5) ) border-bottom: 2px solid #d8e2ef !important; @endif"
                         class="mr-1 ml-1" href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($index+1),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id,'part_id'=>5])}}"> Parte 5 </a>
                    @endif
                @else
                    <div class="mt-2"></div>
                @endif
           </div>    
        @endif
        @if($section['section']['type'] == 4)
            <a id="section_type_connection"  class="@if($section_type == 4) bottom-selected @endif  cursor-pointer d-flex color-gray-dark mt-2" href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($index+1),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id])}}">
            <img src="{{asset('/images/icons/masConexiones.png')}}" height= "45px" width="45px">
                <h6 class="tooltiptext d-none">No tienes acceso al módulo</h6>
                <div class="fs--1 text-align-left ml-3 mb-auto mt-auto"> + Conexiones:<span class="fs--2 ml-2">@if(isset($section['title'])){{$section['title']}} @endif</span></div>
            </a>
            <div class="text-align-left ml-5">    
                @if(isset($section['part_2']) && count($section['part_2'])>0 && isset($section['part_2']['elements']) && count($section['part_2']['elements'])>0)
                    <a style="@if($section_type == 4 && (!isset($part_id) || isset($part_id) && $part_id == 1) ) border-bottom: 2px solid #d8e2ef !important; @endif"
                       class="mr-1" href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($index+1),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id,'part_id'=>1])}}"> Parte 1 </a>
                    | <a style="@if($section_type == 4 && (!isset($part_id) || isset($part_id) && $part_id == 2) ) border-bottom: 2px solid #d8e2ef !important; @endif"
                         class="mr-1 ml-1" href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($index+1),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id,'part_id'=>2])}}"> Parte 2 </a>
                    @if(isset($section['part_3']) && count($section['part_3'])>0 && isset($section['part_3']['elements']) && count($section['part_3']['elements'])>0)
                    | <a style="@if($section_type == 4 && (!isset($part_id) || isset($part_id) && $part_id == 3) ) border-bottom: 2px solid #d8e2ef !important; @endif"
                         class="mr-1 ml-1" href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($index+1),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id,'part_id'=>3])}}"> Parte 3 </a>
                    @endif
                    @if(isset($section['part_4']) && count($section['part_4'])>0 && isset($section['part_4']['elements']) && count($section['part_4']['elements'])>0)
                    | <a style="@if($section_type == 4 && (!isset($part_id) || isset($part_id) && $part_id == 4) ) border-bottom: 2px solid #d8e2ef !important; @endif"
                         class="mr-1 ml-1" href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($index+1),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id,'part_id'=>4])}}"> Parte 4 </a>
                    @endif
                    @if(isset($section['part_5']) && count($section['part_5'])>0 && isset($section['part_5']['elements']) && count($section['part_5']['elements'])>0)
                    | <a style="@if($section_type == 4 && (!isset($part_id) || isset($part_id) && $part_id == 5) ) border-bottom: 2px solid #d8e2ef !important; @endif"
                         class="mr-1 ml-1" href="{{route('student.show_moment_section',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence_id, 'moment_id' => $moment->id, 'section_id' => ($index+1),'account_service_id'=>$account_service_id,'order_moment_id'=>$order_moment_id,'part_id'=>5])}}"> Parte 5 </a>
                    @endif
                @else
                    <div class="mt-2"></div>
                @endif
            </div>    
        @endif
        @endforeach
        @endif
    </div>

@endif
</div>
