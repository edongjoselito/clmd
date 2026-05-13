<div class="card">
  <div class="card-header bg-white"><strong><?= $is_edit ? 'Edit Learning Material' : 'Submit Learning Material' ?></strong></div>
  <div class="card-body">
    <?= validation_errors('<div class="alert alert-danger py-2">', '</div>') ?>

    <?php if ($is_edit && !empty($row['remarks']) && $row['status'] === 'Revised'): ?>
      <div class="alert alert-warning">
        <strong>For Revision:</strong> <?= nl2br(htmlspecialchars($row['remarks'])) ?>
      </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
      <div class="row g-3">
        <div class="col-12">
          <label class="form-label">Title *</label>
          <input type="text" name="title" class="form-control" required
                 value="<?= htmlspecialchars(set_value('title', $row['title'] ?? '')) ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Type *</label>
          <select name="type" class="form-select" required>
            <?php $t = set_value('type', $row['type'] ?? 'Module'); ?>
            <?php foreach (['Module','Lesson Plan','Worksheet','Video','Other'] as $opt): ?>
              <option value="<?= $opt ?>" <?= $t === $opt ? 'selected' : '' ?>><?= $opt ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Grade Level *</label>
          <input type="text" name="grade_level" class="form-control" required
                 value="<?= htmlspecialchars(set_value('grade_level', $row['grade_level'] ?? '')) ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Subject *</label>
          <input type="text" name="subject" class="form-control" required
                 value="<?= htmlspecialchars(set_value('subject', $row['subject'] ?? '')) ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Quarter</label>
          <select name="quarter" class="form-select">
            <option value="">—</option>
            <?php $q = set_value('quarter', $row['quarter'] ?? ''); ?>
            <?php foreach (['1','2','3','4'] as $v): ?>
              <option value="<?= $v ?>" <?= $q === $v ? 'selected':'' ?>>Q<?= $v ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-12">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars(set_value('description', $row['description'] ?? '')) ?></textarea>
        </div>
        <div class="col-12">
          <label class="form-label">Attachment (PDF/DOC/PPT/XLS/ZIP/MP4, max 100MB)</label>
          <input type="file" name="file" class="form-control">
          <?php if ($is_edit && !empty($row['file_path'])): ?>
            <small>Current: <a target="_blank" href="<?= base_url($row['file_path']) ?>">view file</a></small>
          <?php endif; ?>
        </div>
      </div>
      <hr>
      <a href="<?= site_url('learning-materials') ?>" class="btn btn-light">Cancel</a>
      <button type="submit" class="btn btn-primary"><i class="bi bi-cloud-upload"></i> Submit</button>
    </form>
  </div>
</div>
