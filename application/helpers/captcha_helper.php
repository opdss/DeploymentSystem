<?php
/**
 * Created by PhpStorm.
 * @author wuxin
 * @date 2015 15/6/22 下午1:56
 * @copyright 7659.com
 */

function createCaptcha($strnum=4,$disturb=0, $width=60, $height=26) {
    $im = imagecreatetruecolor($width,$height);
    $bgcolor = imagecolorallocate($im,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
    imagefill($im,0,0,$bgcolor);
    $str = 'abcdefghijkmnpqrstuvwxyz23456789ABCDEFGHIJKMNOPQRSTUVWXYZ';
    $str = substr(str_shuffle($str),0,$strnum);

    //session
    $CI = &get_instance();
    $CI->session->set_userdata('captcha',$str);

    if($disturb == 1) {
        for($i=0; $i<$strnum; $i++){
            $color1 = Imagecolorallocate($im, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255));
            Imagearc($im,mt_rand(-5,$width),mt_rand(-5,$height),mt_rand(20,300),mt_rand(20,200),55,44,$color1);
        }

        for($i=0; $i<$strnum*20; $i++){
            $color2 = ImageColorAllocate($im, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255));
            Imagesetpixel($im, mt_rand(0,$width), mt_rand(0,$height), $color2);
        }
    }

    for($i=0;$i<$strnum;$i++){
        $j = $width/$strnum;
        $strcolor = imagecolorallocate($im,mt_rand(0,255),mt_rand(0,200),mt_rand(0,200));
        imagestring($im,rand(4,6),$j*$i+5,mt_rand(1,$height/4),$str[$i],$strcolor);
    }
    header('Content-type:image/jpeg');
    imagejpeg($im);
    imagedestroy($im);
}