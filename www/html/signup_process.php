<?php
//require_once ファイルが読み込まれているかどうかチェック。すでに読み込まれていればファイルは読み込まない。
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';

//セッションスタート
session_start();

//is_loginedがtrueであれば、HOME_URLへリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}

//postから送られてきたものを変数に追加
$name = get_post('name');
$password = get_post('password');
$password_confirmation = get_post('password_confirmation');

//データベース接続に関する関数を変数に入れる
$db = get_db_connect();

try{
  //ユーザー登録に関する情報を変数に入れる
  $result = regist_user($db, $name, $password, $password_confirmation);
  if( $result=== false){
    set_error('ユーザー登録に失敗しました。');
    redirect_to(SIGNUP_URL);
  }
}catch(PDOException $e){
  set_error('ユーザー登録に失敗しました。');
  redirect_to(SIGNUP_URL);
}

set_message('ユーザー登録が完了しました。');
login_as($db, $name, $password);
redirect_to(HOME_URL);