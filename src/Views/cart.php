<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
</head>

<body>
<?php require_once '../Controllers/CartController.php'; ?>
<div class="container">
    <a href="/profile" target="_blank">Мой профиль<br></a>
    <a href="/catalog" class="catalog">Каталог<br></a>
    <h3>Cart</h3>
    <div class="card-deck">
        <?php foreach ($allProducts as $product): ?>
            <div class="card text-center">
                <a href="#">
                    <img class="card-img-top" src="<?php echo $product->getProduct()->getProductImageUrl(); ?>">
                    <div class="card-body">
                        <p class="card-text text-muted"><?php echo $product->getProduct()->getProductName();?></p>
                        <p class="card-text text-muted">Количество: <?php echo $product->getAmount();?></p>
                        <a href="#"><h5 class="card-title"><?php echo $product->getProduct()->getProductDescription(); ?></h5></a>
                        <div class="card-footer">
                            <?php echo $product->getProduct()->getProductPrice();?>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>

        <div class="total-sum">
            <span>Итоговая сумма заказа</span>
            <div class="total-sum-amount" id="cartTotalSum">
                <?php echo $cartTotalSum;?>
            </div>
        </div>

        <a href="/create-order" class="edit-mode-btn" id="checkoutBtn">Оформить заказ</a>
        <div id="errorMessage" class="error-message"></div>
    </div>
</div>

<a href="/logout" class="logout-btn">Выйти из профиля<br></a>
<script>
    // Получаем сумму заказа
    const totalSumElement = document.getElementById('cartTotalSum');
    const checkoutBtn = document.getElementById('checkoutBtn');
    const errorMessageDiv = document.getElementById('errorMessage');

    // Функция для проверки суммы и обновления состояния кнопки
    function updateCheckoutButton() {
        // Получаем сумму из текста (убираем пробелы и преобразуем в число)
        let totalSum = parseFloat(totalSumElement.innerText);

        // Проверяем, что сумма - число
        if (isNaN(totalSum)) {
            totalSum = 0;
        }

        if (totalSum < 100) {
            // Блокируем кнопку
            checkoutBtn.disabled = true;
            checkoutBtn.style.backgroundColor = '#6c757d';
            checkoutBtn.style.cursor = 'not-allowed';
            checkoutBtn.style.opacity = '0.6';

            // Показываем сообщение об ошибке
            errorMessageDiv.innerHTML = 'Сумма заказа должна быть больше 100 ₽';
            errorMessageDiv.style.display = 'block';
        } else {
            // Разблокируем кнопку
            checkoutBtn.disabled = false;
            checkoutBtn.style.backgroundColor = '#007bff';
            checkoutBtn.style.cursor = 'pointer';
            checkoutBtn.style.opacity = '1';

            // Скрываем сообщение об ошибке
            errorMessageDiv.style.display = 'none';
        }
    }

    // Вызываем функцию при загрузке страницы
    updateCheckoutButton();

    // Если сумма может меняться без перезагрузки (например, при удалении товаров),
    // добавь вызов этой функции в те места, где сумма обновляется
</script>
</body>
</html>

<style>
    /* Сброс стилей и базовые настройки */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        background-color: #f5f5f5;
        color: #333;
        line-height: 1.6;
    }

    /* Контейнер */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    /* Ссылки навигации */
    .container > a {
        display: inline-block;
        text-decoration: none;
        color: #007bff;
        font-weight: 500;
        margin-right: 20px;
        padding: 8px 0;
        transition: color 0.3s ease;
    }

    .container > a:hover {
        color: #0056b3;
        text-decoration: underline;
    }

    /* Заголовок */
    h3 {
        font-size: 28px;
        font-weight: 600;
        margin: 20px 0;
        color: #222;
        border-bottom: 2px solid #007bff;
        padding-bottom: 10px;
        display: inline-block;
    }

    /* Карточки товаров */
    .card-deck {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 24px;
        margin: 30px 0;
    }

    .card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .card a {
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .card-img-top {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background-color: #f8f9fa;
    }

    .card-body {
        padding: 16px;
    }

    .card-text {
        font-size: 14px;
        margin-bottom: 8px;
    }

    .card-text.text-muted {
        color: #6c757d;
    }

    .card-title {
        font-size: 16px;
        font-weight: 600;
        margin: 12px 0;
        color: #007bff;
        transition: color 0.3s ease;
    }

    .card:hover .card-title {
        color: #0056b3;
    }

    .card-footer {
        padding: 12px 16px;
        background-color: #f8f9fa;
        font-size: 18px;
        font-weight: 700;
        color: #28a745;
        text-align: center;
        border-top: 1px solid #e9ecef;
    }

    /* Блок итоговой суммы */
    .total-sum {
        grid-column: 1 / -1;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        margin-top: 20px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .total-sum span {
        display: block;
        font-size: 18px;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 10px;
    }

    .total-sum-amount {
        font-size: 32px;
        font-weight: 700;
        color: white;
    }

    /* Кнопки действий */
    .edit-mode-btn {
        display: inline-block;
        background-color: #007bff;
        color: white !important;
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        margin: 10px 10px 0 0;
        transition: background-color 0.3s ease, transform 0.1s ease;
        border: none;
        cursor: pointer;
    }

    .edit-mode-btn:hover {
        background-color: #0056b3;
        transform: scale(1.02);
    }

    .edit-mode-btn:active {
        transform: scale(0.98);
    }

    /* Кнопка выхода из профиля (вне контейнера, в правом нижнем углу) */
    .logout-btn {
        display: inline-block;
        background-color: #6c757d;
        color: white !important;
        padding: 6px 12px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 400;
        font-size: 12px;
        transition: background-color 0.3s ease;
        border: none;
        cursor: pointer;
        position: fixed;
        bottom: 15px;
        right: 15px;
        z-index: 1000;
    }

    .logout-btn:hover {
        background-color: #5a6268;
    }

    .logout-btn:active {
        background-color: #545b62;
    }
    .card {
        animation: fadeIn 0.4s ease-out;
    }
</style>