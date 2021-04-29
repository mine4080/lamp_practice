<?php
//require_once ファイルが読み込まれているかどうかチェック。すでに読み込まれていればファイルは読み込まない。
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

//セッションスタート
session_start();

//is_loginedがfalseであれば、LOGIN_URLにリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//データベースに関する関数を変数に入れる
$db = get_db_connect();

//ログインに関する変数を変数に入れる
$user = get_login_user($db);

//openになっている商品を変数に入れる
$items = get_open_items($db);

//include_once ファイルの読み込み。すでに読み込まれている場合、読み込み和行われない。
//view.phpへのパス
include_once VIEW_PATH . 'index_view.php';