<?php
//require_once ファイルが読み込まれているかどうかチェック。すでに読み込まれていればファイルは読み込まない。
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

//セッションスタート
session_start();

//is_logined がFALSEの場合、LOGIN＿URLへリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

//関数get_db_donnectを変数$dbへ代入
$db = get_db_connect();

//関数get_login_userを変数に代入
$user = get_login_user($db);

//is_adminがFALSEだったら、LOGIN_URLへリダイレクト
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

//postで送られてきた内容を変数に代入
$item_id = get_post('item_id');
$changes_to = get_post('changes_to');

//トークンのチェック
if(is_valid_csrf_token(get_post('token')) === FALSE) {
  set_error('不正なリクエストです。');
  redirect_to(ADMIN_URL);
}

//$changes_toがopenであれば
if($changes_to === 'open'){
  update_item_status($db, $item_id, ITEM_STATUS_OPEN);
  set_message('ステータスを変更しました。');
//$changes_toがcloseであれば  
}else if($changes_to === 'close'){
  update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
  set_message('ステータスを変更しました。');
//openでもcloseでも無い場合  
}else {
  set_error('不正なリクエストです。');
}


redirect_to(ADMIN_URL);