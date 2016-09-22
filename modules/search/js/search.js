function showResult(page = '') {
    var str = $('#search').val();
    var t = $('select').val();
    if (str.length == 0) {
        $('#livesearch').html('');
       return;
    }
    if (window.XMLHttpRequest) {
       xmlhttp = new XMLHttpRequest();
    }else {
       xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
       if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            $('#livesearch').html(xmlhttp.responseText);
       }
    }
    xmlhttp.open("GET","upload.php?q="+str+"&p="+page+'&t='+t,true);
    xmlhttp.send();
}
$('#search').on('input', function(){
    showResult(1);
});
$('select').change(function(){
    showResult(1);
});