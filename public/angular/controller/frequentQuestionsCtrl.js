MyApp.controller("frequentQuestionCtrl", function ($scope, $http, $timeout,$sce) {
    $scope.toogleChatPanel = false;
    $scope.frequentQuestions = [];
    $scope.email = null;

    $scope.init = function (email) {    
        
        $scope.email = email;
        
        $('.result-finish-done').removeClass('d-none');
         
        $http({
            url:"/get_frequent_questions/",
            method: "GET",
        }).
        then(function (response) { 
            $scope.frequentQuestions = response.data.data

        }).catch(function (e) {
            $scope.errorMessageFilter = 'Error consultando las preguntas frecuentes, compruebe su conexión a internet';
        });

    
    }
    $scope.onSendEmail=function(){
        if ($scope.message && $scope.message.length > 0) {
            if ($scope.email && $scope.email.length > 0 ){
                $('#sendButton').addClass('fa fa-spinner fa-spin'); 
                $http.post('/send_email_contactus',
                {                            
                    'name':$scope.name,
                    'email':$scope.email,
                    'affair':$scope.affair,
                    'message':$scope.message,                           
                }).
                then(function onSuccess(response) {
                    $scope.message = "";   
                    $('#sendButton').removeClass('fa fa-spinner fa-spin'); 
                    $('#sendButton').addClass('fas fa-paper-plane');          
                    swal('Conexiones','Tu consulta ha sido enviada a nuentro grupo de operaciones.','success');
                }, function onError(response) {
                    $('#sendButton').removeClass('fa fa-spinner fa-spin'); 
                    $('#sendButton').addClass('fas fa-paper-plane');
                    swal('Conexiones','Lo sentimos, en estos momentos no podemos procesar tu solicitud, por favor intenta más tarde','error');
                });

            }
        }
    };
    
    $scope.trustAsHtml = function(string) {
        return $sce.trustAsHtml(string);
    };

});
