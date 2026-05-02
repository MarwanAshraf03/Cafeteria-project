<?php
use App\Enums\ProductStatus;
ob_start();
require_once __DIR__ . "/../../app/enums/ProductStatus.php";
?>
<div class="d-flex flex-column gap-4">
  <div class="d-flex justify-content-between align-items-center">
    <div>
      <h3 class="mb-1">All Products</h3>
      <p class="text-muted mb-0 small">Manage your cafeteria inventory and availability.</p>
    </div>
    <a href="/products/create" class="btn btn-success px-4 py-2 shadow-sm fw-semibold">
      + Add Product
    </a>
  </div>
  <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light text-muted">
          <tr>
            <th class="fw-semibold px-4 py-3">Product</th>
            <th class="fw-semibold py-3">Price</th>
            <th class="fw-semibold py-3">Image</th>
            <th class="fw-semibold px-4 py-3 text-end">Action</th>
          </tr>
        </thead>
        <tbody class="border-top-0">
          <?php foreach ($products as $product): ?>
            <tr>
              <td class="px-4 fw-medium text-dark">
                <?= htmlspecialchars($product['name']) ?>
              </td>
              <td class="text-muted fw-medium">
                <?= htmlspecialchars($product['price']) ?> <span class="small">EGP</span>
              </td>
              <td>
                <img src="../../storage/product-images/<?= htmlspecialchars($product['image_url']) ?>"
                  class="rounded-3 shadow-sm border" style="width: 60px; height: 60px; object-fit: cover;"
                  alt="<?= htmlspecialchars($product['name']) ?>">
              </td>
              <td class="px-4 text-end">
                <div class="d-flex justify-content-end gap-2">
                  <form action="/products/availability?id=<?= htmlspecialchars($product['id']) ?>" method="POST"
                    class="m-0">
                    <button type="submit"
                      class="btn btn-sm <?= ProductStatus::tryFrom($product['status']) === ProductStatus::Available ? 'btn-outline-success' : 'btn-outline-secondary' ?>"
                      onclick="return confirm('Change availability?')">
                      <?= htmlspecialchars(ProductStatus::tryFrom($product['status'])->value ?? 'UNKNOWN') ?>
                    </button>
                  </form>
                  <a href="/products/edit?id=<?= htmlspecialchars($product['id']) ?>"
                    class="btn btn-sm btn-outline-primary">
                    UPDATE
                  </a>
                  <form action="/products?id=<?= htmlspecialchars($product['id']) ?>" method="POST" class="m-0">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-sm btn-outline-danger"
                      onclick="return confirm('Are you sure you want to delete this product?')">
                      DELETE
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php
$content = ob_get_clean();
$title = "All Products";
$activePage = "products";
require __DIR__ . '/../layouts/main-layout.php';
?>