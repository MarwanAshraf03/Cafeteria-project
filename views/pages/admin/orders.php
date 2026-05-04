<?php
ob_start();

function order_status_badge($status)
{
  $normalized = strtoupper(trim((string) $status));
  switch ($normalized) {
    case 'PROCESSING':
      return ['Processing', 'warning'];
    case 'BEING_DELIVERED':
      return ['Out for delivery', 'info'];
    case 'DONE':
      return ['Done', 'success'];
    case 'CANCELED':
      return ['Canceled', 'danger'];
    default:
      return [$status, 'secondary'];
  }
}
?>
<div class="d-flex flex-column gap-4">
  <div class="d-flex justify-content-between align-items-center">
    <div>
      <h3 class="mb-1">Orders</h3>
      <div class="text-muted">Current orders to finish.</div>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table align-middle m-0" style="border-collapse: separate; border-spacing: 0;">
          <thead>
            <tr>
              <th class="p-3 border-bottom">Order Date</th>
              <th class="p-3 border-bottom">Name</th>
              <th class="p-3 border-bottom">Room</th>
              <th class="p-3 border-bottom">Ext.</th>
              <th class="p-3 border-bottom">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($ordersWithItems)): ?>
              <tr>
                <td colspan="5" class="text-center text-muted p-4">No pending orders.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($ordersWithItems as $order): ?>
                <tr>
                  <td class="p-3"><?php echo htmlspecialchars($order['created_at']); ?></td>
                  <td class="p-3"><?php echo htmlspecialchars($order['user_name']); ?></td>
                  <td class="p-3"><?php echo htmlspecialchars($order['room_name']); ?></td>
                  <td class="p-3"><?php echo htmlspecialchars($order['extension']); ?></td>
                  <td class="p-3">
                    <form method="POST" action="<?php echo base_path('admin/orders/deliver'); ?>" class="m-0">
                      <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                      <button class="btn btn-sm btn-outline-success">deliver</button>
                    </form>
                  </td>
                </tr>
                <tr>
                  <td colspan="5" class="p-0 border-bottom">
                    <div class="d-flex flex-wrap gap-4 p-4 table-light">
                      <?php foreach ($order['items'] as $item): ?>
                        <div class="text-center" style="width: 80px;">
                          <div class="position-relative d-inline-block">
                            <?php if (!empty($item['image_url'])): ?>
                              <?php $imgUrl = str_starts_with($item['image_url'], 'http') ? $item['image_url'] : base_path($item['image_url']); ?>
                              <img src="../../../storage/product-images/<?php echo htmlspecialchars($imgUrl); ?>"
                                alt="<?php echo htmlspecialchars($item['product_name']); ?>"
                                style="width: 60px; height: 60px; object-fit: cover;" class="rounded-circle mb-2 border">
                            <?php else: ?>
                              <div
                                class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mb-2 mx-auto"
                                style="width: 60px; height: 60px; font-size: 24px;">
                                <?php echo htmlspecialchars(substr($item['product_name'], 0, 1)); ?>
                              </div>
                            <?php endif; ?>
                            <span
                              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white">
                              <?php echo number_format($item['unit_price'], 0); ?> LE
                            </span>
                          </div>
                          <div class="fw-bold small text-truncate"
                            title="<?php echo htmlspecialchars($item['product_name']); ?>">
                            <?php echo htmlspecialchars($item['product_name']); ?>
                          </div>
                          <div class="small fw-semibold mt-1 h5"><?php echo $item['quantity']; ?></div>
                        </div>
                      <?php endforeach; ?>
                      <div class="ms-auto align-self-end text-end p-2 pb-0">
                        <span class="fw-bold fs-4">Total: EGP <?php echo number_format($order['total_price'], 0); ?></span>
                      </div>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <h5 class="mb-3">Canceled Orders</h5>
      <?php if (empty($canceledOrdersWithItems)): ?>
        <div class="text-muted">No canceled orders.</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table align-middle m-0" style="border-collapse: separate; border-spacing: 0;">
            <thead>
              <tr>
                <th class="p-3 border-bottom">Order Date</th>
                <th class="p-3 border-bottom">Name</th>
                <th class="p-3 border-bottom">Room</th>
                <th class="p-3 border-bottom">Ext.</th>
                <th class="p-3 border-bottom">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($canceledOrdersWithItems as $order): ?>
                <?php
                [$statusLabel, $statusClass] = order_status_badge($order['status']);
                ?>
                <tr>
                  <td class="p-3"><?php echo htmlspecialchars($order['created_at']); ?></td>
                  <td class="p-3"><?php echo htmlspecialchars($order['user_name']); ?></td>
                  <td class="p-3"><?php echo htmlspecialchars($order['room_name']); ?></td>
                  <td class="p-3"><?php echo htmlspecialchars($order['extension']); ?></td>
                  <td class="p-3"><span
                      class="badge bg-<?php echo $statusClass; ?>"><?php echo htmlspecialchars($statusLabel); ?></span></td>
                </tr>
                <tr>
                  <td colspan="5" class="p-0 border-bottom">
                    <div class="d-flex flex-wrap gap-4 p-4 table-light">
                      <?php foreach ($order['items'] as $item): ?>
                        <div class="text-center" style="width: 80px;">
                          <div class="position-relative d-inline-block">
                            <?php if (!empty($item['image_url'])): ?>
                              <?php $imgUrl = str_starts_with($item['image_url'], 'http') ? $item['image_url'] : base_path($item['image_url']); ?>
                              <img src="../../../storage/product-images/<?php echo htmlspecialchars($imgUrl); ?>"
                                alt="<?php echo htmlspecialchars($item['product_name']); ?>"
                                style="width: 60px; height: 60px; object-fit: cover;" class="rounded-circle mb-2 border">
                            <?php else: ?>
                              <div
                                class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mb-2 mx-auto"
                                style="width: 60px; height: 60px; font-size: 24px;">
                                <?php echo htmlspecialchars(substr($item['product_name'], 0, 1)); ?>
                              </div>
                            <?php endif; ?>
                            <span
                              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white">
                              <?php echo number_format($item['unit_price'], 0); ?> LE
                            </span>
                          </div>
                          <div class="fw-bold small text-truncate"
                            title="<?php echo htmlspecialchars($item['product_name']); ?>">
                            <?php echo htmlspecialchars($item['product_name']); ?>
                          </div>
                          <div class="small fw-semibold mt-1 h5"><?php echo $item['quantity']; ?></div>
                        </div>
                      <?php endforeach; ?>
                      <div class="ms-auto align-self-end text-end p-2 pb-0">
                        <span class="fw-bold fs-4">Total: EGP <?php echo number_format($order['total_price'], 0); ?></span>
                      </div>
                    </div>
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
<?php
$content = ob_get_clean();
$title = "Orders";
$activePage = "admin-orders";
require __DIR__ . '/../../layouts/main-layout.php';
?>