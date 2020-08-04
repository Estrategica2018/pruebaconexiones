@extends('roles.student.layout')

@section('content')
    <div class="container" ng-controller="achievementsStudentCtrl" ng-init="initProfile()">
        <div class="content row">
            <div class="col-md-3 ml-md-0 ml-sm-7 col-sm-9 text-align mt-5">
                <div class="col-12">
                    <h6>{{$student->name}} {{$student->last_name}}</h6>
                    @if(isset($student->url_image)) 
                        <img class="rounded-circle" src="{{asset($student->url_image)}}" width="100px" height="auto" style="margin-left: 10px;">
                    @else 
                        <img class="rounded-circle" src="{{asset('images/icons/default-avatar.png')}}" width="100px" height="auto" style="margin-left: 10px;">
                    @endif
                </div>
                <div class="col-12 mb-3">
                    <img class="mt-3 line_up_beatyfull" src="{{asset('images/icons/lineaReporte.png')}}"></img>
                    <div class="ml-7 line row" style="">
                        <div class="other"></div>
                        <div class="other-overwrite"></div>
                        <div class="up"></div>
                        <div class="overwrite"></div>
                    </div>    
                    <span>{{$countSequences}}</span>
                    <h6>{{ __('Guias de aprendizajes activas') }}</h6>
                    <img class="line_up_beatyfull mt-3 rotateimg180" src="{{asset('images/icons/lineaReporte.png')}}"></img>
                    <div class="ml-7 line row" style="">    
                        <div class="down"></div>
                        <div class="overwrite"></div>
                    </div>
                </div>
                <div class="col-12 row m-0">
                    <div class="col-6 fs-lg-0 fs-md--1 fs-0 pl-0">
                        @if($firstAccess)
						<span>{{$firstAccess}}</span>
						@else 
						<span>No iniciada</span>
					    @endif
                        <h6 class="mt-1 fs--1">Fecha del primer acceso</h6>
                    </div>
                    <div class="border-left col-6 fs-lg-0 fs-md--1 fs-0 pr-0" style="border-left: 1px solid grays;">
                        @if($lastAccess)
                        <span>{{$lastAccess}}</span>
                        @else 
                        <span>No iniciada</span>
                        @endif
                        <h6 class="mt-1 fs--1">Fecha del último acceso</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-9 p-1 p-md-3">
                 @yield('achievements_layout')
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{asset('/../angular/controller/achievementsStudentCtrl.js')}}"></script>
@endsection
