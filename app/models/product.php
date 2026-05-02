<?php

// use App\Enums;
use App\Enums\ProductStatus;

require_once __DIR__ . '/../services/Database.php';
require_once __DIR__ . '/../enums/ProductStatus.php';

class Product
{
    public static function all()
    {
        $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM products ORDER BY name");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findByIds($ids)
    {
        if (empty($ids)) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($product)
    {
        $stmt = Database::getInstance()->getConnection()->prepare("INSERT INTO products (name, price, image_url, category_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$product['name'], $product['price'], $product['image_url'], $product['category_id']]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($id, $product)
    {
        $stmt = Database::getInstance()->getConnection()->prepare('UPDATE products SET name = ?, price = ?, image_url = ?, category_id = ? WHERE id = ?');
        $stmt->execute([$product['name'], $product['price'], $product['image_url'], $product['category_id'], $id]);
    }

    public static function delete($id)
    {
        $stmt = Database::getInstance()->getConnection()->prepare("DELETE FROM products WHERE id=?");
        $stmt->execute([$id]);
    }

    public static function toggleAvailablitiy($id)
    {
        $stmt = Database::getInstance()->getConnection()->prepare("SELECT status FROM products WHERE id=?");
        $stmt->execute([$id]);
        $productStatus = $stmt->fetch(PDO::FETCH_ASSOC)['status'];
        $stmt = Database::getInstance()->getConnection()->prepare("UPDATE products SET status=? WHERE id=?");
        $newStatus = ProductStatus::tryFrom($productStatus) == ProductStatus::Available ? ProductStatus::Unavailable->value : ProductStatus::Available->value;
        $stmt->execute([$newStatus, $id]);
    }
}
