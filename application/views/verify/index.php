<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Verify Certification - CLMD Region XI</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
  body { background: linear-gradient(135deg,#0a3d62 0%, #1e6091 100%); min-height: 100vh; padding: 30px 0; }
  .verify-card { max-width: 560px; margin: 0 auto; border:none; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,.2); }
  .head { background:#0a3d62; color:#fff; padding: 18px 24px; border-radius: 12px 12px 0 0; text-align:center; }
  .ok-banner { background:#198754; color:#fff; padding: 14px; text-align:center; font-weight: 600; }
  .err-banner { background:#dc3545; color:#fff; padding: 14px; text-align:center; font-weight: 600; }
</style>
</head>
<body>
<div class="card verify-card">
  <div class="head">
    <i class="bi bi-shield-check" style="font-size: 2rem;"></i>
    <h5 class="mt-1 mb-0">Document Verification</h5>
    <small>CLMD - DepEd Region XI</small>
  </div>

  <?php if (empty($row)): ?>
    <div class="err-banner"><i class="bi bi-x-circle"></i> No certification found for this control number.</div>
    <div class="card-body small">
      <div><strong>Control No.:</strong> <?= htmlspecialchars($control_no ?? '—') ?></div>
      <div class="text-muted mt-2">If you scanned this from a document, the certification is invalid or has been revoked.</div>
    </div>
  <?php elseif ($row['status'] !== 'Approved'): ?>
    <div class="err-banner"><i class="bi bi-exclamation-triangle"></i> This document is not currently approved.</div>
    <div class="card-body small">
      <div><strong>Control No.:</strong> <?= htmlspecialchars($row['control_no']) ?></div>
      <div><strong>Status:</strong> <?= htmlspecialchars($row['status']) ?></div>
    </div>
  <?php else: ?>
    <div class="ok-banner"><i class="bi bi-patch-check-fill"></i> Authentic &amp; Approved Certification</div>
    <div class="card-body">
      <dl class="row mb-0">
        <dt class="col-sm-5">Control No.</dt>
        <dd class="col-sm-7"><code><?= htmlspecialchars($row['control_no']) ?></code></dd>
        <dt class="col-sm-5">Document Title</dt>
        <dd class="col-sm-7"><?= htmlspecialchars($row['document_title']) ?></dd>
        <dt class="col-sm-5">Document Type</dt>
        <dd class="col-sm-7"><?= htmlspecialchars($row['document_type']) ?></dd>
        <dt class="col-sm-5">School</dt>
        <dd class="col-sm-7"><?= htmlspecialchars($row['school_name']) ?>
          (<?= htmlspecialchars($row['school_type']) ?>)</dd>
        <dt class="col-sm-5">Division</dt>
        <dd class="col-sm-7"><?= htmlspecialchars($row['division_name']) ?></dd>
        <dt class="col-sm-5">Approved On</dt>
        <dd class="col-sm-7"><?= date('F d, Y H:i', strtotime($row['approved_at'] ?: $row['reviewed_at'])) ?></dd>
        <dt class="col-sm-5">Issued By</dt>
        <dd class="col-sm-7"><?= htmlspecialchars($settings['chief_name'] ?? 'CLMD Chief') ?>,
          <?= htmlspecialchars($settings['chief_position'] ?? '') ?></dd>
      </dl>
    </div>
  <?php endif; ?>
  <div class="card-footer text-center small text-muted">
    &copy; <?= date('Y') ?> Department of Education - Region XI &middot; CLMD
  </div>
</div>
</body>
</html>
