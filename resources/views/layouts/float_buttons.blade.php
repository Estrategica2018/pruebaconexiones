<a class="float" ng-init="toogleChatPanel=false">
    <img ng-hide="toogleChatPanel" class="cursor-pointer" src="{{asset('images/icons/chat.png')}}" width="60px" height="auto" ng-click="toogleChatPanel=true">
    <div class="card" ng-show="toogleChatPanel" style="width: 435px;">
		<div class="card-header fs--1 pr-5">
			<div ng-click="toogleChatPanel= false" class="position-absolute fs-2 cursor-pointer" style="top: 3px;right: 16px;text-align: right;"> 
			<i class="far fa-times-circle"></i> 
			</div>
		�Hola!
		Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat ut wisi enim ad 
		</div>
		<div class="line-separator"></div>
		<div class="card-body">
			<div ng-repeat="items in [1,2,3,4,5,6,7]" class="d-flex bg-secondary mt-1 mb-1 rounded bg-soft-dark-light pr-1 pl-1">
				<label>Pregunta frecuente @{{item}}</label> 
				<span class="ml-auto">></span>
			</div>
			<div class="d-flex">
				<input type="text" class="w-100"/>
				<button class="btn btn-sm btn-primary">></button>
			</div>
		</div>
	</div>
</a>