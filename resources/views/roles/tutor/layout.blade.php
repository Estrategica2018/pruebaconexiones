@extends('layouts.app')

@section('content_layout')
    
    @include('roles/tutor/tutor_sidebar')
    
    <div class="content">

        @include('roles/tutor/tutor_navbar')

        <div class="row p-lg-4 p-md-3 p-sm-2 sticky-margin-top-ie">
            <div class="container">
                <div class="content">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3 card">
                               <div class="card-header">
                                  <div class="ml-auto mr-auto avatar-5xl">
                                     @if(isset(auth('afiliadoempresa')->user()->url_image))
                                       <img class="rounded-circle m-auto" src="{{asset(auth('afiliadoempresa')->user()->url_image)}}" width="150px" height="150px">
                                      @else 
                                       <img class="rounded-circle m-auto" src="{{asset('images/icons/default-avatar.png')}}" width="150px" height="150px">
                                     @endif 
                                  </div>
                                  <div class="mt-4 ml-auto mr-auto m-3 text-align" id="tutorProfileFullName">{{auth('afiliadoempresa')->user()->name }}
                                     {{auth('afiliadoempresa')->user()->last_name}}
                                  </div>
                                   <div class="ml-auto mr-auto text-align">
                                    <a href="{{route('tutor','conexiones')}}">
                                       <button class="btn btn-sm btn-outline-primary fs--3">Editar perfíl</button>
                                    </a>
                                   </div>
                               </div>
                               <div class="bg-light border-top card-body">
                                  <div class="row">
                                     <div class="mt-2 col-12 fs--1"  >
                                        <h6 class="font-weight-semi-bold ls mb-3 text-uppercase">Información de cuenta</h6>
                                        <div class="row">
                                           <div class="col-12">
                                              <p class="font-weight-semi-bold mb-1">Nombre de usuario</p>
                                           </div>
                                           <div class="col-12">{{auth('afiliadoempresa')->user()->user_name}}</div>
                                        </div>
                                        <div class="row">
                                           <div class="col-12">
                                              <p class="font-weight-semi-bold mb-1 mt-2 ">Correo electrónico</p>
                                           </div>
                                           <div class="col-12">{{auth('afiliadoempresa')->user()->email}}</div>
                                        </div>
                                        @if(auth('afiliadoempresa')->user()->last_payment_date())
                                        <div class="row">
                                           <div class="col-12">
                                              <p class="font-weight-semi-bold mb-1 mt-2 ">Fecha del último pago </p>
                                           </div>
                                           <div class="font-italic text-400 col-12">{{auth('afiliadoempresa')->user()->last_payment_date()}}</div>
                                        </div>
                                        @endif
                                        <div class="row">
                                           <div class="col-12">
                                              <p class="font-weight-semi-bold mb-1 mt-2 ">Fecha de creación de usuario </p>
                                           </div>
                                           <div class="font-italic text-400 col-12">{{auth('afiliadoempresa')->user()->created_at}}</div>
                                        </div>
                                     </div>
                                  </div>
                               </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="mb-3 card">
                                <div class="card-header">
                                    <ul class="nav">
                                        <li class="nav-item nav-item-tutor">
                                            <a class="avatar avatar-3xl tutor-button-head @if(\Route::current()->getName() == 'tutor.inscriptions' ) selected @endif"
                                            href="{{route('tutor.inscriptions','conexiones')}}">
                                                <img src="{{asset('images/icons/portal-padres/inscripciones-01.png')}}" class="ml-auto mr-auto" width="auto" height="42px"  style="width: auto;height: 42px;"/>
                                                <small class="fs--2 mb-1 text-700 font-weight-bold"> Inscripciones</small>
                                            </a>
                                        </li>
                                        <li class="nav-item nav-item-tutor">
                                            <a class="avatar avatar-3xl tutor-button-head @if(\Route::current()->getName() == 'tutor.products' ) selected @endif" 
                                                href="{{route('tutor.products','conexiones')}}">
                                                <img src="{{asset('images/icons/portal-padres/productos-01.png')}}" class="ml-auto mr-auto" width="auto" height="42px"  style="width: auto;height: 42px;"/>
                                                <small class="fs--2 mb-1 text-700 font-weight-bold"> Productos</small>
                                            </a>
                                        </li>
                                        <!--li class="nav-item nav-item-tutor">
                                            <a class="avatar avatar-3xl tutor-button-head @if(\Route::current()->getName() == 'tutor.calendar' ) selected @endif"
                                                href="{{route('tutor.inscriptions','conexiones')}}">
                                                <i class="fas fa-stopwatch icon"></i>
                                                <small class="fs--2 mb-1 text-700 font-weight-bold"> Calendario</small>
                                            </a>
                                        </li-->
                                        <li class="nav-item nav-item-tutor">
                                            <a class="avatar avatar-3xl tutor-button-head @if(\Route::current()->getName() == 'tutor.reports' ) selected @endif"
                                                href="{{ route('tutor.achievements',auth('afiliadoempresa')->user()->company_name()) }}" >
                                                <img src="{{asset('images/icons/portal-padres/reportes-01.png')}}" class="ml-auto mr-auto" width="auto" height="42px"  style="width: auto;height: 42px;"/>
                                                <small class="fs--2 mb-1 text-700 font-weight-bold"> Reportes</small>
                                            </a>
                                        </li>
                                        <li class="nav-item nav-item-tutor">
                                            <a class="avatar avatar-3xl tutor-button-head @if(\Route::current()->getName() == 'tutor.history' ) selected @endif"                                                
                                                href="{{route('tutor.history','conexiones')}}">
                                                <img src="{{asset('images/icons/portal-padres/historialPagos-01.png')}}" class="ml-auto mr-auto" width="auto" height="42px"  style="width: auto;height: 42px;"/>
                                                <div class="fs--2 mb-1 text-700 font-weight-bold" style="line-height: 1.2;margin-top: 6px;"> Historial de pagos</div>
                                            </a>
                                        </li>
                                        <li class="nav-item nav-item-tutor">
                                            <a class="avatar avatar-3xl tutor-button-head @if(\Route::current()->getName() == 'tutor.wishList' ) selected @endif"
                                            href="{{route('tutor.wishList','conexiones')}}">
                                                <img src="{{asset('images/icons/portal-padres/listaDeseos-01.png')}}" class="ml-auto mr-auto" width="auto" height="42px"  style="width: auto;height: 42px;"/>
                                                <small class="fs--2 mb-1 text-700 font-weight-bold"> Lista de deseos</small>
                                                
                                            </a>
                                        </li>
                                        <li class="nav-item nav-item-tutor">
                                            <a class="avatar avatar-3xl tutor-button-head @if(\Route::current()->getName() == 'tutor' ) selected @endif"
                                                href="{{route('tutor','conexiones')}}">
                                                <img src="{{asset('images/icons/portal-padres/configuracion-01.png')}}" class="ml-auto mr-auto" width="auto" height="42px"  style="width: auto;height: 42px;"/>
                                                <small class="fs--2 mb-1 text-700 font-weight-bold"> Conﬁguración</small>
                                            </a>
                                        </li>
                                        <li class="nav-item nav-item-tutor">
                                            <a class="avatar avatar-3xl tutor-button-head freePlan">
                                                <img src="{{asset('images/icons/portal-padres/free-01.png')}}" class="ml-auto mr-auto" width="auto" height="42px"  style="width: auto;height: 42px;"/>
                                                <small class="fs--2 mb-1 text-700 font-weight-bold"> Plan gratuito </small>
                                            </a>
                                        </li>
                                        <li class="nav-item nav-item-tutor">
                                            <a class="avatar avatar-3xl tutor-button-head" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                <img src="{{asset('images/icons/portal-padres/salir-01.png')}}" class="ml-auto mr-auto" width="auto" height="42px"  style="width: auto;height: 42px;"/>
                                                <small class="fs--2 mb-1 text-700 font-weight-bold"> Salir</small>
                                                <form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
                                                   @csrf
                                                </form>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="bg-light card-body min_height_panel">
                                        @yield('content-tutor-index')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready( function () {

                $('.freePlan').on('click',function(){
                    var data = new FormData();
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{route('validate_registry_free_plan',1)}}",
                        data: data,
                        cache: false,
                        contentType: false,
                        processData: false,
                        method: 'GET',
                        type: 'GET',
                        success: function (response, xhr, request) {
                            console.log(response)
                            if(response.status === 'successfull'){
                                swal({
                                    text: response.message,
                                    type: "success",
                                    showCancelButton: false,
                                    showConfirmButton: false
                                }).catch(swal.noop);
                            }else{
                                swal({
                                    text: response.message,
                                    type: "warning",
                                    showCancelButton: false,
                                    showConfirmButton: false
                                }).catch(swal.noop);
                            }

                        },
                        error: function (response, xhr, request) {
                            swal({
                                text: response.message,
                                type: "warning",
                                showCancelButton: false,
                                showConfirmButton: false
                            }).catch(swal.noop);
                        }
                    });
                })


            })
        </script>
        <footer class="w-100">
            @include('layouts/footer')
        </footer>
    </div>

@endsection
