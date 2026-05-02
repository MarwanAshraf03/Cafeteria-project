<?php
ob_start();

$baseQuery = [
    'from' => $fromDate,
    'to' => $toDate,
    'user_id' => $selectedUserId > 0 ? $selectedUserId : '',
];

function checks_url($params) {
    $filtered = [];
    foreach ($params as $key => $value) {
        if ($value !== '' && $value !== null) {
            $filtered[$key] = $value;
        }
    }
    return base_path('admin/checks') . (empty($filtered) ? '' : ('?' . http_build_query($filtered)));
}
?>
<div class="d-flex flex-column gap-4">
  <div>
    <h3 class="mb-1">Checks</h3>
    <div class="text-muted">Review checks by user and date range, then drill into orders and details.</div>
  </div>

  <form class="row g-3 align-items-end" method="GET" action="<?php echo base_path('admin/checks'); ?>" id="checks-filter-form">
    <div class="col-md-3">
      <label class="form-label">Date from</label>
      <input type="date" class="form-control" name="from" id="from-date" value="<?php echo htmlspecialchars($fromDate); ?>">
    </div>
    <div class="col-md-3">
      <label class="form-label">Date to</label>
      <input type="date" class="form-control" name="to" id="to-date" value="<?php echo htmlspecialchars($toDate); ?>">
    </div>
    <div class="col-md-3">
      <label class="form-label">User</label>
      <select class="form-select" name="user_id">
        <option value="">All users</option>
        <?php foreach ($availableUsers as $availableUser): ?>
          <option value="<?php echo $availableUser['id']; ?>" <?php echo ($selectedUserId === (int)$availableUser['id']) ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($availableUser['name']); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <button class="btn btn-outline-success w-100">Apply Filters</button>
    </div>
  </form>

  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <h5 class="mb-3">Users Checks</h5>
      <?php if (empty($checksRows)): ?>
        <div class="text-muted">No checks found for this filter.</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead>
              <tr>
                <th>Name</th>
                <th class="text-end">Total amount</th>
                <th class="text-end">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($checksRows as $row): ?>
                <?php
                $isExpanded = $expandedUserId === (int)$row['user_id'];
                $toggleParams = $baseQuery;
                $toggleParams['expanded_user_id'] = $isExpanded ? '' : (int)$row['user_id'];
                $toggleParams['order_id'] = '';
                $toggleParams['page'] = $currentPage;
                ?>
                <tr>
                  <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                  <td class="text-end">$<?php echo number_format((float)$row['total_amount'], 2); ?></td>
                  <td class="text-end">
                    <a class="btn btn-sm btn-outline-primary" href="<?php echo checks_url($toggleParams); ?>">
                      <?php echo $isExpanded ? 'Hide Orders' : 'Show Orders'; ?>
                    </a>
                  </td>
                </tr>
                <?php if ($isExpanded): ?>
                  <tr>
                    <td colspan="3" class="bg-light-subtle">
                      <div class="p-2">
                        <?php if (empty($expandedOrders)): ?>
                          <div class="text-muted">No orders for this user in selected range.</div>
                        <?php else: ?>
                          <div class="table-responsive">
                            <table class="table table-sm align-middle mb-0">
                              <thead>
                                <tr>
                                  <th>Order Date</th>
                                  <th>Amount</th>
                                  <th>Status</th>
                                  <th class="text-end">Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php foreach ($expandedOrders as $order): ?>
                                  <?php
                                  $orderParams = $baseQuery;
                                  $orderParams['expanded_user_id'] = (int)$row['user_id'];
                                  $orderParams['order_id'] = (int)$order['id'];
                                  $orderParams['page'] = $currentPage;
                                  ?>
                                  <tr>
                                    <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                                    <td>$<?php echo number_format((float)$order['total_price'], 2); ?></td>
                                    <td><span class="badge bg-<?php echo $order['status'] === 'Processing' ? 'warning' : ($order['status'] === 'Out for delivery' ? 'info' : ($order['status'] === 'Done' ? 'success' : 'secondary')); ?>">
                          <?php echo $order['status']; ?>
                        </span></td>
                                    <td class="text-end">
                                      <a class="btn btn-sm btn-outline-secondary" href="<?php echo checks_url($orderParams); ?>">
                                        View Details
                                      </a>
                                    </td>
                                  </tr>
                                <?php endforeach; ?>
                              </tbody>
                            </table>
                          </div>
                        <?php endif; ?>
                      </div>
                    </td>
                  </tr>
                <?php endif; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <?php if ($totalPages > 1): ?>
    <nav aria-label="Checks pagination">
      <ul class="pagination justify-content-center mb-0">
        <?php
        $firstParams = $baseQuery;
        $firstParams['expanded_user_id'] = $expandedUserId > 0 ? $expandedUserId : '';
        $firstParams['order_id'] = $selectedOrderId > 0 ? $selectedOrderId : '';
        $firstParams['page'] = 1;

        $prevParams = $firstParams;
        $prevParams['page'] = max(1, $currentPage - 1);
        ?>
        <li class="page-item <?php echo $currentPage <= 1 ? 'disabled' : ''; ?>">
          <a class="page-link" href="<?php echo checks_url($firstParams); ?>">&laquo;</a>
        </li>
        <li class="page-item <?php echo $currentPage <= 1 ? 'disabled' : ''; ?>">
          <a class="page-link" href="<?php echo checks_url($prevParams); ?>">&lsaquo;</a>
        </li>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <?php
          $pageParams = $firstParams;
          $pageParams['page'] = $i;
          ?>
          <li class="page-item <?php echo $currentPage === $i ? 'active' : ''; ?>">
            <a class="page-link" href="<?php echo checks_url($pageParams); ?>"><?php echo $i; ?></a>
          </li>
        <?php endfor; ?>

        <?php
        $nextParams = $firstParams;
        $nextParams['page'] = min($totalPages, $currentPage + 1);

        $lastParams = $firstParams;
        $lastParams['page'] = $totalPages;
        ?>
        <li class="page-item <?php echo $currentPage >= $totalPages ? 'disabled' : ''; ?>">
          <a class="page-link" href="<?php echo checks_url($nextParams); ?>">&rsaquo;</a>
        </li>
        <li class="page-item <?php echo $currentPage >= $totalPages ? 'disabled' : ''; ?>">
          <a class="page-link" href="<?php echo checks_url($lastParams); ?>">&raquo;</a>
        </li>
      </ul>
    </nav>
  <?php endif; ?>

  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <h5 class="mb-3">Order Details</h5>
      <?php if ($selectedOrder): ?>
        <div class="mb-3">
          <div class="text-muted small">Order #<?php echo $selectedOrder['id']; ?></div>
          <div class="text-muted small">Placed: <?php echo htmlspecialchars($selectedOrder['created_at']); ?></div>
          <div class="text-muted small">Status: <?php echo htmlspecialchars($selectedOrder['status']); ?></div>
        </div>
        <?php if (empty($selectedItems)): ?>
          <div class="text-muted">No order items found.</div>
        <?php else: ?>
          <ul class="list-unstyled mb-3">
            <?php foreach ($selectedItems as $item): ?>
              <li class="d-flex justify-content-between mb-1">
                <span><?php echo htmlspecialchars($item['product_name']); ?> x <?php echo (int)$item['quantity']; ?></span>
                <span>$<?php echo number_format((float)$item['item_total'], 2); ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
          <div class="d-flex justify-content-between fw-bold">
            <span>Total</span>
            <span>$<?php echo number_format((float)$selectedOrder['total_price'], 2); ?></span>
          </div>
        <?php endif; ?>
      <?php else: ?>
        <div class="text-muted">Click an order to display its details.</div>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
  (function () {
    var form = document.getElementById('checks-filter-form');
    var fromInput = document.getElementById('from-date');
    var toInput = document.getElementById('to-date');

    function syncBounds() {
      if (fromInput.value) {
        toInput.min = fromInput.value;
      } else {
        toInput.removeAttribute('min');
      }

      if (toInput.value) {
        fromInput.max = toInput.value;
      } else {
        fromInput.removeAttribute('max');
      }
    }

    syncBounds();
    fromInput.addEventListener('change', syncBounds);
    toInput.addEventListener('change', syncBounds);

    form.addEventListener('submit', function (event) {
      if (fromInput.value && toInput.value && fromInput.value > toInput.value) {
        event.preventDefault();
        window.alert('Date from cannot be after date to.');
      }
    });
  })();
</script>
<?php
$content = ob_get_clean();
$title = "Checks";
$activePage = "admin-checks";
require __DIR__ . '/../layouts/main-layout.php';
?>
