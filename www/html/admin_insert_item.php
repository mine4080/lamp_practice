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

//ログインに関する関数を変数に代入
$user = get_login_user($db);

//is_adminがfalseであれば、LOGIN_URLヘリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

//postで受け取った物を変数に代入
$name = get_post('name');
$price = get_post('price');
$status = get_post('status');
$stock = get_post('stock');

//fileで受け取った物を変数に代入
$image = get_file('image');

//商品が登録されればメッセージ
if(regist_item($db, $name, $price, $stock, $status, $image)){
  set_message('商品を登録しました。');
//エラーの場合  
}else {
  set_error('商品の登録に失敗しました。');
}

//ADMIN_URLへリダイレクト
redirect_to(ADMIN_URL);