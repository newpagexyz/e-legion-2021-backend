<?php
if(isset($_GET['file'])){
    $file=$_GET['file'];
    if(file_exists($_SERVER['DOCUMENT_ROOT'].'/user_files/profile_photo/'.$file)){
        $image = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/user_files/profile_photo/'.$file);
        header('Content-type: image/jpeg;');
        header("Content-Length: " . strlen($image));
        echo $image;
        exit;   
    }
}
//http_response_code(404);