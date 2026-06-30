<?php $role = $_user['role']; ?>

<!-- Hero Section -->
<div class="card mb-4 border-0 overflow-hidden" style="background: linear-gradient(135deg, var(--maroon-primary) 0%, var(--maroon-dark) 100%);">
  <div class="card-body py-5 px-4 position-relative">
    <div class="row align-items-center">
      <div class="col-lg-12">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="bg-white bg-opacity-25 rounded-pill px-3 py-1">
            <span class="text-white small fw-medium" style="text-shadow: 0 1px 2px rgba(0,0,0,0.3);">
              <i class="bi bi-shield-check me-1"></i>
              <?= ucfirst($role) ?> User
            </span>
          </div>
          <div class="bg-white bg-opacity-25 rounded-pill px-3 py-1">
            <span class="text-white small fw-medium" style="text-shadow: 0 1px 2px rgba(0,0,0,0.3);">
              <i class="bi bi-calendar3 me-1"></i>
              <?= date('F d, Y') ?>
            </span>
          </div>
        </div>
        <h1 class="text-white fw-bold mb-3" style="font-size: 2.2rem; text-shadow: 0 2px 4px rgba(0,0,0,0.4);">
          Welcome back, <?= htmlspecialchars($_user['full_name']) ?>!
        </h1>
        <?php if ($role === 'division'): ?>
        <div class="d-flex gap-2 flex-wrap">
          <a href="<?= site_url('documents/create') ?>" class="btn btn-light text-primary fw-semibold px-4 py-2 rounded-pill">
            <i class="bi bi-plus-lg me-2"></i>New Document
          </a>
          <a href="<?= site_url('documents') ?>" class="btn btn-outline-light text-white fw-semibold px-4 py-2 rounded-pill">
            <i class="bi bi-folder me-2"></i>View All
          </a>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="row g-4 mb-4">
  <div class="col-md-3">
    <div class="card stat-card p-4 h-100 border-0">
      <div class="d-flex align-items-start justify-content-between">
        <div>
          <small class="text-muted text-uppercase fw-semibold tracking-wide mb-2 d-block" style="font-size: 0.75rem;">For Approval</small>
          <div class="num text-warning fw-bold" style="font-size: 2rem;"><?= (int)$counts['For Approval'] ?></div>
        </div>
        <div class="bg-warning bg-opacity-10 p-3 rounded-3">
          <i class="bi bi-clock-history text-warning fs-4"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card stat-card p-4 h-100 border-0">
      <div class="d-flex align-items-start justify-content-between">
        <div>
          <small class="text-muted text-uppercase fw-semibold tracking-wide mb-2 d-block" style="font-size: 0.75rem;">Approved</small>
          <div class="num text-success fw-bold" style="font-size: 2rem;"><?= (int)$counts['Approved'] ?></div>
        </div>
        <div class="bg-success bg-opacity-10 p-3 rounded-3">
          <i class="bi bi-check-circle text-success fs-4"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card stat-card p-4 h-100 border-0">
      <div class="d-flex align-items-start justify-content-between">
        <div>
          <small class="text-muted text-uppercase fw-semibold tracking-wide mb-2 d-block" style="font-size: 0.75rem;">For Revision</small>
          <div class="num text-primary fw-bold" style="font-size: 2rem;"><?= (int)$counts['Revised'] ?></div>
        </div>
        <div class="bg-primary bg-opacity-10 p-3 rounded-3">
          <i class="bi bi-arrow-repeat text-primary fs-4"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card stat-card p-4 h-100 border-0">
      <div class="d-flex align-items-start justify-content-between">
        <div>
          <small class="text-muted text-uppercase fw-semibold tracking-wide mb-2 d-block" style="font-size: 0.75rem;">Rejected</small>
          <div class="num text-danger fw-bold" style="font-size: 2rem;"><?= (int)$counts['Rejected'] ?></div>
        </div>
        <div class="bg-danger bg-opacity-10 p-3 rounded-3">
          <i class="bi bi-x-circle text-danger fs-4"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<?php if ($role === 'regional'): ?>
