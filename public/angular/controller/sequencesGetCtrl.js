MyApp.controller("sequencesGetCtrl", function ($scope, $http, $timeout) {
    $scope.sequence = null;
    $scope.errorMessageFilter = '';
    $scope.elementsKits = [];
    $scope.ratingPlans = [];

    var params = window.location.href.split('/');
    $scope.sequenceName = window.location.href.split('/')[params.length - 1];
    $scope.sequenceId = window.location.href.split('/')[params.length - 2];

    $scope.init = function () {
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
        
        $http({
            url: '/get_sequence/' + $scope.sequenceId,
            method: "GET",
        }).
            then(function (response) {

                $scope.sequence = response.data[0];
                $scope.sequence.images = [];
                if ($scope.sequence.url_slider_images) {
                    $scope.sequence.images = $scope.sequence.url_slider_images.split('|');
                }

                $timeout(function () {
                    $('#loading').removeClass('show');
                    $('.d-none-result2').removeClass('d-none');
                    new Swiper('.swiper-container', {
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                    });
                }, 100);


                function searchElementKit(elementKit) {
                    for (var i = 0; i < $scope.elementsKits.length; i++) {
                        if ($scope.elementsKits[i].type === elementKit.type && $scope.elementsKits[i].id === elementKit.id)
                            return true;
                    }
                    return false;
                }


                var kit = moment = element = null;
                $scope.elementsKits = [];
                if ($scope.sequence.moments) {
                    for (var i = 0; i < $scope.sequence.moments.length; i++) {

                        moment = $scope.sequence.moments[i];
                       
                        if (moment.moment_kit) {
                            for (var j = 0; j < moment.moment_kit.length; j++) {
                                kit = moment.moment_kit[j].kit;
                                if (kit) {
                                    kit.type = "kit";
                                    kit.name_url_value = kit.name.replace(/\s/g, '_').toLowerCase();
                                    if (!searchElementKit(kit)) {
                                        $scope.elementsKits.push(kit);
                                    }
                                    if (kit.elementsKits && kit.elementsKits[0]) {
                                        element = kit.elementsKits[0].element;
                                        element.type = "element";
                                        element.name_url_value = element.name.replace(/\s/g, '_').toLowerCase();
                                        if (!searchElementKit(element)) {
                                            $scope.elementsKits.push(element);
                                        }
                                    }
                                }
                                else {
                                    element = moment.moment_kit[j].element;
                                    if (element) {
                                        element.type = "element";
                                        element.name_url_value = element.name.replace(/\s/g, '_').toLowerCase();
                                        if (!searchElementKit(element)) {
                                            $scope.elementsKits.push(element);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

            }).catch(function (e) {
                $('.d-none-result2').removeClass('d-none');
                $('#loading').removeClass('show');
                $scope.errorMessageFilter = 'Error consultando las secuencias, compruebe su conexión a internet';
            });
    };
    
    $scope.showMash = function (sequence) {
        var width = $( window ).width() * 492 / 1280;
        if(sequence.mesh) {
            $http.post('/conexiones/admin/get_folder_image', { 'dir': sequence.mesh }).then(function (response) {
                $scope.meshDirectory = [];
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
    
    function getShoppingCart() {
        $('.notification-indicator-number').html('!');
        $http({
            url: "/get_shopping_cart/",
            method: "GET",
        }).
        then(function (response) {
            $scope.shopping_carts = response.data.data;
            var length = response.data && response.data.data ? response.data.data.length : 0;
            if(response.data.data)
            for(var i=0;i<response.data.data.length;i++) {
                if(response.data.data[i].type_product_id === 3 ||
                   response.data.data[i].type_product_id === 4) {
                       length --;
                       length += response.data.data[i].shopping_cart_product.length;
                }
            }
            $('.notification-indicator-number').html(length);
        }).catch(function (e) {
            $scope.errorMessage = 'Error consultando el carrito de compras, compruebe su conexión a internet';
            
        });
    }
    

    
    $scope.buyKitElement = function(kitElement) {
        
        if(kitElement.quantity === 1) {
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
            html = '<h5 class="text-justify">Confirma para agregar el <strong>'+kitElement.name+'</strong> al carrito de compras!</h5><div class="col-12"><img width="231px" height="auto" src="/'+kitElement.url_image+'"></div>';
            swal({
              html: html,
              showCancelButton: true,
              confirmButtonClass: "btn-danger",
              confirmButtonText: "Confirmar",
              cancelButtonText: "Cancelar",
              closeOnConfirm: false,
              closeOnCancel: false
            })
            .then((isConfirm) => {
                if (isConfirm) {
                    var data = {};
                    if(kitElement.type==='kit') {  
                        data.type_product_id = 4; //tipo kit 
                    }
                    else if(kitElement.type==='element') {
                        data.type_product_id = 5; //tipo element
                    }
                
                    data.products = [kitElement];
                    
                    $http.post('/create_shopping_cart',[data]).
                    then(function onSuccess(response) {
                        
                        $scope.getShoppingCart();
                        
                        var message = response.data && response.data.message ? response.data.message : 'implemento de laboratorio agregado exitosamente al carrito de compras!';
                        swal({
                          text: message,
                          type: "success",
                          showCancelButton: true,
                          confirmButtonClass: "btn-danger",
                          cancelButtonText: "Continuar comprando",
                          confirmButtonText: "Ir a carrito de compras",
                          closeOnConfirm: false,
                          closeOnCancel: false
                        })
                        .then((isConfirm) => {
                            if (isConfirm) {
                                window.location='/carrito_de_compras';     
                            }
                        }).catch(swal.noop);
                        
                    }, function onError(response) {
                        var message = response.data && response.data.message ? response.data.message : 'No se ha registrado el producto correctamente, intente de nuevo';
                        swal('Conexiones',message,'error');
                    });
                }
            }).catch(swal.noop);            
        }

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
               var button =   '<div onclick="location=\''+href+'\'" class="cursor-pointer w-75 trapecio-top position-absolute card-rating-button-id-'+ (i+1)  +'" style= "right: 12%;box-shadow: 0px 0px 0px 0px rgb(255 255 255), 0px -2px 0px rgba(255, 255, 255, 0.3);">'+
               '<a href="'+href+'" style="margin-left: -14px;"> <span class="fs-0 mt-2" style="position: absolute;top: -30px;color: white; ">Adquirir</span> </a> </div> ';

               var message = 'por '+rt.count+' guía de aprendizaje';
               if(rt.type_plan.id === 2) {
                   message = 'Por momentos individuales';
               }
               if(rt.type_plan.id === 3) {
                   message = 'Por experiencias individuales';
               }
               
               ratingPlans += '<div class="mt-3 col-12 col-md-6 col-lg-4 p-4"><div class="card-header card-rating-background-id-' + (i+1) + ' mt-3 fs--3 flex-100 box-shadow ">'+
                '<h5 class="card-title pl-lg-3 pr-lg-3 font-weight-bold card-rating-plan-id-'+ (i+1) +'" style="color: white;">'+rt.name+'</h5></div>'+
                '<div class="card-body bg-light ratinPlanCard pr-2 pl-2 pb-0 w-100 box-shadow " style="min-height: 165px;"><ul class=" p-0 ml-2 text-left fs-2 mb-auto">' + items + '</ul>'+  button+'</div>'+
                '<div class="row no-gutters card-footer card-rating-background-id-' + (i+1) + ' font-weight-bold text-align box-shadow " style="color: white;">'+
                ' <div class="col-5"> $'+rt.price+' USD  </div> <div class="pl-lg-1 pr-lg-1 col-7 font-14px" style="    max-width: 176px;margin-top:-10px"> '+ message +' </div></div></div>';
            }
        }
        var html = '<div class="row justify-content-center">' + ratingPlans + '</div>';
        swal({
            html: html,
            width: '75%',
            showConfirmButton: false, showCancelButton: false
        }).catch(swal.noop);
        $('.swal2-show').css('background-color','transparent');


        setTimeout(function () {
            marginLeftText();
         }, 300);
         
         
         function marginLeftText() {
             
              var maxHeight = 0;
              var maxHeightTitle = 0;
              var minHeight = 999;
              
              $('.ratinPlanCard ul').each(function(){
                var height =  Number($(this).css('height').replace('px',''));
                if(maxHeight < height) {
                    maxHeight = height ;
                }
              });
              $('.card-footer').each(function(){
                var height =  Number($(this).css('height').replace('px',''));
                if(minHeight > height) {
                    minHeight = height;
                }
              });
              
              $('.card-title').each(function(){
                var height =  Number($(this).css('height').replace('px',''));
                if(maxHeightTitle < height) {
                    maxHeightTitle = height;
                }
              });
              
              $('.ratinPlanCard ul').each(function(){
                $(this).css('height',maxHeight);
              });
              
              $('.card-title').each(function(){
                $(this).css('height',maxHeightTitle);
              });
              
              $('.card-footer').each(function(){
                $(this).css('height',minHeight);
              });
                 
              $('.trapecio-top').each(function(){ 
                  var width  = $(this).width(); 
                  $(this).find('a span').each(function(){ 
                       var delta =  (width) - $(this).width();
                       $(this).css('margin-left',(delta/2)+'px');  
                  });
              }); 
         }
  
         $( window ).resize(function() {
          marginLeftText();
        });

    }

});


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
