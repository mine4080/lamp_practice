<?php
//require_once ファイルが読み込まれているかどうかチェック。すでに読み込まれていればファイルは読み込まない。
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';

//セッションスタート
session_start();

//is_loginedがtrueであれば、HOME_URLへリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}

//トークンの生成
$token = get_csrf_token();

//include_once ファイルの読み込み。すでに読み込まれている場合、読み込みは行われない。
//login_view.phpへのパス
include_once VIEW_PATH . 'login_view.php';