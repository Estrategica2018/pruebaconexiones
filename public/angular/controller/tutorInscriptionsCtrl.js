MyApp.controller("tutorInscriptionsCtrl", ["$scope", "$http", function($scope, $http) {

    $scope.initInscriptions = function() {
        
        $scope.showHelpModal = true;
        
        $scope.students = [];
        $scope.newStudent = {};
        $scope.loadingRegistry = false;
        $scope.newRegisterForm = false;
        $scope.editRegisterForm = false;
        $scope.errorMessageRegister = "";
        
        $http({
            url:"/get_students_tutor/",
            method: "GET",
        }).
        then(function (response) {
            $scope.students = response.data;
            $('.d-none-result').removeClass('d-none');
            
        }).catch(function (e) {
            $scope.errorMessageFilter = 'Error consultando los estudiantes';
            $('.d-none-result').removeClass('d-none');
        });
    };
    
    $scope.registerUserForm = function (){
        if($scope.students && $scope.students.length >= 3) {
            swal({
                html: "<p style='text-align: justify;text-justify: inter-word;'>¡Qué bueno que te guste nuestra propuesta educativa! A propósito, tienes posibilidad de\n" +
                    "registrar hasta tres estudiantes con tu usuario. En caso de querer que más personas\n" +
                    "aprovechen la plataforma, puedes recomendarla y una vez hecha la subscrición en alguno\n" +
                    "de los planes que tenemos, podrá acceder a un bono de descuento.\n" +
                    "Para más información, pueden escríbenos a través del formulario de contacto.</p>",
                //html: true,
                type: "warning",
                showCancelButton: false,
                showConfirmButton: false
            }).catch(swal.noop);
        }
        else {
            window.scrollTo( 0, 0 );
            $scope.newStudent = {};
            $scope.newRegisterForm=true;
            $scope.errorMessageRegister="";
        }
    }
    
    $scope.onRegistry = function() {
        $scope.loadingRegistry = true;
        $http({
            url:"/register_student/",
            method: "POST",
            data: $scope.newStudent
        }).
        then(function (response) {
            $scope.loadingRegistry = false;
            if(response.data.status === 200) {
                swal({
                  text: "Estudiante registrado exitosamente",
                  type: "success",
                  showCancelButton: false,
                  showConfirmButton: false
                }).catch(swal.noop);
                $scope.initInscriptions();
            }else{
                $scope.errorMessageFilter = 'Error registrando el estudiante.';
                $scope.initInscriptions();
            }
        }
        ).catch(function (e) {
            $scope.errorMessageRegister = 'Error registrando el estudiante';
            $scope.loadingRegistry = false;
        });
    }
    $scope.editUserForm = function (idUser){
        $scope.user_name_old = null;
        $http({
            url:"/get_user/"+idUser,
            method: "GET",
        }).
        then(function (response) {
            window.scrollTo( 0, 0 );
            $scope.newStudent = {};
            $scope.editRegisterForm=true;
            $scope.errorMessageRegister="";
            $scope.newStudent.name= response.data.data.name;
            $scope.newStudent.last_name= response.data.data.last_name;
            $scope.newStudent.birthday= response.data.data.birthday ? new Date (response.data.data.birthday) : null;
            $scope.newStudent.user_name= response.data.data.user_name;
            $scope.newStudent.id = response.data.data.id;
            $scope.newStudent.kidSelected = response.data.age_stage.age_stage;
            $scope.validateUserName = true;
            $scope.user_name_old = $scope.newStudent.user_name;

            $('.d-none-result.d-none').removeClass('d-none');

        }).catch(function (e) {
            $scope.errorMessageFilter = 'Error consultando los estudiantes';
        });
    }
    $scope.onEditStudent = function() {
        if($scope.validateUserName){
            $scope.loadingRegistry = true;
            $http({
                url:"/edit_user_student/",
                method: "POST",
                data: $scope.newStudent
            }).
            then(function (response) {
                $scope.loadingRegistry = false;
                if(response.status === 200) {
                    swal({
                        text: "Estudiante editado exitosamente",
                        type: "success",
                        showCancelButton: false,
                        showConfirmButton: false
                    }).catch(swal.noop);
                    $scope.initInscriptions();
                }
            }).catch(function (e) {
                $scope.errorMessageRegister = 'Error editando el estudiante';
                $scope.loadingRegistry = false;
            });
        }else{
            swal({
                text: "El nombre de usuario no esta disponible",
                type: "warning",
                showCancelButton: false,
                showConfirmButton: false
            }).catch(swal.noop);
        }

    }
    $scope.viewPassword = (idInput) => {
        var cambio = document.getElementById(idInput)
        if(cambio.type == "password"){
            cambio.type = "text";
            $(`.${idInput}`).removeClass('fa fa-eye-slash').addClass('fa fa-eye');
        }else{
            cambio.type = "password";
            $(`.${idInput}`).removeClass('fa fa-eye').addClass('fa fa-eye-slash');
        }
    }
    $scope.onValidateUserName = function () {
        $http({
            url:"/validate_user_name/"+$scope.newStudent.user_name,
            method: "GET",
        }).
        then(function (response) {
            ($scope.newStudent.user_name ===  $scope.user_name_old)?response.data.data = true:'';
            $scope.validateUserName = response.data.data;

        }).catch(function (e) {
            $scope.errorMessageFilter = 'Error validando nombre de usuario';
        });
    }
    $scope.onKidSelected = (type)=>{
        $scope.newStudent.kidSelected = type

    }
}]);


