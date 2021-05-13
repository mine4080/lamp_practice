<?php
//require_once ファイルが読み込まれているかどうかチェック。すでに読み込まれていればファイルは読み込まない。
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

//セッションスタート
session_start();

//is_loginedがFALSEであれば、LOGIN_URLにリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//データベースに接続するための関数を変数に代入
$db = get_db_connect();

//ログインするための関数を変数に代入
$user = get_login_user($db);

//is_adminがfalseであれば、LOGIN_URLにリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

//postで受け取ったものを変数に代入
$item_id = get_post('item_id');
$stock = get_post('stock');

//トークンのチェック
if(is_valid_csrf_token(get_post('token')) === FALSE) {
  set_error('不正なリクエストです。');
  redirect_to(ADMIN_URL);
}

//在庫が変更されたら
if(update_item_stock($db, $item_id, $stock)){
  set_message('在庫数を変更しました。');
//変更が失敗したら 
} else {
  set_error('在庫数の変更に失敗しました。');
}

//ADMIN_URLにリダイレクト
redirect_to(ADMIN_URL);