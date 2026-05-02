<?php

require_once __DIR__ . '/../models/product.php';
require_once __DIR__ . '/../models/room.php';
require_once __DIR__ . '/../models/order.php';
require_once __DIR__ . '/../models/order_item.php';
require_once __DIR__ . '/../models/category.php';
require_once __DIR__ . '/../services/Auth.php';

class ProductController
{
    public function home()
    {
        $products = Product::all();
        require __DIR__ . '/../../views/pages/products.php';
    }

    public static function destroy()
    {
        $product_id = $_GET['id'];
        Product::delete($product_id);
        header('Location: ' . base_path('products'));

    }

    public static function availability()
    {
        $product_id = $_GET['id'];
        Product::toggleAvailablitiy($product_id);
        header('Location: ' . base_path('products'));
    }

    //# update product
    public static function update()
    {
        $file = $_FILES['image'];

        if ($file['error'] === UPLOAD_ERR_OK) {

            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid('product_', true) . '.' . $extension;

            $uploadDir = __DIR__ . '/../../storage/product-images/';

            $destination = $uploadDir . $newFileName;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
            } else {
            }
        }
        // var_dump($_POST['image_url']);
        $product_id = $_GET['id'];
        $product['name'] = $_POST['name'];
        $product['price'] = $_POST['price'];
        $product['category_id'] = $_POST['category_id'];
        $product['image_url'] = $newFileName ?? $_POST['image_url'];
        // var_dump($product);
        Product::update($product_id, $product);
        header('Location: ' . base_path('products'));
    }
    //# create a product
    public static function create()
    {
        $file = $_FILES['image'];

        if ($file['error'] === UPLOAD_ERR_OK) {

            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid('product_', true) . '.' . $extension;

            $uploadDir = __DIR__ . '/../../storage/product-images/';

            $destination = $uploadDir . $newFileName;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
            } else {
            }
        }
        $product = [];
        $product['name'] = $_POST['name'];
        $product['price'] = $_POST['price'];
        $product['category_id'] = $_POST['category_id'];
        $product['image_url'] = $newFileName;
        Product::create($product);
        header('Location: ' . base_path('products'));
    }
    //# get the add product page
    public static function add()
    {
        $categories = Category::all();
        require __DIR__ . '/../../views/pages/admin/products-create.php';
    }
    //# get the update product page
    public static function edit()
    {
        $product = Product::findById($_GET['id']);
        $categories = Category::all();
        require __DIR__ . '/../../views/pages/admin/products-edit.php';
    }

}

