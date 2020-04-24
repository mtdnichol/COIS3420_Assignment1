<?php

require 'data.php';

var_dump($_FILES);

if($_FILES["files"]["name"][0] != '') {
    $expl = explode(".", $_FILES["files"]["name"][0]);
    $ext = end($expl);
    $location = "/home/bobbyhorth/public_html/3420/project/COIS3420_FinalProject/userimages/" . randomURL() . "." . $ext;
    copy($_FILES["files"]["tmp_name"][0], $location);
    response(200, "Uploaded!", $location);
}

function randomURL($URLlength = 8) {
    $charray = array_merge(range('a','z'), range('0','9'));
    $max = count($charray) - 1;
    $url = "";
    for ($i = 0; $i < $URLlength; $i++) {
        $randomChar = mt_rand(0, $max);
        $url .= $charray[$randomChar];
    }
    return $url;
}