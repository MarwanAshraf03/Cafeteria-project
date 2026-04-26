<?php
    ob_start();
?>
<h1>Home page</h1>
<?php
    $content = ob_get_clean();
    $title = "Home";
    $activePage = "home";
    require __DIR__ . '/../layouts/main-layout.php';
?>