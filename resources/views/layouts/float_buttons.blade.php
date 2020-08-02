<a class="float result-finish-done d-none"  ng-controller="frequentQuestionCtrl" ng-init="init()">
    <img ng-hide="toogleChatPanel" class="cursor-pointer" src="{{asset('images/icons/chat.png')}}" width="60px" height="auto" ng-click="toogleChatPanel=true">
    <div class="card" ng-show="toogleChatPanel" style="width: 435px;">
		<div class="card-header fs--1 pr-5">
			<div ng-click="toogleChatPanel= false" class="position-absolute fs-2 cursor-pointer" style="top: 3px;right: 16px;text-align: right;"> 
			<i class="far fa-times-circle"></i> 
			</div>
		¡Hola! 
		Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat ut wisi enim ad 
		</div>
		<div class="line-separator"></div>
		<div class="card-body" style="height: 400px;overflow-y: auto;">
			<div ng-repeat="items in frequentQuestions"> 
				<div ng-click="items.isShow=!items.isShow" class="d-flex bg-secondary mt-1 mb-1 rounded bg-soft-dark-light pr-1 pl-1">
					<label>@{{items.question}}</label>
					<span class="ml-auto">></span>
				</div>
				<div ng-show="items.isShow" class="d-flex bg-secondary mt-1 mb-1 rounded bg-soft-light pr-1 pl-1">
					<label>@{{items.answer}}</label> 
				</div>
			</div>	
			<div class="">
				<input ng-model="email" placeholder="Correo" type="text" class="w-100"/>
				<input ng-model="comment" placeholder="Comentario" type="text" class="w-80"/>
				<button ng-click="onSendEmail()" class="btn btn-sm btn-primary" style=" height: 22px; padding-top: 0;"> &gt;</button>
			</div>
		</div>
	</div>
</a>
 