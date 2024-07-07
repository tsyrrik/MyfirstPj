<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Удаление продукта из корзины</title>
</head>
<body>
<h1>Удаление продукта из корзины</h1>
<form action="/remove-product" method="POST">
    <label for="product_id">Идентификатор продукта:</label>
    <input type="number" id="product_id" name="product_id" required>
    <button type="submit">Удалить продукт</button>
</form>
</body>
</html>
