<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta http-equiv="X-Content-Type-Options" content="nosniff">
<meta http-equiv="X-Frame-Options" content="SAMEORIGIN">
<meta http-equiv="X-XSS-Protection" content="1; mode=block">
<meta http-equiv="Referrer-Policy" content="strict-origin-when-cross-origin">
<title><?= htmlspecialchars($_title) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<style>
  :root {
    --bs-body-font-family: 'Poppins', system-ui, -apple-system, sans-serif;
    --maroon-primary: #0c2461;
    --maroon-dark: #081a48;
    --maroon-light: #1e3a8a;
    --maroon-soft: #e8edf5;
    --gold-accent: #d4af37;
  }
  body { background:#f8f9fa; font-family: var(--bs-body-font-family); color:#2d3748; }
  .sidebar {
    width: 260px; min-height: 100vh; background: linear-gradient(180deg, var(--maroon-primary) 0%, var(--maroon-dark) 100%);
    color:#fff; position:fixed; top:0; left:0; box-shadow: 4px 0 12px rgba(0,0,0,.15);
  }
  .sidebar .brand { padding: 24px 20px; border-bottom: 1px solid rgba(255,255,255,.15); background: rgba(0,0,0,.1); }
  .sidebar .brand h5 { margin:0; font-weight:700; letter-spacing: 0.5px; }
  .sidebar .brand small { color:#e8d5d5; font-weight:500; }
  .sidebar a.nav-link { color:#e8d5d5; padding: 12px 20px; border-left: 4px solid transparent; transition: all 0.2s ease; border-radius: 0 8px 8px 0; margin-right: 12px; }
  .sidebar a.nav-link:hover, .sidebar a.nav-link.active {
    background: rgba(255,255,255,.12); color:#fff; border-left-color: var(--gold-accent);
  }
  .sidebar a.nav-link i { width: 24px; font-size: 1.1rem; }
  .topbar { background:#fff; border-bottom:1px solid #e2e8f0; padding: 16px 28px; box-shadow: 0 2px 4px rgba(0,0,0,.03); }
  .main { margin-left: 260px; }
  .content { padding: 28px; }
  .card { border:none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,.06); transition: transform 0.2s ease, box-shadow 0.2s ease; }
  .card:hover { transform: translateY(-2px); box-shadow: 0 4px 16px rgba(0,0,0,.1); }
  .stat-card { background: linear-gradient(135deg, #fff 0%, #fafafa 100%); border-left: 4px solid var(--maroon-primary); }
  .stat-card .num { font-size: 2rem; font-weight:700; color: var(--maroon-primary); }
  .stat-card small { font-weight:600; text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.75rem; }
  .badge-role-regional { background: linear-gradient(135deg, var(--gold-accent) 0%, #c9a227 100%); color: var(--maroon-dark); padding: 6px 14px; border-radius: 20px; font-weight:600; }
  .badge-role-division { background: linear-gradient(135deg, var(--maroon-primary) 0%, var(--maroon-dark) 100%); color:#fff; padding: 6px 14px; border-radius: 20px; font-weight:600; }
  .notif-item { padding: 14px 16px; border-bottom: 1px solid #f0f0f0; text-decoration:none; color:#333; display:block; transition: background 0.2s ease; }
  .notif-item:hover { background:var(--maroon-soft); }
  .notif-item.unread { background: linear-gradient(135deg, #fff8e7 0%, #fff3d6 100%); border-left: 3px solid var(--gold-accent); }
  .notif-item .small { color:#718096; }
  .dropdown-menu { min-width: 340px; border-radius: 12px; border:none; box-shadow: 0 8px 24px rgba(0,0,0,.12); }
  .btn-light { background:#f8f9fa; border:1px solid #e2e8f0; transition: all 0.2s ease; }
  .btn-light:hover { background:var(--maroon-soft); border-color: var(--maroon-light); }
  .table { border-radius: 8px; overflow: hidden; }
  .table thead th { background: linear-gradient(135deg, var(--maroon-primary) 0%, var(--maroon-light) 100%); color:#fff; font-weight:600; border:none; padding: 14px 16px; }
  .table tbody tr { transition: background 0.2s ease; }
  .table tbody tr:hover { background:var(--maroon-soft); }
  .select2-container--default .select2-selection--single {
    height: 38px;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 36px;
    padding-left: 12px;
    color: #2d3748;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px;
  }
  .select2-container--default .select2-results__option {
    color: #2d3748;
    padding: 8px 12px;
  }
  .select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: var(--maroon-primary);
    color: #fff;
  }
  .select2-container--default .select2-results__option[aria-selected=true] {
    background-color: var(--maroon-soft);
    color: #2d3748;
  }
</style>
</head>
<body>
<?php $role = $_user['role']; $uri = $this->uri->segment(1); ?>

<aside class="sidebar">
  <div class="brand">
    <h5>CLMD - Region XI</h5>
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
    <a class="nav-link <?= $uri === 'change_password' ? 'active':'' ?>" href="<?= site_url('change_password') ?>">
      <i class="bi bi-shield-lock"></i> Change Password
    </a>
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
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
  $(document).ready(function() {
    $('.select2').select2({
      placeholder: '— Select school —',
      allowClear: true,
      width: '100%'
    });
  });
</script>
</body>
</html>
