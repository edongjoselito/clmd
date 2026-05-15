<?php
$badges = ['For Approval'=>'warning','Approved'=>'success','Rejected'=>'danger','Revised'=>'primary'];
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Documents</h5>
  <?php if ($_user['role'] === 'division'): ?>
    <a href="<?= site_url('documents/create') ?>" class="btn btn-primary btn-sm">
      <i class="bi bi-cloud-upload"></i> Submit Document
    </a>
  <?php endif; ?>
</div>

<form method="get" class="card card-body py-2 mb-3">
  <div class="row g-2 align-items-end">
    <div class="col-md-4">
      <label class="form-label small mb-1">Search</label>
      <input type="text" name="q" class="form-control form-control-sm"
             value="<?= htmlspecialchars($filters['search'] ?? '') ?>"
             placeholder="Title, school, control no...">
    </div>
    <div class="col-md-3">
      <label class="form-label small mb-1">Status</label>
      <select name="status" class="form-select form-select-sm">
        <option value="">All</option>
        <?php foreach (['For Approval','Approved','Rejected','Revised'] as $s): ?>
          <option value="<?= $s ?>" <?= ($filters['status'] ?? '') === $s ? 'selected':'' ?>><?= $s ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <?php if ($_user['role'] === 'regional'): ?>
      <div class="col-md-3">
        <label class="form-label small mb-1">Division</label>
        <select name="division_id" class="form-select form-select-sm">
          <option value="">All Divisions</option>
          <?php foreach ($divisions as $d): ?>
            <option value="<?= $d['division_id'] ?>"
              <?= (int)($filters['division_id'] ?? 0) === (int)$d['division_id'] ? 'selected':'' ?>>
              <?= htmlspecialchars($d['code']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    <?php endif; ?>
    <div class="col-auto">
      <button class="btn btn-sm btn-primary"><i class="bi bi-funnel"></i></button>
      <a class="btn btn-sm btn-light" href="<?= site_url('documents') ?>">Clear</a>
    </div>
  </div>
</form>

<div class="card">
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light">
        <tr>
          <th>Document</th><th>School</th>
          <?php if ($_user['role'] === 'regional'): ?><th>Division</th><?php endif; ?>
          <th>Status</th><th>Submitted</th><th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($rows)): ?>
          <tr><td colspan="6" class="text-center text-muted py-4">No documents.</td></tr>
        <?php else: foreach ($rows as $r): ?>
          <tr>
            <td>
              <strong><?= htmlspecialchars($r['document_title']) ?></strong>
              <div class="small text-muted">
                <?= htmlspecialchars($r['document_type']) ?>
                <?php if (!empty($r['control_no'])): ?>
                  &middot; <code><?= htmlspecialchars($r['control_no']) ?></code>
                <?php endif; ?>
              </div>
            </td>
            <td>
              <?= htmlspecialchars($r['school_name'] ?? '—') ?>
              <div class="small text-muted"><?= htmlspecialchars($r['school_type'] ?? '') ?></div>
            </td>
            <?php if ($_user['role'] === 'regional'): ?>
              <td><?= htmlspecialchars($r['division_code'] ?? '—') ?></td>
            <?php endif; ?>
            <td>
              <span class="badge bg-<?= $badges[$r['status']] ?? 'secondary' ?>">
                <?= htmlspecialchars($r['status']) ?>
              </span>
            </td>
            <td>
              <?= date('M d, Y', strtotime($r['created_at'])) ?>
              <div class="small text-muted">by <?= htmlspecialchars($r['submitted_by_name'] ?? '') ?></div>
            </td>
            <td class="text-end text-nowrap">
              <a class="btn btn-sm btn-outline-secondary"
                 href="<?= site_url('documents/view/'.$r['document_id']) ?>" title="View">
                <i class="bi bi-eye"></i>
              </a>
              <?php if (!empty($ready[(int)$r['school_id']])): ?>
                <a class="btn btn-sm btn-outline-success"
                   target="_blank"
                   href="<?= site_url('documents/certificate/'.$r['school_id']) ?>"
                   title="Print Combined Certification (Cert + Endorsement both Approved)">
                  <i class="bi bi-printer"></i>
                </a>
              <?php elseif ($r['status'] === 'Approved'): ?>
                <button type="button" class="btn btn-sm btn-outline-secondary"
                        disabled
                        title="Print available only when both the Certification and Endorsement for this school are Approved">
                  <i class="bi bi-printer"></i>
                </button>
              <?php endif; ?>
              <?php if ($_user['role'] === 'regional' && $r['status'] === 'For Approval'): ?>
                <a class="btn btn-sm btn-outline-primary"
                   href="<?= site_url('documents/review/'.$r['document_id']) ?>" title="Review">
                  <i class="bi bi-clipboard-check"></i>
                </a>
              <?php endif; ?>
              <?php if ($_user['role'] === 'division' && $r['status'] !== 'Approved'): ?>
                <a class="btn btn-sm btn-outline-primary"
                   href="<?= site_url('documents/edit/'.$r['document_id']) ?>" title="Edit">
                  <i class="bi bi-pencil"></i>
                </a>
                <a class="btn btn-sm btn-outline-danger"
                   onclick="return confirm('Delete this document?');"
                   href="<?= site_url('documents/delete/'.$r['document_id']) ?>" title="Delete">
                  <i class="bi bi-trash"></i>
                </a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
