MyApp.controller("adminIndexCtrl", function ($scope, $http, $compile) {
    
    $scope.showDetail = function(idShoppingCart) {
        $scope.idShoppingCart = idShoppingCart;
    }
    
});

MyApp.directive('conxShoppingcartDetail', [function () {
    return {
        restrict: 'E',
        scope: {
            id: "="
        },
        templateUrl: '/dialog_template_detail_user',
        controller: function ($scope, $timeout, $http) {
            
            $scope.$watch('id',function () {
                if(typeof $scope.id != 'undefined') {
                    
                    $scope.tabSelected = 'tranaction';
                    $scope.loading = true;
                    
                    $http.get('/conexiones/admin/get_user_shoppingCart/' + $scope.id)
                    .then(function (response) {
                        $scope.response = response.data;
                        $scope.loading = false;
                        
                    }).catch(function(err){
                        var message = err.data && err.data.exception ? err.data.exception : ' Error inesperado'
                        swal('Conexiones','Error consultando detalle de compra: ' + err,'error').catch(swal.noop);
                    });
                }
            });
        }
    };
}]);
