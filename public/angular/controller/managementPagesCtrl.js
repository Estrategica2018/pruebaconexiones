MyApp.controller("managementPagesCtrl", ["$scope", "$http",'$timeout', function($scope, $http, $timeout) {

    $scope.errorMessage = null;
    $scope.pageSelected = null;
    
    $scope.dataJstree = {};
    $scope.applyChange = false;
    $scope.directoryPath = null;
    $scope.widthOriginal = null;
    $scope.heightOriginal = null;
    $scope.mbDelete = null;
    $scope.showCopyButton = false;
    $scope.copyCache = null;
    
    $scope.mbDraggable = false;
    $scope.showDeleteButton = false;
 
    $scope.resizeWidth = function () {
        
        var card = $('.background-page-card');
        var newW = Math.round(Number(card.css('width').replace('px', '')));

        var deltaW = newW - $scope.container.w;
        var deltaH = (deltaW * $scope.container.h) / $scope.container.w;
        var scaleW =  newW / $scope.container.w;
        $scope.container.w = Math.round(newW);
        $scope.container.h = Math.round($scope.container.h + deltaH); 
    
        var w = Number(card.attr('w'));
        var h = Number(card.attr('h'));
        var newW = Number(card.css('width').replace('px', ''));
         
        var deltaW = newW - w;
        var deltaH = (deltaW * h) / w;

        var newH = Math.round(h + deltaH);

        var background = $('.background-sequence-image');
        background.css('width', newW);
        background.css('height', + newH);
        card.css('height', + newH);
        
        $scope.resizeEdit(scaleW);  
    }

    $scope.resizeEdit = function (scaleW) {
        function selectElement(id) { 
            for(var i=0, ele = null;i<$scope.pageSelected.elements.length;i++) {
                ele = $scope.pageSelected.elements[i];
                if(Number(ele.id) === Number(id)) {
                    return ele;
                }
            }
            return null;
        }
      
        var card = $('.background-page-card');
        $(card).find('[fs]').each(function (value, key) {
            var fs = Number($(this).attr('fs')); 
            if(fs >0 ) { 
                var ele = selectElement($(this).attr('id'));
                if(ele) {
                    var newFs = fs * scaleW; 
                    newFs = Math.round(newFs*100)/100;
                    ele.fs = newFs;
                    $(this).css('font-size', newFs + 'px');
                } 
            }
        });
    
        $(card).find('[mt]').each(function (value, key) {
            var mt = $(this).attr('mt');
            if(mt.includes('%')) {
                $(this).css('top', mt);
            }
            else {  
                var id = $(this).attr('id') ? $(this).attr('id') : $(this).find('[id]').attr('id') ;
                var ele = selectElement(id);
                if(ele) {
                    var newMt = Number($(this).attr('mt')) * scaleW;
                    newMt = Math.round(newMt); 
                    ele.mt = newMt; 
                    $(this).css('top', newMt + 'px');
                    $(this).addClass('position-absolute');
                } 
            }  
        });
    
        $(card).find('[ml]').each(function (value, key) {
            
            var ml = $(this).attr('ml');
            if(ml.includes('%')) {
                $(this).css('left', ml);
            }
            else {  
                var id = $(this).attr('id') ? $(this).attr('id') : $(this).find('[id]').attr('id') ;
                var ele = selectElement(id);
                if(ele) {
                    var newMl = Number($(this).attr('ml')) * scaleW; 
                    newMl = Math.round(newMl); 
                    ele.ml = newMl;  
                    $(this).css('left', newMl + 'px');
                    $(this).addClass('position-absolute');
                } 
            } 
        });
    
        $(card).find('[w]').each(function (value, key) {
            if ($(this).attr('w') === 'auto') {
                $(this).css('width', 'auto');
            }
            else {
                var w = Number($(this).attr('w'));
                var id = $(this).attr('id') ;
                if(id){
                    var ele = selectElement(id);
                    if(ele) {
                        var newW =  Number($(this).attr('w')) * scaleW; 
                        newW = Math.round(newW);
                        ele.w = newW;
                        $(this).addClass('position-absolute');
                        $(this).css('width', newW + 'px');
                    }
                }
                
            }
        });
    
        $(card).find('[h]').each(function (value, key) {
            if ($(this).attr('h') === 'auto') {
                $(this).css('height', 'auto');
            }
            else {
                var ele = selectElement($(this).attr('id'));
                if(ele) { 
                    var newH = Number($(this).attr('h')) * scaleW;
                    newH = Math.round(newH);  
                    ele.h = newH; 
                    $(this).addClass('position-absolute');
                    $(this).css('height', newH + 'px');
                }
            }
        });
    }

    $(window).resize(function () {
        $timeout(function () {
            $scope.resizeWidth();
        }, 10);
    });

    $scope.onChangeHeight = function () {
        var card = $('.background-page-card');
        var minH = Number(card.css('min-height').replace('px', ''));
        if ($scope.container.h < minH) {
            $scope.container.h = minH;
            return;
        }

        card.css('height', $scope.container.h);
        var background = $('.background-sequence-image');
        background.css('width', $scope.container.w);
        background.css('height', $scope.container.h);

        $scope.pageSelected.container = $scope.container;
        $scope.applyChange = true;
    }

    $scope.toggleSideMenu = function () {
        if ($('#sidemenu-page-button').hasClass('fa-caret-square-left')) {
            hiddenSideMenu();
        }
        else if ($('#sidemenu-page-button').hasClass('fa-caret-square-right')) {
            showSideMenu();
        }
        $scope.resizeWidth();
    };

    function refreshElements(elements) {
         
        var newElements = [];
        if(elements)
        for (var i = 0; i < elements.length; i++) {
            elements[i].selected = false;
            newElements.push(Object.assign({}, elements[i]));
        } 
        $scope.pageSelected.elements = $scope.pageSelected.elements || [];
        var length = $scope.pageSelected.elements.length;
        for(var i=0;i<length; i++) {
            $scope.pageSelected.elements.pop();
        }
        
        $timeout(function () {
            for (var i = 0; i < newElements.length; i++) {
                newElements[i].id = getId_forElement();
                $scope.pageSelected.elements.push(newElements[i]);
            } 
            $timeout(function () {
                $scope.resizeWidth();
            }, 10);
        }, 10);
        
    }

    function InitializeJstree() {

        $('#jstree').on('select_node.jstree', function (evt, data) {

            if ($scope.applyChange) {
                $scope.openChangeAlert();
                return;
            }
            
            $scope.dataJstree = JSON.parse($('#' + data.selected).attr('data-jstree'));
            
            switch ($scope.dataJstree.type) {
                case 'openAllSequence':
                    location="/conexiones/admin/sequences_list";
                    break; 
                default: 
                    $scope.pageSelected = $scope.pages[$scope.dataJstree.pageIndex];
                    $scope.pageSelected.index = $scope.dataJstree.pageIndex;
                    var src = JSON.parse($scope.pageSelected.src);
                    $scope.pageSelected.elements = src.elements || [];
                    break;
            }
            $scope.$apply();

        }).jstree({
            "core": {
                "multiple": false,
                "animation": 0
            }
        });

    }

    $scope.init = function() {
        loadPages();
    }
    
    function loadFolderImage(parentElement,elementId,path) {
        $scope.onChangeFolderImage(path,function(data){
            var images_str = '';
            var file = null;
            for(var index in data.scanned_directory) {
                file = data.scanned_directory[index];
                if(file != '..' && file.includes('.') ) {
                    if(images_str.length>0) images_str += '|';
                    images_str += data.directory + '/' + file;
                }
            }
            images_str = images_str.replace('//','/');
            parentElement[elementId + 'ScannedDirectory'] = images_str;
        });
    }

    function loadPages() {

        $http.get('/conexiones/admin/get_pages')
            .then(function (response) {

                $scope.pages = response.data.pages;
                $scope.pageSelected = $scope.pages[0];
                $scope.pageSelected.index = 0;
                var src = JSON.parse($scope.pageSelected.src);
                $scope.pageSelected.elements = src.elements || [];
                $scope.showCopyButton = $scope.showDeleteButton = false;
                $timeout(function () {
                    InitializeJstree();
                }, 10);
            }).catch(function(err){
                swal('Conexiones','Error consultando secuencia: ' + err,'error');
            });
    };

    $scope.onClickElement = function (parent, element, title, type) {
        if ($scope.mbDelete || $scope.mbDraggable) {
            $scope.mbDelete = false;
            $scope.mbDraggable = false;
            return;
        }
        $scope.typeEdit = type;
        $scope.pageSelected = parent;
        $scope.elementEdit = element;
        $scope.mbImageShow = false;

        if ($scope.typeEdit === 'image-element' || $scope.typeEdit === 'video-element') {
            element.bindWidthHeight = element.bindWidthHeight || true;
            $scope.bindWidthHeight = element.bindWidthHeight;
            $scope.widthOriginal = element.w;
            $scope.heightOriginal = element.h;
        }
        else {
            $scope.bindWidthHeight = false;
        }

        $scope.titleEdit = title;

        if($scope.typeEdit === 'date') {
            var dateControl = document.querySelector('#typeEditDateInput');
            dateControl.value = parent[element];
        }
        else if ($scope.typeEdit === 'img') {
            var dir = $scope.pageSelected[$scope.elementEdit] || '/';
            dir = getLastPath(dir);
            $scope.onChangeFolderImage(dir);
        }
        else if ($scope.typeEdit === 'image-element') {
            var dir = $scope.elementEdit.url_image || 'images/sequences/sequence' + $scope.sequence.id + '/.';
            dir = getLastPath(dir);
            $scope.onChangeFolderImage(dir);
        }
        else if ($scope.typeEdit === 'evidence-element') {
            $scope.applyChangeEvidence = true;
        }
        else if ($scope.typeEdit === 'slide-images') {
            if($scope.pageSelected[$scope.elementEdit] && $scope.pageSelected[$scope.elementEdit].length > 0) {
                var dir = $scope.pageSelected[$scope.elementEdit];
                
                $scope.onChangeFolderImage(dir,function(data){
                    var images_str = '';
                    var file = null;
                    $scope.mbImageShow = true;
                    for(var index in data.scanned_directory) {
                        file = data.scanned_directory[index];
                        if(file != '..' && file.includes('.') ) {
                            if(images_str.length>0) images_str += '|';
                            images_str += data.directory + '/' + file;
                        }
                    }
                    images_str = images_str.replace('//','/');
                    //$scope.pageSelected[$scope.elementEdit] = images_str;
                });
            }
            else {
                $scope.onChangeFolderImage('');
            }
        }
    }

    $scope.onClickElementWithDelete = function (parent, element, $index) {
        $scope.indexElement = $index;
        
        var title = (element.type === 'text-element') ? 'Texto' :
            (element.type === 'text-area-element') ? 'Párrafo' :
                (element.type === 'image-element') ? 'Imágen' :
                    (element.type === 'video-element') ? 'Video' :
                        (element.type === 'button-element') ? 'Botón' : ''
        $scope.onClickElement(parent, element, title, element.type);
    }
    
    $scope.onCopyElements = function(indexElement) {
        
        var elementsSelected =  typeof indexElement !== 'undefined' ? 
            [$scope.pageSelected.elements[indexElement]] :
            $scope.pageSelected.elements.filter(function(value){
                return value.selected;
            });
        
        $scope.copyCache  = [];
        
        for(var i=0, copyElement= null; i< elementsSelected.length; i++) {
            copyElement = elementsSelected[i];
            $scope.copyCache.push(Object.assign({},copyElement));
        }
        
        if(typeof indexElement !== 'undefined' ) { 
            $scope.typeEdit='';
            for(var i=0, elem= null; i< $scope.pageSelected.elements.length; i++) {
                elem = $scope.pageSelected.elements[i];
                elem.selected = false;
            }
        }
        
        $scope.showCopyButton = $scope.showDeleteButton = false;
    }

    $scope.onDeleteSelectedElements = function() {
        
        var list = $scope.pageSelected.elements.filter(function(value){
            return !value.selected;
        }) 
        refreshElements(list);
        
        $scope.copyCache  = null;
        $scope.showCopyButton = $scope.showDeleteButton = false;
        $scope.applyChange = true;
    }

    function getId_forElement() {
        var id = null;
        do{
            next = false;
            id = Number(moment().format('YYYYMMDDHHmmssSSS'));
            for(var i=0, elem= null; i< $scope.pageSelected.elements.length; i++) {
                elem = $scope.pageSelected.elements[i];
                if(elem.id === id) {
                    next = true;
                    break;
                }
            }
        }
        while(next);
        return id;
    }

    $scope.onPasteElements = function() {
       
        for(var i=0, copyElement= null; i< $scope.copyCache.length; i++) {
            copyElement = $scope.copyCache[i];
            copyElement.id = getId_forElement();
            for(var j=0, elm; j<$scope.pageSelected.elements.length; j++) {
                elm = $scope.pageSelected.elements[j];
                if(copyElement.ml === elm.ml && copyElement.mt == elm.mt    ) {
                    copyElement.ml += 20;
                    copyElement.mt += 20;
                 }
            }
            
            $scope.pageSelected.elements.push(copyElement);
            if(copyElement.type === "evidence-element" ) {
               $scope.applyChangeEvidence = true;
            }
        }
        
        for(var i=0, elem= null; i< $scope.pageSelected.elements.length; i++) {
            elem = $scope.pageSelected.elements[i];
            elem.selected = false;
        }
        
        $scope.showCopyButton = $scope.showDeleteButton = false;
        
        $scope.copyCache = null;
        $scope.applyChange = true;

        $timeout(function () {
            $scope.resizeWidth();
        }, 10);
    }

    $scope.onChangeElementSelected = function() {
        var elementsSelected = 
            $scope.pageSelected.elements.filter(function(value){
                return value.selected;
            });
            
        $scope.showCopyButton = elementsSelected && elementsSelected.length > 0;   
        $scope.showDeleteButton = elementsSelected && elementsSelected.length > 0;     
    }
    
    $scope.changeFormatDate = function (pageSelected, elementEdit, format) {
        try {
            $scope.pageSelected[elementEdit] = moment($scope.pageSelected[elementEdit], "YYYY-MM-DD").format(format);
            $scope.applyChange = true;
            $scope.pageSelected.isDateChange = true;
        } catch (e) { console.log(e);}
    }
    
    $scope.clearChangeFormatDate = function ( ) {
        $scope.pageSelected[$scope.elementEdit] = null;
        $scope.applyChange = true;
        var dateControl = document.querySelector('#typeEditDateInput');
        dateControl.value = '';
        $scope.pageSelected.isDateChange = true;
    }

    $scope.onImgChange = function (field) {
        $scope.applyChange = true;

        if (typeof $scope.elementEdit === 'object') {
            var image = new Image();
            var refSplit = window.location.href.split('/');
            //image.src = refSplit[0] + '//' + refSplit[2] + '/' + field.url_image;
            image.src = '/'+field.url_image;
            image.onload = function () {
                $scope.elementEdit.url_image = field.url_image;
                if(this.width > 500) {
                    this.height = 500 * this.height  / this.width ;
                    this.width = 500;
                }
  
    

                $scope.elementEdit.w = this.width;
                $scope.elementEdit.h = this.height;
                $scope.widthOriginal = $scope.elementEdit.w;
                $scope.heightOriginal = $scope.elementEdit.h;
                $scope.bindWidthHeight = true;
                $scope.elementEdit.bindWidthHeight = $scope.bindWidthHeight;
                $scope.mbImageShow = false;
                $scope.$apply();
            };
        }
        else {
            if ($scope.dataJstree.type === 'openSequenceSectionPart') {
                $scope.pageSelected[$scope.elementEdit] = field.url_image;
            }
            else {
                $scope.pageSelected[$scope.elementEdit] = field.url_image;
            }

        }

        $timeout(function () {
            $scope.resizeWidth();
        }, 10);
    }
    
    $scope.onChangeFolderSlideImage = function (path,callback) {
        $scope.onChangeFolderImage(path,function(data){
            var images_str = '';
            var file = null;
            for(var index in data.scanned_directory) {
                file = data.scanned_directory[index];
                if(file != '..' && file.includes('.') ) {
                    if(images_str.length>0) images_str += '|';
                    images_str += data.directory + '/' + file;
                }
            }
            images_str = images_str.replace('//','/');
            $scope.pageSelected[$scope.elementEdit] = path;
            $scope.pageSelected[$scope.elementEdit + 'ScannedDirectory'] = images_str;
            $scope.applyChange = true;
        });
    }

    $scope.onChangeFolderImage = function (path,callback) {
        $http.post('/conexiones/admin/get_folder_image', { 'dir': path }).then(function (response) {
            var list = response.data.scanned_directory;
            $scope.directoryPath = response.data.directory;
            $scope.directory = [];
            $scope.filesImages = [];
            var item = null;
            for (indx in list) {
                item = list[indx];
                if (item.includes('.png') || item.includes('.jpg') || item.includes('.jpeg')) {
                    var filedir = $scope.directoryPath + '/' + item;
                    $scope.filesImages.push({ 'type': 'img', 'url_image': filedir });
                }
                else if (!item.includes('.')) {
                    var dir = $scope.directoryPath + '/'+ item;
                    dir = dir.replace('//','/');
                    $scope.directory.push({ 'type': 'dir', 'name': item, 'dir': dir });
                }
                else if (item === '..') {
                    var dir = getLastPath($scope.directoryPath);
                    $scope.directory.push({ 'type': 'dir', 'name': item, 'dir': dir });
                }
            }
            if(callback) callback(response.data);
        },function(e){
            var message = 'Error consultando el directorio';
            if(e.message) {
                message += e.message;
            }
            $scope.errorMessage = angular.toJson(message);
            $scope.directoryPath = null;
        });
    }

    $scope.onNewElement = function (typeItem) {
        $scope.applyChange = true;
        var newElement = null;
        var id = getId_forElement();

        if (typeItem === 'text-element') {
            newElement = { 'id': id, 'type': typeItem, 'fs': 11, 'ml': 10, 'mt': 76, 'w': 100, 'h': 26, 'text': '--texto de guía--' };
        }
        else if (typeItem === 'text-area-element') {
            newElement = { 'id': id, 'type': typeItem, 'fs': 11, 'ml': 100, 'mt': 76, 'w': 100, 'h': 100, 'text': '--Parrafo 1--' };
        }
        else if (typeItem === 'image-element') {
            newElement = { 'id': id, 'type': typeItem, 'url_image': 'images/icons/NoImageAvailable.jpeg', 'w': 135, 'h': 115, 'ml': 150, 'mt': 76 };
        }
        else if (typeItem === 'video-element') {
            newElement = { 'id': id, 'type': typeItem, 'url_vimeo': 'https://player.vimeo.com/video/286898202', 'w': 210, 'h': 151, 'ml': 260, 'mt': 170 };
        }
        else if (typeItem === 'button-element') {
            newElement = { 'id': id, 'type': typeItem, 'fs': 11, 'ml': 210, 'mt': 176, 'w': 130, 'h': 50, 'text': '--texto de guía--', 'class': 'btn-sm btn-primary' };
        }
        else if (typeItem === 'evidence-element') {
            newElement = { 'id': id, 'type': typeItem, 'questionEditType': "1",'fs': 11, 'ml': 210, 'mt': 176, 'w': 277, 'h': 58, 'text': 'Abrir evidencias de aprendizaje', 'class': '', 'subtitle':'Evidencias de aprendizaje','icon': 'images/designerAdmin/icons/evidenciasAprendizajeIcono.png', 'questions': [] };
        }

        for(var j=0, elm; j<$scope.pageSelected.elements.length; j++) {
            elm = $scope.pageSelected.elements[j];
            if(newElement.ml === elm.ml && newElement.mt == elm.mt    ) {
                newElement.ml += 20;
                newElement.mt += 20;
             }
        }
        $scope.pageSelected.elements.push(newElement);
        
        $timeout(function () {
            $scope.resizeWidth();
        }, 10);

    }

    $scope.onDeleteElement = function (parentElement, $index, mbDelete) {
        if ($index || $index === 0) {
            $scope.mbDelete = mbDelete;
            $scope.elementEdit = null;
            $scope.indexElement = null;
            $scope.typeEdit = '';
            $scope.applyChange = true;
            var list = $scope.pageSelected.elements.filter(function(value,index){
                return  (index !== $index)
            });
            refreshElements(list);
        }
        else {
            if (parentElement.background_image) {
                $scope.deleteBackgroundSection();
            }
        }
    }

    function getLastPath(directory) {
        var dirSplit = directory.split('/');
        var dirName = '';
        for (var i = 0; i < dirSplit.length - 1; i++) {
            if (dirName.length > 0) dirName += '/';
            dirName += dirSplit[i];
        }
        return dirName;
    }

    $scope.openChangeAlert = function () {
        swal({
            text: "Se deben guardar cambios para continuar!",
            showCancelButton: true,
            confirmButtonColor: '#748194',
            confirmButtonClass: 'mr-4',
            cancelButtonColor: '#2c7be5',
            confirmButtonText: "Deshacer cambios",
            cancelButtonText: "Ok", 
        })
        .then((result) => {
            if (result) {
                swal({
                  text: "Confirma para deshacer los cambios!",
                  type: "warning",
                  showCancelButton: true,
                  confirmButtonClass: "btn-danger",
                  confirmButtonText: "Confirmar",
                  cancelButtonText: "Cancelar",
                })
                .then((isConfirm) => {
                    if (isConfirm) {
                        $scope.applyChange = false;
                        loadSequence($scope.sequence.id);
                    }
                }).catch(swal.noop);
            }
        }).catch(swal.noop);
    }

}]);

