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
        <a href="/catalog" class="Cart">Каталог<br></a>
        <a href="/logout" class="edit-mode-btn">Выйти из профиля<br></a>
        <h3>Catalog</h3>
        <div class="card-deck">
                <!-- Каждая карточка товара с формой -->
                <div class="card text-center">
                    <img class="card-img-top" src="<?php echo $product->getImageUrl(); ?>">
                    <div class="card-body">
                        <p class="card-text text-muted"><?php echo $product->getName();?></p>
                        <h5 class="card-title"><?php echo $product->getDescription(); ?></h5>
                        <div class="card-footer">
                            <?php echo $product->getPrice();?>
                        </div>
                    </div>
                    <div style="display: flex; margin: 10px;">
                        <!-- Кнопка - -->
                        <form action="/product" method="POST" style="margin: 0;">
                            <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>">
                            <input type="hidden" name="action" value="minus">
                            <button type="submit" style="width: 30px; height: 30px;">-</button>
                        </form>

                        <!-- Количество -->
                        <span style="margin: 0 10px; min-width: 30px; text-align: center;">
                        <?php echo $productsAmount[$product->getId()] ?? 0; ?>
                        </span>

                        <!-- Кнопка + -->
                        <form action="/product" method="POST" style="margin: 0;">
                            <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>">
                            <input type="hidden" name="action" value="plus">
                            <button type="submit" style="width: 30px; height: 30px;">+</button>
                        </form>
                        <?php if (isset($errors['amount'])): print_r($errors['amount']); endif; ?>
                        <?php if (isset($errors['product_id'])): print_r($errors['product_id']); endif; ?>
                    </div>
                    <?php if (isset($productReviews)): ?>
                        <?php foreach ($productReviews as $review): ?>
                            <p><strong><?php echo $review['user_name']; ?></strong> - Оценка: <?php echo $review['rating']; ?>/5</p>
                            <p style="margin-left: 20px;">Отзыв: "<?php echo $review['review']; ?>"</p>
                            <hr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
        </div> <!-- закрываем div.card-deck -->
    </div> <!-- закрываем div.container -->
</div>
</body>
</html>