@extends('roles.tutor.layout')

@section('content-tutor-index')
   <div class="d-none-result d-none list-group" ng-controller="tutorInscriptionsCtrl" ng-init="initInscriptions()" >
        @foreach($students as $student)
        <div class=" student-tutor-inscription row p-3">
          <div class="col-auto">
             <img class="rounded-circle" src="@if($student->url_image) {{asset($student->url_image)}} @else {{'/images/icons/default-avatar.png'}}@endif" width="100px"/>
          </div>
          <div class="col-10 col-md-4 mt-3"><p>{{$student->name}} {{$student->last_name}}</p></div>
           <div class="col-12 col-md-auto mt-3 ">
              <h6>@if($student->firstMoment) Primer acceso {{$student->firstMoment}} @else {{'Sin iniciar el primer acceso '}} @endif</h6>
              <h6>@if($student->firstMoment) Última conexión @if($student->lastMoment) {{$student->lastMoment}} @else {{'sin iniciar'}} @endif @endif</h6>
          </div>
          <div class="ml-auto mt-0 mt-md-2">
              <a href="{{route('tutor.achievements.student',['empresa'=>auth('afiliadoempresa')->user()->company_name(),'student' => $student->id])}}">
                  <button class="btn btn-sm btn-primary font-weight-bold">
                        Ver logros
                  </button>
              </a>
          </div>
        </div>
        @endforeach
        @if(count($students) == 0)
        <div class="fs--1 d-none-result d-none">
          <p>Aún no has registrado estudiantes para la realización de las guías de aprendizaje</p>
        </div>
        @endif

   </div>
@endsection
@section('js')
    <script src="{{asset('/../angular/controller/tutorInscriptionsCtrl.js')}}"></script>
@endsection
