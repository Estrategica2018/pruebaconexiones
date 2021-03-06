<div class="pr-2 bg-light d-flex justify-content-between card-header">
    @isset($sequence)
    <h6 class="mb-0">Secuencia {{$sequence->name}}</h6>
    @endif
</div>
<div class="card-body">
@if(isset($sequence))
    <div class="mb-3 fs--1 text-justify">
        <span><strong>Los invitamos a:</strong></span>
        @if ($sequence->objectives != "")
        <ul class="navbar-nav flex-column">
          @foreach(explode('|', $sequence->objectives) as $obj) 
            <li class="nav-item list-style-inside">
                <span>{{$obj}}</span>
            </li>
          @endforeach
        </ul> 
        @endif
    </div>
    <nav class="pr-sm-6 pl-sm-6 pr-md-2 pl-md-2 pr-lg-3 pl-lg-3 fs--2 font-weight-semi-bold row navbar text-center">
        <a class="cursor-pointer" href="{{route('student.sequences_section_1',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence->id,'account_service_id'=>$account_service_id])}}">
            <img src="{{asset('/images/icons/situacionGeneradora.png')}}" height= "auto" width="50px">
            <span class="d-flex" style="top: 69px;width: 45px;">Situación Generadora</span>
        </a>
        <a class="cursor-pointer" href="{{route('student.sequences_section_2',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence->id,'account_service_id'=>$account_service_id])}}">
            <img src="{{asset('/images/icons/rutaViaje.png')}}" height= "auto" width="50px">
            <span class="d-flex" style="top: 69px;width: 45px;">Ruta de viaje</span>
        </a>
        <a class="cursor-pointer" href="{{route('student.sequences_section_3',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence->id,'account_service_id'=>$account_service_id])}}">
            <img src="{{asset('/images/icons/GuiaSaberes.png')}}" height= "auto" width="50px">
            <span class="d-flex" style="top: 69px;width: 45px;">Guía de saberes</span>
        </a>
        <a class="cursor-pointer" href="{{route('student.sequences_section_4',['empresa'=>auth('afiliadoempresa')->user()->company_name(), 'sequence_id' => $sequence->id,'account_service_id'=>$account_service_id])}}">
            <img src="{{asset('/images/icons/puntoEncuentro.png')}}" height= "auto" width="50px">
            <span class="d-flex" style="top: 69px;width: 45px;">Punto de encuentro</span>
        </a>
    </nav>
@endif
</div>