MyApp.directive('conxDraggable', function () {
    return {
        controller: function ($scope, $timeout) {
            $timeout(function () {
                var $element = $('#' + $scope.element.id);
                if($scope.element.type === 'video-element') {
                    $element = $('#' + $scope.element.id).parent().find('span');
                }

                $element.draggable({

                    start: function (event, ui) {
                        $scope.startEvent = event;
                        $scope.position = ui.position;
                        $scope.$parent.mbDraggable = true;
                    },
                    stop: function (event, ui) {
                        $scope.$parent.applyChange = true;

                        var deltaY = event.clientY - $scope.startEvent.clientY;
                        var deltaX = event.clientX - $scope.startEvent.clientX;

                        $scope.element.ml = $scope.element.ml + deltaX;
                        $scope.element.mt = $scope.element.mt + deltaY;
                        $scope.$apply();
                            switch ($scope.element.type) {
                                case 'image-element':
                                case 'button-element':
                                case 'video-element':
                                case 'evidence-element':
                                    $element.parent().css('top', $scope.element.mt + 'px');
                                    $element.parent().css('left', $scope.element.ml + 'px');
                                    $element.css('top', '0px');
                                    $element.css('left', '0px');
                                    break;
                            }
                    }
                });
            }, 10);
        }
    };
});

