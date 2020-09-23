<form novalidate name="myForm">
    <div class="">
        <div class="row">
            <h6><i class="fa fas fa-arrow-right arrow-icon"></i>Registra los datos del estudiantess</h6>
            <div class="col-12 d-flex mt-3">
                <div class="register-avatar-kid" ng-click="onKidSelected('niño')" ng-class="{'selected':newStudent.kidSelected==='niño'}">
                    <img src="{{asset('images/icons/kid2.png')}}" width="93px;" height="auto"/>
                    <h6>Niño</h6>
                </div>
                <div class="register-avatar-kid" ng-click="onKidSelected('niña')" ng-class="{'selected':newStudent.kidSelected==='niña'}">
                    <img src="{{asset('images/icons/kid1.png')}}" width="93px;" height="auto"/>
                    <h6>Niña</h6>
                </div>
                <div class="register-avatar-kid" ng-click="onKidSelected('joven')" ng-class="{'selected':newStudent.kidSelected==='joven'}" style="width: 218px;">
                    <img src="{{asset('images/icons/kid3.png')}}" width="186px;" height="auto"/>
                    <h6>Joven</h6>
                </div>
            </div>

            <div class="form-group col-lg-4">
                <label class=""><i class="fa fas fa-arrow-right arrow-icon"></i>{{ __('Nombre') }}</label>
                <input placeholder="" type="text" name="name" ng-model="newStudent.name" required
                       class="form-control @error('name') is-invalid @enderror" value="">
                @error('name')
                <span class="invalid-feedback" role="alert">
                     <strong>{{ $message }}</strong>
                  </span>
                @enderror
            </div>
            <div class="form-group col-lg-4">
                <label class="">{{ __('Apellido') }}</label>
                <input placeholder="" type="text" name="last_name"  ng-model="newStudent.last_name" required
                       class="form-control @error('last_name') is-invalid @enderror" value="">
                @error('last_name')
                <span class="invalid-feedback" role="alert">
                     <strong>{{ $message }}</strong>
                  </span>
                @enderror
            </div>
            <div class="form-group col-lg-4">
                <label class="">{{ __('Fecha de nacimiento') }}</label>
                <input placeholder="día/mes/año" type="date" name="birthday"  ng-model="newStudent.birthday"
                       class="form-control @error('birthday') is-invalid @enderror" value="">
                @error('birthday')
                <span class="invalid-feedback" role="alert">
                     <strong>{{ $message }}</strong>
                  </span>
                @enderror
            </div>
            <div class="form-group col-lg-4">
                <label class=""><i class="fa fas fa-arrow-right arrow-icon"></i>{{ __('Usuario') }}</label>
                <input placeholder="" type="text" name="user_name" ng-blur="onValidateUserName()" ng-keydown="onValidateUserName()" ng-model="newStudent.user_name" required
                       class="form-control" value="" ng-class="(validateUserName)?'':'is-invalid'">
                <span class="invalid-feedback" role="alert">
                     <strong>Usuario no disponible</strong>
                </span>
            </div>
            <div class="form-group col-lg-6">
                <label class=""><i class="fa fas fa-arrow-right arrow-icon"></i>{{ __('Asignar y/o modificar contraseña') }}</label>
                <div class="input-group">
                    <input ID="txtPassword" type="Password" name="password"  ng-model="newStudent.password"
                           class="form-control @error('last_name') is-invalid @enderror" value="">
                    <div class="input-group-append">
                        <button id="show_password" class="btn btn-primary" type="button" ng-click="viewPassword('txtPassword')"> <span class="fa fa-eye-slash icon txtPassword"></span> </button>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-small btn-primary d-flex" ng-click="onEditStudent()" ng-disabled="(myForm.$invalid || myForm.$pending) || !validateUserName">
            <div ng-show="loadingRegistry"><i class="fa fa-spinner fa-spin mr-2"></i></div>
            {{ __('Editar Registro') }}
        </button>
        <span ng-show="errorMessageRegister" class="invalid-feedback" role="alert">
                 <strong>@{{errorMessageRegister}}</strong>
        </span>
    </div>
</form>