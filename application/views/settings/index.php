<div class="card">
  <div class="card-header bg-white"><strong>System Settings</strong></div>
  <div class="card-body">
    <form method="post" enctype="multipart/form-data">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">CLMD Chief Name</label>
          <input type="text" name="chief_name" class="form-control"
                 value="<?= htmlspecialchars($settings['chief_name'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Position</label>
          <input type="text" name="chief_position" class="form-control"
                 value="<?= htmlspecialchars($settings['chief_position'] ?? '') ?>">
        </div>
        <div class="col-12">
          <label class="form-label">Letterhead Text (used on certificates)</label>
          <textarea name="letterhead_text" class="form-control" rows="3"><?= htmlspecialchars($settings['letterhead_text'] ?? '') ?></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">e-Signature Image (PNG/JPG, transparent PNG recommended)</label>
          <input type="file" name="signature" class="form-control" accept="image/png,image/jpeg">
          <?php if (!empty($settings['signature_path'])): ?>
            <div class="mt-2 p-2 border rounded bg-white">
              <small class="text-muted">Current:</small><br>
              <img src="<?= base_url($settings['signature_path']) ?>" style="max-height:80px;">
            </div>
          <?php endif; ?>
        </div>
      </div>
      <hr>
      <button class="btn btn-primary"><i class="bi bi-save"></i> Save Settings</button>
    </form>
  </div>
</div>
