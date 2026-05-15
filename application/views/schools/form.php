<div class="card">
  <div class="card-header bg-white"><strong><?= $is_edit ? 'Edit School' : 'New School' ?></strong></div>
  <div class="card-body">
    <?= validation_errors('<div class="alert alert-danger py-2">', '</div>') ?>

    <form method="post">
      <div class="row g-3">
        <div class="col-md-8">
          <label class="form-label">School Name *</label>
          <input type="text" name="school_name" class="form-control" required
                 value="<?= htmlspecialchars(set_value('school_name', $row['school_name'] ?? '')) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">School ID / Code</label>
          <input type="text" name="school_code" class="form-control"
                 value="<?= htmlspecialchars(set_value('school_code', $row['school_code'] ?? '')) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">School Type *</label>
          <select name="school_type" class="form-select" required>
            <?php $st = set_value('school_type', $row['school_type'] ?? 'Private'); ?>
            <option value="Private" <?= $st === 'Private' ? 'selected':'' ?>>Private</option>
            <option value="Public"  <?= $st === 'Public'  ? 'selected':'' ?>>Public</option>
          </select>
        </div>
        <div class="col-md-8">
          <label class="form-label">Address</label>
          <input type="text" name="address" class="form-control"
                 value="<?= htmlspecialchars(set_value('address', $row['address'] ?? '')) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Municipality / City</label>
          <input type="text" name="municipality" class="form-control"
                 value="<?= htmlspecialchars(set_value('municipality', $row['municipality'] ?? '')) ?>">
        </div>
        <div class="col-md-6 d-flex align-items-end">
          <div class="form-check">
            <input type="checkbox" name="is_active" id="is_active" class="form-check-input"
              <?= !$is_edit || !empty($row['is_active']) ? 'checked' : '' ?>>
            <label class="form-check-label" for="is_active">Active</label>
          </div>
        </div>
      </div>
      <hr>
      <a href="<?= site_url('schools') ?>" class="btn btn-light">Cancel</a>
      <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save</button>
    </form>
  </div>
</div>
