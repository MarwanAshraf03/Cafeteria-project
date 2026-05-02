<?php
require_once("core/globals.php");
ob_start();
?>
<div class="mb-4">
    <h3 style="font-family:'Noto Serif'">Add New Product</h3>
    <p class="text-muted">Add Product</p>
</div>
<div class="card card-custom p-4 mb-4">

    <form class="row g-4" action="<?php echo base_path('products'); ?>" method="POST" enctype="multipart/form-data">
        <div class="col-12">
            <label class="form-label">Product Picture</label>
            <div class="d-flex align-items-center gap-4">
                <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center"
                    style="width:120px;height:120px;">
                    📷
                </div>
                <div>
                    <!-- <button class="btn btn-outline-secondary btn-sm">Choose File</button> -->
                    <input type="file" name="image">
                    <div class="text-muted small">Max 2MB</div>
                </div>
            </div>
        </div>

        <!-- Fields -->
        <div class="col-md-6">
            <label class="form-label">Product Name</label>
            <input class="form-control" type="text" name="name">
        </div>

        <div class="col-md-6">
            <label class="form-label">Price</label>
            <input class="form-control" min="1" type="number" name="price">
        </div>

        <div class="col-md-6">
            <label class="form-label">Category</label>
            <!-- <input class="form-control" type="password" name="password"> -->
            <select name="category_id" id="category_id">
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['id']) ?>"><?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-12 text-end">
            <button class="btn btn-outline-secondary me-2">Cancel</button>
            <button class="btn btn-success">Add User</button>
        </div>
    </form>
</div>
<?php
$content = ob_get_clean();
$title = "Add Product";
// $activePage = "create-user";
require __DIR__ . '/../../layouts/main-layout.php';
?>