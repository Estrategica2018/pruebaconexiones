MyApp.controller("tutorProductsCtrl", ["$scope", "$http", function($scope, $http,$timeout) {

    $scope.errorMessage = '';
    $scope.products = null;
    $scope.ratingPlans = null;
    
    $scope.init = function() {
        $('.d-none-result').removeClass('d-none');
        
        $http({
            url:"/get_products_tutor/",
            method: "GET",
        }).
        then(function (response) {
            if(response.data) {
                
                $scope.products = setProduct(response.data);
                
                if( $scope.products.length > 0 ) { 
                    swal({
                    text: "Recuerda que para poder visualizar los contenidos, debes ingresar con una inscripción del rol de estudiante!",
                    type: "warning",
                    showCancelButton: false,
                    showConfirmButton: false,
                    }).catch(swal.noop); 
                }
                else { 
                    swal({
                    text: "Aún no cuentas con productos con nosotros, te invitamos a activar el plan gratuito!",
                    type: "warning",
                    showCancelButton: false,
                    showConfirmButton: false,
                    }).catch(swal.noop); 
                }
            }
            else {
                $scope.errorMessage = 'Error consultando los productos asociados';
            }
                
            $('.d-none-result.d-none').removeClass('d-none');
            
        }).catch(function (e) {
            $scope.errorMessage = 'Error consultando los productos asociados';
        });
        
        $http({
            url:"/get_rating_plans",
            method: "GET",
        }).
        then(function (response) {
            $scope.ratingPlans = response.data ? response.data.data || response.data : response;
            
            $scope.ratingPlans = $scope.ratingPlans.map(function(value) {
                value.description_items = value.description_items ?value.description_items.split('|'):[];
                value.name_url_value = value.name.replace(/\s/g,'_').toLowerCase();
              return value;
            });
            
         

          

        }).catch(function (e) {
            $scope.errorMessageFilter = 'Error consultando las secuencias, compruebe su conexión a internet';
        });
    };
    
    function setProduct(data) {
        var listSequences = {};
        for(var i=0, accountService = null, rating_plan_type = null; i < data.length; i++ ) {
           accountService = data[i];
           rating_plan_type = accountService.rating_plan_type;
           
           for(var j=0, content=null, product = null; j<accountService.affiliated_content_account_service.length; j++) {
               content = accountService.affiliated_content_account_service[j];
               product = listSequences[content.sequence.id+'_'+rating_plan_type];
               if(!product) {
                   product = { 
                       "rating_plan_type": rating_plan_type,
                       "sequence": content.sequence,
                       "affiliated_content_account_service": [],
                   };
               }
               listSequences[content.sequence.id+'_'+rating_plan_type] = product;
               product.affiliated_content_account_service.push(content);
           }
        }
        var list = [];
        for(var indx in listSequences) {
           list.push(listSequences[indx]);
        }
        return list;
    }
}]);


