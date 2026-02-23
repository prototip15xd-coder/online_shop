<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
<div>
    <div class="container">
        <a href="/profile" target="_blank">Мой профиль<br></a>
        <a href="/cart" class="Cart">Корзина<br></a>
        <a href="/orders" class="Cart">Мои заказы<br></a>
        <a href="/logout" class="edit-mode-btn">Выйти из профиля<br></a>
        <h3>Order</h3>
        <div class="card-deck">
            <td>Номер заказа: </td>
            <td><?php echo  $order->getOrderId(); ?></td>
            <td>Имя получателя:</td>
            <td><?php echo  $order->getContactName(); ?></td>
            <td>Адрес доставки:</td>
            <td><?php echo  $order->getAddress(); ?></td>
            <td>Комментарий:</td>
            <td><?php echo  $order->getComment(); ?></td>
<!--            <td>--><?php //$c = 0; ?><!--</td>-->
<!--            <td>--><?php //$o = $order->getAmountProduct(); ?><!--</td>-->
            <td>Товары:</td>
            <?php foreach ($order->products as $product): ?>
                <div class="card text-center">
                    <td><?php echo $product->getProductName(); ?></td>
                    <td>Стоимость товара:</td>
                    <td><?php echo $product->getProductPrice(); ?></td>
                    <td>Количество товара:</td>
                    <td><?php echo $product->amount; ?></td>
                    <img class="card-img-top" src="<?php echo $product->getProductImageUrl(); ?>">
                </div>
            <?php endforeach; ?>
            </tr>
        </div> <!-- закрываем div.card -->
    </div> <!-- закрываем div.container -->
</div>
</body>
</html>
<style>
    /* Set a style for the submit/register button */
    .registerbtn {
        background-color: #04AA6D;
        color: white;
        padding: 16px 20px;
        margin: 8px 0;
        border: none;
        cursor: pointer;
        width: 100%;
        opacity: 0.9;
    }

    .registerbtn:hover {
        opacity:1;
    }

    /* Add a blue text color to links */
    a {
        color: dodgerblue;
    }

    /* Set a grey background color and center the text of the "sign in" section */
    .signin {
        background-color: #f1f1f1;
        text-align: center;
    }
</style>