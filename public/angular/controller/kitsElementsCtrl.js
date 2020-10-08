MyApp.controller("kitsElementsCtrl", function ($scope, $http, $timeout) {
    $scope.kits = [];
    $scope.errorMessageFilter = '';
    $scope.searchText = '';

    
    if($('#main-card').position()) {
        var top = $('#main-card').position().top;
        $('#loading').css('top',top);
    }

    function getRatingPlans() { 
        $scope.ratingPlans = [];
        //retrive plan
        $http({
            url: '/get_rating_plans/',
            method: "GET",
        }).
        then(function (response) {
            var data = response.data.data || response.data;
            $scope.ratingPlans = data.filter(function(value){
                //return !value.is_free && ( (value.type_plan.id === 1 && value.count === 1) || value.type_plan.id === 2 || value.type_plan.id === 3    );
                return !value.is_free ;
            })

        }).catch(function (e) {
            $('.d-none-result').removeClass('d-none');
            $('#loading').removeClass('show');
            $scope.errorMessageFilter = 'Error consultando las secuencias, compruebe su conexión a internet';
        });  
    }
    
    $scope.allKitElements = function() {
        $('.d-none-result').removeClass('d-none');
        $http({
            url:"/get_kit_elements",
            method: "GET",
        }).
        then(function (response) {
            $scope.kit_elements = [];
            var kits = response.data.kits;
            for(var i=0; i<kits.length; i++){
                var kit = kits[i];
                kit.type="kit";
                kit.name_url_value = kit.name.replace(/\s/g,'_').toLowerCase();
                $scope.kit_elements.push(kit);
            }
            
            var elements = response.data.elements;
            for(var i=0; i<elements.length; i++){
                var element = elements[i];
                element.type="element";
                element.name_url_value = element.name.replace(/\s/g,'_').toLowerCase();
                $scope.kit_elements.push(element);
            }
            
            $timeout(function() {
                $('#loading').removeClass('show');
                $('.d-none-result').removeClass('d-none');
                renderDisabledKit();
            },10);
            
        }).catch(function (e) {
            $scope.errorMessageFilter = 'Error consultando las secuencias';
            $('#loading').removeClass('show');
            $('.d-none-result').removeClass('d-none');
        });
    };
    
    $scope.getKit = function() { 
        
        var params = window.location.href.split('/');
        var kitName = window.location.href.split('/')[params.length - 1];
        var kitId = window.location.href.split('/')[params.length - 2];
        
        getRatingPlans();

        $('.d-none-result').removeClass('d-none');
            $http({
            url:"/get_kit_element/kit/" + kitId,
            method: "GET",
        }).
        then(function (response) {
            $scope.kit = response.data;
            var moment = null;
            var mbSeq = null;
            $scope.listSequence = [];
            if($scope.kit.moment_kits)
            for(var i=0;i<$scope.kit.moment_kits.length;i++) {
                moment = $scope.kit.moment_kits[i].moment;
                mbSeq = moment.sequence !== null;
                if(mbSeq) 
                for(var j=0;j<$scope.listSequence.length;j++) {
                    if( $scope.listSequence[j].id === moment.sequence.id ) {
                        mbSeq = false;
                        break;
                    }
                }
                if(mbSeq) {
                    moment.sequence.name_url_value = moment.sequence.name.replace(/\s/g,'_').toLowerCase();
                    $scope.listSequence.push(moment.sequence);
                }
            }
            
            $scope.kit.type = 'kit';
            
            $timeout(function() {
                $('#loading').removeClass('show');
                $('.d-none-result').removeClass('d-none');
                renderDisabledKit();
                resizeMiniCard();
              },100);
            
        }).catch(function (e) {
            $scope.errorMessageFilter = 'Error consultando los kits de laboratorio. ['+e+']';
            $('#loading').removeClass('show');
            $('.d-none-result').removeClass('d-none');
        });
    };
    
    $scope.getElement = function() {

        var params = window.location.href.split('/');
        var elementName = window.location.href.split('/')[params.length - 1];
        var elementId = window.location.href.split('/')[params.length - 2];

        getRatingPlans();

        $('.d-none-result').removeClass('d-none');
            $http({
            url:"/get_kit_element/element/" + elementId,
            method: "GET",
        }).
        then(function (response) {
            $scope.element = response.data;
            $scope.element.type = 'element';
            var moment = null;
            var mbSeq = null;
            $scope.listSequence = []; 
            if($scope.element.element_in_moment)
            for(var i=0;i<$scope.element.element_in_moment.length;i++) {
                moment = $scope.element.element_in_moment[i].moment;
                mbSeq = moment.sequence !== null;
                if(mbSeq) 
                for(var j=0;j<$scope.listSequence.length;j++) {
                    if($scope.listSequence[j].id === moment.sequence.id) {
                        mbSeq = false;
                        break;
                    }
                }
                if(mbSeq) {
                    moment.sequence.name_url_value = moment.sequence.name.replace(/\s/g,'_').toLowerCase();
                    $scope.listSequence.push(moment.sequence);
                }
            }

            $timeout(function() {
                $('#loading').removeClass('show');
                $('.d-none-result').removeClass('d-none');
                renderDisabledKit();
            },100);
            
        }).catch(function (e) {
            $scope.errorMessageFilter = 'Error consultando el elemento de laboratorio';
            $('#loading').removeClass('show');
            $('.d-none-result').removeClass('d-none');
        });
    };    
    
    $scope.onAddShoppingCart = function (kitElement) {
        if(kitElement.quantity === 0) {
            swal({
              text: 'Este producto no se encuentra disponible actualmente',
              type: "warning",
              showCancelButton: true,
              showCancelButton: false,
              cancelButtonClass: "btn-danger",
              cancelButtonText: "Cancelar"
            }).catch(swal.noop);                
        }
        else {
            swal({
            title: "Añadir elemento al carrito?",
            text: "Confirmas que deseas adicionar este kit de laboratorio al carrito",
            type: "warning",
            cancelButtonText: 'Cancelar',
            showCancelButton: true,
            showConfirmButton: true,
            dangerMode: false,
            })
            .then((willConfirm) => {
            if (willConfirm) {
                var data = {};
                data.type_product_id = kitElement.type === 'kit' ?  4 : kitElement.type === 'element' ?  5 : 0;
                data.products = [kitElement];
                createShoppingCart([data]);
            }
            }).catch(swal.noop);
        }
    }
    
    function createShoppingCart(data) {
        $http({
            url:"/create_shopping_cart",
            method: "POST",
            data: data
        }).
        then(function (response) {
            var message = response.data.message || 'Se ha registrado el producto correctamente';
            swal({
              title: message,
              type: 'success',
              buttons: ['Continuar comprando', 'Ir al carrito'],
              dangerMode: false,
            })
            .then((willGo) => {
              if (willGo) {
                window.location='/carrito_de_compras/';
              }
            });
        }).catch(function (e) {
            if(e.status === 404)
                $scope.errorMessageFilter = 'Error agregando el pedido al carrito de compras, comprueba la conexión a internet';
            else $scope.errorMessageFilter = 'Error agregando el pedido al carrito de compras';
            swal('Conexiones',$scope.errorMessageFilter,'error');
            $('#move').next().removeClass('d-none');
        });
    }
    
    $scope.onSequenceBuy = function (sequence) {
        var ratingPlans = '';
        for(var i = 0; i < $scope.ratingPlans.length; i++) {
            var rt = $scope.ratingPlans[i];
            if(!rt.is_free) {
                var listItem = rt.description_items.split('|');
                var items = '';
                for(var j=0;j<listItem.length;j++) {
                    items += '<li style="line-height: 17px;" class="card-rating-plan-id-'+ (i+1) +' fs-2 small pr-0 mt-4 ml-3"><span class="color-gray-dark font-14px font-family ">' + listItem[j] + '</span></li>';
                }
               var name = rt.name ? rt.name.replace(/\s/g,'_').toLowerCase() : '';
               var href = '/plan_de_acceso/' + rt.id + '/' + name + '/' + sequence.id;
            
               var button =   '<div onclick="location=\''+href+'\'" class="cursor-pointer w-100 trapecio-top  card-rating-button-id-'+ (i+1)  +'" style= "right: 12%;box-shadow: 0px 0px 0px 0px rgb(255 255 255), 0px -2px 0px rgba(255, 255, 255, 0.3);">'+
               '<a href="'+href+'" style="margin-left: -14px;"> <span class="fs-0" style="color: white;top: -23px;position: relative;">Adquirir</span> </a> </div> ';

               var message = 'por '+rt.count+' guía de aprendizaje';
               if(rt.type_plan.id === 2) {
                   message = 'Por momentos individuales';
               }
               if(rt.type_plan.id === 3) {
                   message = 'Por experiencias individuales';
               }
               
               ratingPlans += '<div class="pb-3 pl-3 pr-3 card-rating-id-' + (i+1) + ' "><div class="card-header card-rating-background-id-' + (i+1) + ' mt-3 fs--3 flex-100 box-shadow ">'+
                '<h5 class="card-title pl-lg-3 pr-lg-3 mb-0 font-weight-bold card-rating-plan-id-'+ (i+1) +'" style="color: white;">'+rt.name+'</h5></div>'+
                '<div class="card-body bg-light ratinPlanCard pr-2 pl-2 pb-0 w-100 box-shadow " style="min-height: 165px;"><ul class=" p-0 ml-2 text-left fs-2 mb-auto">' + items + '</ul></div>'+
                '<div class="row no-gutters card-footer card-rating-background-id-' + (i+1) + ' font-weight-bold text-align box-shadow " style="color: white;">'+
                ' <div class="col-5"> $'+rt.price+' USD  </div> <div class="pl-lg-1 pr-lg-1 col-7 font-14px" style="    max-width: 176px;margin-top:-10px"> '+ message +' </div></div>'+  button+'</div>';
            }
        }
        var html = '<div class="row justify-content-center">' + ratingPlans + '</div>';
        swal({
            html: html,
            customClass: 'container-alert-plans m-auto',
            width: '100%',
            showConfirmButton: false, showCancelButton: false
        }).catch(swal.noop);
        $('.swal2-show').css('background-color','transparent');
    }
    
    function renderDisabledKit() {
        
        $('.swiper-wrapper.kit.disabled').each(function(){
            //$(this).next().removeClass('d-none');
            $(this).next().css('width',$(this).width()/1.5);
            $(this).next().css('height',$(this).height()/5);
            $(this).next().css('top',$(this).height()/2.5);
            $(this).next().css('left',$(this).width()/7);
            $(this).next().css('font-size',$(this).width()/20);
        });
        
        $('.kit-imagen.disabled').each(function(){
            //$(this).next().removeClass('d-none');
            $(this).next().css('width',$(this).width()/1.5);
            $(this).next().css('height',$(this).height()/5);
            $(this).next().css('top',$(this).position().top * 3.5);
            $(this).next().css('left',72 + ($(this).parent().width() - $(this).width())/2);
            $(this).next().css('font-size',$(this).width()/15);
        });
    }
    
    function resizeMiniCard() {
        var minHeight = 0;
        $('.mini-card').each(function(){
            var height = $(this).css('height').replace('px','');
            if(Number(height) > minHeight ) {
                minHeight = Number(height);
            }
        });
        $('.mini-card').each(function(){
            $(this).css('height',minHeight + 'px');
        });
    }
    
    $(window).resize(function () {
        $timeout(function() {
            renderDisabledKit();
            resizeMiniCard();
        },10);
    });
    
    

});
 