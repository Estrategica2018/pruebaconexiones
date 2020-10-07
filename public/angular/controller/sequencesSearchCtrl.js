MyApp.controller("sequencesSearchCtrl", ["$scope", "$http", function ($scope, $http) {
    $scope.sequences = [];
    $scope.shoppingCarts = [];
    $scope.activesPlan = [];
    $scope.errorMessageFilter = '';
    $scope.searchText = '';
    $scope.areas = [];
    $scope.areaName = null;
    $scope.themesList = [];
    $scope.themeName = null;
    $scope.wordList = null;
    $scope.keywords = [];
    $scope.defaultCompanySequences = 1;
    $scope.responseData = null;
    $scope.ratingPlans = [];

    $scope.init = function(company_id)
    {
        $scope.defaultCompanySequences = company_id;
        $('.d-none-result').removeClass('d-none');
        resizeIcons();
        
        //retrive plan
        $http({
            url: '/get_rating_plans/',
            method: "GET",
        }).
        then(function (response) {
            var data = response.data.data || response.data;
            $scope.ratingPlans = data.filter(function(value){
                 //return !value.is_free && ( (value.type_plan.id === 1 && value.count === 1) || value.type_plan.id === 2 || value.type_plan.id === 3    );
                 return !value.is_free;
            })
        }).catch(function (e) {
            $('.d-none-result').removeClass('d-none');
            $('#loading').removeClass('show');
            $scope.errorMessageFilter = 'Error consultando las secuencias, compruebe su conexión a internet';
        });
        
        $http({
            url:"/get_company_sequences/"+$scope.defaultCompanySequences ,
            method: "GET",
        }).
        then(function (response) {
            
            $scope.sequences = response.data.companySequences;
            $scope.shoppingCarts = response.data.shoppingCarts;
            $scope.activesPlan = response.data.activesPlan;

            $scope.responseData = $scope.sequences;
            
            var value = null;
            for(var i = 0; i<$scope.sequences.length; i++) {
                value = $scope.sequences[i];
                value.name_url_value = value.name.replace(/\s/g,'_').toLowerCase()
                if (value.areas) {
                    angular.forEach(value.areas.split('|'), function (areaName, key) {
                        areaName = (areaName[0] == ' ') ? areaName.substr(1) : areaName;
                        if (!searchArea(areaName)) {
                            $scope.areas.push(areaName);
                        }
                    });
                }
                if (value.themes) {
                    angular.forEach(value.themes.split('|'), function (themeName, key) {
                        themeName = (themeName[0] == ' ') ? themeName.substr(1) : themeName;
                        if (!searchTheme(themeName)) {
                            $scope.themesList.push(themeName);
                        }
                    });
                }
                if (value.keywords) {
                    angular.forEach(value.keywords.split('|'), function (keyword, key) {
                        keyword = (keyword[0] == ' ') ? keyword.substr(1) : keyword;
                        if (!searchKeyword(keyword)) {
                            $scope.keywords.push(keyword);
                        }
                    });
                }
            };
            
            initAutocompleteList();

        }).catch(function (e) {
            $scope.errorMessageFilter = 'Error consultando las secuencias, compruebe su conexión a internet';
        });


    };
    
    $(window).resize(function () {
        resizeIcons();
    });

    function resizeIcons() {
        $('[icon-pedagogy]').each(function(index){
            var left = $(this).position().left - ($(this).width()/2);
            var top = $(this).position().top + 130 ;
            $(this).next().next().css('top',top);
            
            if($(this).attr('id') ==="pedagogy7") {
                left = $(this).position().left - 395  + 100 ;
                $(this).next().next().css('left', left);
                $(this).next().next().css('right',0);
            }
            else if($(this).attr('id') ==="pedagogy8" ) {
                left = $(this).position().left - 395  + 80 ;
                $(this).next().next().css('left', left);
                $(this).next().next().css('right',0);
            }
            else {
                $(this).next().next().css('left',left);
            }
            
            
        });
    }
    
    function searchArea(areaName) {
        for (var i = 0; i < $scope.areas.length; i++) {
            if ($scope.areas[i] === areaName) { return true; }
        }
        return false;
    }
    function searchTheme(themeName) {
        for (var i = 0; i < $scope.themesList.length; i++) {
            if ($scope.themesList[i] === themeName) { return true; }
        }
        return false;
    }
    function searchKeyword(keyword) {
        for (var i = 0; i < $scope.keywords.length; i++) {
            if ($scope.keywords[i] === keyword) { return true; }
        }
        return false;
    }

    $scope.onThemeChange = function () {
        $scope.areaName = null;
        $scope.searchText = '';
        var sequence = null;
        $scope.sequences = [];
        if($scope.responseData) {
            for(var i = 0; i<$scope.responseData.length;i++){
                sequence = $scope.responseData[i];
                if(sequence.themes && sequence.themes.toLocaleUpperCase().includes($scope.themeName.toLocaleUpperCase())) {
                    $scope.sequences.push(sequence);
                     
                }
            };
        }
        
    };
    $scope.onSeachChange = function () {
        $scope.areaName = null;
        $scope.themeName = null;
        $scope.sequences = $scope.responseData;
        
    };
    $scope.onAreaChange = function () {
        $scope.searchText = '';
        $scope.themeName = null;
        var sequence = null;
        $scope.sequences = [];
        if($scope.responseData) {
            for(var i = 0; i<$scope.responseData.length;i++){
                sequence = $scope.responseData[i];
                if(sequence.areas && sequence.areas.toLocaleUpperCase().includes($scope.areaName.toLocaleUpperCase())) {
                    $scope.sequences.push(sequence);
                     
                }
            };
        }    
         
    };

    function initAutocompleteList() {

        var names = $scope.themesList.concat($scope.areas);
        var keywordsList = $scope.keywords.concat(names);
        if($scope.responseData) {
            for(var i = 0; i<$scope.responseData.length;i++){
                keywordsList.push($scope.responseData[i].name);
            }
        }
        $scope.complete=function(event, string){
            
            if (event.key === "Enter" || event.key === "Escape"  ) {
                $scope.wordList = null;
                return;    
            }
            var output=[];
            angular.forEach(keywordsList,function(kw){
                if(kw.toLowerCase().indexOf(string.toLowerCase())>=0){
                    output.push(kw);
                }
            });
            $scope.wordList = output;
        }
        $scope.fillTextbox=function(event, keyword){
            if(event.relatedTarget && event.relatedTarget.id === 'keywordlist'){
                $scope.searchText = event.relatedTarget.text;
            }
            else {
                $scope.searchText = keyword;
            }
            //$scope.searchText = keyword;
            $scope.wordList = null;
        }
    }
 
    $scope.onSequenceBuy = async function (sequence) {
        
        var _continue = await validateSequencesActivate(sequence.id, $scope.shoppingCarts, $scope.activesPlan);
        
        if(_continue) {
            var ratingPlans = '';
            for(var i = 0; i < $scope.ratingPlans.length; i++) {
                var rt = $scope.ratingPlans[i];
                if(!rt.is_free) {
                    var listItem = rt.description_items.split('|');
                    var items = '';
                    for(var j=0;j<listItem.length;j++) {
                        items += '<li style="line-height: 17px;" class="card-rating-plan-id-'+ (i+1) +' fs-2 small pr-0 mt-4 ml-3"><span class="color-gray-dark font-14px font-family ">' + listItem[j] + '</span></li>';
                    }
                   var name = rt.name ? rt.name.replace(/\s/g,'_').toLowerCase() : '';
                   var href = '/plan_de_acceso/' + rt.id + '/' + name + '/' + sequence.id;
                   var button =   '<div onclick="location=\''+href+'\'" class="cursor-pointer w-100 trapecio-top  card-rating-button-id-'+ (i+1)  +'" style= "right: 12%;box-shadow: 0px 0px 0px 0px rgb(255 255 255), 0px -2px 0px rgba(255, 255, 255, 0.3);">'+
                   '<a href="'+href+'" style="margin-left: -14px;"> <span class="fs-0" style="color: white;top: -23px;position: relative;">Adquirir</span> </a> </div> ';

                   var message = 'por '+rt.count+' guía de aprendizaje';
                   if(rt.type_plan.id === 2) {
                       message = 'Por momentos individuales';
                   }
                   if(rt.type_plan.id === 3) {
                       message = 'Por experiencias individuales';
                   }

                   ratingPlans += '<div class="pb-3 pl-3 pr-3 card-rating-id-' + (i+1) + ' "><div class="card-header card-rating-background-id-' + (i+1) + ' mt-3 fs--3 flex-100 box-shadow ">'+
                    '<h5 class="card-title pl-lg-3 pr-lg-3 mb-0 font-weight-bold card-rating-plan-id-'+ (i+1) +'" style="color: white;">'+rt.name+'</h5></div>'+
                    '<div class="card-body bg-light ratinPlanCard pr-2 pl-2 pb-0 w-100 box-shadow " style="min-height: 165px;"><ul class=" p-0 ml-2 text-left fs-2 mb-auto">' + items + '</ul></div>'+
                    '<div class="row no-gutters card-footer card-rating-background-id-' + (i+1) + ' font-weight-bold text-align box-shadow " style="color: white;">'+
                    ' <div class="col-5"> $'+rt.price+' USD  </div> <div class="pl-lg-1 pr-lg-1 col-7 font-14px" style="    max-width: 176px; margin-top:-10px"> '+ message +' </div></div>'+  button+'</div>';
                }
            }
            var html = '<div class="row justify-content-center p-3">' + ratingPlans + '</div>';
            swal({
                html: html,
                customClass: 'container-alert-plans m-auto',
                width: '100%',
                showConfirmButton: false, showCancelButton: false
            }).catch(swal.noop);
            $('.swal2-show').css('background-color','transparent');            
        }
    }

    function validateSequencesActivate(sequence_id, shoppingCarts, activesPlan) {
         return new Promise(resolve => {
            var mbControl = false; 
            var message = '';
            if(activesPlan && activesPlan.affiliated_account_services) {
                for(var i=0, account;i<activesPlan.affiliated_account_services.length; i++) {
                    account = activesPlan.affiliated_account_services[i];
                    if(account.affiliated_content_account_service[0].sequence_id === sequence_id){
                        mbControl = true; 
                        message = 'Ya tienes contratada esta guía de aprendizaje, deseas adquirirla de nuevo?';
                        break;
                    }
                }
            }
            if(!mbControl)
            for(var i=0, sc=null;i<shoppingCarts.length; i++) {
                sc = shoppingCarts[i];
                for(var j=0, product=null;j<sc.shopping_cart_product.length; j++) {
                    product = sc.shopping_cart_product[j];
                    if( ( product.sequence && product.sequence.id === sequence_id) || 
                        ( product.sequenceStruct_experience && product.sequenceStruct_experience.id === sequence_id ) ||
                        ( product.sequenceStruct_moment && product.sequenceStruct_moment.id === sequence_id )) {
                        mbControl = true; 
                        message = 'Ya tienes asignada esta guía en el carrito de compras, deseas adicionarla de nuevo?';
                        break;
                    }
                }
            }
            
            if(mbControl) {
                swal({
                    html: '<strong> '+ message+' </strong>',
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: '#748194',
                    confirmButtonClass: 'mr-4',
                    confirmButtonColor: '#2c7be5',
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar', 
                })
                .then((result) => {
                    if (result) {
                        resolve(true);
                    }
                    resolve(false);
                }).catch(swal.noop);                
            }
            else {
                resolve(true);
            }
        });
    }
    
    $scope.setPositionScroll = function () {
        
        if(window.scrollY <= 50) {
            var eTop = $('#divSearch').offset().top;
            window.scrollTo( 0, eTop - 80 );
        }
    }

    $scope.onIconPedagogy = function(icon) {
        if($scope.icon_pedagogy === icon)  {
            $scope.icon_pedagogy = '';
        }
        else {
            $scope.icon_pedagogy = icon;
        }
    }
    
}]);
