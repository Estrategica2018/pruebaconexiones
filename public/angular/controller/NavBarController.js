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
            var numberOfItems = 0;
            if(response.data.data) {
                for(var i=0,sc = null;i<response.data.data.length;i++) {
                    sc = response.data.data[i];
                    if(sc.rating_plan && sc.rating_plan.type_rating_plan_id === 1 ) {
                        numberOfItems += sc.rating_plan.count;
                    }
                    else if(sc.rating_plan && ( sc.rating_plan.type_rating_plan_id === 2  || sc.rating_plan.type_rating_plan_id === 3 ) ) {
                        var secList = {};
                        for(var j=0,sec = null, id = null; j<sc.shopping_cart_product.length; j++) {
                            sec = sc.shopping_cart_product[j];
                            id = sec.sequenceStruct_moment ? sec.sequenceStruct_moment.id : sec.sequenceStruct_experience.id;
                            secList[id] = sec.sequenceStruct_moment;
                        }
                        for(sec in secList) {
                            numberOfItems ++;
                        }
                    }
                    else {
                        numberOfItems++;
                    }
                }
            }
            if(numberOfItems>0) {
                $('.notification-indicator-number').html(numberOfItems);
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
            for(var i=0, secuence = null;i<response.data.companySequences.length;i++) {
                secuence = response.data.companySequences [i];
                $scope.searchList.push({
                    type: 'Guía',  
                    obj: { 
                        'name': response.data.companySequences[i].name,
                        'areas': response.data.companySequences[i].areas,
                        'description': response.data.companySequences[i].description,
                        'keywords': response.data.companySequences[i].keywords,
                        'objectives': response.data.companySequences[i].objectives,
                        'themes': response.data.companySequences[i].themes,
                        'id': response.data.companySequences[i].id,
                    }
                })
            }
        }).catch(function(err){ 
        });

        $http.get('/get_kit_elements/')
        .then(function (response) {
            for(var i=0, kit = null;i<response.data.kits.length;i++) {
                kit = response.data.kits[i];
                $scope.searchList.push({ type: 'Kit',  obj:kit });
            }
            for(var j=0,element = null; j<response.data.elements.length; j++){
                element = response.data.elements[j];
                $scope.searchList.push({ type: 'Elemento', obj: element });
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
