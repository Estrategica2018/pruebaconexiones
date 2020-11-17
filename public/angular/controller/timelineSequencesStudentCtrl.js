MyApp.controller("timelineSequencesStudentCtrl", ["$scope", "$http","refresTimeLine", function ($scope, $http,refresTimeLine) {
    
    $scope.sequences = null;
    $scope.errorMessage = null;
    $scope.alertProgress = []
    $scope.init = function(company_id,account_service_id,sequence_id)    {
        
        refresTimeLine(company_id,account_service_id,sequence_id,$scope);
        
    }

}]);

MyApp.factory('refresTimeLine', ['$http', function($http) {
   
   return function(company_id,account_service_id,sequence_id,$scope) {
        $http({
            url:"/get_advance_line/"+account_service_id+'/'+sequence_id,
            method: "GET",
        }).
        then(function (response) {

            let moments = response.data.moments;
            let complete = '#FFD400';
            let incomplete = '#FFEE99';
            let partial = '#F9FAFD';
            let none = '#494b9a';
            let notAvailable = 'gray';

            var moment = null;
            var items = [];
            for(moment_orderAx in moments) {
                moment = moments[moment_orderAx];
                if(moment.progress >= 75){
                    for (section_orderAx in moment.sections) {
                        if(moment.sections[section_orderAx].progress === 89 ){
                            items.push({
                                text: 'Prueba saber '+ moment.sections[section_orderAx].nombre + ' del momento ' +moment.order,
                                name:moment.sections[section_orderAx].nombre,
                                moment:moment.order,
                                section:section_orderAx,
                                moment_id:moment.moment_id,
                                account_service_id:account_service_id,
                                sequence_id:sequence_id
                            })
                        }
                        else if(moment.sections[section_orderAx].progress === -1 ){
                            items.push({
                                text: 'Cursar por '+ moment.sections[section_orderAx].nombre + ' del momento ' +moment.order,
                                name:moment.sections[section_orderAx].nombre,
                                moment:moment.order,
                                section:section_orderAx,
                                moment_id:moment.moment_id,
                                account_service_id:account_service_id,
                                sequence_id:sequence_id
                            })
                        }
                    }
                }
            }
            $scope.alertProgress = items;
            var moment = null;
            for(moment_order in moments) { 
                moment = moments[moment_order];
                
                let fill_color = none;
                if(!moment.isAvailable) {
                    fill_color = notAvailable;
                    for(var section_id=1; section_id<=4; section_id++) {
                        $(`.circle${moment.order}${section_id}`).attr('fill', fill_color);
                        $(`.circle${moment.order}${section_id}`).attr('opacity', '0.5');
                        $(`.circle${moment.order}${section_id}`).parent().css('cursor', 'not-allowed');
                        $(`.circle${moment.order}${section_id}`).parent().find('text').css('cursor', 'not-allowed');
                        $(`.circle${moment.order}${section_id}`).parent().find('a').attr('href', '#');
                    }
                    $(`.star${moment.order}`).attr('fill', fill_color);
                    $(`.star${moment.order}`).attr('stroke', fill_color);
                    $(`.star${moment.order}`).attr('opacity', '0.5');
                }
                else if(moment.progress === 100) {
                    fill_color = complete;
                    for(var section_id=1; section_id<=4; section_id++) {
                        $(`.circle${moment.order}${section_id}`).attr('fill', fill_color);
                        $(`.circle${moment.order}${section_id}`).attr('opacity', '1');
                    }
                    $(`.star${moment.order}`).attr('fill', fill_color);
                    $(`.star${moment.order}`).attr('stroke', fill_color);
                    $(`.star${moment.order}`).attr('opacity', '1');
                    $(`.number${moment.order}`).attr('stroke', '#FFFFFF');
                    
                }
                else if(moment.progress > 0) {
                    fill_color = complete;
                    for(var section_id=1,section=null; section_id<=4; section_id++) {
                        section = moment.sections[section_id];
                        if(section.progress === 100 ){
                            $(`.circle${moment.order}${section_id}`).attr('fill', fill_color);
                            $(`.circle${moment.order}${section_id}`).attr('opacity', '1');
                        }
                        else if(section.progress > 0 ){
                            $(`.circle${moment.order}${section_id}`).attr('fill', fill_color);
                            $(`.circle${moment.order}${section_id}`).attr('opacity', '0.4');
                        }
                        else {
                            fill_colo2 = none;
                        }
                        
                    }
                    $(`.star${moment.order}`).attr('fill', incomplete);
                    $(`.star${moment.order}`).attr('stroke', fill_color);
                    $(`.star${moment.order}`).attr('opacity', '1');
                    $(`.number${moment.order}`).attr('stroke', '#FFFFFF');
                }
            }
        }).catch(function (e) {
            //$scope.errorMessage = 'Error consultando las secuencias, compruebe su conexión a internet';
        });
   };
 }]);