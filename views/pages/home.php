<?php

use App\Enums\Role;
use App\Services\Auth;
use App\Models\User;
// require_once('../../app/models/user.php');
// require_once('../../app/enums/Role.php');
ob_start();
?>
<div class="d-flex flex-column gap-4">
    <?php if ($latestOrder && (Auth::role() == Role::User)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="mb-1">Latest Order</h5>
                        <div class="text-muted small">#<?php echo $latestOrder['id']; ?> ·
                            <?php echo $latestOrder['created_at']; ?>
                        </div>
                        <div class="text-muted small">Status: <?php echo $latestOrder['status']; ?></div>
                    </div>
                    <div class="fw-bold text-success">$<?php echo number_format($latestOrder['total_price'], 2); ?></div>
                </div>
                <div class="mt-3">
                    <?php if (!empty($latestItems)): ?>
                        <ul class="list-unstyled mb-0">
                            <?php foreach ($latestItems as $item): ?>
                                <li class="d-flex justify-content-between small">
                                    <span><?php echo htmlspecialchars($item['product_name']); ?> x
                                        <?php echo $item['quantity']; ?></span>
                                    <span>$<?php echo number_format($item['item_total'], 2); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="text-muted small">No items found.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-1">Place a New Order</h3>
            <div class="text-muted">Click a product image to add it.</div>
        </div>
        <div class="order-total-pill">
            Total: <span id="order-total">$0.00</span>
        </div>
    </div>

    <form action="<?php echo base_path('orders/confirm'); ?>" method="POST" id="order-form">
        <?php if (Auth::role() === Role::Admin): ?>
            <label for="user_id">Select User To Place Order For:</label>
            <select name="user_id" id="user_id" class="form-select">
                <?php foreach (User::all() as $orderUser): ?>
                    <?php if (Role::tryFrom($orderUser['role']) == Role::User): ?>
                        <option style="color: black" value="<?= htmlspecialchars($orderUser['id']) ?>">
                            <?= htmlspecialchars($orderUser['name'] . " - " . $orderUser['id']) ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <br><br>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="row g-3">
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-6 col-xl-4">
                            <div class="product-card card h-100 border-0 shadow-sm"
                                data-product-id="<?php echo $product['id']; ?>"
                                data-price="<?php echo $product['price']; ?>">
                                <button type="button" class="product-image-btn" data-action="add">
                                    <img src="../storage/product-images/<?php echo htmlspecialchars($product['image_url']); ?>"
                                        alt="<?php echo htmlspecialchars($product['name']); ?>">
                                </button>
                                <div class="card-body">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($product['name']); ?></h6>
                                    <div class="text-muted small mb-3">$<?php echo number_format($product['price'], 2); ?>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button" class="btn btn-light btn-sm" data-action="decrease">-</button>
                                        <input class="form-control form-control-sm text-center quantity-input" type="number"
                                            min="0" name="items[<?php echo $product['id']; ?>]" value="0">
                                        <button type="button" class="btn btn-light btn-sm" data-action="increase">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">Order Details</h5>
                        <div class="mb-3">
                            <label class="form-label">Room</label>
                            <select class="form-select" name="room_id" required>
                                <option value="" selected disabled>Select room</option>
                                <?php foreach ($rooms as $room): ?>
                                    <option value="<?php echo $room['id']; ?>">
                                        <?php echo htmlspecialchars($room['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes / Comment</label>
                            <textarea class="form-control" name="notes" rows="4"
                                placeholder="Any special instructions..."></textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Payable now</span>
                            <span class="fw-bold" id="order-total-secondary">$0.00</span>
                        </div>
                        <button class="btn btn-success w-100">Confirm Order</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    (function () {
        var cards = document.querySelectorAll('.product-card');
        var totalEl = document.getElementById('order-total');
        var totalSecondaryEl = document.getElementById('order-total-secondary');

        function updateTotal() {
            var total = 0;
            cards.forEach(function (card) {
                var price = parseFloat(card.getAttribute('data-price')) || 0;
                var qtyInput = card.querySelector('.quantity-input');
                var qty = parseInt(qtyInput.value || '0', 10);
                total += price * qty;
            });
            var formatted = '$' + total.toFixed(2);
            totalEl.textContent = formatted;
            totalSecondaryEl.textContent = formatted;
        }

        cards.forEach(function (card) {
            var qtyInput = card.querySelector('.quantity-input');
            card.addEventListener('click', function (event) {
                var action = event.target.getAttribute('data-action');
                if (!action) {
                    return;
                }
                var current = parseInt(qtyInput.value || '0', 10);
                if (action === 'add' || action === 'increase') {
                    qtyInput.value = current + 1;
                }
                if (action === 'decrease' && current > 0) {
                    qtyInput.value = current - 1;
                }
                updateTotal();
            });

            qtyInput.addEventListener('change', updateTotal);
        });
    })();
</script>

<?php
$content = ob_get_clean();
$title = "Home";
$activePage = "home";
require __DIR__ . '/../layouts/main-layout.php';
?>