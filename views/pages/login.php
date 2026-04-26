<?php
    $title = "Login";
    ob_start();
?>
<h1>Login</h1>
<?php
    $content = ob_get_clean();
    require __DIR__ . '/../layouts/main-layout.php';
?>