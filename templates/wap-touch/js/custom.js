function zoominLetter() {
   var p = $('#blog-content');
   for(i=0;i<p.length;i++) {
      if(p[i].style.fontSize) {
         var s = parseInt(p[i].style.fontSize.replace("px",""));
      } else {
         var s = 16;
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
         var s = 16;
      }
      if(s>=16) {
         s -= 2;
      }
      p[i].style.fontSize = s+"px"
   }
}