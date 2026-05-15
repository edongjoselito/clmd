<?php
$badges = ['For Approval'=>'warning','Approved'=>'success','Rejected'=>'danger','Revised'=>'primary'];
?>
<div class="card">
  <div class="card-header bg-white d-flex justify-content-between">
    <strong><?= htmlspecialchars($row['document_title']) ?></strong>
    <span class="badge bg-<?= $badges[$row['status']] ?? 'secondary' ?>"><?= htmlspecialchars($row['status']) ?></span>
  </div>
  <div class="card-body">
    <dl class="row mb-0">
      <?php if (!empty($row['control_no'])): ?>
        <dt class="col-sm-3">Control No.</dt>
        <dd class="col-sm-9"><code><?= htmlspecialchars($row['control_no']) ?></code></dd>
      <?php endif; ?>
      <dt class="col-sm-3">Document Type</dt><dd class="col-sm-9"><?= htmlspecialchars($row['document_type']) ?></dd>
      <dt class="col-sm-3">School</dt>
      <dd class="col-sm-9">
        <?= htmlspecialchars($row['school_name']) ?>
        <span class="badge <?= $row['school_type']==='Private'?'bg-info':'bg-secondary' ?>"><?= $row['school_type'] ?></span>
        <div class="small text-muted"><?= htmlspecialchars(trim(($row['school_address'] ?? '').' '.($row['municipality'] ?? ''))) ?></div>
      </dd>
      <dt class="col-sm-3">Division</dt><dd class="col-sm-9"><?= htmlspecialchars($row['division_name'] ?? '—') ?></dd>
      <dt class="col-sm-3">Submitted by</dt>
      <dd class="col-sm-9"><?= htmlspecialchars($row['submitted_by_name'] ?? '—') ?>
        on <?= date('M d, Y H:i', strtotime($row['created_at'])) ?></dd>
      <?php if (!empty($row['reviewed_at'])): ?>
        <dt class="col-sm-3">Reviewed by</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($row['reviewed_by_name'] ?? '—') ?>
          on <?= date('M d, Y H:i', strtotime($row['reviewed_at'])) ?></dd>
      <?php endif; ?>
      <?php if (!empty($row['remarks'])): ?>
        <dt class="col-sm-3">Remarks</dt>
        <dd class="col-sm-9"><?= nl2br(htmlspecialchars($row['remarks'])) ?></dd>
      <?php endif; ?>
      <?php if (!empty($row['review_notes'])): ?>
        <dt class="col-sm-3">Reviewer Notes</dt>
        <dd class="col-sm-9"><?= nl2br(htmlspecialchars($row['review_notes'])) ?></dd>
      <?php endif; ?>
      <dt class="col-sm-3">File</dt>
      <dd class="col-sm-9">
        <?php if (!empty($row['file_path'])): ?>
          <a target="_blank" href="<?= base_url($row['file_path']) ?>">
            <i class="bi bi-file-earmark-arrow-down"></i> Download
          </a>
        <?php else: ?>—<?php endif; ?>
      </dd>
    </dl>
  </div>
  <div class="card-footer bg-white">
    <a href="<?= site_url('documents') ?>" class="btn btn-light btn-sm">Back</a>
    <?php if ($_user['role'] === 'regional' && $row['status'] === 'For Approval'): ?>
      <a class="btn btn-primary btn-sm" href="<?= site_url('documents/review/'.$row['document_id']) ?>">
        <i class="bi bi-clipboard-check"></i> Review
      </a>
    <?php endif; ?>
    <?php if (!empty($pair_ready)): ?>
      <a class="btn btn-success btn-sm" target="_blank"
         href="<?= site_url('documents/certificate/'.$row['school_id']) ?>">
        <i class="bi bi-printer"></i> Print Combined Certification
      </a>
    <?php elseif ($row['status'] === 'Approved'): ?>
      <span class="text-muted small ms-2">
        <i class="bi bi-info-circle"></i>
        Print available once <strong>both</strong> the Certification of Compliance to DepEd Order No. 54, s. 2022
        and the Endorsement for this school are approved.
      </span>
    <?php endif; ?>
  </div>
</div>
