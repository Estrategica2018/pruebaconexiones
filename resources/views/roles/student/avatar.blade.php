@extends('roles.student.layout')

@section('content')
    <div class="container ">
        <div class="content">
            <div class="row">
                <div class="col-md-12">
                   <div ng-controller="avatarStudentCtrl" ng-init="init()">
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

                    <div class="mb-3 card">
                        <div class="card-body">
                           <div class="justify-content-between align-items-center row">
                              <div class="col-md">
                                 <h5 class="mb-2 mb-md-0">Crear Avatar</h5>
                                 <h6>Antes de iniciar la ruta de aprendizaje, te invitamos a que personalices tu imagen</h6>
                              </div>
                              <div class="col-auto">
                                 @if(!auth('afiliadoempresa')->user()->url_image)
                                 <button class="mr-2 btn btn-outline-primary btn-sm" ng-click="onSaveAvatarDefault()">Omitir</button>
                                 @endif
                                 <button ng-disabled="!customImage && !avatar"
                                 class="mr-2 btn btn-falcon-default btn-sm" ng-click="onSaveAvatar()">Guardar</button>
                                 <form action="{{route('update_avatar',auth('afiliadoempresa')->user()->company_name())}}" method="POST" id="save-avatar-form">
                                    @csrf
                                    <input type="hidden" id="url_image" name="url_image" ng-value="urlImage"/>
                                    <input type="hidden" id="custom_image" name="custom_image" ng-value="customImage"/>
                                 </form>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="mb-3 overflow-hidden card" style="min-width: 1210px;">
                        <div class="p-3 border-lg-y col-lg-2 w-100 loading"
                           style="min-height: 23vw; border: 0.4px solid grey; min-width: 100%" ng-hide="kits.length > 0">
                           cargando...
                        </div>
                        <div class="position-relative card-body pr-1 d-result d-none ">
                           <h6> Puedes crear tu propio avatar o elegir uno</h6>
                           <div class="row mt-3">
                              <div class="col-3 col-md-4">
                                 <img ng-repeat="avatar in avatars" class="shadow-sm avatar-default rounded-circle" src="@{{avatar.urlImage}}" ng-click="setAvatar(avatar)" >
                              </div>
                              <div class="col-7 col-md-4 col-lg-4">
                                 <div ng-show="avatar" style="width: 75%;">
                                    <img class="shadow-sm rounded-circle" width="100%" height="auto" src="@{{avatar.urlImage}}">
                                    <h6 class="text-align mt-2 font-weight-extra-bold">¿Sabes quien es éste personaje?</h6>
                                    <h6 class="text-align mt-2">@{{avatar.name}}</h6>
                                    <h5 class="text-align mt-2">@{{avatar.job}}</h5>
                                 </div>
                                 <canvas ng-hide="avatar" class="" width="318" height="357" id="canvas">No Canvas support</canvas>
                              </div>
                              <div class="col-2 col-md-3 col-lg-4"  class="card-body">
                                 <div id="menu">
                                    <div class="mb-3 d-flex">
                                       <div class="mr-auto"><img width="65px" height="65px" src="{{asset('images/avatars/mini/icons/cara.png')}}" class="tab-avatar btn btn-falcon-primary pl-2 pr-2 ml-auto mr-auto" data-tab="skin"/></div>
                                       <div class="ml-auto mr-auto"><img width="65px" height="65px" src="{{asset('images/avatars/mini/icons/rasgo.png')}}" class="tab-avatar btn btn-falcon-primary pl-2 pr-2" data-tab="features"/></div>
                                       <div class="ml-auto mr-auto"><img width="65px" height="65px" src="{{asset('images/avatars/mini/icons/cabello.png')}}" class="tab-avatar btn btn-falcon-primary pl-2 pr-2" data-tab="hair"/></div>
                                       <div class="ml-auto mr-auto"><img width="65px" height="65px" src="{{asset('images/avatars/mini/icons/accesorio11.png')}}" class="tab-avatar btn btn-falcon-primary p-2 pt-3 pb-3" data-tab="accessories1"/></div>
                                       <div class="ml-auto mr-auto"  ><img width="65px" height="65px" src="{{asset('images/avatars/mini/icons/accesorio2.png')}}" class="tab-avatar btn btn-falcon-primary p-2 pt-3 pb-3" data-tab="accessories2"/></div>
                                    </div>
                                 </div>
                                 <div id="avatar">
                                    <div id="skin">
                                       <img width="65px" height="65px" class="img-thumbnail cursor-pointer activo seleccionado" src="{{ asset('images/avatars/mini/rostro/rostro1.png')}}"/>
                                       <img width="65px" height="65px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/rostro/rostro2.png')}}"/>
                                       <img width="65px" height="65px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/rostro/rostro3.png')}}"/>
                                       <img width="65px" height="65px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/rostro/rostro4.png')}}"/>
                                    </div>
                                    <div id="features" class="d-none">
                                       <img width="65px" height="55px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/rasgos/rasgos1.png')}}">
                                       <img width="65px" height="55px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/rasgos/rasgos2.png')}}">
                                       <img width="65px" height="55px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/rasgos/rasgos3.png')}}">
                                       <img width="65px" height="55px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/rasgos/rasgos4.png')}}">
                                       <img width="65px" height="55px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/rasgos/rasgos5.png')}}">
                                       <img width="65px" height="55px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/rasgos/rasgos6.png')}}">
                                       <img width="65px" height="55px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/rasgos/rasgos7.png')}}">
                                       <img width="65px" height="55px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/rasgos/rasgos8.png')}}">
                                       <img width="65px" height="55px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/rasgos/rasgos9.png')}}">
                                       <img width="65px" height="55px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/rasgos/rasgos10.png')}}">
                                       <img width="65px" height="55px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/rasgos/rasgos11.png')}}">
                                       <img width="65px" height="55px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/rasgos/rasgos12.png')}}">
                                    </div>
                                    <div id="hair" class="d-none">
                                       <img width="65px" height="65px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/cabello/cabello1.png')}}">
                                       <img width="65px" height="65px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/cabello/cabello2.png')}}">
                                       <img width="65px" height="65px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/cabello/cabello3.png')}}">
                                       <img width="65px" height="65px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/cabello/cabello4.png')}}">
                                       <img width="65px" height="65px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/cabello/cabello5.png')}}">
                                       <img width="65px" height="95px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/cabello/cabello6.png')}}">
                                    </div>
                                    <div id="accessories1" class="d-none">
                                       <img width="65px" height="55px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/accesorios/gafasNegras.png')}}"/>
                                       <img width="65px" height="55px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/accesorios/gafasGata.png')}}"/>
                                       <img width="65px" height="55px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/accesorios/gafas.png')}}"/>
                                       <img width="65px" height="55px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/accesorios/gafasAzul.png')}}"/>
                                       <img width="65px" height="55px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/accesorios/eraser.png')}}"/>
                                    </div>
                                    <div id="accessories2" class="d-none">
                                       <img width="65px" height="55px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/accesorios/diademaGato.png')}}"/>
                                       <img width="65px" height="55px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/accesorios/diademaRosa.png')}}"/>
                                       <img width="65px" height="55px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/accesorios/gorra.png')}}"/>
                                       <img width="65px" height="55px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/accesorios/sombrero.png')}}"/>
                                       <img width="65px" height="55px" class="img-thumbnail cursor-pointer" src="{{ asset('images/avatars/mini/accesorios/eraser.png')}}"/>
                                    </div>
                                 </div> 
                                  <div class="mt-3 pr-2">
                                     <div id="color-skin" class="p-2 mt-7 d-flex border ">
                                        <div data-rgb="fac9b7" class=" ml-auto mr-auto border-color seleccionado" style="background-color:#fac9b7;"></div>
                                        <div data-rgb="5b4031" class=" ml-auto mr-auto border-color" style="background-color:#5b4031;"></div>
                                        <div data-rgb="c69a7b" class=" ml-auto mr-auto border-color" style="background-color:#c69a7b;"></div>
                                        <div data-rgb="cca39a" class=" ml-auto mr-auto border-color" style="background-color:#cca39a;"></div>
                                        <div data-rgb="ffefcf" class=" ml-auto mr-auto border-color" style="background-color:#ffefcf;"></div>
                                    </div>
                                     <div id="color-hair" class="p-2 mt-7 d-none     border ">
                                        <div data-rgb="000000" class="ml-auto mr-auto border-color seleccionado" style="background-color:#000000;"></div>
                                        <div data-rgb="2d1b13" class="ml-auto mr-auto border-color" style="background-color:#2d1b13;"></div>
                                        <div data-rgb="e8d68b" class="ml-auto mr-auto border-color" style="background-color:#e8d68b;"></div>
                                        <div data-rgb="3f1414" class="ml-auto mr-auto border-color" style="background-color:#3f1414;"></div>
                                     </div>
                                  </div> 
                              </div>
                           </div>
                        </div>
                     </div>
                </div>
                <div class="d-none">
                   <img id="rostro1fac9b7" scr="/images/avatars/resources/rostro1/fac9b7.png"/>
                   <img id="rostro1ffefcf" scr="/images/avatars/resources/rostro1/ffefcf.png"/>
                   <img id="rostro1cca39a" scr="/images/avatars/resources/rostro1/cca39a.png"/>
                   <img id="rostro1c69a7b" scr="/images/avatars/resources/rostro1/c69a7b.png"/>
                   <img id="rostro15b4031" scr="/images/avatars/resources/rostro1/5b4031.png"/>

               </div>
                <style>
                    .avatar-default {
                        max-width: 85px;
                        margin: 4px;
                        cursor: pointer;
                        border-radius: 39%!important;
                    }
                    .colorbg {
                        display: inline-table;
                        width: 20px;
                        height: 20px;
                        margin: 2px;
                        width: 20px;
                        height: 20px;
                        cursor: pointer;
                     }

                    #avatar-selected {
                        border-radius: 39%!important;
                    }
                    
                    #colors-skin li:first-child {
                        margin-top: 10px;
                    }
                    #colors-skin {
                        padding-left: 20px!important;
                    }
                </style>

                    
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('angular/controller/avatarStudentCtrl.js') }}" defer></script>
@endsection

