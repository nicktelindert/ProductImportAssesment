<?php
/**
 * Helper functie om prijzen te formatteren als Euro's.
 */
if (!function_exists('formatPrice')) {
    function formatPrice(float $price): string {
        return '€ ' . number_format($price, 2, ',', '.');
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product->title) ?> - Details</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; max-width: 800px; margin: 0 auto; padding: 20px; }
        .back-link { display: inline-block; margin-bottom: 20px; text-decoration: none; color: #666; }
        .product-header { display: flex; gap: 30px; margin-bottom: 30px; align-items: flex-start; }
        .main-image { max-width: 300px; border-radius: 8px; border: 1px solid #ddd; }
        .gallery { display: flex; gap: 10px; margin-top: 15px; flex-wrap: wrap; }
        .gallery img { width: 80px; height: 80px; object-fit: cover; border-radius: 4px; border: 1px solid #eee; }
        .price-container { font-size: 1.5em; margin: 20px 0; }
        .price-original { text-decoration: line-through; color: #888; font-size: 0.8em; }
        .price-discount { color: #e44d26; font-weight: bold; }
        .badge { background: #eee; padding: 4px 12px; border-radius: 12px; font-size: 0.9em; display: inline-block; margin-bottom: 10px; }
        .specs { background: #f9f9f9; padding: 20px; border-radius: 8px; margin-top: 20px; }
        .specs h3 { margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .specs dl { display: grid; grid-template-columns: 120px 1fr; gap: 5px; }
        .specs dt { font-weight: bold; color: #666; }
    </style>
</head>
<body>

    <a href="/" class="back-link">← Terug naar overzicht</a>

    <div class="product-header">
        <div>
            <?php if ($product->thumbnail): ?>
                <img src="<?= htmlspecialchars($product->thumbnail) ?>" alt="<?= htmlspecialchars($product->title) ?>" class="main-image">
            <?php endif; ?>
            
            <div class="gallery">
                <?php foreach ($product->images as $image): ?>
                    <img src="<?= htmlspecialchars($image) ?>" alt="Product afbeelding">
                <?php endforeach; ?>
            </div>
        </div>

        <div>
            <span class="badge"><?= htmlspecialchars(ucfirst($product->category)) ?></span>
            <h1><?= htmlspecialchars($product->title) ?></h1>
            <p><strong>Merk:</strong> <?= htmlspecialchars($product->brand ?? '-') ?></p>

            <div class="price-container">
                <?php if ($product->discount_percentage > 0): ?>
                    <span class="price-original"><?= formatPrice($product->price) ?></span>
                    <span class="price-discount"><?= formatPrice($product->getDiscountedPrice()) ?></span>
                    <small>(<?= $product->discount_percentage ?>% korting)</small>
                <?php else: ?>
                    <?= formatPrice($product->price) ?>
                <?php endif; ?>
            </div>

            <p><?= nl2br(htmlspecialchars($product->description)) ?></p>
        </div>
    </div>

    <div class="specs">
        <h3>Specificaties</h3>
        <dl>
            <dt>ID:</dt><dd><?= $product->external_id ?></dd>
            <dt>Categorie:</dt><dd><?= htmlspecialchars($product->category) ?></dd>
            <?php if (!empty($product->dimensions)): ?>
                <dt>Breedte:</dt><dd><?= $product->dimensions['width'] ?? '-' ?> cm</dd>
                <dt>Hoogte:</dt><dd><?= $product->dimensions['height'] ?? '-' ?> cm</dd>
                <dt>Diepte:</dt><dd><?= $product->dimensions['depth'] ?? '-' ?> cm</dd>
            <?php endif; ?>
        </dl>
    </div>

</body>
</html>
