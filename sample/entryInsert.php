<?php

require_once '../MTAuth.php';

$instance = new MTAuth(array(
    'url' => 'http://your-domain/mt/mt-data-api.cgi'
));

$status = $instance->login(
    'hoge',
    'hogehoge'
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

