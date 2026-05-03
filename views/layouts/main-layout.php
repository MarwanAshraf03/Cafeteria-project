<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@600;700&family=Be+Vietnam+Pro:wght@400;600&display=swap" rel="stylesheet">
    <link href="<?php echo base_path('style'); ?>" rel="stylesheet">
    <title><?php echo $title; ?></title>
</head>
<body>
    <?php
    require_once __DIR__ . '/../../app/services/Auth.php';
    if (!isset($user)) {
        $user = \App\Services\Auth::user();
    }
    if (!isset($activePage)) {
        $activePage = "";
    }
    ?>
    <?php 
    require __DIR__ . '/../components/side-bar.php';
    ?>
    <div class="main-content d-flex flex-column" style="min-height: 100vh;">
        <div class="flex-grow-1 p-4">
            <?php echo $content; ?>
        </div>
        <?php require __DIR__ . '/../components/footer.php'; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>