MyApp.directive('conxTextList', function () {
    return {
        restrict: 'E',
        template: '<div ng-show="pageSelected" ng-repeat="split in pageSelected[elementEdit].split(\'|\') track by $index"> ' +
            '<span ng-show="showIndexLetter">{{letters[$index]}}).</span><input ng-change="onChangeSplit($index,split)" ng-model="split" class="mt-1 fs--1 w-75"/>  ' +
            '<a ng-click="delete($index)" style="marging-top: 8px:;"><i class="far fa-times-circle"></i><a/> </div> ' +
            '<input class="mt-1 w-75 fs--1" type="text" ng-model="newSplit"/>' +
            '<a class="cursor-pointer" ng-click="onNewSplit()"> ' +
            '<i class="fas fa-plus"></i><a/>',
        controller: function ($scope, $timeout) {
            
            $scope.delete = function ($index) {

                $scope.applyChange = true;

                var list = $scope.pageSelected[$scope.elementEdit].split('|');
                var newList = '';
                for (var i = 0; i < list.length; i++) {
                    if (i != $index) {
                        if (newList.length > 0) {
                            newList = newList + '|';
                        }
                        newList = newList + list[i];
                    }
                }
                $scope.pageSelected[$scope.elementEdit] = newList;
            }
            $scope.onChangeSplit = function ($index, split) {
                $scope.applyChange = true;
                var list = $scope.pageSelected[$scope.elementEdit].split('|');
                var newList = '';
                for (var i = 0; i < list.length; i++) {
                    if (newList.length > 0) {
                        newList = newList + '|';
                    }
                    if (i != $index) {
                        newList = newList + list[i];
                    }
                    else {
                        newList = newList + split;
                    }
                }
                $scope.pageSelected[$scope.elementEdit] = newList;
            }
            $scope.onNewSplit = function () {
                $scope.applyChange = true;
                $scope.pageSelected[$scope.elementEdit] = $scope.pageSelected[$scope.elementEdit] || '';
                if ($scope.newSplit && $scope.newSplit.length > 0) {
                    if ($scope.pageSelected[$scope.elementEdit].length > 0) {
                        $scope.pageSelected[$scope.elementEdit] += '|';
                    }
                    $scope.pageSelected[$scope.elementEdit] += $scope.newSplit;
                }
                $scope.newSplit = '';
            }
            $scope.onChangeInput = function () {
                $scope.applyChange = true;
                if ($scope.dataJstree.type === 'openSequenceSectionPart') {
                    $scope.sequence[$scope.sequenceSectionIndex] = angular.toJson($scope.sequenceSection);
                }
                $timeout(function () {
                    $scope.resizeWidth();
                }, 10);
            }
            $scope.onChangeWidthHeight = function (elementEdit, type) {
                if ($scope.bindWidthHeight) {
                    if (type === 'w') {
                        var deltaW = elementEdit.w - $scope.widthOriginal;
                        var deltaH = deltaW * $scope.heightOriginal / $scope.widthOriginal;
                        elementEdit.h += deltaH;
                        elementEdit.h = Math.round(elementEdit.h);
                    }
                    else if (type === 'h') {
                        var deltaH = elementEdit.h - $scope.heightOriginal;
                        var deltaW = deltaH * $scope.widthOriginal / $scope.heightOriginal;
                        elementEdit.w += deltaW;
                        elementEdit.w = Math.round(elementEdit.w);
                    }
                }
                $scope.widthOriginal = elementEdit.w;
                $scope.heightOriginal = elementEdit.h;

                $scope.applyChange = true;

                $timeout(function () {
                    $scope.resizeWidth();
                }, 10);

            }
        }
    };
});

