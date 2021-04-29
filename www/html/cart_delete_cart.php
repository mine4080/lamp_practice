<?php
//require_once ファイルが読み込まれているかどうかチェック。すでに読み込まれていればファイルは読み込まない。
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

//セッションスタート
session_start();

//is_loginedがfalseの場合、LOGIN_URLへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//データベース接続に関する関数を変数に入れる
$db = get_db_connect();

//ログインに関する関数を変数に入れる
$user = get_login_user($db);

//postで受け取ったものを変数に入れる
$cart_id = get_post('cart_id');

//$dbと$cart_idを削除
if(delete_cart($db, $cart_id)){
  set_message('カートを削除しました。');
//エラーメッセージ
} else {
  set_error('カートの削除に失敗しました。');
}

//CART_URLへリダイレクト
redirect_to(CART_URL);