<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= htmlspecialchars($_title) ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
  body { background:#f4f6fb; font-family: 'Segoe UI', Roboto, sans-serif; }
  .sidebar {
    width: 250px; min-height: 100vh; background:#0a3d62; color:#fff; position:fixed; top:0; left:0;
  }
  .sidebar .brand { padding: 18px 16px; border-bottom: 1px solid rgba(255,255,255,.1); }
  .sidebar .brand h5 { margin:0; font-weight:700; }
  .sidebar .brand small { color:#cfd8e3; }
  .sidebar a.nav-link { color:#dbe7f3; padding: 10px 16px; border-left: 3px solid transparent; }
  .sidebar a.nav-link:hover, .sidebar a.nav-link.active {
    background: rgba(255,255,255,.08); color:#fff; border-left-color:#f5b342;
  }
  .sidebar a.nav-link i { width: 22px; }
  .topbar { background:#fff; border-bottom:1px solid #e3e7ef; padding: 12px 24px; }
  .main { margin-left: 250px; }
  .content { padding: 24px; }
  .card { border:none; box-shadow: 0 1px 3px rgba(0,0,0,.05); }
  .stat-card .num { font-size: 1.8rem; font-weight:700; }
  .badge-role-regional { background:#f5b342; color:#0a3d62; }
  .badge-role-division { background:#0a3d62; }
</style>
</head>
<body>
<?php $role = $_user['role']; $uri = $this->uri->segment(1); ?>

<aside class="sidebar">
  <div class="brand">
    <h5>CLMD - Region XI</h5>
    <small>Curriculum &amp; Learning Mgmt.</small>
  </div>
  <nav class="nav flex-column mt-2">
    <a class="nav-link <?= $uri === 'dashboard' ? 'active':'' ?>" href="<?= site_url('dashboard') ?>">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    <a class="nav-link <?= $uri === 'curriculum' ? 'active':'' ?>" href="<?= site_url('curriculum') ?>">
      <i class="bi bi-journal-bookmark"></i> Curriculum
    </a>
    <a class="nav-link <?= $uri === 'learning-materials' || $uri === 'learning_materials' ? 'active':'' ?>"
       href="<?= site_url('learning-materials') ?>">
      <i class="bi bi-collection"></i> Learning Materials
    </a>
    <?php if ($role === 'regional'): ?>
      <hr class="text-white-50 my-2">
      <a class="nav-link <?= $uri === 'divisions' ? 'active':'' ?>" href="<?= site_url('divisions') ?>">
        <i class="bi bi-building"></i> Divisions
      </a>
      <a class="nav-link <?= $uri === 'users' ? 'active':'' ?>" href="<?= site_url('users') ?>">
        <i class="bi bi-people"></i> Users
      </a>
    <?php endif; ?>
    <hr class="text-white-50 my-2">
    <a class="nav-link" href="<?= site_url('logout') ?>">
      <i class="bi bi-box-arrow-right"></i> Logout
    </a>
  </nav>
</aside>

<div class="main">
  <div class="topbar d-flex justify-content-between align-items-center">
    <div>
      <strong><?= htmlspecialchars($_title) ?></strong>
    </div>
    <div class="d-flex align-items-center gap-2">
      <span class="badge <?= $role === 'regional' ? 'badge-role-regional' : 'badge-role-division' ?>">
        <?= ucfirst($role) ?> User
      </span>
      <span class="text-muted small">
        <?= htmlspecialchars($_user['full_name']) ?>
        <?php if (!empty($_user['division_name'] ?? null)): ?>
          &middot; <?= htmlspecialchars($_user['division_name']) ?>
        <?php endif; ?>
      </span>
    </div>
  </div>

  <div class="content">
    <?php if ($msg = $this->session->flashdata('success')): ?>
      <div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <?php if ($msg = $this->session->flashdata('error')): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>
    <?= $_content ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
