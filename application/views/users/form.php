<div class="card">
  <div class="card-header bg-white">
    <strong><?= $is_edit ? 'Edit User' : 'New User' ?></strong>
  </div>
  <div class="card-body">
    <?= validation_errors('<div class="alert alert-danger py-2">', '</div>') ?>

    <form method="post" autocomplete="off">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Username *</label>
          <input type="text" name="username" class="form-control" required
                 value="<?= htmlspecialchars(set_value('username', $user['username'] ?? '')) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Full Name *</label>
          <input type="text" name="full_name" class="form-control" required
                 value="<?= htmlspecialchars(set_value('full_name', $user['full_name'] ?? '')) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control"
                 value="<?= htmlspecialchars(set_value('email', $user['email'] ?? '')) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Position</label>
          <input type="text" name="position" class="form-control"
                 value="<?= htmlspecialchars(set_value('position', $user['position'] ?? '')) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Role *</label>
          <select name="role" class="form-select" required id="roleSelect">
            <?php $r = set_value('role', $user['role'] ?? 'division'); ?>
            <option value="regional" <?= $r === 'regional' ? 'selected':'' ?>>Regional</option>
            <option value="division" <?= $r === 'division' ? 'selected':'' ?>>Division</option>
          </select>
        </div>
        <div class="col-md-6" id="divisionWrap">
          <label class="form-label">Division</label>
          <select name="division_id" class="form-select">
            <option value="">— Select —</option>
            <?php $sel = set_value('division_id', $user['division_id'] ?? ''); ?>
            <?php foreach ($divisions as $d): ?>
              <option value="<?= $d['division_id'] ?>" <?= (int)$sel === (int)$d['division_id'] ? 'selected':'' ?>>
                <?= htmlspecialchars($d['code'] . ' - ' . $d['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label"><?= $is_edit ? 'New Password (leave blank to keep)' : 'Password *' ?></label>
          <input type="password" name="password" class="form-control" <?= $is_edit ? '' : 'required' ?>>
        </div>
        <div class="col-md-6 d-flex align-items-end">
          <div class="form-check">
            <input type="checkbox" name="is_active" id="is_active" class="form-check-input"
              <?= !$is_edit || !empty($user['is_active']) ? 'checked' : '' ?>>
            <label class="form-check-label" for="is_active">Active</label>
          </div>
        </div>
      </div>

      <hr>
      <a href="<?= site_url('users') ?>" class="btn btn-light">Cancel</a>
      <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save</button>
    </form>
  </div>
</div>

<script>
  const roleSel = document.getElementById('roleSelect');
  const divWrap = document.getElementById('divisionWrap');
  function toggleDiv() {
    divWrap.style.display = roleSel.value === 'regional' ? 'none' : '';
  }
  roleSel.addEventListener('change', toggleDiv);
  toggleDiv();
</script>