MyApp.directive('conxSlideImages', function () {
    return {
        restrict: 'E',
        /*template: '<div ng-show="pageSelected && pageSelected[elementEdit].length > 0" ng-repeat="split in pageSelected[elementEdit].split(\'|\') track by $index"> ' +
            '<input ng-change="onChangeSplit($index,split)" ng-model="split" class="mt-1 fs--1 w-90"/>  ' +
            '<a ng-click="delete($index)" style="marging-top: 8px:;"><i class="far fa-times-circle"></i><a/> </div> ' +
            '<input class="mt-1 w-90 fs--1" type="text" ng-model="newSplit"/> <a href="#" class="cursor-pointer" ng-click="onNewSplit()"> <i class="fas fa-plus"></i><a/>',
            */
        template: '',        
        controller: function ($scope, $timeout) {
            $scope.delete = function ($index) {

                $scope.applyChange = true;

                var list = $scope.pageSelected[$scope.elementEdit].split('|');
                var newList = '';
                for (var i = 0; i < list.length; i++) {
                    if (i != $index) {
                        if (newList.length > 0) {
                            newList = newList + '|';
                        }
                        newList = newList + list[i];
                    }
                }
                $scope.pageSelected[$scope.elementEdit] = newList;
            }
            $scope.onChangeSplit = function ($index, split) {
                $scope.applyChange = true;
                var list = $scope.pageSelected[$scope.elementEdit].split('|');
                var newList = '';
                for (var i = 0; i < list.length; i++) {
                    if (newList.length > 0) {
                        newList = newList + '|';
                    }
                    if (i != $index) {
                        newList = newList + list[i];
                    }
                    else {
                        newList = newList + split;
                    }
                }
                $scope.pageSelected[$scope.elementEdit] = newList;
            }
            $scope.onNewSplit = function () {
                $scope.applyChange = true;
                $scope.pageSelected[$scope.elementEdit] = $scope.pageSelected[$scope.elementEdit] || '';
                if ($scope.newSplit && $scope.newSplit.length > 0) {
                    if ($scope.pageSelected[$scope.elementEdit].length > 0) {
                        $scope.pageSelected[$scope.elementEdit] += '|';
                    }
                    $scope.pageSelected[$scope.elementEdit] += $scope.newSplit;
                }
                $scope.newSplit = '';
            }
            $scope.onChangeInput = function () {
                $scope.applyChange = true;
                if ($scope.dataJstree.type === 'openSequenceSectionPart') {
                    $scope.sequence[$scope.sequenceSectionIndex] = angular.toJson($scope.sequenceSection);
                }
                $timeout(function () {
                    $scope.resizeWidth();
                }, 10);
            }
            $scope.onChangeWidthHeight = function (elementEdit, type) {
                if ($scope.bindWidthHeight) {
                    if (type === 'w') {
                        var deltaW = elementEdit.w - $scope.widthOriginal;
                        var deltaH = Math.round(deltaW * $scope.heightOriginal / $scope.widthOriginal);
                        elementEdit.h += deltaH;
                    }
                    else if (type === 'h') {
                        var deltaH = elementEdit.h - $scope.heightOriginal;
                        var deltaW = Math.round(deltaH * $scope.widthOriginal / $scope.heightOriginal);
                        elementEdit.w += deltaW;
                    }
                }
                $scope.widthOriginal = elementEdit.w;
                $scope.heightOriginal = elementEdit.h;

                $scope.applyChange = true;

                $timeout(function () {
                    $scope.resizeWidth();
                }, 10);

            }
        }
    };
});

