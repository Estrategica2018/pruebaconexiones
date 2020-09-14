<style>
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
}
</style>

<div class="swal2-container swal2-fade swal2-shown" ng-show="id" >
   <div role="dialog" class="swal2-modal swal2-show" style="width: 500px; padding: 20px; background: rgb(255, 255, 255); display: block; min-height: 229px;">
      <div ng-click="id=null" class="position_absolute fs-2 cursor-pointer" style="top: 0px;right: 16px;left: 35px;text-align: right;position: absolute;"> 
        <i class="far fa-times-circle"></i>
        <!--img ng-click="id=null" class="cursor-pointer" src="/images/icons/close.png"/-->
      </div>
      <div id="swal2-content" class="swal2-content  mt-2" style="display: block;">
         <ul class="nav nav-tabs">
            <li class="nav-item cursor-pointer"  onclick="$('#tabContact').addClass('show active');">
               <span class="nav-link" ng-class="{'active': tabSelected === 'contact'}" ng-click="tabSelected = 'contact'"> Contacto</span>
            </li>
            <li class="nav-item cursor-pointer"  onclick="$('#subscription').addClass('show active');">
               <span class="nav-link" ng-class="{'active': tabSelected === 'subscription'}" ng-click="tabSelected = 'subscription'">Suscripciones</span>
            </li>
            <li class="nav-item cursor-pointer" ng-click="tabSelected = 'payment')">
               <span class="nav-link" ng-class="{'active': tabSelected === 'payment'}" ng-click="tabSelected = 'payment'">Pagos</span>
            </li>
         </ul>
         <div class="tab-content border-x border-bottom p-3" id="myTabContent">
            <div id="tabContact" class="tab-pane fade" ng-class="{'show active': tabSelected === 'contact'}">
                <div>
                    <div>Nombre</div>
                    <div class="ml-2">@{{response.affiliate.name}} @{{response.affiliate.last_name}}</div>
                </div>
                <div>
                    <div>Email</div>
                    <div class="ml-2">@{{response.affiliate.email}}</div>
                </div>
                <div>
                    <div>Ciudad</div>
                    <div class="ml-2">@{{response.affiliate.country && response.affiliate.country.name ?  response.affiliate.country.name : '' }} @{{ response.affiliate.country ? '-': '' }} @{{response.affiliate.city}}</div>
                </div>
                <div>
                    <div>Teléfono</div>
                    <div class="ml-2">@{{response.affiliate.phone}}</div>
                </div>
                <div>
                    <div>Fecha Creación</div>
                    <div class="ml-2">@{{response.affiliate.created_at}}</div>
                </div>
            </div>
            <div class="tab-pane fade" ng-class="{'show active': tabSelected === 'subscription'}">
               Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic. 
            </div>
            <div class="tab-pane fade" ng-class="{'show active': tabSelected === 'payment'}">
               Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork.
            </div>
         </div>
      </div>
   </div>
</div>