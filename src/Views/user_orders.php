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
            <?php foreach ($orders as $order): ?>
                <!-- Каждая карточка товара с формой -->
                <div class="card text-center">
                    <tr>
                        <td>Номер заказа: </td>
                        <td><?php echo  $order['id']; ?></td>
                        <td>Количество товаров:</td>
                        <td><?php echo  $order['amount']; ?></td>
                        <td>Имя получателя:</td>
                        <td><?php echo  $order['contact_name']; ?></td>
                        <td>Адрес доставки:</td>
                        <td><?php echo  $order['address']; ?></td>
                        <td>Комментарий:</td>
                        <td><?php echo  $order['comment']; ?></td>
                    </tr>
                </div> <!-- закрываем div.card -->
            <?php endforeach; ?>
        </div> <!-- закрываем div.card-deck -->
        <a href="/logout" class="edit-mode-btn">Выйти из профиля<br></a>
    </div> <!-- закрываем div.container -->
</div>
</body>
</html>