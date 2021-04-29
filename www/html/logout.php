<?php
//require_once ファイルが読み込まれているかどうかチェック。すでに読み込まれていればファイルは読み込まない。
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';

//セッションスタート
session_start();
//セッション変数の定義
$_SESSION = array();
//セッションに関連する設定を取得
$params = session_get_cookie_params();
//セッションに利用しているcookieの有効期限を過去に設定することで無効化
setcookie(session_name(), '', time() - 42000,
  $params["path"], 
  $params["domain"],
  $params["secure"], 
  $params["httponly"]
);
//セッションIDを無効化
session_destroy();

//LOGIN_URLへリダイレクト
redirect_to(LOGIN_URL);

