<div class="card">
  <div class="card-header bg-white"><strong><?= $is_edit ? 'Edit Document' : 'Submit Document' ?></strong></div>
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
      <div class="row g-3">
        <div class="col-12">
          <label class="form-label">School *</label>
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
        </div>
      </div>

      <hr>
      <h5 class="mb-3">Certification of Compliance</h5>
      <div class="row g-3 mb-4">
        <div class="col-12">
          <label class="form-label">Document Title *</label>
          <input type="text" name="cert_title" class="form-control" required
                 value="<?= htmlspecialchars(set_value('cert_title', $row['document_title'] ?? '')) ?>">
        </div>
        <div class="col-12">
          <label class="form-label">Upload Certification File (PDF only, max 50MB) *</label>
          <input type="file" name="cert_file" class="form-control" accept=".pdf,application/pdf" required>
          <?php if ($is_edit && !empty($row['file_path']) && $row['document_type'] === 'Certification of Compliance to DepEd Order No. 54, s. 2022'): ?>
            <small>Current: <a target="_blank" href="<?= base_url($row['file_path']) ?>">view file</a></small>
          <?php endif; ?>
        </div>
        <div class="col-12">
          <label class="form-label">Remarks</label>
          <textarea name="cert_remarks" class="form-control" rows="2"><?= htmlspecialchars(set_value('cert_remarks', $row['remarks'] ?? '')) ?></textarea>
        </div>
      </div>

      <hr>
      <h5 class="mb-3">Endorsement</h5>
      <div class="row g-3 mb-4">
        <div class="col-12">
          <label class="form-label">Document Title *</label>
          <input type="text" name="endorse_title" class="form-control" required
                 value="<?= htmlspecialchars(set_value('endorse_title', '')) ?>">
        </div>
        <div class="col-12">
          <label class="form-label">Upload Endorsement File (PDF only, max 50MB) *</label>
          <input type="file" name="endorse_file" class="form-control" accept=".pdf,application/pdf" required>
          <?php if ($is_edit && !empty($row['file_path']) && $row['document_type'] === 'Endorsement'): ?>
            <small>Current: <a target="_blank" href="<?= base_url($row['file_path']) ?>">view file</a></small>
          <?php endif; ?>
        </div>
        <div class="col-12">
          <label class="form-label">Remarks</label>
          <textarea name="endorse_remarks" class="form-control" rows="2"><?= htmlspecialchars(set_value('endorse_remarks', '')) ?></textarea>
        </div>
      </div>

      <hr>
      <a href="<?= site_url('documents') ?>" class="btn btn-light">Cancel</a>
      <button type="submit" class="btn btn-primary">
        <i class="bi bi-cloud-upload"></i> Submit Both Documents
      </button>
    </form>
  </div>
</div>
