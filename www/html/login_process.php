<?php
//require_once ファイルが読み込まれているかどうかチェック。すでに読み込まれていればファイルは読み込まない。
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

//セッションスタート
session_start();

//is_loginedがtrueであれば、HOME_URLにリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}

//postで送られてきたnameを変数に入れる
$name = get_post('name');

//postで送られてきたpasswordを変数に入れる
$password = get_post('password');

//データベースへの接続に関する関数を変数に入れる
$db = get_db_connect();

//ユーザー情報を変数に入れ、falseの場合、エラーメッセージ,
//LOGIN_URLへリダイレクト
$user = login_as($db, $name, $password);
if( $user === false){
  set_error('ログインに失敗しました。');
  redirect_to(LOGIN_URL);
}

//ログインできた場合、メッセージを表示。ユーザータイプがアドミンの場合、ADMIN_URLへリダイレクト。
set_message('ログインしました。');
if ($user['type'] === USER_TYPE_ADMIN){
  redirect_to(ADMIN_URL);
}
//HOME_URLへリダイレクト
redirect_to(HOME_URL);