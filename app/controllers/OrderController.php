<?php

require_once __DIR__ . '/../models/product.php';
require_once __DIR__ . '/../models/room.php';
require_once __DIR__ . '/../models/order.php';
require_once __DIR__ . '/../models/order_item.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../services/Auth.php';

class OrderController {
    private function parseDateInput($value) {
        if (!is_string($value) || trim($value) === '') {
            return '';
        }

        $trimmed = trim($value);
        $date = \DateTime::createFromFormat('Y-m-d', $trimmed);
        if (!$date || $date->format('Y-m-d') !== $trimmed) {
            return '';
        }
        return $trimmed;
    }

    private function parsePositiveIntInput($value) {
        if ($value === null || $value === '') {
            return 0;
        }

        if (!is_numeric($value)) {
            return 0;
        }

        $number = intval($value);
        return $number > 0 ? $number : 0;
    }

    public function home() {
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

    public function store() {
        $user = \App\Services\Auth::user();
        if (!$user) {
            header('Location: ' . base_path('login'));
            return;
        }

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

        $orderId = Order::create($user->id, $roomId, $notes, 'Processing', $total);
        OrderItem::addItems($orderId, $orderItems);

        header('Location: ' . base_path(''));
    }

    public function history() {
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

    public function cancel() {
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

    public function adminChecks() {
        $user = \App\Services\Auth::user();
        if (!$user) {
            header('Location: ' . base_path('login'));
            return;
        }
        if (strtoupper($user->role) !== 'ADMIN') {
            header('Location: ' . base_path(''));
            return;
        }

        $fromDate = $this->parseDateInput($_GET['from'] ?? '');
        $toDate = $this->parseDateInput($_GET['to'] ?? '');
        if ($fromDate !== '' && $toDate !== '' && $fromDate > $toDate) {
            $toDate = $fromDate;
        }

        $selectedUserId = $this->parsePositiveIntInput($_GET['user_id'] ?? 0);
        $expandedUserId = $this->parsePositiveIntInput($_GET['expanded_user_id'] ?? 0);
        $selectedOrderId = $this->parsePositiveIntInput($_GET['order_id'] ?? 0);
        $currentPage = $this->parsePositiveIntInput($_GET['page'] ?? 1);
        if ($currentPage <= 0) {
            $currentPage = 1;
        }

        $perPage = 5;
        $availableUsers = User::allCustomers();

        $totalUsersWithChecks = Order::countChecksUsers($selectedUserId, $fromDate, $toDate);
        $totalPages = max(1, (int)ceil($totalUsersWithChecks / $perPage));
        if ($currentPage > $totalPages) {
            $currentPage = $totalPages;
        }
        $offset = ($currentPage - 1) * $perPage;
        $checksRows = Order::checksSummaryByUser($selectedUserId, $fromDate, $toDate, $perPage, $offset);

        if ($expandedUserId <= 0 && $selectedUserId > 0) {
            $expandedUserId = $selectedUserId;
        }

        $expandedOrders = [];
        if ($expandedUserId > 0) {
            $expandedOrders = Order::ordersForChecksUser($expandedUserId, $fromDate, $toDate);
        }

        $selectedOrder = null;
        $selectedItems = [];
        if ($expandedUserId > 0 && $selectedOrderId > 0) {
            $selectedOrder = Order::findForChecksUser($selectedOrderId, $expandedUserId, $fromDate, $toDate);
            if ($selectedOrder) {
                $selectedItems = OrderItem::forOrder($selectedOrderId);
            }
        }

        require __DIR__ . '/../../views/pages/admin-checks.php';
    }
}
