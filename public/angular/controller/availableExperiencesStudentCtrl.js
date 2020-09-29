MyApp.controller("availableExperiencesStudentCtrl", ["$scope", "$http", function ($scope, $http) {
    
    $scope.accountServices = null;
    $scope.errorMessage = null;

    $scope.init = function(company_id, sequence_id, account_service_id)    {
        console.log('ingresa consulta experiencias');
        $scope.defaultCompanySequences = company_id;
        $scope.sequenceId = sequence_id;
        $('.d-none-result').removeClass('d-none');
        $http({
            url:"/get_available_experiences/"+company_id+"/"+sequence_id+"/"+account_service_id,
            method: "GET",
        }).
        then(function (response) {
            $scope.accountServices = response.data;
            console.log($scope.accountServices);
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
