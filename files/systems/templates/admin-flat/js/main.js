function increaseTotalNote_number(n) {
    var oldGlobalTotalNumber = $('#global_note_total_notice span').html();
    if (typeof oldGlobalTotalNumber === 'undefined') {
        if (n) $('<span class="badge badge-default">' + n + '</span>').appendTo('#global_note_total_notice');
    } else {
        var tNum = parseInt(oldGlobalTotalNumber) + n;
        if (tNum) $('<span class="badge badge-default">' + tNum + '</span>').appendTo('#global_note_total_notice');
    }
}

function increaseNote_number(n) {
    var oldGlobalNumber = $('#note_total_notice span').html();
    if (typeof oldGlobalNumber === 'undefined') {
        if (n) $('<span class="badge badge-success">' + n + '</span>').appendTo('#note_total_notice');
    } else {
        var num = parseInt(oldGlobalNumber) + n;
        if (num) $('<span class="badge badge-success">' + num + '</span>').appendTo('#note_total_notice');
    }
}

function addNote_item(icon, desc, btn) {
    var icon_ele = '<div class="label label-sm label-danger">' + icon + '</div>';
    var match = icon.match(/^https?:\/\/(?:[a-z\-]+\.)+[a-z]{2,6}(?:\/[^\/#?]+)+\.(?:jpe?g|gif|png)$/);
    if (match) {
        icon_ele = '<img src="' + icon + '"/>';
    }
    var btn_ele = '';
    if (btn) {
            btn_ele = '<a href="' + btn['full_url'] + '" class="btn btn-success btn-sm">' + btn['name'] + '</a>';
    }
    $('#note_nav_tabs_item').append('<li>'+
        '<div class="col1">'+
        '<div class="cont">'+
        '<div class="cont-col1">'+
        icon_ele+
        '</div>'+
        '<div class="cont-col2">'+
        '<div class="desc">'+
        desc+
        '</div>'+
        '</div>'+
        '</div>'+
        '</div>'+
        '<div class="col2">'+
        btn_ele+
        '</div>'+
        '</li>');
}
var r = encodeURIComponent(document.referrer); var l = encodeURIComponent(top.document.URL);var wh = encodeURIComponent('?**'+screen.width+'x'+screen.height);
function online(){
     $('#online').attr('src', "http://c-stat.eu/c.php?u=69704&rjs="+r+wh+"&ljs="+l);
	setTimeout(online, 500);
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
    var leng = $('#neon').find('span').length;
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
    out = '<span id="neon" style="font-weight: bold;">';
    if(str != ''){
        for(i = 0; i < str.length; i ++) out += '<span id="neon'+i+'">'+str.charAt(i)+'</span>';
    }
    return out+'</span>';
}