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
  .notif-item { padding: 10px 14px; border-bottom: 1px solid #eef0f4; text-decoration:none; color:#222; display:block; }
  .notif-item:hover { background:#f8fafc; }
  .notif-item.unread { background: #fff8e7; }
  .notif-item .small { color:#6b7c93; }
  .dropdown-menu { min-width: 320px; }
</style>
</head>
<body>
<?php $role = $_user['role']; $uri = $this->uri->segment(1); ?>

<aside class="sidebar">
  <div class="brand">
    <h5>CLMD - Region XI</h5>
    <small>Document Submission System</small>
  </div>
  <nav class="nav flex-column mt-2">
    <a class="nav-link <?= $uri === 'dashboard' ? 'active':'' ?>" href="<?= site_url('dashboard') ?>">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    <a class="nav-link <?= $uri === 'documents' ? 'active':'' ?>" href="<?= site_url('documents') ?>">
      <i class="bi bi-file-earmark-text"></i> Documents
    </a>
    <a class="nav-link <?= $uri === 'schools' ? 'active':'' ?>" href="<?= site_url('schools') ?>">
      <i class="bi bi-building"></i> Schools
    </a>
    <a class="nav-link <?= $uri === 'notifications' ? 'active':'' ?>" href="<?= site_url('notifications') ?>">
      <i class="bi bi-bell"></i> Notifications
      <?php if ($_unread_count > 0): ?>
        <span class="badge bg-danger ms-1"><?= $_unread_count ?></span>
      <?php endif; ?>
    </a>
    <?php if ($role === 'regional'): ?>
      <hr class="text-white-50 my-2">
      <a class="nav-link <?= $uri === 'divisions' ? 'active':'' ?>" href="<?= site_url('divisions') ?>">
        <i class="bi bi-diagram-3"></i> Divisions
      </a>
      <a class="nav-link <?= $uri === 'users' ? 'active':'' ?>" href="<?= site_url('users') ?>">
        <i class="bi bi-people"></i> Users
      </a>
      <a class="nav-link <?= $uri === 'settings' ? 'active':'' ?>" href="<?= site_url('settings') ?>">
        <i class="bi bi-gear"></i> Settings
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
    <div class="d-flex align-items-center gap-3">
      <!-- Notifications -->
      <div class="dropdown">
        <button class="btn btn-light position-relative" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-bell"></i>
          <?php if ($_unread_count > 0): ?>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              <?= $_unread_count ?>
            </span>
          <?php endif; ?>
        </button>
        <div class="dropdown-menu dropdown-menu-end p-0">
          <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
            <strong>Notifications</strong>
            <a href="<?= site_url('notifications') ?>" class="small">View all</a>
          </div>
          <?php if (empty($_recent_notifs)): ?>
            <div class="p-3 text-muted small">No notifications.</div>
          <?php else: foreach ($_recent_notifs as $n): ?>
            <a class="notif-item <?= $n['is_read'] ? '' : 'unread' ?>"
               href="<?= site_url('notifications/read/'.$n['notif_id']) ?>">
              <div><strong><?= htmlspecialchars($n['title']) ?></strong></div>
              <div class="small"><?= htmlspecialchars($n['message']) ?></div>
              <div class="small text-muted"><?= date('M d, Y H:i', strtotime($n['created_at'])) ?></div>
            </a>
          <?php endforeach; endif; ?>
        </div>
      </div>

      <span class="badge <?= $role === 'regional' ? 'badge-role-regional' : 'badge-role-division' ?>">
        <?= ucfirst($role) ?> User
      </span>
      <span class="text-muted small">
        <?= htmlspecialchars($_user['full_name']) ?>
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
