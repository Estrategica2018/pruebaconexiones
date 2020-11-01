<nav ng-controller="navbarController"  id="sideMenu" class="navbar-vertical navbar-glass navbar navbar-expand-xl navbar-light max-width-sidemenu"
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
                                     <i class="fas fa-user fs-1"></i>
                                  </span>
                                  Mi perfíl
                               </div>
                            </a>
                        </li>
                     
                        <li class="nav-item">
                            <a class="nav-link" aria-expanded="false" href="{{ route('student.available_sequences',auth('afiliadoempresa')->user()->company_name()) }}" >
                               <div class="d-flex align-items-center">
                                  <span class="ml-2 mr-2 nav-link-icon">
                                     <i class="fas fa-book-open fs-1"></i>
                                  </span>
                                   Guías de aprendizaje
                               </div>
                            </a>
                        </li>
                      @endif
                    @endif
                  @endauth
                         
                     <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                           <div class="d-flex align-items-center">
                              <span>Inicio</span>
                           </div>
                        </a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="{{ route('aboutus') }}">
                           <div class="d-flex align-items-center">
                              <span>Acerca de conexiones</span>
                           </div>
                        </a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="{{ route('sequences.search') }}">
                           <div class="d-flex align-items-center">
                              <span>Guías de aprendizaje</span>
                           </div>
                        </a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="{{ route('elementsKits.search') }}">
                           <div class="d-flex align-items-center">
                              <span>Implementos de laboratorio</span>
                           </div>
                        </a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="{{ route('contactus') }}">
                           <div class="d-flex align-items-center">
                              <span></span>
                              <span>Contáctenos</span>
                           </div>
                        </a>
                     </li>
                  </ul>
                  <ul class="navbar-nav flex-column">
                     <li class="nav-item">
                        <a class="nav-link" aria-expanded="false" href="{{ route('shoppingCart') }}">
                           <div class="d-flex align-items-center">
                              <span class="mr-3 nav-link-icon">
                                 <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="shopping-cart"
                                    class="svg-inline--fa fa-shopping-cart fa-w-18 fs-4" role="img"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"
                                    style="transform-origin: 0.5625em 0.5em;">
                                    <g transform="translate(288 256)">
                                       <g transform="translate(0, 0)  scale(0.5625, 0.5625)  rotate(0 0 0)">
                                          <path fill="currentColor"
                                             d="M528.12 301.319l47.273-208C578.806 78.301 567.391 64 551.99 64H159.208l-9.166-44.81C147.758 8.021 137.93 0 126.529 0H24C10.745 0 0 10.745 0 24v16c0 13.255 10.745 24 24 24h69.883l70.248 343.435C147.325 417.1 136 435.222 136 456c0 30.928 25.072 56 56 56s56-25.072 56-56c0-15.674-6.447-29.835-16.824-40h209.647C430.447 426.165 424 440.326 424 456c0 30.928 25.072 56 56 56s56-25.072 56-56c0-22.172-12.888-41.332-31.579-50.405l5.517-24.276c3.413-15.018-8.002-29.319-23.403-29.319H218.117l-6.545-32h293.145c11.206 0 20.92-7.754 23.403-18.681z"
                                             transform="translate(-288 -256)"></path>
                                       </g>
                                    </g>
                                 </svg>
                              </span>
                              Carrito de compras
                           </div>
                        </a>
                     </li>
                     @auth('afiliadoempresa')
                         @if(auth('afiliadoempresa')->user()->hasAnyRole('tutor'))
                         <li class="nav-item">
                            <a class="nav-link"
                               ng-href="{{ route('tutor',auth('afiliadoempresa')->user()->company_name()) }}">
                               <div class="d-flex align-items-center">
                                  <span></span>
                                  <span>Ver estado</span>
                               </div>
                            </a>
                         </li>
                         @elseif(auth('afiliadoempresa')->user()->hasAnyRole('student'))
                         <li class="nav-item">
                            <a class="nav-link"
                               ng-href="{{ route('student',auth('afiliadoempresa')->user()->company_name()) }}">
                               <div class="d-flex align-items-center">
                                  <span></span>
                                  <span>Perfíl &amp; cuenta</span>
                               </div>
                            </a>
                         </li>
                         @endif
                         @if(auth('afiliadoempresa')->user()->hasAnyRole('admin'))
                         <li class="nav-item">
                            <a class="nav-link"
                               ng-href="{{ route('admin',auth('afiliadoempresa')->user()->company_name()) }}">
                               <div class="d-flex align-items-center">
                                  <span></span>
                                  <span>Configuración</span>
                               </div>
                            </a>
                         </li>
                         @endif                         
                         <li class="mt-1 nav-item">
                           <button class="my-3 btn btn-primary btn-sm btn-block" href="{{ route('user.logout') }}"
                             ng-click="closeSession('{{ route('user.logout') }}')">Cerrar Sesión
                           </button> 
                         </li>
                     @else
                     <li class="nav-item">
                        <a class="btn btn-primary btn-sm" href="{{ route('user.login') }}">Iniciar Sesión</a>
                     </li>
                     <li class="mt-2 nav-item">
                        <a class="btn btn-secondary btn-sm" href="{{ route('register') }}">Registro gratis</a>
                     </li>
                     @endauth
                  </ul>
               </div>
            </div>
         </div>
         <span class="TrackY" axis="y"
            style="position: absolute; overflow: hidden; border-radius: 4px; background: rgba(0, 0, 0, 0.1); user-select: none; width: 10px; height: calc(100% - 20px); top: 10px; right: 0px;">
            <div class="ScrollbarsCustom-Thumb ScrollbarsCustom-ThumbY"
               style="touch-action: none; cursor: pointer; border-radius: 4px; background: rgba(0, 0, 0, 0.4); width: 100%; height: 348.506px; transform: translateY(0px);">
            </div>
         </span>
         <div class="ScrollbarsCustom-Track ScrollbarsCustom-TrackX"
            style="position: absolute; overflow: hidden; border-radius: 4px; background: rgba(0, 0, 0, 0.1); user-select: none; height: 10px; width: calc(100% - 20px); bottom: 0px; left: 10px; display: none;">
            <div class="ScrollbarsCustom-Thumb ScrollbarsCustom-ThumbX"
               style="touch-action: none; cursor: pointer; border-radius: 4px; background: rgba(0, 0, 0, 0.4); height: 100%; width: 0px; display: none;">
            </div>
         </div>
      </div>
   </div>
</nav>
