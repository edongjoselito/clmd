<?php
$badges = ['For Approval'=>'warning','Approved'=>'success','Rejected'=>'danger','Revised'=>'primary'];
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">Division Endorsement</h5>
  <?php if ($_user['role'] === 'division'): ?>
    <a href="<?= site_url('documents/create') ?>" class="btn btn-primary btn-sm">
      <i class="bi bi-cloud-upload"></i> Add Endorsement
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
        <?php foreach (['For Approval'=>'For Approval','Approved'=>'Approved','Rejected'=>'Rejected','Revised'=>'For Compliance'] as $value => $label): ?>
          <option value="<?= $value ?>" <?= ($filters['status'] ?? '') === $value ? 'selected':'' ?>><?= $label ?></option>
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
          <th class="ps-4">School</th>
          <th>Status</th>
          <th class="text-end pe-4">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($grouped)): ?>
          <tr><td colspan="3" class="text-center text-muted py-5">
            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
            No documents found.
          </td></tr>
        <?php else: foreach ($grouped as $school):
          $status_summary = [];
          foreach ($school['documents'] as $doc) {
            $status_summary[$doc['status']] = ($status_summary[$doc['status']] ?? 0) + 1;
          }
          $school_id = (int)$school['school_id'];
        ?>
          <tr>
            <td class="ps-4">
              <div class="fw-semibold text-dark"><?= htmlspecialchars($school['school_name']) ?></div>
              <small class="text-muted"><?= htmlspecialchars($school['school_type']) ?></small>
              <?php if ($_user['role'] === 'regional' && !empty($school['division_code'])): ?>
                <span class="badge bg-light text-dark ms-2"><?= htmlspecialchars($school['division_code']) ?></span>
              <?php endif; ?>
            </td>
            <td>
              <?php foreach ($status_summary as $status => $count): ?>
                <span class="badge bg-<?= $badges[$status] ?? 'secondary' ?> px-2 py-1 rounded-pill me-1">
                  <?= $status === 'Revised' ? 'For Compliance' : htmlspecialchars($status) ?> (<?= $count ?>)
                </span>
              <?php endforeach; ?>
            </td>
            <td class="text-end pe-4 text-nowrap">
              <?php if (!empty($ready[$school_id])): ?>
                <a class="btn btn-sm btn-outline-success rounded-pill px-3"
                   target="_blank"
                   href="<?= site_url('documents/certificate/'.$school_id) ?>"
                   title="Print Combined Certification">
                  <i class="bi bi-printer"></i> Print
                </a>
              <?php endif; ?>
              <?php if (!empty($school['documents'])): ?>
                <?php $first_doc = $school['documents'][0]; ?>
                <a class="btn btn-sm btn-light rounded-circle" style="width: 38px; height: 38px;"
                   href="<?= site_url('documents/view/'.$first_doc['document_id']) ?>" title="View Submissions">
                  <i class="bi bi-eye"></i>
                </a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
