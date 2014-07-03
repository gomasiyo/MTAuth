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

    // ログインが出来た時の処理

    // ブログID
    $blogId = 1;
    // 記事作成
    $entry = array(
        'title'     => 'サンプル用のタイトル',
        'body'      => 'サンプル用の記事',
        'more'      => 'サンプル用の追記'
    );

    $status = $instance->userRequest(array(
        'url'           => "/v1/sites/{$blogId}/entries",
        'request'       => 'entry',
        'json_params'   => true,
        'login'         => true,
        'params'        => $entry
    ));

    if($status) {
        echo "Success";
    } else {
        echo "failed";
    }

} else {

    // ログインが失敗した時の処理
    echo "Login Failed";
}

