<div class="card">
  <div class="card-header bg-white">
    <strong><?= $is_edit ? 'Edit User' : 'New User' ?></strong>
  </div>
  <div class="card-body">
    <?= validation_errors('<div class="alert alert-danger py-2">', '</div>') ?>

    <form method="post" autocomplete="off">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Full Name *</label>
          <input type="text" name="full_name" class="form-control" required
                 value="<?= htmlspecialchars(set_value('full_name', $user['full_name'] ?? '')) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Email *</label>
          <input type="email" name="email" class="form-control" required
                 value="<?= htmlspecialchars(set_value('email', $user['email'] ?? '')) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Position</label>
          <input type="text" name="position" class="form-control"
                 value="<?= htmlspecialchars(set_value('position', $user['position'] ?? '')) ?>">
        </div>
        <?php if ($_user['role'] === 'regional'): ?>
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
        <?php else: ?>
          <div class="col-md-6">
            <label class="form-label">Role</label>
            <input type="text" class="form-control" value="Division" disabled>
            <input type="hidden" name="role" value="division">
          </div>
          <div class="col-md-6">
            <label class="form-label">Division</label>
            <?php $my_div = array_values(array_filter($divisions, fn($d) => (int)$d['division_id'] === (int)$_user['division_id']))[0] ?? null; ?>
            <input type="text" class="form-control" value="<?= $my_div ? htmlspecialchars($my_div['code'] . ' - ' . $my_div['name']) : '' ?>" disabled>
            <input type="hidden" name="division_id" value="<?= htmlspecialchars($_user['division_id']) ?>">
          </div>
        <?php endif; ?>
        <div class="col-md-6">
          <label class="form-label"><?= $is_edit ? 'New Password (leave blank to keep)' : 'Password *' ?></label>
          <div class="input-group">
            <input type="password" name="password" id="passwordInput" class="form-control" <?= $is_edit ? '' : 'required' ?>>
            <button class="btn btn-outline-secondary" type="button" id="togglePassword" title="Show password">
              <i class="bi bi-eye" id="togglePasswordIcon"></i>
            </button>
          </div>
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
  if (roleSel && divWrap) {
    function toggleDiv() {
      divWrap.style.display = roleSel.value === 'regional' ? 'none' : '';
    }
    roleSel.addEventListener('change', toggleDiv);
    toggleDiv();
  }

  const pwdInput = document.getElementById('passwordInput');
  const pwdBtn = document.getElementById('togglePassword');
  const pwdIcon = document.getElementById('togglePasswordIcon');
  if (pwdInput && pwdBtn && pwdIcon) {
    pwdBtn.addEventListener('click', function () {
      const show = pwdInput.type === 'password';
      pwdInput.type = show ? 'text' : 'password';
      pwdIcon.classList.toggle('bi-eye', !show);
      pwdIcon.classList.toggle('bi-eye-slash', show);
      pwdBtn.title = show ? 'Hide password' : 'Show password';
    });
  }
</script>
