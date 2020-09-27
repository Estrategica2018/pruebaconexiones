MyApp.controller('navbarController', ['$scope','$http', function ($scope,$http) {
    
    $scope.initNumberShoppingCart = function() { 
        $('.notification-indicator-warning').removeClass('fill');
        $http({
            url: "/get_shopping_cart/",
            method: "GET",
        }).
        then(function (response) {
            $('.d-none-result.d-none').removeClass('d-none');
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
            if(length>0) {
                $('.notification-indicator-number').html(length);
                $('.notification-indicator-warning').addClass('fill');
            }
        }).catch(function (e) {
            $('.d-none-result.d-none').removeClass('d-none');
            $scope.errorMessage = 'Error consultando el carrito de compras, compruebe su conexión a internet';
        }); 
    }
    $scope.initSearch = function () {

        $scope.shoppingCartLength = null;

        $scope.searchList = [];
        $http.get('/get_company_sequences/' + 1)
        .then(function (response) {
            for(var i=0;i<response.data.companySequences.length;i++) {
                $scope.searchList.push({
                    type: 'Guía',  
                    obj:response.data.companySequences[i]
                })
            }
        }).catch(function(err){ 
        });

        $http.get('/get_kit_elements/')
        .then(function (response) {
            var kit = element = elementTmp =null;
            var mbControl = false;
            var elements = [];
            for(var i=0;i<response.data.length;i++) {
                kit = response.data[i];
                for(var j=0; j<kit.kit_elements.length; j++){
                    element = kit.kit_elements[j].element;
                    mbAditionControl = true;
                    for(var k = 0; k<elements.length; k++) {
                        elementTmp = elements[k];
                        if(elementTmp.id===element.id){
                            mbAditionControl = false;
                        }
                    }
                    if(mbAditionControl) {
                        elements.push(element);
                    }
                }
                $scope.searchList.push({ type: 'Kit',  obj:response.data[i] });
            }
            for(var j=0; j<elements.length; j++){
                element = elements[j];
                $scope.searchList.push({ type: 'Elemento', obj:element });
            }
            
        }).catch(function(err){ 
        });

        
    }
    $scope.closeSession = function(url) {
        $http({
            url: url,
            method: "POST",
        }).
        then(function (response) {
            if(response.data.url) {
                window.location = response.data.url;
            }
            else {
                window.location = '/';
            }
            
        }).catch(function (e) {
            $scope.errorMessage = 'Cerrando la sesión de usuario';
            swal('Conexiones',$scope.errorMessage,'error');
        });
    } 
    
}]);

$(window).resize(function () {
    $("#sideMenu").removeClass("show");
    FixNavbarMenu();
});

$("#toggleMenu").click(function () {
    $("#sideMenu").toggleClass("show");
});

function FixNavbarMenu () {
    var previousScroll = 0;
    $(window).scroll(function () {
        var currentScroll = $(this).scrollTop();
        if(Math.abs(previousScroll-currentScroll) > 5) {
            if (currentScroll < 120) {
                bigNav();
            } else if (currentScroll > 0 && currentScroll < $(document).height() - $(window).height()) {
                if (currentScroll > previousScroll) {
                    smallNav();
                } else {
                    smallNav();
                }
            }
        }
        previousScroll = currentScroll;
        
    });

    function bigNav() {
        $("#topLogo div div").removeClass('fs-lg--2').addClass('fs-lg-0');
        $("#topLogo div img").removeClass('small');
        $("#topLogo div img").css('height','auto');
    }

    function smallNav() {
        $("#topLogo div div").removeClass('fs-lg-0').addClass('fs-lg--2');
        $("#topLogo div img").addClass('small');
        $("#topLogo div img").css('height','auto');
    }
}

$(document).ready(function () {
    FixNavbarMenu(); 
});
