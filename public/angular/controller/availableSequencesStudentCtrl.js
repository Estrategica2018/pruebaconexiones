MyApp.controller("availableSequencesStudentCtrl", ["$scope", "$http", function ($scope, $http) {
    
    $scope.accountServices = null;
    $scope.errorMessage = null;

    $scope.init = function(companyId,companyNick)    {
        
        $scope.defaultCompanySequences = companyId;

        $('.d-none-result').removeClass('d-none');
        $http({
            url:"/"+companyNick+"/get_available_sequences/"+companyId,
            method: "GET",
        }).
        then(function (response) {
            $scope.accountServices = response.data;
        }).catch(function (e) {
            $scope.errorMessage = 'Error consultando las secuencias, compruebe su conexión a internet';
            swal('Conexiones',$scope.errorMessage,'error');
        });
    };

    $(".guidetype").click(function(){
        $(".guidetype").removeClass("active");
        $(this).addClass("active");
     });
    
}]);
