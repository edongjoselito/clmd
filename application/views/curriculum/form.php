<div class="card">
  <div class="card-header bg-white"><strong><?= $is_edit ? 'Edit Curriculum' : 'New Curriculum' ?></strong></div>
  <div class="card-body">
    <?= validation_errors('<div class="alert alert-danger py-2">', '</div>') ?>

    <form method="post" enctype="multipart/form-data">
      <div class="row g-3">
        <div class="col-12">
          <label class="form-label">Title *</label>
          <input type="text" name="title" class="form-control" required
                 value="<?= htmlspecialchars(set_value('title', $row['title'] ?? '')) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Grade Level *</label>
          <input type="text" name="grade_level" class="form-control" required
                 placeholder="e.g. Grade 7"
                 value="<?= htmlspecialchars(set_value('grade_level', $row['grade_level'] ?? '')) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">Subject *</label>
          <input type="text" name="subject" class="form-control" required
                 value="<?= htmlspecialchars(set_value('subject', $row['subject'] ?? '')) ?>">
        </div>
        <div class="col-md-4">
          <label class="form-label">School Year</label>
          <input type="text" name="school_year" class="form-control" placeholder="2025-2026"
                 value="<?= htmlspecialchars(set_value('school_year', $row['school_year'] ?? '')) ?>">
        </div>
        <div class="col-12">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars(set_value('description', $row['description'] ?? '')) ?></textarea>
        </div>
        <div class="col-md-8">
          <label class="form-label">Attachment (PDF/DOC/PPT/XLS/ZIP, max 50MB)</label>
          <input type="file" name="file" class="form-control">
          <?php if ($is_edit && !empty($row['file_path'])): ?>
            <small>Current: <a target="_blank" href="<?= base_url($row['file_path']) ?>">view file</a></small>
          <?php endif; ?>
        </div>
        <div class="col-md-4 d-flex align-items-end">
          <div class="form-check">
            <input type="checkbox" name="is_active" id="is_active" class="form-check-input"
              <?= !$is_edit || !empty($row['is_active']) ? 'checked' : '' ?>>
            <label class="form-check-label" for="is_active">Active</label>
          </div>
        </div>
      </div>
      <hr>
      <a href="<?= site_url('curriculum') ?>" class="btn btn-light">Cancel</a>
      <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save</button>
    </form>
  </div>
</div>
