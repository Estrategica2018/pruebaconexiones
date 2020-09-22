@extends('layouts.app_side')
@section('plugins')
<link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css" rel="stylesheet">
@endsection
@section('content')
<div class="container">
   <div class="content">
      <div class="row">
         @include('roles.admin.sidebar')
         <div class="col-md-8" ng-controller="listAllUsersCtrl" ng-init="init()">
            <div ng-show="errorMessage" class="fade-message d-none-result d-none alert alert-danger p-1 pl-2 row">
               <span class="col">@{{ errorMessage }}</span>
               <span class="col-auto">
               <a ng-click="errorMessage = null"><i class="far fa-times-circle"></a></i></span>
            </div>
            <div class="mb-3 card" style="min-width:700px;">
               <div class="card-header">
                  <div class="align-items-center row">
                     <div class="col">
                        <h6 class="mb-0">
                           Lista de usuarios en el sistema
                        </h6>
                     </div>
                     <div class="text-right col-auto">
                        <button class="mx-2 btn btn-falcon-default btn-sm">
                            <input ng-model="search" placeholder="Buscar..." aria-label="Search" type="search" class="rounded-pill search-input" style="border:0px">
                            <i class="fas fa-filter"></i>
                            Filtro
                        </button>
                        <button class="btn btn-falcon-default btn-sm">
                           <i class="fas fa-file-export"></i>
                           Exportar
                        </button>
                     </div>
                  </div>
               </div>
               <div class="p-0 card-body">
                  <div class="table-responsive">
                     <div class="react-bootstrap-table">
                        <table class="table table-dashboard table-sm fs--1 border-bottom border-200 mb-0 table-dashboard-th-nowrap" ng-init="orderByItem='status'">
                           <thead>
                              <tr class="bg-200 text-900 border-y border-200">
                                 <th tabindex="0">Afiliado<span class="sortable order-4" ng-click="orderByItem != 'name' ? orderByItem = 'name' : orderByItem ='-name'"></span></th>
                                 <th tabindex="0">Email<span class=" sortable order-4" ng-click="orderByItem !='email' ? orderByItem = 'email' : orderByItem ='-email'"></span></th>
                                 <th tabindex="0">Localidad<span class="sortable order-4" ng-click="orderByItem !='location' ? orderByItem = 'location' : orderByItem ='-location'"></span></th>
                                 <th tabindex="0"># Contacto</th>
                                 <th tabindex="0">Estado<span class="sortable order-4" ng-click="orderByItem !='status' ? orderByItem = 'status' : orderByItem ='-status'"></span></th>
                                 <th tabindex="0" class="border-0"></th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr class="btn-reveal-trigger border-top border-200" ng-repeat="user in users | filter:search | orderBy: orderByItem" >
                                 <td class="align-middle"><a class="font-weight-semi-bold" href="/pages/customer-details">@{{user.name}} @{{user.last_name}}</a></td>
                                 <td class="align-middle">@{{user.email}}</td>
                                 <td class="align-middle">@{{ user.location }}</td>
                                 <td class="align-middle">@{{ user.phone }}</td>
                                 <td class="align-middle fs-0">
                                    <span class="rounded-capsule badge badge-soft-success" ng-show="user.status=='Activo'">
                                       Activo
                                       <i class="fas fa-check"></i>
                                    </span>
                                    <span class="rounded-capsule badge badge-soft-warning" ng-show="user.status=='Inactivo'">
                                       Inactivo
                                    </span>
                                 </td>
                                 <td class="border-0 align-middle">
                                    <div class="dropdown">
                                       <button type="button" aria-haspopup="true" aria-expanded="false" class="text-600 btn-reveal btn btn-link btn-sm" ng-click="showUser(user.id)">
                                          <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="ellipsis-h" class="svg-inline--fa fa-ellipsis-h fa-w-16 fs--1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                             <path fill="currentColor" d="M328 256c0 39.8-32.2 72-72 72s-72-32.2-72-72 32.2-72 72-72 72 32.2 72 72zm104-72c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72zm-352 0c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72z"></path>
                                          </svg>
                                       </button>
                                    </div>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
                  <div class="px-1 py-3 no-gutters row">
                     <div class="pl-3 fs--1 col">
                        <span>@{{users.length}} usuarios</span>
                     </div>
                  </div>
                  <conx-shoppingcart-detail userid="userid"> </conx-shoppingcart-detail>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('js')
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="{{asset('/../angular/controller/listAllUsersCtrl.js')}}"></script>
@endsection