<div class="row g-4 mb-4">
  <div class="col-md-4">
    <div class="card stat-card p-4 h-100 border-0">
      <div class="d-flex align-items-start justify-content-between">
        <div>
          <small class="text-muted text-uppercase fw-semibold tracking-wide mb-2 d-block" style="font-size: 0.75rem;">Users</small>
          <div class="num fw-bold" style="font-size: 2rem; color: var(--maroon-primary);"><?= (int)$total_users ?></div>
        </div>
        <div class="bg-info bg-opacity-10 p-3 rounded-3">
          <i class="bi bi-people text-info fs-4"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card stat-card p-4 h-100 border-0">
      <div class="d-flex align-items-start justify-content-between">
        <div>
          <small class="text-muted text-uppercase fw-semibold tracking-wide mb-2 d-block" style="font-size: 0.75rem;">Divisions</small>
          <div class="num fw-bold" style="font-size: 2rem; color: var(--maroon-primary);"><?= (int)$total_divs ?></div>
        </div>
        <div class="bg-secondary bg-opacity-10 p-3 rounded-3">
          <i class="bi bi-diagram-3 text-secondary fs-4"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card stat-card p-4 h-100 border-0">
      <div class="d-flex align-items-start justify-content-between">
        <div>
          <small class="text-muted text-uppercase fw-semibold tracking-wide mb-2 d-block" style="font-size: 0.75rem;">Schools</small>
          <div class="num fw-bold" style="font-size: 2rem; color: var(--maroon-primary);"><?= (int)$total_schools ?></div>
        </div>
        <div class="bg-dark bg-opacity-10 p-3 rounded-3">
          <i class="bi bi-building text-dark fs-4"></i>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="card border-0">
  <div class="card-header bg-white border-0 py-4">
    <div class="d-flex align-items-center justify-content-between">
      <div>
        <h5 class="fw-bold mb-1"><?= $role === 'regional' ? 'Pending Approvals' : 'Recent Submissions' ?></h5>
        <small class="text-muted">Latest document submissions</small>
      </div>
      <a href="<?= site_url('documents') ?>" class="btn btn-sm btn-outline-primary rounded-pill px-4">
        View All <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table table-hover mb-0 align-middle">
      <thead>
        <tr>
          <th class="ps-4">Document</th>
          <?php if ($role === 'regional'): ?><th>Division</th><?php endif; ?>
          <th>School</th><th>Type</th><th>Status</th><th>Submitted</th><th class="text-end pe-4"></th>
        </tr>
      </thead>
      <tbody>
      <?php if (empty($recent_subs)): ?>
        <tr><td colspan="<?= $role === 'regional' ? 7 : 6 ?>" class="text-center text-muted py-5">
          <i class="bi bi-inbox fs-1 d-block mb-2"></i>
          No documents found.
        </td></tr>
      <?php else: foreach ($recent_subs as $r):
        $b = ['For Approval'=>'warning','Approved'=>'success','Rejected'=>'danger','Revised'=>'primary'];
      ?>
        <tr>
          <td class="ps-4">
            <div class="fw-semibold text-dark"><?= htmlspecialchars($r['document_title']) ?></div>
            <small class="text-muted"><?= htmlspecialchars($r['document_type']) ?></small>
          </td>
          <?php if ($role === 'regional'): ?>
            <td><span class="badge bg-light text-dark"><?= htmlspecialchars($r['division_code'] ?? $r['division_name'] ?? '—') ?></span></td>
          <?php endif; ?>
          <td><?= htmlspecialchars($r['school_name'] ?? '—') ?></td>
          <td><?= htmlspecialchars($r['school_type'] ?? '—') ?></td>
          <td><span class="badge bg-<?= $b[$r['status']] ?? 'secondary' ?> px-3 py-2 rounded-pill">
            <?= htmlspecialchars($r['status']) ?></span></td>
          <td><small class="text-muted"><?= date('M d, Y', strtotime($r['created_at'])) ?></small></td>
          <td class="text-end pe-4">
            <a class="btn btn-sm btn-light rounded-circle" style="width: 38px; height: 38px;" href="<?= site_url('documents/view/'.$r['document_id']) ?>">
              <i class="bi bi-eye"></i>
            </a>
          </td>
        </tr>
      <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
