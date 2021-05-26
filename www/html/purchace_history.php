<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

if(is_logined() === false) {
    redirect_to(HOME_URL);
}

$db = get_db_connect();

$user = get_login_user($db);

$histories = array();

if(is_admin($user) === false){
    $histories = get_purchace_histories($db, $user['user_id']);
} else {
    $histories = get_all_purchace_histories($db);
}


$token = get_csrf_token();

include_once VIEW_PATH . 'purchace_history_view.php';