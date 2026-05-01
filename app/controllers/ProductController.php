<?php

use App\Enums\Role;
use App\Services\Auth;

require_once __DIR__ . '/../models/product.php';
require_once __DIR__ . '/../models/room.php';
require_once __DIR__ . '/../models/order.php';
require_once __DIR__ . '/../models/order_item.php';
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
        // $product_id = $product_id[count($product_id) - 1];
        Product::delete($product_id);
        header('Location: ' . base_path('products'));

    }

    public static function availability()
    {
        $product_id = $_GET['id'];
        Product::toggleAvailablitiy($product_id);
        header('Location: ' . base_path('products'));
    }

    // update product
    public static function update()
    {
        $product_id = $_GET['id'];
        Product::update($product_id, $product_id);
        header('Location: ' . base_path('products'));
    }
    // create a product
    public static function create()
    {
    }
    // get the add product page
    public static function add()
    {
        header('Location: ' . base_path('create-product'));
    }
    // get the update product page
    public static function edit()
    {
        $product = Product::findByIds($_GET['id']);
        header('Location: ' . base_path('update-product'));
    }

}

// // get all products
// public static function home() {}
// // delete product
// public static function destroy() {}
// // update product
// public static function update() {}
// // create a product
// public static function create() {}
// // get the add product page
// public static function add() {}
// // get the update product page
// public static function edit() {}
// // make product available or unavailable
// public static function availability() {}
