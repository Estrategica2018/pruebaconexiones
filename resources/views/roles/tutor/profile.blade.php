@extends('roles.tutor.layout')

@section('content-tutor-index')
    <script src="https://cdn.jsdelivr.net/gh/jamesssooi/Croppr.js@2.3.0/dist/croppr.min.js"></script>
    <link href="https://cdn.jsdelivr.net/gh/jamesssooi/Croppr.js@2.3.0/dist/croppr.min.css" rel="stylesheet"/>

<script>
         document.addEventListener('DOMContentLoaded', () => {

             // Input File
             const inputImage = document.querySelector('#upfile');
             // Nodo donde estará el editor
             const editor = document.querySelector('#editor');
             // El canvas donde se mostrará la previa
             const miCanvas = document.querySelector('#preview');
             // Contexto del canvas
             const contexto = miCanvas.getContext('2d');
             // Ruta de la imagen seleccionada
             let urlImage = undefined;
             // Evento disparado cuando se adjunte una imagen
             inputImage.addEventListener('change', abrirEditor, false);
             let imagenEn64 = '';
             

             /**
              * Método que abre el editor con la imagen seleccionada
              */
             function abrirEditor(e) {
                 
                $('#result').addClass('show');
                $('#result').removeClass('d-none');

                 // Obtiene la imagen
                 urlImage = URL.createObjectURL(e.target.files[0]);

                 // Borra editor en caso que existiera una imagen previa
                 editor.innerHTML = '';
                 let cropprImg = document.createElement('img');
                 cropprImg.setAttribute('id', 'croppr');
                 editor.appendChild(cropprImg);

                 // Limpia la previa en caso que existiera algún elemento previo
                 contexto.clearRect(0, 0, miCanvas.width, miCanvas.height);

                 // Envia la imagen al editor para su recorte
                 document.querySelector('#croppr').setAttribute('src', urlImage);

                 // Crea el editor
                 new Croppr('#croppr', {
                     aspectRatio: 1,
                     startSize: [70, 70],
                     onCropEnd: recortarImagen
                 })
             }

             /**
              * Método que recorta la imagen con las coordenadas proporcionadas con croppr.js
              */
             function recortarImagen(data) {
                 
                 // Variables
                 const inicioX = data.x;
                 const inicioY = data.y;
                 const nuevoAncho = data.width;
                 const nuevaAltura = data.height;
                 const zoom = 1;
                 
                 // La imprimo
                 miCanvas.width = nuevoAncho;
                 miCanvas.height = nuevaAltura;
                 
                 // La declaro
                 let miNuevaImagenTemp = new Image();
                 // Cuando la imagen se carge se procederá al recorte
                 miNuevaImagenTemp.onload = function() {
                     // Se recorta
                     contexto.drawImage(miNuevaImagenTemp, inicioX, inicioY, nuevoAncho * zoom, nuevaAltura * zoom, 0, 0, nuevoAncho, nuevaAltura);
                     // Se transforma a base64
                     imagenEn64 = miCanvas.toDataURL("image/jpeg");
                     // Mostramos el código generado
                     //document.querySelector('#base64').textContent = imagenEn64;
                     //document.querySelector('#base64HTML').textContent = '<img src="' + imagenEn64.slice(0, 40) + '...">';

                 }
                 // Proporciona la imagen cruda, sin editarla por ahora
                 miNuevaImagenTemp.src = urlImage;
             }
             
             
             $( "#saveBtnPicture" ).click(savePicture);
             
             function savePicture() {
                 
                 $('#result').removeClass('show');
                 $('#result').addClass('d-none');
                 //$('#preview').removeClass('d-none');
                 
                 $('#move').removeClass('d-none');
                 
                 var data = new FormData();
                    data.append('image', imagenEn64);
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{route('edit_image_perfil')}}",
                        data: data,
                        cache: false,
                        contentType: false,
                        processData: false,
                        method: 'POST',
                        type: 'POST',
                        success: function (response, xhr, request){
                            $('#move').addClass('d-none');
                            if(response.valid){
                                $('.rounded-pill,.rounded-circle ').attr("src",response.imagenNueva+"?timestamp=" + new Date().getTime());
                                swal({
                                    text: 'Imagen editada exitósamente',
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
                            $('#move').addClass('d-none');
                            swal({
                                text: response.message,
                                type: "warning",
                                showCancelButton: false,
                                showConfirmButton: false
                            }).catch(swal.noop);
                        }
                    });
                    
             }

         });
        </script>
        
    <div class="" ng-controller="tutorProfileCtrl" ng-init="init({{$tutor}},{{$statusValidationFreePlan}})">
        <h5 class="mt-3">Mi perfíl</h5>
        <div class="row pl-4 pb-4 pt-4 pr-4">
            <div class="col-3">Imagen de perfíl</div>
            <div class="col-6">
            @if(isset(auth('afiliadoempresa')->user()->url_image)) 
                <img class="rounded-pill" src="{{asset(auth('afiliadoempresa')->user()->url_image)}}" width="60px" height="60px" style="margin:-15px;">
            @else 
                <img class="rounded-pill" src="{{asset('images/icons/default-avatar.png')}}" width="60px" height="60px" style="margin:-15px;">
            @endif 
                <canvas id="preview" class="d-none" style="width:300px;height:300px;"></canvas>
            
            </div>
            <div class="col-3">
                <div style="cursor: pointer" class="btn btn-sm btn-primary" id="yourBtn" ng-click="getFile()">Cambiar</div>
                <div style='height: 0px;width: 0px; overflow:hidden;'>
                <input id="upfile" type="file" value="upload" accept="image/*" /></div>
            </div>
        </div>
        <div class="row pl-4 pb-4 pt-1 pr-4">
            <div class="col-3">Nombre</div>
            <div class="col-6" id="div_name">@{{tutor.name}}</div>
            <div class="col-3"><button class="btn btn-sm btn-primary" ng-click="registerUserForm(1)">Cambiar</button></div>
        </div>
        <div class="row pl-4 pb-4 pt-1 pr-4">
            <div class="col-3">Apellido</div>
            <div class="col-6" id="div_last">@{{tutor.last_name}}</div>
            <div class="col-3"><button class="btn btn-sm btn-primary" ng-click="registerUserForm(2)">Cambiar</button></div>
        </div>
        <div class="row pl-4 pb-4 pt-1 pr-4">
            <div class="col-3">Localidad</div>
            <div class="col-6" id="div_country">@{{tutor.country.name}}   
                @{{tutor.city && tutor.city.id  ? ' - ' + tutor.city.name: tutor.city_name ? ' - ' + tutor.city_name : '' }}</div>
            <div class="col-3">
            <button class="btn btn-sm btn-primary" ng-click="registerUserForm(4)">Cambiar</button>
            </div>
        </div>
        <div class="row pl-4 pb-4 pt-1 pr-4">
            <div class="col-3">Teléfono</div>
            <div class="col-6" id="div_phone">@{{tutor.phone}}</div>
            <div class="col-3"><button class="btn btn-sm btn-primary" ng-click="registerUserForm(3)">Cambiar</button></div>
        </div>
        <h5 class="mt-3">Cuenta</h5>
        <form class="" ng-submit="changePassword(changePasswordForm)" name="changePasswordForm" id="changePasswordForm" novalidate>
            <div class="row pl-4 pb-4 pt-1 pr-4">
                <div class="col-6">
                    <label class=""><i class="fa fas fa-arrow-right arrow-icon"></i>{{ __('Contraseña actual') }}</label>
                    <div class="input-group">
                        <input id="txtPassword1" type="Password" name="password1"  ng-model="tutor.password1"
                               class="form-control" value="">
                        <div class="input-group-append">
                            <button id="show_password1" class="btn btn-primary" type="button" ng-click="viewPassword('txtPassword1')"> <span class="fa fa-eye-slash icon txtPassword1"></span> </button>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <label class=""><i class="fa fas fa-arrow-right arrow-icon"></i>{{ __('Nueva contraseña') }}</label>
                    <div class="input-group">
                        <input id="txtPassword2" type="password" name="password2"  ng-model="tutor.password2"
                               class="form-control" value="">
                        <div class="input-group-append">
                            <button id="show_password2" class="btn btn-primary" type="button" ng-click="viewPassword('txtPassword2')"> <span class="fa fa-eye-slash icon txtPassword2"></span> </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pl-4 pb-4 pt-1 pr-4">
                <div class="col-5"><button class="btn btn-sm btn-primary" ng-click="onChangePassword()">Cambiar contraseña</button></div>
            </div>
        </form>
        <div ng-show="newRegisterForm" class="d-none-result d-none dropdown-menu-card" id="elementkitsModal" style="">
            <div class="modal-backdrop fade show"></div>
            <div class="w-100 w-lg-75 position-absolute modal-menu card-notification shadow-none card" style="top: 0px;margin-left: -15px;">
                <div ng-click="newRegisterForm=false" class="position_absolute fs-2 cursor-pointer" style="top: 3px;right: 16px;left: 35px;text-align: right;position: absolute;"> <svg class="svg-inline--fa fa-times-circle fa-w-16" aria-hidden="true" focusable="false" data-prefix="far" data-icon="times-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm101.8-262.2L295.6 256l62.2 62.2c4.7 4.7 4.7 12.3 0 17l-22.6 22.6c-4.7 4.7-12.3 4.7-17 0L256 295.6l-62.2 62.2c-4.7 4.7-12.3 4.7-17 0l-22.6-22.6c-4.7-4.7-4.7-12.3 0-17l62.2-62.2-62.2-62.2c-4.7-4.7-4.7-12.3 0-17l22.6-22.6c4.7-4.7 12.3-4.7 17 0l62.2 62.2 62.2-62.2c4.7-4.7 12.3-4.7 17 0l22.6 22.6c4.7 4.7 4.7 12.3 0 17z"></path></svg><!-- <i class="far fa-times-circle"></i> --> </div>
                <div class="p-4">
                    <div class="">
                        <div class="row">
                            <div class="form-group col-lg-7">
                                <h5 id="label_name" class="mb-3">
                                    <i class="fa fas fa-arrow-right arrow-icon"></i>@{{labelName}}
                                </h5>
                                <div ng-show="labelName!=='Localidad'">
                                    <input placeholder="" type="text" name="varChange" ng-model="varChange" class="form-control ng-pristine ng-untouched ng-valid ng-empty" value="">
                                </div>
                                <div ng-show="labelName==='Localidad'">
                                    <h6>País</h6>
                                    <select id="selectCountry" ng-change="onChangeCountry()" name="country_id" ng-model="copyTutor.country_id">
                                        <option selected value="-1"> Seleccione ...
                                        </option>
                                        <option value="@{{country.id}}" ng-repeat="country in countries">
                                            @{{country.text}}
                                        </option>
                                    </select>

                                    <h6 class="mt-lg-3">Ciudad</h6>

                                    <div ng-show="showCitySelect" >
                                        <select id="selectCity" ng-model="copyTutor.city_id" name="selectCity"
                                        class="select2_group form-control d-none-result d-none is-invalid">
                                          
                                        </select>
                                    </div>
                                    <div ng-hide="showCitySelect">
                                        <input class="form-control" ng-required="!showCitySelect" ng-model="copyTutor.city_name" type="text" id="city"
                                        name="city" autocomplete="off" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row mt-lg-5 ml-5" style="margin-block-end: auto;">
                                <button type="submit" class="btn btn-small btn-primary d-flex" ng-click="onEdit(inputToEdit)">
                                    <div ng-show="loadingRegistry" class="ng-hide"><svg class="svg-inline--fa fa-spinner fa-w-16 fa-spin mr-2" aria-hidden="true" focusable="false" data-prefix="fa" data-icon="spinner" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg><!-- <i class="fa fa-spinner fa-spin mr-2"></i> --></div>
                                    Editar campo
                                </button>
                                <span ng-show="errorMessageRegister" class="invalid-feedback ng-hide" role="alert">
                                     <strong class="ng-binding"></strong>
                                  </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="result" class="fade show d-none" >
            <div class="modal-backdrop fade show"></div>
            <div class="modal-menu position-absolute shadow-none card card-body d-flex" style="top: 0px;width: 100%;margin: auto; z-index:1989;">
                <div id="editor" style="width: 458px;height: auto;margin: auto;"></div>
                <button class="btn btn-sm btn-primary w-50 mr-auto ml-auto mt-4" id="saveBtnPicture">  <i id="move" class="d-none fa fa-spinner fa-spin"></i> Recortar </button>
            </div>
            
        </div>
    </div>
    
    <script src="{{ asset('angular/controller/TutorProfileCtrl.js') }}" defer></script>
    
    
@endsection

