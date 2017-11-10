<?php
if (!isset($_GET['src']) || !isset($_GET['w'])) {
    out_error();
    return;
}
$tmp = $_GET['src'];
$tmp = explode('/', $tmp);
$src = array_pop($tmp);
$src = $_SERVER['DOCUMENT_ROOT'] . '/app/public/uploads/' . preg_replace('#[^a-z0-9\-_\.]+#si', '', $src);

if (!file_exists($src)) {
    out_error();
}
$w = intval($_GET['w']);
if ($w < 1) {
    out_error();
}


$img = imagecreatetruecolor($w, $w);
$white = imagecolorallocate($img, 255, 255, 255);
imagefill ($img, 1,1, $white);
$size = getimagesize($src);
$function_create = '';
switch ($size[2]) {
    case IMAGETYPE_GIF: {
        $function_create = 'imagecreatefromgif';
        break;
    }
    case IMAGETYPE_JPEG: {
        $function_create = 'imagecreatefromjpeg';
        break;
    }
    case IMAGETYPE_PNG: {
        $function_create = 'imagecreatefrompng';
        break;
    }
    default:
        {
        out_error();
        break;
        }
}
if ($function_create == '') {
    out_error();
}

$old = $function_create($src);



$k = 1;
if ($size[0] >= $w || $size[1] >= $w) {
    $k = ($size[0] / $w > $size[1] / $w) ? $w / $size[0] : $w / $size[1];
} elseif ($size[0] <= $w || $size[1] <= $w) {
    $k = ($size[0] / $w > $size[1] / $w) ? $size[0] / $w : $size[1] / $w;
}

if ($size[0] >= $w || $size[1] >= $w) {
    $kx = round($size[0] * $k); $ky = round($size[1] * $k);
}else {
    $kx = round($size[0] / $k); $ky = round($size[1] / $k);
}

$x = ($kx >= $w) ? 0 : round(($w - $kx) / 2);
$y = ($ky >= $w) ? 0 : round(($w - $ky) / 2);

imagecopyresampled($img, $old, $x, $y, 0, 0, $kx, $ky, $size[0],$size[1]);
header('Content-type: image/jpeg');
imagejpeg($img);

function out_error()
{
    return false;
}