<?php 
//require_once ファイルが読み込まれているかどうかチェック。すでに読み込まれていればファイルは読み込まない。
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

//userのカートの中身を表示させる関数
function get_user_carts($db, $user_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = {$user_id}
  ";
  return fetch_all_query($db, $sql);
}

//カートの中身を表示させる関数
function get_user_cart($db, $user_id, $item_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = {$user_id}
    AND
      items.item_id = {$item_id}
  ";

  return fetch_query($db, $sql);

}

//カートの中アイテムが存在しなければインサート、存在すればアップデート
function add_cart($db, $user_id, $item_id ) {
  //カートの中の情報を変数に代入
  $cart = get_user_cart($db, $user_id, $item_id);
  //情報が無ければインサート
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  //あればカートの情報をアップデート
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

//カートにitem_id,user_id,amount情報をインサート
function insert_cart($db, $user_id, $item_id, $amount = 1){
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES({$item_id}, {$user_id}, {$amount})
  ";
  //execute
  return execute_query($db, $sql);
}

//カートの中のアイテムの個数をアップデート
function update_cart_amount($db, $cart_id, $amount){
  $sql = "
    UPDATE
      carts
    SET
      amount = {$amount}
    WHERE
      cart_id = {$cart_id}
    LIMIT 1
  ";
  //execute
  return execute_query($db, $sql);
}

//カートの中の情報をcart_idを元に削除
function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = {$cart_id}
    LIMIT 1
  ";
  //execute
  return execute_query($db, $sql);
}

//カートの中身を購入
function purchase_carts($db, $carts){
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  foreach($carts as $cart){
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }
  
  delete_user_carts($db, $carts[0]['user_id']);
}

function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = {$user_id}
  ";

  execute_query($db, $sql);
}


function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}

//カートの中身チェック
function validate_cart_purchase($carts){
  //カートの中身がなかったら
  if(count($carts) === 0){
    //エラーメッセージを表示
    set_error('カートに商品が入っていません。');
    return false;
  }

  //カートの中身を$cartに代入
  foreach($carts as $cart){
    //itemがopenでなかったら
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    //カートのアイテムの量がアイテムのストックを上回ったら、エラーメッセージ
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  //
  if(has_error() === true){
    return false;
  }
  return true;
}

