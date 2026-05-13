<div class="card">
  <div class="card-header bg-white d-flex justify-content-between">
    <strong><?= htmlspecialchars($row['title']) ?></strong>
    <?php
      $badge = ['Pending'=>'warning','Approved'=>'success','Rejected'=>'danger','Revised'=>'primary'];
      $cls = $badge[$row['status']] ?? 'secondary';
    ?>
    <span class="badge bg-<?= $cls ?>"><?= htmlspecialchars($row['status']) ?></span>
  </div>
  <div class="card-body">
    <dl class="row mb-0">
      <dt class="col-sm-3">Type</dt><dd class="col-sm-9"><?= htmlspecialchars($row['type']) ?></dd>
      <dt class="col-sm-3">Grade / Subject</dt>
      <dd class="col-sm-9"><?= htmlspecialchars($row['grade_level'] . ' / ' . $row['subject']) ?></dd>
      <dt class="col-sm-3">Quarter</dt><dd class="col-sm-9"><?= htmlspecialchars($row['quarter'] ?? '—') ?></dd>
      <dt class="col-sm-3">Division</dt><dd class="col-sm-9"><?= htmlspecialchars($row['division_name'] ?? '—') ?></dd>
      <dt class="col-sm-3">Submitted by</dt>
      <dd class="col-sm-9"><?= htmlspecialchars($row['submitted_by_name'] ?? '—') ?>
          on <?= date('M d, Y H:i', strtotime($row['created_at'])) ?></dd>
      <dt class="col-sm-3">Description</dt>
      <dd class="col-sm-9"><?= nl2br(htmlspecialchars($row['description'] ?? '—')) ?></dd>
      <dt class="col-sm-3">File</dt>
      <dd class="col-sm-9">
        <?php if (!empty($row['file_path'])): ?>
          <a target="_blank" href="<?= base_url($row['file_path']) ?>"><i class="bi bi-file-earmark-arrow-down"></i> Download</a>
        <?php else: ?>—<?php endif; ?>
      </dd>
      <?php if (!empty($row['remarks'])): ?>
        <dt class="col-sm-3">Remarks</dt>
        <dd class="col-sm-9"><?= nl2br(htmlspecialchars($row['remarks'])) ?></dd>
      <?php endif; ?>
    </dl>
  </div>
  <div class="card-footer bg-white">
    <a href="<?= site_url('learning-materials') ?>" class="btn btn-light btn-sm">Back</a>
    <?php if ($_user['role'] === 'regional'): ?>
      <a href="<?= site_url('learning_materials/review/'.$row['material_id']) ?>" class="btn btn-primary btn-sm">
        <i class="bi bi-clipboard-check"></i> Review
      </a>
    <?php endif; ?>
  </div>
</div>
