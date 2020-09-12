MyApp.controller('navbarController', ['$scope','$http', function ($scope,$http) {
    
    $scope.shoppingCartLength = null;

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
        getShoppingCart();
    });
    
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
    
}]);
