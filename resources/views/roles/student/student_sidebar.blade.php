<nav  ng-controller="navbarController"  id="sideMenu" class="navbar-vertical navbar-glass navbar navbar-expand-xl navbar-light max-width-sidemenu"
   style="display:none">
   <div class="collapse show navbar-collapse" aria-expanded="true" style="">
      <div class="ScrollbarsCustom trackYVisible"
         style="position: relative; width: 100%; height: 85vh; display: block;">
         <div class="ScrollbarsCustom-Wrapper"
            style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 10px; overflow: hidden;">
            <div class="ScrollbarsCustom-Scroller"
               style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; direction: ltr; overflow: hidden scroll; padding-right: 20px; margin-right: -21px;">
               <div class="ScrollbarsCustom-Content"
                  style="box-sizing: border-box; padding: 0.05px; min-height: 100%; min-width: 100%;">
                  
                  <ul class="navbar-nav flex-column">
                  @auth('afiliadoempresa')
                    @if(auth('afiliadoempresa')->user())
                     @if(auth('afiliadoempresa')->user()->hasAnyRole('student'))
                        <li class="nav-item">
                            <a class="nav-link" aria-expanded="false" href="{{ route('student', auth('afiliadoempresa')->user()->company_name()) }}" >
                               <div class="d-flex align-items-center">
                                  <span class="ml-2 mr-2 nav-link-icon">
                                     <i class="fas fa-user fs-1" style="color:#aecb41;"></i>
                                  </span>
                                  Mi perfíl
                               </div>
                            </a>
                        </li>
                     
                        <li class="nav-item">
                            <a class="nav-link" aria-expanded="false" href="{{ route('student.available_sequences',auth('afiliadoempresa')->user()->company_name()) }}" >
                               <div class="d-flex align-items-center">
                                  <span class="ml-2 mr-2 nav-link-icon">
                                     <i class="fas fa-book-open fs-1" style="color:#26b7c4;"></i>
                                  </span>
                                   Guías de aprendizaje
                               </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-expanded="false" href="{{ route('student.achievements',auth('afiliadoempresa')->user()->company_name()) }}" >
                               <div class="d-flex align-items-center">
                                  <span class="ml-2 mr-2 nav-link-icon">
                                     <i class="fas fa-star fs-1" style="color:#5f347c;"></i>
                                  </span>
                                   Logros
                               </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" ng-click="closeSession('{{ route('user.logout') }}')"
                               aria-expanded="false" >
                               <div class="d-flex align-items-center">
                                  <span class="ml-2 mr-2 nav-link-icon">
                                     <i class="fas fa-door-open fs-1" style="color:#35af7e;"></i>
                                  </span>
                                   Salir
                               </div>
                            </a>
                        </li>
                      @endif
                    @endif
                  @endauth
               </div>
            </div>
         </div>
      </div>
   </div>
</nav>
