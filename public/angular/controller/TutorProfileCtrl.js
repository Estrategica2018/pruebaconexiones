MyApp.controller("tutorProfileCtrl", ["$scope", "$http", "$timeout", function($scope, $http, $timeout) {

    $scope.newRegisterForm=false;
    $scope.tutor={};
    $scope.copyTutor={};
    $scope.labelName;
    $scope.inputToEdit;
    $scope.countries = null;
    $scope.cities = null;
    $scope.countryId = null;
    $scope.cityId = 0;
    $scope.city = '';
    $scope.showCitySelect = null;  

    $scope.initCountries = function() {
        //load countries
        $http.get("/get_countries")
        .then(function(res){
            $scope.countries = res.data.data;
            $("#selectCountry").select2({
                placeholder: "Seleccione...",
            }); 

            $('#selectCountry').on('select2:select', function (e) {
                var country = e.params.data;
                if(country.id === "42") {
                    $scope.showCitySelect =  true;
                    $("#selectCity").val(null).trigger("change");
                }
                else {
                    if($scope.copyTutor.countryId === "42") { //previous country selected
                        $scope.copyTutor.city_name = '';    
                    }
                    $scope.showCitySelect =  false;
                }
                $scope.copyTutor.city_id = null;
                $scope.copyTutor.city= null; 
                $scope.copyTutor.city_name= null; 
                $scope.copyTutor.department_id = null;
                $scope.copyTutor.countryId = country.id;
                $scope.$apply();
            });

        }).catch(function(er){
            $scope.messageError = 'Error consultando lista de paises';
        });

        //load cities
        $http.get("/get_cities")
        .then(function(res){
            var cities = res.data.data;
            var departments = [];
            function searchDepartment(dptId,dptName){
                for(var i=0;i<departments.length;i++) {
                    if(departments[i].id === dptId)
                        return departments[i];
                }
                var newDept = { id: dptId, text: dptName,children:[] };
                departments.push(newDept);
                return newDept;
            }

            angular.forEach(cities, function(value, key) {
                dpt = searchDepartment(value.department_id,value.department_name);
                dpt.children.push(value);
            });

            $scope.departments = departments;

            $('#selectCity').select2({
                placeholder: "Seleccione...",
                data: departments
            })

            $('#selectCity').on('select2:select', function (e) {
                $cityId = e.params.data.id;
                
              
                var city = null;
                for(var i=0;i<cities.length;i++){
                    city = cities[i];
                    if(city.id === Number($cityId)) {
                        $scope.copyTutor.city = city;
                        $scope.copyTutor.city.name = city.text;
                        $scope.copyTutor.city_name = city.text;
                        $scope.copyTutor.department_id = Number($scope.copyTutor.city.department_id);
                        break;
                    }
                 }
                $scope.$apply();
            });

        }).catch(function(error){
            $scope.messageError = 'Error consultando lista de ciudades';
        });
    }
    $scope.init = function(tutor,statusValidationFreePlan) {

        $scope.tutor=tutor
        $scope.tutor.password1 = ''
        $scope.tutor.password2 = ''
        $scope.newRegisterForm=false
        $('.d-none-result.d-none').removeClass('d-none');
        $scope.initCountries(); 
        if(statusValidationFreePlan == 1){
            swal({
                text:'El plan ha sido registrado correctamente, los estudiantes inscritos puden acceder a este plan' ,
                type: "success",
                showCancelButton: false,
                showConfirmButton: false
            }).catch(swal.noop);
        }else{
            if(statusValidationFreePlan == 2){
                swal({
                    text:'Ya tiene registrado un plan gratuito' ,
                    type: "warning",
                    showCancelButton: false,
                    showConfirmButton: false
                }).catch(swal.noop);
            }
        }

    };
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
    $scope.onChangePassword = () => {
        if($scope.tutor.password2.length !== 0){
            $http({
                url:"/conexiones/validate_password/"+$scope.tutor.password1,
                method: "GET",
            }).
            then(function (response) {
                if(response.data.validation){
                    $http({
                        url:"/conexiones/update_password/",
                        method: "POST",
                        data:{
                            'password1':$scope.tutor.password1,
                            'password2':$scope.tutor.password2,
                        }
                    }).then((response)=>{
                        if(response.data.validation){
                            swal({
                                text:response.data.message ,
                                type: "success",
                                showCancelButton: false,
                                showConfirmButton: false
                            }).catch(swal.noop);
                        }else{
                            swal({
                                text:response.data.message ,
                                type: "warning",
                                showCancelButton: false,
                                showConfirmButton: false
                            }).catch(swal.noop);
                        }
                    }).catch(function (e) {
                        swal({
                            text:'algo salió mal, intente de nuevo' ,
                            type: "warning",
                            showCancelButton: false,
                            showConfirmButton: false
                        }).catch(swal.noop);
                    });
                }else{
                    swal({
                        text:response.data.message ,
                        type: "warning",
                        showCancelButton: false,
                        showConfirmButton: false
                    }).catch(swal.noop);
                }

            }).catch(function (e) {
                $scope.errorMessageFilter = 'Error validando contraseña';
            });
        }else{
            swal({
                text: "No se puede editar, debe completar el campo 'Nueva contraseña'",
                type: "warning",
                showCancelButton: false,
                showConfirmButton: false
            }).catch(swal.noop);
        }

    }
    $scope.registerUserForm = (inputVar)=>{
        $('.d-none-result.d-none').removeClass('d-none');
        window.scrollTo( 0, 0 );
        $scope.newStudent = {};
        $scope.newRegisterForm=true;
        $scope.errorMessageRegister="";
        $scope.inputToEdit=parseInt(inputVar)
        switch (parseInt(inputVar)) {
            case 1:
                $scope.labelName="Nombre"
                $scope.varChange = $( "#div_name" ).text()
                break;
            case 2:
                $scope.labelName="Apellido"
                $scope.varChange = $( "#div_last" ).text()
                break;
            case 3:
                $scope.labelName="Teléfono"
                $scope.varChange = $( "#div_phone" ).text()
                break; 
            case 4:
                $scope.labelName="Localidad"
                $scope.copyTutor.country_id = $scope.tutor.country_id
                $scope.copyTutor.country = $scope.tutor.country
                $scope.copyTutor.city_id = $scope.tutor.city_id
                $scope.copyTutor.city_name = $scope.tutor.city_name
                $timeout(function () {
                    $("#selectCountry").val($scope.tutor.country_id).trigger("change");
                    if($scope.tutor.city_id) {
                        $("#selectCity").val($scope.tutor.city_id).trigger("change");
                    }
                }, 10);
                break; 
        }
    }
    $scope.onEdit = (inputVar) => {
        let columnEdit = '';
        let editInput = false;
        let varChange = null;
        
        if(inputVar <=7 ){
            switch (inputVar) {
                case 1:
                    columnEdit = 'name'
                    $scope.copyTutor.name = $scope.tutor.name
                    $scope.tutor.name = $scope.varChange
                    varChange = $scope.varChange
                    editInput = $scope.valiateInputs()
                    break;
                case 2:
                    columnEdit = 'last_name'
                    $scope.copyTutor.last_name = $scope.tutor.last_name
                    $scope.tutor.last_name = $scope.varChange
                    varChange = $scope.varChange
                    editInput = $scope.valiateInputs()
                    break;
                case 3:
                    columnEdit = 'phone'
                    $scope.copyTutor.phone = $scope.tutor.phone
                    $scope.tutor.phone = $scope.varChange
                    varChange = $scope.varChange
                    editInput = $scope.valiateInputs()
                    break;
                case 4:
                    columnEdit = 'country_id';
                    editInput = true;
                    varChange = $scope.copyTutor.country_id;
                    $scope.tutor.country_id = $scope.copyTutor.country_id;
                    $scope.tutor.country = $scope.copyTutor.country;
                    $scope.tutor.city = $scope.copyTutor.city;
                    $scope.tutor.city_id = $scope.copyTutor.city_id;
                    $scope.tutor.city_name = $scope.copyTutor.city_name;
                    $scope.tutor.department_id = $scope.copyTutor.department_id;
                    $scope.onEdit(5);
                    $scope.onEdit(6);
                    $scope.onEdit(7);
                    break; 
                case 5:
                    columnEdit = 'department_id';
                    editInput = true;
                    varChange = $scope.copyTutor.department_id;
                    break; 
                case 6:
                    columnEdit = 'city_id';
                    editInput = true;
                    varChange = $scope.copyTutor.city ? $scope.copyTutor.city.id : null;
                    break;  
                case 7:
                    columnEdit = 'city_name';
                    editInput = true;
                    varChange = $scope.copyTutor.city_name;
                    break;
            }
            if(editInput){
                $http({
                    url:"/conexiones/edit_column_tutor/",
                    method: "POST",
                    data: {
                        column:columnEdit,
                        data:varChange
                    }
                }).
                then(function (response) {
                    $scope.newRegisterForm=false
                    if(response.status === 200) {
                        swal({
                            text: "Campo editado exitosamente",
                            type: "success",
                            showCancelButton: false,
                            showConfirmButton: false
                        }).catch(swal.noop);
                        $('#tutorProfileFullName').html(`${$scope.tutor.name} ${$scope.tutor.last_name} `);
                    }else{
                        $scope.newRegisterForm=false
                        swal({
                            text: "Algo salió mal, intente de nuevo",
                            type: "warning",
                            showCancelButton: false,
                            showConfirmButton: false
                        }).catch(swal.noop);
                        $scope.presentTutorValues(inputVar)
                    }
                }).catch(function (e) {
                    $scope.newRegisterForm=false
                    alert(JSON.stringify(response));
                    swal({
                        text: "Algo salió mal, intente de nuevo",
                        type: "warning",
                        showCancelButton: false,
                        showConfirmButton: false
                    }).catch(swal.noop);
                    $scope.loadingRegistry = false;
                    $scope.presentTutorValues(inputVar)
                });
            }else{
                switch (inputVar) {
                    case 1:
                        $scope.tutor.name = $scope.copyTutor.name
                        break;
                    case 2:
                        $scope.tutor.last_name = $scope.copyTutor.last_name
                        break;
                    case 3:
                        $scope.tutor.phone = $scope.copyTutor.phone
                        break;
                    case 4:
                        $scope.tutor.country_id = $scope.copyTutor.country_id
                        break;
                    case 5:
                        $scope.tutor.city_id = $scope.copyTutor.city_id
                        break;    
                }
                swal({
                    text: "No se puede editar el campo, debe tener minimo 3 caracteres máximo 20",
                    type: "warning",
                    showCancelButton: false,
                    showConfirmButton: false
                }).catch(swal.noop);
            }

        }
    } 

    $scope.presentTutorValues = (inputVar) => {

        switch (inputVar) {
            case 1:
                $scope.tutor.name = $scope.copyTutor.name
                break;
            case 2:
                $scope.tutor.last_name = $scope.copyTutor.last_name
                break;
            case 3:
                $scope.tutor.phone = $scope.copyTutor.phone
                break;
            case 4:
                $scope.tutor.country = $scope.copyTutor.country
                $scope.tutor.country_id = $scope.copyTutor.country_id
                break;
            case 5:
                $scope.tutor.department_id = $scope.copyTutor.department_id
                break;   
            case 6:
                $scope.tutor.city = $scope.copyTutor.city
                break;                                                 
            case 7:
                $scope.tutor.city_name = $scope.copyTutor.city_name
                break;
        }
    }
    $scope.getFile = () => {
        document.getElementById("upfile").click();
    }

    $scope.valiateInputs = () =>{
        if( $scope.varChange.length >= 3 && $scope.varChange.length <= 20 )
            return true;
        return false;
    }


    $scope.onChangeCountry = () =>{
        var country = null;
        $scope.showCitySelect = false;
        for(var i=0;i<$scope.countries.length;i++){
           country = $scope.countries[i];
           if(country.id === Number($scope.copyTutor.country_id)) {
               $scope.copyTutor.country = country;
               $scope.copyTutor.country.name = country.text;
               if($scope.copyTutor.country.id== 42) {
                   $scope.showCitySelect = true;
               }
               $scope.copyTutor.city = null;
               $scope.copyTutor.city_id = null;
               break;
           }
        }
    }


}]);


