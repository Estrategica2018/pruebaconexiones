@extends('layouts.app_side')
@section('content')
<div class="container">
   <div class="content">
      <div class="row">
         @include('roles.admin.sidebar')
         <div class="col-md-8">
            <div class="mb-3 card">
               <div class="card-header row no-gutters">
                  <h5 class="mb-0 col">Carga Masiva</h5>
                  <div class="text-right cursor-pointer image-tooltip">
                     <a href="{{asset('fileUploadDirectory/example/Plantilla_Carga-Estudiantes_Corporativos.xls')}}" download>
                        <img src="{{asset('images/icons/excel.png')}}" width="26px" height="auto">
                        <span class="fs--1"> Plantilla </span>
                     </a>
                     <span class="tooltiptext" style="top: 50px;">Descargar Ejemplo</span>
                  </div>
               </div>
               <div class="bg-light card-body">
                  <div ng-controller="FileUploadController">
                     <form action="{{ route('fileuploadAction') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                           <label for="companyLabel" class="">Compañía</label>
                           <select name="companySelect" id="companySelect" class="form-control" ng-model="companyId" ng-change="onChangeCompany()">
                              <option value="@{{company.id}}" ng-repeat="company in companies">@{{company.name}}</option>
                           </select>
                           <input type="text" style = "display:none" name="company_name" value = "" ng-model="companyName"/>
                        </div>
                        <div class="form-group">
                           <label for="sequenceLabel" class="">Secuencia</label>
                           <select name="sequenceSelect" id="sequenceSelect" class="form-control" ng-model="sequenceId" ng-change="onChangeSecuence()">
                              <option value="@{{sequence.id}}" ng-repeat="sequence in sequences">@{{sequence.name}}</option>
                           </select>
                           <input type="text" style = "display:none" name="sequence_name" value = "" ng-model="sequenceName"/>
                        </div>
                        <div class="form-group">
                           <label for="teacherLabel" class="">Docente</label>
                           <select name="teacherSelect" id="teacherSelect" class="form-control" ng-model="teacherId" ng-change="onChangeTeachers()">
                              <option value="@{{teacher.id}}" ng-repeat="teacher in teachers">@{{teacher.name +' '+ teacher.last_name}}</option>
                           </select>
                           <input type="text"  style = "display:none" name="teacher_name" value = "" ng-model="teacherName"/>
                        </div>
                        <div class="form-group">
                           <label for="groupLabel" class="">Grupo</label>
                           <select name="groupSelect" id="groupSelect" class="form-control" ng-model="groupId" ng-change="onChangeGroups()">
                              <option value="@{{group.id}}" ng-repeat="group in groups">@{{group.name}}</option>
                           </select>
                           <input type="text"  style = "display:none" name="group_name" value = "" ng-model="groupName"/>
                        </div>
                        <div class="form-group">
                           <input name="fileInput" id="fileInput" type="file" class="form-control-file">
                        </div>
                        <button type="submit" class="btn btn-outline-primary">
                           <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="plus" class="svg-inline--fa fa-plus fa-w-14 mr-1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" style="transform-origin: 0.4375em 0.5em;">
                              <g transform="translate(224 256)">
                                 <g transform="translate(0, 0)  scale(0.8125, 0.8125)  rotate(0 0 0)">
                                    <path fill="currentColor" d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z" transform="translate(-224 -256)"></path>
                                 </g>
                              </g>
                           </svg>
                           Cargar archivo
                        </button>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('js')
<script src="{{asset('/../angular/controller/FileUploadController.js')}}"></script>
@endsection