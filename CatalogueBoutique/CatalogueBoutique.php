<?php
require_once 'Product.php';
require_once 'CatalogManager.php';

$productManager = new CatalogManager();
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Faire les vérifications
    $name = htmlspecialchars($_POST["name"]);
    $price = floatval($_POST["price"]);
    $category = htmlspecialchars($_POST["category"]);
    $stock = isset($_POST["stock"]);
    $imagePath = "default.jpg";

    if (isset($_FILES["Image"]) && $_FILES["Image"]["error"] == 0) {
        $target_dir = "images/";

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        if (!is_writable($target_dir)) {
            exit("Le dossier n'a pas d'accès en écriture: $target_dir");
        }
        $target_file = $target_dir . basename($_FILES["Image"]["name"]);
        if (move_uploaded_file($_FILES["Image"]["tmp_name"], $target_file)) {
            $imagePath = $target_file;
        } else {
            $message = "Problème lors du téléchargement de l'image";
        }
    }

    $product = new Product($name, $price, $category, $stock, $imagePath);
    $productManager->addProductToCatalog($product);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue de Produits</title>
    <link href="styles.css" rel="stylesheet">
</head>
<body onload="init()">
<h1>Gestion des Produits</h1>
<div class="container">
    <div id="entry">
        <form id="productForm" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Nom du produit : </label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="category">Catégorie :</label>
                <input type="text" id="category" name="category" required>
            </div>
            <div class="form-group">
                <label for="image">Sélectionner une image : </label>
                <input type="file" id="image" name="Image" accept=".jpg, .jpeg, .png" required>
            </div>
            <div class="form-group">
                <label for="price">Prix : </label>
                <input type="number" id="price" name="price" step="0.01" required>
            </div>
            <div class="form-group checkbox-inline">
                <label for="stock">Stock : </label>
                <input type="checkbox" id="stock" name="stock">
            </div>
            <div class="form-group">
                <button type="submit">Ajouter Produit</button>
            </div>
            <?php if (!empty($message)): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>
        </form>
        <div>
            <img id="img-src" src="default.jpg" alt="Image produit">
        </div>
    </div>
</div>
<div class="container">
    <div class="form-group" id="total">
        <button type="button" onclick="totalPrice()">Calculer le prix total</button>
    </div>
    <div id="search">
        <label for="search-products">Rechercher un produit</label>
        <input type="text" id="search-products" onkeyup="searchProducts()" placeholder="Rechercher par nom...">
    </div>
    <div id="filter">
        <label for="cat-filter">Filtrer par catégorie</label>
        <select id="cat-filter" onchange="filterProducts()">
            <option value="">Toutes les catégories</option>
        </select>
    </div>
    <div class="product-grid" id="product-grid"></div>
    <button type="button" onclick="applyDiscount()">Afficher la réduction de 10%</button>
    <button type="button" onclick="resetDisplay()">Réinitialisation</button>
</div>
<script src="script.js"></script>
</body>
</html>