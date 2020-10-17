@extends('layouts.app_side')
@section('plugins')
<link rel="stylesheet" href="../../../jstree/themes/default/style.min.css">
@endsection

@section('content')
<div class="container" ng-controller="managementPagesCtrl" ng-init="init()">
   <div class="content">
      <div class="row">
         <div class="col-lg-3 open" id="sidemenu-page">
            <div ng-show="applyChange" class="position-absolute w-100 h-50vw cursor-not-allowed"
               ng-click="openChangeAlert()" style="top:0;">
            </div>
            <div class="mb-3 card fade show p-3 overflow-auto row" id="sidemenu-page-content">
               <div id="jstree" class="fs--1">
                <ul>
                   <li ng-repeat="page in pages" data-jstree='{ 
                      "pageIndex":"@{{$index}}", 
                      "pageName":"@{{page.name}}",
                      "selected": @{{ pageSelected.index === $index || (pageSelected === null && $index === 0)}},
                      "icon": "jstree-file"}'>
                      @{{page.name}}
                   </li>
                </ul>
               </div>
            </div>
            <div class="mb-3 card fade show d-none d-block p-3 height_282 row" id="sidemenu-tools-content" style="overflow-y: auto;">
               <div class="row no-gutters">
                  <div class="col-5">
                     <h6> Herramientas</h6>
                     <ul class="navbar-nav flex-column">
                        <li class="nav-item">
                           <a class="nav-link" href="#" ng-click="onNewElement('text-element')">
                              <div class="d-flex align-items-center">
                                 <i class="fas fa-heading mr-3"></i>
                                 Texto
                              </div>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" href="#" ng-click="onNewElement('text-area-element')">
                              <div class="d-flex align-items-center">
                                 <i class="fas fa-align-left mr-3"></i>
                                 Párrafo
                              </div>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" href="#" ng-click="onNewElement('image-element')">
                              <div class="d-flex align-items-center">
                                 <i class="fas fa-image mr-3"></i>
                                 Imágen
                              </div>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" href="#" ng-click="onNewElement('video-element')">
                              <div class="d-flex align-items-center">
                                 <i class="fab fa-youtube mr-3"></i>
                                 Video
                              </div>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a class="nav-link" href="#" ng-click="onNewElement('button-element')">
                              <div class="d-flex align-items-center">
                                 <i class="fab fa-flickr mr-3"></i>
                                 Botón
                              </div>
                           </a>
                        </li>
                     </ul>
                  </div>
                  
                  <div class="col-7">
                     <h6 ng-hide="showCopyButton ||  showDeleteButton || copyCache.length > 0 || copyBackground">
                        <span> 
                        @{{ (pageSelected.elements.length === 1 ?  '1 Elemento' : 
                           pageSelected.elements.length === 0 ? 'No hay Elementos' : pageSelected.elements.length + ' Elementos' ) }}
                        </span>
                     </h6>
                     <div>
                         <button ng-show="copyCache && copyCache.length > 0" ng-click="onPasteElements()"  class="btn btn-sm btn-outline-warning mb-3" >
                              Pegar
                         </button>
                         <button ng-show="copyBackground" ng-click="onPasteBackground()" class="btn btn-sm btn-outline-warning mb-3" >
                           Pegar Fondo
                         </button>
                         <button ng-show="showCopyButton" ng-click="onCopyElements()" class="btn btn-sm btn-outline-primary mb-3" > 
                            Copiar
                         </button>
                         <button ng-show="showDeleteButton" ng-click="onDeleteSelectedElements()" class="btn btn-sm btn-outline-danger mb-3" > 
                            Eliminar
                         </button>
                         
                     </div>
                   
                     <div ng-repeat="element in pageSelected.elements track by $index" class="cursor-pointer">
                        <div class="d-flex align-items-center fs--2">
                           <input type="checkbox" ng-model="element.selected" ng-change="onChangeElementSelected()" >
                           <div ng-click="onClickElementWithDelete(pageSelected,element,$index)">
                               <i ng-show="element.type === 'text-element'" class="fas fa-heading mr-3"></i>
                               <i ng-show="element.type === 'text-area-element'" class="fas fa-align-left mr-3"></i>
                               <i ng-show="element.type === 'image-element'" class="fas fa-image mr-3"></i>
                               <i ng-show="element.type === 'video-element'" class="fab fa-youtube mr-3"></i>
                               <i ng-show="element.type === 'button-element'" class="fab fa-flickr mr-3"></i>
                               <i ng-show="element.type === 'evidence-element'" class="fas fa-edit mr-3"></i>
                               @{{element.type}}
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="h-75 mb-3 fade show d-none card w-10" id="sidemenu-page-empty">
            </div>
            <div class="d-none d-lg-block text-sans-serif dropdown position-absolute cursor-pointer"
               style="top: 91px; right:7px;" ng-click="toggleSideMenu();">
               <i class="far fa-caret-square-left" id="sidemenu-page-button"></i>
            </div>
         </div>
         <div class="col-lg-9" id="content-section-page">
            <div ng-show="errorMessage" id="errorMessage"
               class="fade-message d-none-result d-none alert alert-danger p-1 pl-2 row">
               <span class="col">@{{ errorMessage }}</span>
               <span class="col-auto"><a ng-click="errorMessage = null"><i class="far fa-times-circle"></i></a></span>
            </div>
            <div class="mb-3 card z-index-0">
               <div class="card-header d-flex bg-light ">
                  <h5 class="">@{{pageSelected.name}}</h5>
                  <div class="ml-auto">
                     <button ng-disabled="!applyChange" class="btn btn-sm btn-outline-primary ml-2"
                        ng-click="onSavePage()">Guardar</button>
                     <div class="d-flex position-absolute r-0 fs--2 ml-auto" style="top: 26px;right: 113px;">
                        <span>Ancho: @{{container.w}} Alto: <input type="number" style="width:50px;" class="ml-2"
                              size="2" styletype="number" ng-model="container.h" ng-change="onChangeHeight()" />
                     </div>
                  </div>
               </div>
               <div ng-class="{'h-100': dataJstree.type === 'openSequence'}"
                  class="card-body min-card-body p-0 background-page-card z-index--1" w="@{{container.w}}"
                  h="@{{container.h}}">
                  <div ng-show="dataJstree.pageName === 'tutorial'">
                     <div class="col-1 d-flex">
                        <img ng-show="pageSelected.background_image" class="background-sequence-image z-index--1"
                           src="../../../@{{pageSelected.background_image}}" />
                        <button class="edit-button-background btn btn-sm btn-outline-primary"
                           ng-click="onClickElement(pageSelected ,'background_image','Fondo','img')">Fondo</button>
                        <a class="cursor-pointer" ng-show="pageSelected.background_image.length > 0"
                           ng-click="deleteBackgroundSection()" style="marging-top: 8px:;">
                           <i class="far fa-times-circle"></i>
                        </a>
                     </div>
                     <div class="col-4 fs--1 position-absolute"
                        ng-repeat="element in pageSelected.elements track by $index">
                        <div id="@{{element.type==='text-element' ? element.id : ''}}"
                           ng-show="element.type==='text-element'" ml="@{{element.ml}}" mt="@{{element.mt}}"
                           w="@{{element.w}}" h="@{{element.h}}" fs="@{{element.fs}}"
                           class="@{{element.class}} font-text conx-element"
                           ng-class="{'selected':element.selected}"
                           ng-click="onClickElementWithDelete(pageSelected,element,$index)"
                           style="@{{element.style}}"
                           ng-style="{'color':element.color, 'background-color': element.background_color}"
                           ng-bind-html = "element.text"> 
                           
                           <div class="delete-element" ng-click="onDeleteElement(pageSelected,$index,true)">
                              <i class="far fa-times-circle"></i>
                           </div>
                        </div>
                        <div id="@{{element.type==='text-area-element' ? element.id : ''}}"
                           ng-show="element.type==='text-area-element'" ml="@{{element.ml}}" mt="@{{element.mt}}"
                           w="@{{element.w}}" h="@{{element.h}}" fs="@{{element.fs}}"
                           class="@{{element.class}} font-text conx-element"
                           ng-class="{'selected':element.selected}"
                           ng-click="onClickElementWithDelete(pageSelected ,element,$index)"
                           style="@{{element.style}}"
                           ng-style="{'color':element.color, 'background-color': element.background_color}"
                           ng-bind-html = "element.text">
                            
                           <div class="delete-element" ng-click="onDeleteElement(pageSelected ,$index,true)">
                              <i class="far fa-times-circle"></i>
                           </div>
                        </div>
                        <div ng-show="element.type==='image-element'" mt="@{{element.mt}}" ml="@{{element.ml}}"
                           class="font-text conx-element"
                           ng-click="onClickElementWithDelete(pageSelected ,element,$index)">
                           <img class="@{{element.class}} conx-element" style="@{{element.style}}"
                              ng-class="{'selected':element.selected}"
                              id="@{{element.type==='image-element' ? element.id : ''}}"
                              src="@{{'/'+element.url_image || '/images/icons/NoImageAvailable.jpeg'}}"
                              w="@{{element.w}}" h="@{{element.h}}" />
                           <div class="delete-element" ng-click="onDeleteElement(pageSelected ,$index,true)">
                              <i class="far fa-times-circle"></i>
                           </div>
                        </div>
                        <div ng-show="element.type==='video-element'" mt="@{{element.mt}}" ml="@{{element.ml}}"
                           style="@{{element.style}}" class="@{{element.class}} font-text conx-element" ng-class="{'selected':element.selected}">
                           <iframe id="@{{element.type==='video-element' ? element.id : ''}}"
                              refreshable="element.url_vimeo" src="https://player.vimeo.com/video/286898202"
                              w="@{{element.w}}" h="@{{element.h}}" frameborder="0" webkitallowfullscreen="false"
                              mozallowfullscreen="false" allowfullscreen="false">
                           </iframe>
                           <button class="btn btn-sm btn-primary position-absolute"
                              ng-click="onClickElementWithDelete(pageSelected ,element,$index)">
                              Editar
                           </button>
                           <span conx-draggable="element">
                              <i class="fas fa-arrows-alt position-absolute"
                                 style="left: 70px;color: white;font-size: 23px;border: 1px solid gray;border-radius: 34px;   padding:8px;height: 42px;width: 42px;margin-left: 10px;background: rgba(243, 243, 243, 0.2);"></i>
                           </span>
                           <div class="delete-element" ng-click="onDeleteElement(pageSelected ,$index,true)">
                              <i class="far fa-times-circle"></i>
                           </div>
                        </div>
                        <div ng-show="element.type==='button-element'" mt="@{{element.mt}}" ml="@{{element.ml}}"
                           class="conx-element">
                           <div id="@{{element.type==='button-element' ? element.id : ''}}"
                              class="@{{element.class}} conx-element position-absolute" 
                              style="@{{element.style}}"
                              ng-class="{'selected':element.selected}"
                              ng-style="{'color':element.color, 'background-color': element.background_color}"
                              w="@{{element.w}}" h="@{{element.h}}" fs="@{{element.fs}}" conx-draggable="element"
                              ng-click="onClickElementWithDelete(pageSelected,element,$index)">
                              @{{element.text}}
                           </div>
                           <div class="delete-element" ng-click="deleteElement(pageSelected,$index,true)">
                              <i class="far fa-times-circle"></i>
                           </div>
                        </div>
                        <div ng-show="element.type==='evidence-element'" mt="@{{element.mt}}" ml="@{{element.ml}}"
                           class="conx-element">
                           <div id="@{{element.type==='evidence-element' ? element.id : ''}}" style="@{{element.style}}"
                              class="@{{element.class}} conx-element position-absolute evidence-head"
                              ng-class="{'selected':element.selected}"
                              ng-style="{'color':element.color, 'background-color': element.background_color}"
                              w="@{{element.w}}" h="@{{element.h}}" fs="@{{element.fs}}" conx-draggable="element"
                              ng-click="onClickElementWithDelete(pageSelected ,element,$index)">
                              <img src="/@{{element.icon || 'images/icons/evidenciasAprendizajeIcono-01.png' }}"
                                 width="auto" height="40px" />
                              @{{element.text}}
                           </div>
                           <div class="delete-element" ng-click="onDeleteElement(pageSelected ,$index,true)">
                              <i class="far fa-times-circle"></i>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="edit-element-panel" ng-show="typeEdit">
      <div class="modal-backdrop fade show" ng-click="typeEdit='';indexElement=null"></div>
      <div class="mb-3 card">
         <div class="card-header">
            <h5 class="">@{{titleEdit}}</h5>
            <div ng-click="typeEdit=''" class="position-absolute fs-2 cursor-pointer"
               style="top: 3px;right: 16px;text-align: right;position: absolute;">
               <i class="far fa-times-circle"></i>
            </div>
            <div class="position-absolute" style="top:7px; right:51px;" ng-show="indexElement || indexElement >= 0">
               <button class="btn btn-sm btn-outline-secondary"
                  ng-show=" ( dataJstree.type==='openSequenceSectionPart' || dataJstree.type==='openMomentSectionPart' )  && titleEdit != 'Fondo'"
                  ng-click="onCopyElements(indexElement)">
                  <i class="fa fa-trash" aria-hidden="true"></i> Copiar
               </button>
               <button class="btn btn-sm btn-outline-secondary"
                  ng-show="pageSelected.background_image.length > 0 && ( dataJstree.type==='openSequenceSectionPart' || dataJstree.type==='openMomentSectionPart' )  && titleEdit === 'Fondo'"
                  ng-click="onCopyBackground()">
                  <i class="fa fa-trash" aria-hidden="true"></i> Copiar 
               </button>
               <button ng-show="indexElement" class="btn btn-sm btn-outline-warning"
                  ng-click="onDeleteElement(pageSelected, indexElement,false)">
                  <i class="fa fa-trash" aria-hidden="true"></i> Eliminar
               </button>
            </div>
         </div>
         <div class="bg-light card-body">
            <input type="text" ng-show="typeEdit === 'text'" ng-change="onChangeInput(pageSelected[elementEdit])"
               ng-model="pageSelected[elementEdit]" class="w-100" />
            <textarea ng-change="onChangeInput(elementEdit.text)" ng-show="typeEdit === 'textArea'"
               ng-model="pageSelected[elementEdit]" rows="5" class="w-100">
            </textarea>
            <input ng-show="typeEdit === 'video'" ng-change="onChangeInput(pageSelected[elementEdit])"
               ng-model="pageSelected[elementEdit]" class="w-100" />

            <div ng-show="typeEdit === 'slide-images'">
               <h6><small>Seleccione el directorio</small></h6>
               <div class="col-12 d-flex">
                  <h6 class="p-2 cursor-pointer conex-table card-header w-100" style="border: 1px solid;"
                     ng-click="mbImageShow=!mbImageShow">Directorio: <small>@{{directoryPath}}</small>
                  </h6>
                  <div ng-show="!mbImageShow" class="cursor-pointer" ng-click="mbImageShow = true;"
                     style="position: absolute;right: 35px;top: 5px;">
                     <i class="fas fa-caret-down"></i>
                  </div>
                  <div ng-hide="!mbImageShow" class="cursor-pointer" ng-click="mbImageShow = false;"
                     style="position: absolute;right: 35px;top: 5px;">
                     <i class="fas fa-caret-up"></i>
                  </div>
               </div>
               <div class="bg-light mb-3 row edit-div-folder pt-2" ng-show="mbImageShow">
                  <ul class="p-0 list-inline mt-2 mb-0">
                     <li class="mb-2 ml-2" ng-repeat="field in directory" style="opacity:0.8">
                        <a class="btn btn-sm btn-outline-primary" href="#"
                           ng-click="onChangeFolderSlideImage(field.dir)">
                           @{{field.name}}
                        </a>
                     </li>
                  </ul>
                  <div class="col-12 row mt-3">
                     <div ng-repeat="field in filesImages" class="col-4">
                        <img ng-src="/@{{field.url_image}}" width="79px" height="auto" style="opacity:0.8"/>
                     </div>
                  </div>
               </div>
               <div class="line-separator"></div>
            </div>
            <div ng-show="typeEdit === 'img'">
               <img ng-src="/@{{pageSelected[elementEdit]}}" width="79px" height="auto" class="" />
               <div class="line-separator"></div>
               <div class="col-12 d-flex">
                  <h6 class="p-2 mt-3 cursor-pointer conex-table card-header w-100" style="border: 1px solid;"
                     ng-click="mbImageShow=!mbImageShow">Directorio: <small>@{{directoryPath}}</small>
                  </h6>
                  <div ng-show="!mbImageShow" class="cursor-pointer" ng-click="mbImageShow = true;"
                     style="position: absolute;right: 35px;top: 19px;">
                     <i class="fas fa-caret-down"></i>
                  </div>
                  <div ng-hide="!mbImageShow" class="cursor-pointer" ng-click="mbImageShow = false;"
                     style="position: absolute;right: 35px;top: 19px;">
                     <i class="fas fa-caret-up"></i>
                  </div>
               </div>
               <div class="bg-light mb-3 row edit-div-folder pt-2" ng-show="mbImageShow">
                  <ul class="p-0 list-inline mt-2 mb-0">
                     <li class="mb-2 ml-2" ng-repeat="field in directory">
                        <a class="btn btn-sm btn-outline-primary" href="#" ng-click="onChangeFolderImage(field.dir)">
                           @{{field.name}}
                        </a>
                     </li>
                  </ul>
                  <div class="col-12 row mt-3">
                     <div ng-repeat="field in filesImages" class="col-4">
                        <img ng-src="/@{{field.url_image}}" width="79px" height="auto" ng-click="onImgChange(field)"
                           class="cursor-pointer" />
                     </div>
                  </div>
               </div>
               <div class="line-separator"></div>
            </div>
            <div ng-show="typeEdit === 'text-list'">
               <conx-text-list elementParent="pageSelected" elementChild="elementEdit"></conx-text-list>
            </div>
            <div ng-show="typeEdit === 'date'">
               <input id="typeEditDateInput" placeholder="día/mes/año" type="date" data-date=""
                  ng-change="changeFormatDate(pageSelected,elementEdit,'YYYY-MM-DD')" data-date-format="YYYY-MM-DD"
                  ng-model="pageSelected[elementEdit]"/>
               <a class="" ng-click="clearChangeFormatDate()">
               <i class=" far fa-times-circle"></i> 
               </a>
            </div>
            <div ng-show="typeEdit === 'text-element' || typeEdit === 'text-area-element'">
               <div ng-show="typeEdit === 'text-area-element'">
                  <textarea ng-change="onChangeInput(elementEdit.text)" ng-show="typeEdit === 'text-area-element'"
                     ng-model="elementEdit.text" rows="5" class="w-100 h-100">
                  </textarea>
               </div>
               <div ng-show="typeEdit === 'text-element'">
                  <input type="text" ng-change="onChangeInput(elementEdit.text)" ng-model="elementEdit.text"
                     class="w-100" />
               </div>
               <div class="d-flex mt-3">
                  <i class="fas fa-arrow-right mr-2"></i>
                  <input class="mr-2 col-4" type="number" ng-keypress="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.ml" />
                  <i class="fas fa-arrow-down mr-2 "></i>
                  <input class="col-4" type="number" ng-keypress="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.mt" />
               </div>
               <div class="d-flex mt-3">
                  <i class="fas fa-arrows-alt-h mr-2"></i>
                  <input class="mr-2 col-4" type="number" ng-keypress="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.w" />
                  <i class="fas fa-arrows-alt-v mr-2"></i>
                  <input class="col-4" type="number" ng-keypress="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.h" />
               </div>
               <div class="d-flex mt-3">
                  <i class="fas fa-text-height mr-2"></i><small>Tamaño letra</small>
                  <input class="col-4 ml-2" type="number" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.fs" />
               </div>
               <div class="d-flex mt-3">
                  <i class="fas fa-palette mr-2"></i><small>Color</small>
                  <input class="ml-2" type="color" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.color" />
                  <input class="ml-2" type="text" size="7" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.color" />
               </div>
               <div class="d-flex mt-3">
                  <i class="fas fa-fill-drip mr-2"></i><small>Fondo</small>
                  <input class="ml-2" type="color" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.background_color" />
                  <input class="ml-2" type="text" size="7" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.background_color" />
               </div>
               <div class="d-flex mt-3">
                  <i class="fas fa-map-pin mr-2"></i><small>Clases de estilos</small>
                  <input class="w-100 ml-2" type="text" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.class" />
               </div>
               <div class="d-flex mt-3">
                  <i class="fab fa-accusoft mr-2"></i><small>Estilos</small>
                  <input class="w-100 ml-2" type="text" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.style" />
               </div>
            </div>
            <div ng-show="typeEdit === 'image-element'">
               <img ng-src="/@{{elementEdit.url_image}}" width="79px" height="auto" class="" />
               <div class="line-separator"></div>
               <div class="col-12 d-flex">
                  <h6 class="p-2 mt-3 cursor-pointer conex-table card-header w-100" style="border: 1px solid;"
                     ng-click="mbImageShow=!mbImageShow">Directorio: <small>@{{directoryPath}}</small>
                  </h6>
                  <div ng-show="!mbImageShow" class="cursor-pointer" ng-click="mbImageShow = true;"
                     style="position: absolute;right: 35px;top: 19px;">
                     <i class="fas fa-caret-down"></i>
                  </div>
                  <div ng-hide="!mbImageShow" class="cursor-pointer" ng-click="mbImageShow = false;"
                     style="position: absolute;right: 35px;top: 19px;">
                     <i class="fas fa-caret-up"></i>
                  </div>
               </div>
               <div class="bg-light mb-3 row edit-div-folder pt-2" ng-show="mbImageShow">
                  <ul class="p-0 list-inline mt-2 mb-0">
                     <li class="mb-2 ml-2" ng-repeat="field in directory">
                        <a class="btn btn-sm btn-outline-primary" href="#" ng-click="onChangeFolderImage(field.dir)">
                           @{{field.name}}
                        </a>
                     </li>
                  </ul>
                  <div class="col-12 row mt-3">
                     <div ng-repeat="field in filesImages" class="col-4">
                        <img ng-src="/@{{field.url_image}}" width="79px" height="auto" ng-click="onImgChange(field)"
                           class="cursor-pointer" />
                     </div>
                  </div>
               </div>
               <div class="line-separator"></div>
               <div class="d-flex mt-3">
                  <i class="fas fa-arrow-right mr-2"></i>
                  <input class="mr-2 col-4" type="number" ng-keypress="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.ml" />
                  <i class="fas fa-arrow-down mr-2 "></i>
                  <input class="col-4" type="number" ng-keypress="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.mt" />
               </div>
               <div class="d-flex mt-3">
                  <i class="fas fa-arrows-alt-h mr-2"></i>
                  <input class="mr-2 col-4" type="number" ng-keypress="onChangeWidthHeight(elementEdit,'w')"
                     ng-change="onChangeWidthHeight(elementEdit,'w')" ng-model="elementEdit.w" />
                  <i class="fas fa-arrows-alt-v mr-2"></i>
                  <input class="col-4" type="number" ng-keypress="onChangeWidthHeight(elementEdit,'h')"
                     ng-change="onChangeWidthHeight(elementEdit,'h')" ng-model="elementEdit.h" />
                  <button class="btn btn-sm pl-2 pr-2 ml-2"
                     ng-class="{'btn-outline-primary':!bindWidthHeight, 'btn-outline-primary': bindWidthHeight}"
                     ng-click="bindWidthHeight=!bindWidthHeight">
                     <i class="fas fa-link"></i>
                  </button>
               </div>
               <div class="d-flex mt-3">
                  <i class="fas fa-map-pin mr-2"></i><small>Clases de estilos</small>
                  <input class="w-100 ml-2" type="text" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.class" />
               </div>
               <div class="d-flex mt-3">
                  <i class="fab fa-accusoft mr-2"></i><small>Estilos</small>
                  <input class="w-100 ml-2" type="text" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.style" />
               </div>
            </div>
            <div ng-show="typeEdit === 'video-element'">
               <input type="text" ng-change="onChangeInput(elementEdit.url_vimeo)" ng-model="elementEdit.url_vimeo"
                  class="w-100" />
               <div class="line-separator"></div>
               <div class="d-flex mt-3">
                  <i class="fas fa-arrow-right mr-2"></i>
                  <input class="mr-2 col-4" type="number" ng-keypress="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.ml" />
                  <i class="fas fa-arrow-down mr-2 "></i>
                  <input class="col-4" type="number" ng-keypress="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.mt" />
               </div>
               <div class="d-flex mt-3">
                  <i class="fas fa-arrows-alt-h mr-2"></i>
                  <input class="mr-2 col-4" type="number" ng-keypress="onChangeWidthHeight(elementEdit,'w')"
                     ng-change="onChangeWidthHeight(elementEdit,'w')" ng-model="elementEdit.w" />
                  <i class="fas fa-arrows-alt-v mr-2"></i>
                  <input class="col-4" type="number" ng-keypress="onChangeWidthHeight(elementEdit,'h')"
                     ng-change="onChangeWidthHeight(elementEdit,'h')" ng-model="elementEdit.h" />
                  <button class="btn btn-sm pl-2 pr-2 ml-2"
                     ng-class="{'btn-outline-primary':!bindWidthHeight, 'btn-outline-primary': bindWidthHeight}"
                     ng-click="bindWidthHeight=!bindWidthHeight">
                     <i class="fas fa-link"></i>
                  </button>
               </div>
               <div class="d-flex mt-3">
                  <i class="fas fa-map-pin mr-2"></i><small>Clases de estilos</small>
                  <input class="w-100 ml-2" type="text" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.class" />
               </div>
               <div class="d-flex mt-3">
                  <i class="fab fa-accusoft mr-2"></i><small>Estilos</small>
                  <input class="w-100 ml-2" type="text" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.style" />
               </div>
            </div>
            <div ng-show="typeEdit === 'button-element'">
               <input type="text" ng-change="onChangeInput(elementEdit.text)" ng-model="elementEdit.text"
                  class="w-100" />
               <div class="d-flex mt-3">
                  <i class="fas fa-arrow-right mr-2"></i>
                  <input class="mr-2 col-4" type="number" ng-keypress="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.ml" />
                  <i class="fas fa-arrow-down mr-2 "></i>
                  <input class="col-4" type="number" ng-keypress="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.mt" />
               </div>
               <div class="d-flex mt-3">
                  <i class="fas fa-arrows-alt-h mr-2"></i>
                  <input class="mr-2 col-4" type="number" ng-keypress="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.w" />
                  <i class="fas fa-arrows-alt-v mr-2"></i>
                  <input class="col-4" type="number" ng-keypress="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.h" />
               </div>
               <div class="d-flex mt-3">
                  <i class="fas fa-text-height mr-2"></i><small>Tamaño letra</small>
                  <input class="col-4 ml-2" type="number" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.fs" />
               </div>
               <div class="d-flex mt-3">
                  <i class="fas fa-palette mr-2"></i><small>Color</small>
                  <input class="ml-2" type="color" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.color" />
                  <input class="ml-2" type="text" size="7" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.color" />
               </div>
               <div class="d-flex mt-3">
                  <i class="fas fa-fill-drip mr-2"></i><small>Fondo</small>
                  <input class="ml-2" type="color" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.background_color" />
                  <input class="ml-2" type="text" size="7" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.background_color" />
               </div>
               <div class="d-flex mt-3">
                  <i class="fas fa-map-pin mr-2"></i><small>Clases de estilos</small>
                  <input class="w-100 ml-2" type="text" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.class" />
               </div>
               <div class="d-flex mt-3">
                  <i class="fab fa-accusoft mr-2"></i><small>Estilos</small>
                  <input class="w-100 ml-2" type="text" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.style" />
               </div>

               <div class="line-separator"></div>
               <div class="d-flex mt-3">
                  <h6>
                     <i class="fab fa-flickr mr-3"></i>Ir a:</h6>
                  <select ng-change="onChangeInput()" ng-model="elementEdit.action" class="ml-3 w-75">
                     <option value=""></option>
                     <option ng-repeat="moment in moments" value="@{{sequence.id+'|'+moment.id+'|'+moment.order+'|1'}}">
                        momento @{{$index + 1}}</option>
                  </select>
               </div>
            </div>
            <div ng-show="typeEdit === 'evidence-element'">
               <div class="position-absolute text-align" ng-click="openEvidence(elementEdit)"
                  style="right: 15px;top: 102px;">
                  <h6 class="cursor-pointer mb-0 fs--2" style=""> Evidencias </h6>
                  <img src="/images/icons/evidences.png" width="34" height="34" class="cursor-pointer">
               </div>
               <div ng-show="showEvidenceModal" class="w-100 h-100 position-absolute" style="top:0; left:0;">
                  <div class="modal-backdrop fade show"></div>
                  <div class="modal-menu card-notification shadow-none card"
                     style="width: 517px;position: absolute;left: -140px;top: -29px;min-height: 458px;">
                     <div class="card-header bg-light pl-4">
                        Evidencias de aprendizaje
                     </div>
                     <div class="fs--1 position-absolute" style="right: 12px; top: 7px;">
                        <span class=""> Tipo Pregunta @{{elementEdit.questionEditType}}</span>
                        <select ng-model="elementEdit.questionEditType">
                           <option value="1">Pregunta sin respuesta</option>
                           <option value="2">Pregunta cerrada </option>
                           <option value="3">Pregunta docente </option>
                        </select>
                     </div>
                     <div class="position-absolute" style="left: 7px;top: 9px;" ng-click="closeEvidence()">
                        <i class="fas fa-arrow-left cursor-position"></i>
                     </div>
                     <div class="card-body" style="overflow: auto;">
                        <h6>Preguntas</h6>
                        <conx-evidence-questions></conx-evidence-questions>
                        <div ng-show="showHTMLEditor" class="card"
                           style="padding: 42px 0 0 1px;position: absolute;top: 0px;left: -154px;height: 659px; width: 567px;">
                           <div ng-click="onCloseHTMLEditor();" class="position-absolute fs-2 cursor-pointer"
                              style="top: 3px;right: 16px;left: 35px;text-align: right;">
                              <i class="far fa-times-circle"></i>
                           </div>
                           <textarea id="editorhtml" name="editorhtml"></textarea>
                        </div>
                        <div class="line-separator"></div>
                        <div class="" ng-show="questionEdit">
                           <div class="mt-2">
                              <h6>Pregunta @{{questionEdit.$index + 1}}:
                                 <small ng-show="questionEdit.isHtml" style="width:10px"
                                    ng-bind-html="questionEdit.title"></small>
                                 <small ng-hide="questionEdit.isHtml">@{{questionEdit.title}}</small>
                              </h6>
                           </div>
                           <div class="p-2">
                              <h6>Objetivo</h6>
                              <textarea class="ml-2 fs--1 w-100" ng-change="applyChange = true"
                                 ng-model="questionEdit.objective" rows="3"></textarea>
                              </textarea>
                           </div>
                           <div class="p-2" ng-show="elementEdit.questionEditType==='2'">
                              <h6>Concepto:</h6>
                              <textarea class="ml-2 fs--1 w-100" ng-change="applyChange = true"
                                 ng-model="questionEdit.concept" rows="3"></textarea>
                              </textarea>
                           </div>
                           <div class="p-2" ng-show="elementEdit.questionEditType==='2'">
                              <h6>Respuestas</h6>
                              <conx-evidence-options></conx-evidence-options>
                              <div ng-show="showHTMLEditorAnswer" class="card"
                               style="padding: 42px 0 0 1px;position: absolute;top: 0px;left: -154px;height: 659px; width: 567px;">
                               <div ng-click="onCloseHTMLEditorAnswer();" class="position-absolute fs-2 cursor-pointer"
                                  style="top: 3px;right: 16px;left: 35px;text-align: right;">
                                  <i class="far fa-times-circle"></i>
                               </div>
                               <textarea id="editorAnserHtml" name="editorAnserHtml"></textarea>
                              </div>
                           </div>
                           <div class="p-2" ng-show="elementEdit.questionEditType==='2'">
                              <div class="line-separator m-0"></div>
                              <h6 class="mt-2">Calificación</h6>
                              <div type="text" ng-repeat="itemReview in questionEdit.review track by $index">
                                 <span class="fs--1 font-weight-semi-bold">@{{itemReview.id}}</span>
                                 <input class="ml-2 fs--1" type="text" ng-change="applyChange = true"
                                    ng-model="itemReview.review" />
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <input type="text" ng-change="onChangeInput(elementEdit.text)" ng-model="elementEdit.text"
                  class="w-100" />
               <div class="d-flex mt-3">
       
                  <i class="fas fa-arrow-right mr-2"></i>
                  <input class="mr-2 col-4" type="number" ng-keypress="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.ml" />
                  <i class="fas fa-arrow-down mr-2 "></i>
                  <input class="col-4" type="number" ng-keypress="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.mt" />
               </div>
               <div class="d-flex mt-3">
                  <i class="fas fa-arrows-alt-h mr-2"></i>
                  <input class="mr-2 col-4" type="number" ng-keypress="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.w" />
                  <i class="fas fa-arrows-alt-v mr-2"></i>
                  <input class="col-4" type="number" ng-keypress="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.h" />
               </div>
               <div class="d-flex mt-3">
                  <i class="fas fa-text-height mr-2"></i><small>Tamaño letra</small>
                  <input class="col-4 ml-2" type="number" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.fs" />
               </div>
               <div class="d-flex mt-3">
                  <i class="fas fa-palette mr-2"></i><small>Color</small>
                  <input class="ml-2" type="color" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.color" />
                  <input class="ml-2" type="text" size="7" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.color" />
               </div>
               <div class="d-flex mt-3">
                  <i class="fas fa-fill-drip mr-2"></i><small>Fondo</small>
                  <input class="ml-2" type="color" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.background_color" />
                  <input class="ml-2" type="text" size="7" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.background_color" />
               </div>
               <div class="d-flex mt-3">
                  <i class="fas fa-map-pin mr-2"></i><small>Clases de estilos</small>
                  <input class="w-100 ml-2" type="text" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.class" />
               </div>
               <div class="d-flex mt-3">
                  <i class="fab fa-accusoft mr-2"></i><small>Estilos</small>
                  <input class="w-100 ml-2" type="text" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.style" />
               </div>
               <div class="line-separator"></div>
               <div class="d-flex mt-3">
                  <img class="mr-2" src="/@{{elementEdit.icon}}" width="auto" height="40px">
                  <input class="w-100 ml-2" type="text" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                     ng-model="elementEdit.icon" />
               </div>
               <div class="d-flex mt-3">
                     <small class="font-weight-bold">Subtitulo</small>
                     <input class="w-100 ml-2" type="text" onkeyup="onChangeInput()" ng-change="onChangeInput()"
                        ng-model="elementEdit.subtitle" />
                  </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('js')
<script src="{{asset('/../angular/controller/managementPagesCtrl.js')}}"></script>
<script src="{{asset('/../jstree/jstree.min.js')}}"></script>
<script src="https://cdn.tiny.cloud/1/v4mwkpxb4xl040unqtsepspvu82ecwea01wqejwwy6gmv4jg/tinymce/5/tinymce.min.js"
   referrerpolicy="origin"></script>

@endsection