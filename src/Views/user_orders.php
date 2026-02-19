<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
<div>
    <div class="container">
        <a href="./profile" target="_blank">Мой профиль<br></a>
        <a href="/cart" class="Cart">Корзина<br></a>
        <a href="/catalog" class="Catalog">Каталог</a>
        <h3>Мои заказы</h3>
        <div class="card-deck">
            <form action="/order" method = "POST">
                <?php foreach ($orders as $order): ?>
                    <!-- Каждая карточка товара с формой -->
                    <div class="card text-center">
                        <tr>
                            <form action="/order" method="POST" style="margin: 0;">   <!--а точно пост а не гет?-->
                                <input type="hidden" name="order_id" value="<?php echo $order->getOrderId(); ?>">
                                <button type="submit">Посмотреть заказ</button>
                            </form>
                            <td>Номер заказа: </td>
                            <td><?php echo  $order->getOrderId(); ?></td>
                            <td>Количество товаров:</td>
                            <td><?php echo  $order->getAmount(); ?></td>
                            <td>Имя получателя:</td>
                            <td><?php echo  $order->getContactName(); ?></td>
                            <td>Адрес доставки:</td>
                            <td><?php echo  $order->getAddress(); ?></td>
                            <td>Комментарий:</td>
                            <td><?php echo  $order->getComment(); ?></td>
                            <td><?php $c = 0; ?></td>
                            <td><?php $o = $order->getAmountProduct(); ?></td>
                            <td>Товары:</td>
                            <?php foreach ($order->getProducts() as $product): ?>
                            <div class="card text-center">
                                <td><?php echo $product->getProductName(); ?></td>
                                <td>Количество товара:</td>
                                <td><?php echo $o[$c]; ?></td>
                                <td><?php $c += 1; ?></td>
                                <td>Стоимость товара:</td>
                                <td><?php echo $product->getProductPrice(); ?></td>
                                <img class="card-img-top" src="<?php echo $product->getProductImageUrl(); ?>">
                            </div>
                            <?php endforeach; ?>
                        </tr>
                    </div> <!-- закрываем div.card -->
                <?php endforeach; ?>
            </form>
        </div> <!-- закрываем div.card-deck -->
        <a href="/logout" class="edit-mode-btn">Выйти из профиля<br></a>
    </div> <!-- закрываем div.container -->
</div>
</body>
</html>