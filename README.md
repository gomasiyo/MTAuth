# MTAuth4p
このクラスは、Movable TypeのDataAPIをPHPで利用するためのクラスです。  
ログイン後の処理も簡単に行えます。

## 使い方

まず、本クラスを読み込みます。

```
require_once 'MTAuth4p.php';
```

次にインスタンス作成します。データは配列で送ります。  
必要パラメーター

| パラメーター | 必須 | 説明 | 例 | デフォルト |
|--------|--------|--------|--------|--------|
| url | true | DataAPIへのPath | http://your-domain/path-to-mt/mt-data-api.cgi | null |
| clientId | false | このIDは認証やCookieに利用されます。任意で変更できます | MTAuth| MTAuth |

```
$instance = new MTAuth4p(array(
    'url' => 'http://your-domain/mt/mt-data-api.cgi',
    'clientId' => 'MYAuth'
));
```

### Login
ブログに記事を投稿するには、X-MT-AuthorizationにaccessTokenを追加したヘッダーが必要です。  
この、accessTokenを取得するためにLoginを行います。  
戻り値でBooleanで帰ってくるため、ログインできたかif文で判断できます。  
必要パラメーター

| パラメーター | 必須 | 説明 | 例 | デフォルト |
|--------|--------|--------|--------|--------|
| username | true | ログインするユーザーです。 | hoge | null |
| password | true | ログインするユーザーのパスワードです | hogehogehoge | null |

```
$status = $instance->login(
    'hoge',
    'hogehogehoge'
);
```

### insertEntries
ブログに記事を投稿するメソッドです。  
戻り値でBooleanで帰ってくるため、投稿できたか判断できます。  
必要パラメーター

| パラメーター | 必須 | 説明 | 例 | デフォルト |
|--------|--------|--------|--------|--------|
| blogid | true | どのブログに投稿するかの指定です。 | 1 | null |
| title | false | 記事のタイトルです。 | hogehogehoge | null |
| body | true | 記事の本文です。 | hoge | null |
| more | false | 記事の追記です。 | hoge | null |

### userRequest
このメソッドはリクエストを送るためのメソッドです。  
※ まだ、postとgetのHttpメソッドしか対応していないので、updateとdeleteは現在使用不可です。  
データは配列で送ります。  
必要パラメーター

| パラメーター | 必須 | 説明 | 例 | デフォルト |
|--------|--------|--------|--------|--------|
| method | false | 送るデータのメソッドです | get | post |
| url | true | 送るデータのアドレスです。[DataAPIのPathより後の文字列です] | /v1/sites/1/entries | null |
| request | false | 送るデータの種類によって変更します | entry | null |
| json_params | false | 送るデータをJsonに変更するかどうか | true | true |
| login | false | accessTokenを追加したヘッダーを追加するかどうか | true | false |
| parms | false | 送信するデータを配列で送ります | array( 'title' => 'Test' ) | array() |

```
$status = $instance->userRequest(array(
    'method'        => 'post',
    'url'           => '/v1/sites/1/entries',
    'request'       => 'entry',
    'json_params'   => true,
    'login'         => true,
    'params'        => array(
        'title'         => 'Test',
        'body'          => 'TestContets',
        'more'          => 'TestMore'
    )
));
```

### response
これは、メソッドではなくメンバ変数です。  
基本的にエラーメッセージやuserRequestでデータを送って帰ってくる値は全てこのresponseに入ります。

```
$response = $instance->response;
```

エラーメッセージは添字['error']に入っています。

---

## 締めくくり

これを、継承し使うのもよし、そのまま使うのよし。  
自由にお使いください。
