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

//データベースへの接続に関する関数を変数に代入
$db = get_db_connect();

//  ログインに関する関数を変数に代入
$user = get_login_user($db);

//is_adminがfalseであれば、LOGIN_URLヘリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

//トークンを生成
$token = get_csrf_token();

//全てのitemをセレクトする関数を変数に代入
$items = get_all_items($db);
//include_once ファイルを読み込み。1度読み込まれている場合は実行されない
include_once VIEW_PATH . '/admin_view.php';
