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

