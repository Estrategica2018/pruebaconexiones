@extends('roles.student.layout')

@section('content')
    <div class="container" ng-controller="profileStudentCtrl" ng-init="initProfile('{{$student->kidSelected()}}','{{$student->user_name}}','{{$student->url_image}}')">
        <div class="content row">
            <div class="col-8 mt-4 ml-auto mr-auto">
                <div class="border border-light rounded-radius-1 card card-body border-dark_opacity"  style="min-width: 24rem;">
                    <div class="position-relative card-body border border-dark_opacity rounded-radius-1 row h-75 m-1">
                        <div class="col-lg-5 p-0 text-align">
                        @if(isset($student->url_image)) 
                            <img src="{{asset($student->url_image)}}" width="264px" height="auto"/>
                            <div ng-repeat="avatar in avatars" class="d-none-result d-none">
                                <div ng-show="urlImage.includes(avatar.urlImage)">
                                    <h5 class="mt-3">@{{avatar.name}}</h5>
                                    <h6>@{{avatar.job}}</h6>
                                </div>
                            </div>
                        @else 
                            <img src="{{asset('images/icons/default-avatar.png')}}" width="264px" height="auto"/>
                        @endif
                            <a class="btn btn-sm btn-primary mt-2" href="{{route('student.avatar',['empresa'=>auth('afiliadoempresa')->user()->company_name()])}}">Editar Avatar</a>
                        </div>
                        <div class="col-lg-7 mt-3 mb-auto">
                            <div class="col-12 text-align-right">
                                <a class="btn btn-sm btn-primary mt-2" href="#" ng-click="editRegisterForm=true">Editar perfíl</a>
                            </div>
                            <h5> Nombres </h5>
                            <h5 class="mt-2 mb-4 border-bottom border-dark_opacity"><small> {{$student->name}}</small></h5>
                            <h5> Apellidos </h5>
                            <h5 class="mt-2 mb-4 border-bottom border-dark_opacity"><small> {{$student->last_name}} </small></h5>
                            <h5> Edad </h5>
                            <h5 class="mt-2 mb-4 border-bottom border-dark_opacity"><small> @if($age) {{$age}} años @endif</small></h5>
                            
                        </div>   
                        <div ng-show="editRegisterForm" class="d-none-result d-none dropdown-menu-card">
                           <div class="modal-backdrop fade show"></div>
                           <div class="position-absolute modal-menu card-notification shadow-none card" style="top: -65px;left: -33px;width: 100%;margin-left: -15px;min-width: 388px;">
                               <div ng-click="editRegisterForm=false" class="position_absolute fs-2 cursor-pointer" style="top: 3px;right: 16px;left: 35px;text-align: right;position: absolute;"> <i class="far fa-times-circle"></i> </div>
                               <div class="p-lg-6 p-sm-4">
                                   <div class="row">
                                        <h6><i class="fa fas fa-arrow-right arrow-icon"></i>Registra los datos del estudiante</h6>
                                        <div class="col-12 d-flex mt-3">
                                            <div class="register-avatar-kid" ng-click="onKidSelected('niño')" ng-class="{'selected':kidSelected==='niño'}">
                                                <img src="{{asset('images/icons/kid2.png')}}" width="103px;"/>
                                                <span>Niño</span>
                                            </div>
                                            <div class="register-avatar-kid" ng-click="onKidSelected('niña')" ng-class="{'selected':kidSelected==='niña'}">
                                                <img src="{{asset('images/icons/kid1.png')}}" width="103px;"/>
                                                <span>Niña</span>
                                            </div>
                                            <div class="register-avatar-kid" ng-click="onKidSelected('joven')" ng-class="{'selected':kidSelected==='joven'}">
                                                <img src="{{asset('images/icons/kid3.png')}}" width="103px;"/>
                                                <span>Joven</span>
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-4">
                                            <label class=""><i class="fa fas fa-arrow-right arrow-icon"></i>{{ __('Nombre') }}</label>
                                            <input placeholder="" type="text" name="name"
                                                   class="form-control" ng-class="{'is-invalid':errorName}" value="{{$student->name}} ">
                                            <span class="invalid-feedback" role="alert" ng-show="errorName">
                                                <strong>@{{ errorName }}</strong>
                                            </span>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label class="">{{ __('Apellido') }}</label>
                                            <input placeholder="" type="text" name="last_name" 
                                                   ng-class="{'is-invalid':errorLastName}" class="form-control" value="{{$student->last_name}} ">
                                            <span class="invalid-feedback" role="alert" ng-show="errorLastName"> 
                                                 <strong>@{{ errorLastName }}</strong>
                                            </span>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label class="">{{ __('Fecha de nacimiento') }}</label>
                                            <input placeholder="día/mes/año" type="date" name="birthday"
                                                   class="" ng-class="{'is-invalid':errorBirthday}" value="{{$student->birthday}}"/>
                                            <span class="invalid-feedback" role="alert"  ng-show="errorBirthday"> 
                                                <strong>@{{ errorBirthday }}</strong>
                                            </span>
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label class=""><i class="fa fas fa-arrow-right arrow-icon"></i>{{ __('Usuario') }}</label>
                                            <input placeholder="" type="text" name="user_name"
                                                   class="form-control" ng-class="{'is-invalid':mbInvalidate}" value="{{$student->user_name}}"/>
                                            <span class="invalid-feedback" role="alert" ng-show="mbInvalidate">
                                                 <strong>@{{mbInvalidate}}</strong>
                                            </span>
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label class=""><i class="fa fas fa-arrow-right arrow-icon"></i>{{ __('Asignar contraseña') }}</label>
                                            <div class="input-group">
                                                <input id="txtPassword" type="Password" name="password" class="form-control" ng-class="{'is-invalid':errorPassword}" value="">
                                                <div class="input-group-append">
                                                    <button id="show_password" class="btn btn-primary" type="button" ng-click="viewPassword('txtPassword')"> <span class="fa fa-eye-slash icon"></span> </button>
                                                </div>
                                                <span class="invalid-feedback" role="alert" ng-show="errorPassword">
                                                  <strong>@{{errorPassword}}</strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-small btn-primary d-flex" ng-click="onEditStudent()" ng-disabled="!validateUserName">
                                        <div ng-show="loadingRegistry"><i class="fa fa-spinner fa-spin mr-2"></i></div>
                                        {{ __('Editar') }}
                                    </button>
                                    <span ng-show="errorMessageRegister" class="invalid-feedback" role="alert">
                                         <strong>@{{errorMessageRegister}}</strong>
                                    </span>
                               </div>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{asset('/../angular/controller/profileStudentCtrl.js')}}"></script>
@endsection
