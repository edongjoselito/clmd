<?php
$badges = ['For Approval'=>'warning','Approved'=>'success','Rejected'=>'danger','Revised'=>'primary'];
?>
<div class="card">
  <div class="card-header bg-white">
    <strong><?= htmlspecialchars($row['school_name']) ?></strong>
    <span class="badge <?= $row['school_type']==='Private'?'bg-info':'bg-secondary' ?>"><?= $row['school_type'] ?></span>
  </div>
  <div class="card-body">
    <div class="row mb-3">
      <div class="col-md-6">
        <small class="text-muted">Division</small>
        <div><?= htmlspecialchars($row['division_name'] ?? '—') ?></div>
      </div>
      <div class="col-md-6">
        <small class="text-muted">Address</small>
        <div>
          <?php
            $addr_parts = array_filter([$row['barangay'] ?? '', $row['city'] ?? '', $row['province'] ?? '']);
            echo htmlspecialchars(implode(', ', $addr_parts));
          ?>
        </div>
      </div>
    </div>

    <h6 class="fw-bold mb-3">Documents</h6>
    <div class="table-responsive">
      <table class="table table-sm table-hover">
        <thead>
          <tr>
            <th>Type</th>
            <th>Title</th>
            <th>Status</th>
            <th>Submitted</th>
            <th>Attachment</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($school_docs as $doc): ?>
            <tr>
              <td><?= htmlspecialchars($doc['document_type']) ?></td>
              <td>
                <strong><?= htmlspecialchars($doc['document_title']) ?></strong>
                <?php if (!empty($doc['review_notes'])): ?>
                  <div class="small text-danger"><?= htmlspecialchars($doc['review_notes']) ?></div>
                <?php endif; ?>
              </td>
              <td>
                <span class="badge bg-<?= $badges[$doc['status']] ?? 'secondary' ?> rounded-pill">
                  <?= $doc['status'] === 'Revised' ? 'For Compliance' : htmlspecialchars($doc['status']) ?>
                </span>
              </td>
              <td><?= date('M d, Y', strtotime($doc['created_at'])) ?></td>
              <td>
                <?php if (!empty($doc['file_path'])): ?>
                  <a target="_blank" href="<?= base_url($doc['file_path']) ?>" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-file-earmark-pdf"></i> View
                  </a>
                <?php else: ?>
                  —
                <?php endif; ?>
              </td>
              <td class="text-end">
                <?php if ($_user['role'] === 'regional' && $doc['status'] === 'For Approval'): ?>
                  <a class="btn btn-sm btn-primary" href="<?= site_url('documents/review/'.$doc['document_id']) ?>">
                    Review
                  </a>
                <?php endif; ?>
                <?php if ($_user['role'] === 'division' && $doc['status'] !== 'Approved'): ?>
                  <a class="btn btn-sm btn-outline-primary" href="<?= site_url('documents/edit/'.$doc['document_id']) ?>">
                    Edit
                  </a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="card-footer bg-white">
    <a href="<?= site_url('documents') ?>" class="btn btn-light btn-sm">Back</a>
    <?php if (!empty($pair_ready)): ?>
      <a class="btn btn-success btn-sm" target="_blank"
         href="<?= site_url('documents/certificate/'.$row['school_id']) ?>">
        <i class="bi bi-printer"></i> Print Combined Certification
      </a>
    <?php else: ?>
      <span class="text-muted small ms-2">
        <i class="bi bi-info-circle"></i>
        Print available once both documents are approved.
      </span>
    <?php endif; ?>
  </div>
</div>
