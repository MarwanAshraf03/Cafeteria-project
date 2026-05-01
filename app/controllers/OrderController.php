<?php

use App\Enums\Role;
use App\Services\Auth;

require_once __DIR__ . '/../models/product.php';
require_once __DIR__ . '/../models/room.php';
require_once __DIR__ . '/../models/order.php';
require_once __DIR__ . '/../models/order_item.php';
require_once __DIR__ . '/../services/Auth.php';

class OrderController
{
    public function home()
    {
        $products = Product::all();
        $rooms = Room::all();
        $user = \App\Services\Auth::user();
        $latestOrder = null;
        $latestItems = [];

        if ($user) {
            $latestOrder = Order::latestForUser($user->id);
            if ($latestOrder) {
                $latestItems = OrderItem::forOrder($latestOrder['id']);
            }
        }

        require __DIR__ . '/../../views/pages/home.php';
    }

    public function store()
    {
        $user = Auth::user();
        if (!$user) {
            header('Location: ' . base_path('login'));
            return;
        }

        $user_id = null;

        $roomId = isset($_POST['room_id']) ? intval($_POST['room_id']) : 0;
        $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';
        $itemsInput = isset($_POST['items']) ? $_POST['items'] : [];

        $items = [];
        foreach ($itemsInput as $productId => $quantity) {
            $qty = intval($quantity);
            if ($qty > 0) {
                $items[intval($productId)] = $qty;
            }
        }

        if ($roomId <= 0 || empty($items)) {
            header('Location: ' . base_path(''));
            return;
        }

        $products = Product::findByIds(array_keys($items));
        $productMap = [];
        foreach ($products as $product) {
            $productMap[$product['id']] = $product;
        }

        $orderItems = [];
        $total = 0.0;

        foreach ($items as $productId => $qty) {
            if (!isset($productMap[$productId])) {
                continue;
            }
            $unitPrice = floatval($productMap[$productId]['price']);
            $itemTotal = $unitPrice * $qty;
            $total += $itemTotal;
            $orderItems[] = [
                'product_id' => $productId,
                'quantity' => $qty,
                'unit_price' => $unitPrice,
                'item_total' => $itemTotal
            ];
        }

        if (empty($orderItems)) {
            header('Location: ' . base_path(''));
            return;
        }

        if (Auth::role() == Role::Admin) {
            $user_id = $_POST['user_id'];
        }

        $orderId = Order::create($user_id ?? $user->id, $roomId, $notes, 'Processing', $total);
        OrderItem::addItems($orderId, $orderItems);

        header('Location: ' . base_path('/home'));
    }

    public function history()
    {
        $user = \App\Services\Auth::user();
        if (!$user) {
            header('Location: ' . base_path('login'));
            return;
        }

        $fromDate = isset($_GET['from']) ? $_GET['from'] : '';
        $toDate = isset($_GET['to']) ? $_GET['to'] : '';
        $orders = Order::forUserByDateRange($user->id, $fromDate, $toDate);

        $selectedOrderId = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        $selectedOrder = null;
        $selectedItems = [];

        if ($selectedOrderId > 0) {
            $selectedOrder = Order::findForUser($selectedOrderId, $user->id);
            if ($selectedOrder) {
                $selectedItems = OrderItem::forOrder($selectedOrderId);
            }
        }

        require __DIR__ . '/../../views/pages/orders.php';
    }

    public function cancel()
    {
        $user = \App\Services\Auth::user();
        if (!$user) {
            header('Location: ' . base_path('login'));
            return;
        }

        $orderId = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        if ($orderId <= 0) {
            header('Location: ' . base_path('orders'));
            return;
        }

        $order = Order::findForUser($orderId, $user->id);
        if ($order && $order['status'] === 'Processing') {
            Order::updateStatus($orderId, 'Canceled');
        }

        header('Location: ' . base_path('orders'));
    }
}
