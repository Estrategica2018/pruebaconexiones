MyApp.controller("ratingPlanDetailCtrl", ["$scope", "$http", "$timeout", function ($scope, $http, $timeout) {
    $scope.ratingPlan = null;
    $scope.sequences = null;
    $scope.shoppingCarts = [];
    $scope.activesPlan = [];

    $scope.sequenceForAdd = null;
    $scope.elementsKits = [];
    $scope.meshDirectory = null;
    $scope.totalMoments = 0;
    var type_sequence = 1;
    var type_moment = 2;
    var type_experience = 3;
    var type_kit = 4;
    var type_element = 5;
    
    $scope.selectComplete = false;
    $scope.requiredMoment = false;
    $scope.errorMessageFilter = '';
    $scope.messageToastPrice = null;
    $("#toast-name-1").fadeOut();
    
    var scrollOrig = $("#toast-name-1").offset().top;
    var $mbDelayCtrlFlag = false;
    
    function clickSelected(totalSequences, ratingPlanCount) {
       
        if($scope.ratingPlan.type_rating_plan_id === type_sequence) {
            if(totalSequences === 0) {
                var textm = ratingPlanCount > 1 ? 'las '+ratingPlanCount+' secuencias ' : 'la secuencia';
                $scope.messageToast = 'Seleccciona '+ textm +' que deseas incluir'; 
            }
            else {
                if(ratingPlanCount > 1 ) {
                    $scope.messageToast = 'Has seleccionado ' + totalSequences  + ' de las ' + ratingPlanCount + ' secuencias permitidas';
                }
                else {
                    $scope.messageToast = 'Has seleccionado ' + totalSequences   + ' secuencia permitida';
                }
                
            }
        }
        else if($scope.ratingPlan.type_rating_plan_id === type_moment || $scope.ratingPlan.type_rating_plan_id === type_experience) {
            var textm = ratingPlanCount > 0 ? ' de los ' + ratingPlanCount : '';
            $scope.messageToast = 'Has seleccionado ' + totalSequences + textm + '  momento(s)';
        }
       
        if(!$mbDelayCtrlFlag) {
            $mbDelayCtrlFlag = true;
            $("#toast-name-1").fadeIn(400).delay(1000).fadeOut(400);
            setTimeout(function(){ $mbDelayCtrlFlag = false;}, 1000); 
            $("#toast-name-1").css('position','fixed');
            $("#toast-name-1").css('bottom',30);
            $("#toast-name-1").css('top','');
            $("#toast-name-1").removeClass('position-absolute');
        }
    }

    $scope.init = function(company_id, ratingPlanId, sequence_id) {
        $scope.defaultCompanySequences = company_id;
        $('.d-none-result').removeClass('d-none');
        getRatingPlan(company_id,ratingPlanId,sequence_id);        
    }

    function getRatingPlan(company_id,ratingPlanId,sequence_id) {
        $http({
            url:"/get_rating_plan/" + ratingPlanId,
            method: "GET",
        }).
        then(function (response) {
            
            $scope.ratingPlan = response.data ? response.data.data || response.data : response;
            $scope.ratingPlan = ( $scope.ratingPlan && $scope.ratingPlan.length ) ? $scope.ratingPlan[0] : $scope.ratingPlan;
            $scope.requiredMoment = $scope.ratingPlan.type_rating_plan_id === 2;
            $scope.requiredExperience = $scope.ratingPlan.type_rating_plan_id === 3;
 
            if(( $scope.requiredExperience || $scope.requiredMoment ) && sequence_id) { 
                $('#moment_div_responsive_ForAdd').addClass('show');
            }

            getSequences(company_id,sequence_id);

        }).catch(function (e) { 
            $scope.errorMessageFilter = 'Error consultando los planes de acceso, compruebe su conexión a internet';
        });
    }

    function getSequences(company_id,sequence_id) {
        
        $http({
            url:"/get_company_sequences/" + company_id,
            method: "GET",
        }).
        then(function (response) {
            $scope.sequences = response.data.companySequences;
            $scope.shoppingCarts = response.data.shoppingCarts;
            $scope.activesPlan = response.data.activesPlan;
            var listTemp = [];
            
            for(var i=0;i<$scope.sequences.length;i++) {
                if(Number($scope.sequences[i].id) ===  Number(sequence_id)) {
                    $scope.sequenceForAdd = Object.assign({},$scope.sequences[i]) ;
                    $scope.sequenceForAdd.isSelected = true;
                    if( $scope.ratingPlan.type_rating_plan_id === type_sequence) {
                       clickSelected(1, $scope.ratingPlan.count);
                    }
                    else if( $scope.ratingPlan.type_rating_plan_id === type_moment || $scope.ratingPlan.type_rating_plan_id === type_experience) {
                        $('#moment_div_responsive_ForAdd').addClass('show');
                        $scope.messageToast = 'Seleccciona los momentos que deseas incluir';
                        $("#toast-name-1").fadeIn(400).delay(1000).fadeOut(400);
                        $("#toast-name-1").css('top',scrollOrig + $(window).scrollTop());                
                    }
                        
                    $scope.selectComplete =  Number($scope.ratingPlan.count) === 1;
                    if($scope.selectComplete) {
                        $('.confirm_rating').addClass("btn-primary");
                        $('.confirm_rating').removeClass("btn-outline-primary");
                    }
                    else {
                        $('.confirm_rating').removeClass("btn-primary");
                        $('.confirm_rating').addClass("btn-outline-primary");
                    }
                    
                }
                else {
                    if((sequence_id && $scope.ratingPlan.count > 1) || !sequence_id) {
                       listTemp.push($scope.sequences[i]);
                    }
                }
            }
            
            if(!sequence_id) {
                clickSelected(0, $scope.ratingPlan.count);
            }
            $scope.sequences = listTemp;
            
            function resizeWidth() {
                var maxHeight = 0;
                    $('.card-boody-sequence').each(function(){
                        var height =  Number($(this).css('height').replace('px',''));
                        if(maxHeight < height) {
                            maxHeight = height ;
                        }
                    });
                    if(maxHeight > 765 ) {
                        maxHeight = 765;
                    }

                    $('.card-boody-sequence').each(function(){
                        $(this).css('min-height',maxHeight);
                    });
            }
            
            $(window).resize(function () {
                resizeWidth();    
            });
            
            $timeout(function () {
                resizeWidth();        
            }, 300);
            
        }).catch(function (e) {
            $scope.errorMessageFilter = 'Error consultando las guías de aprendizaje, compruebe su conexión a internet' + e;
        });
    }
    
    $scope.onCheckChange = async function(sequence,moment,sequenceIdForAdd) {

        var _continue = true;
        if( (sequence && sequence.isSelected) ||  (moment && moment.isSelected) ) {
            _continue = await validateSequencesActivate(sequence.id, $scope.shoppingCarts, $scope.activesPlan);  
        }
        
        if(_continue) {
            //Rating plan for sequence
            if( $scope.ratingPlan.type_rating_plan_id === type_sequence) {
                var totalSequences = 0;
                
                if($scope.sequenceForAdd && $scope.sequenceForAdd.isSelected ) {
                    totalSequences++;
                }
                
                angular.forEach($scope.sequences, function(sequenceTmp, key) {
                  if(sequenceTmp.isSelected) totalSequences++;
                });
                
                
                if(totalSequences > $scope.ratingPlan.count) {
                    sequence.isSelected = false;

                    swal({
                      html: "<strong>Has excedido en número máximo de guías de aprendizaje permitidas en el plan seleccionado.</strong>",
                      buttons: true,
                      dangerMode: true,
                    })
                }
                else {
                    $scope.selectComplete = totalSequences === $scope.ratingPlan.count;
                    if($scope.selectComplete) {
                        $('.confirm_rating').addClass("btn-primary");
                        $('.confirm_rating').removeClass("btn-outline-primary");
                    }
                    else {
                        $('.confirm_rating').removeClass("btn-primary");
                        $('.confirm_rating').addClass("btn-outline-primary");
                    }
                    $scope.messageToast = 'Seleccciona los momentos que deseas incluir';
                    
                    clickSelected(totalSequences, $scope.ratingPlan.count);
                }
            }
            //Rating plan for moment or experience
            else if($scope.ratingPlan.type_rating_plan_id === type_moment || $scope.ratingPlan.type_rating_plan_id === type_experience) {

                $scope.totalMoments = 0;
                
                if(sequenceIdForAdd) {
                    $('#moment_div_responsive_ForAdd').addClass('show');
                }
                else {
                    if(!$scope.sequenceForAdd) {
                        $('#moment_div_responsive_ForAdd').removeClass('show');
                    }
                    
                        $('#moment_div_responsive_'+sequence.id).addClass('show');
                    
                }
                
                angular.forEach($scope.sequences, function(sequenceTmp, key) {
                  
                    angular.forEach(sequenceTmp.moments, function(momentTmp, key) {
                        if(momentTmp.isSelected) $scope.totalMoments++;
                    });
                  
                });
                
                if($scope.sequenceForAdd) {
                    angular.forEach($scope.sequenceForAdd.moments, function(momentTmp, key) {
                        if(momentTmp.isSelected) $scope.totalMoments++;
                    });
                }

                if($scope.totalMoments===0) {
                    $scope.messageToastPrice = null;
                    $scope.selectComplete = 0;
                    $scope.messageToast = 'Seleccciona los momentos que deseas incluir';
                    
                }
                else {
                    if($scope.totalMoments > $scope.ratingPlan.count && $scope.ratingPlan.count > 0) {
                        
                        swal({
                          title: "Has excedido en número máximo de momentos de aprendizaje permitidos en el plan seleccionado",
                          buttons: true,
                          dangerMode: true,
                        })
                    }
                    else { 
                        $scope.selectComplete = $scope.totalMoments === $scope.ratingPlan.count || $scope.ratingPlan.count === 0
                        if($scope.selectComplete) {
                            $('.confirm_rating').addClass("btn-primary");
                            $('.confirm_rating').removeClass("btn-outline-primary");
                        }
                        else {
                            $('.confirm_rating').removeClass("btn-primary");
                            $('.confirm_rating').addClass("btn-outline-primary");
                        }
                        clickSelected($scope.totalMoments, $scope.ratingPlan.count);
                        var price = Math.round10($scope.totalMoments * $scope.ratingPlan.price,-2 );
                        $scope.messageToastPrice = 'Precio del plan $' + price + ' USD';
                    }
                }
            }
        }
        else {
            if(sequence) { sequence.isSelected = false; }
            if(moment) { moment.isSelected = false; }
        }
        
        $scope.$apply();
    }
    
    function validateSequencesActivate(sequence_id, shoppingCarts, activesPlan) {
         return new Promise(resolve => {
            var mbControl = false; 
            var message = '';
            if(activesPlan) {
                for(var i=0, account;i<activesPlan.length; i++) {
                    account = activesPlan[i];
                    if(account.affiliated_content_account_service[0].sequence_id === sequence_id){
                        mbControl = true; 
                        message = 'Ya tienes contratada esta guía de aprendizaje, deseas adquirirla de nuevo?';
                        break;
                    }
                }
            }
            if(!mbControl)
            for(var i=0, sc=null;i<shoppingCarts.length; i++) {
                sc = shoppingCarts[i];
                for(var j=0, product=null;j<sc.shopping_cart_product.length; j++) {
                    product = sc.shopping_cart_product[j];
                    if( ( product.sequence && product.sequence.id === sequence_id) || 
                        ( product.sequenceStruct_experience && product.sequenceStruct_experience.id === sequence_id ) ||
                        ( product.sequenceStruct_moment && product.sequenceStruct_moment.id === sequence_id )) {
                        mbControl = true; 
                        message = 'Ya tienes asignada esta guía en el carrito de compras, deseas adicionarla de nuevo?';
                        break;
                    }
                }
            }
            
            if(mbControl) {
                swal({
                    html: '<strong> '+ message+' </strong>',
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: '#748194',
                    confirmButtonClass: 'mr-4',
                    confirmButtonColor: '#2c7be5',
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar'
                })
                .then((result) => {
                    resolve(result);
                }).catch(function(res){
                    resolve(false);
                });                
            }
            else {
                resolve(true);
            }
        });
    }

    $scope.onContinueElements = function() {
        
        if(!$scope.selectComplete){
            
            var message = 'Recuerda que debes seleccionar '+ $scope.ratingPlan.count +' guía(s) antes de continuar con la compra.';
            if($scope.ratingPlan.type_rating_plan_id === type_moment || $scope.ratingPlan.type_rating_plan_id === type_experience) {
                message = 'Recuerda que debes seleccionar algún momento antes de continuar con la compra.';
            }
            swal({
                html: '<strong>' + message + '</strong>',
                buttons: true,
                dangerMode: true
            }).catch(swal.noop);
            return;        
        }
        
        window.scrollTo( 0, 0 );
        
        function searchElementKit(elementKit) {
            for(var i=0;i<$scope.elementsKits.length;i++) {
                if($scope.elementsKits[i].type === elementKit.type && $scope.elementsKits[i].id === elementKit.id)
                return true;
            }
            return false;
        }

        $scope.elementsKits = [];
        
        if($scope.sequenceForAdd && $scope.sequenceForAdd.moments && (
            ($scope.sequenceForAdd.isSelected && $scope.ratingPlan.type_rating_plan_id === type_sequence) ||
            ($scope.ratingPlan.type_rating_plan_id === type_moment || $scope.ratingPlan.type_rating_plan_id === type_experience)
        )) {
            var sequenceTmp = $scope.sequenceForAdd;
            var mbAdd = true;
            var kit,moment,element = null;
            
            for(var i=0;i<sequenceTmp.moments.length;i++) {
                moment = sequenceTmp.moments[i];
                if((moment.isSelected && ($scope.ratingPlan.type_rating_plan_id === type_moment || $scope.ratingPlan.type_rating_plan_id === type_experience)) || 
                    $scope.ratingPlan.type_rating_plan_id === type_sequence) {
                    for(var j=0;j<moment.moment_kit.length;j++) {
                        kit = moment.moment_kit[j].kit;
                        if(kit) {
                            kit.type = 'kit';
                            if(!searchElementKit(kit)) {
                                $scope.elementsKits.push(kit);
                            }
                            for(var k=0;k<kit.kit_elements.length;k++) {
                                element = kit.kit_elements[k].element;
                                element.type = 'element';
                                if(!searchElementKit(element)) {
                                    $scope.elementsKits.push(element);
                                }
                            }
                        }
                        else {
                            element = moment.moment_kit[j].element;
                            if(element) {
                                element.type = 'element';
                                if(!searchElementKit(element)) {
                                    $scope.elementsKits.push(element);
                                }
                            }
                        }
                    }
                }
            }
        }
        
        for(var s=0;s<$scope.sequences.length;s++) {
            var sequenceTmp = $scope.sequences[s];
            if( sequenceTmp.moments && (
                (sequenceTmp.isSelected && $scope.ratingPlan.type_rating_plan_id === type_sequence) ||
                ($scope.ratingPlan.type_rating_plan_id === type_moment || $scope.ratingPlan.type_rating_plan_id === type_experience)
            )) {
                var mbAdd = true;
                var kit,moment,element = null;
                
                for(var i=0;i<sequenceTmp.moments.length;i++) {
                    moment = sequenceTmp.moments[i];
                    
                    if((moment.isSelected && ($scope.ratingPlan.type_rating_plan_id === type_moment || $scope.ratingPlan.type_rating_plan_id === type_experience)) || 
                    $scope.ratingPlan.type_rating_plan_id === type_sequence) {
                        
                        for(var j=0;j<moment.moment_kit.length;j++) {
                            kit = moment.moment_kit[j].kit;
                            if(kit) {
                                kit.type = 'kit';
                                if(!searchElementKit(kit)) {
                                    $scope.elementsKits.push(kit);
                                }
                                for(var k=0;k<kit.kit_elements.length;k++) {
                                    element = kit.kit_elements[k].element;
                                    element.type = 'element';
                                    if(!searchElementKit(element)) {
                                        $scope.elementsKits.push(element);
                                    }
                                }
                            }
                            else {
                                element = moment.moment_kit[j].element;
                                if(element) {
                                    element.type = 'element';
                                    if(!searchElementKit(element)) {
                                        $scope.elementsKits.push(element);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    
        if($scope.elementsKits.length == 0 ) {
            swal({
                html: '<strong>Confirma para continuar con la compra</strong>',
                showConfirmButton: true, showCancelButton: true,
                confirmButtonColor: '#2c7be5',
                confirmButtonText: "Continuar compra",
                cancelButtonText: "Cancelar",
            })
            .then((result) => {
                if (result) {
                    $scope.onContinuePayment();
                }
            }).catch(swal.noop);
        }
    }
    
    $scope.onContinuePayment = function() {
        
        //retrive products to add shoppingCart
        var products = [];
        var moment = null;
        if($scope.sequenceForAdd) {
            if($scope.ratingPlan.type_rating_plan_id === type_sequence && $scope.sequenceForAdd.isSelected) {
                products.push({id:$scope.sequenceForAdd.id});
            }
            if($scope.ratingPlan.type_rating_plan_id === type_moment || $scope.ratingPlan.type_rating_plan_id === type_experience) {
                for(var i=0; i < $scope.sequenceForAdd.moments.length; i++ ) {
                    var moment = $scope.sequenceForAdd.moments[i];
                    if(moment.isSelected) {
                        products.push({id:moment.id});        
                    }
                }
            }
        }
        for(var s = 0; s < $scope.sequences.length; s++) {
            var sequenceTmp = $scope.sequences[s];
            if($scope.ratingPlan.type_rating_plan_id === type_sequence && sequenceTmp.isSelected) {
                products.push({id:sequenceTmp.id});
            }
            else if($scope.ratingPlan.type_rating_plan_id === type_moment || $scope.ratingPlan.type_rating_plan_id === type_experience) {
                
                for(var i=0; i < sequenceTmp.moments.length; i++ ) {
                    var moment = sequenceTmp.moments[i];
                    if(moment.isSelected) {
                        products.push({id:moment.id});        
                    }
                }
            }
        }
        var ratingPlanData = { 'type_product_id': $scope.ratingPlan.type_rating_plan_id,'rating_plan_id': $scope.ratingPlan.id, 'products': products};
        var kitsData =     { 'type_product_id': 4, products: [] };
        var elementsData =     { 'type_product_id': 5, products: [] };
        
        if($scope.elementsKits.length>0) {
            for(var i=0; i < $scope.elementsKits.length; i++) {
                if($scope.elementsKits[i].isSelected) {
                    if($scope.elementsKits[i].type === 'kit') {
                        kitsData.products.push($scope.elementsKits[i]);
                    }
                    else if($scope.elementsKits[i].type === 'element') {
                        elementsData.products.push($scope.elementsKits[i]);
                    }
                }
            }
        }
        
        var data = [ratingPlanData];
        
        if(kitsData.products.length > 0) {
            data.push(kitsData);
        }
        if(elementsData.products.length > 0) {
            data.push(elementsData);
        }
        
        $('#move').addClass('fa fa-spinner fa-spin');
        $('#move').next().addClass('d-none');
        
        $http({
            url:"/create_shopping_cart",
            method: "POST",
            data: data
        }).
        then(function (response) {
            if(response && response.data && typeof response.data === 'object') {
                $('#move').removeClass('fa fa-spinner fa-spin');
                var message = response.data.message || 'Se ha registrado el producto correctamente';
                swal({
                  html: '<h5>' + message + '</h5>',
                  type: 'success',
                  buttons: false,
                  dangerMode: false,
                });
                $('#move').next().removeClass('d-none');
                window.location = '/carrito_de_compras';
            }
            else {
                $scope.errorMessageFilter = 'Error agregando el pedido al carrito de compras, por favor intenta nuevamente';
                swal({
                  html: '<h6>' + $scope.errorMessageFilter + '</h6>',
                  type: 'error',
                  buttons: false,
                  dangerMode: false,
                });
                $('#move').removeClass('fa fa-spinner fa-spin');
                $('#move').next().removeClass('d-none');
            }
            
        }).catch(function (e) {
            $scope.errorMessageFilter = 'Error agregando el pedido al carrito de compras, comprueba tu conexión a internet';
            swal('Conexiones',$scope.errorMessageFilter,'error');
            $('#move').removeClass('fa fa-spinner fa-spin');
            $('#move').next().removeClass('d-none');
        });
    }
    
    
    $scope.showMash = function (sequence) {
        var width = $( window ).width() * 492 / 1280;
        if(sequence.mesh) {
            $http.post('/conexiones/admin/get_folder_image', { 'dir': sequence.mesh }).then(function (response) {
                $scope.meshDirectory = [];
                //Javascript control image index
                _mbControl = 0;
                
                var i = 0;
                var htmlImg = '';
                for(var dir in response.data.scanned_directory) {
                    
                    if(response.data.scanned_directory[dir]!=='..') {
                        if(response.data.directory.substr(response.data.directory.length-1,1) === '/') {
                            response.data.directory = response.data.directory.substr(0,response.data.directory.length-1);
                        }
                        var src = response.data.directory + '/' + response.data.scanned_directory[dir];
                        $scope.meshDirectory.push(src);
                        htmlImg += '<div id="id-image-'+i+'"><img src="/'+src+'" width="'+width+'px" height="auto"></div>';    
                        i++;
                    } 
                }
                
                var html = '<div ng-init="idElement=0;">' + 
                            '<div class="row mt-2 mb-3">'+
                                '<div class="ml-auto mr-auto">'+
                                '<button id="btnOnPrevius" onclick="onPrevius(\''+i+'\');"  class="btn btn-sm btn-primary">Previo</button>'+
                                '<button id="btnOnNext" onclick="onNext(\''+i+'\');" class="btn btn-sm btn-primary ml-2">Siguiente</button>'+
                                '</div>'+
                            '</div>' + htmlImg + '</div>';
                swal({
                    html: html,
                    width: '50%',
                    showConfirmButton: false, showCancelButton: false
                }).catch(swal.noop);
                
                $timeout(function () {
                    _mbControl = 0;
                    $('#btnOnPrevius').click(function() {onPrevius(i);});
                    $('#btnOnNext').click(function() {onNext(i);});
                    $('#btnOnPrevius').attr('disabled', true);

                    $('div[id^="id-image-"]').hide();
                    $('#id-image-' + _mbControl).show();
                }, 300);
                
            },function(e){
                var message = 'Error consultando el directorio';
                if(e.message) {
                    message += e.message;
                }
                $scope.errorMessage = angular.toJson(message);
                $scope.meshDirectory = null;
            });
        } else {
            var html = '<img src="/images/icons/NoImageAvailable.jpeg" width="'+width+'px" height="auto">';
            swal({
                html: html,
                width: '50%',
                showConfirmButton: false, showCancelButton: false
            }).catch(swal.noop);
        }
    }

    $scope.showVideo = function (sequence) {
        var width = $( window ).width() * 50/100;
        var height = $( window ).height() * 50/100;
        var width1 = width * 90/100;
        var height1 = height * 100/100;
        var html = '<div class="m-auto" style="height:'+height+'px;width:'+width+'px;"><iframe src="'+sequence.url_vimeo+'" width="'+width1+'px"  height="'+height1+'px" frameborder="0" webkitallowfullscreen="false" mozallowfullscreen="false" allowfullscreen="false"></iframe></div>';

        swal({
            html: html,
            width: '75%',
            showConfirmButton: false, showCancelButton: false
        }).catch(swal.noop);
    
    }

}]);

//Javascript control image index
var _mbControl = 0;
$('div[id^="id-image-"]').hide();
$('#id-image-' + _mbControl).show();

function onNext(arrayLenght){
    if(_mbControl + 1 < arrayLenght ) {
        _mbControl ++;
        $('div[id^="id-image-"]').hide();
        $('#id-image-' + _mbControl).show();
        if(_mbControl + 1 >= arrayLenght ) {
           $('#btnOnNext').attr('disabled', true);
        }
        else {
            $('#btnOnNext').attr('disabled', false);
        }
        
        $('#btnOnPrevius').attr('disabled', false);
    }
}


function onPrevius(arrayLenght){
    if(_mbControl > 0 ) {
        _mbControl --;
        $('div[id^="id-image-"]').hide();
        $('#id-image-' + _mbControl).show();

        if(_mbControl - 1 > 0 ) {
            $('#btnOnPrevius').attr('disabled', false);
        }
        else {
            $('#btnOnPrevius').attr('disabled', true);
        }
        
        $('#btnOnNext').attr('disabled', false);
    }
}
