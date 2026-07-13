<div class="card">
  <div class="card-header bg-white"><strong>System Settings</strong></div>
  <div class="card-body">
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
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
        <div class="col-md-4">
          <label class="form-label">Letterhead Image (PNG/JPG)</label>
          <input type="file" name="letterhead" class="form-control" accept="image/png,image/jpeg">
          <?php if (!empty($settings['letterhead_path'])): ?>
            <div class="mt-2 p-2 border rounded bg-white">
              <small class="text-muted">Current:</small><br>
              <img src="<?= base_url($settings['letterhead_path']) ?>" style="max-height:100px;">
            </div>
          <?php endif; ?>
        </div>
        <div class="col-md-4">
          <label class="form-label">Footer Image (PNG/JPG)</label>
          <input type="file" name="footer" class="form-control" accept="image/png,image/jpeg">
          <?php if (!empty($settings['footer_path'])): ?>
            <div class="mt-2 p-2 border rounded bg-white">
              <small class="text-muted">Current:</small><br>
              <img src="<?= base_url($settings['footer_path']) ?>" style="max-height:80px;">
            </div>
          <?php endif; ?>
        </div>
        <div class="col-md-4">
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
      <h6 class="mb-3">Curriculum Options</h6>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Current Curriculum Tracks (one per line)</label>
          <textarea name="current_tracks" class="form-control" rows="4" placeholder="TVL Track"><?= htmlspecialchars($settings['current_tracks'] ?? '') ?></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">Current Curriculum Strands (one per line)</label>
          <textarea name="current_strands" class="form-control" rows="4" placeholder="I.A. Strand&#10;H.E. Strand&#10;ICT Strand"><?= htmlspecialchars($settings['current_strands'] ?? '') ?></textarea>
        </div>
        <div class="col-12">
          <label class="form-label">Current Curriculum Specializations (JSON format: {"Strand Name": ["Spec1", "Spec2"]})</label>
          <textarea name="current_specializations" class="form-control" rows="6" placeholder='{"I.A. Strand": ["Automotive", "Electrical"], "H.E. Strand": ["Cookery", "Bartending"], "ICT Strand": ["Computer Programming", "Web Development"]}'><?= htmlspecialchars($settings['current_specializations'] ?? '') ?></textarea>
          <small class="text-muted">Map each strand to its available specializations as a JSON object.</small>
        </div>
        <div class="col-md-6">
          <label class="form-label">Strengthened Curriculum Tracks (one per line)</label>
          <textarea name="strengthened_tracks" class="form-control" rows="4" placeholder="TechPro Track"><?= htmlspecialchars($settings['strengthened_tracks'] ?? '') ?></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">Strengthened Curriculum Strands (one per line)</label>
          <textarea name="strengthened_strands" class="form-control" rows="4" placeholder="Industrial Technologies&#10;Hospitality and Tourism&#10;ICT Support and Computer Programming Technologies"><?= htmlspecialchars($settings['strengthened_strands'] ?? '') ?></textarea>
        </div>
        <div class="col-12">
          <label class="form-label">Strengthened Curriculum Specializations (JSON format: {"Strand Name": ["Spec1", "Spec2"]})</label>
          <textarea name="strengthened_specializations" class="form-control" rows="6" placeholder='{"Industrial Technologies": ["Automotive", "Electrical"], "Hospitality and Tourism": ["Cookery", "Bartending"], "ICT Support and Computer Programming Technologies": ["Computer Programming", "Web Development"]}'><?= htmlspecialchars($settings['strengthened_specializations'] ?? '') ?></textarea>
          <small class="text-muted">Map each strand to its available specializations as a JSON object.</small>
        </div>
      </div>
      <hr>
      <button class="btn btn-primary"><i class="bi bi-save"></i> Save Settings</button>
    </form>
  </div>
</div>
