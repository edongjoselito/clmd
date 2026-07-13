<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Schools Divisions</h5>
  <a href="<?= site_url('divisions/create') ?>" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg"></i> New Division
  </a>
</div>

<div class="card">
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light">
        <tr>
          <th>Name</th><th>Address</th><th>Contact</th>
          <th>Status</th><th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($divisions)): ?>
          <tr><td colspan="5" class="text-center text-muted py-4">No divisions.</td></tr>
        <?php else: foreach ($divisions as $d): ?>
          <tr>
            <td><?= htmlspecialchars($d['name']) ?></td>
            <td><?= htmlspecialchars($d['address'] ?? '—') ?></td>
            <td><?= htmlspecialchars($d['contact'] ?? '—') ?></td>
            <td>
              <?php if ((int)$d['is_active'] === 1): ?>
                <span class="badge bg-success">Active</span>
              <?php else: ?>
                <span class="badge bg-secondary">Inactive</span>
              <?php endif; ?>
            </td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="<?= site_url('divisions/edit/'.$d['division_id']) ?>">
                <i class="bi bi-pencil"></i>
              </a>
              <a class="btn btn-sm btn-outline-danger"
                 onclick="return confirm('Delete this division?');"
                 href="<?= site_url('divisions/delete/'.$d['division_id']) ?>">
                <i class="bi bi-trash"></i>
              </a>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
