<nav ng-controller="navbarController" id="navbar" class="pb-0 navbar-glass sticky-top-ie row navbar-top sticky-kit navbar navbar-expand-lg navbar-light">
  <button aria-label="Toggle navigation" id="toggleMenu" type="button" class="navbar-toggler">
  <span class="navbar-toggler-icon"></span>
  </button>
  <a class="text-decoration-none navbar-brand text-left ml-3" id="topLogo" href="#">
     <div class="d-flex align-items-center">
        <a href="{{asset('/')}}"><img href="" class="mr-2 avatar-logo" src="{{ asset('images/icons/iconosoloConexiones-01.png') }}" alt="Logo" width="40"></a>
        <div class="text-sans-serif text-center fs-sm--3 fs-md--2 fs-lg-0 font-weight-semi-bold" style="min-width: 154px;">
           <span id="slogan" >Experiencias científicas <br/> para conocer el mundo <br/> natural</span>
        </div>
     </div>
  </a>
  @auth('afiliadoempresa')
  @if(auth('afiliadoempresa')->user())
     @if(auth('afiliadoempresa')->user()->hasAnyRole('tutor'))
     <ul class="nav collapse navbar-collapse row text-align-rigth row">
		
		
		
     </ul>
     @endif
  @endif
  @endauth
  @guest('afiliadoempresa')
     <ul class="ml-1 nav collapse navbar-collapse row text-align fs--1 font-weight-semi-bold">
        <li class="nav-item col-1 p-0 nav-small-fs--1"><a href="{{ route('home') }}" class="nav-link p-0 pb-1 @if(\Route::current()->getName() == 'home' || Route::current()->getName() == '/') selected @endif">Inicio</a></li>
        <li class="nav-item col-1-5 p-0 "><a href="{{ route('aboutus') }}" class="nav-link p-0 pb-1 @if(\Route::current()->getName() == 'aboutus') selected @endif">Acerca de conexiones</a></li>
        <li class="nav-item col-1-5 p-0"><a href="{{ route('sequences.search') }}" class="nav-link p-0 pb-1 
        @if(\Route::current()->getName() == 'sequences.search') selected @endif
        @if(\Route::current()->getName() == 'sequences.get') selected @endif
        ">Guías de aprendizaje</a></li>
        <li class="nav-item col-2 p-0"><a href="{{ route('elementsKits.search') }}" class="nav-link p-0 pb-1 @if(\Route::current()->getName() == 'elementsKits.search') selected @endif">Implementos de laboratorio</a></li>
        <li class="nav-item ml-2 col-1-6 p-0"><a href="{{ route('contactus') }}" class="nav-link p-0 pb-1 @if(\Route::current()->getName() == 'contactus') selected @endif">Contáctenos</a></li>
        <li class="ml-2 nav-item col-2 p-0 text-align-right">
           <a class="btn btn-primary btn-sm badge-pill fs-lg--1" href="{{ route('user.showLogin') }}">Inicio de Sesión</a>
        </li>
        <li class="ml-2 nav-item p-0">
           <a class="btn btn-secondary btn-sm badge-pill  fs-lg--1" href="{{ route('registerForm') }}">Registro</a>
        </li>
        <li class="nav-item">
           <a class="px-0 notification-indicator notification-indicator-warning notification-indicator-fill nav-link" href="{{route('shoppingCart')}}">
              <span class="notification-indicator-number" ng-init="initNumberShoppingCart()">0</span>
              <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="shopping-cart" class="svg-inline--fa fa-shopping-cart fa-w-18 fs-4" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" style="transform-origin: 0.5625em 0.5em;">
                 <g transform="translate(288 256)">
                    <g transform="translate(0, 0)  scale(0.5625, 0.5625)  rotate(0 0 0)">
                       <path fill="currentColor" d="M528.12 301.319l47.273-208C578.806 78.301 567.391 64 551.99 64H159.208l-9.166-44.81C147.758 8.021 137.93 0 126.529 0H24C10.745 0 0 10.745 0 24v16c0 13.255 10.745 24 24 24h69.883l70.248 343.435C147.325 417.1 136 435.222 136 456c0 30.928 25.072 56 56 56s56-25.072 56-56c0-15.674-6.447-29.835-16.824-40h209.647C430.447 426.165 424 440.326 424 456c0 30.928 25.072 56 56 56s56-25.072 56-56c0-22.172-12.888-41.332-31.579-50.405l5.517-24.276c3.413-15.018-8.002-29.319-23.403-29.319H218.117l-6.545-32h293.145c11.206 0 20.92-7.754 23.403-18.681z" transform="translate(-288 -256)"></path>
                    </g>
                 </g>
              </svg>
           </a>
        </li>
        <li class="nav-item ml-auto mr-auto">
           <form class="search-box form-inline ng-pristine ng-valid">
              <input placeholder="Buscar..." aria-label="Search" type="search" class="rounded-pill search-input form-control">
              <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="search" class="svg-inline--fa fa-search fa-w-16 position-absolute text-400 search-box-icon" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                 <path fill="currentColor" d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path>
              </svg>
           </form>
        </li>
     </ul>
  @endguest
</nav>
