<?php
    ob_start();
?>
<div class="d-flex flex-column gap-4">
  <div class="d-flex justify-content-between align-items-center">
    <div>
      <h3 class="mb-1">My Orders</h3>
      <div class="text-muted">Filter by date range and review your order status.</div>
    </div>
  </div>

  <form class="row g-3 align-items-end" method="GET" action="<?php echo base_path('orders'); ?>">
    <div class="col-md-4">
      <label class="form-label">From</label>
      <input type="date" class="form-control" name="from" value="<?php echo htmlspecialchars($fromDate); ?>">
    </div>
    <div class="col-md-4">
      <label class="form-label">To</label>
      <input type="date" class="form-control" name="to" value="<?php echo htmlspecialchars($toDate); ?>">
    </div>
    <div class="col-md-4">
      <button class="btn btn-outline-success w-100">Apply</button>
    </div>
  </form>

  <div class="row g-4">
    <div class="col-lg-7">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h5 class="mb-3">Orders</h5>
          <?php if (empty($orders)): ?>
            <div class="text-muted">No orders in this range.</div>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table align-middle">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($orders as $order): ?>
                    <tr>
                      <td>#<?php echo $order['id']; ?></td>
                      <td><?php echo $order['created_at']; ?></td>
                      <td>
                        <span class="badge bg-<?php echo $order['status'] === 'Processing' ? 'warning' : ($order['status'] === 'Out for delivery' ? 'info' : ($order['status'] === 'Done' ? 'success' : 'secondary')); ?>">
                          <?php echo $order['status']; ?>
                        </span>
                      </td>
                      <td>$<?php echo number_format($order['total_price'], 2); ?></td>
                      <td class="text-end">
                        <a class="btn btn-sm btn-outline-primary" href="<?php echo base_path('orders'); ?>?from=<?php echo htmlspecialchars($fromDate); ?>&to=<?php echo htmlspecialchars($toDate); ?>&order_id=<?php echo $order['id']; ?>">
                          View
                        </a>
                        <?php if ($order['status'] === 'Processing'): ?>
                          <form method="POST" action="<?php echo base_path('orders/cancel'); ?>" class="d-inline">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <button class="btn btn-sm btn-outline-danger">Cancel</button>
                          </form>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h5 class="mb-3">Order Details</h5>
          <?php if ($selectedOrder): ?>
            <div class="mb-3">
              <div class="text-muted small">Order #<?php echo $selectedOrder['id']; ?></div>
              <div class="text-muted small">Placed: <?php echo $selectedOrder['created_at']; ?></div>
              <div class="text-muted small">Status: <?php echo $selectedOrder['status']; ?></div>
            </div>
            <?php if (empty($selectedItems)): ?>
              <div class="text-muted">No items found.</div>
            <?php else: ?>
              <ul class="list-unstyled mb-3">
                <?php foreach ($selectedItems as $item): ?>
                  <li class="d-flex justify-content-between">
                    <span><?php echo htmlspecialchars($item['product_name']); ?> x <?php echo $item['quantity']; ?></span>
                    <span>$<?php echo number_format($item['item_total'], 2); ?></span>
                  </li>
                <?php endforeach; ?>
              </ul>
              <div class="d-flex justify-content-between fw-bold">
                <span>Total</span>
                <span>$<?php echo number_format($selectedOrder['total_price'], 2); ?></span>
              </div>
            <?php endif; ?>
          <?php else: ?>
            <div class="text-muted">Select an order to view its contents.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
    $content = ob_get_clean();
    $title = "My Orders";
    $activePage = "orders";
    require __DIR__ . '/../layouts/main-layout.php';
?>
