MyApp.controller("helpPlatformCtrl", function ($scope, $http) {
    
    $scope.loadFinished = true;
    $scope.init = function() {
        $scope.loadFinished = true;
        resizePageCard();
    }

});

$(window).resize(function () {
    resizePageCard();
});
    
function resizePageCard() {
    var card = $('.background-page-card');
    var background = $('.background-page-image');
    var w = Number(card.attr('w'));
    var h = Number(card.attr('h'));
    var newW = Number(card.css('width').replace('px', ''));
    var newH = newW * h / w;
    var deltaX = 1 + (newW - w) / w;
    card.css('height', newH  );
    
    var background = $('.background-page-image');
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
