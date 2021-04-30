<?php

//$varに対して、var_dump
function dd($var){
  var_dump($var);
  exit();
}

//$urlに指定された場所へリダイレクト
function redirect_to($url){
  header('Location: ' . $url);
  exit;
}

//$nameが$_GETで送信された場合、$_GET[$name]をリターン
function get_get($name){
  if(isset($_GET[$name]) === true){
    return $_GET[$name];
  };
  return '';
}

//$nameが$_POSTで送信された場合、$_POST[$name]をリターン
function get_post($name){
  if(isset($_POST[$name]) === true){
    return $_POST[$name];
  };
  return '';
}

//$nameが$$_FILESで送信された場合、$_FILES[$name]をリターン
function get_file($name){
  if(isset($_FILES[$name]) === true){
    return $_FILES[$name];
  };
  return array();
}

//$_SESSION[$name]が存在すれば、$_SESSION[$name]をreturnする
function get_session($name){
  if(isset($_SESSION[$name]) === true){
    return $_SESSION[$name];
  };
  return '';
}

//$_SESSION[$name] に $valueを入れる
function set_session($name, $value){
  $_SESSION[$name] = $value;
}

// $error を$_SESSION['__errors'][]に入れる 
function set_error($error){
  $_SESSION['__errors'][] = $error;
}

//get_sessionのエラーを変数に入れる
function get_errors(){
  $errors = get_session('__errors');
  if($errors === ''){
    return array();
  }
  
  //'__errors',array()があれば、$errorsをreturn
  set_session('__errors',  array());
  return $errors;
}

//$_SESSION['__errors']が存在するか、$_SESSIONの数をカウント
function has_error(){
  return isset($_SESSION['__errors']) && count($_SESSION['__errors']) !== 0;
}

//$messageを$_SESSION['__messages'][]に代入
function set_message($message){
  $_SESSION['__messages'][] = $message;
}

//
function get_messages(){
  $messages = get_session('__messages');
  if($messages === ''){
    return array();
  }
  set_session('__messages',  array());
  return $messages;
}

//sessionでuser_idが存在しない場合
function is_logined(){
  return get_session('user_id') !== '';
}

//ファイルをアップロードするための関数
//is_valid_upload_imageがfalseであれば、return
//
function get_upload_filename($file){
  if(is_valid_upload_image($file) === false){
    return '';
  }
  $mimetype = exif_imagetype($file['tmp_name']);
  $ext = PERMITTED_IMAGE_TYPES[$mimetype];
  return get_random_string() . '.' . $ext;
}

//substr 
//uniqid　現在の時刻を元に作成される
//hash 文字列をハッシュ化（ランダム文字列）
//base_convert 記号をなくす、数値と文字列に変換
//20文字でハッシュ
function get_random_string($length = 20){
  return substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, $length);
}

//アップロードされたファイルを指定のディレクトリに保存
function save_image($image, $filename){
  return move_uploaded_file($image['tmp_name'], IMAGE_DIR . $filename);
}

//既存のイメージを削除
function delete_image($filename){
  if(file_exists(IMAGE_DIR . $filename) === true){
    unlink(IMAGE_DIR . $filename);
    return true;
  }
  return false;
  
}

//php_int_max 最大の文字
//長さ比較
function is_valid_length($string, $minimum_length, $maximum_length = PHP_INT_MAX){
  $length = mb_strlen($string);
  return ($minimum_length <= $length) && ($length <= $maximum_length);
}

//正規表現を渡して、マッチするかどうか
function is_alphanumeric($string){
  return is_valid_format($string, REGEXP_ALPHANUMERIC);
}

//正規表現を渡して、マッチするかどうか
function is_positive_integer($string){
  return is_valid_format($string, REGEXP_POSITIVE_INTEGER);
}

//フォーマットが正しければ1を返す
function is_valid_format($string, $format){
  return preg_match($format, $string) === 1;
}

//アップロードされたイメージの形式が違った場合、return
function is_valid_upload_image($image){
  if(is_uploaded_file($image['tmp_name']) === false){
    set_error('ファイル形式が不正です。');
    return false;
  }
  $mimetype = exif_imagetype($image['tmp_name']);
  if( isset(PERMITTED_IMAGE_TYPES[$mimetype]) === false ){
    set_error('ファイル形式は' . implode('、', PERMITTED_IMAGE_TYPES) . 'のみ利用可能です。');
    return false;
  }
  return true;
}

//h_function
function h($string){
  return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}