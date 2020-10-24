MyApp.controller("contentSequencesStudentCtrl", ["$scope", "$http", function ($scope, $http) {

    $scope.errorMessage = null;
    $scope.sequences = null;
    $scope.evidenceOpened = null;
    $scope.indexQuestion = 0;
    $scope.optionSelected = false;
    $scope.companyId = null;
    $scope.accountServiceId = null;
    $scope.sequenceId = null;
    $scope.momentId = null;
    $scope.sectionId = null;
    $scope.onFinishEvidenceLoad = false;
    $scope.maxAttempts = 3;
    $scope.attempts = 1;
    $scope.disabledEvidence = false;
    $scope.letters = ['a','b','c','d','e','f','g','h','i','j','k','l','m'];
    

    $scope.init = function (companyId, companyNick, sequenceId, accountServiceId) {
        $scope.companyId = companyId;
        $scope.accountServiceId = accountServiceId;
        $('.d-none-result').removeClass('d-none');
        getAvailableSequences(companyId, companyNick, sequenceId,accountServiceId);
    }

    $scope.onClickEvidence = function(sequenceId,momentId,sectionId,experience_id,icon,subtitle,partId) {

        window.scrollTo( 0, 0 );

        $scope.evidenceOpened = {};
        $scope.evidenceOpened.icon = icon || 'images/icons/evidenciasAprendizajeIcono-01.png';
        $scope.evidenceOpened.subtitle = subtitle || 'Evidencias de aprendizaje';
        
        
        $scope.indexQuestion = 0;
        $scope.sequenceId = sequenceId;
        $scope.momentId = momentId;
        $scope.sectionId = sectionId;
        $scope.partId = partId;
        $scope.experience_id = experience_id;
        $scope.optionSelected = false;
        $('#' + $scope.experience_id + ' img').addClass('d-none');
        $('#' + $scope.experience_id + ' span').removeClass('d-none');
        $http({
            url: "/get_questions/"+sequenceId+"/"+momentId+"/"+experience_id+"/"+$scope.accountServiceId,
            method: "GET",
        }).
        then(function (response) {
            $scope.evidenceOpened.questions = response.data.data || [];
            for(var i=0; i<$scope.evidenceOpened.questions.length;i++) {
                $scope.evidenceOpened.type_answer = $scope.evidenceOpened.questions[i].type_answer;
                $scope.evidenceOpened.questions[i].options = JSON.parse($scope.evidenceOpened.questions[i].options);
                $scope.evidenceOpened.questions[i].optionSelected = false;
            }
            $('#' + $scope.experience_id + ' img').removeClass('d-none');
            $('#' + $scope.experience_id + ' span').addClass('d-none');
            
            if($scope.evidenceOpened.type_answer === 2) {
                $scope.attempts = response.data && response.data.rating && response.data.rating.attempts ? response.data.rating.attempts : 0;
                if($scope.attempts >= $scope.maxAttempts ) {
                    $scope.attempts = $scope.maxAttempts;
                    $scope.disabledEvidence = true;
                }
                else {
                    $scope.attempts ++;
                    $scope.disabledEvidence = false;
                }
            }
            
            
        }).catch(function (e) {
            $scope.errorMessage = 'Error consultando las preguntas';
            swal('Conexiones', $scope.errorMessage, 'error');
            $('#' + $scope.experience_id + ' img').removeClass('d-none');
            $('#' + $scope.experience_id + ' span').addClass('d-none');
        });
    }

    $scope.closeEvidence = function() {
        $scope.evidenceOpened = null;
        $scope.experience_id = null;
    }
    
    $scope.onSelectOption = function(question,option) {
        question.optionSelected  = option;
    }
    
    $scope.nextQuestion = function() {
        $scope.indexQuestion++;
        window.scrollTo( 0, 0 );
    }
    
    $scope.backQuestion = function() {
        $scope.indexQuestion--;
        window.scrollTo( 0, 0 );
    }
    
    $scope.onFinishEvidence = function() {
        if($scope.disabledEvidence) {
            swal('Prueba concluida', 'Has alcanzado el número máximo de intentos para desarrollar esta prueba!!', 'error');
            return;
        }
        var questionsAnswers = [];
        var answer = null;
        $scope.onFinishEvidenceLoad = true;
        
        for(var i=0;i<$scope.evidenceOpened.questions.length;i++) {
            if ($scope.evidenceOpened.questions[i].optionSelected.id === undefined) {
                swal('Respuestas', 'Por favor responda todas las preguntas antes de finalizar!!', 'error');
                $scope.onFinishEvidenceLoad=false;
                return
            } 
            answer = { "question_id": $scope.evidenceOpened.questions[i].id,
                       "answer": $scope.evidenceOpened.questions[i].optionSelected.id
            };
            questionsAnswers.push(answer);
        }
        var data = {
            "questions_answers":questionsAnswers,
            "company_id": $scope.companyId,
            "affiliated_account_service_id": $scope.accountServiceId,
            "sequence_id": $scope.sequenceId,
            "moment_id": $scope.momentId,
            "experience_id": $scope.experience_id,
            "section": $scope.sectionId
        };
        $http({
            url: "/register_update_answer/",
            method: "POST",
            data: data
        }).
        then(function (response) {
            swal('Conexiones', 'Ya hemos recibido las respuestas. El reporte de desempeño llegará al correo registrado', 'success');
            $scope.evidenceOpened = null;
            $scope.indexQuestion = 0;
            $scope.onFinishEvidenceLoad = false;
            
        }).catch(function (e) {
            $scope.errorMessage = e.data.message || 'Error guardando las respuestas';
            swal('Conexiones', $scope.errorMessage, 'error');
            $('#' + $scope.experience_id + ' img').removeClass('d-none');
            $('#' + $scope.experience_id + ' span').addClass('d-none');
            $scope.onFinishEvidenceLoad = false;
        });
    }
    
    function getAvailableSequences(companyId,companyNick, sequenceId, accountServiceId) {
        $http({
            url: "/"+companyNick+"/get_available_sequences/" + companyId + "/" + accountServiceId,
            method: "GET",
        }).
            then(function (response) {
                var accountService = response.data[0];
                $('.d-result').removeClass('d-none');
                resizeSequenceCard();
                $('#loading').addClass('d-none');
                
                if ( Number(accountService.rating_plan_type) === 3 ) {
                    $('#section_type_question').addClass('disabled-section');
                    $('#section_type_question').removeAttr('href');
                    $('#section_type_question + div').find('a').addClass('disabled-section');
                    $('#section_type_question + div').find('a').removeAttr('href','');
                    
                    
                    $('#section_type_science').addClass('disabled-section');
                    $('#section_type_science').removeAttr('href');
                    $('#section_type_science + div').find('a').addClass('disabled-section');
                    $('#section_type_science + div').find('a').removeAttr('href');
                    
                    $('#section_type_connection').addClass('disabled-section');
                    $('#section_type_connection').removeAttr('href');
                    $('#section_type_connection + div').find('a').addClass('disabled-section');
                    $('#section_type_connection + div').find('a').removeAttr('href');
                    
                }
                
                $('.button-moment-validate[conx-action]').each(function (index, value) {
                    var momentId = Number($(value).attr('conx-action').split('|')[1]);
                    for (var i = 0; i < accountService.affiliated_content_account_service.length; i++) {
                        scp = accountService.affiliated_content_account_service[i];
                        if (scp.moment_id === momentId) {
                            $(this).removeClass('cursor-not-allowed');
                            $(this).attr('disabled', false);
                            $(this).prop('disabled', false);
                        }
                    }
                })
            }).catch(function (e) {
                $scope.errorMessage = 'Error consultando las secuencias, compruebe su conexión a internet';
                swal('Conexiones', $scope.errorMessage, 'error');
                $('.d-result').removeClass('d-none');
                $('#loading').addClass('d-none');
            });
    }
    
    $(window).resize(function () {
        resizeSequenceCard();
    });

    var hiddenSideMenu = function () {
        $('#sidemenu-sequences-button').removeClass('fa-caret-square-left');
        $('#sidemenu-sequences-button').addClass('fa-caret-square-right');
        $('#sidemenu-sequences-empty').addClass('show');
        $('#sidemenu-sequences-empty').removeClass('d-none');

        $('#sidemenu-sequences-content').addClass('d-none');
        $('#sidemenu-sequences-content').removeClass("show");
        $('#sidemenu-sequences-content').removeClass("d-md-block");

        $('#sidemenu-tools-content').addClass('d-none');
        $('#sidemenu-tools-content').removeClass("show");
        $('#sidemenu-tools-content').removeClass("d-md-block");

        $('#sidemenu-sequences').addClass("col-md-0_5");
        $('#sidemenu-sequences').removeClass("col-md-3");

        $('#content-section-sequences').removeClass("col-md-9");
        $('#content-section-sequences').addClass("col-md-11_5");

        resizeSequenceCard();
    };
    var showSideMenu = function () {
        $('#sidemenu-sequences-empty').removeClass('show');
        $('#sidemenu-sequences-empty').addClass('d-none');

        $('#sidemenu-sequences-content').removeClass('d-none');
        $('#sidemenu-sequences-content').addClass("show");
        $('#sidemenu-sequences-content').addClass("d-md-block");

        $('#sidemenu-tools-content').removeClass('d-none');
        $('#sidemenu-tools-content').addClass("show");
        $('#sidemenu-tools-content').addClass("d-md-block");

        $('#sidemenu-sequences-button').addClass('fa-caret-square-left');
        $('#sidemenu-sequences-button').removeClass('fa-caret-square-right');

        $('#sidemenu-sequences-hidden-side').removeClass("d-none");
        $('#sidemenu-sequences-content').removeClass("d-none");
        $('#sidemenu-sequences-empty').addClass("d-none");

        $('#sidemenu-tools-content').addClass("show");
        $('#sidemenu-tools-content').removeClass("d-none");

        $('#sidemenu-sequences').removeClass("col-md-0_5");
        $('#sidemenu-sequences').addClass("col-md-3");

        $('#content-section-sequences').addClass("col-md-9");
        $('#content-section-sequences').removeClass("col-md-11_5");

        resizeSequenceCard();
    }
    $scope.toggleSideMenu = function () {

        if ($('#sidemenu-sequences-button').hasClass('fa-caret-square-left')) {
            hiddenSideMenu();
        }
        else if ($('#sidemenu-sequences-button').hasClass('fa-caret-square-right')) {
            showSideMenu();
        }
        resizeSequenceCard();
    };

}]);

