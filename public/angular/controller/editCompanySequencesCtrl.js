MyApp.controller("editCompanySequencesCtrl", ["$scope", "$http", "$timeout", function ($scope, $http, $timeout) {

    $scope.errorMessage = null;
    $scope.sequence = null;
    $scope.sequenceSection = null;
    $scope.sequenceSectionPart = null;
    $scope.moment = null;
    $scope.momentSection = null;
    $scope.momentSectionPart = null;
    $scope.moments = [];
    $scope.momentSections = [];
    $scope.momentSectionParts = [];
    $scope.sectionsSequenceNames = [{ "id": 1, "name": "Situación Generadora" }, { "id": 2, "name": "Ruta de viaje" }, { "id": 3, "name": "Guía de saberes" }, { "id": 4, "name": "Punto de encuentro" }];
    $scope.sectionsMomentNames = [{ "type": 1, "name": "Pregunta Central" }, { "type": 2, "name": "Ciencia en contexto" }, { "type": 3, "name": "Experiencia científica" }, { "type": 4, "name": "+ Conexiones" }];
    $scope.PageName = '';

    $scope.dataJstree = {};
    $scope.elementParentEdit = null;
    $scope.typeEdit = null;
    $scope.container = {};
    $scope.applyChange = false;
    $scope.applyChangeEvidence = false;

    $scope.elementEdit = null;
    $scope.questionEdit = null;
    $scope.answerEdit = null;
    $scope.indexElement = null;

    $scope.directoryPath = null;
    $scope.widthOriginal = null;
    $scope.heightOriginal = null;
    $scope.mbDelete = null;
    $scope.showEvidenceModal = false;
	
	$scope.showCopyButton = false;
	$scope.copyCache = false;

    var card = $('.background-sequence-card');
    $scope.container.h = Math.round(Number(card.css('height').replace('px', '')));
    $scope.container.w = Math.round(card.css('width').replace('px', ''));


    $scope.resizeWidth = function () {
        var newW = Number(card.css('width').replace('px', ''));
        var deltaW = newW - $scope.container.w;
        var deltaH = Math.round((deltaW * $scope.container.h) / $scope.container.w);
        $scope.container.w = Math.round(card.css('width').replace('px', ''));
        $scope.container.h += deltaH;
        var background = $('.background-sequence-image');
        background.css('width', $scope.container.w);
        background.css('height', $scope.container.h);
        card.css('height', $scope.container.h);

        if ($scope.dataJstree.type === 'openSequenceSectionPart') {
            $scope.sequenceSectionPart.container = $scope.container;
        }
        else if ($scope.dataJstree.type === 'openMomentSectionPart') {
            $scope.momentSectionPart.container = $scope.container;
        }
    }

    $(window).resize(function () {
        $scope.resizeWidth();
    });

    $scope.resizeWidth();

    $scope.onChangeHeight = function () {

        var minH = Number(card.css('min-height').replace('px', ''));
        if ($scope.container.h < minH) {
            $scope.container.h = minH;
            return;
        }

        card.css('height', $scope.container.h);
        var background = $('.background-sequence-image');
        background.css('width', $scope.container.w);
        background.css('height', $scope.container.h);

        if ($scope.dataJstree.type === 'openSequenceSectionPart') {
            $scope.sequenceSectionPart.container = $scope.container;
        }
        else if ($scope.dataJstree.type === 'openMomentSectionPart') {
            $scope.momentSectionPart.container = $scope.container;
        }
        $scope.applyChange = true;
    }

    $scope.toggleSideMenu = function () {
        if ($('#sidemenu-sequences-button').hasClass('fa-caret-square-left')) {
            hiddenSideMenu();
        }
        else if ($('#sidemenu-sequences-button').hasClass('fa-caret-square-right')) {
            showSideMenu();
        }
        $scope.resizeSequenceCard();
        $scope.resizeWidth();
    };

    function findSectionSequenceEmpty(sequence) {
        var section = null;
        var list = Object.assign([], $scope.sectionsSequenceNames);

        for (var i = 0; i < 4; i++) {
            section = sequence['section_' + (i + 1)];
            if (section && JSON.parse(section).section && JSON.parse(section).section.id) {
                for (var j = 0; j < list.length; j++) {
                    if (list[j].id === JSON.parse(section).section.id) {
                        list.splice(j, 1);
                    }
                }
            }
        }
        return list[0];
    }

    function findMoment(order) {
        var moment = null;
        for (var i = 0; i < $scope.moments.length; i++) {
            if (Number($scope.moments[i].order) === Number(order)) {
                return $scope.moments[i];
            }
        }
        return moment;
    }

    function findSectionMomentEmpty(moment) {
        var section = null;
        var list = Object.assign([], $scope.sectionsMomentNames);

        for (var i = 0; i < 4; i++) {
            section = moment['section_' + (i + 1)];
            if (section && JSON.parse(section).section && JSON.parse(section).section.type) {
                for (var j = 0; j < list.length; j++) {
                    if (list[j].type === JSON.parse(section).section.type) {
                        list.splice(j, 1);
                    }
                }

            }
        }
        return list[0];
    }

    function InitializeJstree() {

        $('#sidemenu-sequences-content').remove();
        $scope.typeEdit = $scope.indexElement = null;

        $newDiv = $('#sidemenu-sequences-content-temp').clone().prependTo('#sidemenu-sequences');
        $newDiv.addClass('d-lg-block');
        $newDiv.find('#jstree').attr('id', 'jstreetemp');
        $newDiv.attr('id', 'sidemenu-sequences-content');
        $('#jstreetemp').on('select_node.jstree', function (evt, data) {

            if ($scope.applyChange) {
                $scope.openChangeAlert();
                return;
            }

            $scope.dataJstree = JSON.parse($('#' + data.selected).attr('data-jstree'));
            $scope.sequenceSection = $scope.sequenceSectionPart = $scope.moment = $scope.momentSection = $scope.momentSectionPart = $scope.momentSections = $scope.momentSectionParts = null;


            switch ($scope.dataJstree.type) {
                case 'openAllSequence':
                    location="/conexiones/admin/sequences_list";
                    break; 
                case 'openSequence':
                    $scope.PageName = 'Guía de Aprendizaje';
                    $scope.elementParentEdit = $scope.sequence;
                    $('#sidemenu-sequences .overflow-auto').addClass('height_337').removeClass('height_235');
                    break;
                case 'openSequenceSection':
                case 'openSequenceSectionPart':
                    $scope.sequenceSection = JSON.parse($scope.sequence[$scope.dataJstree.sequenceSectionIndex]);
                    $scope.sequenceSection.sequenceSectionIndex = $scope.dataJstree.sequenceSectionIndex;
                    $scope.sequenceSection.sequenceSectionPartIndex = $scope.dataJstree.partId;
                    $scope.sequenceSection[$scope.sequenceSection.sequenceSectionPartIndex] = $scope.sequenceSection[$scope.sequenceSection.sequenceSectionPartIndex] || {};
                    $scope.sequenceSectionPart = $scope.sequenceSection[$scope.sequenceSection.sequenceSectionPartIndex];
                    $scope.PageName = $scope.sequenceSection.section.name;
                    $scope.elementParentEdit = $scope.sequenceSectionPart;
                    $scope.container = $scope.sequenceSectionPart.container || { "w": $scope.container.w, "h": 385 };
                    $('#sidemenu-sequences .overflow-auto').addClass('height_235').removeClass('height_337');

                    if ($scope.dataJstree.type === 'openSequenceSection') {
                        var section = $('#' + data.selected[0]);
                        data.instance.open_node(section);
                        data.instance.deselect_all(true);
                        data.instance.select_node($(section.find('.jstree-children li'))[0]);
                    }

                    $scope.resizeWidth();
                    break;
                case 'openMoment':
                    $scope.moment = findMoment($scope.dataJstree.momentIndex);
                    $scope.PageName = 'Momento ' + $scope.moment.order;
                    $scope.elementParentEdit = $scope.moment;
                    $('#sidemenu-sequences .overflow-auto').addClass('height_337').removeClass('height_235');
                    var section = $('#' + data.selected[0]);
                    data.instance.toggle_node(section);
                    break;
                case 'openSectionMoment':
                case 'openMomentSectionPart':
                    $scope.moment = findMoment($scope.dataJstree.momentIndex);
                    $scope.momentSection = $scope.moment.sections[Number($scope.dataJstree.momentSectionIndex)];

                    $scope.PageName = $scope.momentSection.section.name;

                    if ($scope.dataJstree.type === 'openSectionMoment') {
                        var section = $('#' + data.selected[0]);
                        data.instance.open_node(section);
                        data.instance.deselect_all(true);
                        data.instance.select_node($(section.find('.jstree-children li'))[0]);
                    }

                    $scope.dataJstree.type = 'openMomentSectionPart';

                    $scope.momentSectionPart = $scope.momentSection[$scope.dataJstree.momentSectionPartIndex];
                    if(!$scope.momentSectionPart) $scope.momentSectionPart = $scope.momentSection[$scope.dataJstree.momentSectionPartIndex] = {};
                    $scope.momentSectionPart.momentSectionPartIndex = $scope.dataJstree.momentSectionPartIndex;
                    $scope.elementParentEdit = $scope.momentSectionPart;

                    $scope.container = $scope.momentSectionPart.container || { "w": $scope.container.w, "h": 385 };
                    $scope.resizeWidth();

                    $('#sidemenu-sequences .overflow-auto').addClass('height_235').removeClass('height_337');
                    break;
            }

            $scope.$apply();
            $scope.resizeSequenceCard();
        }).jstree({
            "core": {
                "multiple": false,
                "animation": 0
            }
        });

    }

    $scope.initSequence = function (sequence_id) {
        loadSequence(sequence_id);
        $scope.PageName = 'Secuencia';
        $scope.dataJstree.type = 'openSequence';
        $scope.elementParentEdit = $scope.sequence;
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

    function loadSequence(sequence_id) {

        $http.get('/get_sequence/' + sequence_id)
            .then(function (response) {

                $scope.sequence = response.data[0];
                
                loadFolderImage($scope.sequence,'mesh',$scope.sequence.mesh);
                loadFolderImage($scope.sequence,'url_slider_images',$scope.sequence.url_slider_images);
                
                
                $scope.applyChangeEvidence = false;
                if (!$scope.sequence['section_1']) $scope.sequence['section_1'] = angular.toJson({ "section": findSectionSequenceEmpty($scope.sequence) });
                if (!$scope.sequence['section_2']) $scope.sequence['section_2'] = angular.toJson({ "section": findSectionSequenceEmpty($scope.sequence) });
                if (!$scope.sequence['section_3']) $scope.sequence['section_3'] = angular.toJson({ "section": findSectionSequenceEmpty($scope.sequence) });
                if (!$scope.sequence['section_4']) $scope.sequence['section_4'] = angular.toJson({ "section": findSectionSequenceEmpty($scope.sequence) });
                if (!$scope.sequence['section_5']) $scope.sequence['section_5'] = angular.toJson({ "section": findSectionSequenceEmpty($scope.sequence) });

                $scope.moments = $scope.sequence.moments;
                var moment = section1 = section2 = section3 = section4 = null;
                var partsDefault = { 'part_1': {}, 'part_2': {}, 'part_3': {}, 'part_4': {} , 'part_5': {}};
                for (var i = 0; i < $scope.moments.length; i++) {
                    moment = $scope.moments[i];
                    section1 = JSON.parse(moment['section_1']);
                    if (!section1) {
                        section1 = Object.assign({ "section": findSectionMomentEmpty(moment) }, Object.assign({}, partsDefault));
                        moment.section_1 = angular.toJson(section1);
                    }
                    section1 = Object.assign(section1, { 'momentSectionIndex': '0' });

                    section2 = JSON.parse(moment['section_2']);
                    if (!section2) {
                        section2 = Object.assign({ "section": findSectionMomentEmpty(moment) }, Object.assign({}, partsDefault));
                        moment.section_2 = angular.toJson(section2);
                    }
                    section2 = Object.assign(section2, { 'momentSectionIndex': '1' });

                    section3 = JSON.parse(moment['section_3']);
                    if (!section3) {
                        section3 = Object.assign({ "section": findSectionMomentEmpty(moment) }, Object.assign({}, partsDefault));
                        moment.section_3 = angular.toJson(section3);
                    }
                    section3 = Object.assign(section3, { 'momentSectionIndex': '2' });

                    section4 = JSON.parse(moment['section_4']);
                    if (!section4) {
                        section4 = Object.assign({ "section": findSectionMomentEmpty(moment) }, Object.assign({}, partsDefault));
                        moment.section_4 = angular.toJson(section4);
                    }
                    section4 = Object.assign(section4, { 'momentSectionIndex': '3' });
                    moment.sections = [section1, section2, section3, section4];
                }
                
                switch ($scope.dataJstree.type) {
                    case 'openSequence':
                        $scope.PageName = 'Guía de Aprendizaje';
                        $scope.elementParentEdit = $scope.sequence;
                        break;
                    case 'openSequenceSectionPart':
                        $scope.sequenceSection = JSON.parse($scope.sequence[$scope.sequenceSection.sequenceSectionIndex]);
                        $scope.sequenceSectionPart = $scope.sequenceSection[$scope.sequenceSection.sequenceSectionPartIndex];
                        $scope.PageName = $scope.sequenceSection.section.name;
                        $scope.elementParentEdit = $scope.sequenceSectionPart;
                        $scope.container = $scope.sequenceSectionPart.container || { "w": $scope.container.w, "h": 385 };
                        $scope.resizeWidth();
                        break;
                    case 'openMoment':
                        $scope.moment = findMoment($scope.moment.order);
                        $scope.PageName = 'Momento ' + $scope.moment.order;
                        $scope.elementParentEdit = $scope.moment;
                        break;
                    case 'openMomentSectionPart':
                        $scope.moment = findMoment($scope.moment.order);
                        $scope.momentSection = $scope.moment.sections[Number($scope.momentSection.momentSectionIndex)];
                        $scope.PageName = $scope.momentSection.section.name;
                        $scope.momentSectionPart = $scope.momentSection[$scope.momentSectionPart.momentSectionPartIndex];
                        $scope.elementParentEdit = $scope.momentSectionPart;
                        $scope.container = $scope.momentSectionPart.container || { "w": $scope.container.w, "h": 385 };
                        $scope.resizeWidth();
                        break;
                }

                $timeout(function () {
                    InitializeJstree();
                    $scope.resizeSequenceCard();
                }, 10);
            }).catch(function(err){
                swal('Conexiones','Error consultando secuencia: ' + err,'error');
            });
    };

    $scope.deleteBackgroundSection = function () {

        if ($scope.dataJstree.type === 'openSequenceSectionPart') {
            $scope.sequenceSectionPart.background_image = '';
        }
        else if ($scope.dataJstree.type === 'openMomentSectionPart') {
            $scope.momentSectionPart.background_image = '';
        }

        $scope.applyChange = true;
    }

    $scope.onClickElement = function (parent, element, title, type) {
        if ($scope.mbDelete || $scope.mbDraggable) {
            $scope.mbDelete = false;
            $scope.mbDraggable = false;
            return;
        }
        $scope.typeEdit = type;
        $scope.elementParentEdit = parent;
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
            var dir = $scope.elementParentEdit[$scope.elementEdit] || '/';
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
            if($scope.elementParentEdit[$scope.elementEdit] && $scope.elementParentEdit[$scope.elementEdit].length > 0) {
                var dir = $scope.elementParentEdit[$scope.elementEdit];
                
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
                    //$scope.elementParentEdit[$scope.elementEdit] = images_str;
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
	
	$scope.copyElements = function(indexElement) {
		
		var elementsSelected = 
		typeof indexElement !== 'undefined' ? [$scope.elementParentEdit.elements[indexElement]] :
			$scope.elementParentEdit.elements.filter(function(value){
				return value.selected;
			});
		
		$scope.copyCache  = [];
		
		for(var i=0, copyElement= null; i< elementsSelected.length; i++) {
			copyElement = elementsSelected[i];
			$scope.copyCache.push(Object.assign({},copyElement));
		}
		
		if(typeof indexElement !== 'undefined' ) { 
			$scope.typeEdit='';
			for(var i=0, elem= null; i< $scope.elementParentEdit.elements.length; i++) {
				elem = $scope.elementParentEdit.elements[i];
				elem.selected = false;
			}
		}
		
		$scope.showCopyButton = false;
	}
	
	$scope.pasteElements = function() {
		
		for(var i=0, copyElement= null; i< $scope.copyCache.length; i++) {
			copyElement = $scope.copyCache[i];
			copyElement.id = moment().format('YYYYMMDDHHmmssSSS');
			for(var j=0, elm; j<$scope.elementParentEdit.elements.length; j++) {
				elm = $scope.elementParentEdit.elements[j];
				if(copyElement.ml === elm.ml && copyElement.mt == elm.mt	) {
					copyElement.ml += 20;
					copyElement.mt += 20;
 				}
			}
			
			$scope.elementParentEdit.elements.push(copyElement);
		}
		
		for(var i=0, elem= null; i< $scope.elementParentEdit.elements.length; i++) {
			elem = $scope.elementParentEdit.elements[i];
			elem.selected = false;
		}
		
		$scope.showCopyButton = false;
		
		$scope.copyCache = [];
		$timeout(function () {
            $scope.resizeSequenceCard();
        }, 10);

	}

	$scope.onChangeElementSelected = function() {
		var elementsSelected = 
			$scope.elementParentEdit.elements.filter(function(value){
				return value.selected;
			});
			
		$scope.showCopyButton = elementsSelected && elementsSelected.length > 0;	
	}
	
    $scope.changeFormatDate = function (elementParentEdit, elementEdit, format) {
        try {
            $scope.elementParentEdit[elementEdit] = moment($scope.elementParentEdit[elementEdit], "YYYY-MM-DD").format(format);
            $scope.applyChange = true;
            $scope.elementParentEdit.isDateChange = true;
        } catch (e) { console.log(e);}
    }
    
    $scope.clearChangeFormatDate = function ( ) {
        $scope.elementParentEdit[$scope.elementEdit] = null;
        $scope.applyChange = true;
        var dateControl = document.querySelector('#typeEditDateInput');
        dateControl.value = '';
        $scope.elementParentEdit.isDateChange = true;
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
                $scope.elementParentEdit[$scope.elementEdit] = field.url_image;
                $scope.sequence[$scope.sequenceSectionIndex] = angular.toJson($scope.elementParentEdit);
            }
            else {
                $scope.elementParentEdit[$scope.elementEdit] = field.url_image;
            }

        }

        $timeout(function () {
            $scope.resizeSequenceCard();
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
            $scope.elementParentEdit[$scope.elementEdit] = path;
            $scope.elementParentEdit[$scope.elementEdit + 'ScannedDirectory'] = images_str;
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

    $scope.newElement = function (typeItem) {
        $scope.applyChange = true;

        var id = moment().format('YYYYMMDDHHmmssSSS');

        var parentElement = $scope.dataJstree.type === 'openMomentSectionPart' ? $scope.momentSectionPart : $scope.elementParentEdit;
        if (typeItem === 'text-element') {
            parentElement.elements = parentElement.elements || [];
            parentElement.elements.push({ 'id': id, 'type': typeItem, 'fs': 11, 'ml': 10, 'mt': 76, 'w': 100, 'h': 26, 'text': '--texto de guía--' });
        }
        else if (typeItem === 'text-area-element') {
            parentElement.elements = parentElement.elements || [];
            parentElement.elements.push({ 'id': id, 'type': typeItem, 'fs': 11, 'ml': 100, 'mt': 76, 'w': 100, 'h': 100, 'text': '--Parrafo 1--' });
        }
        else if (typeItem === 'image-element') {
            parentElement.elements = parentElement.elements || [];
            parentElement.elements.push({ 'id': id, 'type': typeItem, 'url_image': 'images/icons/NoImageAvailable.jpeg', 'w': 135, 'h': 115, 'ml': 150, 'mt': 76 });
        }
        else if (typeItem === 'video-element') {
            parentElement.elements = parentElement.elements || [];
            parentElement.elements.push({ 'id': id, 'type': typeItem, 'url_vimeo': 'https://player.vimeo.com/video/286898202', 'w': 210, 'h': 151, 'ml': 260, 'mt': 170 });
        }
        else if (typeItem === 'button-element') {
            parentElement.elements = parentElement.elements || [];
            parentElement.elements.push({ 'id': id, 'type': typeItem, 'fs': 11, 'ml': 210, 'mt': 176, 'w': 130, 'h': 26, 'text': '--texto de guía--', 'class': 'btn-sm btn-primary' });
        }
        else if (typeItem === 'evidence-element') {
            parentElement.elements = parentElement.elements || [];
            parentElement.elements.push({ 'id': id, 'type': typeItem, 'questionEditType': "1",'fs': 11, 'ml': 210, 'mt': 176, 'w': 277, 'h': 58, 'text': 'Abrir evidencias de aprendizaje', 'class': '', 'subtitle':'Evidencias de aprendizaje','icon': 'images/designerAdmin/icons/evidenciasAprendizajeIcono.png', 'questions': [] });
        }
        
        $timeout(function () {
            $scope.resizeSequenceCard();
        }, 10);

    }

    $scope.deleteElement = function (parentElement, $index, mbDelete) {
        if ($index || $index === 0) {
            $scope.mbDelete = mbDelete;
            $scope.elementEdit = null;
            $scope.indexElement = null;
            $scope.typeEdit = '';
            $scope.applyChange = true;
            if (parentElement && parentElement.elements) {
                var newElements = [];
                for (var i = 0; i < parentElement.elements.length; i++) {
                    if (i !== $index)
                        newElements.push(Object.assign({}, parentElement.elements[i]));
                }
                //parentElement.elements = newElements;
            }
            parentElement.elements = [];
            parentElement.elements = newElements;
            //.splice($index,1);

            $timeout(function () {
                $scope.resizeSequenceCard();
                $scope.resizeWidth();
            }, 10);
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

    $scope.onSaveSequence = function () {
        $http.post('/update_sequence/', $scope.sequence)
            .then(function (response) {
                if (response && response.status === 200) {
                    $scope.applyChange = false;
                    swal('Conexiones', response.data.message, 'success');
                    loadSequence($scope.sequence.id);
                }
                else {
                    swal('Conexiones', 'Error al modificar la secuencia', 'danger');
                }
            }).catch(function(reason) {
                swal('Conexiones','Error al modificar la secuencia','danger');
            });
    }
    
    function saveEvidence(sectionPart,callback){
        var countElements = sectionPart.elements? sectionPart.elements.length : 0;
       
        var countElementsError = [];
        if($scope.applyChangeEvidence) {
            var data = { 
                "sequence_id": $scope.sequence.id,
                "moment_id":  $scope.moment ? $scope.moment.id : ''
            }
            $http.post('/remove_question/', data)
            .then(function (response) {
                sectionPart.elements = sectionPart.elements || [];
                var element = null;
				
                function refreshQuestion(question) {
					for(var i=0;i<sectionPart.elements.length;i++) {
						element = sectionPart.elements[i];
						if(element.type === 'evidence-element') {
							for(var j=0;j<element.questions.length;j++) {
								if(element.questions[j].id === question.id || element.questions[j].title === question.title) {
									element.questions[j].id = question.id;
								}
							}
                        }
					}
                }
				 
                for(var i=0;i<sectionPart.elements.length;i++) {
                    element = sectionPart.elements[i];
                    if(element.type === 'evidence-element') {
                        if(element.questions.length === 0) {
                            finishCallback();
                        }
                        else {
                            countElements--;
                            countElements += element.questions.length;
                            for(var j=0;j<element.questions.length;j++) {
                                var data = { 
                                    "id": element.questions[j].id,
                                    "title": element.questions[j].title,
                                    "sequence_id": $scope.sequence.id,
                                    "moment_id":  $scope.moment ? $scope.moment.id : '',
                                    "section":  Number($scope.momentSection.momentSectionIndex)+ 1, 
                                    "objective":  element.questions[j].objective,
                                    "concept":  element.questions[j].concept,
                                    "isHtml":  element.questions[j].isHtml,
                                    "order":   j + 1,
                                    "experience_id":  element.id,
                                    "options": removeHashKey(element.questions[j].options),
                                    "review": removeHashKey(element.questions[j].review),
                                    "type_answer": element.questionEditType
                                }
                                $http.post('/register_update_question/', data)
                                .then(function (response) {
                                    if (response && response.status === 200) {
                                        refreshQuestion(response.data.data);
                                        finishCallback();
                                    }
                                    else {
                                        var message = (reason && reason.data) ? reason.data.message : '';
                                        finishCallback('Error invocando el servicio register_update_question:['+message+']');
                                    }
                                }, function (reason) {
                                    var message = (reason && reason.data) ? reason.data.message : '';
                                    finishCallback('Error invocando el servicio register_update_question:['+message+']');
                                });
                            }
                        }
                    }
                    else {
                        finishCallback();
                    }
                }
    
                if(sectionPart.elements.length === 0) {
                    finishCallback();
                }
    
            }, function (reason) {
                var message = (reason && reason.data) ? reason.data.message : '';
                countElements = 0;
                finishCallback('Error invocando el servicio register_update_question:['+message+']');
            });
        }
        else {
            countElements = 0;
            countElementsError = [];
            finishCallback();
        }

        function finishCallback(error) {
            countElements--;
            if(error) countElementsError.push(error);
            if(countElements <= 0) {
                if(countElementsError.length === 0 ) {
                    callback();    
                }
                else {
                    swal('Conexiones', 'Error al actualizar las preguntas en el servidor. Han ocurrido los siguientes errores : '+JSON.stringify(countElementsError), 'error');
                }
                
            }
        }
    }

    $scope.onSaveSequenceSection = function () {

        var sectionNumber = Number($scope.sequenceSection.sequenceSectionIndex.replace('section_', ''));
        
        saveEvidence($scope.sequenceSectionPart,function(){
            var data = {
                'id': $scope.sequence.id,
                'section_number': sectionNumber,
                'data_section': JSON.stringify($scope.sequenceSection)
            };

            $http.post('/update_sequence_section/', data)
                .then(function (response) {
                    if (response && response.status === 200) {
                        $scope.applyChange = false;
                        swal('Conexiones', response.data.message, 'success');
                        loadSequence($scope.sequence.id);
                    }
                    else {
                        swal('Conexiones', 'Error al modificar la sección de la secuencia', 'error');
                    }
                    loadSequence($scope.sequence.id);
                }, function (reason) {
                    var message = (reason && reason.data) ? reason.data.message : '';
                    swal('Conexiones', 'Error al modificar la secuencia: ' + message, 'error');
                    loadSequence($scope.sequence.id);
                });
        });
    }

    $scope.onSaveMoment = function () {
        $http.post('/update_moment/', $scope.moment)
            .then(function (response) {
                if (response && response.status === 200) {
                    $scope.applyChange = false;
                    loadSequence($scope.sequence.id);
                    swal('Conexiones', response.data.message, 'success');
                }
                else {
                    swal('Conexiones', 'Error al modificar la secuencia', 'danger');
                }

            }, function (reason) {
                var message = (reason && reason.data) ? reason.data.message : '';
                swal('Conexiones', 'Error al modificar el momento: ' + message, 'error');
                loadSequence($scope.sequence.id);
            });
    }

    $scope.onSaveMomentSectionPart = function () {

        //var sectionNumber = Number($scope.momentSectionPart.momentSectionPartIndex.replace('part_',''));
        var sectionNumber = Number($scope.momentSection.momentSectionIndex) + 1;

        saveEvidence($scope.momentSectionPart,function(){
            var data = {
                'id': $scope.moment.id,
                'section_number': sectionNumber,
                'data_section': angular.toJson($scope.momentSection)
            };

            $http.post('/update_moment_section/', data)
                .then(function (response) {
                    if (response && response.status === 200) {
                        $scope.applyChange = false;
                        loadSequence($scope.sequence.id);
                        swal('Conexiones', response.data.message, 'success');
                    }
                    else {
                        swal('Conexiones', 'Error al modificar la seccíón del momento', 'danger');
                    }

                }, function (reason) {
                    var message = (reason && reason.data) ? reason.data.message : '';
                    swal('Conexiones', 'Error al modificar la seccion de momento: ' + message, 'error');
                    loadSequence($scope.sequence.id);
                });
        });
    }

    $scope.downSection = function (list, item) {
        var newList = [];
        for (var i = 0; i < list.length; i++) {
            if (i === item) {
                newList.push(list[i + 1]);
                newList.push(list[i]);
                i++;
            }
            else {
                newList.push(list[i]);
            }
        }
        $scope.moment.sections = newList;

        if ($scope.dataJstree.type === 'openMoment') {
            $scope.applyChange = true;
            $scope.moment.section_1 = angular.toJson(newList[0]);
            $scope.moment.section_2 = angular.toJson(newList[1]);
            $scope.moment.section_3 = angular.toJson(newList[2]);
            $scope.moment.section_4 = angular.toJson(newList[3]);
        }
    }

    $scope.upSection = function (list, item) {
        var newList = [];
        for (var i = 0; i < list.length; i++) {
            if (i + 1 === item) {
                newList.push(list[i + 1]);
                newList.push(list[i]);
                i++;
            }
            else {
                newList.push(list[i]);
            }
        }
        $scope.moment.sections = newList;
        if ($scope.dataJstree.type === 'openMoment') {
            $scope.applyChange = true;
            $scope.moment.section_1 = angular.toJson(newList[0]);
            $scope.moment.section_2 = angular.toJson(newList[1]);
            $scope.moment.section_3 = angular.toJson(newList[2]);
            $scope.moment.section_4 = angular.toJson(newList[3]);
        }
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
            closeOnConfirm: true,
            closeOnCancel: false
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
                  closeOnConfirm: false,
                  closeOnCancel: false
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

    $scope.openEvidence = function(elementEvidence) {
        $scope.showEvidenceModal = true;
    }

    $scope.closeEvidence = function() {
        $scope.showEvidenceModal = false;
        $scope.questionEdit = null;
    }
    
    $scope.resizeSequenceCard = function () {
        var card = $('.background-sequence-card');

        var w = Number(card.attr('w'));
        var h = Number(card.attr('h'));
        var newW = Number(card.css('width').replace('px', ''));
        var newH = Number(card.css('height').replace('px', ''));

        var deltaW = newW - w;
        var deltaH = Math.round((deltaW * h) / w);

        var background = $('.background-sequence-image');
        background.css('width', newW);
        background.css('height', + (h + deltaH));
        card.css('height', + (h + deltaH));

        $(card).find('[fs]').each(function (value, key) {
            var fs = $(this).attr('fs');
            var newFs = fs;//( fs * newW / w);
            $(this).css('font-size', newFs + 'px');
        });

        $(card).find('[w]').each(function (value, key) {
            var objW = Number($(this).attr('w'));
            $(this).css('width', objW + 'px');
            $(this).addClass('position-absolute');
        });

        $(card).find('[h]').each(function (value, key) {
            var objH = Number($(this).attr('h'));
            $(this).css('height', objH + 'px');
            $(this).addClass('position-absolute');
        });

        $(card).find('[mt]').each(function (value, key) {
            var objMt = Number($(this).attr('mt'));
            $(this).css('top', objMt + 'px');
            $(this).addClass('position-absolute');
        });

        $(card).find('[ml]').each(function (value, key) {
            var objMl = Number($(this).attr('ml'));
            $(this).css('left', objMl + 'px');
            $(this).addClass('position-absolute');
        });

        $('.d-none-result').removeClass('d-none');
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
                    },
                    stop: function (event, ui) {
                        $scope.$parent.mbDraggable = true;
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
        template: '<div ng-show="elementParentEdit" ng-repeat="split in elementParentEdit[elementEdit].split(\'|\') track by $index"> ' +
            '<span ng-show="showIndexLetter">{{letters[$index]}}).</span><input ng-change="onChangeSplit($index,split)" ng-model="split" class="mt-1 fs--1 w-75"/>  ' +
            '<a ng-click="delete($index)" style="marging-top: 8px:;"><i class="far fa-times-circle"></i><a/> </div> ' +
            '<input class="mt-1 w-75 fs--1" type="text" ng-model="newSplit"/>' +
            '<a class="cursor-pointer" ng-click="onNewSplit()"> ' +
            '<i class="fas fa-plus"></i><a/>',
        controller: function ($scope, $timeout) {
            
            $scope.delete = function ($index) {

                $scope.applyChange = true;

                var list = $scope.elementParentEdit[$scope.elementEdit].split('|');
                var newList = '';
                for (var i = 0; i < list.length; i++) {
                    if (i != $index) {
                        if (newList.length > 0) {
                            newList = newList + '|';
                        }
                        newList = newList + list[i];
                    }
                }
                $scope.elementParentEdit[$scope.elementEdit] = newList;
            }
            $scope.onChangeSplit = function ($index, split) {
                $scope.applyChange = true;
                var list = $scope.elementParentEdit[$scope.elementEdit].split('|');
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
                $scope.elementParentEdit[$scope.elementEdit] = newList;
            }
            $scope.onNewSplit = function () {
                $scope.applyChange = true;
                $scope.elementParentEdit[$scope.elementEdit] = $scope.elementParentEdit[$scope.elementEdit] || '';
                if ($scope.newSplit && $scope.newSplit.length > 0) {
                    if ($scope.elementParentEdit[$scope.elementEdit].length > 0) {
                        $scope.elementParentEdit[$scope.elementEdit] += '|';
                    }
                    $scope.elementParentEdit[$scope.elementEdit] += $scope.newSplit;
                }
                $scope.newSplit = '';
            }
            $scope.onChangeInput = function () {
                $scope.applyChange = true;
                if ($scope.dataJstree.type === 'openSequenceSectionPart') {
                    $scope.sequence[$scope.sequenceSectionIndex] = angular.toJson($scope.sequenceSection);
                }
                $timeout(function () {
                    $scope.resizeSequenceCard();
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
                    $scope.resizeSequenceCard();
                }, 10);

            }
        }
    };
});

MyApp.directive('conxEvidenceQuestions', function () {
    var date = moment().format('YYYYMMDDHHMMSS');
    return {
        restrict: 'E',
        template: '<div class="d-flex" ng-show="elementEdit && elementEdit.questions.length > 0"  ng-repeat="question in elementEdit.questions track by $index"> ' +
            '<div class="fs--1 mt-1 font-weight-semi-bold mr-2">{{$index + 1}}) </div>' +
            '<input style="padding-right: 51px;" ng-change="onChangeQuestion()" ng-readonly="question.isHtml" ng-model="question.title" ng-click="onOpenEvidenceQuestion(question)" class="mt-1 fs--1 w-75 mr-1 cursor-pointer"/>  ' +
            '<button class="btn btn-sm btn-primary" ng-click="onOpenHTMLEditor(question)" style="margin-left: -44px;height: 26px;padding: 3px;margin-top: 5px;"> html </button>  ' +
            '<a ng-click="deleteQuestion($index)" style="margin-left: 10px;margin-top: 5px;"><i class="cursor-pointer  far fa-times-circle"></i><a/> </div> ' +
            '<a href="#" ng-click="onNewQuestion()"><span class="fs--1"> Nueva pregunta </span><i class="fas fa-plus cursor-pointer"></i><a/>',
        controller: function ($scope, $timeout) {
            
            $scope.onNewQuestion = function () {
                $scope.applyChange = true;
                $scope.elementEdit.questions = $scope.elementEdit.questions || [];
                $scope.questionEdit = {"review":[{"id":"a","review":"0"}],"options":[{"id":"a","option":""}],"$index":$scope.elementEdit.questions.length};
                $scope.elementEdit.questions.push($scope.questionEdit);
                $scope.applyChangeEvidence = true;
            }

            $scope.onOpenEvidenceQuestion = function(question) {
                $scope.questionEdit = question;
            }
            
            $scope.onCloseHTMLEditor = function() {
                $scope.showHTMLEditor = false;
                $scope.questionEdit.title = $('#editorhtml_ifr').contents().find('#tinymce').html() || 'prueba';
                $scope.questionEdit.isHtml = true;
                $scope.questionEdit.placeHolderHtml = $('#editorhtml_ifr').contents().find('#tinymce').text();
                $scope.applyChange = true;
                $scope.applyChangeEvidence = true;
            }
            
            $scope.onOpenHTMLEditor = function(question) {
                $scope.showHTMLEditor = true;
                $scope.questionEdit = question;
                
                var title = question.title;
                //$('.tox.tox-tinymce').remove();
                $('#editorhtml').html(title);
                if(tinymce.get('editorhtml'))
                $(tinymce.get('editorhtml').getBody()).html(title);
                
                tinymce.init({
                  selector: '#editorhtml',
                  height: 500,
                  plugins: [
                    'link image imagetools table spellchecker lists'
                  ],
                  contextmenu: "link image imagetools table spellchecker lists",
                  content_css: "body { color: #E15433; }"
                });
            }
            
            $scope.onChangeQuestion = function() {
                $scope.applyChange = true;
                $scope.applyChangeEvidence = true;
            }
            
            $scope.deleteQuestion = function ($index) {
                $scope.applyChange = true;
                $scope.applyChangeEvidence = true;
                $scope.elementEdit.questions = $scope.elementEdit.questions || [];
                var list = $scope.elementEdit.questions;
                var newList = [];
                for (var i = 0; i < list.length; i++) {
                    if (i != $index) {
                        newList.push(list[i]);
                    }
                }
                $scope.elementEdit.questions = newList;
                $scope.questionEdit = null;
            }
        }
    };
});

MyApp.directive('conxEvidenceOptions', function () {
    return {
        restrict: 'E',
        template: '<div ng-show="questionEdit" ng-repeat="itemOption in questionEdit.options track by $index"> ' +
            '<span class="fs--1 font-weight-semi-bold">{{itemOption.id}}) </span>' +
            '<input ng-change="onChange()" ng-model="itemOption.option" class="mt-1 fs--1 w-75"/>  ' +
            '<button class="btn btn-sm btn-primary" ng-click="onOpenHTMLEditorAnswer(itemOption)" style="margin-left: -42px;height: 24px;padding: 2px;margin-top: 0px;"> html </button>  ' +
            '<a ng-click="onDelete($index)" style="marging-top: 8px;"><i class="far fa-times-circle"></i><a/>'+
            '</div> ' +
            '<a href="#" ng-click="onNew()"><span class="fs--1"> Nueva respuesta </span><i class="fas fa-plus cursor-pointer"></i><a/>',
        controller: function ($scope, $timeout) {
            
            $scope.letters = ['a','b','c','d','e','f','g','h','i','j','k','l','m'];
            
            $scope.onOpenHTMLEditorAnswer = function(answer) {
                $scope.showHTMLEditorAnswer = true;
                $scope.answerEdit = answer;
                
                var title = answer.option;
                //$('.tox.tox-tinymce').remove();
                $('#editorAnserHtml').html(title);
                if(tinymce.get('editorAnserHtml'))
                $(tinymce.get('editorAnserHtml').getBody()).html(title);
                
                tinymce.init({
                  selector: '#editorAnserHtml',
                  height: 500,
                  plugins: [
                    'link image imagetools table spellchecker lists'
                  ],
                  contextmenu: "link image imagetools table spellchecker lists",
                  content_css: '//www.tiny.cloud/css/codepen.min.css'
                });
            }
            
            $scope.onCloseHTMLEditorAnswer = function() {
                $scope.showHTMLEditorAnswer = false;
                $scope.answerEdit.option = $('#editorAnserHtml_ifr').contents().find('#tinymce').html() || 'prueba';
                $scope.answerEdit.isHtml = true;
                $scope.answerEdit.placeHolderHtml = $('#editorAnserHtml_ifr').contents().find('#tinymce').text();
                $scope.applyChange = true;
                $scope.applyChangeEvidence = true;
            }

            $scope.onDelete = function ($index) {

                $scope.applyChange = true;
                //--delete option
                var list = $scope.questionEdit.options;
                var newList = [];
                for (var i = 0; i < list.length; i++) {
                    if (i != $index) {
                        newList.push(list[i]);
                    }
                }
                $scope.questionEdit.options = newList;

                //--delete review 
                var list = $scope.questionEdit.review;
                var newList = [];
                for (var i = 0; i < list.length; i++) {
                    if (i != $index) {
                        newList.push(list[i]);
                    }
                }
                $scope.questionEdit.review = newList;
            }
            $scope.onChange = function ($index, split) {
                $scope.applyChange = true;
            }
            $scope.onNew = function () {
                $scope.applyChange = true;
                var id = $scope.letters[$scope.questionEdit.options.length];
                $scope.questionEdit.options.push({"id":id,"option":$scope.newOption});
                $scope.questionEdit.review.push({"id":id,"review":"0"});
            }
        }
    };
});

MyApp.directive('conxSlideImages', function () {
    return {
        restrict: 'E',
        /*template: '<div ng-show="elementParentEdit && elementParentEdit[elementEdit].length > 0" ng-repeat="split in elementParentEdit[elementEdit].split(\'|\') track by $index"> ' +
            '<input ng-change="onChangeSplit($index,split)" ng-model="split" class="mt-1 fs--1 w-90"/>  ' +
            '<a ng-click="delete($index)" style="marging-top: 8px:;"><i class="far fa-times-circle"></i><a/> </div> ' +
            '<input class="mt-1 w-90 fs--1" type="text" ng-model="newSplit"/> <a href="#" class="cursor-pointer" ng-click="onNewSplit()"> <i class="fas fa-plus"></i><a/>',
            */
        template: '',        
        controller: function ($scope, $timeout) {
            $scope.delete = function ($index) {

                $scope.applyChange = true;

                var list = $scope.elementParentEdit[$scope.elementEdit].split('|');
                var newList = '';
                for (var i = 0; i < list.length; i++) {
                    if (i != $index) {
                        if (newList.length > 0) {
                            newList = newList + '|';
                        }
                        newList = newList + list[i];
                    }
                }
                $scope.elementParentEdit[$scope.elementEdit] = newList;
            }
            $scope.onChangeSplit = function ($index, split) {
                $scope.applyChange = true;
                var list = $scope.elementParentEdit[$scope.elementEdit].split('|');
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
                $scope.elementParentEdit[$scope.elementEdit] = newList;
            }
            $scope.onNewSplit = function () {
                $scope.applyChange = true;
                $scope.elementParentEdit[$scope.elementEdit] = $scope.elementParentEdit[$scope.elementEdit] || '';
                if ($scope.newSplit && $scope.newSplit.length > 0) {
                    if ($scope.elementParentEdit[$scope.elementEdit].length > 0) {
                        $scope.elementParentEdit[$scope.elementEdit] += '|';
                    }
                    $scope.elementParentEdit[$scope.elementEdit] += $scope.newSplit;
                }
                $scope.newSplit = '';
            }
            $scope.onChangeInput = function () {
                $scope.applyChange = true;
                if ($scope.dataJstree.type === 'openSequenceSectionPart') {
                    $scope.sequence[$scope.sequenceSectionIndex] = angular.toJson($scope.sequenceSection);
                }
                $timeout(function () {
                    $scope.resizeSequenceCard();
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
                    $scope.resizeSequenceCard();
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
    $('#sidemenu-sequences-content').addClass('d-none');
    $('#sidemenu-sequences-content').removeClass("show");
    $('#sidemenu-sequences-content').removeClass("d-lg-block");
    $('#sidemenu-tools-content').addClass('d-none');
    $('#sidemenu-tools-content').removeClass("show");
    $('#sidemenu-tools-content').removeClass("d-lg-block");
    $('#sidemenu-sequences').addClass("col-lg-0_5");
    $('#sidemenu-sequences').removeClass("col-lg-3");
    $('#content-section-sequences').removeClass("col-lg-9");
    $('#content-section-sequences').addClass("col-lg-11_5");
};

var showSideMenu = function () {
    $('#sidemenu-sequences-empty').removeClass('show');
    $('#sidemenu-sequences-empty').addClass('d-none');

    $('#sidemenu-sequences-content').removeClass('d-none');
    $('#sidemenu-sequences-content').addClass("show");
    $('#sidemenu-sequences-content').addClass("d-lg-block");

    $('#sidemenu-tools-content').removeClass('d-none');
    $('#sidemenu-tools-content').addClass("show");
    $('#sidemenu-tools-content').addClass("d-lg-block");

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
