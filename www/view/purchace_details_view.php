<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include VIEW_PATH . 'templates/head.php'; ?>
    <title>購入商品詳細データ一覧</title>
</head>
<body>
    <?php
    include VIEW_PATH . 'templates/header_logined.php';
    ?>

    <div class="container">

        <header>
            <table>
                <tr>
                    <th>注文番号</th>
                    <th>購入時日時</th>
                    <th>合計金額</th>
                </tr>
                
                    <tr>
                        <td><?php print $history['id']; ?></td>
                        <td><?php print $history['created']; ?></td>
                        <td><?php print $history['total_price']; ?></td>
                    </tr>
                
            </table>
        </header>

        <h1>購入商品一覧</h1>

        <?php include VIEW_PATH . 'templates/messages.php'; ?>

        <table class="table table-bordered text-center">
            <thead class="thead-light">
                <tr>
                    <th>商品名</th>
                    <th>購入時の価格</th>
                    <th>購入数</th>
                    <th>小計</th>
                </tr>
            </thead>

            <?php foreach($details as $detail){ ?>
                <tr>
                    <td><?php print $detail['name']; ?></td>
                    <td><?php print $detail['price']; ?></td>
                    <td><?php print $detail['amount']; ?></td>
                    <td><?php print $detail['price'] * $detail['amount']; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>


</body>