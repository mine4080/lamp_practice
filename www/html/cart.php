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

//データベースに関する関数を変数に入れる
$db = get_db_connect();

//dbのユーザー情報を変数に入れる
$user = get_login_user($db);

//$db,$user['user_id']を元に、ユーザーのカートの中身を表示する関数を変数に入れる
$carts = get_user_carts($db, $user['user_id']);

//カートの中身の商品の金額を計算。変数に入れる
$total_price = sum_carts($carts);

//include_once ファイルの読み込み。すでに読み込まれている場合、読み込みは行われない。
//view.phpへのパス
include_once VIEW_PATH . 'cart_view.php';