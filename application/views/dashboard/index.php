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
      </div>
    </div>
  </div>
</div>

<div class="row g-4 mb-4">
  <div class="col-md-3">
    <a href="<?= site_url('documents?status=For Approval') ?>" class="text-decoration-none">
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
    </a>
  </div>
  <div class="col-md-3">
    <a href="<?= site_url('documents?status=Approved') ?>" class="text-decoration-none">
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
    </a>
  </div>
  <div class="col-md-3">
    <a href="<?= site_url('documents?status=Revised') ?>" class="text-decoration-none">
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
    </a>
  </div>
  <div class="col-md-3">
    <a href="<?= site_url('documents?status=Rejected') ?>" class="text-decoration-none">
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
    </a>
  </div>
</div>
