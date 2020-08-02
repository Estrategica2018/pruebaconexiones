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
            $scope.products = response.data; 
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
            
        
            setTimeout(function () {
                marginLeftText();
             }, 300);
      
             function marginLeftText() {
                $('.trapecio-top').each(function(){ 
                    var width  = $(this).width(); 
                    $(this).find('a span').each(function(){ 
                        var delta =  (width) - 46;
                        $(this).css('margin-left',(delta/2)+'px');  
                    });
                }); 
             }
      
             $( window ).resize(function() {
              marginLeftText();
            });


          

        }).catch(function (e) {
            $scope.errorMessageFilter = 'Error consultando las secuencias, compruebe su conexión a internet';
        });

    };
}]);


