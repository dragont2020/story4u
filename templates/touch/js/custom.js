$(document).ready(function(){
    $('.vipLoad').fadeOut(2000);
    $('.icon_post').each(function(){
		var a=$(this).find("img");a.attr("src",a.attr("src").replace("thumb","files/posts/images"));
		$(this).find('.title_post').css('width', $(this).find('img').css('width'));
		var b=$(this).find('.caption-view');b.css('');
    });
});
function topnav() {
    $('.topnav').toggleClass('responsive');
}
function ChangeFont (selectTag) {
    var whichSelected = selectTag.selectedIndex;

    var selectState = selectTag.options[whichSelected].text;

    var ht_middle = document.getElementById ("blog-content");

    ht_middle.style.fontFamily = selectState;
}

function zoominLetter() {
   var p = $('#blog-content');
   for(i=0;i<p.length;i++) {
      if(p[i].style.fontSize) {
         var s = parseInt(p[i].style.fontSize.replace("px",""));
      } else {
         var s = 20;
      }
      if(s<=40) {
         s += 2;
      }
      p[i].style.fontSize = s+"px"
   }
}
function zoomoutLetter() {
   var p = $('#blog-content');
   for(i=0;i<p.length;i++) {
      if(p[i].style.fontSize) {
         var s = parseInt(p[i].style.fontSize.replace("px",""));
      } else {
         var s = 20;
      }
      if(s>=16) {
         s -= 2;
      }
      p[i].style.fontSize = s+"px"
   }
}