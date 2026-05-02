<?php
require_once("core/globals.php");
ob_start();
?>
<div class="mb-4">
    <h3 style="font-family:'Noto Serif'">Update Existing Product</h3>
    <p class="text-muted">Update Product</p>
</div>
<div class="card card-custom p-4 mb-4">

    <form class="row g-4" action="<?php echo base_path('products?id=' . $product['id']); ?>" method="POST"
        enctype="multipart/form-data">
        <input type="hidden" name="_method" value="PUT">

        <div class="col-12">
            <label class="form-label">Product Picture</label>
            <div class="d-flex align-items-center gap-4">
                <?php if ($product['image_url']): ?>
                    <img class="rounded-circle bg-light border d-flex align-items-center justify-content-center"
                        style="width:120px;height:120px;"
                        src="../../storage/product-images/<?= htmlspecialchars($product['image_url']) ?>" alt="product">
                <?php else: ?>
                    <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center"
                        style="width:120px;height:120px;">
                        📷
                    </div>
                <?php endif; ?>
                <div>
                    <input type="file" name="image">
                    <input type="hidden" name="image_url" value="<?= htmlspecialchars($product['image_url']) ?>">
                    <div class="text-muted small">Max 2MB</div>
                </div>
            </div>
        </div>

        <!-- Fields -->
        <div class="col-md-6">
            <label class="form-label">Product Name</label>
            <input class="form-control" type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>"
                required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Price</label>
            <input class="form-control" min="1" type="number" name="price"
                value="<?= htmlspecialchars($product['price']) ?>" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Category</label>
            <!-- <input class="form-control" type="password" name="password"> -->
            <select name="category_id" id="category_id">
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['id']) ?>" <?php if ($category['id'] == $product['category_id'])
                          echo "selected" ?>>
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-12 text-end">
            <button class="btn btn-outline-secondary me-2">Cancel</button>
            <button class="btn btn-success">Update</button>
        </div>
    </form>
</div>
<?php
$content = ob_get_clean();
$title = "Update Product";
// $activePage = "create-user";
require __DIR__ . '/../../layouts/main-layout.php';
?>