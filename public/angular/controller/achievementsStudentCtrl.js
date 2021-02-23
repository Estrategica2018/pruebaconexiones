MyApp.controller("achievementsStudentCtrl", ["$scope", "$http", "$timeout", function ($scope, $http, $timeout) {
    
    $scope.sequences = null;
    
    $scope.initProfile = function() {
        $('.d-none-result').removeClass('d-none');
    }
    
    $scope.initSequences = function(companyId) {
        $('.d-none-result').removeClass('d-none'); 
        $scope.loadFinish = true;
		$('.evidences-answers').find('img').css("width", "100%");
		$('.evidences-answers').find('img').css("height", "auto");
            
        // $http({
        //     url:"/conexiones/get_available_sequences/" + companyId,
        //     method: "GET",
        // }).
        // then(function (response) {
        //     $scope.sequences = response.data;
        // }).catch(function (e) {
        //     $scope.errorMessage = 'Error consultando las secuencias, compruebe su conexión a internet';
        //     swal('Conexiones',$scope.errorMessage,'error');
        // });
    }
  
}]);
