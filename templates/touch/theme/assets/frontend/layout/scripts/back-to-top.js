$(document).ready(function(){
	var id_button_top = '#scrolltop';
	$(id_button_top).hide();
	var id_buttom_bottom = '#scrollbottom';
    var top = 50;
    var duration = 500;
	var bottom = $(document).height()-$(window).height();
    if($(window).height() >= bottom){
        $(id_buttom_bottom).hide();
    }
    $(window).scroll(function() {
        if ($(this).scrollTop() > top) {
            $(id_button_top).fadeIn(duration);
        } else {
            $(id_button_top).fadeOut(duration);
        }
		if($(this).scrollTop() < bottom - top){
			$(id_buttom_bottom).fadeIn(duration);
        } else {
            $(id_buttom_bottom).fadeOut(duration);
        }
    });
    $(id_button_top).click(function(event) {
        event.preventDefault();
        $('html, body').animate({scrollTop: 0}, duration*2);
        return false;
    });
	$(id_buttom_bottom).click(function(event) {
        event.preventDefault();
        $('html, body').animate({scrollTop: bottom}, duration*2);
        return false;
    });
});