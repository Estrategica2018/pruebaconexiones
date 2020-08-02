MyApp.controller("frequentQuestionCtrl", function ($scope, $http, $timeout) {
    $scope.toogleChatPanel = false
    $scope.frequentQuestions = []
    $scope.init = function () {    
    
        $('.result-finish-done').removeClass('d-none');
         
        $http({
            url:"/get_frequent_questions/",
            method: "GET",
        }).
        then(function (response) { 
            $scope.frequentQuestions = response.data.data

        }).catch(function (e) {
            $scope.errorMessageFilter = 'Error consultando los planes de acceso, compruebe su conexión a internet';
        });

    
    }
    $scope.onSendEmail=function(){
        if ($scope.comment && $scope.comment.length > 0) {
            if ($scope.email && $scope.email.length > 0 ){
                $http.post('/send_frequent_question',
                {                            
                    'email':$scope.email,
                    'comment':$scope.comment                            
                }).
                then(function onSuccess(response) {
                    $scope.email = "";
                    $scope.comment = "";            
                    swal('Conexiones','Tu consulta ha sido enviada a nuentro grupo de operaciones.','success');
                }, function onError(response) {
                    swal('Conexiones','Lo sentimos, en estos momentos no podemos procesar tu solicitud, por favor intenta más tarde','error');
                });

            }
        }
        

    };

});