//JASCRIPT JQUERY METHODS
//TOOGLE MENU
var hiddenSideMenu = function () {
    $('#sidemenu-sequences-button').removeClass('fa-caret-square-left');
    $('#sidemenu-sequences-button').addClass('fa-caret-square-right');
    $('#sidemenu-sequences-empty').addClass('show');
    $('#sidemenu-sequences-empty').removeClass('d-none');
    $('#sidemenu-sequences-content').addClass('d-lg-none');
  //  $('#sidemenu-sequences-content').removeClass("show"); 
    //$('#sidemenu-tools-content').removeClass("show");
    $('#sidemenu-tools-content').addClass("d-lg-none");
    $('#sidemenu-sequences').addClass("col-lg-0_5");
    $('#sidemenu-sequences').removeClass("col-lg-3");
    $('#content-section-sequences').removeClass("col-lg-9");
    $('#content-section-sequences').addClass("col-lg-11_5");
};

var showSideMenu = function () {
    $('#sidemenu-sequences-empty').removeClass('show');
    $('#sidemenu-sequences-empty').addClass('d-none');

    $('#sidemenu-sequences-content').removeClass('d-lg-none');
    $('#sidemenu-sequences-content').addClass("show"); 

    $('#sidemenu-tools-content').removeClass('d-lg-none');
    $('#sidemenu-tools-content').addClass("show"); 

    $('#sidemenu-sequences-button').addClass('fa-caret-square-left');
    $('#sidemenu-sequences-button').removeClass('fa-caret-square-right');

    $('#sidemenu-sequences-hidden-side').removeClass("d-none");
    $('#sidemenu-sequences-content').removeClass("d-none");
    $('#sidemenu-sequences-empty').addClass("d-none");

    $('#sidemenu-tools-content').addClass("show");
    $('#sidemenu-tools-content').removeClass("d-none");

    $('#sidemenu-sequences').removeClass("col-lg-0_5");
    $('#sidemenu-sequences').addClass("col-lg-3");

    $('#content-section-sequences').addClass("col-lg-9");
    $('#content-section-sequences').removeClass("col-lg-11_5");
}

function removeHashKey (appdata) {
    return JSON.stringify( appdata, function( key, value ) {
            if( key === "$$hashKey" ) {
                return undefined;
        }
        
        return value;
    });
}
 