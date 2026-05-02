<?php
require_once("core/globals.php");
ob_start();
?>
<div class="mb-4">
    <h3 style="font-family:'Noto Serif'">Add New Category</h3>
    <p class="text-muted">Add Category</p>
</div>
<div class="card card-custom p-4 mb-4">

    <form class="row g-4" action="<?php echo base_path('categories'); ?>" method="POST">
        <div class="col-md-6">
            <label class="form-label">Category Name</label>
            <input class="form-control" type="text" name="name">
        </div>
        <div class="col-12 text-end">
            <button class="btn btn-outline-secondary me-2">Cancel</button>
            <button class="btn btn-success">Add Category</button>
        </div>
    </form>
</div>
<?php
$content = ob_get_clean();
$title = "Add Category";
// $activePage = "create-user";
require __DIR__ . '/../../layouts/main-layout.php';
?>