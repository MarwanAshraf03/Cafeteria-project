<?php
use App\Enums\ProductStatus;
ob_start();
require_once __DIR__ . "/../../app/enums/ProductStatus.php";
?>
<div class="d-flex flex-column gap-4">
  <div class="d-flex justify-content-between align-items-center">
    <div>
      <div class="d-flex justify-content-between align-items-center flex-row">
        <h3 class="mb-1">All Products</h3>
        <a href="/products/create">Add Product</a>
      </div>
      <div class="text-muted">Filter by date range and review your order status.</div>
    </div>
  </div>

  <table>
    <tr>
      <th>Product</th>
      <th>Price</th>
      <th>Image</th>
      <th>Action</th>
    </tr>
    <?php foreach ($products as $product): ?>
      <tr>
        <td><?= htmlspecialchars($product['name']) ?></td>
        <td><?= htmlspecialchars($product['price']) ?> EGP</td>
        <td><img src="<?= htmlspecialchars($product['product_picture_url']) ?>"
            alt="<?= htmlspecialchars($product['name']) ?>"></td>
        <td>
          <form action="/products/availability?id=<?= htmlspecialchars($product['id']) ?>" method="POST"
            style="display: inline;">
            <!-- <input type="hidden" name="_method" value="DELETE"> -->
            <button type="submit" class="btn btn-submit" onclick="return confirm('Are you sure?')">
              <?= htmlspecialchars(ProductStatus::tryFrom($product['status'])->value) ?>
            </button>
          </form>
          <form action="/products?id=<?= htmlspecialchars($product['id']) ?>" method="POST" style="display: inline;">
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn btn-submit" onclick="return confirm('Are you sure?')">DELETE</button>
          </form>
          <a href="#" class="btn btn-submit">UPDATE</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
<?php
$content = ob_get_clean();
$title = "All Products";
$activePage = "products";
require __DIR__ . '/../layouts/main-layout.php';
?>