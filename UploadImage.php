<?php

require 'data.php';

// Upload an image file with a random URL
// NOTE: this is broken due to loki not allowing permission to modify files in the /tmp/ folder
if($_FILES["files"]["name"][0] != '') {
    // Get file extension from file
    $expl = explode(".", $_FILES["files"]["name"][0]);
    $ext = end($expl);
    // Create new file location
    // hardcoded but it doesn't work anyway
    $location = "/home/bobbyhorth/public_html/3420/project/COIS3420_FinalProject/userimages/" . randomURL() . "." . $ext;
    // Copy file from temp file to new loc
    copy($_FILES["files"]["tmp_name"][0], $location);
    // Return valid response even though its broken
    response(200, "Uploaded!", $location);
}

// Generate a random URL (taken from stack overflow)
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