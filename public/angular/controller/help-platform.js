MyApp.controller("helpPlatformCtrl", function ($scope, $http) {
    
    $scope.init = function() {
		swal({
		  text: "Recuerda que para poder visualizar los contenidos, debes ingresar con el rol de estudiante!",
		  type: "warning",
		  showCancelButton: false,
		  showConfirmButton: false,
		}).catch(swal.noop);
    }
    
    
});
