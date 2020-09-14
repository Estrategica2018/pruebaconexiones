@extends('layouts.app_side')

@section('content')

@include('layouts/float_buttons')

<div class="ml-auto mr-auto row " ng-controller="contactusController" ng-init="init()">
   <div class="pr-lg-2 col-12 col-lg-6 m-auto">
      <div class="mb-3 card">
         <div class="card-header">
            <h5 class="mb-0">Contáctenos</h5>
         </div>
         <div class="bg-light card-body">
            <form class="" ng-submit="insertData(contactusForm)" name="contactusForm" id="contactusForm" novalidate>
               <div class="row">
                  <div class="col-lg-12">
                     <div class="form-group">
                        <label for="name" class="">Nombre</label>
                        <input  name="name" type="text" class="form-control"
                                ng-model="name"  ng-minlength="5" autofocus required autocomplete="off">
                        <div class="d-result d-none" ng-messages="contactusForm.name.$error">
                           <div ng-message="minlength"><span class="text-danger">Debe tener minimo 5 caracteres.</span></div>
                           <div ng-message="required" ng-if="contactusForm.name.$invalid && contactusForm.name.$touched"><span class="text-danger">Campo obligatorio</span></div>
                        </div>
                     </div>

                  </div>

               </div>
               <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                     <div class="form-group">
                        <label for="email" class="">Correo electrónico</label>
                        <input  name="email" type="email" class="form-control" ng-model="email" autofocus required="" autocomplete="off">
                        <div class="d-result d-none" ng-messages="contactusForm.email.$error">
                           <div ng-message="email"><span class="text-danger">Formato de correo no valido.</span></div>
                           <div ng-message="required" ng-if="contactusForm.email.$invalid && contactusForm.email.$touched"><span class="text-danger">Campo obligatorio</span></div>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12">
                     <div class="form-group">
                         <label for="affair" class="">Asunto</label>
                         <input name="affair" id="affair" type="text" class="form-control" ng-model="affair" required >
                         <div class="d-result d-none" ng-messages="contactusForm.affair.$error">
                           <div ng-message="required" ng-if="contactusForm.affair.$invalid && contactusForm.affair.$touched"><span class="text-danger">Campo obligatorio</span></div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-12">
                     <div class="form-group">
                         <label for="message" class="">Mensaje</label>
                         <textarea name="message" id="message" type="text" class="form-control" ng-model="message" required ></textarea>
                         <div class="d-result d-none" ng-messages="contactusForm.message.$error">
                           <div ng-message="required" ng-if="contactusForm.message.$invalid && contactusForm.message.$touched"><span class="text-danger">Campo obligatorio</span></div>
                        </div>
                     </div>
                  </div>
                  <div class="d-flex justify-content-end col-12">
                     <button id="send" type="submit" class="buttonload btn btn-primary" ng-disabled="contactusForm.$invalid"><i id="move" class=""></i> Enviar</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>

</div>
@endsection
@section('js')
   <script src="{{asset('/../angular/controller/ContactusController.js')}}"></script>
@endsection