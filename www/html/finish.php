<?php
//require_once ファイルが読み込まれているかどうかチェック。すでに読み込まれていればファイルは読み込まない。
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

//セッションスタート
session_start();

//is_loginedがfalseであれば、LOGIN_URLにリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//トークンのチェック
if(is_valid_csrf_token(get_post('token')) === FALSE) {
  set_error('不正なリクエストです。');
  redirect_to(ADMIN_URL);
}

//データベースに接続する関数を変数に入れる
$db = get_db_connect();

//ログインに関する関数を変数に入れる
$user = get_login_user($db);

//$db,$user['user_id']を元にカートから情報を取得し、変数に入れる
$carts = get_user_carts($db, $user['user_id']);

//purchase_cartsの$db,$cartsがfalseであったら、エラーメッセージ。CART_URLへリダイレクト
$db->beginTransaction();
if(purchase_carts($db, $carts) === false){
  set_error('商品が購入できませんでした。');
} else {
  insert_purchace_history($db, $user['user_id']);
  insert_purchace_details($db, $user['user_id'], $carts);
} 

// //エラーが発生していたら
if(has_error() === TRUE && has_error() >= 1) {
  $db->rollback();
  redirect_to(CART_URL);
} else {
  $db->commit();
}

//カートの中身の商品の値段を合計し、変数に入れる
$total_price = sum_carts($carts);

//include_once ファイルの読み込み。すでに読み込まれている場合、読み込み和行われない。
//view/finish_view.phpへのパス
include_once '../view/finish_view.php';