function get_multi(num, now){
	if(now > num){
		return false;
	}else{
		pt = ((100/num)*now).toFixed(2);
        inlog('Link '+now+' ready');
		if($('#c-'+now).is(':checked')){
			$.ajax({
		        url : 'upload.php',
		        type : 'post',
		        dataType : 'text',
		        data : {
                     url 	: 	$('#u-'+now).html(),
                     fol    : 	$('#title').val(),
                     weight : 	$('#st-'+now).html(),
                     parent : 	$('#id_parent').val()
                },
		        success : function(kq){
		        	$('.progress-bar').css('width',pt+'%').html(pt+'%');
		            if(kq.indexOf('<a href') >= 0 || kq.indexOf('exists') >= 0){
						$('#u-'+now).html(kq);
                    }else get_multi(num, now);
                }
		    }).done(function() {
		          inlog($.trim($('#u-'+now).text())+' ok');
                  if(num == now){
					$('.progress-bar').addClass('progress-bar-success');
					$('#get_multi').html('<i class="fa fa-check-square-o"></i> Done').attr('class', 'btn btn-success');
                    inlog('Done!');$('.progress').slideUp();
                    var notify = new Notification(
                        'A new message from MStory.Ga',
                        {
                            body: $('#title').val()+' Finished',
                            icon: 'http://a1.mzstatic.com/us/r30/Purple3/v4/d2/1b/c1/d21bc114-f9d6-d7cc-dabf-b8aa9d7c4800/icon128.png'
                        }
                    );
                    if(check_auto()) auto_all();
                    document.title = 'Get Multi';
			     }else get_multi(num, ++now);
              }).fail(function() {
                inlog('Link '+now+' error. Is restarted');
                get_multi(num, now);
              });
		}else{
			$('.progress-bar').css('width',pt+'%').html(pt+'%');
			if(num == now){
				$('.progress-bar').addClass('progress-bar-success');
				$('#get_multi').html('<i class="fa fa-check-square-o"></i> Done').attr('class', 'btn btn-success');
                inlog('Done!');$('.progress').slideUp();
                if(check_auto()) auto_all();
                document.title = 'Get Multi';
			}else{
			     inlog('Link '+now+' skipping');
                get_multi(num, ++now);
			}
		}
	}
}
function upload(){
    if($('#url_icon').val() != ''){
        inlog('Uploading icon');
        if($('#cre_fol').html() != 'Created') $('#cre_fol').html('Waiting...');
    	$.ajax({
            url : 'upload.php',
            type : 'post',
            dataType : 'text',
            data : {
                 url_icon 	: 	$('#url_icon').val(),
                 title_icon : 	$('#title').val()
            },
            success : function(kq){
                kq = $.trim(kq);
            	$('#icon_post').val(kq);
            	$('#view_icon').html('<img style="max-height: 350px" src="files/posts/images/'+kq+'?w=300">');
                inlog('Uploaded icon');
                if($('#cre_fol').html() != 'Created'){
                    $('#cre_fol').html('Create');
                    if(check_auto()) cre();
                }
            }
        });
    }else{
        inlog('Upload error');
    }
}
function inlog(data){
    var d = new Date();
    var time = d.getHours()+':'+d.getMinutes()+':'+d.getSeconds();
    data = time+': '+data;
    console.log(data);
    $("#logs").val($('#logs').val()+'\n'+data);
    $("#logs").animate({
		scrollTop:$("#logs")[0].scrollHeight - $("#logs").height()
	},500);
	$('.w3-panel').find('h4').fadeOut(function(){
		$(this).html(data).fadeIn();
	});
}
function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}
function multi(urls = ''){
    urls = (urls == ''?$('#urls').val():urls);
    if(urls != ''){
        $('#auto_title').html('<div class="loader"></div>');
        $('#auto_info').html('<div class="loader"></div>');
        $('#title').val('');
        $('#id_parent').val($('#parent').val());
        $('#cre_fol').attr('class', 'btn btn-primary').html('Create').attr('disabled', true);
        $('#get_multi').html('Loading...').attr('class', 'btn btn-primary').attr('disabled', true);
        $('#button_get').html('<div class="loader"></div>');
        $('.progress').slideUp();
        $('.progress-bar').removeClass('progress-bar-success').attr('style', 'width: 0%; color: black;').html('');
        $('#info').removeClass('in');
        document.title = 'Get Multi';
        $('.w3-panel').find('h3').html('<div class="loader"></div>'+urls);
        $.ajax({
            url : 'upload.php',
            type : 'post',
            dataType : 'text',
            data : {
                 urls 	: 	urls
            },
            success : function(re){
                if(re != ''){
                    re1 = re.split("</textarea>");
                    re2 = re1[0].replace('<textarea>', '');
                    if(IsJsonString(re2)){
                        obj = $.parseJSON(re2);
                        if(obj){
                            var ne = $('#urls').val().replace(urls, '');
                            $('#urls').val(ne.replace('\n', ''));
                            fil($('#urls').val());
                            inlog('Received data');
                            //title
                            $('#title').val(obj.info.title);
                            $('#auto_title').find('i').attr('class', 'fa fa-check').attr('style', 'color: green');
                            $('.w3-panel').find('h3').html('<a href="'+urls+'" target="_blank">'+span(obj.info.title)+'</a>');
                            //icon
                            $('#url_icon').val(obj.info.icon);
                            if($('#get_icon').is(':checked')){upload();}
                            //des
                            $('#des_url').val(obj.info.des);
                            var url = ' <ul class="w3-ul">';
                            var u = 1;
                            $.each (obj.link, function (k, v){
                                url += '<li id="'+u+'"><input type="checkbox" id="c-'+u+'" checked /><label for="c-'+u+'"></label>Link <b id="st-'+u+'">'+k+'</b>: <span id="u-'+u+'">'+v+'</span></li>';
                                u += 1;
                            });
                            url += '</ul>';
                            var mx = (u - 1);
                            $('.w3-panel').find('h3').append('<h5>'+mx+' link(s)</h5>');
                            inlog('Received '+mx+' link(s)');
                            $('#max').html(mx);
                            $('.panel-body').html(url);
                            $('#auto_info').find('i').attr('class', 'fa fa-check').attr('style', 'color: green');
                            $('ul.w3-ul li:even').css("background-color", "white");
                            $('ul.w3-ul li:odd').css("background-color", "lightgray");
                            $('#get_multi').html('<i class="fa fa-upload"></i> Start Get').attr('disabled', false);
                            $('#cre_fol').attr('disabled', false);
                        }else{
                            inlog('An error occurred');
                        }
                    }else if(check_auto()){$('.progress-bar').addClass('progress-bar-success'); auto_all();}
                }else if(check_auto()){$('.progress-bar').addClass('progress-bar-success'); auto_all();}
            }
        }).fail(function(){
            multi(urls);
        });
        $('.w3-content').show();
    }
}
function cre(){
    if($('#id_parent').val() > 0 && $('#title').val() != ''){
        inlog('Creating directory');
    	$.ajax({
            url : 'upload.php',
            type : 'post',
            dataType : 'text',
            data : {
                 name_fol 	: 	$('#title').val(),
                 par_fol 	: 	$('#id_parent').val(),
                 icon_fol 	: 	$('#icon_post').val(),
                 des_fol 	: 	$('#des_url').val()
            },
            success : function(kq){
            	$('#id_parent').val(kq);
            	$('#cre_fol').attr('class', 'btn btn-success').html('Created');
                inlog('Created directory');
                $('#cre_fol').attr('disabled', true);
                if(check_auto()) get_m();
            }
        });
    }
}
function get_m(){
    var num = Number($('#max').html());
	$('#get_multi').html('Receving').attr('disabled', true);
    inlog('Start getting');
    $('.progress').slideDown();
   	get_multi(num, 1);
	document.title = $('#title').val();
}
function auto_all(){
    var u = $('#urls');
    var e = u.val().split('\n');
        e = e.filter(function(v){return ($.trim(v)!=='')&&(v.indexOf('http://')>=0)});
    if(u.val() != '' || e.length > 0){
        if($('.progress-bar').hasClass('progress-bar-success') || $('.w3-content').attr('style') == 'display: none'){
            multi(e[0]);
        }
    }
}
function fil(urls = ''){
    var cou = urls.split('\n');
    cou = cou.filter(function(v){return ($.trim(v)!=='')&&(v.indexOf('http://')>=0)});
    $('#comma').html('<code>'+cou.length+'</code> link(s)');
}
function check_auto(){
    return $('#auto_get').is(":checked");
}
function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++ ) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}
function neon(num = 0, base = 'black', type = 'right'){
    var leneon = 0;
    var leng = $('.w3-panel').find('h3').find('span').length;
    if(num == 0 || num == leng) base = getRandomColor();
    var chan = getRandomColor();
    $('#neon'+num).css('color', chan);
    if(type == 'right'){
        if(num > leneon) $('#neon'+(num - leneon - 1)).css('color', base);
        if(num < (leng)) setTimeout("neon("+(num + 1)+", '"+base+"')", 100);
        else setTimeout("neon("+leng+", '', 'left')", 10);
    }else{
        if(num + leneon < leng) $('#neon'+(num + leneon + 1)).css('color', base);
        if(num > 0) setTimeout("neon("+(num - 1)+", '"+base+"', 'left')", 100);
        else setTimeout("neon()", 10);
    }
}
function span(str){
    out = '';
    if(str != ''){
        for(i = 0; i < str.length; i ++) out += '<span id="neon'+i+'">'+str.charAt(i)+'</span>';
    }
    return out;
}
$('#cretool').click(function(){
    $('#comma').html('<div class="loader"></div>');
    $.ajax({
        url : 'upload.php',
        type : 'post',
        dataType : 'text',
        data : {
             urltool 	: 	$('#urltool').val(),
             fromtool 	: 	$('#fromtool').val(),
             totool 	: 	$('#totool').val()
        },
        success : function(kq){
            if(kq != ''){
                $('#urls').val($('#urls').val()+kq);
                fil($('#urls').val());
                if(check_auto() && ($('.progress-bar').hasClass('progress-bar-success') || $('.w3-content').attr('style') == 'display: none')) auto_all();
            }
        }
    });
});
$('#parent').change(function(){
	$('#id_parent').val($('#parent').val());
});
$('#id_parent').change(function(){
	$('#parent').val($('#id_parent').val());
});
$('#urls').on('input', function(){
	if($('#auto_comma').is(":checked")){
		$(this).val($(this).val()+'\n');
	}
    fil($(this).val());
    if(check_auto() && ($('.progress-bar').hasClass('progress-bar-success') || $('.w3-content').attr('style') == 'display: none')) auto_all();
});
$('#urls').dblclick(function(){
    $(this).select();
});
$('#urls').bind('paste', function (e) {
    $(this).animate({
		scrollTop:$(this)[0].scrollHeight - $(this).height()
	},500);
});
$('#all').change(function(){
    $('input[id|="c"]').prop('checked', $(this).prop('checked'));
});
$('#auto_get').change(function(){
    $('#get').toggle();
    $('#fol_form').toggle();
    $('#button_get').toggle();
    $('#info_form').toggle();
    if(check_auto() && ($('.progress-bar').hasClass('progress-bar-success') || $('.w3-content').attr('style') == 'display: none')) auto_all();
});
$(document).ready(function(){
    if (Notification.permission == 'default'){
        Notification.requestPermission();
    }
    if(check_auto()){
        $('#get').hide();
        $('#fol_form').hide();
        $('#button_get').hide();
        $('#info_form').hide();
    }
    neon();
});