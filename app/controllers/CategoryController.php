<?php

require_once __DIR__ . '/../models/product.php';
require_once __DIR__ . '/../models/room.php';
require_once __DIR__ . '/../models/order.php';
require_once __DIR__ . '/../models/order_item.php';
require_once __DIR__ . '/../models/category.php';
require_once __DIR__ . '/../services/Auth.php';

class CategoryController
{
    // public function home()
    // {
    //     $products = Product::all();
    //     require __DIR__ . '/../../views/pages/products.php';
    // }

    // public static function destroy()
    // {
    //     $product_id = $_GET['id'];
    //     Product::delete($product_id);
    //     header('Location: ' . base_path('products'));

    // }

    // public static function availability()
    // {
    //     $product_id = $_GET['id'];
    //     Product::toggleAvailablitiy($product_id);
    //     header('Location: ' . base_path('products'));
    // }

    // //# update product
    // public static function update()
    // {
    //     $file = $_FILES['image'];

    //     if ($file['error'] === UPLOAD_ERR_OK) {

    //         $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    //         $newFileName = uniqid('product_', true) . '.' . $extension;

    //         $uploadDir = __DIR__ . '/../../storage/product-images/';

    //         $destination = $uploadDir . $newFileName;

    //         if (move_uploaded_file($file['tmp_name'], $destination)) {
    //         } else {
    //         }
    //     }
    //     // var_dump($_POST['image_url']);
    //     $product_id = $_GET['id'];
    //     $product['name'] = $_POST['name'];
    //     $product['price'] = $_POST['price'];
    //     $product['category_id'] = $_POST['category_id'];
    //     $product['image_url'] = $newFileName ?? $_POST['image_url'];
    //     // var_dump($product);
    //     Product::update($product_id, $product);
    //     header('Location: ' . base_path('products'));
    // }
    //# create a product
    public static function create()
    {
        $category = [];
        $category['name'] = $_POST['name'];
        Category::create($category);
        // $previousPage = $_SERVER['HTTP_REFERER'] ?? '/home';
        header('Location: ' . base_path('products/create'));
    }
    //# get the add product page
    public static function add()
    {
        $categories = Category::all();
        require __DIR__ . '/../../views/pages/admin/categories-create.php';
    }
    //# get the update product page
    // public static function edit()
    // {
    //     $product = Product::findById($_GET['id']);
    //     $categories = Category::all();
    //     require __DIR__ . '/../../views/pages/admin/products-edit.php';
    // }

}

