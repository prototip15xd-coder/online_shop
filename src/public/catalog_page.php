<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
    </head>

    <body>
        <div class="container">
            <a href="./profile" target="_blank">Мой профиль</a>
            <h3>Catalog</h3>
            <div class="card-deck">
                <?php foreach ($products as $product): ?>
                    <div class="card text-center">
                        <a href="#">
                            <div class="card-header">
                                Hit!
                            </div>
                            <img class="card-img-top" src="<?php echo $product['image_url']; ?>">
                            <div class="card-body">
                                <p class="card-text text-muted"><?php echo $product['name'];?></p>
                                <a href="#"><h5 class="card-title"><?php echo $product['description']; ?></h5></a>
                                <div class="card-footer">
                                    <?php echo $product['price'];?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </body>
</html>

