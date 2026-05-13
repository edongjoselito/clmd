<div class="card">
  <div class="card-header bg-white"><strong>Review: <?= htmlspecialchars($row['title']) ?></strong></div>
  <div class="card-body">
    <dl class="row">
      <dt class="col-sm-3">Type</dt><dd class="col-sm-9"><?= htmlspecialchars($row['type']) ?></dd>
      <dt class="col-sm-3">Grade / Subject</dt>
      <dd class="col-sm-9"><?= htmlspecialchars($row['grade_level'].' / '.$row['subject']) ?></dd>
      <dt class="col-sm-3">Division</dt><dd class="col-sm-9"><?= htmlspecialchars($row['division_name'] ?? '—') ?></dd>
      <dt class="col-sm-3">File</dt>
      <dd class="col-sm-9">
        <?php if (!empty($row['file_path'])): ?>
          <a target="_blank" href="<?= base_url($row['file_path']) ?>"><i class="bi bi-file-earmark"></i> Open</a>
        <?php else: ?>—<?php endif; ?>
      </dd>
      <dt class="col-sm-3">Description</dt>
      <dd class="col-sm-9"><?= nl2br(htmlspecialchars($row['description'] ?? '—')) ?></dd>
    </dl>

    <hr>
    <form method="post">
      <div class="row g-3">
        <div class="col-md-4">
          <label class="form-label">Decision *</label>
          <select name="status" class="form-select" required>
            <option value="Approved">Approve</option>
            <option value="Revised">Return for Revision</option>
            <option value="Rejected">Reject</option>
          </select>
        </div>
        <div class="col-md-8">
          <label class="form-label">Remarks</label>
          <input type="text" name="remarks" class="form-control" placeholder="Notes for the division...">
        </div>
      </div>
      <hr>
      <a href="<?= site_url('learning-materials') ?>" class="btn btn-light">Cancel</a>
      <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle"></i> Save Review</button>
    </form>
  </div>
</div>
