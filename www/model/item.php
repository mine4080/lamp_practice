<?php
//require_once ファイルが読み込まれているかどうかチェック。すでに読み込まれていればファイルは読み込まない。
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// DB利用

//dbからitem_idを元にitemの情報を取得
function get_item($db, $item_id){
  $sql = "
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
    WHERE
      item_id = ?
  ";
  //データをバインド
  $params = array($item_id);
  return fetch_query($db, $sql, $params);
}

//dbから商品情報を取得
function get_items($db, $is_open = false){
  $sql = '
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
  ';
  if($is_open === true){
    $sql .= '
      WHERE status = 1
    ';
  }

  return fetch_all_query($db, $sql);
}

//get_itemsの情報をget_all_itemsへ
function get_all_items($db){
  return get_items($db);
}

//情報公開されているアイテムをget_open_itemsへ
function get_open_items($db){
  return get_items($db, true);
}

//
function regist_item($db, $name, $price, $stock, $status, $image){
  $filename = get_upload_filename($image);
  if(validate_item($name, $price, $stock, $filename, $status) === false){
    return false;
  }
  return regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename);
}

//アイテム登録のトランザクション処理
//トランザクション開始
//アイテムをインサート＆イメージを保存
//commitできなければロールバック
function regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename){
  $db->beginTransaction();
  if(insert_item($db, $name, $price, $stock, $filename, $status) 
    && save_image($image, $filename)){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
  
}

/**商品追加
 * statusのvalueを変数に格納
 * name,price,stock,filename,status_valueをinsertしexecute
*/
function insert_item($db, $name, $price, $stock, $filename, $status){
  $status_value = PERMITTED_ITEM_STATUSES[$status];
  $sql = "
    INSERT INTO
      items(
        name,
        price,
        stock,
        image,
        status
      )
    VALUES(?, ?, ?, ?, ?);
  ";
   //データをバインド
   $params = array($name, $price, $stock, $filename, $status_value);
  return execute_query($db, $sql, $params);
}

//アイテムのステータスをitem_idを元にアップデート
function update_item_status($db, $item_id, $status){
  $sql = "
    UPDATE
      items
    SET
      status = ?
    WHERE
      item_id = ?
    LIMIT 1
  ";
  //データをバインド
  $params = array($status, $item_id);
  return execute_query($db, $sql, $params);
}

//アイテムの在庫数をitem_idを元にアップデート
function update_item_stock($db, $item_id, $stock){
  $sql = "
    UPDATE
      items
    SET
      stock = ?
    WHERE
      item_id = ?
    LIMIT 1
  ";
  //データをバインド
  $params = array($stock, $item_id);
  return execute_query($db, $sql, $params);
}

/**アイテム情報を削除
 * dbからアイテム情報を取得し、失敗したらfalse
 * $itemに格納されているitem_idとimageを削除
 */
function destroy_item($db, $item_id){
  $item = get_item($db, $item_id);
  if($item === false){
    return false;
  }
  $db->beginTransaction();
  if(delete_item($db, $item['item_id'])
    && delete_image($item['image'])){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
}

//商品の削除
function delete_item($db, $item_id){
  $sql = "
    DELETE FROM
      items
    WHERE
      item_id = ?
    LIMIT 1
  ";
  //データをバインド
  $params = array($item_id);
  return execute_query($db, $sql, $params);
}


// 非DB

//ステータスが公開されている
function is_open($item){
  return $item['status'] === 1;
}

//validateされたアイテム情報を変数に格納し、変数をreturn
function validate_item($name, $price, $stock, $filename, $status){
  $is_valid_item_name = is_valid_item_name($name);
  $is_valid_item_price = is_valid_item_price($price);
  $is_valid_item_stock = is_valid_item_stock($stock);
  $is_valid_item_filename = is_valid_item_filename($filename);
  $is_valid_item_status = is_valid_item_status($status);

  return $is_valid_item_name
    && $is_valid_item_price
    && $is_valid_item_stock
    && $is_valid_item_filename
    && $is_valid_item_status;
}

//商品名のの長さを満たしていればreturn、満たしていなければエラーメッセージ。
function is_valid_item_name($name){
  $is_valid = true;
  if(is_valid_length($name, ITEM_NAME_LENGTH_MIN, ITEM_NAME_LENGTH_MAX) === false){
    set_error('商品名は'. ITEM_NAME_LENGTH_MIN . '文字以上、' . ITEM_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  return $is_valid;
}

//価格が正常に入力されていればreturn、されていなければエラーメッセージ。
function is_valid_item_price($price){
  $is_valid = true;
  if(is_positive_integer($price) === false){
    set_error('価格は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

//在庫が正常に入力されていればreturn、されていなければエラーメッセージ。
function is_valid_item_stock($stock){
  $is_valid = true;
  if(is_positive_integer($stock) === false){
    set_error('在庫数は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

//ファイルが正常に登録されていればreturn、されていなければfalse
function is_valid_item_filename($filename){
  $is_valid = true;
  if($filename === ''){
    $is_valid = false;
  }
  return $is_valid;
}

//アイテムのステータスが正常に入力されていればreturn、されていなければfalse
function is_valid_item_status($status){
  $is_valid = true;
  if(isset(PERMITTED_ITEM_STATUSES[$status]) === false){
    $is_valid = false;
  }
  return $is_valid;
}