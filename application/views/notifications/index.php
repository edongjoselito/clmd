<div class="card">
  <div class="card-header bg-white d-flex justify-content-between">
    <strong>All Notifications</strong>
    <a href="<?= site_url('notifications/mark_all_read') ?>" class="btn btn-sm btn-light">
      <i class="bi bi-check2-all"></i> Mark all read
    </a>
  </div>
  <div class="list-group list-group-flush">
    <?php if (empty($rows)): ?>
      <div class="p-4 text-center text-muted">No notifications.</div>
    <?php else: foreach ($rows as $n): ?>
      <a class="list-group-item list-group-item-action <?= $n['is_read'] ? '' : 'list-group-item-warning' ?>"
         href="<?= site_url('notifications/read/'.$n['notif_id']) ?>">
        <div class="d-flex justify-content-between">
          <strong><?= htmlspecialchars($n['title']) ?></strong>
          <small class="text-muted"><?= date('M d, Y H:i', strtotime($n['created_at'])) ?></small>
        </div>
        <div><?= htmlspecialchars($n['message']) ?></div>
      </a>
    <?php endforeach; endif; ?>
  </div>
</div>
