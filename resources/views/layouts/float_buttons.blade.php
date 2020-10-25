<div class="float result-finish-done d-none col-10 col-md-6 col-xl-4 text-align-right w-auto"  ng-controller="frequentQuestionCtrl" ng-init="init()">
    <img ng-hide="toogleChatPanel" class="cursor-pointer" src="{{asset('images/icons/chat.png')}}" width="80px" height="auto" ng-click="toogleChatPanel=true">
    <a href="{{route('help_platform')}}"><img ng-hide="toogleChatPanel" class="cursor-pointer" src="{{asset('images/icons/help_icon.png')}}" width="80px" height="auto"></a>
    <div class="card text-align-justify" ng-show="toogleChatPanel">
        <div class="card-header fs--1 pr-5">
            <div ng-click="toogleChatPanel= false" class="position-absolute fs-2 cursor-pointer" style="top: 3px;right: 16px;text-align: right;"> 
            <i class="far fa-times-circle"></i> 
            </div>
        ¡Hola! <br>
        Aquí encontrarás respuesta a  algunas preguntas frecuentes. Si tienes otros temas sobre los que requieras mas claridad, por favor escríbenos
        </div>
        <div class="line-separator"></div>
        <div class="card-body mb-7" style="height: 55vw; max-height: 400px;overflow-y: auto;">
            <div ng-repeat="items in frequentQuestions"> 
                <div ng-click="items.isShow=!items.isShow" class="cursor-pointer d-flex bg-secondary mt-1 mb-1 rounded bg-soft-dark-light p-3 ">
                    <label class="pr-2">@{{items.question}}</label> 
                    <div ng-show="items.isShow" class="ml-auto">  <i class="fas fa-arrow-up"></i> </div>
                    <div ng-hide="items.isShow" class="ml-auto">  <i ng-show="!items.isShow"  class="fas fa-arrow-right ml-auto"></i> </div>
                </div>
                <div ng-show="items.isShow" class="d-flex bg-secondary mt-1 mb-1 rounded bg-soft-light p-3">
                    <label ng-bind-html="trustAsHtml(items.answer)"></label> 
                </div>
            </div>    
            <div class="position-absolute" style="bottom: 15px;  width: 92%;">
                <div ng-show="showDetailEmail" class="p-3 rounded-top" style="background-color: #edf2f9;">
                    <div ng-click="showDetailEmail = false" class="cursor-pointer position-absolute" style="top: 5px; right: 10px;"> <i class="fa fa-times-circle"></i> </div>
                    <label for="name" class="font-weight-bold mb-0"> Nombre</label>
                    <input ng-model="name"  type="text" ng-init="name='@auth('afiliadoempresa'){{auth('afiliadoempresa')->user()->name}}@endauth'" class="w-100" style="height:35px"/>    
                    <label for="email" class="font-weight-bold mb-0"> Correo</label>
                    <input ng-model="email" type="email" ng-init="email='@auth('afiliadoempresa'){{auth('afiliadoempresa')->user()->emailForContact()}}@endauth'" class="w-100" style="height:35px"/>
                    <label for="affair" class="font-weight-bold mb-0"> Asunto</label>
                    <input ng-model="affair"  type="text" ng-init="affair='Consulta sobre plataforma educonexiones'" class="w-100" style="height:35px"/>    
                </div>
                <input ng-model="message" placeholder="Comentario" type="text" ng-click="showDetailEmail = true" class="w-75 mt-1" style="height:35px"/>
                <button ng-click="onSendEmail()" ng-disabled="!name || !email ||  !affair ||  !message" class="btn btn-sm btn-primary" style=" height: 35px; padding-top: 0;">
                <i id="sendButton" class="fas fa-paper-plane"></i> Enviar</button>
            </div>
        </div>
    </div>
</div>
 