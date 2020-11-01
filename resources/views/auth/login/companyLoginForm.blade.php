@extends('layouts.appCompany')
@section('content')

@include('layouts/float_buttons')

<div class="flex-center min-vh-100 py-6 row">
   <div class="col-xxl-5 col-sm-11 col-md-9 col-lg-7 col-xl-6">
      
         <div class="d-flex flex-center font-weight-extra-bold fs-5 mb-4">
            <div class="avatar avatar-5xl">
               <img class="rounded-circle " src="{{ $company->url_icon }}" alt="">
            </div>
         </div>
      
      <div class="text-center card">
         <div class="card-header">{{ __('Inicio de sesion como ') }}<strong>alumno</strong></div>
         <div class="p-4 card-body">
            <form method="POST" action="{{ route('user.login','1') }}">
               @csrf
               <div class="form-group">
                  <input autocomplete='off' placeholder="Usuario" name="user_name" id="user_name" type="text" class="form-control @error('email') is-invalid @enderror" 
                  required autocomplete="name" autofocus>
                  @error('email')
                  <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                  </span>
                  @enderror
               </div>
               <div class="form-group">
                  <input placeholder="Contraseña" id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                  @error('password')
                  <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                  </span>
                  @enderror
               </div>
               <div class="form-check form-group">
                  <input name="check" id="exampleCheck" type="checkbox" class="form-check-input">
                  <label for="exampleCheck" class="form-check-label">Recordar datos</label>
               </div>
               <div class="form-group row mb-0">
                  <div class="col-md-8 offset-md-4">
                     <input type="hidden" name="company" value="{{$company->id}}"/>
                     <button type="submit" class="btn btn-primary">
                     {{ __('Entrar') }}
                     </button>
                  </div>
               </div>
               <div class="mt-2 custom-control">
                  <label class="label"><a href="{{route('password.sendlink',[$company->nick_name,1])}}">¿ Olvidaste tus datos ?</a></label>
               </div>
            </form>
         </div>
         <a href="#" class="pl-0 btn btn-link btn-sm" style="text-align: right;">
            Entrar como Profesor <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-right" class="svg-inline--fa fa-chevron-right fa-w-10 ml-1 fs--2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="currentColor" d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"></path></svg></a>
      </div>
   </div>
</div>
@endsection