MyApp.controller('shoppingCartController', function ($scope, $http, $timeout) {

    $scope.shopping_carts = null;
    $scope.totalPrices = 0;
    //$scope.ivaPrice = 0;
    //$scope.subtotalPrice = 0;
    $scope.numberOfItems = 0;
    $scope.cards = [];
    $scope.preferenceInitPoint = null;

    $scope.init = function () {
        $scope.totalPrices = 0;
        $scope.shopping_carts = null;
        $scope.totalPrices = 0;
        $scope.numberOfItems = 0;
        $scope.cards = [];
        $scope.preferenceInitPoint = null;
        
        $('.d-none-result').removeClass('d-none');
        $http({
            url: "/get_shopping_cart/",
            method: "GET",
        }).
            then(function (response) {
                $scope.shopping_carts = response.data.data;
                if ($scope.shopping_carts && $scope.shopping_carts.length > 0) {
                    for (var i = 0; i < $scope.shopping_carts.length; i++) { 
                        var sc = $scope.shopping_carts[i];
                        if (sc.rating_plan_id != null) {
                            if(sc.rating_plan.type_rating_plan_id === 1) {
                                $scope.totalPrices += sc.rating_plan.price;
                                $scope.numberOfItems+=sc.rating_plan.count;
                            }
                            else { 
                                $scope.totalPrices += sc.rating_plan_price;
                                $scope.numberOfItems++;
                            }
                        }
                        else {
                            for (var l = 0; l <  sc.shopping_cart_product.length; l++) {
                                var scp = sc.shopping_cart_product[l];
                                if (sc.type_product_id == 4) {
                                    $scope.totalPrices += scp.kiStruct.price;//parseInt(scp.kiStruct.price);
                                    $scope.numberOfItems++;
                                } else if (sc.type_product_id == 5) {
                                    for (var k = 0; k < scp.elementStruct.length; k++) {
                                        $scope.totalPrices += scp.elementStruct[k].price;
                                        $scope.numberOfItems++;
                                    }
                                }
                            }
                        }
                        if (sc.type_product_id === 2 && sc.shopping_cart_product) {
                            sc.sequences = [];
                            for (var j = 0; j < sc.shopping_cart_product.length; j++) {
                                scp = sc.shopping_cart_product[j];
                                if (scp.sequenceStruct_moment) {
                                    mbControl = false;
                                    for (var k = 0; k < sc.sequences.length; k++) {
                                        if (sc.sequences[k].id === scp.sequenceStruct_moment.id) {
                                            mbControl = true;
                                            break;
                                        }
                                    }
                                    if (!mbControl) {
                                        sc.sequences.push(scp.sequenceStruct_moment);
                                    }
                                }
                            }
                        }
                        if (sc.type_product_id === 3 && sc.shopping_cart_product) {
                            sc.sequences = [];
                            for (var j = 0; j < sc.shopping_cart_product.length; j++) {
                                scp = sc.shopping_cart_product[j];
                                if (scp.sequenceStruct_experience) {
                                    mbControl = false;
                                    for (var k = 0; k < sc.sequences.length; k++) {
                                        if (sc.sequences[k].id === scp.sequenceStruct_experience.id) {
                                            mbControl = true;
                                            break;
                                        }
                                    }
                                    if (!mbControl) {
                                        sc.sequences.push(scp.sequenceStruct_experience);
                                    }
                                }
                            }
                        }
                    }
                }
            //$scope.ivaPrice = (parseFloat($scope.totalPrices) * parseFloat(env('MERCADOPAGO_IVA'))) / 100;
            //$scope.ivaPrice = ((parseFloat($scope.totalPrices) * 15.9864) / 100).toFixed(2);
            //$scope.subtotalPrice = ($scope.totalPrices - $scope.ivaPrice).toFixed(2);  

            }).catch(function (e) {
                $scope.errorMessage = 'Error consultando el carrito de compras, compruebe su conexión a internet';
            });
    };
    $scope.onRegistryWithPendingShoppingCart = function (url) {
        swal({
            text: "Serás redireccionado al registro",
            showConfirmButton: false, showCancelButton: false,
            dangerMode: false,
        }).catch(swal.noop);

        window.location = url;
    }

    //invoca el servicio que construye la preferencia en mercado pago y retorna el link de pago    
    $scope.getPreferenceInitPoint = function () {
        $('.btn-spinner').removeClass('d-none');
        $http({
            url: "/get_preference_initPoint/",
            method: "GET",
        }).
            then(function (response) {
                window.location = response.data.initPoint;
                $('.btn-spinner').addClass('d-none');
            }).catch(function (e) {
                $scope.errorMessage = 'Error registrando preferencia de compra';
                swal('Conexiones', $scope.errorMessage, 'error');
                $('.btn-spinner').addClass('d-none');
            });
    }

    $scope.simulator = function () {
        $('.btn-spinner').removeClass('d-none');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "user_mail_validation/",
            cache: false,
            contentType: false,
            processData: false,
            method: 'GET',
            type: 'GET',
            success: function (response, xhr, request) {
                if(response.status === 'successfull'){
                    $http({
                        url: "/get_preference_simulator/",
                        method: "GET",
                    }).
                    then(function (response) {
                        document.getElementById('preference_id').value = response.data.preference_id;
                        document.getElementById('external_reference').value = response.data.preference_id;
                        document.getElementById('simulate-form').submit();
                        $('.btn-spinner').addClass('d-none');
                    }).catch(function (e) {
                        $scope.errorMessage = 'Error registrando preferencia de compra:'+ JSON.stringify(e);
                        swal('Conexiones', $scope.errorMessage, 'error');
                        $('.btn-spinner').addClass('d-none');
                    });
                }else{
                    swal({
                        text: 'Debe validar el correo, se ha enviado una notificación al correo registrado.',
                        type: "warning",
                        showCancelButton: false,
                        showConfirmButton: false
                    }).catch(swal.noop);
                    $('.btn-spinner').addClass('d-none');
                }

            },
            error: function (response, xhr, request) {
                swal({
                    text: 'No se pudo realizar la acción, vuelva a intentarlo',
                    type: "error",
                    showCancelButton: false,
                    showConfirmButton: false
                }).catch(swal.noop);
                $('.btn-spinner').addClass('d-none');
            }
        });

    }

    $scope.onDelete = function (idShoppingCart) {

        var data = { 'ids': idShoppingCart, 'payment_status_id': 5 };

        $http({
            url: "/delete_shopping_cart/",
            method: "POST",
            data: data
        }).
            then(function (response) {
                swal('Conexiones', 'El registro fué borrado exitósamente', 'success');
                $scope.init();

            }).catch(function (e) {
                $scope.errorMessage = 'Error eliminando registro del carrito de compras';
                swal('Conexiones', $scope.errorMessage, 'error');
            });
    }
});

