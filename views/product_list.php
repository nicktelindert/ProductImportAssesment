<?php
/**
 * Helper functie om prijzen te formatteren als Euro's.
 */
function formatPrice(float $price): string {
    return '€ ' . number_format($price, 2, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productoverzicht</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; max-width: 1200px; margin: 0 auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f4f4f4; }
        tr:hover { background-color: #fafafa; }
        .thumbnail { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }
        .price-original { text-decoration: line-through; color: #888; font-size: 0.9em; }
        .price-discount { color: #e44d26; font-weight: bold; }
        .badge { background: #eee; padding: 2px 8px; border-radius: 12px; font-size: 0.8em; }
    </style>
</head>
<body>

    <h1>Productoverzicht</h1>
    <p>Totaal aantal producten: <?= count($products) ?></p>

    <table>
        <thead>
            <tr>
                <th>Afbeelding</th>
                <th>Titel</th>
                <th>Merk</th>
                <th>Categorie</th>
                <th>Prijs</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($products)): ?>
                <tr>
                    <td colspan="5">Geen producten gevonden. Draai eerst de import.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td>
                            <?php if ($product->thumbnail): ?>
                                <img src="<?= htmlspecialchars($product->thumbnail) ?>" alt="<?= htmlspecialchars($product->title) ?>" class="thumbnail">
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($product->title) ?></td>
                        <td><?= htmlspecialchars($product->brand ?? '-') ?></td>
                        <td><span class="badge"><?= htmlspecialchars($product->category) ?></span></td>
                        <td>
                            <?php if ($product->discount_percentage > 0): ?>
                                <div class="price-original"><?= formatPrice($product->price) ?></div>
                                <div class="price-discount"><?= formatPrice($product->getDiscountedPrice()) ?></div>
                            <?php else: ?>
                                <?= formatPrice($product->price) ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>