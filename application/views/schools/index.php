<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Schools</h5>
  <?php if ($_user['role'] === 'division'): ?>
    <a href="<?= site_url('schools/create') ?>" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-lg"></i> New School
    </a>
  <?php endif; ?>
</div>

<form method="get" class="card card-body py-2 mb-3">
  <div class="row g-2 align-items-end">
    <div class="col-md-4">
      <label class="form-label small mb-1">Search</label>
      <input type="text" name="q" class="form-control form-control-sm"
             value="<?= htmlspecialchars($filters['search'] ?? '') ?>"
             placeholder="Name, code, municipality...">
    </div>
    <div class="col-md-3">
      <label class="form-label small mb-1">Type</label>
      <select name="type" class="form-select form-select-sm">
        <option value="">All</option>
        <?php foreach (['Public','Private'] as $t): ?>
          <option value="<?= $t ?>" <?= ($filters['school_type'] ?? '') === $t ? 'selected':'' ?>><?= $t ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <?php if ($_user['role'] === 'regional'): ?>
      <div class="col-md-3">
        <label class="form-label small mb-1">Division</label>
        <select name="division_id" class="form-select form-select-sm">
          <option value="">All Divisions</option>
          <?php foreach ($divisions as $d): ?>
            <option value="<?= $d['division_id'] ?>"
              <?= (int)($filters['division_id'] ?? 0) === (int)$d['division_id'] ? 'selected':'' ?>>
              <?= htmlspecialchars($d['code']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    <?php endif; ?>
    <div class="col-auto">
      <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
      <a class="btn btn-sm btn-light" href="<?= site_url('schools') ?>">Clear</a>
    </div>
  </div>
</form>

<div class="card">
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light">
        <tr>
          <th>School Name</th><th>Code</th><th>Type</th>
          <th>Address</th>
          <?php if ($_user['role'] === 'regional'): ?><th>Division</th><?php endif; ?>
          <th>Status</th>
          <?php if ($_user['role'] === 'division'): ?><th class="text-end">Actions</th><?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($rows)): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">No schools.</td></tr>
        <?php else: foreach ($rows as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['school_name']) ?></td>
            <td><?= htmlspecialchars($r['school_code'] ?? '—') ?></td>
            <td>
              <span class="badge <?= $r['school_type'] === 'Private' ? 'bg-info' : 'bg-secondary' ?>">
                <?= htmlspecialchars($r['school_type']) ?>
              </span>
            </td>
            <td><?= htmlspecialchars(trim(($r['address'] ?? '').' '.($r['municipality'] ? '('.$r['municipality'].')' : ''))) ?: '—' ?></td>
            <?php if ($_user['role'] === 'regional'): ?>
              <td><?= htmlspecialchars($r['division_code'] ?? '—') ?></td>
            <?php endif; ?>
            <td>
              <?php if ((int)$r['is_active'] === 1): ?>
                <span class="badge bg-success">Active</span>
              <?php else: ?>
                <span class="badge bg-secondary">Inactive</span>
              <?php endif; ?>
            </td>
            <?php if ($_user['role'] === 'division'): ?>
              <td class="text-end">
                <a class="btn btn-sm btn-outline-primary" href="<?= site_url('schools/edit/'.$r['school_id']) ?>">
                  <i class="bi bi-pencil"></i>
                </a>
                <a class="btn btn-sm btn-outline-danger"
                   onclick="return confirm('Delete this school?');"
                   href="<?= site_url('schools/delete/'.$r['school_id']) ?>">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            <?php endif; ?>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
