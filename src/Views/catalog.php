<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
<div>
    <div class="container">
        <a href="/profile" target="_blank">Мой профиль<br></a>
        <a href="/cart" class="Cart">
            Корзина <span class="badge">0</span><br>
        </a>
        <a href="/logout" class="edit-mode-btn">Выйти из профиля<br></a>
        <h3>Catalog</h3>
        <div class="card-deck">
            <?php if (isset($products)): ?>
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
                            <form class="add-form" method="POST" onsubmit="return false" style="margin: 0;">
                                <input type="hidden" name="product_id" value="<?php echo $product->getProductId(); ?>">
                                <input type="hidden" name="action" value="minus">
                                <input type="hidden" name="redirect_url" value="<?= $_SERVER['REQUEST_URI'] ?>">
                                <button type="submit" style="width: 30px; height: 30px;">-</button>
                            </form>

                            <!-- Количество -->
                            <span style="margin: 0 10px; min-width: 30px; text-align: center;">
                            <?php echo $product->getProductAmount() ?? 0; ?>
                            </span>

                            <!-- Кнопка + -->
                            <form class="decrease-form" method="POST" onsubmit="return false" style="margin: 0;">
                                <input type="hidden" name="product_id" value="<?php echo $product->getProductId(); ?>">
                                <input type="hidden" name="action" value="plus">
                                <input type="hidden" name="redirect_url" value="<?= $_SERVER['REQUEST_URI'] ?>">
                                <button type="submit" style="width: 30px; height: 30px;">+</button>
                            </form>
                            <?php if (isset($errors['amount'])): print_r($errors['amount']); endif; ?>
                            <?php if (isset($errors['product_id'])): print_r($errors['product_id']); endif; ?>
                        </div>
                        <div>
                            <form action="/product" method="POST" style="margin: 0;">
                                <input type="hidden" name="product_id" value="<?php echo $product->getProductId(); ?>">
                                <button type="submit">Посмотреть товар</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div> <!-- закрываем div.card-deck -->
    </div> <!-- закрываем div.container -->
</div>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/IYI/Cxo=" crossorigin="anonymous"></script>
<!--<script>-->
<!--    $("document").ready(function () {-->
<!--        $(.add-form).submit(function () {-->
<!--            $.ajax({-->
<!--                type: "POST",-->
<!--                url: "/add-product",-->
<!--                data: $(this).serialize(),-->
<!--                dataType: 'json',-->
<!--                success: function (response) {-->
<!--                    var amountSpan = form.closest('.card').find('span');-->
<!--                    amountSpan.text(response.amount);-->
<!--                },-->
<!--                error: function(xhr, status, error) {-->
<!--                    console.error('Ошибка при добавлении товара:', error);-->
<!--                }-->
<!--            });-->
<!--        });-->
<!--    });-->
<!--</script>-->
<script>
$("document").ready(function () {
    $('.add-form').submit(function () {
        $.ajax({
            type: "POST",
            url: "/add-product",
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                console.log('test');
                // Обновляем количество товаров в бейдже корзины
                $('.badge').text(response.count);
            },
            error: function(xhr, status, error) {
                console.error('Ошибка при добавлении товара:', error);
            }
            return false;
        });
    });
});
$("document").ready(function () {
    $('.decrease-form').submit(function () {
        $.ajax({
            type: "POST",
            url: "/add-product",
            data: $(this).serialize(),
            dataType: 'json',
            success: function (response) {
                console.log('test');
                // Обновляем количество товаров в бейдже корзины
                $('.badge').text(response.count);
            },
            error: function(xhr, status, error) {
                console.error('Ошибка при добавлении товара:', error);
            }
            return false;
        });
    });
});
</script>
<style>
    body {
        padding-top: 80px;
    }

    .show-cart li {
        display: flex;
    }
    .card {
        margin-bottom: 20px;
    }
    .card-img-top {
        width: 200px;
        height: 200px;
        align-self: center;
    }
</style>