function resizeSequenceCard() {
    var card = $('.background-sequence-card');
    var background = $('.background-sequence-image');
    var w = Number(card.attr('w'));
    var h = Number(card.attr('h'));
    var newW = Number(card.css('width').replace('px', ''));
    var newH = newW * h / w;
    var deltaX = 1 + (newW - w) / w;
    card.css('height', newH  );
    var background = $('.background-sequence-image');
        background.css('width', card.css('width'));
        background.css('height', card.css('height'));
        

    $(card).find('[fs]').each(function (value, key) {
        var fs = $(this).attr('fs');
        var newFs = fs * deltaX;
        $(this).css('font-size', newFs + 'px');
    });

    $(card).find('[mt]').each(function (value, key) {
        var mt = $(this).attr('mt');
        if(mt.includes('%')) {
            $(this).css('top', mt);
        }
        else {
            var newMt = (mt * deltaX);
            $(this).css('top', newMt + 'px');
        }
        $(this).addClass('position-absolute');
    });

    $(card).find('[ml]').each(function (value, key) {
        
        var ml = $(this).attr('ml');
        if(ml.includes('%')) {
            $(this).css('left', ml);
        }
        else {
            var newMl = (ml * deltaX);
            $(this).css('left', newMl + 'px');
        }
        $(this).addClass('position-absolute');
    });

    $(card).find('[w]').each(function (value, key) {
        if ($(this).attr('w') === 'auto') {
            $(this).css('width', 'auto');
        }
        else {
            var w = Number($(this).attr('w')) * deltaX;
            $(this).addClass('position-absolute');
            $(this).css('width', w + 'px');
        }
    });

    $(card).find('[h]').each(function (value, key) {
        if ($(this).attr('h') === 'auto') {
            $(this).css('height', 'auto');
        }
        else {
            var h = Number($(this).attr('h')) * deltaX;
            $(this).addClass('position-absolute');
            $(this).css('height', h + 'px');
        }
    });
}
