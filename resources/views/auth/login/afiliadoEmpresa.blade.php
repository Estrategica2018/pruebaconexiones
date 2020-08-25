@extends('layouts.app_side')

@section('content')

@include('layouts/float_buttons')

    <div class="justify-content-center" ng-controller="LoginCtrl">
    <div class=" row">
        <div style="margin-top: 15px" class="border-top-4 col-md-6 col-sm-12">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                    <img class="ml-8" src="{{asset('images/icons/register-student.png')}}" width="auto" height="70px" />
                        <img class="ml-3 d-flex" src="{{asset('images/icons/iconoEstudiante-80.png')}}" width="90px" height="90px"/>
                    </div>
                    
                    <div class="card-body pt-0">
                        <form method="POST" action="{{ route('user.login','1') }}">
                            @csrf

                            <div class="form-group">
                                <input autocomplete='off' placeholder="Usuario" name="user_name" id="user_name" type="text" 
                                class="form-control " value="@if(old('user_name') && session('rol_trans') == 1) {{ old('user_name') }} @endif" required autocomplete="name" autofocus>
                            </div>

                            <div class="form-group">
                                <input placeholder="Contraseña" id="password" type="password" 
                                class="form-control 
                                @if($errors->has('user_name') || $errors->has('email') )
                                    @if(session('rol_trans') == 1)
                                    is-invalid
                                    @endif
                                @endif
                                "
                                name="password" required autocomplete="current-password">

                                @error('user_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-check form-group d-flex">
                                <input name="check" id="exampleCheck" type="checkbox" class="form-check-input">
                                <label for="exampleCheck" class="form-check-label">Recordar datos</label>
                                <div class="ml-3">
                                    <input type="hidden" name="company" value="{{$company->id}}"/>
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Entrar') }}
                                    </button>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                            </div>
                            
                            <div class="mt-2 custom-control">
                                @if (Route::has('password.sendlink'))
                                <label class="label"><a ng-click="notifyStudent()">¿ Olvidó sus datos ?</a></label>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>    
        <div style="margin-top: 15px" class="border-top-4 col-md-6 col-sm-12 justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <img class="ml-8" src="{{asset('images/icons/register-tutor.png')}}" width="auto" height="70px" />
                        <img class="ml-3 d-flex" src="{{asset('images/icons/iconoAdulto-80.png')}}" width="90px" height="90px"/>
                    </div>
                    <div class="card-body pt-0">
                        <form method="POST" action="{{ route('user.login','3') }}">
                            @csrf
                            
                            <div class="form-group">
                                <input autocomplete='off' placeholder="Usuario" name="user_name" id="user_name" type="text" 
                                class="form-control" value="@if(old('user_name') && session('rol_trans') == 3) {{ old('user_name') }} @endif" required autocomplete="name" autofocus>
                            </div>
                            <div class="form-group">
                                <input placeholder="Contraseña" id="password" type="password" 
                                class="form-control 
                                @if($errors->has('user_name') || $errors->has('email') )
                                    @if(session('rol_trans') == 3)
                                    is-invalid
                                    @endif
                                @endif
                                " name="password" required autocomplete="current-password">
                                @error('user_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            
                            <div class="form-check form-group d-flex">
                                <input name="check" id="exampleCheck" type="checkbox" class="form-check-input">
                                <label for="exampleCheck" class="form-check-label">Recordar datos</label>
                                <div class="ml-3">
                                    <input type="hidden" name="company" value="{{$company->id}}"/>
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Entrar') }}
                                    </button>
                                    <button class="btn btn-secondary" onclick="location='{{route('registerForm')}}'" >
                                        {{ __('Registro') }}
                                    </button>
                                </div>
                            </div>
                            
                            <div class="form-group row mb-0">
                                <div class="mt-2 custom-control">
                                    @if (Route::has('password.sendlink'))
                                    <label class="label"><a href="{{route('password.sendlink',[$company->nick_name,3])}}">¿ Olvidó sus datos ?</a></label>
                                    @endif
                                </div>

                            </div>
                        </form>
                        <form id="goToProvider" method="GET">
                         </form>
                        <div class="row">
                            <div style="z-index:1041;"  id="formFacebook" 
                            action="{{ route('user.redirectfacebook',[encrypt(3),'register']) }}" 
                            class="col-12 mt-2"  style="height:43px">
                                <button type="button" class="btn btn-primary btn-block d-flex h-100" ng-click="goToFacebook()">
                                    <i class="fab fa-facebook fs-3 mr-2"></i>
                                    <span class="fs--1">Ingresar con Facebook</span>
                                </button>
                            </div>
                            <div style="z-index:1041; height:43px"  id="formGmail" action="{{ route('user.redirectgmail',[encrypt(3),'register']) }}" class="col-12">
                                <button type="button" class="btn btn-primary btn-block  d-flex mt-2 h-100" 
                                       style="background-color: #dd4b39;border-color: rgb(221, 75, 57);z-index:1041;" ng-click="goToGmail()">
                                  <i class="fab fa-google fs-2 mr-2"></i>
                                  <span class="fs--1">Ingresar con Gmail</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="{{ asset('angular/controller/LoginCtrl.js') }}" defer></script>
@endsection
