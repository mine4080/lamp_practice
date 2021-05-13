<?php
//require_once ファイルが読み込まれているかどうかチェック。すでに読み込まれていればファイルは読み込まない。
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

//セッションスタート
session_start();

//is_loginedがfalseであれば、LOGIN_URLへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//データベースに接続する関数を変数に代入
$db = get_db_connect();

//ログインする関数を変数に代入
$user = get_login_user($db);

//is_adminがfalseであればLOGIN_URLへリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

//postで受け取ったものを変数に代入
$item_id = get_post('item_id');

//トークンのチェック
if(is_valid_csrf_token(get_post('token')) === FALSE) {
  set_error('不正なリクエストです。');
  redirect_to(ADMIN_URL);
}

//destroy_itemがtrueであればメッセージを表示
if(destroy_item($db, $item_id) === true){
  set_message('商品を削除しました。');
//trueでなければ  
} else {
  set_error('商品削除に失敗しました。');
}


//ADMIN_URLへリダイレクト
redirect_to(ADMIN_URL);