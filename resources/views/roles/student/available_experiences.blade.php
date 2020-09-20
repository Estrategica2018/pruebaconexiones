@extends('roles.student.layout')

@section('content')
    <div class="container ">
        <div class="content">
            <div ng-controller="availableSequencesStudentCtrl" ng-init="init(1)">
                prueba
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('angular/controller/availableExperiencesStudentCtrl.js') }}" defer></script>
@endsection
