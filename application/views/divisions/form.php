<div class="card">
  <div class="card-header bg-white"><strong><?= $is_edit ? 'Edit Division' : 'New Division' ?></strong></div>
  <div class="card-body">
    <?= validation_errors('<div class="alert alert-danger py-2">', '</div>') ?>

    <form method="post">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
      <div class="row g-3">
        <div class="col-md-12">
          <label class="form-label">Name *</label>
          <input type="text" name="name" class="form-control" required
                 value="<?= htmlspecialchars(set_value('name', $div['name'] ?? '')) ?>">
        </div>
        <div class="col-md-8">
          <label class="form-label">Address</label>
          <input type="text" name="address" class="form-control"
                 value="<?= htmlspecialchars(set_value('address', $div['address'] ?? '')) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Contact</label>
          <input type="text" name="contact" class="form-control"
                 value="<?= htmlspecialchars(set_value('contact', $div['contact'] ?? '')) ?>">
        </div>
        <div class="col-12">
          <div class="form-check">
            <input type="checkbox" name="is_active" id="is_active" class="form-check-input"
              <?= !$is_edit || !empty($div['is_active']) ? 'checked' : '' ?>>
            <label class="form-check-label" for="is_active">Active</label>
          </div>
        </div>
      </div>
      <hr>
      <a href="<?= site_url('divisions') ?>" class="btn btn-light">Cancel</a>
      <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save</button>
    </form>
  </div>
</div>
