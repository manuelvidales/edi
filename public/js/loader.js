$(document).ready(function(){
    var screen = $('#loading-screen');
    configureLoadingScreen(screen);
}); 

function configureLoadingScreen(screen){
    $(document)
        .ajaxStart(function () {
            screen.fadeIn();
        })
        .ajaxStop(function () {
            screen.fadeOut();
        });
}