<?php $role = $_user['role']; ?>
<div class="row g-3 mb-3">
  <div class="col-md-3">
    <div class="card stat-card p-3">
      <small class="text-muted">For Approval</small>
      <div class="num text-warning"><?= (int)$counts['For Approval'] ?></div>
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

<div class="row g-3 mb-3">
  <?php if ($role === 'regional'): ?>
    <div class="col-md-4">
      <div class="card stat-card p-3"><small class="text-muted">Users</small>
        <div class="num"><?= (int)$total_users ?></div></div>
    </div>
    <div class="col-md-4">
      <div class="card stat-card p-3"><small class="text-muted">Divisions</small>
        <div class="num"><?= (int)$total_divs ?></div></div>
    </div>
    <div class="col-md-4">
      <div class="card stat-card p-3"><small class="text-muted">Schools (system-wide)</small>
        <div class="num"><?= (int)$total_schools ?></div></div>
    </div>
  <?php else: ?>
    <div class="col-md-4">
      <div class="card stat-card p-3"><small class="text-muted">Schools in your Division</small>
        <div class="num"><?= (int)$total_schools ?></div></div>
    </div>
  <?php endif; ?>
</div>

<div class="card">
  <div class="card-header bg-white">
    <strong><?= $role === 'regional' ? 'Pending Approvals' : 'Recent Submissions' ?></strong>
  </div>
  <div class="table-responsive">
    <table class="table table-hover mb-0">
      <thead class="table-light">
        <tr>
          <th>Title</th>
          <?php if ($role === 'regional'): ?><th>Division</th><?php endif; ?>
          <th>School</th><th>Type</th><th>Status</th><th>Submitted</th><th></th>
        </tr>
      </thead>
      <tbody>
      <?php if (empty($recent_subs)): ?>
        <tr><td colspan="7" class="text-center text-muted py-4">No documents.</td></tr>
      <?php else: foreach ($recent_subs as $r):
        $b = ['For Approval'=>'warning','Approved'=>'success','Rejected'=>'danger','Revised'=>'primary'];
      ?>
        <tr>
          <td><?= htmlspecialchars($r['document_title']) ?>
            <div class="small text-muted"><?= htmlspecialchars($r['document_type']) ?></div>
          </td>
          <?php if ($role === 'regional'): ?>
            <td><?= htmlspecialchars($r['division_code'] ?? $r['division_name'] ?? '—') ?></td>
          <?php endif; ?>
          <td><?= htmlspecialchars($r['school_name'] ?? '—') ?></td>
          <td><?= htmlspecialchars($r['school_type'] ?? '—') ?></td>
          <td><span class="badge bg-<?= $b[$r['status']] ?? 'secondary' ?>">
            <?= htmlspecialchars($r['status']) ?></span></td>
          <td><?= date('M d, Y', strtotime($r['created_at'])) ?></td>
          <td>
            <a class="btn btn-sm btn-outline-primary" href="<?= site_url('documents/view/'.$r['document_id']) ?>">
              <i class="bi bi-eye"></i>
            </a>
          </td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
