MyApp.controller("availableExperiencesStudentCtrl", ["$scope", "$http", function ($scope, $http) {
    
    $scope.accountServices = null;
    $scope.errorMessage = null;

    $scope.init = function(company_id, secuence_id)    {
        console.log('ingresa consulta experiencias');
        $scope.defaultCompanySequences = company_id;
        $scope.sequenceId = secuence_id;
        $('.d-none-result').removeClass('d-none');
        $http({
            url:"/conexiones/get_avalible_experiences/"+$scope.defaultCompanySequences+"/"+$scope.secuence_id,
            method: "GET",
        }).
        then(function (response) {
            $scope.accountServices = response.data;
        }).catch(function (e) {
            $scope.errorMessage = 'Error consultando las experiencias, compruebe su conexión a internet';
            swal('Conexiones',$scope.errorMessage,'error');
        });
    };

    $(".guidetype").click(function(){
        $(".guidetype").removeClass("active");
        $(this).addClass("active");
     });
    
}]);
