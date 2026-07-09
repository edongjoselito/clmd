<div class="card">
  <div class="card-header bg-white"><strong><?= $is_edit ? 'Edit Division Endorsement' : 'Division Endorsement' ?></strong></div>
  <div class="card-body">
    <?= validation_errors('<div class="alert alert-danger py-2">', '</div>') ?>

    <?php if ($is_edit && !empty($row['review_notes']) && in_array($row['status'], ['Revised','Rejected'], true)): ?>
      <div class="alert alert-warning">
        <strong>Reviewer Notes:</strong> <?= nl2br(htmlspecialchars($row['review_notes'])) ?>
      </div>
    <?php endif; ?>

    <?php if (empty($schools)): ?>
      <div class="alert alert-info">
        You have no active schools yet. Please <a href="<?= site_url('schools/create') ?>">add a school</a> first.
      </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
      
      <!-- School Selection -->
      <div class="row g-3">
        <div class="col-12">
          <label class="form-label">School *</label>
          <?php if ($is_edit): ?>
            <?php $school_name = '';
            foreach ($schools as $s) {
                if ((int)$s['school_id'] === (int)$row['school_id']) {
                    $school_name = $s['school_name'] . ' (' . $s['school_type'] . ')';
                    break;
                }
            } ?>
            <input type="text" class="form-control" value="<?= htmlspecialchars($school_name) ?>" disabled>
            <input type="hidden" name="school_id" value="<?= htmlspecialchars($row['school_id']) ?>">
          <?php else: ?>
            <select name="school_id" class="form-select select2" required style="width: 100%;">
              <option value="">— Select school —</option>
              <?php $sel = set_value('school_id', $row['school_id'] ?? ''); ?>
              <?php foreach ($schools as $s): ?>
                <option value="<?= $s['school_id'] ?>"
                  <?= (int)$sel === (int)$s['school_id'] ? 'selected':'' ?>>
                  <?= htmlspecialchars($s['school_name']) ?> (<?= $s['school_type'] ?>)
                </option>
              <?php endforeach; ?>
            </select>
          <?php endif; ?>
        </div>
      </div>

      <hr>

      <!-- Current and Strengthened Curriculum Side by Side -->
      <div class="row g-4">
        <!-- Current Curriculum -->
        <div class="col-md-6">
          <h5 class="mb-3">Current Curriculum</h5>
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">Track *</label>
              <select name="current_track" class="form-select" required>
                <option value="">— Select Track —</option>
                <option value="TVL Track" <?= set_value('current_track', $row['current_track'] ?? '') === 'TVL Track' ? 'selected' : '' ?>>TVL Track</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">Strand *</label>
              <select name="current_strand" class="form-select" required>
                <option value="">— Select Strand —</option>
                <option value="I.A. Strand" <?= set_value('current_strand', $row['current_strand'] ?? '') === 'I.A. Strand' ? 'selected' : '' ?>>I.A. Strand</option>
                <option value="H.E. Strand" <?= set_value('current_strand', $row['current_strand'] ?? '') === 'H.E. Strand' ? 'selected' : '' ?>>H.E. Strand</option>
                <option value="ICT Strand" <?= set_value('current_strand', $row['current_strand'] ?? '') === 'ICT Strand' ? 'selected' : '' ?>>ICT Strand</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">Specializations</label>
              <textarea name="current_specializations" class="form-control" rows="3"><?= htmlspecialchars(set_value('current_specializations', $row['current_specializations'] ?? '')) ?></textarea>
            </div>
          </div>
        </div>

        <!-- Strengthened Curriculum -->
        <div class="col-md-6">
          <h5 class="mb-3">Strengthened Curriculum</h5>
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">Track *</label>
              <select name="strengthened_track" class="form-select" required>
                <option value="">— Select Track —</option>
                <option value="TechPro Track" <?= set_value('strengthened_track', $row['strengthened_track'] ?? '') === 'TechPro Track' ? 'selected' : '' ?>>TechPro Track</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">Strand *</label>
              <select name="strengthened_strand" class="form-select" required>
                <option value="">— Select Strand —</option>
                <option value="Industrial Technologies" <?= set_value('strengthened_strand', $row['strengthened_strand'] ?? '') === 'Industrial Technologies' ? 'selected' : '' ?>>Industrial Technologies</option>
                <option value="Hospitality and Tourism" <?= set_value('strengthened_strand', $row['strengthened_strand'] ?? '') === 'Hospitality and Tourism' ? 'selected' : '' ?>>Hospitality and Tourism</option>
                <option value="ICT Support and Computer Programming Technologies" <?= set_value('strengthened_strand', $row['strengthened_strand'] ?? '') === 'ICT Support and Computer Programming Technologies' ? 'selected' : '' ?>>ICT Support and Computer Programming Technologies</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">Specializations</label>
              <textarea name="strengthened_specializations" class="form-control" rows="3"><?= htmlspecialchars(set_value('strengthened_specializations', $row['strengthened_specializations'] ?? '')) ?></textarea>
            </div>
          </div>
        </div>
      </div>

      <hr>
      <h5 class="mb-3">Attachments</h5>
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <label class="form-label">Certification of Compliance (PDF only, max 50MB) <?= !$is_edit ? '*' : '' ?></label>
          <input type="file" name="cert_file" class="form-control" accept=".pdf,application/pdf" <?= !$is_edit ? 'required' : '' ?>>
          <?php if (!empty($pair['certification']['file_path'])): ?>
            <small>Current: <a target="_blank" href="<?= base_url($pair['certification']['file_path']) ?>">view file</a></small>
          <?php endif; ?>
        </div>
        <div class="col-md-6">
          <label class="form-label">Endorsement (PDF only, max 50MB) <?= !$is_edit ? '*' : '' ?></label>
          <input type="file" name="endorse_file" class="form-control" accept=".pdf,application/pdf" <?= !$is_edit ? 'required' : '' ?>>
          <?php if (!empty($pair['endorsement']['file_path'])): ?>
            <small>Current: <a target="_blank" href="<?= base_url($pair['endorsement']['file_path']) ?>">view file</a></small>
          <?php endif; ?>
        </div>
      </div>

      <hr>
      <a href="<?= site_url('documents') ?>" class="btn btn-light">Cancel</a>
      <button type="submit" class="btn btn-primary">
        <i class="bi bi-cloud-upload"></i> <?= $is_edit ? 'Update Document' : 'Submit Both Documents' ?>
      </button>
    </form>
  </div>
</div>
