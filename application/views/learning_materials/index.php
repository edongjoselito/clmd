<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Learning Materials</h5>
  <?php if ($_user['role'] === 'division'): ?>
    <a href="<?= site_url('learning_materials/create') ?>" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg"></i> Submit Material
    </a>
  <?php endif; ?>
</div>

<form method="get" class="card card-body mb-3 py-2">
  <div class="row g-2 align-items-end">
    <div class="col-md-3">
      <label class="form-label small mb-1">Status</label>
      <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
        <option value="">All</option>
        <?php foreach (['Pending','Approved','Rejected','Revised'] as $s): ?>
          <option value="<?= $s ?>" <?= ($status ?? '') === $s ? 'selected':'' ?>><?= $s ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <?php if ($status): ?>
      <div class="col-auto">
        <a class="btn btn-sm btn-light" href="<?= site_url('learning-materials') ?>">Clear</a>
      </div>
    <?php endif; ?>
  </div>
</form>

<div class="card">
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light">
        <tr>
          <th>Title</th><th>Type</th><th>Grade / Subject</th><th>Quarter</th>
          <?php if ($_user['role'] === 'regional'): ?><th>Division</th><?php endif; ?>
          <th>Status</th><th>Submitted</th><th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($rows)): ?>
          <tr><td colspan="8" class="text-center text-muted py-4">No records.</td></tr>
        <?php else: foreach ($rows as $r):
          $badge = ['Pending'=>'warning','Approved'=>'success','Rejected'=>'danger','Revised'=>'primary'];
          $cls = $badge[$r['status']] ?? 'secondary';
        ?>
          <tr>
            <td><?= htmlspecialchars($r['title']) ?>
              <div class="small text-muted">by <?= htmlspecialchars($r['submitted_by_name'] ?? '') ?></div>
            </td>
            <td><?= htmlspecialchars($r['type']) ?></td>
            <td><?= htmlspecialchars($r['grade_level'] . ' / ' . $r['subject']) ?></td>
            <td><?= htmlspecialchars($r['quarter'] ?? '—') ?></td>
            <?php if ($_user['role'] === 'regional'): ?>
              <td><?= htmlspecialchars($r['division_code'] ?? $r['division_name'] ?? '—') ?></td>
            <?php endif; ?>
            <td><span class="badge bg-<?= $cls ?>"><?= htmlspecialchars($r['status']) ?></span></td>
            <td><?= date('M d, Y', strtotime($r['created_at'])) ?></td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-secondary"
                 href="<?= site_url('learning_materials/view/'.$r['material_id']) ?>">
                <i class="bi bi-eye"></i>
              </a>
              <?php if ($_user['role'] === 'regional'): ?>
                <a class="btn btn-sm btn-outline-primary"
                   href="<?= site_url('learning_materials/review/'.$r['material_id']) ?>">
                  <i class="bi bi-clipboard-check"></i>
                </a>
              <?php else: ?>
                <?php if ($r['status'] !== 'Approved'): ?>
                  <a class="btn btn-sm btn-outline-primary"
                     href="<?= site_url('learning_materials/edit/'.$r['material_id']) ?>">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <a class="btn btn-sm btn-outline-danger"
                     onclick="return confirm('Delete this material?');"
                     href="<?= site_url('learning_materials/delete/'.$r['material_id']) ?>">
                    <i class="bi bi-trash"></i>
                  </a>
                <?php endif; ?>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
