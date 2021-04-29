<?php

//define(定数名、値);
//$_SERVER['DOCUMENT_ROOT']は、ドキュメントルートへのフルパス
define('MODEL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../model/');
define('VIEW_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../view/');

//IMAGE,STYLESHEET,IMAG_DIR へのパス
define('IMAGE_PATH', '/assets/images/');
define('STYLESHEET_PATH', '/assets/css/');
define('IMAGE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/assets/images/' );

//データベースへの接続
define('DB_HOST', 'mysql');
define('DB_NAME', 'sample');
define('DB_USER', 'testuser');
define('DB_PASS', 'password');
define('DB_CHARSET', 'utf8');

//htmlから各URLへのパス
define('SIGNUP_URL', '/signup.php');
define('LOGIN_URL', '/login.php');
define('LOGOUT_URL', '/logout.php');
define('HOME_URL', '/index.php');
define('CART_URL', '/cart.php');
define('FINISH_URL', '/finish.php');
define('ADMIN_URL', '/admin.php');

//正規表現
//0~9,アルファベット小文字・大文字が1回以上繰り返す
define('REGEXP_ALPHANUMERIC', '/\A[0-9a-zA-Z]+\z/');
//1~99 or 0
define('REGEXP_POSITIVE_INTEGER', '/\A([1-9][0-9]*|0)\z/');

//USER_NAMEとUSER_PASSWORDの長さは最小で6文字，最大で100文字まで
define('USER_NAME_LENGTH_MIN', 6);
define('USER_NAME_LENGTH_MAX', 100);
define('USER_PASSWORD_LENGTH_MIN', 6);
define('USER_PASSWORD_LENGTH_MAX', 100);

//管理者が1、ノーマルが2
define('USER_TYPE_ADMIN', 1);
define('USER_TYPE_NORMAL', 2);

//ITEM_NAMEの長さは最小で1、最大で100
define('ITEM_NAME_LENGTH_MIN', 1);
define('ITEM_NAME_LENGTH_MAX', 100);

//ITEMを公開する際のステータスは1，しない際は0
define('ITEM_STATUS_OPEN', 1);
define('ITEM_STATUS_CLOSE', 0);

//openに1、closeに0
define('PERMITTED_ITEM_STATUSES', array(
  'open' => 1,
  'close' => 0,
));

//イメージのタイプはjpgまたは、pngのみ
define('PERMITTED_IMAGE_TYPES', array(
  IMAGETYPE_JPEG => 'jpg',
  IMAGETYPE_PNG => 'png',
));