<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">System Users</h5>
  <a href="<?= site_url('users/create') ?>" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-lg"></i> New User
  </a>
</div>

<div class="card">
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light">
        <tr>
          <th>Email</th><th>Full Name</th><th>Role</th>
          <th>Division</th><th>Status</th><th>Last Login</th><th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($users)): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">No users.</td></tr>
        <?php else: foreach ($users as $u): ?>
          <tr>
            <td><?= htmlspecialchars($u['email'] ?: $u['username']) ?></td>
            <td><?= htmlspecialchars($u['full_name']) ?></td>
            <td>
              <span class="badge <?= $u['role'] === 'regional' ? 'badge-role-regional' : 'badge-role-division' ?>">
                <?= ucfirst($u['role']) ?>
              </span>
            </td>
            <td><?= htmlspecialchars($u['division_name'] ?? '—') ?></td>
            <td>
              <?php if ((int)$u['is_active'] === 1): ?>
                <span class="badge bg-success">Active</span>
              <?php else: ?>
                <span class="badge bg-secondary">Inactive</span>
              <?php endif; ?>
            </td>
            <td><?= $u['last_login'] ? date('M d, Y H:i', strtotime($u['last_login'])) : '—' ?></td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="<?= site_url('users/edit/'.$u['user_id']) ?>">
                <i class="bi bi-pencil"></i>
              </a>
              <?php if ((int)$u['user_id'] !== (int)$_user['user_id']): ?>
                <a class="btn btn-sm btn-outline-danger"
                   onclick="return confirm('Delete this user?');"
                   href="<?= site_url('users/delete/'.$u['user_id']) ?>">
                  <i class="bi bi-trash"></i>
                </a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
