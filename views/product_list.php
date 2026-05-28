<?php
/**
 * Helper functie om prijzen te formatteren als Euro's.
 */
if (!function_exists('formatPrice')) {
    function formatPrice(float $price): string {
        return '€ ' . number_format($price, 2, ',', '.');
    }
}

function getSortUrl(string $column, ?string $currentSort, string $currentOrder): string {
    $params = $_GET;
    $params['sort'] = $column;
    $params['order'] = ($currentSort === $column && $currentOrder === 'ASC') ? 'DESC' : 'ASC';
    return '?' . http_build_query($params);
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
        th a { text-decoration: none; color: inherit; display: block; }
        th a:after { content: ' ↕'; opacity: 0.3; }
        th.active-asc a:after { content: ' ↑'; opacity: 1; }
        th.active-desc a:after { content: ' ↓'; opacity: 1; }
        .thumbnail { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }
        .price-original { text-decoration: line-through; color: #888; font-size: 0.9em; }
        .price-discount { color: #e44d26; font-weight: bold; }
        .badge { background: #eee; padding: 2px 8px; border-radius: 12px; font-size: 0.8em; }
        .filters { background: #f9f9f9; padding: 20px; border-radius: 8px; margin-bottom: 20px; display: flex; gap: 10px; align-items: flex-end; }
        .filters select, .filters button { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .filters label { display: block; font-size: 0.8em; margin-bottom: 4px; }
    </style>
</head>
<body>

    <h1>Productoverzicht</h1>

    <form method="GET" class="filters">
        <div>
            <label>Categorie</label>
            <select name="category">
                <option value="">Alle categorieën</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>" <?= ($filters['category'] === $cat) ? 'selected' : '' ?>>
                        <?= htmlspecialchars(ucfirst($cat)) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Merk</label>
            <select name="brand">
                <option value="">Alle merken</option>
                <?php foreach ($brands as $brand): ?>
                    <option value="<?= htmlspecialchars($brand) ?>" <?= ($filters['brand'] === $brand) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($brand) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit">Filteren</button>
        <a href="/" style="padding: 8px; text-decoration: none; color: #666;">Reset</a>
    </form>

    <p>Totaal aantal producten: <?= count($products) ?></p>

    <table>
        <thead>
            <tr>
                <th>Afbeelding</th>
                <th class="<?= ($sort === 'title') ? 'active-' . strtolower($order) : '' ?>">
                    <a href="<?= getSortUrl('title', $sort, $order) ?>">Titel</a>
                </th>
                <th class="<?= ($sort === 'brand') ? 'active-' . strtolower($order) : '' ?>">
                    <a href="<?= getSortUrl('brand', $sort, $order) ?>">Merk</a>
                </th>
                <th class="<?= ($sort === 'category') ? 'active-' . strtolower($order) : '' ?>">
                    <a href="<?= getSortUrl('category', $sort, $order) ?>">Categorie</a>
                </th>
                <th class="<?= ($sort === 'price') ? 'active-' . strtolower($order) : '' ?>">
                    <a href="<?= getSortUrl('price', $sort, $order) ?>">Prijs</a>
                </th>
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
                        <td>
                            <a href="/product/show/<?= $product->external_id ?>/<?= $product->getSlug() ?>"><?= htmlspecialchars($product->title) ?></a>
                        </td>
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