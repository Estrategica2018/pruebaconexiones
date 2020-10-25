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
        $scope.action='';
        $scope.questionEdit.answer = $('#editorhtml_ifr').contents().find('#tinymce').html() || 'prueba';
        $scope.questionEdit.placeHolderHtml = $('#editorhtml_ifr').contents().find('#tinymce').text();
    }
});
