<div ng-show="elementsKits.length > 0" class="d-none-result d-none  dropdown-menu-card" id="elementkitsModal">
    <div class="modal-backdrop fade show"></div>
    <div class="position-absolute modal-menu card-notification shadow-none card" style="top: 0px;width: 100%;margin-left: -15px;">
        <div class="card col-12">
          <div ng-click="elementsKits.length=0" class="position-absolute fs-2 cursor-pointer" style="top: 3px;right: 16px;left: 35px;text-align: right;"> <i class="far fa-times-circle"></i> </div>
          <div class="card-body w-100 d-none-result d-none mb-3">
            <h4 class=" boder-header p-1 pl-3">
               Implementos de Laboratorio
            </h4>
            <p>La guía de aprendizaje seleccionada contiene implementos de laboratorio que también podrá incluir, si no desea adquirirlos omita este paso y continúe con la compra.</p>
            
            <div class="row">
                <div ng-repeat="elementsKit in elementsKits" class="text-center col-xl-4 col-md-6 col-12 border-white-extent">
                    <div class="card card-body bg-dark elementKit_div_responsive row pt-5">
                         <div class="position-absolute" style="top:10px; transform : scale(2);">
                             <input ng-disabled="elementsKit.quantity === 0" type="checkbox" ng-model="elementsKit.isSelected" name="check_elementsKit_@{{elementsKit.id}}_@{{elementsKit.type}}"/> 
                         </div>
                         <div class="col-5">
                            <img ng-src="/{{elementsKit.url_image}}" width="auto" height="auto" class="col-12 p-0"/> 
                         </div>
                         <div class="col-7 pl-0 ml-2 text-justify fs--1 flex-100" style="margin-top: -27px;">
                            <h5 class="pl-3 boder-header"> <span class="ml-2">{{elementsKit.name}} </span></h5>  
                            <p class="mt-4 ml-2"> {{elementsKit.description}}</p>
                         </div>
                    </div>
                </div>
            </div>
            <div class="col-12 text-right">
               <button ng-click="onContinuePayment()" class="ml-3 mt-3 btn btn-primary fs-0" href="#" class="col-6">
               <i id="move" class=""></i>
               <i class="fas fa-arrow-right"></i> Continuar compra</button>
            </div>
          </div>
        </div>
    </div>
</div>
