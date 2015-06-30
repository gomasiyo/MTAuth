<?php

require_once '../MTAuth4p.php';

$instance = new MTAuth(array(
    'url' => 'http://your-domain/mt/mt-data-api.cgi'
));

$status = $instance->login(
    "id",
    "password"
);

if($status) {

    $status = $instance->insertEntries(1, null, "TestBody");

    if($status) {
        echo "Success";
    } else {
        echo "failed";
    print_r($instance->response);
    }

} else {

    // ログインが失敗した時の処理
    echo "Login Failed";

}

