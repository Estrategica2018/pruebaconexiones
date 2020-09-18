MyApp.controller("listAllUsersCtrl", ["$scope", "$http", function ($scope, $http) {
    
    $scope.errorMessage = null;
    $scope.users  = null;
    
    $scope.init = function() {
        $http.get("/conexiones/admin/get_all_users/").then(function (response) {
            if(response.data && response.data.users){
                $scope.users = response.data.users;
                $scope.users = $scope.users.map(function(value){
                        value.location = (value.country && value.country.name ? value.country.name : '') + (value.country && value.country.name ? ' - ' : '') + value.city;
                        value.status = value.affiliated_account_services.length > 0 ? 'Activo' : 'Inactivo';
                        return value;
                })
            }
            else  { 
                $scope.errorMessage = response.message || 'Error consultando usuarios';
                swal('Conexiones',$scope.errorMessage,'error');
            }
        }).catch(function(e){
            var message = e.data && e.data.exception ? e.data.exception : 'Error inesperado';
            $scope.errorMessage = 'Error consultando usuarios. ['+message+']';
            swal('Conexiones',$scope.errorMessage,'error');
        });
    }
    
    $scope.showUser = function(userid) {
       $scope.userid = userid;
    }

}])

MyApp.directive('conxShoppingcartDetail', [function () {
    return {
        restrict: 'E',
        scope: {
            userid: "="
        },
        templateUrl: '/dialog_template_detail_user',
        controller: function ($scope, $timeout, $http) {
            
            $scope.$watch('userid',function () {
                if(typeof $scope.userid != 'undefined') {
                    
                    $scope.tabSelected = 'contact';
                    $scope.loading = true;
                    
                    $http.get('/conexiones/admin/get_user/' + $scope.userid)
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
