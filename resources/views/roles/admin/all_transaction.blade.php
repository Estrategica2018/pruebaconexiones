@extends('layouts.app_side')

@section('content')
<div class="container">
   <div class="content">
      <div class="row">
         @include('roles.admin.sidebar')
         <div class="col-md-8" ng-controller="TransactionCtrl">
            <div class="mb-3 card">
               <div class="card-header">
                  <h5 class="mb-0">Transacciones</h5>
               </div>
               <div class=" card-body">
                      <div class="table-responsive">
                       <div class="react-bootstrap-table">
                          <table class="table table-dashboard table-sm fs--1 border-bottom border-200 mb-0 table-dashboard-th-nowrap">
                             <thead>
                                <tr class="bg-200 text-900 border-y border-200">
                                   <th tabindex="0" class="border-0" style="min-width: 138px;">Fecha</th>
                                   <th tabindex="0" class="border-0" style="min-width: 171px;">Afiliado</th>
                                   <th tabindex="0" class="border-0">Email</th>
                                   <th tabindex="0" class="border-0" style="min-width: 17    0px;">Producto</th>
                                   <th tabindex="0" class="border-0" style="">Estado</th>
                                   <th tabindex="0" class="border-0" style="text-align: right; min-width:80px;">Precio</th>
                                   <th tabindex="0" class="border-0"></th>
                                </tr>
                             </thead>
                             <tbody>
                             
                             @foreach($shoppingCarts as $shoppingCart)
                                @if($shoppingCart['affiliate'])
                                <tr class="btn-reveal-trigger border-top border-200">
                                   <td class="selection-cell" style="border: 0px; vertical-align: middle;">
                                   {{ $shoppingCart['updated_at'] }}
                                   </td>
                                   <td class="border-0 align-middle">
                                     <a class="font-weight-semi-bold" href="#" ng-click="showDetail('{{$shoppingCart['payment_transaction_id']}}')">
                                        {{$shoppingCart['affiliate']->name}} {{$shoppingCart['affiliate']->last_name}}
                                     </a>
                                   </td>
                                   <td class="border-0 align-middle">{{$shoppingCart['affiliate']->email}}</td>
                                   <td class="border-0 align-middle">{{$shoppingCart['description']}}</td>
                                   <td class="border-0 align-middle fs-0">
                                     @if($shoppingCart['payment_status']->id == 2)
                                      <span class="rounded-capsule badge badge-soft-warning">
                                        {{$shoppingCart['payment_status']->name}}
                                      </span>
                                      @endif
                                      @if($shoppingCart['payment_status']->id == 3)
                                      <span class="rounded-capsule badge badge-soft-success">
                                        {{$shoppingCart['payment_status']->name}}
                                         <i class="fas fa-check"></i>
                                      </span>
                                      @endif
                                      @if($shoppingCart['payment_status']->id == 4 || $shoppingCart['payment_status']->id == 5)
                                      <span class="rounded-capsule badge badge-soft-danger">
                                        {{$shoppingCart['payment_status']->name}}
                                         <i class="fas fa-exclamation-triangle"></i>
                                      </span>
                                      @endif
                                   </td>
                                   <td class="border-0 align-middle" style="text-align: right;">
                                   $  {{$shoppingCart['total_price']}} USD 
                                   </td>
                                   <td class="border-0 align-middle">
                                      <div class="dropdown">
                                         <button type="button" aria-haspopup="true" aria-expanded="false" class="text-600 btn-reveal btn btn-link btn-sm" ng-click="showDetail('{{$shoppingCart['payment_transaction_id']}}')">
                                            <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="ellipsis-h" class="svg-inline--fa fa-ellipsis-h fa-w-16 fs--1" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                               <path fill="currentColor" d="M328 256c0 39.8-32.2 72-72 72s-72-32.2-72-72 32.2-72 72-72 72 32.2 72 72zm104-72c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72zm-352 0c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72z"></path>
                                            </svg>
                                         </button>
                                         
                                      </div>
                                   </td>
                                </tr>
                                @endif
                             @endforeach
                             <conx-shoppingcart-detail id="idShoppingCart"> </conx-shoppingcart-detail>
                             </tbody>
                          </table>
                       </div>
                    </div>
                    <div class="px-1 py-3 no-gutters row">
                       <div class="pl-3 fs--1 col">
                          <span>{{$totalShoppingCarts}} Transacciones</span>
                       </div>
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
<script src="{{asset('/../angular/controller/TransactionCtrl.js')}}"></script>
@endsection
