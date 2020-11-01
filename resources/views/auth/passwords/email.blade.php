@extends('layouts.app_side')

@section('content')

@include('layouts/float_buttons')

<div class="container" ng-controller="passwordEmailCtrl" 
    ng-init="init('{{$email}}','{{old('email')}}','{{session('status')}}')">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Link de recuperación de contraseña ') }}</div>
                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('password.email',[$company->nick_name,$rol]) }}" id="sendEmail">
                        @csrf
                        <div class="form-group p-4">
                            @if(strlen($email)==0)
                            <input autocomplete='off' placeholder="Correo electrónico" name="email" id="email" type="text"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{old('email')}}" required autocomplete="name" autofocus>
                            @else
                            <input autocomplete='off' placeholder="Correo electrónico" name="email" id="email" type="text"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{$email}}" required autocomplete="name" autofocus>
                            @endif
                                
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary" id="submBtn">
                                    {{ __('Enviar link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="{{asset('/../angular/controller/passwordEmailCtrl.js')}}"></script>
@endsection