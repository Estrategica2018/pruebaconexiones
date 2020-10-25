@extends('layouts.app_side')
@section('content')

    <div ng-controller="managerFrequentQuestionsCtrl" ng-init="init()">
        <div class="modal fade result-finish-done d-none" ng-class="{'show':action==='modifyQuestion' || action==='newQuestion'}">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" ng-show="action==='modifyQuestion'">Editar Pregunta</h5>
                        <h5 class="modal-title" ng-show="action==='newQuestion'">Nueva Pregunta</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close" ng-click="action=''">
                            <span class="font-weight-light" aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Pregunta</label>
                            <input class="form-control md-input" ng-model="questionEdit.question" type="text" placeholder="Pregunta">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Respuesta</label>
                            <textarea id="editorhtml" name="editorhtml"></textarea>
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary btn-sm" type="button" ng-click="action=''">Cerrar</button>
                        <button class="btn btn-primary btn-sm" type="button" ng-click="saveQuestion(questionEdit)"><i id="move" class=""></i>Guardar</button></div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="content">
                <div class="row">
                    @include('roles.admin.sidebar')
                    <div class="col-12 col-md-8" >
                        <div class="mb-3 card">
                            <div class="card-header">
                                <h5 class="">Preguntas Frecuentes</h5>
                            </div>
                            <div class="bg-light card-body">
                                <button id="formPlan" class="btn btn-outline-primary mr-2 mb-3" type="button" ng-click="newQuestion()">
                                    <span class="fas fa-plus mr-1" data-fa-transform="shrink-3"></span>Nuevo
                                </button>
                                <table class="display-1 table table-sm table-dashboard data-table no-wrap mb-0 fs--1 w-100" style="width: 100%">
                                    <thead class="bg-200">
                                    <tr>
                                        <th>Id</th>
                                        <th>Pregunta</th>
                                        <th>Respuesta</th>
                                        <th colspan="2">Acciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="btn-reveal-trigger border-top border-200" ng-repeat="frequentQuestion in frequentQuestions">
                                            <td class="align-middle">@{{frequentQuestion.id}}</td>
                                            <td class="align-middle">@{{frequentQuestion.question}}</td>
                                            <td class="align-middle" ng-bind-html="frequentQuestion.answer" ></td>
                                            <td class="align-middle"><button class="btn btn-small btn-primary" ng-click="editQuestion(frequentQuestion)">Editar</button></td>
                                            <td class="align-middle"><button class="btn btn-small btn-danger" ng-click="deleteQuestion(frequentQuestion)">Borrar</button></td>
                                        </tr>
                                  
                                    </tbody>
                                    <tfoot class="bg-200">
                                    <tr>
                                        <th>Id</th>
                                        <th>Pregunta</th>
                                        <th>Respuesta</th>
                                        <th colspan="2">Acciones</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('js')
     <script src="{{asset('/../angular/controller/managerFrequentQuestionsCtrl.js')}}"></script>
     <script src="https://cdn.tiny.cloud/1/v4mwkpxb4xl040unqtsepspvu82ecwea01wqejwwy6gmv4jg/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
@endsection
