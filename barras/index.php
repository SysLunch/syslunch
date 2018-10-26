<?php
    ini_set("display_errors",1);
    require_once('../class/php-barcode.php');
    //new barCodeGenrator($_GET["codigo"],0,'hello.gif', $_GET["l"], $_GET["a"], true);
    //Barcode::gd($res, "FFFFFF", 0, 0, $angle, $type, $datas, $width = null, $height = null);

    $im     = imagecreatetruecolor(300, 300);
    $black  = ImageColorAllocate($im,0,0,0);
    $white  = ImageColorAllocate($im,255,255,255);
    imagefilledrectangle($im, 0, 0, 300, 300, $white);
    $data = Barcode::gd($im, $black, 150, 150, 0, "code128", "12345678", 2, 50);
    print_r($data);
    /*
		header("Content-type: image/gif");
		imagegif($data);
		imagedestroy($image);
?>
