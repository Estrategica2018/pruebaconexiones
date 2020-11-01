MyApp.controller("passwordEmailCtrl", ["$scope", "$http","$timeout", function ($scope, $http,$timeout) {
    
    $scope.init = function(email,oldEmail,status) {
        if(email && !oldEmail && !status) {
            $timeout(function () {
                $('#submBtn').click();
            }, 100);
        }
    };
        
}]);
