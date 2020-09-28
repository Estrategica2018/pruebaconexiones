<nav ng-controller="navbarController" id="navbar" class="pb-0 navbar-glass sticky-top-ie row navbar-top sticky-kit navbar navbar-expand-lg navbar-light">
  <button aria-label="Toggle navigation" id="toggleMenu" type="button" class="navbar-toggler">
  <span class="navbar-toggler-icon"></span>
  </button>  
     <div class="d-flex align-items-center">
        <a href="{{route('/')}}"><img href="" class="mr-2 avatar-logo" src="{{ asset('images/icons/iconosoloConexiones-01.png') }}" alt="Logo" width="40"></a>
        <div class="text-sans-serif text-center fs-sm--3 fs-md--2 fs-lg-0 font-weight-semi-bold" style="min-width: 154px;">
           <span id="slogan" >Experiencias científicas <br/> para conocer el mundo <br/> natural</span>
        </div>
     </div>

     <div class="ml-1 nav collapse navbar-collapse row text-align fs-14px font-weight-semi-bold">
        <div class="nav-item ml-auto mr-auto p-0 nav-small-fs--1"><a href="{{ route('home') }}" class="nav-link p-0 pb-1 @if(\Route::current()->getName() == 'home' || Route::current()->getName() == '/') selected @endif">Inicio</a></div>
        <div class="nav-item ml-auto mr-auto p-0 max-with-105px "><a href="{{ route('aboutus') }}" class="nav-link p-0 pb-1 @if(\Route::current()->getName() == 'aboutus') selected @endif">Acerca de conexiones</a></div>
        <div class="nav-item ml-auto mr-auto p-0 max-with-105px " ><a href="{{ route('sequences.search') }}" class="nav-link p-0 pb-1 
        @if(\Route::current()->getName() == 'sequences.search') selected @endif
        @if(\Route::current()->getName() == 'sequences.get') selected @endif
        ">Guías de aprendizaje</a></div>
        <div class="nav-item ml-auto mr-auto p-0 max-with-105px "><a href="{{ route('elementsKits.search') }}" class="nav-link p-0 pb-1 @if(\Route::current()->getName() == 'elementsKits.search') selected @endif">Implementos de laboratorio</a></div>
        <div class="nav-item ml-auto mr-auto p-0"><a href="{{ route('contactus') }}" class="nav-link p-0 pb-1 @if(\Route::current()->getName() == 'contactus') selected @endif">Contáctenos</a></div>
        @guest('afiliadoempresa')
        <div class="nav-item ml-auto mr-auto p-0 text-align-right">
           <a class="btn btn-primary btn-sm badge-pill fs-lg--1 font-weight-bold" href="{{ route('user.showLogin') }}">Inicio de Sesión</a>
        </div>
        <div class="nav-item p-0 ml-auto mr-auto">
           <a class="btn btn-warning btn-sm badge-pill fs-lg--1 font-weight-bold" href="{{ route('registerForm') }}">Registro</a>
        </div>
        @endguest
        
        @auth('afiliadoempresa')
        @if(auth('afiliadoempresa')->user()->hasAnyRole('student'))
        <div class="ml-1 ml-auto mr-auto d-flex">
            <a href="{{ route('student', auth('afiliadoempresa')->user()->company_name()) }}" class="ml-2 pl-2 pr-2 pb-1 btn btn-outline-primary btn-sm"> 
            <i class="fas fa-user fs-1 mt-1"></i> Mi perfíl </a>
        </div>
        @elseif(auth('afiliadoempresa')->user()->hasAnyRole('tutor'))
        <div class="ml-1 ml-auto mr-auto d-flex">
            <a href="{{ route('tutor', auth('afiliadoempresa')->user()->company_name()) }}" class="ml-2 pl-2 pr-2 pb-1 btn btn-outline-primary btn-sm"> 
            <i class="fas fa-user fs-1 mt-1"></i> Mi perfíl </a>
        </div>
        @else
        <div class="ml-1 ml-auto mr-auto d-flex">
            <button class="my-3 btn btn-primary btn-sm btn-block" href="{{ route('user.logout') }}"
               ng-click="closeSession('{{ route('user.logout') }}')">Cerrar Sesión
            </button>
         
        </div>
        @endif


        @endauth
        
         <div class="nav-item">
           <a class="px-0 notification-indicator notification-indicator-warning notification-indicator-fill nav-link" href="{{route('shoppingCart')}}">
              <span class="notification-indicator-number" ng-init="initNumberShoppingCart()"></span>
              <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="shopping-cart" class="svg-inline--fa fa-shopping-cart fa-w-18 fs-4" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" style="transform-origin: 0.5625em 0.5em;">
                 <g transform="translate(288 256)">
                    <g transform="translate(0, 0)  scale(0.5625, 0.5625)  rotate(0 0 0)">
                       <path fill="currentColor" d="M528.12 301.319l47.273-208C578.806 78.301 567.391 64 551.99 64H159.208l-9.166-44.81C147.758 8.021 137.93 0 126.529 0H24C10.745 0 0 10.745 0 24v16c0 13.255 10.745 24 24 24h69.883l70.248 343.435C147.325 417.1 136 435.222 136 456c0 30.928 25.072 56 56 56s56-25.072 56-56c0-15.674-6.447-29.835-16.824-40h209.647C430.447 426.165 424 440.326 424 456c0 30.928 25.072 56 56 56s56-25.072 56-56c0-22.172-12.888-41.332-31.579-50.405l5.517-24.276c3.413-15.018-8.002-29.319-23.403-29.319H218.117l-6.545-32h293.145c11.206 0 20.92-7.754 23.403-18.681z" transform="translate(-288 -256)"></path>
                    </g>
                 </g>
              </svg>
           </a>
        </div> 

        <div class="nav-item ml-auto mr-auto">
           <form class="search-box form-inline ng-pristine ng-valid" ng-init="initSearch()">
              <input placeholder="Buscar..." ng-model="searchText" aria-label="Search" type="search" class="rounded-pill search-input form-control">
              <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="search" class="svg-inline--fa fa-search fa-w-16 position-absolute text-400 search-box-icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                 <path fill="currentColor" d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path>
              </svg>
           </form>
           <div class="d-none-result d-none card card-body p-0 position-absolute" ng-show="searchText.length > 2" style="max-height: 549px;overflow-y: scroll;">
            <ul class="list-group">  
               <li class="text-align-left list-group-item btn btn-light" ng-repeat="item in searchList | filter:searchText">
                  <div ng-show="item.type === 'Guía'">
                  <a href="/guia_de_aprendizaje/@{{item.obj.id}}/@{{item.obj.name}}">
                   <span class="font-weight-bold">@{{item.type}}</span>  @{{item.obj.name}}
                   </a>
                  </div>
                  <div ng-show="item.type === 'Kit'">
                    <a href="/kit_de_laboratorio/@{{item.obj.id}}/@{{item.obj.name}}">
                       <span class="font-weight-bold">@{{item.type}}</span>  @{{item.obj.name}} 
                     </a>
                  </div>
                  <div ng-show="item.type === 'Elemento'">
                    <a href="/elemento_de_laboratorio/@{{item.obj.id}}/@{{item.obj.name}}">
                       <span class="font-weight-bold">@{{item.type}}</span>  @{{item.obj.name}} 
                     </a>
                  </div>
               </li>
            </ul>
           </div>
        </div>
        

</div>
</nav>
