<div class="card">
  <div class="card-header bg-white d-flex justify-content-between">
    <strong>All Notifications</strong>
    <div class="d-flex gap-2">
      <a href="<?= site_url('notifications/delete_all') ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete all notifications?')">
        <i class="bi bi-trash"></i> Delete All
      </a>
      <a href="<?= site_url('notifications/mark_all_read') ?>" class="btn btn-sm btn-light">
        <i class="bi bi-check2-all"></i> Mark all read
      </a>
    </div>
  </div>
  <div class="list-group list-group-flush">
    <?php if (empty($rows)): ?>
      <div class="p-4 text-center text-muted">No notifications.</div>
    <?php else: foreach ($rows as $n): ?>
      <div class="list-group-item list-group-item-action <?= $n['is_read'] ? '' : 'list-group-item-warning' ?>">
        <div class="d-flex justify-content-between align-items-start">
          <div class="flex-grow-1">
            <a href="<?= site_url('notifications/read/'.$n['notif_id']) ?>" class="text-decoration-none text-dark">
              <strong><?= htmlspecialchars($n['title']) ?></strong>
              <div><?= htmlspecialchars($n['message']) ?></div>
            </a>
            <small class="text-muted"><?= date('M d, Y H:i', strtotime($n['created_at'])) ?></small>
          </div>
          <a href="<?= site_url('notifications/delete/'.$n['notif_id']) ?>" class="btn btn-sm btn-light text-danger ms-2" onclick="return confirm('Delete this notification?')">
            <i class="bi bi-trash"></i>
          </a>
        </div>
      </div>
    <?php endforeach; endif; ?>
  </div>
</div>
