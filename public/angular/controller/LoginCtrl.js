MyApp.controller("LoginCtrl", ["$scope", "$http", function($scope, $http) {

    $scope.notifyStudent;
    $scope.goToFacebook = function() {
        var action = $('#formFacebook').attr('action');
        $('#goToProvider').attr("action",action) //set the form attributes
        document.getElementById('goToProvider').submit();
    }
    
    $scope.goToGmail = function() {
        var action = $('#formGmail').attr('action');
        $('#goToProvider').attr("action",action) //set the form attributes
        document.getElementById('goToProvider').submit();
    }

    $scope.notifyStudent = () => {
        swal({
            text: "Para recuperar tus datos de ingreso, por favor ingresa el correo del familiar que realizó la inscripción ",
            type: "warning",
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Enviar Link',
            input: 'email',
            showLoaderOnConfirm: true
        }).then((result) => {
          if (result) {
            var form = $('#form-send-link');
            var input = form.find('#email');
            var action = form.attr('action');
            window.location = action + '/' + result;
          }
        }).catch(swal.noop);
        
        
    }
}]);


