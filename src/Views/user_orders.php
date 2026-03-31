<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Мои заказы</title>
</head>
<body>
<div>
    <div class="container">
        <a href="/profile" target="_blank">Мой профиль<br></a>
        <a href="/cart" class="Cart">Корзина<br></a>
        <a href="/catalog" class="Catalog">Каталог</a>
        <h3>Мои заказы</h3>
        <div class="card-deck">
            <?php foreach ($orders as $order): ?>
                <div class="card text-center">
                    <div class="order-info">
                        <form action="/order" method="POST" class="order-form">
                            <input type="hidden" name="order_id" value="<?php echo $order->getOrderId(); ?>">
                            <button type="submit" class="view-order-btn">Посмотреть заказ</button>
                        </form>

                        <div class="order-details">
                            <p><strong>Номер заказа:</strong> <?php echo $order->getOrderId(); ?></p>
                            <p><strong>Имя получателя:</strong> <?php echo $order->getContactName(); ?></p>
                            <p><strong>Адрес доставки:</strong> <?php echo $order->getAddress(); ?></p>
                            <p><strong>Комментарий:</strong> <?php echo $order->getComment(); ?></p>
                            <p><strong>Товары:</strong></p>
                        </div>

                        <?php foreach ($order->getOrderProducts() as $orderProduct): ?>
                            <div class="product-item">
                                <p><strong>Название:</strong> <?php echo $orderProduct->getProductName(); ?></p>
                                <p><strong>Количество товара:</strong> <?php echo $orderProduct->getProductAmount(); ?></p>
                                <p><strong>Итоговая стоимость товара:</strong> <?php echo $orderProduct->getProductTotalsum(); ?></p>
                                <img class="card-img-top" src="<?php echo $orderProduct->getProductImageUrl(); ?>" alt="Product image">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <a href="/logout" class="edit-mode-btn">Выйти из профиля</a>
    </div>
</div>
</body>
</html>
<style>
    .order-form {
        margin: 0;
        display: inline-block;
    }

    .view-order-btn {
        background: none;
        border: none;
        color: #007bff;
        cursor: pointer;
        text-decoration: underline;
        font-size: 14px;
        padding: 0;
    }

    .view-order-btn:hover {
        color: #0056b3;
    }
</style>