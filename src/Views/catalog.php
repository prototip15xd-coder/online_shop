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
        <a href="/logout" class="edit-mode-btn">Выйти из профиля<br></a>
        <h3>Catalog</h3>
        <div class="card-deck">
            <?php foreach ($products as $product): ?>
                <!-- Каждая карточка товара с формой -->
                <div class="card text-center">
                    <img class="card-img-top" src="<?php echo $product->getProductImageUrl(); ?>">
                    <div class="card-body">
                        <p class="card-text text-muted"><?php echo $product->getProductName();?></p>
                        <h5 class="card-title"><?php echo $product->getProductDescription(); ?></h5>
                        <div class="card-footer">
                            <?php echo $product->getProductPrice();?>
                        </div>
                    </div>
                    <div style="display: flex; margin: 10px;">
                        <!-- Кнопка - -->
                        <form action="/catalog" method="POST" style="margin: 0;">
                            <input type="hidden" name="product_id" value="<?php echo $product->getProductId(); ?>">
                            <input type="hidden" name="action" value="minus">
                            <button type="submit" style="width: 30px; height: 30px;">-</button>
                        </form>

                        <!-- Количество -->
                        <span style="margin: 0 10px; min-width: 30px; text-align: center;">
                        <?php echo $productsAmount[$product->getProductId()] ?? 0; ?>
                        </span>

                        <!-- Кнопка + -->
                        <form action="/catalog" method="POST" style="margin: 0;">
                            <input type="hidden" name="product_id" value="<?php echo $product->getProductId(); ?>">
                            <input type="hidden" name="action" value="plus">
                            <button type="submit" style="width: 30px; height: 30px;">+</button>
                        </form>
                        <?php if (isset($errors['amount'])): print_r($errors['amount']); endif; ?>
                        <?php if (isset($errors['product_id'])): print_r($errors['product_id']); endif; ?>
                    </div>
                    <div>
                        <form action="/product" method="POST" style="margin: 0;">   <!--а точно пост а не гет?-->
                            <input type="hidden" name="product_id" value="<?php echo $product->getProductId(); ?>">
                            <button type="submit">Посмотреть товар</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div> <!-- закрываем div.card-deck -->
    </div> <!-- закрываем div.container -->
</div>
</body>
</html>