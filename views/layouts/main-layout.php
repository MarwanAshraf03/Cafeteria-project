<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
</head>
<body>
    <?php 
    require __DIR__ . '/../components/nav-bar.php';
    echo $content;
    require __DIR__ . '/../components/side-bar.php';
    ?>
</body>
</html>