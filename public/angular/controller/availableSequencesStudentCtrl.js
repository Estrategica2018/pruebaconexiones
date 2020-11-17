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
            $scope.accountServices = [];
            var listSequences = {};
            for(var i=0, accountService = null; i < response.data.length; i++) {
                accountService = response.data[i];
                for(var j=0, seq = null; j < accountService.affiliated_content_account_service.length; j++) {
                    seq = accountService.affiliated_content_account_service[j].sequence;
                    listSequences[accountService.id+'_'+seq.id] = {
                        "id": accountService.id,
                        "sequence": seq,
                        "rating_plan": accountService.rating_plan
                    }
                }
            }
            
            for(var indx in listSequences) {
                $scope.accountServices.push(listSequences[indx]);
            }

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
