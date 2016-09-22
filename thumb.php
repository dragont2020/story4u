<?php
header('Content-Type: image/jpeg');
// Có thể cho tham số chiều rộng chiều cao ở luôn file request đến file này để linh động hơn
createthumb('files/posts/images/'.urldecode($_GET['i']),intval($_GET['w']),intval($_GET['h']));
function createthumb($name = '',$thumb_w = '',$thumb_h = '')
{
 if($thumb_w == '')  $thumb_w = 100;
 $kichthuoc = getimagesize($name);
 $thumb_h = round($kichthuoc[1]*$thumb_w / $kichthuoc[0]);

 list($w,$h,$source_image_type ) = getimagesize( $name );



 switch($source_image_type)
 {
  case IMAGETYPE_GIF:
   $src_img = imagecreatefromgif( $name );
  break;

  case IMAGETYPE_JPEG:
   $src_img = imagecreatefromjpeg( $name );
  break;

  case IMAGETYPE_PNG:
   $src_img = imagecreatefrompng( $name );

  break;
 }

 $old_x=imageSX($src_img);
 $old_y=imageSY($src_img);


 $dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
        imagealphablending($dst_img, false);
        imagesavealpha($dst_img, true);
        $bg = imagecolorallocate($dst_img, 255, 255, 255);
        imagefill ( $dst_img, 0, 0, $bg );
        imagecolortransparent($dst_img, $bg);
 //imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);

        //rewsize
        if (function_exists("ImageCopyResampled"))
        {
                ImageCopyResampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
        }
        else
        {
                imagecopyresized($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
        }

        //set quality

        switch($source_image_type){
                case IMAGETYPE_JPEG:
                        imagejpeg($dst_img, NULL, 100);
                        break;
                case IMAGETYPE_GIF:
                        imagegif($dst_img, NULL, 100);
                        break;
                case IMAGETYPE_PNG:
                        imagepng($dst_img, NULL, 9);
                        break;
        }

 //imagejpeg($dst_img);
 imagedestroy($dst_img);
 imagedestroy($src_img);
}
?>