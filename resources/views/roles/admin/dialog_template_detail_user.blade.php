<style>

#tabTrax div {
    display: flex;
    height: 34px;
    align-items: center;
    width: 374px;
}

#tabTrax div {
   border-bottom: 1px solid #e6edf5;
}

#tabTrax div>div:first-child {
    background-color: #e6edf5;
    min-width: 139px;
    padding-left: 13px;
    font-weight: bold;
    font-size: 12px;
}

#tabContact div {
    display: flex;
    height: 34px;
    align-items: center;
}

#tabContact div {
   border-bottom: 1px solid #e6edf5;
}

#tabContact div>div:first-child {
    background-color: #e6edf5;
    min-width: 139px;
    padding-left: 13px;
    font-weight: bold;
    font-size: 12px;
}


#payment div {
    display: flex;
    height: 34px;
    align-items: center;
}

#payment div {
   border-bottom: 1px solid #e6edf5;
}

#payment div>div:first-child {
    background-color: #e6edf5;
    min-width: 139px;
    padding-left: 13px;
}



</style>

<div class="swal2-container swal2-fade swal2-shown" ng-show="id || userid" >
   <div role="dialog" class="swal2-modal swal2-show" style="width: 652px; padding: 20px; background: rgb(255, 255, 255); display: block; min-height: 229px;">
      <div class="position_absolute fs-2" style="top: 0px;right: 16px;left: 35px;text-align: right;position: absolute;"> 
        <div ng-click="id=null; userid = null" style="width: 20px;margin-left: auto;" class="cursor-pointer">
           <i class="far fa-times-circle" ></i>
        </div>
      </div>
      <div id="swal2-content" class="swal2-content  mt-2" style="display: block;" ng-hide="loading">
         <ul class="nav nav-tabs  font-weight-bold fs--1">
            <li class="nav-item cursor-pointer" ng-show="response.transaction">
               <span class="nav-link" ng-class="{'active': tabSelected === 'tranaction'}" ng-click="tabSelected = 'tranaction'"> Transacción</span>
            </li>
            <li class="nav-item cursor-pointer">
               <span class="nav-link" ng-class="{'active': tabSelected === 'contact'}" ng-click="tabSelected = 'contact'"> Contacto</span>
            </li>
            <li class="nav-item cursor-pointer">
               <span class="nav-link" ng-class="{'active': tabSelected === 'subscription'}" ng-click="tabSelected = 'subscription'">Suscripciones</span>
            </li>
            <li class="nav-item cursor-pointer">
               <span class="nav-link" ng-class="{'active': tabSelected === 'payment'}" ng-click="tabSelected = 'payment'">Pagos</span>
            </li>
         </ul>
         <div class="tab-content border-x border-bottom p-2">
            <div ng-show="response.transaction" id="tabTrax" class="tab-pane fade" ng-class="{'show active': tabSelected === 'tranaction'}">
                <div ng-hide="response.transaction.payment_status.id === 4 || response.transaction.payment_status.id === 5">
                    <div>Código de transacción</div>
                    <div class="ml-2"> @{{ (response.transaction.payment_transaction_id.length === 14  ? 'Pago Simulado' : 'MercadoPago - ' + response.transaction.payment_transaction_id )  }}</div>
                </div>
                <div>
                    <div>Estado</div>
                    <div class="ml-2">
                        <span ng-show="response.transaction.payment_status.id === 2" class="rounded-capsule badge badge-soft-warning">@{{response.transaction.payment_status.name}}</span>
                        <span ng-show="response.transaction.payment_status.id === 3" class="rounded-capsule badge badge-soft-success">@{{response.transaction.payment_status.name}} <i class="fas fa-check"></i></span>
                        <span ng-show="response.transaction.payment_status.id === 4 || response.transaction.payment_status.id === 5" class="rounded-capsule badge badge-soft-danger">  <i class="fas fa-exclamation-triangle"></i> @{{response.transaction.payment_status.name}} </span>
                    </div>
                </div>
                <div>
                    <div>Fecha de Pago</div>
                    <div class="ml-2">@{{ response.transaction.payment_process_date || response.transaction.updated_at}}</div>
                </div>
                <div ng-show="response.transaction.approval_code">
                    <div>Cod. Aprobación</div>
                    <div class="ml-2">@{{response.transaction.approval_code}}</div>
                </div>
                <div>
                    <div>Medio de pago</div>
                    <div class="ml-2">@{{response.transaction.payment_method}}</div>
                </div>
                <div>
                    <div>Descripción</div>
                    <div class="ml-2 text-align-left">@{{response.transaction.description}}</div>
                </div>
                <div>
                    <div>Precio</div>
                    <div class="ml-2">  
                        <span ng-show="response.transaction.total_price>0"> $ @{{response.transaction.total_price}} USD <span>
                    </div>
                </div>
            </div>
            <div id="tabContact" class="tab-pane fade" ng-class="{'show active': tabSelected === 'contact'}">
                <div>
                    <div>Nombre</div>
                    <div class="ml-2 fs--1">@{{response.affiliate.name}} @{{response.affiliate.last_name}}</div>
                </div>
                <div>
                    <div>Email</div>
                    <div class="ml-2 fs--1">@{{response.affiliate.email}}</div>
                </div>
                <div>
                    <div>Localidad</div>
                    <div class="ml-2 fs--1">
                        @{{response.affiliate.country && response.affiliate.country.name ?  response.affiliate.country.name : '' }}
                        - @{{ (response.affiliate.city ? response.affiliate.city.name : 
                            response.affiliate.city_name ) }}</div>
                </div>
                <div>
                    <div>Teléfono</div>
                    <div class="ml-2 fs--1">@{{response.affiliate.phone}}</div>
                </div>
                <div>
                    <div>Fecha Creación</div>
                    <div class="ml-2 fs--1">@{{response.affiliate.created_at}}</div>
                </div>
            </div>
            <div class="tab-pane fade" ng-class="{'show active': tabSelected === 'subscription'}">
               <table class="table table-dashboard table-sm fs--1 border-bottom border-200 mb-0 table-dashboard-th-nowrap text-align-left">
                 <thead>
                    <tr class="bg-200 text-900 border-y border-200">
                       <th tabindex="0" class="border-0" style="min-width: 78px;">Fecha</th>
                       <th tabindex="0" class="border-0">Fecha Fin</th>
                       <th tabindex="0" class="border-0" style="min-width: 121px;">Producto</th>
                    </tr>
                 </thead>
                 <tbody ng-repeat="account_services in response.affiliate.affiliated_account_services">
                    <tr class="btn-reveal-trigger border-top border-200" >
                       <td class="selection-cell" style="border: 0px;">
                       @{{account_services.init_date}}
                       </td>
                       <td class="selection-cell" style="border: 0px;">
                       @{{account_services.end_date}}
                       </td>
                       <td class="selection-cell" style="border: 0px;">
                       @{{ account_services.rating_plan.name }}
                       </td>
                   </tr>
                   <tr class="btn-reveal-trigger border-top border-200" ng-repeat="sequence in account_services.sequences">
                       <td colspan="3" class="selection-cell" style="border: 0px;">
                       @{{sequence.name}}
                       </td>
                   </tr>
                </tbody>
               </table>
            </div>
            <div id="payment" class="tab-pane fade" ng-class="{'show active': tabSelected === 'payment'}">
                <table class="table table-dashboard table-sm fs--1 border-bottom border-200 mb-0 table-dashboard-th-nowrap">
                 <thead>
                    <tr class="bg-200 text-900 border-y border-200">
                       <th tabindex="0" class="border-0" style="min-width: 135px;">Fecha</th>
                       <th tabindex="0" class="border-0">Precio</th>
                       <th tabindex="0" class="border-0">Estado</th>
                       <th tabindex="0" class="border-0" style="min-width: 121px;">Producto</th>
                       <th tabindex="0" class="border-0" style="min-width: 121px;"># Aprobación</th>
                       <th tabindex="0" class="border-0" style="min-width: 121px;">MercadoPago</th>
                    </tr>
                 </thead>
                 <tbody>
                    <tr class="btn-reveal-trigger border-top border-200" ng-repeat="shoppingCart in response.shoppingCarts" >
                       <td class="selection-cell text-align-left" style="border: 0px;">
                       @{{ shoppingCart.updated_at }}
                       </td>
                       <td class="selection-cell" style="border: 0px;">
                       @{{ ( shoppingCart.rating_plan_price  ? '$' + shoppingCart.rating_plan_price + 'USD' : '')  }}
                       </td>
                       <td class="selection-cell" style="border: 0px;">
                       @{{ shoppingCart.payment_status.name }}
                       </td>
                       <td ng-show="shoppingCart.rating_plan" class="text-align-left selection-cell" style="border: 0px;">
                       @{{ shoppingCart.rating_plan.name }}
                       </td>
                       <td ng-show="shoppingCart.shopping_cart_product[0].kiStruct.name" class="text-align-left selection-cell" style="border: 0px;">
                       @{{ shoppingCart.shopping_cart_product[0].kiStruct.name }}
                       </td>
                       <td ng-show="shoppingCart.shopping_cart_product[0].elementStruct[0].name" class="text-align-left selection-cell" style="border: 0px;">
                       @{{ shoppingCart.shopping_cart_product[0].elementStruct[0].name }}
                       </td>
                       <td class="selection-cell" style="border: 0px;">
                       @{{ shoppingCart.approval_code }}
                       </td>
                       <td class="text-align-left selection-cell" style="border: 0px;">
                       @{{ (shoppingCart.payment_transaction_id.length === 14  ? 'Pago Simulado' : 'MercadoPago - ' + shoppingCart.payment_transaction_id )  }}
                       </td>
                   </tr>
                </tbody>
               </table>
            </div>
         </div>
      </div>
      <div class="swal2-content  mt-2" style="display: block;" ng-show="loading">
        Cargando ...
      </div>
    </div>
</div>