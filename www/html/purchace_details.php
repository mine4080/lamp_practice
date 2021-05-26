<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'user.php';

session_start();

if(is_logined() === false) {
    redirect_to(INDEX_URL);
}

$db = get_db_connect();

$user = get_login_user($db);

$id = get_post('id');

$history = get_purchace_history($db, $id);

if(is_admin($user) === false) {
    if($user['user_id'] !== $history['user_id']) {
        redirect_to(HOME_URL);    
    } 
}

//購入商品詳細情報を変数へ入れる
$details = get_purchace_details($db, $id);

$token = get_csrf_token();

include_once VIEW_PATH . 'purchace_details_view.php';