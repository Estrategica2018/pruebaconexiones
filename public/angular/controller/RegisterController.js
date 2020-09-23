MyApp.controller("registerController", ["$scope", "$http", "$templateCache", function($scope, $http, $templateCache) {
    
    $scope.countries = null;
    $scope.cities = null;
    $scope.countryId = null;
    $scope.cityId = 0;
    $scope.city_name = '';
    $scope.name = '';
    $scope.email = '';
    $scope.departmentId = null;
    $scope.showCitySelect = true;
    $scope.messageError = null;
    $scope.registrer_ini = true;
	$scope.termsConditions = $('#terms').html();
    
   $scope.init = function(errorEmailSocial,email) {
        if(errorEmailSocial){
            swal('Conexiones',`El correo ${email} ya existe en educonexiones.`,'warning');
        }
        
        $('.d-none-result').removeClass('d-none');

    };
    $http.get("https://geoip-db.com/json/").then(function (response1) {
        $scope.country_code = response1.data.country_code;
        $http.get("/get_countries")
        .then(function(res){
            $scope.countries = res.data.data;
            $("#selectCountry").select2({
                placeholder: "Seleccione...",
            });
            
            setTimeout(function(){
             if($scope.country_code === 'CO') {
                $("#selectCountry").val("42").trigger("change");
                $scope.countryId = "42";
                $scope.showCitySelect = true;
                $scope.$apply();
            }else{
                $scope.showCitySelect =  false;
                $scope.city_id = null;
                $scope.departmentId = null;
            }
            }, 100);

            $('#selectCountry').on('select2:select', function (e) {
                var country = e.params.data;
                if(country.id === "42") {
                    $scope.showCitySelect =  true;
                    $scope.city_id = null;
                    $scope.city_name = '';
                    $("#selectCity").val(null).trigger("change");
                }
                else {
                    if($scope.countryId === "42") { //previous country selected
                        $scope.city_name = '';    
                    }
                    $scope.showCitySelect =  false;
                    $scope.city_id = null;
                    $scope.departmentId = null;
                }
                $scope.countryId = country.id;
                $scope.$apply();
            });
        }).catch(function(er){
            $scope.messageError = 'Error consultando lista de paises';
        });
    }).catch(function(e){
        $scope.messageError = 'Error consultando el pais';
    });

    
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
            var city = e.params.data;
            $scope.departmentId = Number(city.department_id);
            $scope.city_name = city.text;
            $scope.city_id = city.id;
            $scope.$apply();
        });

    }).catch(function(error){
        $scope.messageError = 'Error consultando lista de ciudades';
    });

    $scope.onTermsConditions = function() {
        swal({
            title:'', 
            customClass: 'w-100 w-md-75 w-lg-50',
            html: $scope.termsConditions,
            allowOutsideClick: true,
            showCancelButton: false,
            showConfirmButton: false,
        });
    }
    
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

}]);


