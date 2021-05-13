<?php
//require_once ファイルが読み込まれているかどうかチェック。すでに読み込まれていればファイルは読み込まない。
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

//セッションスタート
session_start();

//is_loginedがfalseであれば、LOGIN_URLへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//データベースに接続する関数を変数に入れる
$db = get_db_connect();
//ログインに関する関数を変数に入れる
$user = get_login_user($db);

//postで送られてきたiitem_idを変数に入れる
$item_id = get_post('item_id');

//トークンのチェック
if(is_valid_csrf_token(get_post('token')) === FALSE) {
  set_error('不正なリクエストです');
  redirect_to(HOME_URL);
}

//$db,$user['user_id'],$item_idを元に商品をカートに追加。
if(add_cart($db,$user['user_id'], $item_id)){
  set_message('カートに商品を追加しました。');
//追加できなかった場合  
} else {
  set_error('カートの更新に失敗しました。');
}

//HOME_URLにリダイレクト
redirect_to(HOME_URL);