<?php
$role = $_user['role'];
?>
<div class="row g-3 mb-3">
  <div class="col-md-3">
    <div class="card stat-card p-3">
      <small class="text-muted">Pending Materials</small>
      <div class="num text-warning"><?= (int)$counts['Pending'] ?></div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card stat-card p-3">
      <small class="text-muted">Approved</small>
      <div class="num text-success"><?= (int)$counts['Approved'] ?></div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card stat-card p-3">
      <small class="text-muted">For Revision</small>
      <div class="num text-primary"><?= (int)$counts['Revised'] ?></div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card stat-card p-3">
      <small class="text-muted">Rejected</small>
      <div class="num text-danger"><?= (int)$counts['Rejected'] ?></div>
    </div>
  </div>
</div>

<?php if ($role === 'regional'): ?>
<div class="row g-3 mb-3">
  <div class="col-md-4">
    <div class="card stat-card p-3">
      <small class="text-muted">Total Users</small>
      <div class="num"><?= (int)$total_users ?></div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card stat-card p-3">
      <small class="text-muted">Schools Divisions</small>
      <div class="num"><?= (int)$total_divs ?></div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card stat-card p-3">
      <small class="text-muted">Active Curriculum</small>
      <div class="num"><?= (int)$total_curr ?></div>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="card">
  <div class="card-header bg-white"><strong>Recent Submissions</strong></div>
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr>
          <th>Title</th><th>Type</th><th>Grade / Subject</th>
          <?php if ($role === 'regional'): ?><th>Division</th><?php endif; ?>
          <th>Status</th><th>Submitted</th>
        </tr>
      </thead>
      <tbody>
      <?php if (empty($recent_subs)): ?>
        <tr><td colspan="6" class="text-center text-muted py-4">No submissions yet.</td></tr>
      <?php else: foreach ($recent_subs as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['title']) ?></td>
          <td><?= htmlspecialchars($r['type']) ?></td>
          <td><?= htmlspecialchars($r['grade_level'] . ' / ' . $r['subject']) ?></td>
          <?php if ($role === 'regional'): ?>
            <td><?= htmlspecialchars($r['division_name'] ?? '—') ?></td>
          <?php endif; ?>
          <td>
            <?php
              $badge = ['Pending'=>'warning','Approved'=>'success','Rejected'=>'danger','Revised'=>'primary'];
              $cls = $badge[$r['status']] ?? 'secondary';
            ?>
            <span class="badge bg-<?= $cls ?>"><?= htmlspecialchars($r['status']) ?></span>
          </td>
          <td><?= date('M d, Y', strtotime($r['created_at'])) ?></td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
