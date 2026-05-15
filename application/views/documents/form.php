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
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">School *</label>
          <select name="school_id" class="form-select" required>
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
        <div class="col-md-6">
          <label class="form-label">Document Type *</label>
          <select name="document_type" class="form-select" required>
            <?php
              $dt = set_value('document_type', $row['document_type'] ?? '');
              $types = [
                'Certification of Compliance to DepEd Order No. 54',
                'Endorsement',
                'Certification',
                'Other',
              ];
            ?>
            <option value="">— Select —</option>
            <?php foreach ($types as $t): ?>
              <option value="<?= htmlspecialchars($t) ?>" <?= $dt === $t ? 'selected':'' ?>>
                <?= htmlspecialchars($t) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-12">
          <label class="form-label">Document Title *</label>
          <input type="text" name="document_title" class="form-control" required
                 value="<?= htmlspecialchars(set_value('document_title', $row['document_title'] ?? '')) ?>">
        </div>
        <div class="col-12">
          <label class="form-label">Upload File (PDF/DOC/JPG, max 50MB)</label>
          <input type="file" name="file" class="form-control">
          <?php if ($is_edit && !empty($row['file_path'])): ?>
            <small>Current: <a target="_blank" href="<?= base_url($row['file_path']) ?>">view file</a></small>
          <?php endif; ?>
        </div>
        <div class="col-12">
          <label class="form-label">Remarks</label>
          <textarea name="remarks" class="form-control" rows="3"><?= htmlspecialchars(set_value('remarks', $row['remarks'] ?? '')) ?></textarea>
        </div>
      </div>
      <hr>
      <a href="<?= site_url('documents') ?>" class="btn btn-light">Cancel</a>
      <button type="submit" class="btn btn-primary">
        <i class="bi bi-cloud-upload"></i> Submit for Approval
      </button>
    </form>
  </div>
</div>
