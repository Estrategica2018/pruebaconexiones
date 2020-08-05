MyApp.controller("kitsElementsCtrl", function ($scope, $http, $timeout) {
    $scope.kits = [];
    $scope.errorMessageFilter = '';
    $scope.searchText = '';
    
    $scope.allKits = function() {
        $('.d-none-result').removeClass('d-none');
        $http({
            url:"/get_kit_elements",
            method: "GET",
        }).
        then(function (response) {
            $scope.kit_elements = [];
            var kits = response.data;
            for(var i=0; i<kits.length; i++){
                var kit = kits[i];
                kit.type="kit";
                kit.name_url_value = kit.name.replace(/\s/g,'_').toLowerCase();
                $scope.kit_elements.push(kit);
                if(kit.kit_elements && kit.kit_elements[0] ) {
                    var element = kit.kit_elements[0].element;
                    element.type="element";
                    element.name_url_value = element.name.replace(/\s/g,'_').toLowerCase();
                    $scope.kit_elements.push(element);    
                }
            }
            $('#loading').removeClass('show');
            $('.d-none-result').removeClass('d-none');
        }).catch(function (e) {
            $scope.errorMessageFilter = 'Error consultando las secuencias';
            $('#loading').removeClass('show');
            $('.d-none-result').removeClass('d-none');
        });
    };
    
    $scope.getKits = function() { 
        
        var params = window.location.href.split('/');
        var kidName = window.location.href.split('/')[params.length - 1];
        var kidId = window.location.href.split('/')[params.length - 2];

        $('.d-none-result').removeClass('d-none');
            $http({
            url:"/get_kit_element/kit/" + kidId,
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
                mbSeq = false; 
                for(var j=0;j<$scope.listSequence.length;j++) {
                    if($scope.listSequence[j].id === moment.sequence.id) {
                        mbSeq = true;
                        break;
                    }
                }
                if(!mbSeq) {
                    $scope.listSequence.push(moment.sequence);
                }
            }
            
            
            // if($scope.kit.url_slider_images) {
            //     $http.post('/conexiones/admin/get_folder_image', { 'dir': $scope.kit.url_slider_images }).then(function (response) {
            //         $scope.kit.images = [];
            //         var slideImages = '';
            //         for(var dir in response.data.scanned_directory) {
            //             if(response.data.scanned_directory[dir]!=='..') {
            //                 var src = '/' + response.data.directory + '/' + response.data.scanned_directory[dir];
            //                 if(src.indexOf('.png')>0 || src.indexOf('.jpge')>0 || src.indexOf('.jpg')>0) {
            //                     slideImages += '<div class="swiper-slide" style="background-image:url('+src+');"></div>'; 
            //                 } 
            //             }
            //         }
            //        // $('.swiper-wrapper').html(slideImages);  
            //     },function(e){
            //         var message = 'Error consultando el directorio';
            //         if(e.message) {
            //             message += e.message;
            //         }
            //         $scope.errorMessage = angular.toJson(message);
            //         $scope.directoryPath = null;
            //     });
            // }
            
            $scope.kit.type = 'kit';
            
            $timeout(function() {
                $('#loading').removeClass('show');
                $('.d-none-result').removeClass('d-none');
              },1000);
            
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

        $('.d-none-result').removeClass('d-none');
            $http({
            url:"/get_kit_element/element/" + elementId,
            method: "GET",
        }).
        then(function (response) {
            $scope.element = response.data[0];
            $scope.element.type = 'element';

            $('#loading').removeClass('show');
            $('.d-none-result').removeClass('d-none');
        }).catch(function (e) {
            $scope.errorMessageFilter = 'Error consultando el elemento de laboratorio';
            $('#loading').removeClass('show');
            $('.d-none-result').removeClass('d-none');
        });
    };    
    
    $scope.onAddShoppingCart = function (kitElement) {
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
            addShoppingCart([data]);
          }
        }).catch(swal.noop);
    }
    
    function addShoppingCart(data) {
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
});
 