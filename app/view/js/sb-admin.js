$(function() {
    $('#side-menu').metisMenu();
});

//Loads the correct sidebar on window load
$(function() {

    $(window).bind("load", function() {
        if ($(this).width() < 768) {
            $('div.sidebar-collapse').addClass('collapse')
        } else {
            $('div.sidebar-collapse').removeClass('collapse')
        }
    })
})

//Collapses the sidebar on window resize
$(function() {
    $(window).bind("resize", function() {
        if ($(this).width() < 768) {
            $('div.sidebar-collapse').addClass('collapse')
        } else {
            $('div.sidebar-collapse').removeClass('collapse')
        }
    })
})

$(document).ready(function() {
    //animating menus on hover
    $('#side-menu li:not(.nav-header)').hover(function(){
        $(this).animate({
            'margin-left':'+=5'
        },300);
    },
    function(){
        $(this).animate({
            'margin-left':'-=5'
        },300);
    });
});