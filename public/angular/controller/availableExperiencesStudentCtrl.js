MyApp.controller("availableExperiencesStudentCtrl", ["$scope", "$http", "$timeout", function ($scope, $http, $timeout) {
    
    $scope.accountServices = null;
    $scope.errorMessage = null;

    $scope.init = function(company_id, sequence_id, account_service_id)    {
        $scope.defaultCompanySequences = company_id;
        $scope.sequenceId = sequence_id;
        $('.d-none-result').removeClass('d-none');
        $http({
            url:"/get_available_experiences/"+company_id+"/"+sequence_id+"/"+account_service_id,
            method: "GET",
        }).
        then(function (response) {
            $scope.sequence =  { 
                "id": response.data.data.sequence_id,
                "name": response.data.data.sequence_name,
                "url_image": response.data.data.sequence_url_image,
            }
            $('.d-result.d-none').removeClass('d-none');
            
            
            $scope.sequence_namesequence_url_image
            $scope.moments = response.data.data.moments;
            $scope.moment = $scope.moments ? $scope.moments[0] : {};
            if($scope.moment && $scope.moment && $scope.moment.parts[0])
            $scope.changeVideo($scope.moment.parts[0], $scope.moment);
            
        }).catch(function (e) {
            $scope.errorMessage = 'Error consultando las experiencias científicas de la guía ['+e+']';
            swal('Conexiones',$scope.errorMessage,'error');
        });
    };
    
    $scope.changeVideo = function(part, moment) {
        
        $scope.momentPart = part;
        $scope.moment = moment;
        $scope.sequence.url_vimeo = $scope.momentPart.video[0].url_vimeo;
        var params = $scope.momentPart.video[0].url_vimeo.split('/');
        var options = {
            id: "430365608"//params[params.length - 1]
        };
        $timeout(function () {
            var madeInNy = new Vimeo.Player('vimeo-player', options);        
        }, 1000);
    }

    $(".guidetype").click(function(){
        $(".guidetype").removeClass("active");
        $(this).addClass("active");
     });
    
}]);
