@extends('layouts.app_side')
@section('plugins')
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('css/select2.min.css')}}" rel="stylesheet">
    <style>
        .input-group-prepend, .input-group-append {
            display:block ;
        }
    </style>
@endsection
@section('content')
    <div class="container" ng-controller="managmentKitElementCtrl" ng-init="init('{{route('create_or_update_element')}}','{{route('create_or_update_kit')}}','{{route('get_elements')}}')">
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">@{{actionElement}} elemento</h5><button class="close" type="button" data-dismiss="modal" aria-label="Close"><span class="font-weight-light" aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="name"><strong>Nombre</strong></label>
                                    <input class="form-control" id="name" ng-model="element.name" type="text" placeholder="Nombre">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleFormControlSelect1"><strong>Cantidad</strong></label>
                                    <input class="form-control" id="quantity" type="number" ng-model="element.quantity" placeholder="Cantidad">
                                </div>
                            </div>
							<div class="row">
                                <div class="col-md-6 input-group mb-3 mt-4">
                                    <div class="input-group-prepend"><span class="input-group-text">Precio ($) </span></div><input class="form-control"  ng-model="element.cost" type="text" aria-label="Amount (to the nearest dollar)">
                                    <div class="input-group-append"><span class="input-group-text">.00</span></div>
                                </div>
								<div class="form-group col-md-6">
                                    <label class=""><strong>{{ __('Fecha de expiración') }}</strong></label>
									<div class="d-flex">
										<input placeholder="día/mes/año" type="date" name="end_date" ng-model="element.end_date"
											   class="form-control w-90 mr-2"/>
										<a ng-click="element.end_date = null"><i class="far fa-times-circle cursor-pointer"></i></a>
									</div>
                                </div>
                            </div>
							<div class="line-separator"></div>
                            <div class="row">
                                <div class="form-group col-md-6">
									<div>
                                        <label for="exampleFormControlSelect2"><strong>Cáratula</strong> (Seleccione imagen)</label>
                                        <div class="col-12 d-flex">
                                            <h6 class="p-2 mt-3 cursor-pointer conex-table card-header w-100" style="border: 1px solid;"
                                                ng-click="mbImageShow=!mbImageShow">Directorio: <small>@{{directoryPath}}</small>
                                            </h6>
                                            <div ng-show="!mbImageShow" class="cursor-pointer" ng-click="mbImageShow = true;"
                                                 style="position: absolute;right: 35px;top: 19px;">
                                                <i class="fas fa-caret-down"></i>
                                            </div>
                                            <div ng-hide="!mbImageShow" class="cursor-pointer" ng-click="alert('up');mbImageShow = false;"
                                                 style="position: absolute;right: 35px;top: 19px;">
                                                <i class="fas fa-caret-up"></i>
                                            </div>
                                        </div>
                                        <div class="bg-light mb-3 row edit-div-folder pt-2" ng-show="mbImageShow">
                                            <ul class="p-0 list-inline mt-2 mb-0">
                                                <li class="mb-2 ml-2" ng-repeat="field in directory">
                                                    <a class="btn btn-sm btn-outline-primary" href="#" ng-click="onChangeFolderImage(field.dir)">
                                                        @{{field.name}}
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="col-12 row mt-3">
                                                <div ng-repeat="field in filesImages" class="col-4">
                                                    <img ng-src="{{env('APP_URL')}}/@{{field.url_image}}" width="79px" height="auto" ng-click="onImgChange(field)"
                                                         class="cursor-pointer" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
									<label for="exampleFormControlSelect2"><strong>Carrusel imagenes</strong> (Seleccione directorio)</label>
                                    <div class="col-12 d-flex">
                                        <h6 class="p-2 mt-3 cursor-pointer conex-table card-header w-100" style="border: 1px solid;"
                                            ng-click="mbImageShow2=!mbImageShow2">Directorio: <small>@{{directoryPath2}}</small>
                                        </h6>
                                        <div ng-show="!mbImageShow2" class="cursor-pointer" ng-click="mbImageShow2 = true;"
                                             style="position: absolute;right: 35px;top: 19px;">
                                            <i class="fas fa-caret-down"></i>
                                        </div>
                                        <div ng-hide="!mbImageShow2" class="cursor-pointer" ng-click="alert('up');mbImageShow2 = false;"
                                             style="position: absolute;right: 35px;top: 19px;">
                                            <i class="fas fa-caret-up"></i>
                                        </div>
                                    </div>
                                    <div class="bg-light mb-3 row edit-div-folder pt-2" ng-show="mbImageShow2">
                                        <ul class="p-0 list-inline mt-2 mb-0">
                                            <li class="mb-2 ml-2" ng-repeat="field2 in directory2">
                                                <a class="btn btn-sm btn-outline-primary" href="#" ng-click="onChangeFolderImage2(field2.dir)">
                                                    @{{field2.name}}
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="col-12 row mt-3">
                                            <div ng-repeat="field in filesImages2" class="col-4">
                                                <img ng-src="{{env('APP_URL')}}/@{{field.url_image}}" width="79px" height="auto"
                                                     class="cursor-pointer" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="line-separator mt-0"></div>
                            <div class="mt-3" style="border: 1px solid;">
								<div class="col-12 d-flex pt-2 " ng-click="showSequencesDetail=!showSequencesDetail">
									<h6 class="cursor-pointer w-100">
										Configuración por secuencias y momentos
									</h6>
									<div ng-show="!showSequencesDetail" class="cursor-pointer"
										 style="position: absolute;right: 35px;top: 3px;">
										<i class="fas fa-caret-down"></i>
									</div>
									<div ng-hide="!showSequencesDetail" class="cursor-pointer" 
										 style="position: absolute;right: 35px;top: 3px;">
										<i class="fas fa-caret-up"></i>
									</div>
								</div>  
								<div ng-show="showSequencesDetail" class="col-12 border">
									<div class="row">
										<div class="form-group col-md-6">
											<label for="exampleFormControlSelect1">Secuencia</label>
											<select id="selectCity"
													ng-class="{'select2_group':true, 'form-control':true}"
													class="select2_group form-control selectpicker" ng-model="sequenceSelected" ng-change="sequenceChange(sequenceSelected)">
												<option></option>
												<option ng-repeat="x in sequences" value="@{{x.id}}|@{{x.name}}">@{{x.name}}</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="exampleFormControlSelect1">Momento</label>
											<select class="form-control selectpicker" id="exampleFormControlSelect1"
													ng-model="momentSelected" multiple>
												<option></option>
												<option ng-repeat="x in moments" value="@{{x.id}}|@{{x.name}}">@{{x.name}}</option>
											</select>
										</div>
										<div class="col-md-12">
											<button class="btn btn-success btn-sm btn-block" ng-click="addSequenceMoment()">Agregar</button>
										</div>
									</div>
									<br>
									<div class="row" ng-repeat="x in arraySequenceMomentEdit">
										<div class="col-md-5" >
											<label for="name">Secuencia</label>
											<input class="form-control" type="text" placeholder="nombre" ng-model="x.sequence_name">
										</div>
										<div class="col-md-5">
											<label for="exampleFormControlTextarea1">Momento</label>
											<input class="form-control" type="text" placeholder="nombre" ng-model="x.moment_name">
										</div>
										<div class="form-group col-md-2">
											<button class="btn btn-sm btn-warning mt-4" ng-click="deleteSequenceMomentEdit(x.id,$index,x.id_moment_kit)"> - </button>
										</div>
									</div>
									<div class="row" ng-repeat="x in arraySequenceMoment">
										<div class="col-md-5" >
											<label for="name">Secuencia</label>
											<input class="form-control" type="text" placeholder="Descripción" ng-model="x.name">
										</div>
										<div class="col-md-5">
											<label for="exampleFormControlTextarea1">Momentos</label>
											<textarea class="form-control" id="description" rows="1" ng-model="x.moments_name"></textarea>
										</div>
										<div class="form-group col-md-2">
											<button class="btn btn-sm btn-warning mt-4" ng-click="deleteSequenceMoment($index)"> - </button>
										</div>
									</div>
								</div>
							</div>
							<div class="line-separator"></div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="exampleFormControlTextarea1"><strong>Descripción</strong></label>
                                    <textarea class="form-control" ng-model="element.description" id="description" rows="3"></textarea>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
                        <button class="btn btn-sm" ng-class="(actionElement == 'Crear')?'btn-primary':'btn-warning'" type="button" id="onEdit" ng-click="createOrUpdateElement(actionElement)"><i id="move" class=""></i>@{{actionElement}}</button></div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="exampleModalKit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">@{{actionKit}} kit</h5><button class="close" type="button" data-dismiss="modal" aria-label="Close"><span class="font-weight-light" aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="name"><strong>Nombre</strong></label>
                                    <input class="form-control" id="nameKit" ng-model="kit.name" type="text" placeholder="Nombre">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleFormControlSelect1"><strong>Cantidad</strong></label>
                                    <input class="form-control" id="quantityKit" type="number" ng-model="kit.quantity" placeholder="Cantidad">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 input-group mb-3 mt-4">
                                    <div class="input-group-prepend"><span class="input-group-text">Precio ($) </span></div><input class="form-control"  ng-model="kit.cost" type="text" aria-label="Amount (to the nearest dollar)">
                                    <div class="input-group-append"><span class="input-group-text">.00</span></div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class=""><strong>{{ __('Fecha de expiración') }}</strong></label>
									<div class="d-flex">
										<input placeholder="día/mes/año" type="date" name="end_date" ng-model="kit.end_date"
											   class="form-control w-90 mr-2"/>
										<a ng-click="kit.end_date = null"><i class="far fa-times-circle cursor-pointer"></i></a>
									</div>
                                </div>
                            </div>
							<div class="line-separator"></div>
                            <div class="row">
                                <div class="form-group col-md-6">
								    <div>
										<label for="exampleFormControlSelect2"><strong>Cáratula</strong> (Seleccione imagen)</label>
                                        <div class="col-12 d-flex">
                                            <h6 class="p-2 mt-3 cursor-pointer conex-table card-header w-100" style="border: 1px solid;"
                                                ng-click="mbImageShow=!mbImageShow">Directorio: <small>@{{directoryPath}}</small>
                                            </h6>
                                            <div ng-show="!mbImageShow" class="cursor-pointer" ng-click="mbImageShow = true;"
                                                 style="position: absolute;right: 35px;top: 19px;">
                                                <i class="fas fa-caret-down"></i>
                                            </div>
                                            <div ng-hide="!mbImageShow" class="cursor-pointer" ng-click="alert('up');mbImageShow = false;"
                                                 style="position: absolute;right: 35px;top: 19px;">
                                                <i class="fas fa-caret-up"></i>
                                            </div>
                                        </div>
                                        <div class="bg-light mb-3 row edit-div-folder pt-2" ng-show="mbImageShow">
                                            <ul class="p-0 list-inline mt-2 mb-0">
                                                <li class="mb-2 ml-2" ng-repeat="field in directory">
                                                    <a class="btn btn-sm btn-outline-primary" href="#" ng-click="onChangeFolderImage(field.dir)">
                                                        @{{field.name}}
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="col-12 row mt-3">
                                                <div ng-repeat="field in filesImages" class="col-4">
                                                    <img ng-src="/@{{field.url_image}}" width="79px" height="auto" ng-click="onImgChange(field)"
                                                         class="cursor-pointer" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleFormControlSelect2"><strong>Carrusel imagenes</strong> (Seleccione directorio)</label>
                                    <div class="col-12 d-flex">
                                        <h6 class="p-2 mt-3 cursor-pointer conex-table card-header w-100" style="border: 1px solid;"
                                            ng-click="mbImageShow2=!mbImageShow2">Directorio: <small>@{{directoryPath2}}</small>
                                        </h6>
                                        <div ng-show="!mbImageShow2" class="cursor-pointer" ng-click="mbImageShow2 = true;"
                                             style="position: absolute;right: 35px;top: 19px;">
                                            <i class="fas fa-caret-down"></i>
                                        </div>
                                        <div ng-hide="!mbImageShow2" class="cursor-pointer" ng-click="alert('up');mbImageShow2 = false;"
                                             style="position: absolute;right: 35px;top: 19px;">
                                            <i class="fas fa-caret-up"></i>
                                        </div>
                                    </div>
                                    <div class="bg-light mb-3 row edit-div-folder pt-2" ng-show="mbImageShow2">
                                        <ul class="p-0 list-inline mt-2 mb-0">
                                            <li class="mb-2 ml-2" ng-repeat="field2 in directory2">
                                                <a class="btn btn-sm btn-outline-primary" href="#" ng-click="onChangeFolderImage2(field2.dir)">
                                                    @{{field2.name}}
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="col-12 row mt-3">
                                            <div ng-repeat="field in filesImages2" class="col-4">
                                                <img ng-src="/@{{field.url_image}}" width="79px" height="auto"
                                                     class="cursor-pointer" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="line-separator mt-0"></div>
                            <div class="mt-3" style="border: 1px solid;">
								<div class="col-12 d-flex pt-2 " ng-click="showSequencesDetailExp=!showSequencesDetailExp">
									<h6 class="cursor-pointer w-100">
										Configuración por secuencias y momentos
									</h6>
									<div ng-show="!showSequencesDetailExp" class="cursor-pointer"
										 style="position: absolute;right: 35px;top: 3px;">
										<i class="fas fa-caret-down"></i>
									</div>
									<div ng-hide="!showSequencesDetailExp" class="cursor-pointer" 
										 style="position: absolute;right: 35px;top: 3px;">
										<i class="fas fa-caret-up"></i>
									</div>
								</div>
								<div ng-show="showSequencesDetailExp" class="col-12 border">
									<div class="row">
										<div class="form-group col-md-6">
											<label for="exampleFormControlSelect1">Secuencia</label>
											<select id="selectCity"
													ng-class="{'select2_group':true, 'form-control':true}"
													class="select2_group form-control selectpicker" ng-model="sequenceSelected" ng-change="sequenceChange(sequenceSelected)">
												<option></option>
												<option ng-repeat="x in sequences" value="@{{x.id}}|@{{x.name}}">@{{x.name}}</option>
											</select>
										</div>
										<div class="form-group col-md-6">
											<label for="exampleFormControlSelect1">Momento</label>
											<select class="form-control selectpicker" id="exampleFormControlSelect1"
													ng-model="momentSelected" multiple>
												<option></option>
												<option ng-repeat="x in moments" value="@{{x.id}}|@{{x.name}}">@{{x.name}}</option>
											</select>
										</div>
										<div class="col-md-12">
											<button class="btn btn-success btn-sm btn-block" ng-click="addSequenceMoment()">Agregar</button>
										</div>
									</div>
									<br>
									<br>
									<div class="row" ng-repeat="x in arraySequenceMomentEdit">
										<div class="col-md-5" >
											<label for="name">Secuencia</label>
											<input class="form-control" type="text" placeholder="nombre" ng-model="x.sequence_name">
										</div>
										<div class="col-md-5">
											<label for="exampleFormControlTextarea1">Momento</label>
											<input class="form-control" type="text" placeholder="nombre" ng-model="x.moment_name">
										</div>
										<div class="form-group col-md-2">
											<button class="btn btn-sm btn-warning mt-4" ng-click="deleteSequenceMomentEdit(x.id,$index,x.id_moment_kit)"> - </button>
										</div>
									</div>
									<div class="row" ng-repeat="x in arraySequenceMoment">
										<div class="col-md-5" >
											<label for="name">Secuencia</label>
											<input class="form-control" type="text" placeholder="Descripción" ng-model="x.name">
										</div>
										<div class="col-md-5">
											<label for="exampleFormControlTextarea1">Momentos</label>
											<textarea class="form-control" id="description" rows="1" ng-model="x.moments_name"></textarea>
										</div>
										<div class="form-group col-md-2">
											<button class="btn btn-sm btn-warning mt-4" ng-click="deleteSequenceMoment($index)"> - </button>
										</div>
									</div>
								</div>
							</div>
							<div class="line-separator"></div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="exampleFormControlSelect1"><strong>Vincular elementos</strong></label>
                                    <select class="form-control selectpicker" id="exampleFormControlSelect1prue"
                                            ng-model="elementsSelected" multiple>
                                        <option></option>
                                        <option ng-repeat="x in list_elements" value="@{{x.id}}">@{{x.name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="exampleFormControlTextarea1"><strong>Descripción</strong></label>
                                    <textarea class="form-control" ng-model="kit.description" id="description" rows="3"></textarea>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
                        <button class="btn btn-sm" ng-class="(actionKit == 'Crear')?'btn-primary':'btn-warning'" type="button" id="onEdit" ng-click="createOrUpdateKit(actionKit)"><i id="move" class=""></i>@{{actionKit}}</button></div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="row">
                @include('roles.admin.sidebar')
                <div class="col-md-8" >
                    <div class="mb-3 card">
                        <div class="card-header">
                            <h5 class="">Gestión de kits y elementos</h5>
                        </div>
                        <div class="bg-light card-body">
                            <button id="formPlan" class="btn btn-outline-primary mr-2 mb-3" type="button" ng-click="actionModalKit('Crear')">
                                <span class="fas fa-plus mr-1" data-fa-transform="shrink-3"></span>Nuevo Kit
                            </button>
                            <button id="formPlan" class="btn btn-outline-primary mr-2 mb-3" type="button" ng-click="actionModalElement('Crear')">
                                <span class="fas fa-plus mr-1" data-fa-transform="shrink-3"></span>Nuevo Elemento
                            </button>
                            <table id="myTable" class="display-1 table table-sm table-dashboard data-table no-wrap mb-0 fs--1 w-100" style="width: 100%">
                                <thead class="bg-200">
                                <tr>
                                    <th class="sort">Nombre</th>
                                    <th class="sort">Descripción</th>
                                    <th class="sort">Tipo</th>
                                    <th class="sort">Precio</th>
                                    <th class="sort">Cantidad</th>
                                    <th class="sort">Editar</th>
                                </tr>
                                </thead>
                                <tfoot class="bg-200">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Tipo</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Editar</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <script src="{{asset('/../angular/controller/managmentKitElementCtrl.js')}}"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js" type="text/javascript"></script>
    <script src="{{asset("js/select2.full.min.js")}}"></script>
    <script>
        $(document).ready( function () {
            $(".selectpicker").select2({
                placeholder: "Seleccione...",
            });

        } );

    </script>
@endsection
