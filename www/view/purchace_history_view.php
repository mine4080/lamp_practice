<!DOCTYPE html>
<html lang="ja">
<head>
    <?php include VIEW_PATH . 'templates/head.php'; ?>
    <title>購入商品データ一覧</title>
</head>
<body>
    <?php
    include VIEW_PATH . 'templates/header_logined.php';
    ?>

    <div class="container">
    
        <h1>購入商品一覧</h1>

        <?php include VIEW_PATH . 'templates/messages.php'; ?>

        <table class="table table-bordered text-center">
            <thead class="thead-light">
                <tr>
                    <th>注文番号</th>
                    <th>購入日時</th>
                    <th>合計金額</th>
                    <th>購入明細表示</ht>
                </tr>
            </thead>

            <?php foreach($histories as $history){ ?>
                <tr>
                    <td><?php print $history['id']; ?></td>
                    <td><?php print $history['created']; ?></td>
                    <td><?php print $history['total_price']; ?></td>
                    <td>
                        <form method="post" action="purchace_details.php">
                            <input type="submit" value="購入明細">
                            <input type="hidden" name="id" value="<?php print h($history['id']); ?>">
                            <input type="hidden" name="token" value="<?php print $token; ?>">
                        </form>
                    </td>
                </tr>
            <?php } ?>  
        </table>
        
    </div>

</body>