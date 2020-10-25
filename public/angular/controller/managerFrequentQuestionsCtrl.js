MyApp.controller("managerFrequentQuestionsCtrl", function ($scope, $http, $timeout) {
    
    $scope.init = function () {    
        
        $('.result-finish-done').removeClass('d-none');
        $('#editorhtml').html('');
        if(tinymce.get('editorhtml'))
        $(tinymce.get('editorhtml').getBody()).html('');
        
        tinymce.init({
          selector: '#editorhtml',
          height: 500,
          plugins: [
            'link image imagetools table spellchecker lists'
          ],
          contextmenu: "link image imagetools table spellchecker lists",
          content_css: "body { color: #E15433; }"
        });
         
        $http({
            url:"/get_frequent_questions/",
            method: "GET",
        }).
        then(function (response) { 
            $scope.frequentQuestions = response.data.data;

        }).catch(function (e) {
            $scope.errorMessageFilter = 'Error consultando las preguntas frecuentes. ' + JSON.stringify(e);
        });
    }
    
    $scope.newQuestion = function() {
        $scope.questionEdit = {};
        $scope.action = 'newQuestion';
        //$('.tox.tox-tinymce').remove();
        $('#editorhtml').html('');
        if(tinymce.get('editorhtml'))
        $(tinymce.get('editorhtml').getBody()).html('');
        
        tinymce.init({
          selector: '#editorhtml',
          height: 500,
          plugins: [
            'link image imagetools table spellchecker lists'
          ],
          contextmenu: "link image imagetools table spellchecker lists",
          content_css: "body { color: #E15433; }"
        });
    }

    $scope.editQuestion = function(question) {
        $scope.action = 'modifyQuestion';
        $scope.questionEdit = question;
        var title = $scope.questionEdit.answer;
        //$('.tox.tox-tinymce').remove();
        $('#editorhtml').html(title);
        if(tinymce.get('editorhtml'))
        $(tinymce.get('editorhtml').getBody()).html(title);
        
        tinymce.init({
          selector: '#editorhtml',
          height: 500,
          plugins: [
            'link image imagetools table spellchecker lists'
          ],
          contextmenu: "link image imagetools table spellchecker lists",
          content_css: "body { color: #E15433; }"
        });
    }
    
    $scope.saveQuestion = function(question) {
        
        $scope.questionEdit.answer = $('#editorhtml_ifr').contents().find('#tinymce').html() || 'prueba';
        $scope.questionEdit.placeHolderHtml = $('#editorhtml_ifr').contents().find('#tinymce').text();
        $('#spin_loader').removeClass('d-none');
        
        $http({
            url: '/conexiones/admin/update_or_crate_question',
            method: 'POST',
            data: question
        }).
        then(function (response) {
            $('#spin_loader').addClass('d-none');
            $scope.action='';
            swal({
                title: 'Conexiones', 
                html:'<h6>Pregunta guardada correctamente</h6>',
                type: 'success',
            }).catch(swal.noop);
            $scope.init();
            
        }).catch(function (e) {
            $scope.errorMessage = 'Error guardando la pregunta';
            swal('Conexiones', $scope.errorMessage, 'error');
            $('#spin_loader').addClass('d-none');
        });
        
    }

    $scope.deleteQuestion = function(question) {
        
        swal({
            html: "<h6>Estás seguro de borrar esta pregunta? </h6>",
            showCancelButton: true,
            confirmButtonText: "Borrar",
            cancelButtonText: "Cancelar", 
        })
        .then((result) => {
            if (result) {
                $http({
                    url: '/conexiones/admin/delete_question',
                    method: 'POST',
                    data: question
                }).
                then(function (response) {
                    $('#spin_del_loader').addClass('d-none');
                    $scope.action='';
                    swal({
                        title: 'Conexiones', 
                        html:'<h6>Pregunta borrada correctamente</h6>',
                        type: 'success',
                    }).catch(swal.noop);
                    $scope.init();
                    
                }).catch(function (e) {
                    $scope.errorMessage = 'Error borrando la pregunta';
                    swal('Conexiones', $scope.errorMessage, 'error');
                    $('#spin_del_loader').addClass('d-none');
                });        
            }
        }).catch(swal.noop);
        
    }
});
