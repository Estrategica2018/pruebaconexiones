MyApp.controller("sequencesSearchCtrl", ["$scope", "$http", function ($scope, $http) {
    $scope.sequences = [];
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
        $('[icon-pedagogy]').each(function(index){
            var left = $(this).position().left - ($(this).width()/2);
            var top = $(this).position().top + 130;
            $(this).next().next().css('left',left);
            $(this).next().next().css('top',top);
        });
        
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

    };
    
    $(window).resize(function () {
        $('[icon-pedagogy]').each(function(index){
            var left = $(this).position().left - ($(this).width()/2);
            var top = $(this).position().top + 130 ;
            $(this).next().next().css('left',left);
            $(this).next().next().css('top',top);
        });
    });
    
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
    $http({
        url:"/get_company_sequences/"+$scope.defaultCompanySequences ,
        method: "GET",
    }).
    then(function (response) {
        
        $scope.sequences = response.data.companySequences;
        $scope.responseData = $scope.sequences;
        
        var value = null;
        for(var i = 0; i<$scope.sequences.length; i++) {
            value = $scope.sequences[i];
            value.name_url_value = value.name.replace(/\s/g,'_').toLowerCase()
            if (value.areas) {
                angular.forEach(value.areas.split(','), function (areaName, key) {
                    areaName = (areaName[0] == ' ') ? areaName.substr(1) : areaName;
                    if (!searchArea(areaName)) {
                        $scope.areas.push(areaName);
                    }
                });
            }
            if (value.themes) {
                angular.forEach(value.themes.split(','), function (themeName, key) {
                    themeName = (themeName[0] == ' ') ? themeName.substr(1) : themeName;
                    if (!searchTheme(themeName)) {
                        $scope.themesList.push(themeName);
                    }
                });
            }
            if (value.keywords) {
                angular.forEach(value.keywords.split(','), function (keyword, key) {
                    keyword = (keyword[0] == ' ') ? keyword.substr(1) : keyword;
                    if (!searchKeyword(keyword)) {
                        $scope.keywords.push(keyword);
                    }
                });
            }
        };

        
        initAutocompleteList();
        
        setTimeout(function(){
            ellipsizeTextBox();
        }, 1000);
        

    }).catch(function (e) {
        $scope.errorMessageFilter = 'Error consultando las secuencias, compruebe su conexión a internet';
    });

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
        setTimeout(function(){
            ellipsizeTextBox();
        }, 100);
    };
    $scope.onSeachChange = function () {
        $scope.areaName = null;
        $scope.themeName = null;
        $scope.sequences = $scope.responseData;
        setTimeout(function(){
            ellipsizeTextBox();
        }, 100);
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
        setTimeout(function(){
            ellipsizeTextBox();
        }, 100);    
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

    function ellipsizeTextBox() {
        /*if($scope.sequences && $scope.sequences.length ){
            for(var i=0;i<$scope.sequences.length;i++) {
                var el = document.getElementById('sequence-description-'+$scope.sequences[i].id);
                if(!el) continue;
                var wordArray = el.innerHTML.split(' ');
                while(el.scrollHeight > el.offsetHeight) {
                    wordArray.pop();
                    el.innerHTML = wordArray.join(' ') + '...';
                 }
            }
            
        }*/
        
    }
    
    $scope.onSequenceBuy = function (sequence) {
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
               var button =   '<div onclick="location=\''+href+'\'" class="cursor-pointer w-75 trapecio-top position-absolute card-rating-button-id-'+ (i+1)  +'" style= "right: 12%;box-shadow: 0px 0px 0px 0px rgb(255 255 255), 0px -2px 0px rgba(255, 255, 255, 0.3);">'+
               '<a href="'+href+'" style="margin-left: -14px;"> <span class="fs-0 mt-2" style="position: absolute;top: -30px;color: white; ">Adquirir</span> </a> </div> ';
			   
			   var message = 'por '+rt.count+' guía de aprendizaje';
			   if(rt.type_plan.id === 2) {
				   message = 'Por momentos individuales';
			   }
			   if(rt.type_plan.id === 3) {
				   message = 'Por experiencias individuales';
			   }

               ratingPlans += '<div class="mt-3 col-12 col-md-6 col-lg-4 "><div class="card-header card-rating-background-id-' + (i+1) + ' mt-3 fs--3 flex-100 box-shadow ">'+
                '<h5 class="font-weight-bold card-rating-plan-id-'+ (i+1) +'" style="color: white;">'+rt.name+'</h5></div>'+
                '<div class="card-body bg-light pr-2 pl-2 pb-0 w-100 box-shadow " style="min-height: 192px;"><ul class=" p-0 ml-2 text-left fs-2 mb-auto">' + items + '</ul>'+  button+'</div>'+
                '<div class="row no-gutters card-footer card-rating-background-id-' + (i+1) + ' font-weight-bold text-align box-shadow " style="color: white;">'+
                ' <div class="col-5"> $'+rt.price+' USD  </div> <div class="col-7"> '+ message +' </div></div></div>';
            }
        }
        var html = '<div class="row justify-content-center p-3">' + ratingPlans + '</div>';
        swal({
            html: html,
            width: '75%',
            showConfirmButton: false, showCancelButton: false
        }).catch(swal.noop);
        $('.swal2-show').css('background-color','transparent');


        setTimeout(function () {
            marginLeftText();
         }, 300);
  
         function marginLeftText() {
            $('.trapecio-top').each(function(){ 
                var width  = $(this).width(); 
                $(this).find('a span').each(function(){ 
                    var delta =  (width) - $(this).width();
                    $(this).css('margin-left',(delta/2)+'px');  
                });
            }); 
         }
  
         $( window ).resize(function() {
          marginLeftText();
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
