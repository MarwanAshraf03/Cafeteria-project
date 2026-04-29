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
    require __DIR__ . '/../components/side-bar.php';
    ?>
    <div class="main-content">
        <?php
        echo $content;
        require __DIR__ . '/../components/footer.php';
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>