<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalog</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: sans-serif;
        }

        a {
            text-decoration: none;
            margin: 0 10px;
        }

        a:hover {
            text-decoration: underline;
        }

        h3 {
            line-height: 3em;
        }

        .card {
            max-width: 16rem;
            margin: 10px;
            display: inline-block;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card:hover {
            box-shadow: 1px 2px 10px lightgray;
            transition: 0.2s;
        }

        .card-header {
            font-size: 13px;
            color: gray;
            background-color: white;
        }

        .text-muted {
            font-size: 11px;
        }

        .card-footer {
            font-weight: bold;
            font-size: 18px;
            background-color: white;
        }

        .card-img-top {
            max-width: 100px;
            margin: 0 auto;
            display: block;
        }

        .container {
            text-align: center;
        }

        .card-deck {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
    </style>
</head>
<body>
<a href="/my_profile">My profile</a>
<a href="/cart"><i class="fas fa-shopping-cart"></i> Cart</a>
<a href="/add-product">Add product</a>
<a href="/logout">Logout</a>

<div class="container">
    <h3>Catalog</h3>
    <div class="card-deck">
        <?php if (!empty($productsWithCounts)): ?>
            <?php foreach ($productsWithCounts as $item): ?>
                <div class="card text-center">
                    <a href="#">
                        <div class="card-header">
                            Hit!
                        </div>
                        <?php
                        $product = $item['product'];
                        $count = $item['count'];
                        $img_url = htmlspecialchars($product->getImgUrl() ?? 'default_image.jpg');
                        ?>
                        <img class="card-img-top" src="<?= $img_url; ?>" alt="Card image">
                        <div class="card-body">
                            <p class="card-text text-muted"><?= htmlspecialchars($product->getDescription() ?? ''); ?></p>
                            <a href="#"><h5 class="card-title"><?= htmlspecialchars($product->getName() ?? ''); ?></h5></a>
                            <div class="card-footer">
                                Цена: <?= htmlspecialchars($product->getPrice() ?? '0'); ?>$
                                <br>
                                Количество: <?= htmlspecialchars($count ?? '0'); ?>

                                <form class="increase-form" onsubmit="return false" method="POST" style="display: inline;">
                                    <input type="hidden" name="productId" value="<?= htmlspecialchars($product->getId() ?? ''); ?>">
                                    <button type="submit">+</button>
                                </form>
                                <form class="decrease-form" onsubmit="return false" method="POST" style="display: inline;">
                                    <input type="hidden" name="productId" value="<?= htmlspecialchars($product->getId() ?? ''); ?>">
                                    <button type="submit">-</button>
                                </form>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>В каталоге нет товаров.</p>
        <?php endif; ?>
    </div>
    <p>Общее количество продуктов: <span class="product-count"><?= htmlspecialchars($totalProductCount ?? '0'); ?></span> </p>
    <p>Общая сумма продуктов: <span class="product-price"><?= htmlspecialchars($totalPrice ?? '0'); ?></span>> </p>
</div>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        $('.increase-form').on('submit', function () {
            $.ajax({
                type: 'POST',
                url: "/increase-product",
                data: $(this).serialize(),
                success: function (response) {
                    var result = JSON.parse(response);
                    $('.product-count').text(result.totalProductCount);
                    $('.product-price').text(result.totalPrice);
                }
            });
            return false;
        });

        $('.decrease-form').on('submit', function () {
            $.ajax({
                type: 'POST',
                url: "/decrease-product",
                data: $(this).serialize(),
                success: function (response) {
                    var result = JSON.parse(response);
                    $('.product-count').text(result.totalProductCount);
                    $('.product-price').text(result.totalPrice);
                }
            });
            return false;
        });
    });
</script>
