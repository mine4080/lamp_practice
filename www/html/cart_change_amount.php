<?php
//require_once ファイルが読み込まれているかどうかチェック。すでに読み込まれていればファイルは読み込まない。
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

//セッションスタート
session_start();

//is_loginedがfalseであれば、LOGIN_URLヘリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//データベース接続に関する関数を変数に入れる
$db = get_db_connect();

//ログインに関する関数を変数に入れる
$user = get_login_user($db);

//postで送られてきた内容を変数に追加
$cart_id = get_post('cart_id');
$amount = get_post('amount');

//トークンのチェック
if(is_valid_csrf_token(get_post('token')) === FALSE) {
  set_error('不正なリクエストです');
  redirect_to(CART_URL);
}

//update_cart_amountが正常に行われればメッセージ
if(update_cart_amount($db, $cart_id, $amount)){
  set_message('購入数を更新しました。');  
//そうでなければエラーメッセージ  
} else {
  set_error('購入数の更新に失敗しました。');
}

//CART_URLへリダイレクト
redirect_to(CART_URL);